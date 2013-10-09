<?php
/* @var $this TCustomerController */
/* @var $model TCustomer */

$this->breadcrumbs=array(
	'Tcustomers'=>array('index'),
	$model->customer_id,
);

$this->menu=array(
	array('label'=>'List TCustomer', 'url'=>array('index')),
	array('label'=>'Create TCustomer', 'url'=>array('create')),
	array('label'=>'Update TCustomer', 'url'=>array('update', 'id'=>$model->customer_id)),
	array('label'=>'Delete TCustomer', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->customer_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage TCustomer', 'url'=>array('admin')),
);
?>

<h1>View TCustomer #<?php echo $model->customer_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
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
)); ?>
