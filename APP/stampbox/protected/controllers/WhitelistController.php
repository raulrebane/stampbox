<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class WhitelistController extends Controller
{
    public $layout = '//layouts/secure';
    
    public function filters()
    {
        return array(
            'accessControl', // perform access control for operations
        );
    }


    public function accessRules()
    {
        return array(
                array('allow', // allow authenticated user to perform 'create' and 'update' actions
                        'actions'=>array('index','delete','autocomplete'),
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

    public function actionIndex() 
    {
        Yii::log('Whitelist - index', 'info', 'application');
        
        if (isset($_POST['whitelist']) && isset($_POST['selectedIds'])) {
            $whitelistcount = 0;
            foreach ($_POST['selectedIds'] as $id) {
                $add2whitelist = Whitelist::model()->find('customer_id=:1 and e_mail=:2', 
                                    array(':1'=>Yii::app()->user->getId(), ':2'=>$id));
                if ($add2whitelist) { continue; }
                $add2whitelist = new Whitelist;
                $add2whitelist->customer_id = Yii::app()->user->getId();
                $add2whitelist->e_mail = $id;
                $add2whitelist->save();
                $whitelistcount += 1;

            }
            Yii::app()->user->setFlash('success',
                '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
                .$whitelistcount .' e-mails added to whitelist. They can now send you e-mail without stamps needed'); 
            //$this->redirect(array('site/index'));
        }
        
        if(isset($_POST['e_mail']) && isset($_POST['whitelistemail']))
	{  
            //Yii::log('got email' .$_POST['e_mail'], 'info', 'application');
            $model = Whitelist::model()->find('customer_id=:1 and e_mail=:2', 
                                    array(':1'=>Yii::app()->user->getId(), ':2'=>$_POST['e_mail']));
            if ($model == NULL) {
                $model = new Whitelist();
                $model->e_mail=$_POST['e_mail'];
                $model->customer_id = Yii::app()->user->getId();
                if ($model->validate())
                {
                    $model->save();
                    Yii::app()->user->setFlash('success',
                        '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
                        .$model->e_mail .' added to whitelist.'); 
                }
            }
            else {
                    Yii::app()->user->setFlash('info',
                        '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'
                        .$model->e_mail .' is already whitelisted.'); 
            }
        }
        $model = new Whitelist();
        $dataProvider = new CActiveDataProvider('Whitelist', array('pagination'=>array('pageSize'=>100,)));
        $this->render('index',array('model'=>$model, 'dataProvider'=>$dataProvider,)); 
    }

    public function actionDelete($email) 
    {
        $model = Whitelist::model()->find('e_mail=:email', array(':email'=>$email));
        $model->delete();
        $this->redirect(array('whitelist/index'));
    }
    
    public function actionAutocomplete() 
   {
    $res =array();

        if (isset($_GET['term'])) {
            $qtxt ="SELECT distinct(invited_email) FROM ds.t_invitations WHERE invited_email LIKE :username";
            $command =Yii::app()->db->createCommand($qtxt);
            $command->bindValue(":username", '%'.$_GET['term'].'%', PDO::PARAM_STR);
            $res =$command->queryColumn();
        }
        echo CJSON::encode($res);
        Yii::app()->end();
   }    

}
?>
