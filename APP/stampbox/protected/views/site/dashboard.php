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
            //'select'=> array('transaction_id', 'customer_id', 'amount', 'transaction_date', 'description', 'e_mail', 'subject',),
            'select'=> array('*'),
            'from'=> 'ds.v_transactions',
            'where'=> 'customer_id = :1',
            'order'=> 'transaction_id desc',
            'limit'=> '10',
            'params'=> array(':1'=>Yii::app()->user->getId()),
        ))->queryAll();

foreach(Yii::app()->user->getFlashes() as $key => $message) {
        echo '<div class="alert alert-' .$key .'">' .$message ."</div>\n";
}
?>

<div class="col-md-7">
    <div class="row">
    <div class="col-md-12">
        <div class="widget widget-balance"><div class="title">You have</div>
        <div class="content">
            <div class="balance">
                <div class="count"><?php echo $stampcount['stamps_bal'] ?> <div class="suffix">Stamps</div></div> 
                <div class="count"><?php echo $stampcount['points_bal'] ?> <div class="suffix">Credits</div></div>
            </div>
            <a class="btn btn-aqua buy-more" href="<?php echo Yii::app()->createUrl('shop/buy')?>">Buy stamps</a>
        </div>
        </div>
    </div>
    <div class="col-md-12">
    <div class="widget widget-activity"><div class="title">Activity</div>
    <div class="content">
        <?php 
        $gridDataProvider = new CArrayDataProvider($lasttransactions, array('keyField'=>'transaction_id', ));  

        $gridColumns = array(
            array('header'=>'', 'name'=>'type', 'htmlOptions'=>array('class'=>'type', 'width'=>"25"), 'type'=>'raw', 'value'=>function($data) {
            if ($data['transaction_code'] == 'SCR' or $data['transaction_code'] == 'PDB') return ''; 
            elseif ($data['amount']<0) return '<i class="icon-reply"></i>'; else return '<i class="icon-forward"></i>';}),
            array('header'=>'E-mail / Subject', 'name'=>'e_mail', 'htmlOptions'=>array('class'=>'email'), 'type'=>'raw', 'value'=>function($data) {
                if ($data['e_mail'] == NULL) return $data['description'];
                else return $data['e_mail'] .'<span>'.$data['subject'] .'</span>';}),
            array('header'=>'Stamp(s)', 'name'=>'amount', 'headerHtmlOptions'=>array('class'=>'hidden-xs hidden-md'), 
                'cssClassExpression'=>'$data["amount"] < 0 ? "transaction neg" : "transaction"',
                'htmlOptions'=>array('class'=>'hidden-xs hidden-md'), 
                'value'=>function($data) { if ($data['transaction_code'][0] == 'S') return number_format($data['amount'], 0); else return '';}),
            array('header'=>'Credit(s)', 'name'=>'amount', 'headerHtmlOptions'=>array('class'=>'hidden-xs hidden-md'), 
                'cssClassExpression'=>'$data["amount"] < 0 ? "transaction neg" : "transaction"',
                'htmlOptions'=>array('class'=>'hidden-xs hidden-md'), 
                'value'=>function($data) {if ($data['transaction_code'][0] == 'P') return number_format($data['amount'], 3); else return '';}),
            array('header'=>'Amount', 'name'=>'amount', 'headerHtmlOptions'=>array('class'=>'visible-xs visible-md'),
                'cssClassExpression'=>'$data["amount"] < 0 ? "transaction neg" : "transaction"',
                'htmlOptions'=>array('class'=>'visible-xs visible-md'), 'value'=>function($data) {
                if ($data['transaction_code'][0] == 'S') return number_format($data['amount'], 0);
                else return number_format($data['amount'], 3);}),
            array('header'=>'Date', 'name'=>'transaction_date', 'headerHtmlOptions'=>array('class'=>'hidden-xs'), 
                'htmlOptions'=>array('class'=>'date hidden-xs'), 'value'=>'date("d/m/y", strtotime($data["transaction_date"]))'),
            array('header'=>'Time', 'name'=>'transaction_date', 'headerHtmlOptions'=>array('class'=>'hidden-xs'), 
                'htmlOptions'=>array('class'=>'time hidden-xs'), 'value'=>'date("H:i", strtotime($data["transaction_date"]))'));
      
        $this->widget('zii.widgets.grid.CGridView',array(
            'enablePagination'=>FALSE,
            //'hideHeader'=>TRUE,
            'template' => '{items}',
            'htmlOptions'=>array('class'=>'content'),
            'dataProvider' => $gridDataProvider,
            'columns'=>$gridColumns));      
      
        ?>
    </div>
    <div class="footer">
        <a class="btn btn-dark" href="<?php echo Yii::app()->createUrl('account/statement')?>">See all</a>
    </div>
    </div></div></div></div>    
    <div class="col-md-5"><div class="row">
        <div class="col-md-12"><div class="widget widget-accounts">
        <div class="title">Stampboxed email accounts</div>
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
                    'selectableRows' => 0,
                    'dataProvider'=>$mailboxdataprovider,
                    'columns'=>array(
                        array('name'=>'e_mail', 'htmlOptions'=>array('class'=>'email'),),
                        array('name'=>'status', 'htmlOptions'=>array('class'=>'status'), 'type'=>'raw', 'value'=>function($data) {
                        if ($data->status === 'A') return '<span class="glyphicon glyphicon-ok-sign"></span>'; else return '<span class="glyphicon glyphicon-exclamation-sign"></span>'; }),
                    )
                ));?>
            </div>
            <div class="footer">
                <a class="btn btn-dark" href="<?php echo Yii::app()->createUrl('usermailbox/create')?>"><i class="icon-plus-circled"></i>Add account</a>
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
                    <a class="btn btn-dark" href="<?php echo Yii::app()->createUrl('invite/index')?>">Invite friends</a>
                </div>
            </div>
        </div>
    </div>
</div>