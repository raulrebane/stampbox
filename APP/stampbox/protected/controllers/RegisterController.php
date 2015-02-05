<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class RegisterController extends Controller
{
    
    public function actionStep1() {
        $this->layout = 'register';
        $model = new Register;
        $model->scenario = 'Step1';
        if(Yii::app()->getRequest()->getIsAjaxRequest()) {
            $model->attributes=$_POST['Register'];
            //Yii::log("Ajax validation activated: " .$model->useremail, 'info', 'application');
            echo CActiveForm::validate($model); 
            Yii::app()->end(); 
        }
        if(isset($_POST['Register'])) {  
            $model->attributes=$_POST['Register'];     
            if ($model->validate()) {
                $e_mail_verified = FALSE;
                $model->registeredemail = new usermailbox();
                list(, $model->maildomain) = explode("@", $model->useremail); 
                $model->registeredemail->maildomain = mb_convert_case($model->maildomain, MB_CASE_LOWER, "UTF-8");
                $model->registereddomain = mailconfig::model()->find('maildomain=:1', 
                                    array(':1'=>$model->registeredemail->maildomain));
                if ($model->registereddomain !== NULL) {
                    $mailboxcheck = json_encode(array('e_mail'=>mb_convert_case($model->useremail, MB_CASE_LOWER, "UTF-8"),
                            'username'=>$model->emailusername,
                            'password'=>$model->emailpassword,
                            'hostname'=>$model->registereddomain->incoming_hostname,
                            'port'=>$model->registereddomain->incoming_port,
                            'socket_type'=>$model->registereddomain->incoming_socket_type,
                            'auth_type'=>$model->registereddomain->incoming_auth));
                    $gmclient= new GearmanClient();
                    $gmclient->addServer(Yii::app()->params['gearman']['gearmanserver'], Yii::app()->params['gearman']['port']);
                    $result = json_decode($gmclient->do("checkmailbox", $mailboxcheck),TRUE);
                    if ($result['status'] == 'ERROR') {
                        //Changed to allow registering without e-mail username
                        //$model->addError('emailusername', 'We could not access your e-mail inbox. Please verify that your username and password is correct');
                        //$this->render('Step1',array('model'=>$model,)); 
                        //Yii::app()->end();
                    } else { $e_mail_verified = TRUE;}
                }
                $customer = new TCustomer();
                $customer->username = mb_convert_case($model->useremail, MB_CASE_LOWER, "UTF-8");
                $customer->password = crypt($model->emailpassword, self::blowfishSalt());
                $customer->registered_date = Yii::app()->dateFormatter->format('yyyy/MM/dd HH:mm:ss', time());
                // set status A - Active if e_mail was successfully verified, else set V - verify
                if ($e_mail_verified == TRUE) { $customer->status = 'A';} 
                else { $customer->status = 'V';}
                $customer->bad_logins = 0;
                
                // try to use forwarded address first, then remoteaddress. If both fail or IP not in geoip db then put country as XX
                $headers = apache_request_headers();
                if ( array_key_exists( 'X-Forwarded-For', $headers ) && filter_var( $headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ) {
                    $customer->country = geoip_country_code_by_name($headers['X-Forwarded-For']); 
                    if (!$customer->country) {
                        $customer->country = 'XX';
                    }
                }
                else {
                    $customer->country = geoip_country_code_by_name($_SERVER['REMOTE_ADDR']);
                    if (!$customer->country) {
                        $customer->country = 'XX';
                    }
                }
                //Yii::log('In step1 - about to save customer ' .CVarDumper::dumpAsString($customer), 'info', 'application');
                if ($customer->save()) {
                    //log in user right away
                    $identity=new UserIdentity($model->useremail,$model->emailpassword);
                    $identity->authenticate();
                    Yii::app()->user->login($identity, 3600*24*30);               
                    //Generate 100 stamps for the user for registration
                    //$model->registeredemail = new usermailbox();
                    $model->registeredemail->customer_id = Yii::app()->user->getId();
                    $model->registeredemail->e_mail = $customer->username;
                    if ($e_mail_verified == TRUE) { 
                        // e_mail credentials were verified and working
                        $model->registeredemail->status = 'A'; 
                        $model->registeredemail->e_mail_username = NULL;
                        $model->registeredemail->e_mail_password = NULL;
                    }
                    else { 
                        // V = unverified, not usable
                        $model->registeredemail->status = 'V'; 
                        $model->registeredemail->e_mail_username = $model->emailusername;
                        $model->registeredemail->e_mail_password = $model->emailpassword;
                    } 
                    //Yii::log('In step1 - about to save customer e-mail ' .CVarDumper::dumpAsString($model->registeredemail), 'info', 'application');
                    if (!$model->registeredemail->save()) {
                        Yii::log('In step1 - customer mailbox save failed ' .CVarDumper::dumpAsString($model->registeredemail)
                                .$model->registeredemail->getErrors(), 'info', 'application');
                    }
                    $dbcommand =  Yii::app()->db->createCommand();
                    $dbcommand->insert('ds.t_account', array(
                        'customer_id'=>Yii::app()->user->getId(),
                        'points_bal'=>0,
                        'stamps_bal'=>0));
                    //self::GenerateStamps(Yii::App()->user->getId(), 100);
                    $gmclient= new GearmanClient();
                    $gmclient->addServer(Yii::app()->params['gearman']['gearmanserver'], Yii::app()->params['gearman']['port']);
                    $stampparams = json_encode(array('customer_id'=>Yii::app()->user->getId(), 'howmany'=>100, 
                                                'stampid'=>1, 'description'=>'Free stamps for joining'));
                    $result = json_decode($gmclient->do("issuestamps", $stampparams),TRUE);
                }
                else { 
                    Yii::log('Error saving new customer' .CVarDumper::dumpAsString($customer->getErrors()), 'info', 'application');
                    throw new CHttpException(500,'We are sorry for not being able to service you. Request was sent for our administrators to investigate this problem. Please try again later.');
                }
                if ($e_mail_verified) {
                    $loadinvitations = json_encode(array('customer_id'=>Yii::App()->user->getID(),
                                'e_mail'=>$model->useremail,
                                'username'=>$model->emailusername,
                                'password'=>$model->emailpassword,
                                'hostname'=>$model->registereddomain->incoming_hostname,
                                'port'=>$model->registereddomain->incoming_port,
                                'socket_type'=>$model->registereddomain->incoming_socket_type,
                                'auth_type'=>$model->registereddomain->incoming_auth));
                    $gmclient= new GearmanClient();
                    $gmclient->addServer(Yii::app()->params['gearman']['gearmanserver'], Yii::app()->params['gearman']['port']);
                    $result = json_decode($gmclient->do("loadinvitations", $loadinvitations),TRUE);
                    $this->redirect(array('invite/index')); 
                    
                }
                elseif ($model->emailusername <> '') { 
                    Yii::log('Going to step2:', 'info', 'application');
		    //list(, $model->maildomain) = explode("@", $customer->username);
                    //$model->mailtype = 'IMAP';
                    //$model->incoming_auth = 'EMAIL';
                    //$this->render('Step2', array('model'=>$model)); 
		    //Yii::app()->end();
		    $this->redirect(array('register/step2'));}
                else {
                    Yii::log('New customer registered without e-mail parameters', 'info', 'application');
                    $this->redirect(array('site/index'));
                }
            }
        }
        /*
        else {
            echo CActiveForm::validate($model); 
            Yii::app()->end();               
        }*/
        $this->render('Step1',array('model'=>$model,)); 
    }
    
    public function actionStep2() {
        $this->layout = 'register2';        
        $model = new Register;
        $model->scenario = 'Step2';
        if(isset($_POST['Register'])) {  
            $model->attributes=$_POST['Register']; 
            Yii::log('In Step2, got maildomain:', 'info', 'application');
            //list(, $model->maildomain) = explode("@", Yii::app()->user->username);
            // find user mailbox record
            $model->registeredemail = usermailbox::model()->find('customer_id=:1 and e_mail=:2', 
                    array(':1'=>Yii::app()->user->getId(), ':2'=>Yii::app()->user->username));
            if ($model->registeredemail == NULL) {
                // how did we got here at all?
                Yii::log('In Step2, '.Yii::app()->user->getId() .' ' .Yii::app()->user->username 
                        .' missing user mailbox record' , 'info', 'application');
                $this->redirect(array('site/index'));
            }
            // find mail domain record 
            $model->registereddomain = mailconfig::model()->find('maildomain=:1', 
                array(':1'=>$model->registeredemail->maildomain));
            // If user has saved username and password try to verify connection to mailbox and activate mailbox
            if ($model->registeredemail->e_mail_username <> NULL and $model->registeredemail->e_mail_password <> NULL) {
                // Let's first check if we can access mailbox with newly posted mailconfig values
                $mailboxcheck = json_encode(array('e_mail'=>Yii::app()->user->username,
                    'username'=>$model->registeredemail->e_mail_username ,
                    'password'=>$model->registeredemail->e_mail_password,
                    'hostname'=>$model->incoming_hostname,
                    'port'=>$model->incoming_port,
                    'socket_type'=>$model->incoming_socket_type,
                    'auth_type'=>'EMAIL'));
                $gmclient= new GearmanClient();
                $gmclient->addServer(Yii::app()->params['gearman']['gearmanserver'], Yii::app()->params['gearman']['port']);
                $result = json_decode($gmclient->do("checkmailbox", $mailboxcheck),TRUE);
                if ($result['status'] == 'ERROR') {
                    // New posted values did not work so let's see if there are stored values
                    if ($model->registereddomain == NULL) {
                        // Save those values anyway into DB
                        $model->registereddomain = new mailconfig();
                        $model->registereddomain->maildomain = $model->registeredemail->maildomain;
                        $model->registereddomain->mailtype = 'IMAP';
                        $model->registereddomain->incoming_hostname = mb_convert_case($model->incoming_hostname, MB_CASE_LOWER, "UTF-8");
                        $model->registereddomain->incoming_port = $model->incoming_port;
                        $model->registereddomain->incoming_socket_type = $model->incoming_socket_type;
                        $model->registereddomain->incoming_auth = 'EMAIL';
                        $model->registereddomain->outgoing_hostname = mb_convert_case($model->outgoing_hostname, MB_CASE_LOWER, "UTF-8");
                        $model->registereddomain->outgoing_port = $model->outgoing_port;
                        $model->registereddomain->outgoing_socket_type = $model->outgoing_socket_type;
                        $model->registereddomain->save();
                    }
                    else {
                        // try to verify mailbox access using already stored mailbox data
                        // this can theoretically happen only if user was waiting to input data and  
                        // somebody else already saved this data meanwhile to database.
                        $mailboxcheck = json_encode(array('e_mail'=>Yii::app()->user->username,
                            'username'=>$model->registeredemail->e_mail_username ,
                            'password'=>$model->registeredemail->e_mail_password,
                            'hostname'=>$model->registereddomain->incoming_hostname,
                            'port'=>$model->registereddomain->incoming_port,
                            'socket_type'=>$model->registereddomain->incoming_socket_type,
                            'auth_type'=>'EMAIL'));
                        $gmclient= new GearmanClient();
                        $gmclient->addServer(Yii::app()->params['gearman']['gearmanserver'], Yii::app()->params['gearman']['port']);
                        $result = json_decode($gmclient->do("checkmailbox", $mailboxcheck),TRUE);
                        if ($result['status'] == 'ERROR') {
                            // Saved values also did not work so nothing to do. Direct customer to homepage
                            $this->redirect(array('site/index')); 
                        }
                        else {
                            // Config loaded from DB actually works so we load contact and send to invite
                            $loadinvitations = json_encode(array('customer_id'=>Yii::App()->user->getID(),
                                    'e_mail'=>Yii::app()->user->username,
                                    'username'=>$model->registeredemail->e_mail_username,
                                    'password'=>$model->registeredemail->e_mail_password,
                                    'hostname'=>$model->registereddomain->incoming_hostname,
                                    'port'=>$model->registereddomain->incoming_port,
                                    'socket_type'=>$model->registereddomain->incoming_socket_type,
                                    'auth_type'=>$model->registereddomain->incoming_auth));
                            $gmclient= new GearmanClient();
                            $gmclient->addServer(Yii::app()->params['gearman']['gearmanserver'], Yii::app()->params['gearman']['port']);
                            $result = json_decode($gmclient->do("loadinvitations", $loadinvitations),TRUE);
                            $this->redirect(array('invite/index')); 
                        }
                    }
                } 
                else { 
                    //Newly posted values work so we have successful domain config and :) we're saving
                    $model->registeredemail->status = 'A';
                    $model->registeredemail->save();
                    if ($model->registereddomain === NULL) {
                        // init new record
                        $model->registereddomain = new mailconfig();
                    }
                    $model->registereddomain->maildomain = $model->registeredemail->maildomain;
                    $model->registereddomain->mailtype = 'IMAP';
                    $model->registereddomain->incoming_hostname = mb_convert_case($model->incoming_hostname, MB_CASE_LOWER, "UTF-8");
                    $model->registereddomain->incoming_port = $model->incoming_port;
                    $model->registereddomain->incoming_socket_type = $model->incoming_socket_type;
                    $model->registereddomain->incoming_auth = 'EMAIL';
                    $model->registereddomain->outgoing_hostname = mb_convert_case($model->outgoing_hostname, MB_CASE_LOWER, "UTF-8");
                    $model->registereddomain->outgoing_port = $model->outgoing_port;
                    $model->registereddomain->outgoing_socket_type = $model->outgoing_socket_type;
                    $model->registereddomain->save();
                    $loadinvitations = json_encode(array('customer_id'=>Yii::App()->user->getID(),
                            'e_mail'=>$model->useremail,
                            'username'=>$model->emailusername,
                            'password'=>$model->emailpassword,
                            'hostname'=>$model->registereddomain->incoming_hostname,
                            'port'=>$model->registereddomain->incoming_port,
                            'socket_type'=>$model->registereddomain->incoming_socket_type,
                            'auth_type'=>$model->registereddomain->incoming_auth));
                    $gmclient= new GearmanClient();
                    $gmclient->addServer(Yii::app()->params['gearman']['gearmanserver'], Yii::app()->params['gearman']['port']);
                    $result = json_decode($gmclient->do("loadinvitations", $loadinvitations),TRUE);
                    $this->redirect(array('invite/index')); 
                }
            }
            else {
            }
        }
        //Yii::app()->user->setFlash('success', 'Welcome - ' .Yii::app()->user->name .'<br>We have credited your account with 100 free Stamps to start using our service. You can now invite your contacts from your e-mail account');
        list(, $model->maildomain) = explode("@", Yii::app()->user->username);
        //$model->mailtype = 'IMAP';
        //$model->incoming_auth = 'EMAIL';
        $this->render('Step2',array('model'=>$model,));
    }
    
    /*
    public function ActionInvite($id,$name,$email,$rcount) {
        Yii::log("$email with $id now invited", 'info','application');
        if(!isset($_GET['ajax']))
//            $this->redirect(Yii::app()->request->urlReferrer);
            return true;
    }
     * 
     */
    
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
  
    function cmp(array $a, array $b) {
        return $b['rcount'] - $a['rcount'];
    }
    
    function LoadContacs($pmodel) {
        $loadinvitationdata = json_encode(array('customer_id'=>Yii::App()->user->getID(),
                    'e_mail'=>$pmodel->useremail,
                    'username'=>$pmodel->emailusername,
                    'password'=>$pmodel->emailpassword,
                    'hostname'=>$pmodel->registereddomain->incoming_hostname,
                    'port'=>$pmodel->registereddomain->incoming_port,
                    'socket_type'=>$pmodel->registereddomain->incoming_socket_type,
                    'auth_type'=>$pmodel->registereddomain->incoming_auth));
        $gmclient= new GearmanClient();
        $gmclient->addServer(Yii::app()->params['gearman']['gearmanserver'], Yii::app()->params['gearman']['port']);
        $result = json_decode($gmclient->do("loadinvitations", $loadinvitationdata),TRUE);
    }
}
?>
