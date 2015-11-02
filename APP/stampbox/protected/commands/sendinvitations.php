<?php
require_once '/usr/share/php/Swift/swift_required.php';

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
openlog("STAMPBOX", LOG_NDELAY, LOG_LOCAL0);
// try to acquire lock. If this fails then the previous cron script has not yet finished mailbox's processing
$fp = fopen("/var/lock/stampbox/sendinvitations.lock", "w+");
// Locked, start processing
if (flock($fp, LOCK_EX | LOCK_NB)) {
$dbconn = pg_connect("host=localhost port=6432 dbname=stampbox user=sbweb") or die('Query failed: ' . pg_last_error());
$invitations = pg_query($dbconn, "select * from ds.t_invitations where invite = 'Y' AND invited_when is NULL LIMIT 1000;");
if ($invitations) {
    $rediscache = new Redis();
    $rediscache->connect('127.0.0.1', 6379);
   //     $rediscache->set($maildomain, json_encode($mailconfig));
    while ($invitation = pg_fetch_assoc($invitations)) {
        $custmailbox = json_decode($rediscache->get('customer_id:' .$invitation['customer_id']), true);
        if (!$custmailbox) {
          $customermailboxes = pg_query($dbconn, "select * from ds.t_customer_mailbox where customer_id = " .$invitation['customer_id'] ." LIMIT 1;");
          if ($customermailboxes) {
            $custmailbox = pg_fetch_assoc($customermailboxes); 
            $rediscache->set('customer_id:' .$invitation['customer_id'], json_encode($custmailbox));
          }
        }
        $mailconf = json_decode($rediscache->get($custmailbox['maildomain']), true);
        if (!$mailconf) {
            $mailboxconfig = pg_query($dbconn, "select * from ds.t_mailbox_config where maildomain = '" .$custmailbox['maildomain'] ."';");
            if ($mailboxconfig) {
                $mailconf = pg_fetch_assoc($mailboxconfig);
                if ($mailconf['incoming_auth'] == 'USERNAME') {list($username, ) = explode("@", $custmailbox['e_mail_username']);}
                else {$username = $custmailbox['e_mail_username'];}
                $rediscache->set($custmailbox['maildomain'], json_encode($mailconf));
            }
        }
        $invitation['invited_when'] = 'now()';
        $res = pg_update($dbconn, 'ds.t_invitations', $invitation, array('customer_id' => $invitation['customer_id'], 'invited_email' =>  $invitation['invited_email']));
	$transport = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -bs');
	$mailer = Swift_Mailer::newInstance($transport);
	$message = Swift_Message::newInstance('Invitation to join Stampbox')
            ->setFrom(array($custmailbox['e_mail'] => $custmailbox['e_mail']))
            ->setTo(array($invitation['invited_email'] => $invitation['name']))
            ->setBody("Hello " .$invitation['name'] . "\r\nThe amount of emails I receive have lately been a nightmare, so I cannot guarantee that the mail "
		."you sent me will be noticed. To be able to sort out the important ones I have joined Stambox service. Please join the service from "
		."this link https://www.stampbox.email/index.php?r=signup/step1 and you will receive a free trial and ensure that your emails "
		."will always be on top of my list.\r\n\r\n"
		."Best regards,\r\n"
		.$custmailbox['e_mail']);
    	$result = $mailer->send($message);
        var_dump($result);
    }
    $rediscache->close();
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
