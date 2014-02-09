<?php
$this->breadcrumbs=array(
	'My Stamps',
);

$this->menu=array(
array('label'=>'Create MyStamps','url'=>array('create')),
array('label'=>'Manage MyStamps','url'=>array('admin')),
);
?>

<h1>My Stamps</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
'dataProvider'=>$dataProvider,
'itemView'=>'_view',
)); ?>
