<?php
$this->breadcrumbs=array(
	'Usermailboxes'=>array('index'),
	$model->e_mail,
);

$this->menu=array(
array('label'=>'List usermailbox','url'=>array('index')),
array('label'=>'Create usermailbox','url'=>array('create')),
array('label'=>'Update usermailbox','url'=>array('update','id'=>$model->e_mail)),
array('label'=>'Delete usermailbox','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->e_mail),'confirm'=>'Are you sure you want to delete this item?')),
array('label'=>'Manage usermailbox','url'=>array('admin')),
);
?>

<h1>View usermailbox #<?php echo $model->e_mail; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
'data'=>$model,
'attributes'=>array(
		'customer_id',
		'e_mail',
		'e_mail_username',
		'e_mail_password',
		'status',
		'maildomain',
		'worker_ip',
		'worker_type',
		'last_seen',
),
)); ?>
