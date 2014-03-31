<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    
    $gridDataProvider = new CArrayDataProvider($model->top_senders, array( 'id'=>'Email', ));  
    $gridDataProvider->getPagination()->setPageSize(100);
//    $gridDataProvider->setData($model->top_senders);
    $gridColumns = array(
        array(
            'id' => 'selectedIds',
            'class' => 'CCheckBoxColumn',
            'selectableRows'=>100,
            'header'=>'Invite',
            'name'=>'e-mail',
            ),
        array('name'=>'Name', 'header'=>'Name'),
        array('name'=>'e-mail', 'header'=>'E-mail'),
	array('name'=>'rcount', 'header'=>'# of mails'),
        );
//        var_dump($gridColumns);
//        var_dump($gridDataProvider);
    $this->widget('bootstrap.widgets.TbGridView',array(
        'id'=>'invitation-grid',
        'type'=>'bordered',
        'enablePagination'=>TRUE,
        'dataProvider' => $gridDataProvider,
        'template' => "{items}",
        'columns'=>$gridColumns));