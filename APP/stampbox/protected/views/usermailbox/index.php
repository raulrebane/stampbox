

<div class="widget widget-accounts"><div class="title">Stampboxed e-mail accounts</div>

<?php

$gridcolumns = array(
    array('name'=>'e_mail', 'htmlOptions'=>array('class'=>'email')),
    array('name'=>'e_mail_username', 'header'=>'Username', 'htmlOptions'=>array('class'=>'hidden-xs', 'align'=>'left'), 
           'headerHtmlOptions'=>array('class'=>'hidden-xs')),
    array('name'=>'e_mail_password', 'header'=>'Password', 'htmlOptions'=>array('class'=>'hidden-xs', 'align'=>'center'), 'value'=>'"******"',
           'headerHtmlOptions'=>array('class'=>'hidden-xs')),
    array('header'=>'Service type', 'name'=>'extended_service', 'htmlOptions'=>array('class'=>'status', 'align'=>'center'), 'type'=>'raw', 'value'=>function($data) {
        if ($data->extended_service === TRUE) return 'Extended'; else return 'Simple'; }),
    array('class'=>'CButtonColumn','template'=>'{configure}', 'htmlOptions'=>array('class'=>'status'),
        'buttons'=>array('configure' => array('label'=>'','options'=>array('class'=>'glyphicon glyphicon-wrench'),
            'url'=>'Yii::app()->createUrl("usermailbox/update", array("email"=>$data->e_mail))'))),
    array('class'=>'CButtonColumn','template'=>'{remove}', 'htmlOptions'=>array('class'=>'status'),
        'buttons'=>array('remove' => array('label'=>'','options'=>array('class'=>'glyphicon glyphicon-remove'),
            'url'=>'Yii::app()->createUrl("usermailbox/delete", array("email"=>$data->e_mail))')))
);
?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'template'=>"{items}",
    'columns'=>$gridcolumns,
    'htmlOptions'=>array('class'=>'content table-responsive')
)); ?>
    <div class="footer">
        <a class="btn btn-aqua" href="<?php echo Yii::app()->createUrl('usermailbox/create')?>">Stampbox new e-mail</a>
    </div>
</div>

