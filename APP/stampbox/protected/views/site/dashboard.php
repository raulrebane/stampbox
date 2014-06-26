<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 $stampcount = Yii::app()->db->createCommand(array(
            'select'=> array('points_bal', 'stamps_bal'),
            'from' => 'ds.t_account',
            'where'=> 'customer_id=:1',
            'params' => array(':1'=>Yii::app()->user->getId()),
        ))->queryRow();
        $invitationcount = Yii::app()->db->createCommand(array(
            'select'=> array('count(invited_email) as invitedtotal, count(invite) as invited'),
            'from' => 'ds.t_invitations',
            'where'=> 'customer_id=:1',
            'params' => array(':1'=>Yii::app()->user->getId()),
        ))->queryRow();
$lasttransactions = Yii::app()->db->createCommand(array(
            'select'=> array('*'),
            'from'=> 'ds.t_stamps_transactions',
            'where'=> 'customer_id = :1',
            'order'=> 'transaction_id desc',
            'limit'=> '10',
            'params'=> array(':1'=>Yii::app()->user->getId()),
        ))->queryAll();
?>
<div class="col-md-7">
    <div class="row">
    <div class="col-md-12">
        <div class="widget widget-balance"><div class="title">You have</div>
        <div class="content">
            <div class="balance"><?php echo $stampcount['stamps_bal'] ?> <div class="suffix">Stamps</div> 
            <?php echo $stampcount['points_bal'] ?> <div class="suffix">Points left</div>
            </div>
            <div class="btn btn-aqua buy-more">Buy stamps</div>
        </div>
        </div>
    </div>
    <div class="col-md-12">
    <div class="widget widget-activity"><div class="title">Activity</div>
    <div class="content">
        <?php 
        $gridDataProvider = new CArrayDataProvider($lasttransactions, array('keyField'=>'transaction_id', ));  
        $gridColumns = array(
            array('name'=>'type', 'value'=>'if ($data->amount < 0) { echo \'<i class="icon-reply"></i>\'} else { echo \'<i class="icon-forward"></i>\'} '),
            array('name'=>'description'),
            array('name'=>'amount'),
            array('name'=>'date')
            );
        $this->widget('zii.widgets.grid.CGridView',array(
            'id'=>'smallstatement-grid',
            'enablePagination'=>FALSE,
            'dataProvider' => $gridDataProvider,
            'columns'=>$gridColumns));      
        ?>
    </div>