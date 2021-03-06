<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$worker= new GearmanWorker();
$worker->addServer("127.0.0.1", 4730);
$worker->addFunction("loadinvitations", "loadInvitations_fn");
while ($worker->work());

function loadInvitations_fn($job)
{
    $jsonstr = $job->workload();
    $mboxparams = json_decode($jsonstr);
    openlog("STAMPBOX", LOG_NDELAY, LOG_LOCAL0);
    syslog(LOG_INFO, "Starting invitation load: " .$jsonstr);
    $dbconn = pg_connect("host=localhost port=6432 dbname=stampbox user=sbweb") or die('Query failed: ' . pg_last_error());
    $customermailboxes = pg_query($dbconn, "select * from ds.t_customer_mailbox where status = 'A' and customer_id = " 
            .$mboxparams->customer_id ." and e_mail='" .$mboxparams->e_mail ."';");
    if (pg_num_rows($customermailboxes) > 0) {
    while ($custmailbox = pg_fetch_assoc($customermailboxes)) 
        {
        $mailboxconfig = pg_query($dbconn, "select * from ds.t_mailbox_config where maildomain = '" .$custmailbox['maildomain'] ."';");
        if (pg_num_rows($mailboxconfig) > 0) {
            $mailconf = pg_fetch_assoc($mailboxconfig);
            if ($mailconf['incoming_auth'] == 'USERNAME') {list($username, ) = explode("@", $custmailbox['e_mail_username']);}
            else {$username = $custmailbox['e_mail_username'];}
            $inbox = imap_open("{".$mailconf['incoming_hostname'] .":" .$mailconf['incoming_port'] ."/" .$mailconf['incoming_socket_type'] ."/novalidate-cert}",
                    $username,$custmailbox['e_mail_password']);
	    if ($inbox) {
                $searchdate = date( "d-M-Y", strToTime ( "-1000 days" ) );
            	$emails = imap_search($inbox,"SINCE $searchdate");
                if($emails) {
		    syslog(LOG_INFO, "Customer: " .$custmailbox['customer_id'] ." - Processing " .count($emails) ." e-mails");
                    $senders = array();
                    /* for every email... */
                    $percent_done = 0;
                    $total_percent = count($emails);
                    if ($total_percent < 100) { $reportinterval = 1;}
                    else { $reportinterval = round($total_percent / 100, 0);}
                    $job->sendstatus($percent_done, $total_percent);
                    foreach($emails as $email_number) {
                    /* get information specific to this email */
                        ++$percent_done;
                        if ($percent_done % $reportinterval == 0) {
                            $job->sendstatus(round(($percent_done / $total_percent)*100, 0), $total_percent);
                        }
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
                            $fromname = utf8_encode(rtrim($fromname)); 
                        }
                        if ($fromemail == $mboxparams->e_mail) {
                            $direction = 'OUT';
                            $mailfrom = imap_mime_header_decode($overview[0]->to);
                            if (count($mailfrom) == 2) {
                                $fromname = utf8_encode(rtrim($mailfrom[0]->text));
                                $fromemail = trim($mailfrom[1]->text, " <>");}
                            else {
                                if (strpos($overview[0]->from, "<")) {
                                    list($fromname, $fromemail) = explode("<", $overview[0]->from);}
                                else {
                                    $fromemail = $overview[0]->to;
                                    $fromname = $overview[0]->to;
                                }
                                $fromemail = trim($fromemail, " <>");
                                $fromname = utf8_encode(rtrim($fromname)); 
                            }
                        }
                        else { $direction = 'IN';}
                        if (array_key_exists($fromemail,$senders)) {
                            if ($direction == 'IN') {
                                $senders[$fromemail]['rcount']++; 
                            }
                            else {
                                $senders[$fromemail]['tcount']++; 
                            }
                            if ($senders[$fromemail]['last_email_date'] < strtotime($overview[0]->date)) {
                                $senders[$fromemail]['last_email_date'] = strtotime($overview[0]->date);
                            }
                        }
                        else {
                            $senders[$fromemail]['e-mail'] = $fromemail;
                            $senders[$fromemail]['Name'] = $fromname;
                            if ($direction == 'IN') {
                                $senders[$fromemail]['rcount'] = 1; 
                                $senders[$fromemail]['tcount'] = 0; 
                            }
                            else {
                                $senders[$fromemail]['rcount'] = 0;
                                $senders[$fromemail]['tcount'] = 1; 
                            }
                            $senders[$fromemail]['last_email_date'] = strtotime($overview[0]->date);
                        }
                    }
                }
            }
            else {
                syslog(LOG_INFO, "Couldn't open mailbox");
            }
            imap_close($inbox);
            if (isset($senders)) {
                //usort($senders, "self::cmp");
                $top_senders = array_values($senders);
                foreach ($top_senders as $i) {
                $invite = pg_query($dbconn, "select * from ds.t_invitations where invited_email = '".$i['e-mail'] ."' and customer_id = ".$custmailbox['customer_id'] .";");
                if (pg_num_rows($invite) == 0) {
                    $invited['customer_id'] = $custmailbox['customer_id'];
                    $invited['invited_email'] = $i['e-mail'];
                    $invited['from_count'] = $i['rcount'];
                    $invited['to_count'] = $i['tcount'];
                    $invited['name'] = $i['Name'];
                    $invited['last_email_date'] = date('Y-m-d H:i:s', $i['last_email_date']);
                    $res = pg_insert($dbconn, 'ds.t_invitations', $invited);
                }
                else {
                    $invited['customer_id'] = $custmailbox['customer_id'];
                    $invited['invited_email'] = $i['e-mail'];
                    $invited['from_count'] = $i['rcount'];
                    $invited['to_count'] = $i['tcount'];
                    $invited['name'] = $i['Name'];
                    $invited['last_email_date'] = date('Y-m-d H:i:s', $i['last_email_date']);
                    $res = pg_update($dbconn, 'ds.t_invitations', $invited, array('customer_id'=>$custmailbox['customer_id'], 
                                'invited_email'=>$i['e-mail']));
                }
                }
            }
        }
        }
    }
pg_close($dbconn);
// close syslog
closelog();
return json_encode(array('status'=>'OK'));
}
?>