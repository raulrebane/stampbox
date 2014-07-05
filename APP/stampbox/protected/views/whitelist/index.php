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
        $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
            'name'=>'emailfinder',
            'value'=>'',
            'source'=>$this->createUrl('whitelist/Autocomplete'),
            // additional javascript options for the autocomplete plugin
            'options'=>array(
			'showAnim'=>'fold',),
        ));       
        $gridColumns = array(
            array('name'=>'offer_amount', 'header'=>'Stamps', 'htmlOptions'=>array('class'=>'transaction')),
            array('name'=>'offer_price', 'header'=>'Price', 'htmlOptions'=>array('class'=>'email')),
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
