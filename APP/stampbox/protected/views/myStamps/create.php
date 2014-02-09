<?php
$this->breadcrumbs=array(
	'My Stamps'=>array('index'),
	'Create',
);

$this->menu=array(
array('label'=>'List MyStamps','url'=>array('index')),
array('label'=>'Manage MyStamps','url'=>array('admin')),
);
?>

<h1>Create MyStamps</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>