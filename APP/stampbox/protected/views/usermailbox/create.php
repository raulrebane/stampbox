<?php
$this->breadcrumbs=array(
	'Usermailboxes'=>array('index'),
	'Create',
);

$this->menu=array(
array('label'=>'List usermailbox','url'=>array('index')),
array('label'=>'Manage usermailbox','url'=>array('admin')),
);
?>

<h1>Create usermailbox</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>