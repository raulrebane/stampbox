<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Joinstampbox extends CFormModel
{
	public $useremail;
        
        public $userpassword;
        public $userpassrepeat;
        
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
        public $registereddomain;
        public $registeredemail;
        public $top_senders;
        public $Invitations;
        public $agreewithterms;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// required fields
			array('useremail, userpassword, userpassrepeat', 'required', 'on'=>'Step1'),
                        array('useremail', 'checkregistered', 'on'=>'Step1'),
			array('useremail', 'length', 'max'=>128, 'on'=>'Step1'),
			array('userpassword, userpassrepeat', 'length', 'max'=>20, 'on'=>'Step1'),
                        array('useremail', 'email', 'on'=>'Step1'),
                        array('agreewithterms', 'compare', 'compareValue'=>'1', 'message'=>'You have to agree with Terms and Conditions'),
                        array('incoming_hostname, outgoing_hostname, incoming_port, outgoing_port, incoming_socket_type, outgoing_socket_type', 'required', 'on'=>'Step2'),
                        array('incoming_hostname, outgoing_hostname', 'length', 'max'=>'255', 'on'=>'Step2'),
                        array('incoming_port, outgoing_port', 'numerical', 'integerOnly'=>true, 'on'=>'Step2'),
                        array('incoming_socket_type, outgoing_socket_type', 'in','range'=>array('NULL', 'ssl', 'tls'), 'allowEmpty'=>false, 'on'=>'Step2'),
                        array('emailusername, maildomain, incoming_auth, outgoing_auth', 'safe'),
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
                        'emailpassword'=>'E-mail password',
                        'emailusername'=>'E-Mail username',
                        'maildomain'=>'E-mail provider',
                        'incoming_hostname'=>'Incoming mail server',
                        'incoming_port'=>'Port',
                        'incoming_socket_type'=>'Connection security',
                        'outgoing_hostname'=>'Outgoing mail server',
                        'outgoing_port'=>'Port',
                        'outgoing_socket_type'=>'Connection security',
                        'agreewithterms'=>'I agree to the <a href="' .Yii::app()->createUrl('site/terms') .'">Terms of Service</a> which form an integral part of the agreement',
                        'userpassword'=>'password',
                        'userpassrepeat'=>'Repeat password'
		);
	}



}

