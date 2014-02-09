<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

		<?php echo $form->textFieldRow($model,'transaction_code',array('class'=>'span5','maxlength'=>5)); ?>

		<?php echo $form->textFieldRow($model,'customer_id',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'stamp_id',array('class'=>'span5')); ?>

		<?php echo $form->textFieldRow($model,'points',array('class'=>'span5','maxlength'=>4)); ?>

		<?php echo $form->textFieldRow($model,'timestamp',array('class'=>'span5','maxlength'=>5)); ?>

		<?php echo $form->textFieldRow($model,'transaction_id',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType' => 'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
