<?php
/* @var $this TCustomerController */
/* @var $model TCustomer */

$this->breadcrumbs=array(
	'Tcustomers'=>array('index'),
	'Register',
);

?>

<h1>Join our wonderful service and change your e-mail forever</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
