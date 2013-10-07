<?php
/* @var $this TCustomerController */
/* @var $model TCustomer */

$this->breadcrumbs=array(
	'Tcustomers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List TCustomer', 'url'=>array('index')),
	array('label'=>'Manage TCustomer', 'url'=>array('admin')),
);
?>

<h1>Create TCustomer</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>