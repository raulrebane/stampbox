<?php

class UsermailboxController extends Controller
{

    public $layout='//layouts/secure';

    /**
    * @return array action filters
    */
    public function filters()
    {
        return array('accessControl');
    }

    /**
    * Specifies the access control rules.
    * This method is used by the 'accessControl' filter.
    * @return array access control rules
    */
    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                    'actions'=>array('create', 'update','index', 'delete'),
                    'users'=>array('@')),
            array('deny',  // deny all users
                'users'=>array('*')),
        );
    }

//    public function actionNew($e_mail)
//    {
//        
//    }
    
    protected function beforeAction($action)
    {
        if(parent::beforeAction($action))
        {
            $log_line = new LogAction;
            $log_line->WriteLog(CVarDumper::dumpAsString($_GET) .' ' .CVarDumper::dumpAsString($_POST));
            return true;
        }
    }

    public function actionCreate() {
        $model=new NewMailbox;
        $model->scenario = 'Create';
        if(Yii::app()->getRequest()->getIsAjaxRequest()) {
            $model->attributes=$_POST['NewMailbox'];
            //Yii::log("Ajax validation activated: " .$model->useremail, 'info', 'application');
            echo CActiveForm::validate($model); 
            Yii::app()->end(); 
        }

        if(isset($_POST['NewMailbox']) && isset($_POST['cancelbtn'])) {
            $this->redirect(array('usermailbox/index'));
        }

        if(isset($_POST['NewMailbox']))
        {
            $model->attributes=$_POST['NewMailbox'];
            if ($model->validate()) {
                $model->e_mail_verified = FALSE;
                $gmclient= new GearmanClient();
                $gmclient->addServer(Yii::app()->params['gearman']['gearmanserver'], Yii::app()->params['gearman']['port']);
                if ($model->receivingservice == 1 or $model->sortingservice == 1) {
                    $mailboxcheck = json_encode(array('e_mail'=>mb_convert_case($model->useremail, MB_CASE_LOWER, "UTF-8"),
                        'username'=>$model->emailusername,'password'=>$model->emailpassword,'hostname'=>$model->incoming_hostname,'port'=>$model->incoming_port,
                        'socket_type'=>$model->incoming_socket_type,'auth_type'=>$model->incoming_auth));
                    $result = json_decode($gmclient->doNormal("CheckMailbox", $mailboxcheck),TRUE);
                    if ($result['status'] == 'ERROR') {
                        Yii::app()->user->setFlash('danger',
                            '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
                            .'We could not access your e-mail inbox.<br>' 
                            .$result['reason']['0'] 
                            .'</div>'); 
                        $this->render('create',array('model'=>$model,));
                        Yii::app()->end();
                    } 
                    else { $model->e_mail_verified = TRUE;}
                }
                $result = json_decode($gmclient->doNormal("EncryptData", json_encode(array('opentext'=>$model->emailpassword))),TRUE);
                $model->emailpassword = $result['cryptedtext'];
                $model->Save('Create');
                Yii::app()->user->setFlash('success',
                    '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
                    .'New email '.$model->useremail .' saved successfully</div>');                 
                $this->redirect(array('usermailbox/index'));
            }
        }
        $this->render('create',array('model'=>$model,));
    }

    public function actionUpdate($email)
    {
        $model=new NewMailbox;
        $model->scenario = 'Update';
        $model->useremail = $email;
        $model->registeredemail = usermailbox::model()->find('customer_id=:1 and e_mail=:2',
                    array(':1'=>Yii::app()->user->getId(), ':2'=>$email));
        if ($model->registeredemail == NULL) {
            // how did we got here at all?
            Yii::log('In e-mail update, '.Yii::app()->user->getId() .' ' .$email
                        .' missing user mailbox record' , 'info', 'application');
                // should redirect to site/index
            Yii::app()->user->setFlash('danger',
                '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
                . 'E-mail not found</div>');
            $this->redirect(array('usermailbox/index'));
        }
        else {
            $model->registereddomain = mailconfig::model()->find('maildomain=:1', array(':1'=>$model->registeredemail->maildomain));
        }
        
        if(isset($_POST['NewMailbox']) && isset($_POST['cancelbtn'])) {
            $this->redirect(array('usermailbox/index'));
            Yii::app()->end();
        }

        if(isset($_POST['NewMailbox'])) {
            $model->attributes=$_POST['NewMailbox'];
            if ($model->validate()) {
                $gmclient= new GearmanClient();
                $gmclient->addServer(Yii::app()->params['gearman']['gearmanserver'], Yii::app()->params['gearman']['port']);
		if ($model->receivingservice == 1 OR $model->sortingservice == 1) {
                    if (substr($model->emailpassword,0,5) == 'SBPKI') {
                        $result = json_decode($gmclient->doNormal("DecryptData", json_encode(array('cryptedtext'=>$model->emailpassword))),TRUE);
                        $model->emailpassword = $result['opentext'];
                    }
                    if ($model->registeredemail->status == 'A' AND $model->emailpassword == $model->registeredemail->e_mail_password)  {
                        $model->e_mail_verified = TRUE; }
                    else {
                        $mailboxcheck = json_encode(array('e_mail'=>mb_convert_case($model->useremail, MB_CASE_LOWER, "UTF-8"),
                            'username'=>$model->emailusername,'password'=>$model->emailpassword,'hostname'=>$model->incoming_hostname,'port'=>$model->incoming_port,
                            'socket_type'=>$model->incoming_socket_type,'auth_type'=>$model->incoming_auth));
                        Yii::log('Update mailbox - verifying e-mail:' .CVarDumper::dumpAsString($mailboxcheck), 'info', 'application');
                        $result = json_decode($gmclient->doNormal("CheckMailbox", $mailboxcheck),TRUE);
                        if ($result['status'] == 'ERROR') {
                                //Changed to allow registering without e-mail username
                                Yii::app()->user->setFlash('danger',
                                    '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
                                    .'We could not access your e-mail inbox.<br>' 
                                    .$result['reason']['0'] 
                                    .'</div>'); 
                                $this->render('update',array('model'=>$model,));
                                Yii::app()->end();
                        } 
                        else { $model->e_mail_verified = TRUE;}
                    }
                }
                if (substr($model->emailpassword,0,5) <> 'SBPKI') {
                    $result = json_decode($gmclient->doNormal("EncryptData", json_encode(array('opentext'=>$model->emailpassword))),TRUE);
                    $model->emailpassword = $result['cryptedtext'];
                    Yii::log('Encypting password:' .CVarDumper::dumpAsString($result), 'info', 'application');
//                    $result = json_decode($gmclient->doNormal("DecryptData", json_encode(array('cryptedtext'=>$model->emailpassword))),TRUE);
//                    Yii::log('Decrypted password:' .CVarDumper::dumpAsString($result), 'info', 'application');
                }
                $model->Save('Update');
		//Yii::app()->session->remove('updateemail');
                Yii::app()->user->setFlash('success',
                    '<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
                    .'Updated configuration for '.$model->useremail .' saved</div>');                 
                $this->redirect(array('usermailbox/index'));
	    }
            else {
                //Yii::log('Update customer mailbox failed' .CVarDumper::dumpAsString($model), 'info', 'application');
                $this->render('update',array('model'=>$model,));
                Yii::app()->end();
            }
        }

        //Yii::app()->session['updateemail'] =  $email;
        $model->emailusername = $model->registeredemail->e_mail_username;
        $model->emailpassword = $model->registeredemail->e_mail_password;
        $model->maildomain = $model->registeredemail->maildomain;
        $model->receivingservice = $model->registeredemail->receiving_service;
        $model->sendingservice = $model->registeredemail->sending_service;
        $model->sortingservice = $model->registeredemail->sorting_service;
        if ($model->registereddomain !== NULL)  {
            $model->incoming_hostname = $model->registereddomain->incoming_hostname;
            $model->incoming_port = $model->registereddomain->incoming_port;
            $model->incoming_socket_type = $model->registereddomain->incoming_socket_type;
            switch ($model->registereddomain->incoming_auth) {
                case 'EMAIL':
                    $model->emailusername = $model->useremail;
                    break;
                case 'USERNAME':
                    list($model->emailusername,) = explode("@", $model->useremail);
                    break;
            }
        }

        $this->render('update',array('model'=>$model,));
        Yii::app()->end();
    }

    public function actionIndex()
    {
        $dataProvider=new CActiveDataProvider('usermailbox',array(
            'criteria'=>array('condition'=>'customer_id=' .Yii::App()->user->getId())));
        $this->render('index',array('dataProvider'=>$dataProvider));
    }

    public function actionDelete($email)
    {
        Yii::log('deleting mailbox: ' .$email, 'info', 'application');
        //if(Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($email)->delete();
            $this->redirect(array('usermailbox/index'));
        //}
        // else throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }

    /**
    * Returns the data model based on the primary key given in the GET variable.
    * If the data model is not found, an HTTP exception will be raised.
    * @param integer the ID of the model to be loaded
    */
    public function loadModel($id)
    {
        $model=usermailbox::model()->findByPk($id);
        if($model===null OR $model->customer_id <> Yii::App()->user->getID())
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
    * Performs the AJAX validation.
    * @param CModel the model to be validated
    */
    protected function performAjaxValidation($model)
    {
    if(isset($_POST['ajax']) && $_POST['ajax']==='usermailbox-form')
    {
    echo CActiveForm::validate($model);
    Yii::app()->end();
    }
    }
}
