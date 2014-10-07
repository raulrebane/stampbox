<?php

class UsermailboxController extends Controller
{
/**
* @var string the default layout for the views. Defaults to '//layouts/column2', meaning
* using two-column layout. See 'protected/views/layouts/column2.php'.
*/
public $layout='main';

/**
* @return array action filters
*/
public function filters()
{
return array(
'accessControl', // perform access control for CRUD operations
);
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
'actions'=>array('index','create','update'),
'users'=>array('@'),
),
array('allow', // allow admin user to perform 'admin' and 'delete' actions
'actions'=>array('admin','delete'),
'users'=>array('raulrebane71@gmail.com'),

),
array('deny',  // deny all users
'users'=>array('*'),
),
);
}

/**
* Displays a particular model.
* @param integer $id the ID of the model to be displayed
*/
public function actionView($id)
{
$this->render('view',array('model'=>$this->loadModel($id),
));
}

/**
* Creates a new model.
* If creation is successful, the browser will be redirected to the 'view' page.
*/
public function actionCreate()
{
$model=new usermailbox;

// Uncomment the following line if AJAX validation is needed
// $this->performAjaxValidation($model);

if(isset($_POST['usermailbox']))
{
$model->attributes=$_POST['usermailbox'];
list(, $model->maildomain) = explode("@", $model->e_mail); 
$model->customer_id = Yii::App()->user->getId();
if($model->save())
$this->redirect(array('usermailbox/index'));
}

$this->render('create',array('model'=>$model,));
}

/**
* Updates a particular model.
* If update is successful, the browser will be redirected to the 'view' page.
* @param integer $id the ID of the model to be updated
*/
public function actionUpdate($id)
{
    $model=$this->loadModel($id);

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

/**
* Lists all models.
*/
public function actionIndex()
{
$dataProvider=new CActiveDataProvider('usermailbox',array(
    'criteria'=>array('condition'=>'customer_id=' .Yii::App()->user->getId(),),));
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
