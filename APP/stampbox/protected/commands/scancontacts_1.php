<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

openlog("STAMPBOX", LOG_NDELAY, LOG_LOCAL0);

$dbconn = pg_connect("host=localhost dbname=ds user=ds_user password=Apua1234") or die('Query failed: ' . pg_last_error());
$customermailboxes = pg_query($dbconn, "select * from ds.t_customer_mailbox where status = 'A' and e_mail = 'stampboxdemo@yahoo.com';");
if ($customermailboxes) {
    while ($custmailbox = pg_fetch_assoc($customermailboxes)) 
        {
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
                                $maketransaction = pg_query($dbconn, "SELECT ds.fn_make_transaction('" .$fromemail ."', '" .$custmailbox['customer_id'] ."', '"
                                                    .$custmailbox['e_mail'] ."', '" .$overview[0]->subject ."', '" .$overview[0]->message_id ."', '"
                                                    .date('Y-m-d H:i:s', strtotime($overview[0]->date)) ."');");
                                $transresult = pg_fetch_result($maketransaction, 0, 0);
                                echo $transresult;
                                switch ($transresult) {
                                    case 0: // move and send invite
                                        $toemail = $toname = $custmailbox['e_mail'];
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
                                            'toname'=>$toname
                                        ));
                                        //$gmclient= new GearmanClient();
                                        //$gmclient->addServer("127.0.0.1", 4730);
                                        //$result = json_decode($gmclient->do("invitesender", $inviteparams),TRUE);                                        
                                        //imap_mail_move($inbox, $overview[0]->uid,'no-stamp-box',CP_UID);
                                        syslog(LOG_INFO, "Customer: " .$custmailbox['customer_id'] ." - moving mail: ".$overview[0]->uid);
                                        break;
                                    case 1: // booked
                                        break;
                                    case 2: // whitelisted
                                        break;
                                    case -1: //move and don't invite
                                        //imap_mail_move($inbox, $overview[0]->uid,'no-stamp-box',CP_UID);
                                        break;
                                    case -2: // no stamps left
                                        //imap_mail_move($inbox, $overview[0]->uid,'no-stamp-box',CP_UID);
                                        break;
                                }
                        }
			}
  	    	imap_expunge($inbox);
	    	imap_close($inbox);}
           }
        }
    }
//pg_free_result($mailboxconfig);
//pg_free_result($customermailboxes);
pg_close($dbconn);
// close syslog
closelog();

/* 
 //                   Yii::log("after inbox open",'info', 'application');
                    
                    }
                    imap_close($inbox);
//                   Yii::log("Before sort", 'info', 'application');
                    usort($senders, "self::cmp");
 * 
 * 
 */
?>
