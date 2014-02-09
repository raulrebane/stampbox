<?php
/* @var $this TCustomerController */
/* @var $model TCustomer */

$this->breadcrumbs=array(
	'Tcustomers'=>array('index'),
	$model->customer_id=>array('view','id'=>$model->customer_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List TCustomer', 'url'=>array('index')),
	array('label'=>'Create TCustomer', 'url'=>array('create')),
	array('label'=>'View TCustomer', 'url'=>array('view', 'id'=>$model->customer_id)),
	array('label'=>'Manage TCustomer', 'url'=>array('admin')),
);
?>

<h1>Update TCustomer <?php echo $model->customer_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>