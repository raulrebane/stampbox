<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$form = $this->beginWidget('CActiveForm',array(
    'id' => 'Invite',
    //'type'=>'horizontal',
    'htmlOptions' => array('class'=>'form', 'role'=>'form'),
    )); 
?>
<style>
 .loading{
    background-color: #eee;
    background-image: url('loading.gif');
    background-position:  center center;
    background-repeat: no-repeat;
    opacity: 0.8; }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="row"><div class="col-sm-offset-8">
        <?php 
            $model = new usermailbox;
            $useremails = usermailbox::model()->findAll('customer_id = :1', array(':1'=>Yii::app()->user->getId()));
            $emailslist = CHtml::listData($useremails, 'e_mail', 'e_mail');
            echo $form->labelEx($model,'e_mail');
            echo $form->dropDownList($model, 'e_mail',$emailslist);
        ?>
        <button type="submit" name="refresh" class="btn btn-default" 
            onsubmit="js:function(){$(#content).addClass("loading");}">Refresh contacts</button>
        </div></div>
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
                    'enablePagination'=>FALSE,
                    //'hideHeader'=>TRUE,
                    'template' => '{items}',
                    'htmlOptions'=>array('class'=>'content'),
                    'dataProvider' => $dataProvider,
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