<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div id="p-shop" class="row">
    <div class="m-products col-md-12">
        
    <div class="widget widget-activity"><div class="title">Shop</div>
    <div class="content">
        <?php 
        //$gridColumns = 
        $this->widget('zii.widgets.CListView',array(
            'itemView' => 'saleitem',
            'htmlOptions'=>array('class'=>'content'),
            'dataProvider' => $dataProvider,
            'template' => '{items}'
            ));
        ?>
    </div>
    </div>
    </div>
</div>
