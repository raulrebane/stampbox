<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div id="p-shop" class="row">
    <div class="m-products col-md-12">
        
    <div class="widget"><div class="title">Select stamp package you want to buy</div>
    <div class="content">
        <?php 
        //$gridColumns = 
        $this->widget('zii.widgets.CListView',array(
            'itemView' => 'saleitem',
            'htmlOptions'=>array('class'=>''),
            'dataProvider' => $dataProvider,
            'template' => '{items}'
            ));
        ?>
    </div>
    </div>
    </div>
</div>
