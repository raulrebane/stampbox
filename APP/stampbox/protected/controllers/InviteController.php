<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class InviteController extends Controller
{
    public function actionIndex() {
        $sort = new CSort();
        $sort->attributes = array(
            'invited_email',
            'from_count',
            'name',
            'last_email_date',
        );

        $dataProvider=new CActiveDataProvider('Invitations', array('sort'=>$sort, 'pagination'=>array('pageSize'=>1000,)));
        $this->render('index',array('dataProvider'=>$dataProvider,));
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

    
    public function cmp(array $a, array $b) {
        return $b['rcount'] - $a['rcount'];
    }
}
?>
