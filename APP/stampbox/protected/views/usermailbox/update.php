<?php
$this->breadcrumbs=array(
	'Usermailboxes'=>array('index'),
	$model->e_mail=>array('view','id'=>$model->e_mail),
	'Update',
);

	$this->menu=array(
	array('label'=>'List usermailbox','url'=>array('index')),
	array('label'=>'Create usermailbox','url'=>array('create')),
	array('label'=>'View usermailbox','url'=>array('view','id'=>$model->e_mail)),
	array('label'=>'Manage usermailbox','url'=>array('admin')),
	);
	?>

	<h1>Update usermailbox <?php echo $model->e_mail; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>