<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class SignupController extends Controller
{
    public function actionIndex() {
        $this->redirect(array('signup/step1'));     
    }
    
    public function actionStep1() {
        $this->layout = 'register';
        $model = new Signup();
        $model->scenario = 'Step1';
        if(Yii::app()->getRequest()->getIsAjaxRequest()) {
            $model->attributes=$_POST['Signup'];
            //Yii::log("Ajax validation activated: " .$model->useremail, 'info', 'application');
            echo CActiveForm::validate($model); 
            Yii::app()->end(); 
        }
        if(isset($_POST['Signup'])) {  
            $model->attributes=$_POST['Signup'];     
            if ($model->validate()) {
                Yii::log("Step1 signup save: " .$model->useremail, 'info', 'application');
                $model->Save('Step1');
                $this->redirect(array('signup/step2')); 
            }
        }
        $this->render('Step1',array('model'=>$model,)); 
    }
    
    public function actionStep2() {
        $this->layout = 'register';        
        $model = new Signup();
        $model->scenario = 'Step2';
        $model->sendingservice = 1;
        $model->registeredemail = usermailbox::model()->find('customer_id=:1 and e_mail=:2', 
                    array(':1'=>Yii::app()->user->getId(), ':2'=>Yii::app()->user->username));
        if ($model->registeredemail == NULL) {
            Yii::log('In Step2, e-mail record not found: ' .Yii::app()->user->username, 'info', 'application');
            $this->redirect(array('site/index'));
        }
        if(isset($_POST['Signup'])) {  
            $model->attributes=$_POST['Signup'];     
            if ($model->validate()) {
                Yii::log("Step2 signup save: " .$model->useremail, 'info', 'application');
                $model->Save('Step2');
                $this->redirect(array('signup/step3')); 
            }
        }        
        $this->render('Step2',array('model'=>$model));
    }

    public function actionStep3() {
        $this->layout = 'register';        
        $model = new Signup();
        $model->useremail = Yii::app()->user->name;
        list(, $model->maildomain) = explode("@", $model->useremail);
        $model->mailtype = 'IMAP';
        $model->registeredemail = new usermailbox();
        $model->registeredemail->maildomain = mb_convert_case($model->maildomain, MB_CASE_LOWER, "UTF-8");
        $model->registereddomain = mailconfig::model()->find('maildomain=:1', array(':1'=>$model->registeredemail->maildomain));
        if ($model->registereddomain !== NULL) {
            $model->incoming_hostname = $model->registereddomain->incoming_hostname;
            $model->incoming_port = $model->registereddomain->incoming_port;
            $model->incoming_socket_type = $model->registereddomain->incoming_socket_type;
            switch ($model->registereddomain->incoming_auth == 'USERNAME') {
            case 'EMAIL':
                $model->emailusername = $model->useremail;
                break;
            case 'USERNAME':
                list($model->emailusername,) = explode("@", $model->useremail);
                break;
            }
        }
        $model->scenario = 'Step3';
        if(isset($_POST['Signup'])) {  
            Yii::log("Step3 debug: " .Yii::app()->user->name, 'info', 'application');
            $model->attributes=$_POST['Signup'];     
            if ($model->validate()) {
                Yii::log("Step3 signup save: " .Yii::app()->user->name, 'info', 'application');
                $model->Save('Step3');
                $this->redirect(array('site/index')); 
            }
            else {
                Yii::log("Step3 validation error: " .CVarDumper::dumpAsString($model->getErrors()), 'info', 'application');
            }
        }
        $this->render('Step3',array('model'=>$model));
    }

    public function actionStep4() {
        $this->layout = 'register';        
        $model = new Signup();
        $model->scenario = 'Step4';
        //$model->useremail = Yii::app()->user->name;
        //list(, $model->maildomain) = explode("@", $model->useremail);
        $model->registeredemail = usermailbox::model()->find('customer_id=:1 and e_mail=:2', 
                    array(':1'=>Yii::app()->user->getId(), ':2'=>Yii::app()->user->username));
        if ($model->registeredemail == NULL) {
            Yii::log('In Step4, e-mail record not found: ' .Yii::app()->user->username, 'info', 'application');
            $this->redirect(array('site/index'));
        }
        if(isset($_POST['Signup'])) {  
            $model->attributes=$_POST['Signup'];     
            if ($model->validate()) {
                Yii::log("Step4 services save: " .Yii::app()->user->name, 'info', 'application');
                $model->Save('Step4');
                $this->redirect(array('site/index')); 
            }
        }
        $this->render('Step4',array('model'=>$model));
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
