<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    
echo '<div class="box span12 stampwhite"><div class="content-colauto">';
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm',
    array('id' => 'horizontalForm','type' => 'horizontal',));
echo $form->dateRangeRow($model,'statement_range',
        array('htmlOptions' => array(
        'hint' => 'Click to select date range for account statement.',),
        'callback' => 'js:function(start, end){console.log(start.toString("MMMM d, yyyy") + " - " + end.toString("MMMM d, yyyy"));}'),
        array('prepend' => '<i class="icon-calendar"></i>',)
);
echo '<div class="form-actions">';
$this->widget('bootstrap.widgets.TbButton',
    array('buttonType' => 'submit','type' => 'primary','label' => 'Show statement'));
echo '</div>';
$this->endWidget();
unset($form);
echo '</div></div>';

echo '<div class="box span12 stampwhite"><div class="content-colauto">';

        $gridDataProvider = new CArrayDataProvider($model->statement_grid, array('keyField'=>'transaction_id', ));  
        $gridColumns = array(
            array('name'=>'transaction_date', 'header'=>'Date'),
            array('name'=>'stamps', 'header'=>'Stamps'),
            array('name'=>'transaction_points', 'header'=>'Credits'),
            array('name'=>'description', 'header'=>'Description')
            );
        $gridDataProvider->getPagination()->setPageSize(2000);
        $this->widget('bootstrap.widgets.TbGridView',array(
            'id'=>'smallstatement-grid',
            'type'=>'bordered',
            'enablePagination'=>TRUE,
            'dataProvider' => $gridDataProvider,
            'template' => "{items}",
            'columns'=>$gridColumns));      
        echo '</div></div>';