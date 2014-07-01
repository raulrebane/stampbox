<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div class="row">
    <div class="col-md-12">
    <div class="widget widget-activity"><div class="title">Activity</div>
    <div class="content">
        <?php   
        /*
        $gridColumns = array(
            array('name'=>'type', 'htmlOptions'=>array('class'=>'type', 'width'=>"25"), 'type'=>'raw', 'value'=>function($data) {
                if ($data['amount']<0) return '<i class="icon-reply"></i>'; else return '<i class="icon-forward"></i>';}),
            array('name'=>'from_email', 'htmlOptions'=>array('class'=>'email'), 'type'=>'raw', 'value'=>function($data) {
                return $data['from_email'] .'<span>'.$data['subject'] .'</span>';}),
            array('name'=>'amount', 'htmlOptions'=>array('class'=>'transaction')),
            array('name'=>'transaction_date', 'htmlOptions'=>array('class'=>'date'), 'value'=>'date("d/m/y", strtotime($data["transaction_date"]))'),
            array('name'=>'transaction_date', 'htmlOptions'=>array('class'=>'time'), 'value'=>'date("H:i", strtotime($data["transaction_date"]))'));
        
        */ 
        $gridColumns = array(
            array(
                'id' => 'selectedIds',
                'class' => 'CCheckBoxColumn',
                'selectableRows'=>100,
                'header'=>'Invite',
                'name'=>'e-mail',),
            array('name'=>'name', 'header'=>'Name'),
            array('name'=>'invited_email', 'header'=>'E-mail'),
            array('name'=>'from_count', 'header'=>'# of mails'),
            array('name'=>'last_email_date', 'header'=> 'Last e-mail')
        );
        $this->widget('zii.widgets.grid.CGridView',array(
            'enablePagination'=>FALSE,
            //'hideHeader'=>TRUE,
            'template' => '{items}',
            'htmlOptions'=>array('class'=>'content'),
            'dataProvider' => $dataProvider,
            'columns'=>$gridColumns
            ));      
        ?>
    </div>
    </div>
    </div>
</div>