<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class WhitelistController extends Controller
{
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
                        'actions'=>array('index','autocomplete'),
                        'users'=>array('@'),
                ),
                array('deny',  // deny all users
                        'users'=>array('*'),
                ),
        );
    }
        
    public function actionIndex() 
    {

        $model = new Whitelist();
        
        if(isset($_POST['e_mail']))
	{  
            Yii::log('got email' .$_POST['e_mail'], 'info', 'application');
            $model->e_mail=$_POST['e_mail'];
            $model->customer_id = Yii::app()->user->getId();
            if ($model->validate())
            {
                $model->save();
            }
        }
        $dataProvider = new CActiveDataProvider('Whitelist', array('pagination'=>array('pageSize'=>100,)));
        $this->render('index',array('model'=>$model, 'dataProvider'=>$dataProvider,)); 
    }
    
    public function actionAutocomplete() 
   {
    $res =array();

        if (isset($_GET['term'])) {
            // http://www.yiiframework.com/doc/guide/database.dao
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
