<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function InitMailbox($job, $log)
{
  $jsonstr = $job->workload();
  $log = $jsonstr;
  $mboxparams = json_decode($jsonstr);
  // No such server exists
  $config=dirname(__FILE__).'/../../config/commands.php';
  require $config;
  if (substr($mboxparams->password,0,5) == 'SBPKI') {
    $privKey = openssl_pkey_get_private($privatekey, $privatekeypassword);
    $cryptedtext = base64_decode(substr($data->cryptedtext, 5, strlen($data->cryptedtext)-5));
    openssl_private_decrypt($cryptedtext, $mboxparams->password, $privKey);
  }
  $mailserver = gethostbyname($mboxparams->hostname .'.');
  if ($mailserver == $mboxparams->hostname) {
      $log = 'ERROR: No such server $mboxparams->hostname';
      return json_encode(array('status'=>'ERROR', 'reason'=>'No such server'));
  }
  if ($mboxparams->port == '') {
      return json_encode(array('status'=>'ERROR', 'reason'=>'Port is needed'));
  }
  if ($mboxparams->socket_type == '' OR $mboxparams->socket_type == 'None') {
      $inbox = imap_open("{".$mboxparams->hostname .":" .$mboxparams->port ."/novalidate-cert}", $mboxparams->username,$mboxparams->password);  
  }
  else {
      $inbox = imap_open("{".$mboxparams->hostname .":" .$mboxparams->port ."/" .$mboxparams->socket_type ."/novalidate-cert}",
                                $mboxparams->username,$mboxparams->password);
  }
  if ($inbox) {
        openlog("STAMPBOX", LOG_NDELAY, LOG_LOCAL0);
        syslog(LOG_ERR, "Successful mailbox open with: " .$jsonstr);
        $searchdate = date( "d-M-Y", strToTime ( "-1 days" ) );
        $emails = imap_search($inbox,"SINCE $searchdate");
        if($emails) {
	    syslog(LOG_INFO, "Customer: " .$custmailbox['customer_id'] ." - Processing " .count($emails) ." e-mails");
            foreach($emails as $email_number) {
                $overview = imap_fetch_overview($inbox,$email_number,0);
                $alreadyprocessed = pg_query($dbconn, "select * from ds.t_processed_emails where customer_id = "
                    .$custmailbox['customer_id'] ." and email_id = '" .$overview[0]->message_id ."';");
                if (pg_num_rows($alreadyprocessed) >= 1) { 
                    syslog(LOG_INFO, "Customer: " .$custmailbox['customer_id'] ." - email " .$overview[0]->message_id ." already processed");
                    continue; 
                }
                else {
                    $res = pg_query($dbconn, "insert into ds.t_processed_emails values (" .$custmailbox['customer_id']
                        .",'" .$custmailbox['e_mail'] ."','" .$overview[0]->message_id ."',now());");
                }
            }
        }
        closelog();
        return json_encode(array('status'=>'OK'));
  }
  else {
    openlog("STAMPBOX", LOG_NDELAY, LOG_LOCAL0);
    $mail_errors = imap_errors();
    $log = $mail_errors;
    syslog(LOG_ERR, "Error loggin in with $jsonstr" .json_encode($mail_errors));
    closelog();
    return json_encode(array('status'=>'ERROR', 'reason'=>'Cannot log in into mailbox. Please check username and/or password'));
  }
}
?>

