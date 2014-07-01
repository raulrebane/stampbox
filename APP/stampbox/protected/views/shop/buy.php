<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="row">
    <div class="col-md-12">
    <div class="widget widget-activity"><div class="title">Shop</div>
    <div class="content">
        <?php 
        $gridDataProvider = new CArrayDataProvider($model->shop_grid, array('keyField'=>'offer_id',
                'pagination'=>array('pageSize'=>1000,)));  
        $gridColumns = array(
            array('name'=>'offer_amount', 'header'=>'Stamps', 'htmlOptions'=>array('class'=>'transaction', 'width'=>"25"),),
            array('name'=>'offer_price', 'header'=>'Price', 'htmlOptions'=>array('class'=>'email'),),
            array('name'=>'amount', 'htmlOptions'=>array('class'=>'transaction')),);
        $this->widget('zii.widgets.grid.CGridView',array(
            'enablePagination'=>FALSE,
            //'hideHeader'=>False,
            'template' => '{items}',
            'htmlOptions'=>array('class'=>'content'),
            'dataProvider' => $gridDataProvider,
            'columns'=>$gridColumns));      
        ?>
    </div>
    </div>
    </div>
</div>
