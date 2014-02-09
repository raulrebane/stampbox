<?php
$this->breadcrumbs=array(
	'Tstamps Transactions'=>array('index'),
	$model->transaction_id,
);

$this->menu=array(
array('label'=>'List TStampsTransactions','url'=>array('index')),
array('label'=>'Create TStampsTransactions','url'=>array('create')),
array('label'=>'Update TStampsTransactions','url'=>array('update','id'=>$model->transaction_id)),
array('label'=>'Delete TStampsTransactions','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->transaction_id),'confirm'=>'Are you sure you want to delete this item?')),
array('label'=>'Manage TStampsTransactions','url'=>array('admin')),
);
?>

<h1>View TStampsTransactions #<?php echo $model->transaction_id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
'data'=>$model,
'attributes'=>array(
		'transaction_code',
		'customer_id',
		'stamp_id',
		'points',
		'timestamp',
		'transaction_id',
),
)); ?>
