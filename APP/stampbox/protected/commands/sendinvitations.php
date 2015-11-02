<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

openlog("STAMPBOX", LOG_NDELAY, LOG_LOCAL0);
// try to acquire lock. If this fails then the previous cron script has not yet finished mailbox's processing
$fp = fopen("/var/run/stampbox/sendinvitations.lock", "w+");
// Locked, start processing
if (flock($fp, LOCK_EX | LOCK_NB)) {
$dbconn = pg_connect("host=localhost port=6432 dbname=stampbox user=sbweb") or die('Query failed: ' . pg_last_error());
$invitations = pg_query($dbconn, "select * from ds.t_invitations where invite = 'Y' AND invited_when = NULL LIMIT 1000;");
if ($invitations) {
    while ($invitation = pg_fetch_assoc($invitations)) {
        $customermailboxes = pg_query($dbconn, "select * from ds.t_customer_mailbox where customer_id = " .$invitation['customer_id'] ." LIMIT 1;");
        if ($customermailboxes) {
            $custmailbox = pg_fetch_assoc($customermailboxes); 
            $mailboxconfig = pg_query($dbconn, "select * from ds.t_mailbox_config where maildomain = '" .$custmailbox['maildomain'] ."';");
            if ($mailboxconfig) {
                $mailconf = pg_fetch_assoc($mailboxconfig);
                if ($mailconf['incoming_auth'] == 'USERNAME') {list($username, ) = explode("@", $custmailbox['e_mail_username']);}
                else {$username = $custmailbox['e_mail_username'];}
                
                $res = pg_update($dbconn, 'ds.t_stamps_issued', $transactionstamp, array('stamp_id' => $transactionstamp['stamp_id']));
                            syslog(LOG_INFO, "Customer: " .$custmailbox['customer_id'] ." - moving mail: ".$overview[0]->uid);
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
                                'toname'=>$toname));
                            $gmclient= new GearmanClient();
                            $gmclient->addServer("127.0.0.1", 4730);
                            $result = json_decode($gmclient->do("invitesender", $inviteparams),TRUE);
                        }
                    }
		}
        }
    }
}
pg_close($dbconn);
}
else {
    syslog(LOG_INFO, 'Could not aquire sendinvitations.lock');
}
flock($fp, LOCK_UN);
fclose($fp);
// close syslog
closelog();

?>
