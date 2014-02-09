<?php
$this->breadcrumbs=array(
	'Tstamps Transactions'=>array('index'),
	'Create',
);

$this->menu=array(
array('label'=>'List TStampsTransactions','url'=>array('index')),
array('label'=>'Manage TStampsTransactions','url'=>array('admin')),
);
?>

<h1>Create TStampsTransactions</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>