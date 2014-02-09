<?php
$this->breadcrumbs=array(
	'My Stamps'=>array('index'),
	$model->stamp_id=>array('view','id'=>$model->stamp_id),
	'Update',
);

	$this->menu=array(
	array('label'=>'List MyStamps','url'=>array('index')),
	array('label'=>'Create MyStamps','url'=>array('create')),
	array('label'=>'View MyStamps','url'=>array('view','id'=>$model->stamp_id)),
	array('label'=>'Manage MyStamps','url'=>array('admin')),
	);
	?>

	<h1>Update MyStamps <?php echo $model->stamp_id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>