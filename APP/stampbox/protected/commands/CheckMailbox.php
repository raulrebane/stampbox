<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$worker= new GearmanWorker();
$worker->addServer("127.0.0.1", 4730);
$worker->addFunction("checkmailbox", "checkMailbox_fn");
while ($worker->work());

function checkMailbox_fn($job)
{
  $jsonstr = $job->workload();
  $mboxparams = json_decode($jsonstr);
  if ($inbox = imap_open("{".$mboxparams->hostname .":" .$mboxparams->port ."/" .$mboxparams->socket_type ."/novalidate-cert}",
                                $mboxparams->username,$mboxparams->password)) {
        openlog("STAMPBOX", LOG_NDELAY, LOG_LOCAL0);
        syslog(LOG_ERR, "Successful mailbox open with: " .$jsonstr);
        closelog();
        return json_encode(array('status'=>'OK'));
  }
  else {
    openlog("STAMPBOX", LOG_NDELAY, LOG_LOCAL0);
    syslog(LOG_ERR, "Error loggin in with $jsonstr" .var_dump($mboxparams));
    closelog();
    return json_encode(array('status'=>'ERROR', 'reason'=>imap_errors()));
  }
}
?>
