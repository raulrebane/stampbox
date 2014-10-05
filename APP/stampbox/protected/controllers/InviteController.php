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
        if (isset($_POST['selectedIds'])) {
            foreach ($_POST['selectedIds'] as $id) {
                $invite = Invitations::model()->find('customer_id=:1 and invited_email=:2', 
                                    array(':1'=>Yii::app()->user->getId(), ':2'=>$id));
                $invite->invite = 'Y';
                $invite->save();
            }
            $this->redirect(array('site/index'));
        }
        if(isset($_POST['refresh'])) {
            
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
