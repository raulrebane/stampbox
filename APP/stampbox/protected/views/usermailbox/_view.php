<div class="view">

		<b><?php echo CHtml::encode($data->getAttributeLabel('e_mail')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->e_mail),array('view','id'=>$data->e_mail)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('customer_id')); ?>:</b>
	<?php echo CHtml::encode($data->customer_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('e_mail_username')); ?>:</b>
	<?php echo CHtml::encode($data->e_mail_username); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('e_mail_password')); ?>:</b>
	<?php echo CHtml::encode($data->e_mail_password); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('maildomain')); ?>:</b>
	<?php echo CHtml::encode($data->maildomain); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('worker_ip')); ?>:</b>
	<?php echo CHtml::encode($data->worker_ip); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('worker_type')); ?>:</b>
	<?php echo CHtml::encode($data->worker_type); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('last_seen')); ?>:</b>
	<?php echo CHtml::encode($data->last_seen); ?>
	<br />

	*/ ?>

</div>