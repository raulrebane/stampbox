<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'usermailbox-form',
	'enableAjaxValidation'=>false,
)); ?>

<p class="help-block">Fields with <span class="required">*</span> are required.</p>

<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'customer_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'e_mail',array('class'=>'span5','maxlength'=>100)); ?>

	<?php echo $form->textFieldRow($model,'e_mail_username',array('class'=>'span5','maxlength'=>100)); ?>

	<?php echo $form->textFieldRow($model,'e_mail_password',array('class'=>'span5','maxlength'=>32)); ?>

	<?php echo $form->textFieldRow($model,'status',array('class'=>'span5','maxlength'=>1)); ?>

	<?php echo $form->textFieldRow($model,'maildomain',array('class'=>'span5','maxlength'=>100)); ?>

	<?php echo $form->textFieldRow($model,'worker_ip',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'worker_type',array('class'=>'span5','maxlength'=>20)); ?>

	<?php echo $form->textFieldRow($model,'last_seen',array('class'=>'span5','maxlength'=>6)); ?>

<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
</div>

<?php $this->endWidget(); ?>
