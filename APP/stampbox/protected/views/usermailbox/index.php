<div id="p-usermailbox" class="row">
    <div class="col-md-12">
    <div class="widget widget-accounts"><div class="title">Stampboxed e-mail accounts</div>

<?php
$gridcolumns = array(
    array('name'=>'e_mail', 'header'=>'e-mail'),
    array('name'=>'e_mail_username', 'header'=>'Username', 'htmlOptions'=>array('class'=>'hidden-xs'), 
           'headerHtmlOptions'=>array('class'=>'hidden-xs')),
    array('name'=>'e_mail_password', 'header'=>'Password', 'htmlOptions'=>array('class'=>'hidden-xs'), 
           'headerHtmlOptions'=>array('class'=>'hidden-xs')),
    array('name'=>'status', 'header'=>'Status', 'htmlOptions'=>array('class'=>'status'), 'type'=>'raw', 'value'=>function($data) {
        if ($data->status == 'A') return '<i class="icon-ok"></i>'; else return '<i class="icon-cw"></i>'; }),
    array('name'=>'sending_service', 'htmlOptions'=>array('class'=>'status', 'align'=>'center'), 'type'=>'raw', 'value'=>function($data) {
        if ($data->sending_service === TRUE) return '<span class="glyphicon glyphicon-ok-sign"></span>'; else return '<span class="glyphicon glyphicon-ban-circle"></span>'; }),
    array('name'=>'receiving_service', 'htmlOptions'=>array('class'=>'status', 'align'=>'center'), 'type'=>'raw', 'value'=>function($data) {
        if ($data->receiving_service === TRUE) return '<span class="glyphicon glyphicon-ok-sign"></span>'; else return '<span class="glyphicon glyphicon-ban-circle"></span>'; }),
    array('name'=>'sorting_service', 'htmlOptions'=>array('class'=>'status', 'align'=>'center'), 'type'=>'raw', 'value'=>function($data) {
        if ($data->sorting_service === TRUE) return '<span class="glyphicon glyphicon-ok-sign"></span>'; else return '<span class="glyphicon glyphicon-ban-circle"></span>'; }),                                
    array('class'=>'CButtonColumn','template'=>'{configure}{remove}', 'htmlOptions'=>array('class'=>'status'),
        'buttons'=>array('configure' => array('label'=>'','options'=>array('class'=>'glyphicon glyphicon-wrench'),
            'url'=>'Yii::app()->createUrl("usermailbox/update", array("email"=>$data->e_mail))'),
            'remove' => array('label'=>'','options'=>array('class'=>'glyphicon glyphicon-remove'),
            'url'=>'Yii::app()->createUrl("usermailbox/delete", array("email"=>$data->e_mail))')))
);
?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'template'=>"{items}",
    'columns'=>$gridcolumns,
    'htmlOptions'=>array('class'=>'content')
)); ?>
    <div class="footer">
        <button type="button" class="btn btn-primary btn-aqua" data-toggle="modal" data-target="#MailboxDialog">
            Stampbox new e-mail
        </button>    </div>
        
    </div>
    </div>
</div>
<!-- Modal add or update-->
<div class="modal fade" id="MailboxDialog" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="dialog-form" style="padding:20px;">
            <?php
                $model=new NewMailbox();
                $form = $this->beginWidget('CActiveForm',array(
                    'id' => 'NewMailbox','action' => Yii::app()->createUrl('usermailbox/new'), 
                    'htmlOptions' => array('class' => 'form form-inline', 'role'=>'form'),
                    'enableClientValidation'=>true,
                    'clientOptions' => array('validateOnSubmit' => true,'validateOnChange'=>false,
                        'afterValidate' => 'js:function(form, data, hasError) {
                            if (!hasError){
                                str = $("#NewMailbox").serialize() + "&ajax=NewMailbox";
                                $.ajax({type: "POST", url: "' . Yii::app()->createUrl('usermailbox/new') . '",
                                data: str,
                                dataType: "json",
                                beforeSend : function() {$("#NewMailbox").attr("disabled",true);},
                                success: function(data, status) {
                                    if(data.savecomplete) {
                                        window.location = data.redirectUrl;}
                                    else {
                                        $.each(data, function(key, value) {
                                            var div = "#"+key+"_em_";
                                            $(div).text(value);
                                            $(div).show();
                                            });
                                        $("#NewMailbox").attr("disabled",false);
                                    }
                            },
                        });
                        return false;
                        }
                    }',
    ),
));?>
        <div class="form-group">
            <div class="row"><div class="col-xs-12">
            <?php
                echo $form->EmailField($model, 'useremail', array('class'=>'', 'id'=>'useremail', 'style'=>'width:100%','placeholder'=>'Enter email'));
            ?>
            </div></div>
            <div class="row">
                <div class="col-xs-1">
                    <?php echo $form->checkBox($model, 'sendingservice'); ?>
                </div>                        
                <div class="col-xs-3">
                    <?php echo $form->labelEx($model, 'sendingservice', array('class' => ''));?>
                </div>                        
                <div class="col-xs-8">This service enables sending stamped e-mails to other users.</div>
            </div>
            <div class="row">
                <div class="col-xs-1">
                    <?php echo $form->checkBox($model, 'receivingservice');?>
                </div>
                <div class="col-xs-3">
                    <?php echo $form->labelEx($model, 'receivingservice', array('class' => ''));?>
                </div>
                <div class="col-xs-8">This service enables receiving credits for e-mails that are stamped and
            	  sent to you. You can receive up to 0.79 EUR for each e-mail.</div>
            </div>
            <div class="row">
                <div class="col-xs-1">
                    <?php echo $form->checkBox($model, 'sortingservice'); ?>
                </div>
                <div class="col-xs-3">
                    <?php echo $form->labelEx($model, 'sortingservice', array('class' => ''));?>
                </div>
                <div class="col-xs-8">This service sorts automatically incoming e-mail between inbox and no-stamp-emails
            	  folder based on whether e-mail is stamped or not.</div>
            </div>
                <div class="row">
                <div id="Extendedsettings" style="display : none;">
                <div class="row">
                    <div class="col-xs-5">
                    <?php echo $form->labelEx($model, 'emailusername', array('class' => ''));?>
                    </div>
                    <div class="col-xs-7">
                    <?php echo $form->textField($model, 'emailusername', array('class' => '', 'placeholder' => 'e-mail login name'));?>
                    </div>
                </div>
                <div class="row">
                    <?php echo $form->error($model, 'emailusername', array('class' => 'col-xs-offset-5')); ?>
                </div>
                <div class="row">
                    <div class="col-xs-5">
                    <?php echo $form->labelEx($model, 'emailpassword', array('class' => '')); ?>
                </div>
                <div class="col-xs-7">
                    <?php echo $form->passwordField($model, 'emailpassword', array('class' => '', 'placeholder' => 'e-mail login password')); ?>
                </div>
                <div class="row">
                    <?php echo $form->error($model, 'emailpassword', array('class' => 'col-xs-offset-5')); ?>
                </div>
                <div class="row">
                    <div class="col-xs-5">
                        <?php echo $form->labelEx($model, 'incoming_hostname', array('class' => '')); ?>
                    </div>
                    <div class="col-xs-7">
                        <?php echo $form->textField($model, 'incoming_hostname', array('class' => ''));?>
                    </div>
                </div>
                <div class="row">
                    <?php echo $form->error($model, 'incoming_hostname', array('class' => 'col-xs-offset-5')); ?>
                </div>
                <div class="row">
                    <div class="col-xs-5">
                        <?php echo $form->labelEx($model, 'incoming_port', array('class' => ''));?>
                    </div>
                    <div class="col-xs-7">
                        <?php echo $form->numberField($model, 'incoming_port', array('class' => ''));?>
                    </div>
                </div>
                <div class="row">
                    <?php echo $form->error($model, 'incoming_port', array('class' => 'col-xs-offset-5')); ?>
                </div>
                <div class="row">
                    <div class="col-xs-5">
                        <?php echo $form->labelEx($model, 'incoming_socket_type', array('class' => ''));?>
                    </div>
                    <div class="col-xs-7">
                        <?php echo '<div class="select-style">';
                        echo $form->dropDownList($model, 'incoming_socket_type', array('NULL' => 'None', 'ssl' => 'SSL', 'tls' => 'TLS'), array('class' => ''));
                        ?>
                    </div>
                </div>
                <div class="row">
                    <?php echo $form->error($model, 'incoming_socket_type', array('class' => 'col-xs-offset-5'));?>
                </div>
                </div>
                </div>
        </div>
        <div class="row"><div class="col-xs-6">
            <button type="submit" class="btn btn-aqua btn-block dialog-form-btn">Save</button>
        </div>
        <div class="col-xs-6">
            <button type="submit" class="btn btn-aqua btn-block dialog-form-btn">Cancel</button>
        </div>
        </div></div>
        <?php $this->endWidget();?>
    </div>
    </div>
</div>
<script type="text/javascript">
$('#NewMailbox_receivingservice').change(function() {
  if (document.getElementById('NewMailbox_receivingservice').checked || document.getElementById('NewMailbox_sortingservice').checked)  
    $('#Extendedsettings').show();
  if (document.getElementById('NewMailbox_receivingservice').checked == 0 && document.getElementById('NewMailbox_sortingservice').checked == 0)  
    $('#Extendedsettings').hide();
});
$('#NewMailbox_sortingservice').change(function() {
  if (document.getElementById('NewMailbox_receivingservice').checked || document.getElementById('NewMailbox_sortingservice').checked)  
    $('#Extendedsettings').show();
  if (document.getElementById('NewMailbox_receivingservice').checked == 0 && document.getElementById('NewMailbox_sortingservice').checked == 0)  
    $('#Extendedsettings').hide();
});
$('#useremail').change(function() {
    var url = '<?php echo Yii::app()->createUrl('signup/GetEmailServerParams');?>';
    url = url + "&email=" + $("#useremail").val();
    $.getJSON(url, function(data) {
        $("#NewMailbox_incoming_hostname").val(data.incoming_hostname);
        $("#NewMailbox_incoming_port").val(data.incoming_port);
        $("#NewMailbox_incoming_socket_type").val(data.incoming_socket_type);
    })
});
</script>
