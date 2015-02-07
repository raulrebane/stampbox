<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AccountController extends Controller
{
    public $layout='//layouts/secure';    
    
    public function filters()
    {
        return array('accessControl');
    }
    
    public function accessRules()
    {
	return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
		'actions'=>array('Statement','Balance'),
                    'users'=>array('@')),
            array('deny',  // deny all users
                'users'=>array('*')),
	);
    }
     
    public function actionStatement() 
       {
        $model = new Account();
        if (isset($_GET['period'])) {
            switch ($_GET['period']) {
                case 'today':
                    $model->from_date = Yii::app()->dateFormatter->format('yyyy/MM/dd',date('d-m-Y'));
                    $model->to_date = Yii::app()->dateFormatter->format('yyyy/MM/dd',date('d-m-Y'));
                    break;
                case 'yesterday':
                    $model->from_date = Yii::app()->dateFormatter->format('yyyy/MM/dd',date('d-m-Y',strtotime("-1 days")));
                    $model->to_date = Yii::app()->dateFormatter->format('yyyy/MM/dd',date('d-m-Y',strtotime("-1 days")));
                    break;
                case 'thisweek':
                    $model->from_date = Yii::app()->dateFormatter->format('yyyy/MM/dd',date("d-m-Y",strtotime('monday this week')));
                    $model->to_date = Yii::app()->dateFormatter->format('yyyy/MM/dd',date("d-m-Y",strtotime('sunday this week')));
                    break;
                case 'lastweek':
                    $model->from_date = Yii::app()->dateFormatter->format('yyyy/MM/dd',date("d-m-Y",strtotime('monday last week')));
                    $model->to_date = Yii::app()->dateFormatter->format('yyyy/MM/dd',date("d-m-Y",strtotime('sunday last week')));
                    break;
                case 'thismonth':
                    $model->from_date = Yii::app()->dateFormatter->format('yyyy/MM/dd',date('m-01-Y'));
                    $model->to_date = Yii::app()->dateFormatter->format('yyyy/MM/dd',date('m-t-Y'));
                    break;
                case 'lastmonth':
                    $model->from_date = Yii::app()->dateFormatter->format('yyyy/MM/dd',mktime(0, 0, 0, date("m")-1, 1, date("Y")));
                    $model->to_date = Yii::app()->dateFormatter->format('yyyy/MM/dd',date('Y-m-d', strtotime('last day of last month')));
                    break;
                //default :
            }
            Yii::log('Statement for $model->from_date , $model->to_date', 'info', 'application');
            $model->statement_grid = Yii::app()->db->createCommand(array(
                //'select'=> array('transaction_id', 'customer_id', 'amount', 'transaction_date', 'description', 'e_mail', 'subject'),
                'select'=> array('*'),
                'from'=> 'ds.v_transactions',
                'where'=> 'customer_id = :1 and transaction_date between :2 and :3',
                'order'=> 'transaction_date desc',
                'limit'=> '1000',
                'params'=> array(':1'=>Yii::app()->user->getId(), ':2'=>$model->from_date, ':3'=>$model->to_date),
            ))->queryAll();
            $this->render('Statement',array('model'=>$model,)); 
            Yii::app()->end();
        }
        if (isset($_POST['Account'])) {
            $model->attributes=$_POST['Account'];
            if ($model->from_date == '') { $model->from_date = Yii::app()->dateFormatter->format('yyyy/MM/dd',date('d-m-Y')); }
            if ($model->to_date == '') { $model->to_date = Yii::app()->dateFormatter->format('yyyy/MM/dd',date('d-m-Y')); }
            //Yii::log('Received statement attributes', 'info', 'application');
            $model->statement_grid = Yii::app()->db->createCommand(array(
                //'select'=> array('transaction_id', 'customer_id', 'amount', 'transaction_date', 'description', 'e_mail', 'subject'),
                'select'=> array('*'),
                'from'=> 'ds.v_transactions',
                'where'=> 'customer_id = :1 and transaction_date between :2 and :3',
                'order'=> 'transaction_date desc',
                'limit'=> '1000',
                'params'=> array(':1'=>Yii::app()->user->getId(), ':2'=>$model->from_date, ':3'=>$model->to_date),
            ))->queryAll();           
        } else 
            $model->statement_grid = Yii::app()->db->createCommand(array(
                //'select'=> array('transaction_id', 'customer_id', 'amount', 'transaction_date', 'description', 'e_mail', 'subject'),
                'select'=> array('*'),
                'from'=> 'ds.v_transactions',
                'where'=> 'customer_id = :1',
                'order'=> 'transaction_date desc',
                'limit'=> '1000',
                'params'=> array(':1'=>Yii::app()->user->getId()),
            ))->queryAll();
        $this->render('Statement',array('model'=>$model,)); 
       }
}