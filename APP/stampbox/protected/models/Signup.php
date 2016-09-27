<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Signup extends CFormModel
{
        public $useremail;
        public $userpassword;
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
			array('useremail, userpassword, agreewithterms', 'required'),
                        array('useremail', 'checkregistered'),
			array('useremail', 'length', 'max'=>128),
			array('userpassword', 'length', 'max'=>255),
                        array('useremail', 'email'),
                        array('agreewithterms', 'compare', 'compareValue'=>'1', 'message'=>'You have to agree with Terms and Conditions'),
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
                        'agreewithterms'=>'I agree to the <a href="' .Yii::app()->createUrl('site/terms') .'">Terms of Service</a> which form an integral part of the agreement',
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

                // Save customer e-mail
                $registeredemail = new usermailbox();
                $registeredemail->customer_id = Yii::app()->user->getId();
                $registeredemail->e_mail = mb_convert_case($this->useremail, MB_CASE_LOWER, "UTF-8");
                $registeredemail->status = 'V';
                list(, $maildomain) = explode("@", $this->useremail);
                $registeredemail->maildomain = mb_convert_case($maildomain, MB_CASE_LOWER, "UTF-8");
                $registeredemail->sending_service = TRUE;
                $registeredemail->receiving_service = FALSE;
                $registeredemail->sorting_service = FALSE;
                if (!$registeredemail->save()) {
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
