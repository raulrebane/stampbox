<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$worker= new GearmanWorker();
$worker->addServer("127.0.0.1", 4730);
$worker->addFunction("DecryptData", "DecryptData");
while ($worker->work());

function DecryptData($job)
{
    $config=dirname(__FILE__).'/../config/commands.php';
    require $config;
    openlog("STAMPBOX", LOG_NDELAY, LOG_LOCAL0);
    $jsonstr = $job->workload();
    $data = json_decode($jsonstr);
    $privKey = openssl_pkey_get_private($privatekey, $privatekeypassword);
    $cryptedtext = base64_decode(substr($data->cryptedtext, 5, strlen($data->cryptedtext)-5));
    openssl_private_decrypt($cryptedtext, $openData, $privKey);
    syslog(LOG_ERR, "Decrypted $data->cryptedtext => $openData");
    closelog();
    return json_encode(array('status'=>'OK', 'opentext'=>$openData));
}