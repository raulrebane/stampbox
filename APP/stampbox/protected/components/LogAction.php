<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class LogAction extends CComponent {
    
    private $log_ip;
    private $log_session;
    private $log_customer_id;
    private $log_path;
    
    
    
    public function WriteLog($log_data) {
        $headers = apache_request_headers();
        if ( array_key_exists( 'X-Forwarded-For', $headers )) {
            $log_ip = $headers['X-Forwarded-For']; }
        else {
            $log_ip = $_SERVER['REMOTE_ADDR']; 
        }
        
        $logcommand = Yii::app()->db->createCommand();
        $logcommand->insert('ds.t_log_line', 
            array(
                'log_datetime'=>'=now()',
                'log_ip'=>$log_ip,
                'log_session' => Yii::app()->session->sessionID,
                'log_customer_id' => Yii::app()->user->getId(),
                'log_path' => $_SERVER['QUERY_STRING'],
                'log_data' => preg_replace('/[\r\n|\r|\n]+|\s+/', "", $log_data)));        
    }
}