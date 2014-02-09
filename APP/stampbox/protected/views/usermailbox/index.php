<?php
/* @var $this UsermailboxController */
/* @var $dataProvider CActiveDataProvider */
$gridcolumns = array(
       array('name'=>'e_mail', 'header'=>'e-mail'),
       array('name'=>'e_mail_username', 'header'=>'Username'),
       array('name'=>'e_mail_password', 'header'=>'Password'),
       array('name'=>'status', 'header'=>'Status'),
 //      array(
 //           'class'=>'bootstrap.widgets.TbButtonColumn',
 //           'htmlOptions'=>array('style'=>'width: 50px'),
 //       ),
    );
?>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'type'=>'striped bordered condensed',
    'dataProvider'=>$dataProvider,
    'template'=>"{items}",
    'columns'=>$gridcolumns,
)); ?>
