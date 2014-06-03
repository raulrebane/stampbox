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
        echo $form->labelEx($model,'useremail');
        echo $form->EmailField($model, 'useremail', array('class'=>'form-control', 'placeholder'=>'Enter email'));
        echo $form->error($model, 'useremail',array('validateOnChange'=>true));
        
        echo $form->labelEx($model, 'emailusername');
        echo $form->textField($model, 'emailusername', array('class'=>'form-control', 'placeholder'=>'E-mail account username'));
        echo $form->error($model, 'emailusername', '', FALSE);

        echo $form->labelEx($model, 'emailpassword');
        echo $form->passwordField($model, 'emailpassword', array('class'=>'form-control', 'placeholder'=>'E-mail password'));
        echo $form->error($model, 'emailpassword', '', FALSE);
?>
<!--        <div class="form-actions">        
-->         
            <button type="submit" class="btn btn-default"></button>
<!--        </div>
-->
<?php
$this->endWidget(); 
//unset($form);