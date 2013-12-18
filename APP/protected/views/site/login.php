<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

//$this->pageTitle=Yii::app()->name . ' - Login';
//$this->breadcrumbs=array('Login',);
?>
<div class ="shadow">
    <div class="form-horizontal">
    <?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),)); 
    ?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'username', array('class' => 'col-sm-2 control-label')); 
		?>
		<?php echo $form->textField($model,'username', 
				array('class' => 'form-control', 'placeholder' => 'Username')); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'password', array('class' => 'col-sm-2 control-label')); ?>
		<?php echo $form->passwordField($model,'password', 
				array('class' => 'form-control')); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="form-group">
	 <div class="col-sm-offset-2">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>
	</div>

	<div class="form-group">
	 <div class="col-sm-offset-2">
		<?php echo CHtml::submitButton('Login', array('class' => 'btn btn-default')); ?>
	</div>
	</div>

<!--
	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<p class"note"><b>Not a user yet? Click <a href="/stampmail/index.php?r=tCustomer/create">here</a> to register</b></p>
-->
    <?php $this->endWidget(); ?>
    </div><!-- form -->
</div>