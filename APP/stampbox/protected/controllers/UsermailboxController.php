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
                    'actions'=>array('create', 'step2', 'update','index'),
                    'users'=>array('@')),
            array('deny',  // deny all users
                'users'=>array('*')),
        );
    }

    public function actionCreate()
    {
        $model=new NewMailbox;
        $model->scenario = 'Step1';
        if(Yii::app()->getRequest()->getIsAjaxRequest()) {
            $model->attributes=$_POST['NewMailbox'];
            //Yii::log("Ajax validation activated: " .$model->useremail, 'info', 'application');
            echo CActiveForm::validate($model); 
            Yii::app()->end(); 
        }
        if(isset($_POST['NewMailbox']))
        {
            $model->attributes=$_POST['NewMailbox'];
            if ($model->validate()) {
                Yii::log("New e-mail step1 save: " .$model->useremail, 'info', 'application');
                $model->Save('Step1');
                if ($model->receivingservice == 1 or $model->sortingservice == 1) {
                    Yii::app()->session['newemail'] = $model->useremail;
                    $this->redirect(array('usermailbox/step2'));
                }
                $this->redirect(array('usermailbox/index'));
            }
        }
        $this->render('step1',array('model'=>$model,));
    }

    public function actionStep2()
    {
        if (!isset(Yii::app()->session['newemail'])) {
            $this->redirect(array('usermailbox/index'));
        }
        $model=new NewMailbox;
        $model->scenario = 'Step2';
        $model->useremail = Yii::app()->session['newemail']; 
        $model->registeredemail = usermailbox::model()->find('customer_id=:1 and e_mail=:2', 
                    array(':1'=>Yii::app()->user->getId(), ':2'=>$model->useremail));
        if ($model->registeredemail == NULL) {
            // how did we got here at all?
            Yii::log('In Step2, '.Yii::app()->user->getId() .' ' .Yii::app()->user->username 
                        .' missing user mailbox record' , 'info', 'application');
                // should redirect to site/index
            $this->redirect(array('usermailbox/index'));
        }
        $model->registereddomain = mailconfig::model()->find('maildomain=:1', array(':1'=>$model->registeredemail->maildomain));
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
        if(Yii::app()->getRequest()->getIsAjaxRequest()) {
            $model->attributes=$_POST['NewMailbox'];
            //Yii::log("Ajax validation activated: " .$model->useremail, 'info', 'application');
            echo CActiveForm::validate($model); 
            Yii::app()->end(); 
        }
        if(isset($_POST['NewMailbox']))
        {
            $model->attributes=$_POST['NewMailbox'];
            if ($model->validate()) {
                Yii::log("New e-mail step2 save: " .$model->useremail, 'info', 'application');
                $model->Save('Step2');
                Yii::app()->session->remove('newemail');
                $this->redirect(array('usermailbox/index'));
            }
        }
        $this->render('step2',array('model'=>$model,));
    }

    public function actionUpdate($email)
    {
        $model = usermailbox::model()->find('e_mail=:email AND customer_id=:customer_id', array(':email'=>$email, 'customer_id'=>Yii::App()->user->getId()));
        $model=new NewMailbox;
        $model->scenario = 'Update';
        $model->registeredemail = usermailbox::model()->find('customer_id=:1 and e_mail=:2',
                    array(':1'=>Yii::app()->user->getId(), ':2'=>$email));
        if ($model->registeredemail == NULL) {
            // how did we got here at all?
            Yii::log('In e-mail update, '.Yii::app()->user->getId() .' ' .$email
                        .' missing user mailbox record' , 'info', 'application');
                // should redirect to site/index
            $this->redirect(array('usermailbox/index'));
        }
        else {
           Yii::app()->session['updateemail'] =  $email;
           $model->emailusername = $model->registeredemail->e_mail_username;
           $model->emailpassword = $model->registeredemail->e_mail_password;
        }
        $model->registereddomain = mailconfig::model()->find('maildomain=:1', array(':1'=>$model->registeredemail->maildomain));
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
	//$model=$this->loadModel($email);

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

        if(isset($_POST['usermailbox']))
        {
            $model->attributes=$_POST['usermailbox'];
            if ($model->validate()) {
                Yii::log("e-mail update save: " .$model->useremail, 'info', 'application');
                $model->Save('Update');
		Yii::app()->session->remove('updateemail');
                $this->redirect(array('usermailbox/index'));
	    }
            else {
                Yii::log('Update customer mailbox failed' .CVarDumper::dumpAsString($model), 'info', 'application');
            }
        }
        $this->render('update',array('model'=>$model,));
    }

    public function actionIndex()
    {
        $dataProvider=new CActiveDataProvider('usermailbox',array(
            'criteria'=>array('condition'=>'customer_id=' .Yii::App()->user->getId())));
        $this->render('index',array('dataProvider'=>$dataProvider));
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
