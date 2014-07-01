<?php

class SiteController extends Controller
{
    /**
    * @var string the default layout for the views. Defaults to '//layouts/main', meaning
    * using  layout. See 'protected/views/layouts/main.php'.
    */
    public $layout='//layouts/main';

    
        /**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}
        
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('resetpasswrd, Checktoken, Newpasswd'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','invite','update','changepsw, Logout'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('raulrebane71@gmail.com'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
            if (Yii::app()->user->isGuest) { $this->redirect(array('site/login')); }
            $this->render('dashboard');
	}

        
	public function actionInvite()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}
        
        public function actionresetpasswd() {
            $this->layout = 'login';
            $model = new ResetPasswd();
            if(isset($_POST['ResetPasswd']))
		{
                    $model->attributes=$_POST['ResetPasswd'];
                    //Yii::log('Reset password for ' .$model->emailaddress, 'info', 'application');
                    $usernames = Yii::app()->db->createCommand(array('select'=> array('customer_id', 'e_mail'),
                        'from' => 'ds.t_customer_mailbox',
                        'where'=> 'e_mail = :1',
                        'params' => array(':1'=>$model->emailaddress),))->queryRow();
                    Yii::log('e-mail search returned: ' .$usernames['customer_id'], 'info', 'application');
                    if ($usernames <> FALSE) {
                        $alreadyreset = Yii::app()->db->createCommand(array('select'=> array('customer_id'),
                        'from' => 'ds.t_passwdresets',
                        'where'=> 'customer_id = :1',
                        'params' => array(':1'=>$usernames['customer_id']),))->queryRow();
                        $command = Yii::app()->db->createCommand();
                        $model->resettoken = Yii::app()->SecurityManager->generateRandomString(32, TRUE);
                        if ($alreadyreset == FALSE) {
                            $command->insert('ds.t_passwdresets', array('customer_id'=>$usernames['customer_id'],
                                'e_mail'=>$usernames['e_mail'], 'token'=>$model->resettoken,
                                'sent'=>'now()'));
                        }
                        else {
                            $command->update('ds.t_passwdresets', array('customer_id'=>$usernames['customer_id'],
                                'e_mail'=>$usernames['e_mail'], 'token'=>$model->resettoken,
                                'sent'=>'now()'), 'customer_id=:id', array(':id'=>$usernames['customer_id']));
                        }
			//$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
			$subject='=?UTF-8?B?'.base64_encode('StampBox.eu password reset requested').'?=';
			$headers="From: Stampbox admin <admin@stampbox.eu>\r\n".
				"Reply-To: no-reply@stampbox.eu\r\n".
				"MIME-Version: 1.0\r\n".
				"Content-type: text/plain; charset=UTF-8";
                        $body = "your password reset link " .Yii::app()->createAbsoluteUrl('site/checktoken') ."&resettoken=" .$model->resettoken;
			mail($model->emailaddress,$subject,$body,$headers);
                        $model->notified = TRUE;
                    }
                }	
            $this->render('resetpasswd',array('model'=>$model));
        } 
        
        public function actionChecktoken() {
            Yii::log('Checktoken begins', 'info', 'application');
            $this->layout = 'login';
            $model = new ResetPasswd();

            if (isset($_GET['resettoken'])) {
                $model->resettoken = Yii::app()->format->text( $_GET['resettoken'] );
                $tokenexists = Yii::app()->db->createCommand(array('select'=> array('customer_id'),
                        'from' => 'ds.t_passwdresets',
                        'where'=> 'token = :1',
                        'params' => array(':1'=>$model->resettoken),))->queryRow();
                if ($tokenexists == FALSE) {
                    $this->redirect($this->createUrl('index'));
                }
                else {
                    //$customer= new TCustomer();
                    //$customer->loadModel($tokenexists['customer_id']);
                    Yii::app()->session['uid'] = $tokenexists['customer_id'];
                    $this->redirect(array('site/newpasswd'));
                    //Yii::app()->end();
                }
            }
        }
        
        public function actionNewpasswd() {
            $this->layout = 'login';
            $model = new ResetPasswd();
            if(isset($_POST['ResetPasswd']))
		{
                    YII::log('Resetpasswd set for customer ' .Yii::app()->session['uid'], 'info', 'application');
                    $model->attributes=$_POST['ResetPasswd'];
                    //$cust = new TCustomer();
                    //$cust->findByPk($_GET['uid']);
                    //$cust->password = crypt($model->newpassword, TCustomer::blowfishSalt());
                    $command = Yii::app()->db->createCommand();
                    $command->update('ds.t_customer', array('password'=>crypt($model->newpassword, TCustomer::blowfishSalt())),
                                'customer_id=:id', array(':id'=>Yii::app()->session['uid']));
                    //if ($cust->save()) {
                        $this->redirect($this->createUrl('site/login')); 
                    //}
                    //else {
                    //    $this->redirect($this->createUrl('index')); 
                    //}
                        
                }          
            $this->render('newpasswd', array('model'=>$model));
            }
        
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
            $this->layout = 'login';
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}