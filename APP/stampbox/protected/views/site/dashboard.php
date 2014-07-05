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
            'select'=> array('transaction_id', 'customer_id', 'amount', 'transaction_date', 'description', 'from_email', 'to_email', 'subject'),
            'from'=> 'ds.v_transactions',
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
            <div class="btn btn-aqua buy-more" onclick="href=<?php echo Yii::app()->createUrl('shop/buy')?>">Buy stamps</div>
        </div>
        </div>
    </div>
    <div class="col-md-12">
    <div class="widget widget-activity"><div class="title">Activity</div>
    <div class="content">
        <?php 
        $gridDataProvider = new CArrayDataProvider($lasttransactions, array('keyField'=>'transaction_id', ));  
        $gridColumns = array(
            array('name'=>'type', 'htmlOptions'=>array('class'=>'type', 'width'=>"25"), 'type'=>'raw', 'value'=>function($data) {
                if ($data['amount']<0) return '<i class="icon-reply"></i>'; else return '<i class="icon-forward"></i>';}),
            array('name'=>'from_email', 'htmlOptions'=>array('class'=>'email'), 'type'=>'raw', 'value'=>function($data) {
                return $data['from_email'] .'<span>'.$data['subject'] .'</span>';}),
            array('name'=>'amount', 'htmlOptions'=>array('class'=>'transaction')),
            array('name'=>'transaction_date', 'htmlOptions'=>array('class'=>'date'), 'value'=>'date("d/m/y", strtotime($data["transaction_date"]))'),
            array('name'=>'transaction_date', 'htmlOptions'=>array('class'=>'time'), 'value'=>'date("H:i", strtotime($data["transaction_date"]))'));
        $this->widget('zii.widgets.grid.CGridView',array(
            'enablePagination'=>FALSE,
            'hideHeader'=>TRUE,
            'template' => '{items}',
            'htmlOptions'=>array('class'=>'content'),
            'dataProvider' => $gridDataProvider,
            'columns'=>$gridColumns));      
        ?>
    </div>
    <div class="footer">
        <div class="btn btn-dark">See all</div>
    </div>
    </div></div></div></div>    
    <div class="col-md-5"><div class="row">
        <div class="col-md-12"><div class="widget widget-accounts">
        <div class="title">Stapboxed email accounts</div>
            <div class="content">
                <?php $mailboxdataprovider = new CActiveDataProvider('usermailbox', array(
                    'criteria'=>array(
                    'condition'=>'customer_id='.Yii::app()->user->getId(),
                    'order'=>'e_mail ASC',),
                    'pagination'=>array('pageSize'=>20,),
                )); 
                
                $this->widget('zii.widgets.grid.CGridView', array(
                    'hideHeader'=>TRUE,
                    'template' => '{items}',
                    'htmlOptions'=>array('class'=>'content'),
                    'dataProvider'=>$mailboxdataprovider,
                    'columns'=>array(
                        array('name'=>'e_mail', 'htmlOptions'=>array('class'=>'email'),),
                        array('name'=>'status', 'htmlOptions'=>array('class'=>'status'), 'type'=>'raw', 'value'=>function($data) {
                        if ($data->status = 'A') return '<i class="icon-ok"></i>'; else return '<i class="icon-cw"></i>'; }),
                    )
                ));?>
            </div>
            <div class="footer">
                <div class="btn btn-dark"><i class="icon-plus-circled"></i> Add account</div>
            </div>
        </div></div>
        <div class="col-md-12">
            <div class="widget widget-invitations">
                <div class="title">Invitations</div>
                <div class="content">
                    <div class="subtitle">Sent</div>
                    <p> <?php echo $invitationcount['invited'] ?><span>of</span> <?php echo $invitationcount['invitedtotal'] ?></p>
<!--
                    <div class="subtitle">Received</div>
                    <p>32</p>
-->
                </div>
                <div class="footer">
                    <div class="btn btn-dark">Invite friends to receive free credits</div>
                </div>
            </div>
        </div>
    </div>
</div>