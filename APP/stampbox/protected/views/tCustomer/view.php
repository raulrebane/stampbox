<?php
/* @var $this TCustomerController */
/* @var $model TCustomer */

 $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'customer_id',
		'username',
		'firstname',
		'lastname',
		'password',
		'last_seen',
		'status',
		'preferred_lang',
		'bad_logins',
	),
)); 
?>
