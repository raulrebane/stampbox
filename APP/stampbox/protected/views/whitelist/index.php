<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div class="row">
    <div class="col-md-12">
    <div class="widget widget-activity"><div class="title">My whitelist</div>
    <div class="content">
        <?php 
         $form = $this->beginWidget(
        'CActiveForm',array(
            'id' => 'Whitelist',
            'htmlOptions' => array('class' => 'form', 'role'=>'form'),));
        
            echo $form->labelEx($model,'e_mail');
            $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'model'=>$model,
                'name'=>'e_mail',
                //'class'=>'form-control',
                'value'=>'',
                'source'=>$this->createUrl('whitelist/Autocomplete'),
                // additional javascript options for the autocomplete plugin
                'options'=>array(
                            'showAnim'=>'fold',),
            ));
            echo '<button type="submit" class="btn btn-default">Add to whitelist</button>';
        $this->endWidget();
        
        $gridColumns = array(
            array('name'=>'e_mail', 'htmlOptions'=>array('class'=>'email')),
            array('class'=>'CButtonColumn','template'=>'{delete}'),
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
</div>

