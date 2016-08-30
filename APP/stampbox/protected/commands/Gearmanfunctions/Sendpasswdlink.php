<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function Sendpasswdlink($job, $log)
    {
    $jsonstr = $job->workload();
    openlog("STAMPBOX", LOG_NDELAY, LOG_LOCAL0);
    syslog(LOG_INFO, "Got password reset request: " .$jsonstr);
    $mboxparams = json_decode($jsonstr);
    $transport = Swift_SmtpTransport::newInstance($mboxparams->outgoing_hostname, $mboxparams->outgoing_port, $mboxparams->outgoing_socket_type)
        ->setUsername($mboxparams->e_mail_username)
        ->setPassword($mboxparams->e_mail_password)
    ;
    $mailer = Swift_Mailer::newInstance($transport);
    $message = Swift_Message::newInstance('RE: ' .$mboxparams->subject)
        ->setFrom(array($mboxparams->from => $mboxparams->fromname))
        ->setTo(array($mboxparams->to => $mboxparams->toname))
        ->setBody($mboxparams->body);
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