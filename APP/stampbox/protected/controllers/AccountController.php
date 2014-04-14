<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AccountController extends Controller
{
    
    public function actionStatement() 
       {
        $model = new Account();
        $model->statement_grid = Yii::app()->db->createCommand(array(
            'select'=> array('*'),
            'from' => 'ds.t_stamps_transactions',
            'where'=> 'customer_id=:1 and transaction_date < :3',
            'order'=> 'transaction_date desc',
            'params' => array(':1'=>Yii::app()->user->getId(), ':3'=>Yii::app()->dateFormatter->format('yyyy/MM/dd', time())
                )))->queryAll();
        $this->render('Statement',array('model'=>$model,)); 
       }
    
       
       
    public function actionBalance() 
       {
        
       }       
}