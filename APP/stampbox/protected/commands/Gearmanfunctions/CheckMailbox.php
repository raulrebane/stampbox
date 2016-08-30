<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function CheckMailbox($job, $log)
{
  $jsonstr = $job->workload();
  $mboxparams = json_decode($jsonstr);
  // No such server exists
  $mailserver = gethostbyname($mboxparams->hostname .'.');
  if ($mailserver == $mboxparams->hostname) {
      return json_encode(array('status'=>'ERROR', 'reason'=>'No such server'));
  }
  if ($mboxparams->port == '') {
      return json_encode(array('status'=>'ERROR', 'reason'=>'Port is needed'));
  }
  if ($mboxparams->socket_type == '') {
      $inbox = imap_open("{".$mboxparams->hostname .":" .$mboxparams->port ."/novalidate-cert}", $mboxparams->username,$mboxparams->password);  
  }
  else {
      $inbox = imap_open("{".$mboxparams->hostname .":" .$mboxparams->port ."/" .$mboxparams->socket_type ."/novalidate-cert}",
                                $mboxparams->username,$mboxparams->password);
  }
  if ($inbox) {
        openlog("STAMPBOX", LOG_NDELAY, LOG_LOCAL0);
        syslog(LOG_ERR, "Successful mailbox open with: " .$jsonstr);
        closelog();
        return json_encode(array('status'=>'OK'));
  }
  else {
    openlog("STAMPBOX", LOG_NDELAY, LOG_LOCAL0);
    $mail_errors = imap_errors();
    syslog(LOG_ERR, "Error loggin in with $jsonstr" .var_dump($mboxparams) .var_dump($mail_errors));
    closelog();
    return json_encode(array('status'=>'ERROR', 'reason'=>$mail_errors));
  }
}
?>
