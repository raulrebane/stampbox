<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// Connecting, selecting database
$dbconn = pg_connect("host=localhost dbname=ds user=ds_user password=Apua1234")
    or die('Could not connect: ' . pg_last_error());

//$stamps = array[];
$stamps['stamp_token'] = '';
//$stamps['stamp_id'] = 'NULL';
$stamps['batch_id'] = 1;
$stamps['issued_to'] = 1;
$stamps['status'] = 'U';
$stamps['timestamp'] = 'now()';

for ($insert_count = 1; $insert_count < 1000; $insert_count++)
{
 $stamps['stamp_token'] = str_ireplace("==", "", base64_encode(time()));
// Performing SQL insert
 $res = pg_insert($dbconn, 'ds.t_stamps_issued', $stamps);
  if ($res) {
      echo "POST data is successfully logged\n";
  } else {
      echo "User must have sent wrong inputs\n";
  }
}
// Closing connection
pg_close($dbconn);
?>
