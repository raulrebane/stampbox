<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

openlog("STAMPBOX", LOG_NDELAY, LOG_LOCAL0);

$dbconn = pg_connect("host=localhost dbname=ds user=ds_user password=Apua1234") or die('Query failed: ' . pg_last_error());
$customermailboxes = pg_query($dbconn, "select * from ds.t_customer_mailbox;");
if ($customermailboxes) {
    while ($custmailbox = pg_fetch_assoc($customermailboxes)) 
        {
        $mailboxconfig = pg_query($dbconn, "select * from ds.t_mailbox_config where maildomain = '" .$custmailbox['maildomain'] ."';");
        if ($mailboxconfig) {
            $mailconf = pg_fetch_assoc($mailboxconfig);
            if ($mailconf['incoming_auth'] == 'USERNAME') {list($username, ) = explode("@", $custmailbox['e_mail_username']);}
            else {$username = $custmailbox['e_mail_username'];}
            $inbox = imap_open("{".$mailconf['incoming_hostname'] .":" .$mailconf['incoming_port'] .$mailconf['incoming_socket_type'] ."/novalidate-cert}INBOX",
                    $username,$custmailbox['e_mail_password']);
	    if (!$inbox) {
		syslog(LOG_INFO, "Customer: " .$custmailbox['customer_id'] ." - connection failed: " .$custmailbox['e_mail'] ." with: " ."{".$mailconf['incoming_hostname'] .":" .$mailconf['incoming_port'] ."/ssl/novalidate-cert}INBOX"
                 ." username: " .$username ." pass: ". $custmailbox['e_mail_password']);
	    } 
	    else {
//            	imap_createmailbox($inbox, "{".$mailconf['incoming_hostname'] ."}STAMPBOX");
	    	$searchdate = date( "d-M-Y", strToTime ( "-1 days" ) );
            	$emails = imap_search($inbox,"SINCE $searchdate");
                	if($emails) {
//		    	syslog(LOG_INFO, "Customer: " .$custmailbox['customer_id'] ." - Processing " .count($emails) ." e-mails");
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
//                        $mailheaders = imap_fetchheader($inbox, $email_number);
                        	$foundsenderres = pg_query($dbconn, "select * from ds.t_customer_mailbox where e_mail = '".$fromemail ."';");                       
                        	if (pg_num_rows($foundsenderres) == 1) {
                            		$foundsender = pg_fetch_assoc($foundsenderres);
                            		$creditstamp['customer_id'] = $custmailbox['customer_id'];
                            		$creditstamp['transaction_code'] = 'CRED';
                            		$creditstamp['transaction_points'] = 50;
//                            $creditstamp['stamp_id'] =  ;
                            		$creditstamp['description'] = $fromname .' ' .$fromemail .' ' .$overview[0]->date .' ' . $overview[0]->subject;
                            		$creditstamp['transaction_date'] = 'NOW()';
                            		$res = pg_insert($dbconn, 'ds.t_stamps_transactions', $creditstamp);
                            		$debitstamp['customer_id'] = $foundsender['customer_id'];
                            		$debitstamp['transaction_code'] = 'DEBIT';
                            		$debitstamp['stamps'] = -1;
//                          $debitstamp['stamp_id'] =  ;
                            		$debitstamp['description'] = $custmailbox['e_mail'] .' ' .$overview[0]->date .' ' . $overview[0]->subject;
                            		$debitstamp['transaction_date'] = 'NOW()';      
                            		$res = pg_insert($dbconn, 'ds.t_stamps_transactions', $debitstamp);
			    		syslog(LOG_INFO, "Customer: " .$custmailbox['customer_id'] ." - moving mail: ".$overview[0]->uid ." from: " .$fromname ." " .$fromemail ." " .$overview[0]->date ." " . $overview[0]->subject);
			    		syslog(LOG_INFO, "Customer: " .$custmailbox['customer_id'] ." - with headers: " .imap_fetchheader($inbox, $email_number));
                            		imap_mail_move($inbox, $overview[0]->uid,'STAMPBOX',CP_UID);
                        	}
                        }
			}
  	    	imap_expunge($inbox);
	    	imap_close($inbox);
            }
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
