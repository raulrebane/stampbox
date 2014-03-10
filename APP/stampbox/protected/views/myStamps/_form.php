<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'my-stamps-form',
	'enableAjaxValidation'=>false,
)); ?>

<p class="help-block">Fields with <span class="required">*</span> are required.</p>

<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'Stamp_token',array('class'=>'span5','maxlength'=>64)); ?>

	<?php echo $form->textFieldRow($model,'stamp_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'batch_id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'issued_to',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'status',array('class'=>'span5','maxlength'=>1)); ?>

	<?php echo $form->textFieldRow($model,'timestamp',array('class'=>'span5','maxlength'=>5)); ?>

<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
</div>

<?php $this->endWidget(); ?>