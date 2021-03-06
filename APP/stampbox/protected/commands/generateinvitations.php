<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$config=dirname(__FILE__).'/../config/commands.php';
require $config;
openlog("STAMPBOX", LOG_NDELAY, LOG_LOCAL0);
$dbconn = pg_connect($dbconnectstring) or die('Query failed: ' . pg_last_error());
$customermailboxes = pg_query($dbconn, "select * from ds.t_customer_mailbox where status = 'A' and customer_id = 648;");
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
	    	$searchdate = date( "d-M-Y", strToTime ( "-1000 days" ) );
            	$emails = imap_search($inbox,"SINCE $searchdate");
                if($emails) {
		    syslog(LOG_INFO, "Customer: " .$custmailbox['customer_id'] ." - Processing " .count($emails) ." e-mails");
                    $senders = array();
                    /* for every email... */
                    foreach($emails as $email_number) {
                    /* get information specific to this email */
                        $overview = imap_fetch_overview($inbox,$email_number,0);
                        $mailfrom = imap_mime_header_decode($overview[0]->from);
                        if (count($mailfrom) == 2) {
                            $fromname = utf8_encode(rtrim($mailfrom[0]->text));
                            $fromemail = trim($mailfrom[1]->text, " <>");}
                        else {
                            if (strpos($overview[0]->from, "<")) {
                                list($fromname, $fromemail) = explode("<", $overview[0]->from);}
                        else {
                            $fromemail = $overview[0]->from;
                            $fromname = $overview[0]->from;
                        }
                        $fromemail = trim($fromemail, " <>");
                        $fromname = utf8_encode(rtrim($fromname)); }
                        if (array_key_exists($fromemail,$senders)) {
                            $senders[$fromemail]['rcount']++; 
                            if ($senders[$fromemail]['last_email_date'] < strtotime($overview[0]->date)) {
                                $senders[$fromemail]['last_email_date'] = strtotime($overview[0]->date);
                            }
                        }
                        else {
                            $senders[$fromemail]['e-mail'] = $fromemail;
                            $senders[$fromemail]['Name'] = $fromname;
                            $senders[$fromemail]['rcount'] = 1;
                            $senders[$fromemail]['last_email_date'] = strtotime($overview[0]->date);
                        }
                    }
                }
            }
            imap_close($inbox);
            if (isset($senders)) {
                //usort($senders, "self::cmp");
                $top_senders = array_values($senders);
                foreach ($top_senders as $i) {
                //$invite = pg_query($dbconn, "select * from ds.t_invitations where invited_email = '".$i['e_mail'] ."' and customer_id = ".$custmailbox['customer_id'] .";");
                //if (!$invite) {
                    $invited['customer_id'] = $custmailbox['customer_id'];
                    $invited['invited_email'] = $i['e-mail'];
                    $invited['from_count'] = $i['rcount'];
                    $invited['name'] = $i['Name'];
                    $invited['last_email_date'] = date('Y-m-d H:i:s', $i['last_email_date']);
                    $res = pg_insert($dbconn, 'ds.t_invitations', $invited);
                    
                //}
                }
            }
        }
        }
    }
pg_close($dbconn);
// close syslog
closelog();
?>