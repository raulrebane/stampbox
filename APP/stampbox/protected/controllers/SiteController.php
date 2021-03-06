<?php

class SiteController extends Controller
{
    /**
    * @var string the default layout for the views. Defaults to '//layouts/main', meaning
    * using  layout. See 'protected/views/layouts/main.php'.
    */
    public $layout='//layouts/main';

        public function filters()
        {
            return array(
                'accessControl', // perform access control for CRUD operations
            );
        }
        
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('invite','update','changepsw', 'logout', 'index', 'intro', 'closeAccount', 'closeMessage'),
				'users'=>array('@'),
			),
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','resetpasswd', 'checktoken', 'newpasswd', 'login', 'help', 'pricing', 'terms', 'error'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
        
        // this method is called before any controller action is performed
        // you may place customized code here
        protected function beforeAction($action)
        {
            if(parent::beforeAction($action))
            {
                $log_line = new LogAction;
                $log_line->WriteLog(CVarDumper::dumpAsString($_GET) .' ' .CVarDumper::dumpAsString($_POST));
                if (Yii::app()->user->isGuest) 
                    { $this->layout = 'public'; }
                else 
                    { $this->layout = 'secure'; }
                return true;
            }
            else
            return false;
        }

        /**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
            if (Yii::app()->user->isGuest) 
                {   //$this->layout = 'public';
                    $this->render('index'); }
            else 
                {   //$this->layout = 'secure';
//                    $brokenmailboxes = Yii::app()->db->createCommand(array(
//                    'select'=> array('*'),
//                    'from'=> 'ds.t_customer_mailbox',
//                    'where'=> "customer_id = :1 AND receiving_service = FALSE",
//                    'params'=> array(':1'=>Yii::app()->user->getId()),
//                    ))->queryAll();
//                    if ($brokenmailboxes) {
//                        foreach ($brokenmailboxes as $mailbox) {
//                            //echo CVarDumper::dumpAsString($mailbox);
//                            $errortext = 'Your are not receiving credits for Stamped e-mails for you e-mail' .$mailbox['e_mail'];
//                            $errortext = $errortext . '<br><a class="btn btn-aqua" href="' .Yii::app()->createUrl('usermailbox/update') 
//                                    .'&email=' .$mailbox['e_mail'] .'">Setup your e-mail</a>';
//                            Yii::app()->user->setFlash('info', $errortext); 
//                        }
//                    }
                    $this->render('dashboard'); }
	}

        public function actionCloseAccount() {
            $command = Yii::app()->db->createCommand('SELECT * FROM ds.clear_data();');
            $command->queryRow();            
            Yii::app()->user->logout();
            $this->redirect(Yii::app()->homeUrl);
            
        }
        
	public function actionInvite()
	{   
		$this->render('index');
	}
        
        public function actionPricing()
	{
            $this->render('pricing');
	}
        
        public function actionTerms()
	{	
            $this->render('terms');
	}
        
        public function actionHelp()
	{
            $this->render('help');
	}
        
        public function actionResetPasswd() {
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
                        $sendmailparams = json_encode(array(
                                    'outgoing_hostname'=>'smtp.googlemail.com',
                                    'outgoing_port'=>'465',
				    'outgoing_socket_type'=>'ssl',
                                    'e_mail_username'=>'support@stampbox.email',
                                    'e_mail_password'=>'Tere1Tere2',
                                    'subject'=>'www.stampbox.email password reset requested',
                                    'from'=>'support@stampbox.email',
                                    'fromname'=>'Stampbox support',
                                    'to'=>$model->emailaddress,
                                    'toname'=>'',
                                    'body'=> "your password reset link " .Yii::app()->createAbsoluteUrl('site/checktoken') ."&resettoken=" .$model->resettoken,
                                ));
                        $gmclient= new GearmanClient();
                        $gmclient->addServer(Yii::app()->params['gearman']['gearmanserver'], Yii::app()->params['gearman']['port']);
                        $result = json_decode($gmclient->doNormal("Sendpasswdlink", $sendmailparams),TRUE);
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
                        $this->redirect($this->createUrl('site/index')); 
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
                    $model->attributes=$_POST['LoginForm'];
                    Yii::log("Login: " .$model->username .' ' .$model->password , 'info', 'application');
                    $errors = CActiveForm::validate($model);
                    if ($errors != '[]')
                        {
                            echo $errors;
                            Yii::app()->end();
                        }
                }

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
                            if(isset($_POST['ajax'])) {
                                echo CJSON::encode(array( 'authenticated' => true,
                                    'redirectUrl' => CController::createUrl('site/index')));
                                Yii::app()->end();
                            }
                            else {
                                $this->redirect(CController::createUrl('site/index'));
                            }
                            //$this->redirect(Yii::app()->user->returnUrl);
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

        public function actionCloseMessage($message_id) {
            $message = Message::model()->find('customer_id=:1 and message_id=:2', 
                                    array(':1'=>Yii::app()->user->getId(), ':2'=>$message_id));
            if ($message) {
                $message->delete();
            }
        }
        
}
