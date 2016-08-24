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
                    $result = json_decode($gmclient->doBackground("loadinvitations", $loadinvitationdata),TRUE);
                }
                echo CJSON::encode(array( 'signupcomplete' => true, 'redirectUrl' => CController::createUrl('site/index')));
                Yii::app()->end();
            }
        }
        $this->redirect($this->createUrl('site/index')); 
    }
    
}
?>
