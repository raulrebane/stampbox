<?php
/* @var $this TCustomerController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tcustomers',
);

$this->menu=array(
	array('label'=>'Create TCustomer', 'url'=>array('create')),
	array('label'=>'Manage TCustomer', 'url'=>array('admin')),
);
?>

<h1>Tcustomers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
