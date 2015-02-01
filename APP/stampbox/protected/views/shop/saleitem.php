<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="w-product">
    <div class="w-hover" onClick="location.href='<?php echo Yii::app()->createUrl("/shop/AddToCart", array("id"=>$data["offer_id"])); ?>'">
        <div class="name"><?php echo $data["offer_amount"]; ?></div>
        <div class="unit">stamps</div>
        <div class="separator"></div>
        <div class="price"><?php echo $data["offer_price"]; ?>€</div>
    </div>
</div>
