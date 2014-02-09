<?php
$this->breadcrumbs=array(
	'My Stamps'=>array('index'),
	$model->stamp_id,
);

$this->menu=array(
array('label'=>'List MyStamps','url'=>array('index')),
array('label'=>'Create MyStamps','url'=>array('create')),
array('label'=>'Update MyStamps','url'=>array('update','id'=>$model->stamp_id)),
array('label'=>'Delete MyStamps','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->stamp_id),'confirm'=>'Are you sure you want to delete this item?')),
array('label'=>'Manage MyStamps','url'=>array('admin')),
);
?>

<h1>View MyStamps #<?php echo $model->stamp_id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
'data'=>$model,
'attributes'=>array(
		'Stamp_token',
		'stamp_id',
		'batch_id',
		'issued_to',
		'status',
		'timestamp',
),
)); ?>
