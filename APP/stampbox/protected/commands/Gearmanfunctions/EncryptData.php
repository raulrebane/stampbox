<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function EncryptData($job, $log)
{
    include '../../config/commands.php';
    $jsonstr = $job->workload();
    $data = json_decode($jsonstr);
    if (substr($data->opentext,0,5) == 'SBPKI') {
        return json_encode(array('status'=>'OK', 'cryptedtext'=>$data->opentext));
    }
    $pubKey = openssl_pkey_get_public($publickey);
    $salt = base64_encode(mcrypt_create_iv(31, MCRYPT_DEV_URANDOM));
    //$saltedpass = $salt .$data->opentext;
    $saltedpass = $data->opentext;
    openssl_public_encrypt($saltedpass, $encryptedData, $pubKey);
    openlog("STAMPBOX", LOG_NDELAY, LOG_LOCAL0);
    syslog(LOG_ERR, "Encrypting $jsonstr =>$saltedpass ->" .base64_encode($encryptedData));
    closelog();
    return json_encode(array('status'=>'OK', 'cryptedtext'=>'SBPKI' .base64_encode($encryptedData)));
}
?>