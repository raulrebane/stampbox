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
                        if ($data->status === 'A') return '<i class="icon-ok"></i>'; else return '<i class="icon-cw"></i>'; }),
       array('class'=>'CButtonColumn', 'template'=>'{update}', 'buttons'=>array(
            'update' => array('label'=>'Update','imageUrl'=>Yii::app()->request->baseUrl.'/images/btn-edit.png',
                            'url'=>'Yii::app()->createUrl("usermailbox/update", array("email"=>$data->e_mail))')))
    );
?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'template'=>"{items}",
    'columns'=>$gridcolumns,
    'htmlOptions'=>array('class'=>'content')
)); ?>
    <div class="footer">
        <a class="btn btn-dark" href="<?php echo Yii::app()->createUrl('usermailbox/create')?>"><i class="icon-plus-circled"></i>Add account</a>
    </div>
        
    </div>
    </div></div>
</div>
