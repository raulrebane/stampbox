<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function EncryptData($job, $log)
{
    $config=dirname(__FILE__).'/../config/commands.php';
    require $config;
    $jsonstr = $job->workload();
    $data = json_decode($jsonstr);
    if (substr($data->opentext,0,5) == 'SBPKI') {
        return json_encode(array('status'=>'OK', 'cryptedtext'=>$data->opentext));
    }
    $pubKey = openssl_pkey_get_public($publickey);
    openssl_public_encrypt($data->opentext, $encryptedData, $pubKey);
    //openlog("STAMPBOX", LOG_NDELAY, LOG_LOCAL0);
    //syslog(LOG_ERR, "Encrypting $jsonstr =>$saltedpass ->" .base64_encode($encryptedData));
    //closelog();
    return json_encode(array('status'=>'OK', 'cryptedtext'=>'SBPKI' .base64_encode($encryptedData)));
}
?>