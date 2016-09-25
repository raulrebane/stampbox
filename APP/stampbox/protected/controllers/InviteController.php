<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class InviteController extends Controller
{
    public $layout = '//layouts/secure';
            
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
                            'actions'=>array('index', 'getprogress'),
                            'users'=>array('@'),
                    ),
                    array('deny',  // deny all users
                            'users'=>array('*'),
                    ),
            );
    }
        
    protected function beforeAction($action)
    {
        if(parent::beforeAction($action))
        {
            $log_line = new LogAction;
            $log_line->WriteLog(CVarDumper::dumpAsString($_GET) .' ' .CVarDumper::dumpAsString($_POST));
            return true;
        }
    }

    public function actionIndex() {
       
        if (isset($_POST['invite']) && isset($_POST['selectedIds'])) {
            foreach ($_POST['selectedIds'] as $id) {
                $invite = Invitations::model()->find('customer_id=:1 and invited_email=:2', 
                                    array(':1'=>Yii::app()->user->getId(), ':2'=>$id));
                $invite->invite = 'Y';
                $invite->save();
            }
            $this->redirect(array('invite/index'));
        }

        if(isset($_POST['invited_email']))
	{  
            Yii::log('got invite email' .$_POST['e_mail'], 'info', 'application');
            $model = new Invitations;
            $model->invited_email =$_POST['invited_email'];
            $model->customer_id = Yii::app()->user->getId();
            if ($model->validate())
            {
                $model->save();
            }
            Yii::log('Invitation save errors: ' .CVarDumper::dumpAsString($model), 'info', 'application');
            $this->redirect(array('invite/index'));
        }        
        if(isset($_POST['refresh'])) {
            Yii::log('Invitation refresh', 'info', 'application');
            $mbox = $_POST['usermailbox'];
            $email = $mbox['e_mail'];
            $mail = usermailbox::model()->find('customer_id=:1 and e_mail=:2', 
                                    array(':1'=>Yii::app()->user->getId(), ':2'=>mb_convert_case($email, MB_CASE_LOWER, "UTF-8")));
            if ($mail !== NULL) {
                $maildomain = mailconfig::model()->find('maildomain=:1', 
                                    array(':1'=>mb_convert_case($mail->maildomain, MB_CASE_LOWER, "UTF-8")));
                if ($maildomain !== NULL) {
                    $loadinvitations = json_encode(array('customer_id'=>$mail->customer_id,
                        'e_mail'=>$mail->e_mail,
                        'username'=>$mail->e_mail_username,
                        'password'=>$mail->e_mail_password,
                        'hostname'=>$maildomain->incoming_hostname,
                        'port'=>$maildomain->incoming_port,
                        'socket_type'=>$maildomain->incoming_socket_type,
                        'auth_type'=>$maildomain->incoming_auth));
                    $gmclient= new GearmanClient();
                    $gmclient->addServer(Yii::app()->params['gearman']['gearmanserver'], Yii::app()->params['gearman']['port']);
                    //$result = json_decode($gmclient->doNormal("loadinvitations", $loadinvitations),TRUE);
                    // implementing backgroung load of contacts
                    $jobhandle = $gmclient->doBackground("LoadInvitations", $loadinvitations);
                    $dbcommand = Yii::app()->db->createCommand();
                    $dbcommand->insert('ds.t_processing', array(
                        'customer_id' => Yii::app()->user->getId(),
                        'action' => 'LoadInvitations',
                        'task_id' => $jobhandle));
                    //yii::log("saved $jobhandle", 'info', 'application');
                }
            }
        }

        $model = new stdClass();
        $model->loading_inprogress = FALSE;
        
        // first let's check if there is currently loading from customer mailbox in progress
        $loadInProgress = Yii::app()->db->createCommand(array(
                    'select' => array('task_id'),
                    'from' => 'ds.t_processing',
                    'where' => "customer_id=:1 and action = 'LoadInvitations'",
                    'params' => array(':1' => Yii::app()->user->getId()),
                ))->queryRow();
        //yii::log('found loadinprogress ' .CVarDumper::dumpAsString($loadInProgress),'info', 'application');
        if ($loadInProgress !== FALSE) {
            $gmclient= new GearmanClient();
            $gmclient->addServer(Yii::app()->params['gearman']['gearmanserver'], Yii::app()->params['gearman']['port']);
            $invitationStatus = $gmclient->jobStatus($loadInProgress['task_id']);
            //yii::log('gearman task status ' .CVarDumper::dumpAsString($invitationStatus),'info', 'application');
            if ($invitationStatus[0] == FALSE) {
                $dbcommand = Yii::app()->db->createCommand();
                $dbcommand->delete('ds.t_processing', 'customer_id=:id', array(':id'=>Yii::app()->user->getId()));
            }
            else {
                $model->loading_inprogress = TRUE;
                $model->task_id = $loadInProgress['task_id'];
                $model->percent_complete = $invitationStatus[2];
            }
        }
        
        $sort = new CSort();
        $sort->attributes = array(
            'invited_email',
            'from_count',
            'name',
            'last_email_date',
        );
        $sort->defaultOrder=array('last_email_date'=>CSort::SORT_DESC);

        $model->dataProvider=new CActiveDataProvider('Invitations', array(
            'criteria'=>array('condition'=>'customer_id='.Yii::app()->user->getId()),
            'sort'=>$sort, 'pagination'=>array('pageSize'=>1000,)));
        
        $model->mailboxlist = new usermailbox;
        $useremails = usermailbox::model()->findAll("customer_id = :1 and status = 'A'", array(':1' => Yii::app()->user->getId()));
        if ($useremails) {
            $model->emailslist = CHtml::listData($useremails, 'e_mail', 'e_mail');
        }
        else {
            unset($model->emailslist);
        }
        //Yii::log('Invite model dump: ' .CVarDumper::dumpAsString($model), 'info', 'application');
        $this->render('index',array('model'=>$model,));
    }
    
    public function actionGetProgress($task_id) {
        if (Yii::app()->request->isAjaxRequest) {
            //yii::log('got ajax request', 'info', 'application');
            if ($task_id == 'test') {
                echo json_encode(array('done'=>date('s')));
            }
            else {
                $gmclient= new GearmanClient();
                $gmclient->addServer(Yii::app()->params['gearman']['gearmanserver'], Yii::app()->params['gearman']['port']);
                $invitationStatus = $gmclient->jobStatus($task_id);
                if ($invitationStatus[0] == FALSE) { $invitationStatus[2] = 100;}
                echo json_encode(array('done'=>$invitationStatus[2])); //to return value in ajax, simply echo it   
            }
        }
    }
}
?>
