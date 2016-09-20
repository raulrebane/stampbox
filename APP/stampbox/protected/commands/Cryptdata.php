<?php
include '../config/commands.php';

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$worker= new GearmanWorker();
$worker->addServer("127.0.0.1", 4730);
$worker->addFunction("cryptdata", "cryptData_fn");
while ($worker->work());

function cryptData_fn($job)
{
    $jsonstr = $job->workload();
    $data = json_decode($jsonstr);
    $pubKey = openssl_pkey_get_public($publickey);
    openssl_public_encrypt($data->opentext, $encryptedData, $pubKey);
    return json_encode(array('status'=>'OK', 'cryptedtext'=>base64_encode($encryptedData)));
}