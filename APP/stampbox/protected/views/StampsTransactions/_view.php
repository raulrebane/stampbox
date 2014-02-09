<div class="view">

		<b><?php echo CHtml::encode($data->getAttributeLabel('transaction_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->transaction_id),array('view','id'=>$data->transaction_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('transaction_code')); ?>:</b>
	<?php echo CHtml::encode($data->transaction_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('customer_id')); ?>:</b>
	<?php echo CHtml::encode($data->customer_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stamp_id')); ?>:</b>
	<?php echo CHtml::encode($data->stamp_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('points')); ?>:</b>
	<?php echo CHtml::encode($data->points); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('timestamp')); ?>:</b>
	<?php echo CHtml::encode($data->timestamp); ?>
	<br />


</div>