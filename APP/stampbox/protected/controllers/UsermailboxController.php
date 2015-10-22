<?php

class UsermailboxController extends Controller
{

    public $layout='//layouts/secure';

    /**
    * @return array action filters
    */
    public function filters()
    {
        return array('accessControl');
    }

    /**
    * Specifies the access control rules.
    * This method is used by the 'accessControl' filter.
    * @return array access control rules
    */
    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                    'actions'=>array('create','update','index'),
                    'users'=>array('@')),
            array('deny',  // deny all users
                'users'=>array('*')),
        );
    }

    public function actionCreate()
    {
        $model=new NewMailbox;

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

        if(isset($_POST['NewMailbox']))
        {
            $model->attributes=$_POST['NewMailbox'];
            if($model->save())
                $this->redirect(array('usermailbox/index'));
        }

        $this->render('create',array('model'=>$model,));
    }

    public function actionUpdate($email)
    {
        $model = usermailbox::model()->find('e_mail=:email AND customer_id=:customer_id', array(':email'=>$email, 'customer_id'=>Yii::App()->user->getId()));
	//$model=$this->loadModel($email);

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

        if(isset($_POST['usermailbox']))
        {
            $model->attributes=$_POST['usermailbox'];
            if($model->save())
                $this->redirect(array('usermailbox/index'));
            else {
                Yii::log('Update customer mailbox failed' .CVarDumper::dumpAsString($model), 'info', 'application');
            }
        }
        $this->render('update',array('model'=>$model,));
    }

    public function actionIndex()
    {
        $dataProvider=new CActiveDataProvider('usermailbox',array(
            'criteria'=>array('condition'=>'customer_id=' .Yii::App()->user->getId())));
        $this->render('index',array('dataProvider'=>$dataProvider));
    }

    /**
    * Returns the data model based on the primary key given in the GET variable.
    * If the data model is not found, an HTTP exception will be raised.
    * @param integer the ID of the model to be loaded
    */
    public function loadModel($id)
    {
        $model=usermailbox::model()->findByPk($id);
        if($model===null OR $model->customer_id <> Yii::App()->user->getID())
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
    * Performs the AJAX validation.
    * @param CModel the model to be validated
    */
    protected function performAjaxValidation($model)
    {
    if(isset($_POST['ajax']) && $_POST['ajax']==='usermailbox-form')
    {
    echo CActiveForm::validate($model);
    Yii::app()->end();
    }
    }
}
