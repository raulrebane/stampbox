<?php
/* @var $this RegisterController */
/* @var $model Register */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Register -> Step1';

$form = $this->beginWidget('CActiveForm',array(
    'id' => 'Step1',
    //'type'=>'horizontal',
    'htmlOptions' => array('class'=>"form register", 'role'=>"form"),
    'enableAjaxValidation'=>true)); 

// echo $form->errorSummary($model);
        echo $form->labelEx($model,'username');
        echo $form->EmailField($model, 'username', array('class'=>'form-control', 'id'=>'email', 'placeholder'=>'Enter email'));
        echo $form->error($model, 'username',array('validateOnChange'=>true));
        
        echo $form->labelEx($model, 'password');
        echo $form->passwordField($model, 'password', array('class'=>'form-control', 'placeholder'=>'Password'));
        echo $form->error($model, 'password','',FALSE);

        echo $form->labelEx($model, 'passwordrepeat');
        echo $form->passwordField($model, 'passwordrepeat', array('class'=>'form-control', 'placeholder'=>'Password again'));
        echo $form->error($model, 'passwordrepeat', '', FALSE);
?>
<!--        <div class="form-actions">        
-->         
            <button type="submit" class="btn btn-default"></button>
<!--        </div>
-->
<?php
$this->endWidget(); 
//unset($form);