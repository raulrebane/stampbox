<?php
require_once '/usr/share/php/Swift/swift_required.php';

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    $test = json_encode(array('outgoing_hostname'=>'smtp.googlemail.com', 'outgoing_port'=>'465', 'e_mail_username'=>'raulrebane71@gmail.com', 
        'e_mail_password'=>'Wfd9epa4', 'subject'=>'join Stampbox', 'from'=>'raulrebane71@gmail.com', 'fromname'=>'Raul',
        'to'=>'raul.rebane@outlook.com', 'toname'=>'Raul'));
    $mboxparams = json_decode($test);
    $transport = Swift_SmtpTransport::newInstance($mboxparams->outgoing_hostname, $mboxparams->outgoing_port, 'ssl')
        ->setUsername($mboxparams->e_mail_username)
        ->setPassword($mboxparams->e_mail_password)
    ;
    $mailer = Swift_Mailer::newInstance($transport);
    $message = Swift_Message::newInstance('RE: ' .$mboxparams->subject)
        ->setFrom(array($mboxparams->from => $mboxparams->fromname))
        ->setTo(array($mboxparams->to => $mboxparams->toname))
        ->setBody("Hello " .$mboxparams->toname . "\r\nThe amount of emails I receive have lately been a nightmare, so I cannot guarantee that the mail " 
            ."you sent me will be noticed. To be able to sort out the important ones I have joined Stambox service. Please join the service from "
            ."this link http://dsdev.dnsdynamic.com/stampbox/index.php?r=register/Step1 and you will receive a free trial and ensure that your emails " 
            ."will always be on top of my list.\r\n"
            ."Best regards,\r\n"
            .$mboxparams->fromname);
    $result = $mailer->send($message);
    
    ?>