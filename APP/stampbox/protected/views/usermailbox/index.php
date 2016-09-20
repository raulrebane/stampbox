<div class="widget widget-accounts"><div class="title">Stampboxed e-mail accounts</div>

<?php
$gridcolumns = array(
    array('name'=>'e_mail', 'header'=>'e-mail'),
    array('name'=>'e_mail_username', 'header'=>'Username', 'htmlOptions'=>array('class'=>'hidden-xs', 'align'=>'left'), 
           'headerHtmlOptions'=>array('class'=>'hidden-xs')),
    array('name'=>'e_mail_password', 'header'=>'Password', 'htmlOptions'=>array('class'=>'hidden-xs', 'align'=>'center'), 'value'=>'"******"',
           'headerHtmlOptions'=>array('class'=>'hidden-xs')),
    array('name'=>'status', 'header'=>'Status', 'htmlOptions'=>array('class'=>'status', 'align'=>'center'), 'type'=>'raw', 'value'=>function($data) {
        if ($data->status == 'A') return '<span class="glyphicon glyphicon-ok"></span>'; else return '<span class="glyphicon glyphicon-warning-sign"></span>'; }),
    array('name'=>'sending_service', 'htmlOptions'=>array('class'=>'status', 'align'=>'center'), 'type'=>'raw', 'value'=>function($data) {
        if ($data->sending_service === TRUE) return '<span class="glyphicon glyphicon-ok-sign"></span>'; else return '<span class="glyphicon glyphicon-ban-circle"></span>'; }),
    array('name'=>'receiving_service', 'htmlOptions'=>array('class'=>'status', 'align'=>'center'), 'type'=>'raw', 'value'=>function($data) {
        if ($data->receiving_service === TRUE) return '<span class="glyphicon glyphicon-ok-sign"></span>'; else return '<span class="glyphicon glyphicon-ban-circle"></span>'; }),
    array('name'=>'sorting_service', 'htmlOptions'=>array('class'=>'status', 'align'=>'center'), 'type'=>'raw', 'value'=>function($data) {
        if ($data->sorting_service === TRUE) return '<span class="glyphicon glyphicon-ok-sign"></span>'; else return '<span class="glyphicon glyphicon-ban-circle"></span>'; }),                                
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

