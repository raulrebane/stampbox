<?php
/* @var $this TCustomerController */
/* @var $data TCustomer */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('customer_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->customer_id), array('view', 'id'=>$data->customer_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
	<?php echo CHtml::encode($data->username); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('firstname')); ?>:</b>
	<?php echo CHtml::encode($data->firstname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lastname')); ?>:</b>
	<?php echo CHtml::encode($data->lastname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('password')); ?>:</b>
	<?php echo CHtml::encode($data->password); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_seen')); ?>:</b>
	<?php echo CHtml::encode($data->last_seen); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('preferred_lang')); ?>:</b>
	<?php echo CHtml::encode($data->preferred_lang); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bad_logins')); ?>:</b>
	<?php echo CHtml::encode($data->bad_logins); ?>
	<br />

	*/ ?>

</div>