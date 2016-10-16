<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<style>.no-close .ui-dialog-titlebar-close { display: none;}</style>

<?php
//$model->task_id = 'test';
//$model->loading_inprogress = TRUE;

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
    $this->endWidget();
?>

    <script type="text/javascript">
        function show_progress() {   
        var url = '<?php echo Yii::app()->createUrl('invite/GetProgress');
                         echo "&task_id=" .$model->task_id; ?>';
        $.getJSON(url, function(data) {
            var done = parseInt(data.done);
            if (done > 100) { done = 100;}
            $("#progressbar").progressbar( "value", done);
            if (done == 100) {
                $("#progressbar").progressbar( "destroy");
                $("#LoadInProgress").dialog("close");
                $.fn.yiiGridView.update('invitation-grid');
            } 
            else {
                setTimeout("show_progress()", 1000);                    
            }
        });
    }
    
    $(document).ready(function() {
        setTimeout("show_progress()", 1000);
    });
    </script>

<?php
}


if (isset($model->emailslist)) {
?>
    <div class="col-xs-12 col-md-8 col-lg-8">
    <div class="widget widget-invitations">
        <div class="dialog-form">
        <?php 
            //$model = new Invitations();
            $form = $this->beginWidget('CActiveForm',array(
            'id' => 'Invite',
            'action' => Yii::app()->createUrl('invite/index'), 
            'htmlOptions' => array('class' => 'form', 'role'=>'form'),));
        ?>
        <div class="row">
            <div class="col-xs-6"><div class="select-style">
                <?php echo $form->dropDownList($model->mailboxlist, 'e_mail',$model->emailslist);?>
            </div></div>
            <div class="col-xs-4"><button type="submit" name="refresh" class="btn btn-aqua refresh-btn">Refresh contacts</button></div>
        <?php $this->endWidget();?>
        </div>
        </div>
    </div>
    </div>
<?php
}
?>

<div class="row">
    <div class="col-md-12">
    <ul class="nav nav-tabs" data-tabs="tabs" role="tablist">
        <li role="presentation" class="active"><a href="#Invite" aria-controls="Invite" role="tab" data-toggle="tab">Invite people</a></li>
        <li role="presentation"><a href="#Invited" aria-controls="Invited" role="tab" data-toggle="tab">Invited list</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="Invite">
        <div class="widget widget-activity">
            <div class="content">
                <div class="row"><div class="col-xs-offset-1"><button type="submit" name="invite" class="btn btn-aqua">Invite selected</button></div></div>
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
                    'htmlOptions'=>array('class'=>''),
                    'dataProvider' => $model->dataProvider,
                    'columns'=>$gridColumns
                ));      
                //$this->endWidget(); 
            ?>
            <div class="row"><div class="col-xs-offset-1"><button type="submit" name="invite" class="btn btn-aqua">Invite selected</button></div></div>
            </div>
        </div>
        </div>
    </div>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane" id="Invited">
        <div class="widget widget-activity">
            <div class="content">
            <?php   
                $gridColumns = array(
                    array('name'=>'name', 'header'=>'Name'),
                    array('name'=>'invited_email', 'header'=>'E-mail'),
                    array('name'=>'invited_when', 'header'=> 'Date invited')
                );
                $this->widget('zii.widgets.grid.CGridView',array(
                    'id'=>'invitation-grid',
                    'enablePagination'=>FALSE,
                    //'hideHeader'=>TRUE,
                    'template' => '{items}',
                    'htmlOptions'=>array('class'=>''),
                    'dataProvider' => $model->dataProvider,
                    'columns'=>$gridColumns
                ));      
                //$this->endWidget(); 
            ?>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>

<?php

//unset($form);
?>
