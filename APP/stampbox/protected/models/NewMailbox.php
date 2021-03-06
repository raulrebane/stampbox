<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class NewMailbox extends CFormModel
{
        public $useremail;
        public $emailusername;
        public $emailpassword;
        public $maildomain;
        public $mailtype;
        public $incoming_hostname;
        public $incoming_port;
        public $incoming_socket_type;
        public $incoming_auth;
        public $extendedservice;
        public $registereddomain;
        public $registeredemail;
        
        public $e_mail_verified;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// required fields
			array('useremail', 'required'),
			array('useremail', 'length', 'max'=>100),
                        array('useremail', 'email'),
                        array('useremail', 'checkRegistered', 'on'=>'Create'),
                        array('emailpassword', 'length', 'max'=>'500'),
                        array('incoming_hostname', 'length', 'max'=>'255'),
                        array('incoming_port', 'numerical', 'integerOnly'=>true),
                        array('incoming_socket_type', 'filter', 'filter'=>'strtolower'),
                        array('incoming_socket_type', 'in','range'=>array('NULL', 'ssl', 'tls'), 'allowEmpty'=>false),
                        array('emailusername, maildomain, incoming_auth', 'safe'),
                        array('incoming_hostname, incoming_port, incoming_socket_type, emailpassword', 'checkFields'),
                        array('extendedservice', 'in', 'range'=>array('0','1'))
		);
	}
      
        public function checkRegistered($attribute,$params)
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
        
        public function checkFields($attribute,$params)
        {
            if ($this->extendedservice == 1) {
                $ev = CValidator::createValidator('required', $this, $attribute, $params);
                $ev->validate($this);
            }
        }
	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'useremail'=>'E-mail: ',
                        'emailpassword'=>'E-mail password',
                        'emailusername'=>'IMAP login name',
                        'maildomain'=>'E-mail provider',
                        'incoming_hostname'=>'Mail server name',
                        'incoming_port'=>'Port',
                        'incoming_socket_type'=>'Connection security',
                        'extendedservice'=>'Enable Extended service',
		);
	}

        public function Save($step)
        {
            switch ($step) {
                case 'Create':
                // Save customer e-mail
                    list(, $this->maildomain) = explode("@", $this->useremail);
                    $this->registeredemail = new usermailbox();
                    $this->registeredemail->customer_id = Yii::app()->user->getId();
                    $this->registeredemail->e_mail = mb_convert_case($this->useremail, MB_CASE_LOWER, "UTF-8");
                    $this->registeredemail->e_mail_username = $this->emailusername;
                    $this->registeredemail->e_mail_password = $this->emailpassword;
                    $this->registeredemail->maildomain = mb_convert_case($this->maildomain, MB_CASE_LOWER, "UTF-8");
                    $this->registeredemail->extended_service = ($this->extendedservice == 1) ? TRUE : FALSE;
                    if ($this->e_mail_verified) {
                        $this->registeredemail->status = 'A'; }
                    else {
                        $this->registeredemail->status = 'V'; }
                    if (!$this->registeredemail->save()) {
                        Yii::log('In step1 - customer mailbox save failed ' .CVarDumper::dumpAsString($this->registeredemail)
                                .$this->registeredemail->getErrors(), 'info', 'application');
                    }
                    $this->registereddomain = mailconfig::model()->find('maildomain=:1', array(':1'=>$this->maildomain));
                    if ($this->registereddomain == NULL) {
                        $this->registereddomain = new mailconfig();
                        $this->registereddomain->maildomain = $this->maildomain;
                        $this->registereddomain->mailtype = 'IMAP';
                        $this->registereddomain->incoming_hostname = mb_convert_case($this->incoming_hostname, MB_CASE_LOWER, "UTF-8");
                        $this->registereddomain->incoming_port = $this->incoming_port;
                        $this->registereddomain->incoming_socket_type = $this->incoming_socket_type;
                        if ($this->registeredemail->e_mail == $this->registeredemail->e_mail_username) {
                                $this->registereddomain->incoming_auth = 'EMAIL';
                        }
                        else {
                                $this->registereddomain->incoming_auth = 'OTHER';
                        }
                        if ($this->e_mail_verified) {
                            $this->registereddomain->status = 'A';
                        }
                        else {
                            $this->registereddomain->status = 'V';
                        }
                        $this->registereddomain->outgoing_hostname = NULL;
                        $this->registereddomain->outgoing_port = NULL;
                        $this->registereddomain->outgoing_socket_type = NULL;
                        if (!$this->registereddomain->save()) {
                            Yii::log('In Step2, save registered domain failed: ' .CVarDumper::dumpAsString($this->registereddomain)
                                        .CVarDumper::dumpAsString($this->registereddomain->getErrors()), 'info', 'application');
                        }
                    }
                    else {
                        if ($this->registereddomain->status !== 'A') {
                            $this->registereddomain->incoming_hostname = mb_convert_case($this->incoming_hostname, MB_CASE_LOWER, "UTF-8");
                            $this->registereddomain->incoming_port = $this->incoming_port;
                            $this->registereddomain->incoming_socket_type = $this->incoming_socket_type;
                            if ($this->registeredemail->e_mail == $this->registeredemail->e_mail_username) {
                                $this->registereddomain->incoming_auth = 'EMAIL';
                            }
                            else {
                                $this->registereddomain->incoming_auth = 'OTHER';
                            }
                            $this->registereddomain->status = 'A';
                            if (!$this->registereddomain->save()) {
                            }
                        }
                    }
                    break;
                case 'Update':
                    //list(, $this->maildomain) = explode("@", $this->useremail);
                    $this->registeredemail->e_mail_username = $this->emailusername;
                    $this->registeredemail->e_mail_password = $this->emailpassword;
                    $this->registeredemail->extended_service = ($this->extendedservice == 1) ? TRUE : FALSE;
                    if ($this->e_mail_verified) {
                        $this->registeredemail->status = 'A';
                    }
                    else {
                        $this->registeredemail->status = 'V';
                    }
                    if (!$this->registeredemail->save()) {
                        Yii::log('In update mailbox save failed: ' .CVarDumper::dumpAsString($this->registeredemail)
                                        .CVarDumper::dumpAsString($this->registeredemail->getErrors()), 'info', 'application');
                    }
                    if ($this->registereddomain <> NULL AND $this->registereddomain->status == 'A') { break; }
                    if ($this->registereddomain == NULL) {
                        $this->registereddomain = new mailconfig();
                        $this->registereddomain->maildomain = $this->maildomain;
                        $this->registereddomain->mailtype = 'IMAP';
		    }
                    $this->registereddomain->incoming_hostname = mb_convert_case($this->incoming_hostname, MB_CASE_LOWER, "UTF-8");
                    $this->registereddomain->incoming_port = $this->incoming_port;
                    $this->registereddomain->incoming_socket_type = $this->incoming_socket_type;
                    if ($this->registeredemail->e_mail == $this->registeredemail->e_mail_username) {
                            $this->registereddomain->incoming_auth = 'EMAIL';
                    }
                    else {
                            $this->registereddomain->incoming_auth = 'OTHER';
                    }
                    if ($this->e_mail_verified) { $this->registereddomain->status = 'A'; } else { $this->registereddomain->status = 'V'; }
//                    if (!$this->registereddomain->save()) {
//                            Yii::log('In update save registered domain failed: ' .CVarDumper::dumpAsString($this->registereddomain)
//                                        .CVarDumper::dumpAsString($this->registereddomain->getErrors()), 'info', 'application');
//                    }
                    break;
            }
        }

}
