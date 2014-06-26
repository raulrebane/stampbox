<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class RegisterController extends Controller
{
    
    public function actionStep1() 
       {
        $this->layout = 'register';
        $model = new Register;
        
        if(Yii::app()->getRequest()->getIsAjaxRequest()) {
            $model->attributes=$_POST['Register'];
            //Yii::log("Ajax validation activated: " .$model->useremail, 'info', 'application');
            echo CActiveForm::validate($model); 
            Yii::app()->end(); 
        }

        if(isset($_POST['Register']))
	{  
            $model->attributes=$_POST['Register'];     
            if ($model->validate())
            {
                $e_mail_verified = FALSE;
                $model->registereddomain = mailconfig::model()->find('maildomain=:1', 
                                    array(':1'=>mb_convert_case($model->registeredemail->maildomain, MB_CASE_LOWER, "UTF-8")));
                if ($model->registereddomain !== NULL)
                    {
                        $mailboxcheck = json_encode(array('e_mail'=>$model->registeredemail->e_mail,
                                'username'=>$model->registeredemail->e_mail_username,
                                'password'=>$model->registeredemail->e_mail_password,
                                'hostname'=>$model->registereddomain->incoming_hostname,
                                'port'=>$model->registereddomain->incoming_port,
                                'socket_type'=>$model->registereddomain->incoming_socket_type,
                                'auth_type'=>$model->registereddomain->incoming_auth));
                        $gmclient= new GearmanClient();
                        $gmclient->addServer("127.0.0.1", 4730);
                        $result = json_decode($gmclient->do("checkmailbox", $mailboxcheck),TRUE);
                        if ($result['status'] == 'ERROR') {
                            $model->addError('emailusername', 'We could not access your e-mail inbox. Please verify that your username and password is correct');
                            $this->render('Step1',array('model'=>$model,)); 
                            Yii::app()->end();
                        } else { $e_mail_verified = TRUE;}
                    }
                $customer = new TCustomer();
                $customer->username = mb_convert_case($model->useremail, MB_CASE_LOWER, "UTF-8");
                $customer->password = crypt($model->emailpassword, self::blowfishSalt());
                $customer->registered_date = Yii::app()->dateFormatter->format('yyyy/MM/dd HH:mm:ss', time());
                // set status A - Active if e_mail was successfully verified, else set V - verify
                if ($e_mail_verified) { $customer->status = 'A';} 
                else { $customer->status = 'V';}
                $customer->bad_logins = 0;
                //$customer->country = geoip_country_code_by_name($_SERVER['REMOTE_ADDR']);
                $customer->country = geoip_country_code_by_name('dsdev.dnsdynamic.com');
                if ($customer->save()) {
                    //log in user right away
                    $identity=new UserIdentity($model->useremail,$model->emailpassword);
                    $identity->authenticate();
                    Yii::app()->user->login($identity);               
                    //Generate 100 stamps for the user for registration
                    self::GenerateStamps(Yii::App()->user->getId(), 100);
                    $model->registeredemail = new usermailbox();
                    $model->registeredemail->customer_id = Yii::app()->user->getId();
                    $model->registeredemail->e_mail = $customer->username;
                    $model->registeredemail->e_mail_username = $model->emailusername;
                    $model->registeredemail->e_mail_password = $model->emailpassword;
                    $model->registeredemail->status = 'V'; // V = verify
                    list(, $model->registeredemail->maildomain) = explode("@", $customer->username);
                    $model->registeredemail->save();
                }
                else { 
                    Yii::log('Error saving new customer' .CVarDumper::dumpAsString($customer->getErrors()), 'info', 'application');
                    throw new CHttpException(500,'We are sorry for not being able to service you. Request was sent for our administrators to investigate this problem. Please try again later.');
                }
                if ($e_mail_verified) { $this->render('Invite', array('model'=>$model,)); }
                else { $this->render('Step2'); }
            }
            else {
               $model->addError('useremail', 'This e-mail is already registered. If you think this is an error please contact us '); 
            }
        }                
        $this->render('Step1',array('model'=>$model,)); 
       }
    
    public function actionStep2() {
        $this->layout = 'register';        
        $model = new Register;
        if(isset($_POST['Register'])) {  
            $model->attributes=$_POST['Register']; {
		     $model->registereddomain = mailconfig::model()->find('maildomain=:1', 
                                    array(':1'=>mb_convert_case($model->maildomain, MB_CASE_LOWER, "UTF-8")));
                    if ($model->registereddomain === NULL)
                    {
                        $model->registereddomain = new mailconfig();
                        $model->registereddomain->maildomain = mb_convert_case($model->maildomain, MB_CASE_LOWER, "UTF-8");
                        $model->registereddomain->mailtype = 'IMAP';
                        $model->registereddomain->incoming_hostname = mb_convert_case($model->incoming_hostname, MB_CASE_LOWER, "UTF-8");
                        $model->registereddomain->incoming_port = $model->incoming_port;
                        $model->registereddomain->save();             
                    }
                    if ($model->registereddomain->incoming_auth == 'USERNAME') {
                        list($model->e_mail_username, ) = explode("@", $model->e_mail_username);
                    }
            }
        }
        //Yii::app()->user->setFlash('success', 'Welcome - ' .Yii::app()->user->name .'<br>We have credited your account with 100 free Stamps to start using our service. You can now invite your contacts from your e-mail account');
        list(, $model->maildomain) = explode("@", Yii::app()->user->username);
        $this->render('Step2',array('model'=>$model,));
    }
    
    public function actionInvite()
    {
        if (isset($_POST['selectedIds'])) {
            foreach ($_POST['selectedIds'] as $id) {
                $invite = Invitations::model()->find('customer_id=:1 and invited_email=:2', 
                                    array(':1'=>Yii::app()->user->getId(), ':2'=>$id));
                $invite->invite = 'Y';
                $invite->save();
            }
            $this->redirect(array('site/index'));
        }
//      Yii::log("{".$model->incoming_hostname .":" .$model->incoming_port ."/ssl/novalidate-cert} - username: "
//                                .$model->e_mail_username ." and passw: " .$model->e_mail_password);
        $inbox = imap_open("{".$model->incoming_hostname .":" .$model->incoming_port ."/ssl/novalidate-cert}",
                                $model->e_mail_username,$model->e_mail_password);
        $emails = imap_search($inbox,'ALL');
        /* if emails are returned, cycle through each... */
        if($emails) {
            $senders = array();
            /* for every email... */
            foreach($emails as $email_number) {
                /* get information specific to this email */
                $overview = imap_fetch_overview($inbox,$email_number,0);
                $mailfrom = imap_mime_header_decode($overview[0]->from);
                if (count($mailfrom) == 2) {
                    $fromname = utf8_encode(rtrim($mailfrom[0]->text));
                    $fromemail = trim($mailfrom[1]->text, " <>");}
                else {
                    if (strpos($overview[0]->from, "<")) {
                        list($fromname, $fromemail) = explode("<", $overview[0]->from);}
                else {
                    $fromemail = $overview[0]->from;
                    $fromname = $overview[0]->from;
		}
                $fromemail = trim($fromemail, " <>");
                $fromname = utf8_encode(rtrim($fromname)); }
                if (array_key_exists($fromemail,$senders)) {
                    $senders[$fromemail]['rcount']++; }
                else {
                    $senders[$fromemail]['e-mail'] = $fromemail;
                    $senders[$fromemail]['Name'] = $fromname;
                    $senders[$fromemail]['rcount'] = 1;
                }
            }
        }
        imap_close($inbox);
        if (isset($senders)) {
            usort($senders, "self::cmp");
            $model->top_senders = array_values($senders);
            foreach ($model->top_senders as $i) {
                $invite = Invitations::model()->find('customer_id=:1 and invited_email=:2', 
                                    array(':1'=>Yii::app()->user->getId(), ':2'=>$i['e-mail']));
                if ($invite == NULL) {
                    $invite = new Invitations;
                    $invite->customer_id = Yii::app()->user->getId();
                    $invite->invited_email = $i['e-mail'];
                    $invite->from_count = $i['rcount'];
                    $invite->name = $i['Name'];
                    $invite->save();
                }
            }
            Yii::app()->user->setFlash('success', 'Here is the list of e-mail senders from your e-mail INBOX. Mark those you want to invite.');
        }
        else {
            Yii::app()->user->setFlash('success', 'Your e-mail inbox seems to be empty and there was nobody to invite');
            $this->redirect(array('site/index'));
        }
        $this->render('Step2',array('model'=>$model,));
        Yii::app()->end();
//        CVarDumper::Dump($model);

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
    
    function GenerateStamps($userid, $howmany)
    {
        $dbconnection = pg_connect("host=localhost dbname=ds user=ds_user password=Apua1234"); 
        $stamps['stamp_token'] = '';
        //$stamps['stamp_id'] = 'NULL';
        $stamps['batch_id'] = 1;
        $stamps['issued_to'] = $userid;
        $stamps['status'] = 'U';
        $stamps['timestamp'] = 'now()';

        for ($insert_count = 1; $insert_count <= $howmany; $insert_count++)
        {
/*
            $rand = array();
	    for ($i = 0; $i < 8; $i += 1) {
	        $rand[] = pack('S', mt_rand(0, 0xffff));
	    }
	    $rand[] = substr(microtime(), 2, 6);
	    $rand = sha1(implode('', $rand), true);
            $stamps['stamp_token'] = strtr(substr(base64_encode($rand), 0, 64), array('+' => '.'));
 * 
 */
            // replaced with token generated by CSecurityManager
            $stamps['stamp_token'] = Yii::app()->SecurityManager->generateRandomString(32, TRUE);
            // Performing SQL insert
            $res = pg_insert($dbconnection, 'ds.t_stamps_issued', $stamps);
        }
        pg_close($dbconnection);
    }
    
    public function cmp(array $a, array $b) {
        return $b['rcount'] - $a['rcount'];
    }
}
?>
