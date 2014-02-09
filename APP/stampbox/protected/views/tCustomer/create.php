<?php
/* @var $this TCustomerController */
/* @var $model TCustomer */

$this->breadcrumbs=array(
	'Tcustomers'=>array('index'),
	'Register',
);

?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
