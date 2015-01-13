<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$form = $this->beginWidget('CActiveForm',array(
    'id' => 'Account',
    //'type'=>'horizontal',
    'htmlOptions' => array('class'=>'form', 'role'=>'form'),
    ));
?>
<div id="p-statement" class="row">
    <div class="col-md-12 m-period-select">
        <h1>Period</h1>
        <ul>
            <li><a href="<?php echo Yii::app()->createUrl('account/statement')?>&period=lastmonth">Last month</a></li>
            <li><a href="<?php echo Yii::app()->createUrl('account/statement')?>&period=thismonth">This month</a></li>
            <li><a href="<?php echo Yii::app()->createUrl('account/statement')?>&period=lastweek">Last week</a></li>
            <li><a href="<?php echo Yii::app()->createUrl('account/statement')?>&period=thisweek">This week</a></li>
            <li><a href="<?php echo Yii::app()->createUrl('account/statement')?>&period=yesterday">Yesterday</a></li>
            <li><a href="<?php echo Yii::app()->createUrl('account/statement')?>&period=today">Today</a></li>
            <li class="date-selectors">
                <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                        'name'=>'from',
                        'model'=>$model,
                        'attribute'=>'from_date',
                        // additional javascript options for the date picker plugin
                        'options'=>array(
                            'dateFormat'=>'dd-mm-yy',
                            'maxDate' => 'today -1'
                        ),
                        'htmlOptions'=>array(),
                        'value'=>date('01-m-Y'),
                    ));   
                ?>
                -
                <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                        'name'=>'to',
                        'model'=>$model,
                        'attribute'=>'to_date',
                        // additional javascript options for the date picker plugin
                        'options'=>array(
                            'dateFormat'=>'dd-mm-yy',
                            'maxDate' => 'today'
                        ),
                        'htmlOptions'=>array(),
                        'value'=>date('d-m-Y')
                    ));   
                ?>
                <button type="submit" name="refresh" class="btn btn-default">Show statement</button>
            </li>
        </ul>
        
    </div>
</div>
<div class="row">
    <div class="col-md-12">
    <div class="widget widget-activity"><div class="title">Account activity</div>
    <div class="content">
        <?php 
        $gridDataProvider = new CArrayDataProvider($model->statement_grid, array('keyField'=>'transaction_id',
                'pagination'=>array('pageSize'=>1000,)));  
        $gridColumns = array(
            array('name'=>'type', 'htmlOptions'=>array('class'=>'type', 'width'=>"25"), 'type'=>'raw', 'value'=>function($data) {
                if ($data['amount']<0) return '<i class="icon-reply"></i>'; else return '<i class="icon-forward"></i>';}),
            array('name'=>'info', 'htmlOptions'=>array('class'=>'email'), 'type'=>'raw', 'value'=>function($data) {
                if ($data['from_email'] == NULL) return $data['description'];
                elseif ($data['amount'] < 0) return $data['to_email'] .'<span>'.$data['subject'] .'</span>';
                else return $data['from_email'] .'<span>'.$data['subject'] .'</span>';}),
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
    </div>
    </div>
</div>

<?php
$this->endWidget(); 
unset($form);

?>