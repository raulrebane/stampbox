<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
echo '<style>.no-close .ui-dialog-titlebar-close { display: none;}</style>';
//$model->task_id = 'test';
//$model->loading_inprogress = TRUE;

/*
* in the end must call $.fn.yiiGridView.update('invitation-grid'); to refresh gridview
 */
if ($model->loading_inprogress == TRUE) {
    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'LoadInProgress',
        'options'=>array(
            'title'=>'Loading contacts... Please wait',
            'width'=>400,
            'height'=>100,
            'autoOpen'=>true,
            'resizable'=>false,
            'modal'=>true,
            'closeOnEscape'=>false,
            'dialogClass'=>"no-close",
            'overlay'=>array('backgroundColor'=>'#000','opacity'=>'0.8')
            ),
        ));
    $this->widget('zii.widgets.jui.CJuiProgressBar', array(
        'id'=>'progressbar',
        'value'=>$model->percent_complete,
        //'htmlOptions'=>array('style'=>'width:200px; height:20px; float:center;')
    ));
    $this->endWidget('zii.widgets.jui.CJuiDialog');

    echo '<script type="text/javascript">';
    echo "function show_progress() {   
        var url = '";
    echo Yii::app()->createUrl('invite/GetProgress');
    echo "&task_id=" .$model->task_id ."'; 
    $.getJSON(url, function(data) {
        var done = parseInt(data.done);
        console.log(data);
        if (done > 100) { done = 100;}";
    echo '$("#progressbar").progressbar( "value", done);
            if (done == 100) {
                $("#progressbar").progressbar( "destroy");
                $("#LoadInProgress").dialog("close");';
    echo "$.fn.yiiGridView.update('invitation-grid');
            } else {";
    echo 'setTimeout("show_progress()", 1000);                    
        }
    });
}
$(document).ready(function() {
   setTimeout("show_progress()", 1000);
});
</script>';

}
$form = $this->beginWidget('CActiveForm',array(
    'id' => 'Invite',
    //'type'=>'horizontal',
    'htmlOptions' => array('class'=>'form', 'role'=>'form'),
    )); 
?>

<div id="p-invite" class="row">
    <div class="col-md-12 m-refresh">
        <?php 
            echo '<h1>' .$form->labelEx($model->mailboxlist,'e_mail') .'</h1>';
            echo $form->dropDownList($model->mailboxlist, 'e_mail',$model->emailslist);
        ?>
        <button type="submit" name="refresh" class="btn btn-aqua">Refresh contacts</button>
        </div>
</div>

<div class="row">
    <div class="col-md-12">
    <div class="widget widget-activity">
            <div class="title"></div>
            <div class="content">
                <div class="row"><div class="col-xs-offset-1"><button type="submit" name="invite" class="btn btn-aqua">Invite</button></div></div>
            <?php   
                $gridColumns = array(
                    array(
                    'id' => 'selectedIds',
                    'class' => 'CCheckBoxColumn',
                    'selectableRows'=>1000,
                    'header'=>'Invite',
                    'name'=>'invited_email',
                    'disabled'=>function($data) {if ($data['invite']==='Y') return TRUE; else return FALSE;},
                    'checked'=>'($row<100 AND $data["invite"] <> "Y")'),
                    array('name'=>'name', 'header'=>'Name'),
                    array('name'=>'invited_email', 'header'=>'E-mail'),
                    array('name'=>'from_count', 'header'=>'# of mails'),
                    array('name'=>'last_email_date', 'header'=> 'Last e-mail')
                );
                $this->widget('zii.widgets.grid.CGridView',array(
                    'id'=>'invitation-grid',
                    'enablePagination'=>FALSE,
                    //'hideHeader'=>TRUE,
                    'template' => '{items}',
                    'htmlOptions'=>array('class'=>'content'),
                    'dataProvider' => $model->dataProvider,
                    'columns'=>$gridColumns
                ));      
            ?>
            <div class="row"><div class="col-xs-offset-1"><button type="submit" name="invite" class="btn btn-aqua">Invite</button></div></div>
            </div>
        </div>
    </div>
</div>

<?php
$this->endWidget(); 
unset($form);

?>
