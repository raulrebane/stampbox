<?php
$this->breadcrumbs=array(
	'Tstamps Transactions'=>array('index'),
	$model->transaction_id=>array('view','id'=>$model->transaction_id),
	'Update',
);

	$this->menu=array(
	array('label'=>'List TStampsTransactions','url'=>array('index')),
	array('label'=>'Create TStampsTransactions','url'=>array('create')),
	array('label'=>'View TStampsTransactions','url'=>array('view','id'=>$model->transaction_id)),
	array('label'=>'Manage TStampsTransactions','url'=>array('admin')),
	);
	?>

	<h1>Update TStampsTransactions <?php echo $model->transaction_id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>