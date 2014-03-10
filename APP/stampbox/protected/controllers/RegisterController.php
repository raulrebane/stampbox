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
        $model = new Register;
        
        if(isset($_POST['Register']))
	{  
            $model->attributes=$_POST['Register'];     
            if ($model->validate())
            {
                $customer['username'] = $model->username;
                $customer['firstname'] = $model->firstname;
                $customer['lastname'] = $model->lastname;
                $customer['password'] = crypt($model->password, self::blowfishSalt());
                $customer['preferred_lang'] = $model->userlang;
                $customer['last_seen'] = Yii::app()->dateFormatter->format('yyyy/MM/dd HH:mm:ss', time());
			// default status A - active
                $customer['status'] = 'A';
			// no bad logins yet
                $customer['bad_logins'] = 0;
                $dbconnection = pg_connect("host=localhost dbname=ds user=ds_user password=Apua1234")
                    or die('Could not connect: ' . pg_last_error());
//                $dbconnection=Yii::app()->db;
                $res = pg_insert($dbconnection, 'ds.t_customer', $customer);
                if ($res) { 
                    $identity=new UserIdentity($model->username,$model->password);
                    $identity->authenticate();
                    Yii::app()->user->login($identity);
                    self::GenerateStamps(Yii::App()->user->getId(), $dbconnection, 100);
                    pg_close($dbconnection);
                    $this->redirect(array('Step2'));
                }
                
            }
         }
        $this->render('Step1',array('model'=>$model,)); 
       }
       
    public function actionStep2()
    {
        $model = new Register;

        if(isset($_POST['Register']))
	{  
            $model->attributes=$_POST['Register'];     
//            if ($model->validate())
                {
                    if (!isset($model->registereddomain))
                    {
                        $model->registereddomain = new mailconfig();
                        $model->registereddomain->maildomain = $model->maildomain;
                        $model->registereddomain->mailtype = 'IMAP';
                        $model->registereddomain->incoming_hostname = $model->incoming_hostname;
                        $model->registereddomain->incoming_port = $model->incoming_port;
                        $model->registereddomain->save();
                    }
                    if (!isset($model->registeredemail))
                    {
                        $model->registeredemail = new usermailbox();
                        $model->registeredemail->customer_id = Yii::app()->user->getId();
                        $model->registeredemail->e_mail = Yii::app()->user->username;
                        $model->registeredemail->e_mail_username = $model->e_mail_username;
                        $model->registeredemail->e_mail_password = $model->e_mail_password;
                        $model->registeredemail->save();
                    }
//                    Yii::log("Before inbox opening",'info', 'application');
//                    Yii::log("{".$model->incoming_hostname .":" .$model->incoming_port ."/ssl/novalidate-cert}",'info', 'application');
//                    Yii::log("Username: " .$model->e_mail_username ." password: " .$model->e_mail_password,'info', 'application');
                    $inbox = imap_open("{".$model->incoming_hostname .":" .$model->incoming_port ."/ssl/novalidate-cert}",
                                $model->e_mail_username,$model->e_mail_password) or die;
 //                   Yii::log("after inbox open",'info', 'application');
                    $emails = imap_search($inbox,'ALL');
                    /* if emails are returned, cycle through each... */
                    if($emails) {
                        $senders = array();
                        $r = 0;
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
                                $senders[$fromemail]['id'] = $r;
                                $r++;
                            }
			}
                    }
                    imap_close($inbox);
//                   Yii::log("Before sort", 'info', 'application');
                    usort($senders, "self::cmp");
//                    Yii::log("After sort", 'info', 'application');
		    $model->top_senders = array_values($senders);
                    foreach ($model->top_senders as $i)
                    {
                        $invite = Invitations::model()->find('customer_id=:1 and invited_email=:2', 
                                    array(':1'=>Yii::app()->user->getId(), ':2'=>$i['e-mail']));
                        if ($invite == NULL)
                        {
                        $invite = new Invitations;
                        $invite->customer_id = Yii::app()->user->getId();
                        $invite->invited_email = $i['e-mail'];
                        $invite->from_count = $i['rcount'];
                        $invite->name = $i['Name'];
                        $invite->save();
                        }
                    }
		    Yii:app()->user->setFlash('success', 'Here is the list of e-mail senders from your e-mail INBOX. Mark those you want to invite.');
                    $this->render('Step2',array('model'=>$model,));
//                    Yii::app()->end();
                }
        }
        
        list(, $model->maildomain) = explode("@", Yii::app()->user->username);
	$model->e_mail_username = Yii::app()->user->username;
        $model->registereddomain = mailconfig::model()->findByAttributes(array('maildomain'=>$model->maildomain));
        if (isset($model->registereddomain)) {
            $model->incoming_hostname = $model->registereddomain->incoming_hostname;
            $model->incoming_port = $model->registereddomain->incoming_port;
        }

        Yii::app()->user->setFlash('success', 'Welcome - ' .Yii::app()->user->name .'<br>We have credited your account with 100 free Stamps to start using our service. You can now invite your contacts from your e-mail account');
        $this->render('Step2',array('model'=>$model,));
    }

    public function ActionInvite($id,$name,$email,$rcount) {
        Yii::log("$email with $id now invited", 'info','application');
        if(!isset($_GET['ajax']))
//            $this->redirect(Yii::app()->request->urlReferrer);
            return true;
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
    
    function GenerateStamps($userid, $dbconn, $howmany)
    {
        $stamps['stamp_token'] = '';
        //$stamps['stamp_id'] = 'NULL';
        $stamps['batch_id'] = 1;
        $stamps['issued_to'] = $userid;
        $stamps['status'] = 'U';
        $stamps['timestamp'] = 'now()';

        for ($insert_count = 1; $insert_count <= $howmany; $insert_count++)
        {
            $rand = array();
	    for ($i = 0; $i < 8; $i += 1) {
	        $rand[] = pack('S', mt_rand(0, 0xffff));
	    }
	    $rand[] = substr(microtime(), 2, 6);
	    $rand = sha1(implode('', $rand), true);
            $stamps['stamp_token'] = strtr(substr(base64_encode($rand), 0, 64), array('+' => '.'));
            // Performing SQL insert
            $res = pg_insert($dbconn, 'ds.t_stamps_issued', $stamps);
        }
    }
    
    public function cmp(array $a, array $b) {
        return $b['rcount'] - $a['rcount'];
    }
}
?>
