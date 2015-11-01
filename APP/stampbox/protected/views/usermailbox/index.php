<div id="p-usermailbox" class="row">
    <div class="col-md-12">
    <div class="widget widget-accounts"><div class="title">Stampboxed e-mail accounts</div>

<?php
/* @var $this UsermailboxController */
/* @var $dataProvider CActiveDataProvider */
$gridcolumns = array(
    array('name'=>'e_mail', 'header'=>'e-mail'),
    array('name'=>'e_mail_username', 'header'=>'Username', 'htmlOptions'=>array('class'=>'hidden-xs'), 
           'headerHtmlOptions'=>array('class'=>'hidden-xs')),
    array('name'=>'e_mail_password', 'header'=>'Password', 'htmlOptions'=>array('class'=>'hidden-xs'), 
           'headerHtmlOptions'=>array('class'=>'hidden-xs')),
    array('name'=>'status', 'header'=>'Status', 'htmlOptions'=>array('class'=>'status'), 'type'=>'raw', 'value'=>function($data) {
        if ($data->status == 'A') return '<i class="icon-ok"></i>'; else return '<i class="icon-cw"></i>'; }),
    array('name'=>'sending_service', 'htmlOptions'=>array('class'=>'status', 'align'=>'center'), 'type'=>'raw', 'value'=>function($data) {
        if ($data->sending_service === TRUE) return '<span class="glyphicon glyphicon-ok-sign"></span>'; else return '<span class="glyphicon glyphicon-ban-circle"></span>'; }),
    array('name'=>'receiving_service', 'htmlOptions'=>array('class'=>'status', 'align'=>'center'), 'type'=>'raw', 'value'=>function($data) {
        if ($data->receiving_service === TRUE) return '<span class="glyphicon glyphicon-ok-sign"></span>'; else return '<span class="glyphicon glyphicon-ban-circle"></span>'; }),
    array('name'=>'sorting_service', 'htmlOptions'=>array('class'=>'status', 'align'=>'center'), 'type'=>'raw', 'value'=>function($data) {
        if ($data->sorting_service === TRUE) return '<span class="glyphicon glyphicon-ok-sign"></span>'; else return '<span class="glyphicon glyphicon-ban-circle"></span>'; }),                                
    array('class'=>'CButtonColumn','template'=>'{configure}{remove}', 'htmlOptions'=>array('class'=>'status'),
        'buttons'=>array('configure' => array('label'=>'','options'=>array('class'=>'glyphicon glyphicon-wrench'),
            'url'=>'Yii::app()->createUrl("usermailbox/update", array("email"=>$data->e_mail))'),
            'remove' => array('label'=>'','options'=>array('class'=>'glyphicon glyphicon-remove'),
            'url'=>'Yii::app()->createUrl("usermailbox/delete", array("email"=>$data->e_mail))')))
);
?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'template'=>"{items}",
    'columns'=>$gridcolumns,
    'htmlOptions'=>array('class'=>'content')
)); ?>
    <div class="footer">
        <a class="btn btn-dark" href="<?php echo Yii::app()->createUrl('usermailbox/create')?>"><i class="icon-plus-circled"></i>Add new e-mail</a>
    </div>
        
    </div>
    </div></div>
</div>
