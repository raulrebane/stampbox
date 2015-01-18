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
        <table>
            <tr>
                <td><h3>Item</h3></td>
                <td><h3>Price</h3></td>
                <td><h3>Quantity</h3></td>
                <td><h3>Total</h3></td>
            </tr>
            <tr>
                <td><?php echo 'Stampbox pack - ' .$model->stamp_amount .' stamps'?></td>
                <td><?php echo round($model->price/1.2/$model->stamp_amount, 3) ?></td>
                <td><?php echo $model->stamp_amount ?></td>
                <td><?php echo round($model->price/1.2, 2) ?></td>
            </tr>
            <tr></tr>
            <tr>
                <td></td>
                <td></td>
                <td>Subtotal</td>
                <td><?php echo round($model->price/1.2, 2) ?></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>VAT (20%)</td>
                <td><?php echo round($model->price - $model->price/1.2, 2) ?></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td>Total</td>
                <td><?php echo $model->price ?></td>
            </tr>
            <tr></tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td><a href="<?php echo Yii::app()->createUrl('shop/PayPalExpress'); ?>">
                        <img src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif" align="left" style="margin-right:7px;">
                    </a>
                </td>
            </tr>
        </table>
    </div>
    </div>
    </div>
</div>
