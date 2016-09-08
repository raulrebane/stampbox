<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class SignupController extends Controller
{
    public function actionIndex() {
        $model = new Signup();
        if(isset($_POST['ajax']) && $_POST['ajax']==='signup-form') {
            $model->attributes=$_POST['Signup'];
            if ($model->simpleservice == 0) { $model->scenario = 'Simple'; }
            else { $model->scenario = 'Extended'; }
            Yii::log("Signup: " .CVarDumper::dumpAsString($model), 'info', 'application');
            $errors = CActiveForm::validate($model);
            if ($errors != '[]')
                {
                Yii::log("Signup error: " .CVarDumper::dumpAsString($errors), 'info', 'application');
                echo $errors;
                Yii::app()->end();
                }
            else {
                $model->save();
                if ($model->e_mail_verified == TRUE) {
                    $loadinvitationdata = json_encode(array('customer_id'=>Yii::App()->user->getID(),
                        'e_mail'=>$model->useremail, 'username'=>$model->emailusername, 'password'=>$model->userpassword,
                        'hostname'=>$model->registereddomain->incoming_hostname, 'port'=>$model->registereddomain->incoming_port,
                        'socket_type'=>$model->registereddomain->incoming_socket_type, 'auth_type'=>$model->registereddomain->incoming_auth));
                    $gmclient= new GearmanClient();
                    $gmclient->addServer(Yii::app()->params['gearman']['gearmanserver'], Yii::app()->params['gearman']['port']);
                    $result = json_decode($gmclient->doBackground("LoadInvitations", $loadinvitationdata),TRUE);
                }
                Yii::app()->user->setFlash('success',
                	'<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
                        . '<h4>Thank you, you are now ready to use the basic service. As a sign up bonus we have '
                        . 'credited you with 100 stamps free of charge.</h4></div>' 
                        . '<div class="alert alert-success">We recommend you to sign up for the extended service which automatically filters '
                        . 'e-mails with digital stamps to your inbox and those without stamps into no-stamp-box email folder.<br>'
                        . 'With the extended service you also earn money for every stamped e-mail you receive. To be able to give '
                        . 'you this service we need your email password. Upgrade to extended service '
                        . '<a class="alert-link" style="text-decoration: underline;" href="' .Yii::app()->createUrl('usermailbox/update') 
                        .'&email=' .$model->useremail .'">here</a></div>');
                echo CJSON::encode(array( 'signupcomplete' => true, 'redirectUrl' => CController::createUrl('site/index')));
                Yii::app()->end();
            }
        }
        $this->redirect($this->createUrl('site/index')); 
    }
    
    public function actionGetEmailServerParams($email) {
        list(, $maildomain) = explode("@", mb_convert_case($email, MB_CASE_LOWER, "UTF-8"));
        $registereddomain = mailconfig::model()->find('maildomain=:1', array(':1'=>$maildomain));
        if ($registereddomain !== NULL) {
            echo json_encode(array('incoming_hostname'=>$registereddomain->incoming_hostname, 
                            'incoming_port'=>$registereddomain->incoming_port, 
                            'incoming_socket_type'=>mb_convert_case($registereddomain->incoming_socket_type, MB_CASE_LOWER, "UTF-8")));
        }
        else echo "";
        Yii::app()->end();
    }
}
?>
