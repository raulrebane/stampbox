<?php
/* @var $this UsermailboxController */
/* @var $dataProvider CActiveDataProvider */
$gridcolumns = array(
       array('name'=>'e_mail', 'header'=>'e-mail'),
       array('name'=>'e_mail_username', 'header'=>'Username'),
       array('name'=>'e_mail_password', 'header'=>'Password'),
       array('name'=>'status', 'header'=>'Status'),
       array('class'=>'CButtonColumn'),
    );
?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'template'=>"{items}",
    'columns'=>$gridcolumns,
    'htmlOptions'=>array('class'=>'content')
)); ?>
