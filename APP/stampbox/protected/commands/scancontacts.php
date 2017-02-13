<?php
require_once '/usr/share/php/Swift/swift_required.php';
$config=dirname(__FILE__).'/../config/commands.php';
require $config;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
openlog("STAMPBOX", LOG_NDELAY, LOG_LOCAL0);
// try to acquire lock. If this fails then the previous cron script has not yet finished mailbox's processing
$fp = fopen("/var/lock/stampbox/scancontacts.lock", "w+");
// Locked, start processing
if (flock($fp, LOCK_EX | LOCK_NB)) {
$dbconn = pg_connect($dbconnectstring) or die('Query failed: ' . pg_last_error());
$customermailboxes = pg_query($dbconn, "select * from ds.t_customer_mailbox where status = 'A' AND extended_service = TRUE;");
if ($customermailboxes) {
    while ($custmailbox = pg_fetch_assoc($customermailboxes)) {
        $mailboxconfig = pg_query($dbconn, "select * from ds.t_mailbox_config where maildomain = '" .$custmailbox['maildomain'] ."';");
        if ($mailboxconfig) {
            $mailconf = pg_fetch_assoc($mailboxconfig);
            if ($mailconf['incoming_auth'] == 'USERNAME') {list($username, ) = explode("@", $custmailbox['e_mail_username']);}
            else {$username = $custmailbox['e_mail_username'];}
            $inbox = imap_open("{".$mailconf['incoming_hostname'] .":" .$mailconf['incoming_port'] ."/" .$mailconf['incoming_socket_type'] ."/novalidate-cert}INBOX",
                    $username,$custmailbox['e_mail_password']);
	    if (!$inbox) {
		syslog(LOG_INFO, "Customer: " .$custmailbox['customer_id'] ." - connection failed: " .$custmailbox['e_mail'] ." with: " ."{".$mailconf['incoming_hostname'] .":" .$mailconf['incoming_port'] ."/ssl/novalidate-cert}INBOX"
                 ." username: " .$username ." pass: ". $custmailbox['e_mail_password']);
	    } 
	    else {
            	$mboxes = imap_list($inbox, "{".$mailconf['incoming_hostname'] ."}", "*");
		if (is_array($mboxes)) {
		 if (!in_array( "{".$mailconf['incoming_hostname'] ."}no-stamp-box", $mboxes)) {
			imap_createmailbox($inbox, "{".$mailconf['incoming_hostname'] ."}no-stamp-box");
		 }
		}
	    	$searchdate = date( "d-M-Y", strToTime ( "-1 days" ) );
            	$emails = imap_search($inbox,"SINCE $searchdate");
                if($emails) {
		    syslog(LOG_INFO, "Customer: " .$custmailbox['customer_id'] ." - Processing " .count($emails) ." e-mails");
                    foreach($emails as $email_number) {
                        $overview = imap_fetch_overview($inbox,$email_number,0);
                        $alreadyprocessed = pg_query($dbconn, "select * from ds.t_processed_emails where customer_id = '"
                                .$foundsender['customer_id'] ."' and email_id = '" .$overview[0]->message_id ."';");
                            if (pg_num_rows($alreadyproccessed) >= 1) { 
				syslog(LOG_INFO, "Customer: " .$custmailbox['customer_id'] ." - email " .$overview[0]->message_id ." already processed");
				continue; 
                            }
                            else {
                                $res = pg_query($dbconn, "insert into ds.t_processed_emails values (" .$custmailbox['customer_id']
                                        ."," .$custmailbox['e_mail'] ."," .$overview[0]->message_id .",now()");
                            }
                        $mailfrom = imap_mime_header_decode($overview[0]->from);
	                if (count($mailfrom) == 2) {
                            $fromname = utf8_encode(rtrim($mailfrom[0]->text));
        	            $fromemail = trim($mailfrom[1]->text, " <>");
                        }
                        else {
                            if (strpos($overview[0]->from, "<")) {
                                list($fromname, $fromemail) = explode("<", $overview[0]->from);
                            }
                            else {
                                $fromemail = $overview[0]->from;
                                $fromname = $overview[0]->from;
                            }
                            $fromemail = trim($fromemail, " <>");
                            $fromname = utf8_encode(rtrim($fromname)); 
                        }
                        $foundwhitelist = pg_query($dbconn, "select * from ds.t_whitelist where e_mail = '".$fromemail ."' and customer_id = '".$custmailbox['customer_id'] ."';");
                        if (pg_num_rows($foundwhitelist) >= 1) {
                            syslog(LOG_INFO, "Customer: " .$custmailbox['customer_id'] ." - e-mail sender " .$fromemail ." in whitelist");
                            continue;
                        }
                        $foundsenderres = pg_query($dbconn, "select * from ds.t_customer_mailbox where e_mail = '".$fromemail ."';");                       
                        if (pg_num_rows($foundsenderres) == 1) {
                            syslog(LOG_INFO, "Customer: " .$custmailbox['customer_id'] ." - found registered sender: " .$fromemail);
                            $foundsender = pg_fetch_assoc($foundsenderres);
                            $alreadystamped = pg_query($dbconn, "select * from ds.t_stamps_issued where customer_id = '"
                                .$foundsender['customer_id'] ."' and email_id = '" .$overview[0]->message_id ."';");
                            if (pg_num_rows($alreadystamped) >= 1) { 
				$stampedstamp = pg_fetch_assoc($alreadystamped);
				syslog(LOG_INFO, "Customer: " .$custmailbox['customer_id'] ." - email already processed with stampid: " .$stampedstamp['email_id']);
				continue; 
                            }
                            $transactionstampres = pg_query($dbconn, "select * from ds.t_stamps_issued where customer_id = '".$foundsender['customer_id'] 
                                ."' and status = 'A' limit 1");
                            if ($transactionstampres == FALSE AND $custmailbox['sorting_service'] == TRUE) {
                                imap_mail_move($inbox, $overview[0]->uid,'no-stamp-box',CP_UID);
                                continue;
                            }
                            $transactionstamp = pg_fetch_assoc($transactionstampres);
                            $transactionstamp['from_email'] = $fromemail;
                            $transactionstamp['to_email'] = $custmailbox['e_mail'];
                            $transactionstamp['email_id'] = $overview[0]->message_id;
                            $transactionstamp['subject'] = $overview[0]->subject;
                            $transactionstamp['status'] = 'U';
                            $res = pg_update($dbconn, 'ds.t_stamps_issued', $transactionstamp, array('stamp_id' => $transactionstamp['stamp_id']));
                            $pricedef = pg_query($dbconn, "select * from ds.t_stamp_definition where batch_id = '".$transactionstamp['batch_id'] ."';");                       
                            if ($pricedef) {
                                $transactiondef = pg_fetch_assoc($pricedef);
                                $creditprice = $transactiondef['pts_earned'];
                            } else $creditprice = 0;
                            $credittrans['customer_id'] = $custmailbox['customer_id'];
                            $credittrans['transaction_code'] = 'PCR';
                            $credittrans['amount'] = $creditprice;
                            $credittrans['stamp_id'] = $transactionstamp['stamp_id'];
                            $credittrans['description'] = NULL;
                            $credittrans['transaction_date'] = date('Y-m-d H:i:s', strtotime($overview[0]->date));
                            $res = pg_insert($dbconn, 'ds.t_stamps_transactions', $credittrans);
                            $res = pg_query($dbconn, "update ds.t_account set points_bal = points_bal + '".$creditprice ."' where customer_id = " .$custmailbox['customer_id'] .";");

                            $debitstamp['customer_id'] = $foundsender['customer_id'];
                            $debitstamp['transaction_code'] = 'SDB';
                            $debitstamp['amount'] = -1;
                            $debitstamp['stamp_id'] = $transactionstamp['stamp_id'];
                            $debitstamp['description'] = NULL;
                            $debitstamp['transaction_date'] = date('Y-m-d H:i:s', strtotime($overview[0]->date));      
                            $res = pg_insert($dbconn, 'ds.t_stamps_transactions', $debitstamp);
                            $res = pg_query($dbconn, "update ds.t_account set stamps_bal = stamps_bal-1 where customer_id = " .$foundsender['customer_id'] .";");
                        }
                        else {
                            $ignoreemails = pg_query($dbconn, "select * from ds.t_ignored_emailaddresses where e_mail = '".$fromemail .";");
                            if (pg_num_rows($ignoreemails) >= 1) {
                                syslog(LOG_INFO, "e-mail sender " .$fromemail ." is ignored");
                                continue;
                            }
                            $toemail = $toname = $custmailbox['e_mail'];
			    $transport = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -bs');
			    $mailer = Swift_Mailer::newInstance($transport);
			    $message = Swift_Message::newInstance('RE: ' .$overview[0]->subject)
				->setFrom(array($custmailbox['e_mail'] => $custmailbox['e_mail']))
				->setTo(array($fromemail => $fromname))
				->setBody("Hello " .$fromname . "\r\nThe amount of emails I receive have lately been a nightmare, so I cannot guarantee that the mail "
					."you sent me will be noticed. To be able to sort out the important ones I have joined Stambox service. Please join the service from "
					."this link https://www.stampbox.email/index.php?r=signup/step1 and you will receive a free trial and ensure that your emails "
					."will always be on top of my list.\r\n\r\n"
					."Best regards,\r\n"
					.$custmailbox['e_mail']);
			    $result = $mailer->send($message);
                            syslog(LOG_INFO, "Sending invitation: " .$custmailbox['customer_id'] ." : ".$overview[0]->uid
                                    ." From: " .$custmailbox['e_mail'] . "To: $fromname $fromemail");
/*
                                $inviteparams = json_encode(array(
                                'outgoing_hostname'=>$mailconf['outgoing_hostname'],
                                'outgoing_port'=>$mailconf['outgoing_port'],
                                'outgoing_socket_type'=>$mailconf['outgoing_socket_type'],
                                'e_mail_username'=>$custmailbox['e_mail_username'],
                                'e_mail_password'=>$custmailbox['e_mail_password'],
                                'subject'=>$overview[0]->subject,
                                'from'=>$fromemail,
                                'fromname'=>$fromname,
                                'to'=>$toemail,
                                'toname'=>$toname));
                            $gmclient= new GearmanClient();
                            $gmclient->addServer("127.0.0.1", 4730);
                            $result = json_decode($gmclient->doNormal("invitesender", $inviteparams),TRUE);
*/
                            if ($custmailbox['sorting_service'] == TRUE) {
                                imap_mail_move($inbox, $overview[0]->uid,'no-stamp-box',CP_UID);
                                syslog(LOG_INFO, "Customer: " .$custmailbox['customer_id'] ." - moving mail: ".$overview[0]->uid);
                            }
                        }
                    }
		}
  	    	imap_expunge($inbox);
	    	imap_close($inbox);}
        }
    }
}
pg_close($dbconn);
}
else {
    syslog(LOG_INFO, 'Could not aquire scancontacts.lock');
}
flock($fp, LOCK_UN);
fclose($fp);
// close syslog
closelog();

?>
