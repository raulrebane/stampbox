<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

openlog("STAMPBOX", LOG_NDELAY, LOG_LOCAL0);
$dbconn = pg_connect("host=localhost dbname=ds user=ds_user password=Apua1234") or die('Query failed: ' . pg_last_error());
$customermailboxes = pg_query($dbconn, "select * from ds.t_customer_mailbox where status = 'A';");
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
	    if ($inbox) 
	    {
	    	$searchdate = date( "d-M-Y", strToTime ( "-365 days" ) );
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
                                if ($fromemail == $custmailbox['e_mail']) { continue; }
//                        $mailheaders = imap_fetchheader($inbox, $email_number);
                        	$foundsenderres = pg_query($dbconn, "select * from ds.t_customer_mailbox where e_mail = '".$fromemail ."';");                       
                        	if (pg_num_rows($foundsenderres) == 1) {
                            		$foundsender = pg_fetch_assoc($foundsenderres);
                                        $alreadystamped = pg_query($dbconn, "select * from ds.t_stamps_issued where customer_id = '"
                                        .$foundsender['customer_id'] ."' and email_id = '" .$overview[0]->message_id ."';");
                                        if (pg_num_rows($alreadystamped) >= 1) { continue; }
                                        $transactionstampres = pg_query($dbconn, "select * from ds.t_stamps_issued where customer_id = '".$foundsender['customer_id'] 
                                                ."' and status = 'A' limit 1");
                                        
                                        $transactionstamp = pg_fetch_assoc($transactionstampres);
                                        $transactionstamp['from_email'] = $fromemail;
                                        $transactionstamp['to_email'] = $custmailbox['e_mail'];
                                        $transactionstamp['email_id'] = $overview[0]->message_id;
                                        $transactionstamp['subject'] = $overview[0]->subject;
                                        $transactionstamp['status'] = 'U';
                                        $res = pg_update($dbconn, 'ds.t_stamps_issued', $transactionstamp, array('stamp_id' => $transactionstamp['stamp_id']));
                                        
                            		$credittrans['customer_id'] = $custmailbox['customer_id'];
                            		$credittrans['transaction_code'] = 'CRED';
                            		$credittrans['amount'] = 90;
                                        $credittrans['stamp_id'] = $transactionstamp['stamp_id'];
                            		$credittrans['description'] = NULL;
                            		$credittrans['transaction_date'] = date('Y-m-d H:i:s', strtotime($overview[0]->date));
                            		$res = pg_insert($dbconn, 'ds.t_stamps_transactions', $credittrans);
                                        $res = pg_query($dbconn, "update ds.t_account set points_bal = points_bal + 90 where customer_id = " .$custmailbox['customer_id'] .";");
                                        
                            		$debitstamp['customer_id'] = $foundsender['customer_id'];
                            		$debitstamp['transaction_code'] = 'DEBIT';
                            		$debitstamp['amount'] = -1;
                                        $debitstamp['stamp_id'] = $transactionstamp['stamp_id'];
                            		$debitstamp['description'] = NULL;
                            		$debitstamp['transaction_date'] = date('Y-m-d H:i:s', strtotime($overview[0]->date));      
                            		$res = pg_insert($dbconn, 'ds.t_stamps_transactions', $debitstamp);
                                        $res = pg_query($dbconn, "update ds.t_account set stamps_bal = stamps_bal-1 where customer_id = " .$foundsender['customer_id'] .";");
                                        
			    		//syslog(LOG_INFO, "Customer: " .$custmailbox['customer_id'] ." - moving mail: ".$overview[0]->uid ." from: " .$fromname ." " .$fromemail ." " .$overview[0]->date ." " . $overview[0]->subject);
			    		//syslog(LOG_INFO, "Customer: " .$custmailbox['customer_id'] ." - with headers: " .imap_fetchheader($inbox, $email_number));
                            		//imap_mail_move($inbox, $overview[0]->uid,'STAMPBOX',CP_UID);
                        	}
                                //syslog(LOG_INFO, "Customer: " .$custmailbox['customer_id'] ." - moving mail: ".$overview[0]->uid ." from: " .$fromname ." " .$fromemail ." " .$overview[0]->date ." " . $overview[0]->subject);
                                //imap_mail_move($inbox, $overview[0]->uid, 'no-',CP_UID);
                        }
			}
  	    	//imap_expunge($inbox);
	    	imap_close($inbox);}
            else { echo "{".$mailconf['incoming_hostname'] .":" .$mailconf['incoming_port'] ."/" .$mailconf['incoming_socket_type'] 
            ."/novalidate-cert}INBOX - user: " .$username ." " .$custmailbox['e_mail_password'];}
            }
        }
    }
//pg_free_result($mailboxconfig);
//pg_free_result($customermailboxes);
pg_close($dbconn);
// close syslog
closelog();

?>

