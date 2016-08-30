<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Signup extends CFormModel
{
        // Step1 data
        public $useremail;
        public $userpassword;
        public $agreewithterms;
        
        public $emailusername;
        
        // mailbox config related fields
        public $maildomain;
        public $mailtype;
        public $incoming_hostname;
        public $incoming_port;
        public $incoming_socket_type;
        public $incoming_auth;
        
        public $simpleservice;
        public $sendingservice;
        public $receivingservice;
        public $sortingservice;
        
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
			array('useremail, userpassword, agreewithterms', 'required', 'on'=>'Simple'),
                    
                        array('useremail, userpassword, agreewithterms, incoming_hostname, incoming_port, incoming_socket_type', 'required', 'on'=>'Extended'),
                        array('incoming_hostname', 'length', 'max'=>'255', 'on'=>'Extended'),
                        array('incoming_port', 'numerical', 'integerOnly'=>true, 'on'=>'Extended'),
                        array('incoming_socket_type', 'in','range'=>array('NULL', 'ssl', 'tls'), 'allowEmpty'=>false, 'on'=>'Extended'),

                        array('useremail', 'checkregistered'),
			array('useremail', 'length', 'max'=>128),
                        array('emailusername', 'length', 'max'=>128),
                        array('emailusername', 'default', 'value'=>NULL),
			array('userpassword', 'length', 'max'=>255),
                        array('useremail', 'email'),
                        array('agreewithterms', 'compare', 'compareValue'=>'1', 'message'=>'You have to agree with Terms and Conditions'),
                        array('maildomain, incoming_auth, simpleservice', 'safe'),
                        array('sendingservice, receivingservice, sortingservice', 'safe'),
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
			'useremail'=>'E-mail: ',
                        'userpassword'=>'Password',
                        'emailusername'=>'IMAP login name',
                        'maildomain'=>'E-mail provider',
                        'incoming_hostname'=>'Mail server name',
                        'incoming_port'=>'Port',
                        'incoming_socket_type'=>'Connection security',
                        'agreewithterms'=>'I agree to the <a href="' .Yii::app()->createUrl('site/terms') .'">Terms of Service</a> which form an integral part of the agreement',
                        'sendingservice'=>'Sending service: ',
                        'receivingservice'=>'Collection service: ',
                        'sortingservice'=>'Protection service: ',
                        'simpleservice'=>'Sign up for extended service: '
		);
	}

        public function save() {
            // we are in step1, register customer, create account, save mailbox and issue free stamps
            $customer = new TCustomer();
            $customer->username = mb_convert_case($this->useremail, MB_CASE_LOWER, "UTF-8");
            $customer->password = crypt($this->userpassword, self::blowfishSalt());
            $customer->registered_date = Yii::app()->dateFormatter->format('yyyy/MM/dd HH:mm:ss', time());
            $customer->status = 'A';
            $customer->bad_logins = 0;
            // try to use forwarded address first, then remoteaddress. If both fail or IP not in geoip db then put country as XX
            $headers = apache_request_headers();
            if ( array_key_exists( 'X-Forwarded-For', $headers ) && filter_var( $headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
                $customer->country = geoip_country_code_by_name($headers['X-Forwarded-For']); 
                if (!$customer->country) { $customer->country = 'XX'; }
            }
            else {
                $customer->country = geoip_country_code_by_name($_SERVER['REMOTE_ADDR']);
                if (!$customer->country) { $customer->country = 'XX'; }
            }
            if ($customer->save()) {
                //log in user right away
                $identity=new UserIdentity($this->useremail,$this->userpassword);
                $identity->authenticate();
                Yii::app()->user->login($identity, 3600*24*30);
                // Create account
                $dbcommand =  Yii::app()->db->createCommand();
                $dbcommand->insert('ds.t_account', array(
                  'customer_id'=>Yii::app()->user->getId(),
                  'points_bal'=>0,
                  'stamps_bal'=>0));

                // Check if we can access customer mailbox
                list(, $this->maildomain) = explode("@", $this->useremail);
                $this->registereddomain = mailconfig::model()->find('maildomain=:1', array(':1'=>$this->maildomain));
                if ($this->registereddomain == NULL OR $this->registereddomain->status <> 'A') {
                    if ($this->registereddomain == NULL) { $this->registereddomain = new mailconfig();} 
                    $this->registereddomain->maildomain = $this->maildomain;
                    $this->registereddomain->mailtype = 'IMAP';
                    $this->registereddomain->incoming_hostname = mb_convert_case($this->incoming_hostname, MB_CASE_LOWER, "UTF-8");
                    $this->registereddomain->incoming_port = $this->incoming_port;
                    $this->registereddomain->incoming_socket_type = $this->incoming_socket_type;
                    if ($this->useremail == $this->emailusername) {
                        $this->registereddomain->incoming_auth = 'EMAIL'; }
                    else {
                        $this->registereddomain->incoming_auth = NULL; }
                    $this->registereddomain->outgoing_hostname = NULL;
                    $this->registereddomain->outgoing_port = NULL;
                    $this->registereddomain->outgoing_socket_type = NULL;
                    $this->registereddomain->status = 'V';
                    if (!$this->registereddomain->save()) {
                        Yii::log('save registered domain failed: ' .CVarDumper::dumpAsString($this->registereddomain)
                                .CVarDumper::dumpAsString($this->registereddomain->getErrors()), 'error', 'application');
                    }
                }
                if (isset($this->emailusername)) {
                    $mailboxcheck = json_encode(array('e_mail'=>mb_convert_case($this->useremail, MB_CASE_LOWER, "UTF-8"),
			'username'=>  $this->emailusername,'password'=>$this->userpassword,
                        'hostname'=>$this->registereddomain->incoming_hostname,'port'=>$this->registereddomain->incoming_port,
			'socket_type'=>$this->registereddomain->incoming_socket_type,'auth_type'=>$this->registereddomain->incoming_auth));
                }
                else {
                    $this->emailusername = $this->useremail;
                    $mailboxcheck = json_encode(array('e_mail'=>mb_convert_case($this->useremail, MB_CASE_LOWER, "UTF-8"),
			'username'=>  $this->useremail,'password'=>$this->userpassword,
                        'hostname'=>$this->registereddomain->incoming_hostname,'port'=>$this->registereddomain->incoming_port,
			'socket_type'=>$this->registereddomain->incoming_socket_type,'auth_type'=>$this->registereddomain->incoming_auth));
                }
                Yii::log('In Signup, verifying e-mail:' .CVarDumper::dumpAsString($mailboxcheck), 'info', 'application');
		$gmclient= new GearmanClient();
		$gmclient->addServer(Yii::app()->params['gearman']['gearmanserver'], Yii::app()->params['gearman']['port']);
		$result = json_decode($gmclient->doNormal("CheckMailbox", $mailboxcheck),TRUE);
		$this->e_mail_verified = FALSE;
                if ($result['status'] == 'ERROR') { 
                    $mailboxcheck = json_encode(array('e_mail'=>mb_convert_case($this->useremail, MB_CASE_LOWER, "UTF-8"),
			'username'=>  $this->useremail,'password'=>$this->userpassword,
                        'hostname'=>$this->registereddomain->incoming_hostname,'port'=>$this->registereddomain->incoming_port,
			'socket_type'=>$this->registereddomain->incoming_socket_type,'auth_type'=>$this->registereddomain->incoming_auth));
                        $result = json_decode($gmclient->doNormal("checkmailbox", $mailboxcheck),TRUE);
                    if ($result['status'] == 'ERROR') { $this->e_mail_verified = FALSE; } 
                    else { 
                        $this->emailusername = $this->useremail;
                        $this->e_mail_verified = TRUE; 
                    }
                } 
		else { 
                    $this->e_mail_verified = TRUE;
                    $this->registereddomain->status = 'A';
                    $this->registereddomain->save();
                }

                // Save customer e-mail
                $this->registeredemail = new usermailbox();
                $this->registeredemail->customer_id = Yii::app()->user->getId();
                $this->registeredemail->e_mail = mb_convert_case($this->useremail, MB_CASE_LOWER, "UTF-8");
                if ($this->e_mail_verified == TRUE) { $this->registeredemail->status = 'A'; }
                else { $this->registeredemail->status = 'U';}
                $this->registeredemail->maildomain = mb_convert_case($this->maildomain, MB_CASE_LOWER, "UTF-8");
                $this->registeredemail->e_mail_username = $this->emailusername;
                $this->registeredemail->e_mail_password = $this->userpassword;
                $this->registeredemail->sending_service = TRUE;
                $this->registeredemail->receiving_service = ($this->simpleservice == 1) ? TRUE : FALSE;
                $this->registeredemail->sorting_service = ($this->simpleservice == 1) ? TRUE : FALSE;
                if (!$this->registeredemail->save()) {
                   Yii::log('customer mailbox save failed during registration' .CVarDumper::dumpAsString($this->registeredemail)
                        .CVarDumper::dumpAsString($this->registeredemail->getErrors()), 'error', 'application');
                }
                // Issue free stamps for customer
                $gmclient= new GearmanClient();
                $gmclient->addServer(Yii::app()->params['gearman']['gearmanserver'], Yii::app()->params['gearman']['port']);
                $stampparams = json_encode(array('customer_id'=>Yii::app()->user->getId(), 'howmany'=>100, 
                                        'stampid'=>1, 'description'=>'Free stamps for joining'));
                $result = json_decode($gmclient->doNormal("IssueStamps", $stampparams),TRUE);
            }
            else { 
                Yii::log('Error saving new customer' .CVarDumper::dumpAsString($customer->getErrors()), 'error', 'application');
                throw new CHttpException(500,'We are sorry for not being able to service you. Request was sent for our administrators to investigate this problem. Please try again later.');
            }
        }
        
        function blowfishSalt($cost = 13)
	{
	    if (!is_numeric($cost) || $cost < 4 || $cost > 31) {
	        throw new Exception("cost parameter must be between 4 and 31");
	    }
	    $rand = array();
	    for ($i = 0; $i < 8; $i += 1) {
	        $rand[] = pack('S', mt_rand(0, 0xffff));
	    }
	    $rand[] = substr(microtime(), 2, 6);
	    $rand = sha1(implode('', $rand), true);
	    $salt = '$2a$' . sprintf('%02d', $cost) . '$';
	    $salt .= strtr(substr(base64_encode($rand), 0, 22), array('+' => '.'));
	    return $salt;
	}

}
