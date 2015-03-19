<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div id="p-whitelist" class="row">
    <div class="col-md-6 m-add">
    <h1>Add e-mail to list</h1>
        <?php 
         $form = $this->beginWidget('CActiveForm',array(
            'id' => 'Whitelist',
            'htmlOptions' => array('class' => 'form', 'role'=>'form'),));
            $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'model'=>$model,
                'name'=>'e_mail',
                //'class'=>'form-control',
                'value'=>'',
                'source'=>$this->createUrl('whitelist/Autocomplete'),
                // additional javascript options for the autocomplete plugin
                'options'=>array('showAnim'=>'fold',),
            ));
            echo '<button type="submit" class="btn btn-aqua">Add to whitelist</button>';
        $this->endWidget();
        ?>
    </div>
    <div class="col-md-6 m-add">
        <h1>Add from mailbox contacts</h1>
        <?php $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id'=>'addFromContacts',
        'options'=>array(
            'title'=>'Add contacts to whitelist',
            'width'=>800,
            'height'=>1000,
            'autoOpen'=>FALSE,
            'resizable'=>true,
            'modal'=>true,
            'closeOnEscape'=>TRUE,
            //'dialogClass'=>"no-close",
            'overlay'=>array('backgroundColor'=>'#000','opacity'=>'0.8')
            ),
        ));
        $this->endWidget('zii.widgets.jui.CJuiDialog');
        ?>
    </div>
</div>
    
 <div class="col-md-12">
    <div class="widget widget-activity"><div class="title">My whitelist</div>
    <div class="content">
        <?php 
        
        $gridColumns = array(
            array('name'=>'e_mail', 'htmlOptions'=>array('class'=>'email')),
            array('class'=>'CButtonColumn','template'=>'{delete}', 'buttons'=>array('delete' => array(
                    'label'=>'Delete',
                    'imageUrl'=>Yii::app()->request->baseUrl.'/images/btn-delete.png',
                    'url'=>'Yii::app()->createUrl("whitelist/delete", array("email"=>$data->e_mail))')
                )),
            );
        $this->widget('zii.widgets.grid.CGridView',array(
            'enablePagination'=>FALSE,
            //'hideHeader'=>False,
            'template' => '{items}',
            'htmlOptions'=>array('class'=>'content'),
            'dataProvider' => $dataProvider,
            'columns'=>$gridColumns));
        ?>
    </div>
    </div>
</div>

