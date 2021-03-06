<?php
require_once '/usr/share/php/Swift/swift_required.php';
$config=dirname(__FILE__).'/../../config/commands.php';
require $config;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function InviteSender($job, $log)
{
    $jsonstr = $job->workload();
    openlog("STAMPBOX", LOG_NDELAY, LOG_LOCAL0);
    syslog(LOG_INFO, "Got invite e-mail request with: " .$jsonstr);
    $mboxparams = json_decode($jsonstr);
    $transport = Swift_SmtpTransport::newInstance($mboxparams->outgoing_hostname, $mboxparams->outgoing_port, $mboxparams->outgoing_socket_type)
        ->setUsername($mboxparams->e_mail_username)
        ->setPassword($mboxparams->e_mail_password)
    ;
    $mailer = Swift_Mailer::newInstance($transport);
    $message = Swift_Message::newInstance('RE: ' .$mboxparams->subject)
        ->setFrom(array($mboxparams->to => $mboxparams->toname))
        ->setTo(array($mboxparams->from => $mboxparams->fromname))
        ->setBody("Hello " .$mboxparams->fromname . "\r\nThe amount of emails I receive have lately been a nightmare, so I cannot guarantee that the mail " 
            ."you sent me will be noticed. To be able to sort out the important ones I have joined Stambox service. Please join the service from "
            ."this link https://www.stampbox.email/index.php?r=register/Step1 and you will receive a free trial and ensure that your emails " 
            ."will always be on top of my list.\r\n\r\n"
            ."Best regards,\r\n"
            .$mboxparams->toname);
    $result = $mailer->send($message);
    if ($result) {
        //openlog("STAMPBOX", LOG_NDELAY, LOG_LOCAL0);
        syslog(LOG_INFO, "Successful mail delivery with: " .$jsonstr);
        closelog();
        return json_encode(array('status'=>'OK'));
    }
    else {
        //openlog("STAMPBOX", LOG_NDELAY, LOG_LOCAL0);
        syslog(LOG_ERR, "Error delivering mail with: " .$jsonstr);
        closelog();
        return json_encode(array('status'=>'ERROR', 'reason'=>imap_errors()));
    }
}

?>
