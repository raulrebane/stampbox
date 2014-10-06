<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class InviteController extends Controller
{
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
                            'actions'=>array('index'),
                            'users'=>array('@'),
                    ),
                    array('deny',  // deny all users
                            'users'=>array('*'),
                    ),
            );
    }
        
    public function actionIndex() {
        if (isset($_POST['invite']) && isset($_POST['selectedIds'])) {
            foreach ($_POST['selectedIds'] as $id) {
                $invite = Invitations::model()->find('customer_id=:1 and invited_email=:2', 
                                    array(':1'=>Yii::app()->user->getId(), ':2'=>$id));
                $invite->invite = 'Y';
                $invite->save();
            }
            $this->redirect(array('site/index'));
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
                    $gmclient->addServer("127.0.0.1", 4730);
                    $result = json_decode($gmclient->do("loadinvitations", $loadinvitations),TRUE);
                }
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

        $dataProvider=new CActiveDataProvider('Invitations', array(
            'criteria'=>array('condition'=>'customer_id='.Yii::app()->user->getId()),
            'sort'=>$sort, 'pagination'=>array('pageSize'=>1000,)));
        $this->render('index',array('dataProvider'=>$dataProvider,));
    }
    
    public function actionRefresh() {
        
    }

}
?>
