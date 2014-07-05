<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class WhitelistController extends Controller
{
    
    public function actionIndex() 
       {
        $dataProvider = new CActiveDataProvider('Offers', array('pagination'=>array('pageSize'=>100,)));
        
        $this->render('index',array('dataProvider'=>$dataProvider,)); 
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
