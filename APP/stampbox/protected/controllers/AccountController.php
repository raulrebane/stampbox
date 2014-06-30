<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AccountController extends Controller
{
    public function accessRules()
    {
	return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
		'actions'=>array('Statement','Balance'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
        }
    public function actionStatement() 
       {
        $model = new Account();
        $model->statement_grid = Yii::app()->db->createCommand(array(
            'select'=> array('transaction_id', 'customer_id', 'amount', 'transaction_date', 'from_email', 'to_email', 'subject'),
            'from'=> 'ds.v_transactions',
            'where'=> 'customer_id = :1',
            'order'=> 'transaction_date desc',
            'limit'=> '1000',
            'params'=> array(':1'=>Yii::app()->user->getId()),
        ))->queryAll();
        $this->render('Statement',array('model'=>$model,)); 
       }
    
       
       
    public function actionBalance() 
       {
        
       }       
}