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
            //$customer = TCustomer::model()->find('username=:1', 
            //                        array(':1'=>mb_convert_case($this->username, MB_CASE_LOWER, "UTF-8")));
            $customer = Yii::app()->db->createCommand(array('select'=> array('customer_id'),
                        'from' => 'ds.t_customer_mailbox',
                        'where'=> 'e_mail = :1',
                        'params' => array(':1'=>$attribute),))->queryRow();
                if ($customer === !NULL) {
                    $this->addError('username', 'This e-mail address is already registered');
                    echo 'jama';
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
                        'incoming_port'=>'Mail server port',
                        'e_mail_username'=>'E-Mail username',
                        'e_mail_password'=>'E-Mail password',
		);
	}



}

