<div class="view">

		<b><?php echo CHtml::encode($data->getAttributeLabel('stamp_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->stamp_id),array('view','id'=>$data->stamp_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('Stamp_token')); ?>:</b>
	<?php echo CHtml::encode($data->Stamp_token); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('batch_id')); ?>:</b>
	<?php echo CHtml::encode($data->batch_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('issued_to')); ?>:</b>
	<?php echo CHtml::encode($data->issued_to); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('timestamp')); ?>:</b>
	<?php echo CHtml::encode($data->timestamp); ?>
	<br />


</div>