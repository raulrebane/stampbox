<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Register extends CFormModel
{
	public $useremail;
	public $emailusername;
	public $emailpassword;
        
        // mailbox config related fields
        public $maildomain;
        public $mailtype;
        public $incoming_hostname;
        public $incoming_port;
        public $incoming_socket_type;
        public $incoming_auth;
        public $outgoing_hostname;
        public $outgoing_port;
        public $outgoing_socket_type;
        public $outgoing_auth;
        public $e_mail_username;
        public $e_mail_password;
        public $registereddomain;
        public $registeredemail;
        public $top_senders;
        public $Invitations;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// required fields
			array('useremail, emailusername, emailpassword', 'required'),
                        array('useremail', 'checkregistered'),
			array('useremail', 'length', 'max'=>128),
			array('emailpassword', 'length', 'max'=>16),
                        array('useremail', 'email'),
                        array('maildomain, mailtype, incoming_hostname, incoming_port,e_mail_username,e_mail_password', 'safe'),
		);
	}
      
        public function checkregistered($attribute,$params)
        {
            $customer = Yii::app()->db->createCommand(array('select'=> array('customer_id'),
                        'from' => 'ds.v_registered_email',
                        'where'=> 'username = :1',
                        'params' => array(':1'=>mb_convert_case($this->useremail, MB_CASE_LOWER, "UTF-8")),))->queryRow();
            //Yii::log("DB query returned customer_id: " .CVarDumper::dumpAsString($customer), 'info', 'application');
            if ($customer == !FALSE) {
                $this->addError('useremail', 'This e-mail address is already registered');
                return false;
            }
         return true;
        }
	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'useremail'=>'E-mail address: ',
                        'emailpassword'=>'E-mail password: ',
                        'emailusername'=>'User name: ',
                        'maildomain'=>'E-mail provider',
                        'incoming_hostname'=>'Mail server name',
                        'incoming_port'=>'Port',
                        'incoming_socket_type'=>'Connection security',
                        'outgoing_hostname'=>'Mail server name',
                        'outgoing_port'=>'Port',
                        'outgoing_socket_type'=>'Connection security',
                        'e_mail_username'=>'E-Mail username',
                        'e_mail_password'=>'E-Mail password',
		);
	}



}

