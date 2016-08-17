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
$whitelistitems = Yii::app()->db->createCommand(array(
            'select'=> array('*'),
            'from'=> 'ds.t_whitelist',
            'where'=> 'customer_id = :1',
            'limit'=> '10',
            'params'=> array(':1'=>Yii::app()->user->getId()),
        ))->queryAll();

foreach(Yii::app()->user->getFlashes() as $key => $message) {
        echo '<div class="alert alert-' .$key .'">' .$message ."</div>\n";
}
?>
<div class="row">
<div class="col-xs-12 col-md-5 col-lg-5">
    <div class="widget widget-balance">
        <div class="title">You have</div>
        <div class="content">
            <div class="balance"><?php echo $stampcount['stamps_bal'] ?></div>
            <div class="balance suffix">stamps and </div>
            <div class="balance"><?php echo Yii::app()->numberFormatter->formatCurrency($stampcount['points_bal'], 'EUR') ?></div> 
            <div class="balance suffix">credits</div>
            <a class="btn btn-aqua" href="<?php echo Yii::app()->createUrl('shop/buy')?>">Buy stamps</a>
        </div>
    </div>
</div>

<div class="col-xs-12 col-md-7">
    <div class="widget widget-invitations">
    <div class="title">Invitations</div>
    <div class="dashboard-form">
        <?php 
            $model = new Invitations();
            $form = $this->beginWidget('CActiveForm',array(
            'id' => 'Invite',
            'action' => Yii::app()->createUrl('invite/index'), 
            'htmlOptions' => array('class' => 'form', 'role'=>'form'),));
            $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'model'=>$model,
                'name'=>'invited_email',
                'htmlOptions' => array('placeholder'=>'Email'),
                //'class'=>'form-control',
                'value'=>'',
                'source'=>$this->createUrl('whitelist/Autocomplete'),
                // additional javascript options for the autocomplete plugin
                'options'=>array('showAnim'=>'fold',),
            ));
            echo '<button type="submit" class="btn btn-aqua">Send invitation</button>';
        $this->endWidget();
        ?>        
        
    </div>
    </div>
</div>

</div>

<div class="col-md-12">
    <div class="widget widget-whitelist">
    <div class="title">My whitelist</div>
    <div class="content">
        <?php 
        $gridDataProvider = new CArrayDataProvider($whitelistitems, array('keyField'=>'e_mail', ));  

        $gridColumns = array(
            array('header'=>'E-mail', 'name'=>'e_mail', 'htmlOptions'=>array('class'=>'email')));
      
        $this->widget('zii.widgets.grid.CGridView',array(
            'enablePagination'=>FALSE,
            //'hideHeader'=>TRUE,
            'template' => '{items}',
            'htmlOptions'=>array('class'=>''),
            'dataProvider' => $gridDataProvider,
            'columns'=>$gridColumns));      
        ?>
    </div>
    <div class="footer dashboard-form">
        <?php 
            $model = new Whitelist();
            $form = $this->beginWidget('CActiveForm',array(
            'id' => 'Whitelist',
            'action' => Yii::app()->createUrl('whitelist/index'), 
            'htmlOptions' => array('class' => 'form', 'role'=>'form'),));
            $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'model'=>$model,
                'name'=>'e_mail',
                'htmlOptions' => array('placeholder'=>'Email'),
                //'class'=>'form-control',
                'value'=>'',
                'source'=>$this->createUrl('whitelist/Autocomplete'),
                // additional javascript options for the autocomplete plugin
                'options'=>array('showAnim'=>'fold',),
            ));
            echo '<button type="submit" class="btn btn-aqua">Add to whitelist</button>';
        $this->endWidget();
        ?>
    </div>
    </div>
</div>

<div class="col-md-12">
    <div class="widget widget-activity">
    <div class="title">Stamps and credits activity</div>
    <div class="content">
        <?php 
        $gridDataProvider = new CArrayDataProvider($lasttransactions, array('keyField'=>'transaction_id', ));  

        $gridColumns = array(
//            array('header'=>'', 'name'=>'type', 'htmlOptions'=>array('class'=>'type', 'width'=>"25"), 'type'=>'raw', 'value'=>function($data) {
//            if ($data['transaction_code'] == 'SCR' or $data['transaction_code'] == 'PDB') return ''; 
//            elseif ($data['amount']<0) return '<i class="sbicon-reply"></i>'; else return '<i class="sbicon-forward"></i>';}),
            array('header'=>'E-mail / Subject', 'name'=>'e_mail', 'htmlOptions'=>array('class'=>'email'), 'type'=>'raw', 'value'=>function($data) {
                if ($data['e_mail'] == NULL) return $data['description'];
                else return $data['e_mail'] .'<span>'.$data['subject'] .'</span>';}),
            array('header'=>'Stamps', 'name'=>'amount', 'headerHtmlOptions'=>array('class'=>'hidden-xs hidden-md'), 
                'cssClassExpression'=>'$data["amount"] < 0 ? "transaction neg" : "transaction"',
                'htmlOptions'=>array('class'=>'hidden-xs hidden-md'), 
                'value'=>function($data) { if ($data['transaction_code'][0] == 'S') return number_format($data['amount'], 0); else return '';}),
            array('header'=>'Credits', 'name'=>'amount', 'headerHtmlOptions'=>array('class'=>'hidden-xs hidden-md'), 
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
            'htmlOptions'=>array('class'=>''),
            'dataProvider' => $gridDataProvider,
            'columns'=>$gridColumns));      
      
        ?>
    </div>
    <div class="footer">
        <a class="btn btn-aqua" href="<?php echo Yii::app()->createUrl('account/statement')?>">See more...</a>
    </div>
    </div>
</div>    

<div class="col-md-12">
    <div class="widget widget-accounts">
        <div class="title">Stampboxed email's</div>
            <div class="content table-responsive">
                <?php $mailboxdataprovider = new CActiveDataProvider('usermailbox', array(
                    'criteria'=>array(
                    'condition'=>'customer_id='.Yii::app()->user->getId(),
                    'order'=>'e_mail ASC',),
                    'pagination'=>array('pageSize'=>20,),
                )); 
                
                $this->widget('zii.widgets.grid.CGridView', array(
                    //'hideHeader'=>TRUE,
                    'template' => '{items}',
                    'htmlOptions'=>array('class'=>''),
                    'selectableRows' => 0,
                    'enableSorting' => false,
                    'dataProvider'=>$mailboxdataprovider,
                    'columns'=>array(
                        array('name'=>'e_mail', 'htmlOptions'=>array('class'=>'email'),),
                        array('name'=>'sending_service', 'htmlOptions'=>array('class'=>'status', 'align'=>'center'), 'type'=>'raw', 'value'=>function($data) {
                            if ($data->sending_service === TRUE) return '<span class="glyphicon glyphicon-ok-sign"></span>'; else return '<span class="glyphicon glyphicon-ban-circle"></span>'; }),
                        array('name'=>'receiving_service', 'htmlOptions'=>array('class'=>'status', 'align'=>'center'), 'type'=>'raw', 'value'=>function($data) {
                            if ($data->receiving_service === TRUE) return '<span class="glyphicon glyphicon-ok-sign"></span>'; else return '<span class="glyphicon glyphicon-ban-circle"></span>'; }),
                        array('name'=>'sorting_service', 'htmlOptions'=>array('class'=>'status', 'align'=>'center'), 'type'=>'raw', 'value'=>function($data) {
                            if ($data->sorting_service === TRUE) return '<span class="glyphicon glyphicon-ok-sign"></span>'; else return '<span class="glyphicon glyphicon-ban-circle"></span>'; }),                                
                        array('class'=>'CButtonColumn','template'=>'{configure}', 
                                'htmlOptions'=>array('class'=>'status'),
                                'buttons'=>array('configure' => array(
                                    'label'=>'',
                                    'options'=>array('class'=>'glyphicon glyphicon-wrench'),
                                    //'imageUrl'=>Yii::app()->request->baseUrl.'/images/btn-delete.png',
                                    'url'=>'Yii::app()->createUrl("usermailbox/update", array("email"=>$data->e_mail))')
                        )),                                    
                    )
                ));?>
            </div>
            <div class="footer">
                <a class="btn btn-aqua" href="<?php echo Yii::app()->createUrl('usermailbox/create')?>"><i class="sbicon-plus-circled"></i>Add new e-mail</a>
            </div>
        </div>
</div>
