<?php
?>
<div class="row">
    <div class="col-xs-12 col-sm-10 col-md-8 col-lg-8 dialog-form" style="padding:20px;">
    <div class="widget widget-accounts">
        <div class="title">Stampbox new e-mail</div>
        <div class="header-row"></div>
    <?php
        $form=$this->beginWidget('CActiveForm',array('id'=>'usermailbox-form',
            'htmlOptions' => array('class' => 'form', 'role'=>'form'), 
            'enableAjaxValidation'=>false,));
        echo $form->hiddenField($model, 'mailtype');
    ?>
    <div class="row">
        <?php echo $form->textField($model, 'useremail', array('class' => '', 'placeholder' => 'e-mail')); ?>
    </div>
    <div class="row">
        <?php echo $form->error($model, 'useremail', array('class' => 'alert alert-danger')); ?>
    </div>

    <div class="row">
        <div class="col-xs-1">
        <?php echo $form->checkBox($model, 'sendingservice');?>
        </div>
        <div class="col-xs-11">
        <?php echo $form->labelEx($model, 'sendingservice', array('class' => '')); ?>
         - This service enables sending stamped e-mails to other users.            
        </div>
    </div>
    <div class="row">
        <div class="col-xs-1">
        <?php echo $form->checkBox($model, 'receivingservice'); ?>
        </div>
        <div class="col-xs-11">
        <?php echo $form->labelEx($model, 'receivingservice', array('class' => '')); ?>
        - This service enables receiving credits for e-mails that are stamped and
        sent to you. You can receive up to 0.07 EUR for each e-mail.
        </div>
    </div>
    <div class="row">
        <div class="col-xs-1">
            <?php echo $form->checkBox($model, 'sortingservice'); ?>
        </div>
        <div class="col-xs-11">
            <?php echo $form->labelEx($model, 'sortingservice', array('class' => ''));?>
            - This service sorts automatically incoming e-mail between inbox and no-stamp-emails
            folder based on whether e-mail is stamped or not.
        </div>
    </div>
        <div id="Extendedsettings" <?php if ($model->receivingservice == 0 AND $model->sortingservice == 0) {
            echo 'style="display : none;"';}?> >
    <div id="username" <?php if ($model->registereddomain->status == 'A' AND $model->registereddomain->incoming_auth <> 'OTHER') { echo 'style="display : none;"';}?>>
        <div class="row">
            <div class="col-xs-3"><?php echo $form->labelEx($model, 'emailusername', array('class' => ''));?></div>
            <div class="col-xs-9"><?php echo $form->textField($model, 'emailusername', array('class' => '', 'placeholder' => 'e-mail login name'));?></div> 
        </div>
        <div class="row">
            <?php echo $form->error($model, 'emailusername', array('class' => 'alert alert-danger')); ?>
        </div>
    </div>
        <div class="row">
            <div class="col-xs-3"><?php echo $form->labelEx($model, 'emailpassword', array('class' => ''));?></div>
            <div class="col-xs-9"><?php echo $form->passwordField($model, 'emailpassword', array('class' => '', 'placeholder' => 'password for e-mail')); ?></div>
        </div>
        <div class="row">
            <?php echo $form->error($model, 'emailpassword', array('class' => 'alert alert-danger')); ?>
        </div>
    <div id="serversettings" <?php if ($model->registereddomain->status == 'A') { echo 'style="display : none;"';}?>>
        <div class="row">
            <div class="col-xs-3 col-xs-offset-1"><?php echo $form->labelEx($model, 'incoming_hostname', array('class' => ''));?></div>
            <div class="col-xs-8"><?php echo $form->textField($model, 'incoming_hostname', array('class' => '', 'placeholder' => 'e-mail server name')); ?></div>
        </div>
        <div class="row">
            <?php echo $form->error($model, 'incoming_hostname', array('class' => 'alert alert-danger')); ?>
        </div>
        <div class="row">
            <div class="col-xs-3 col-xs-offset-1"><?php echo $form->labelEx($model, 'incoming_port', array('class' => ''));?></div>
            <div class="col-xs-3"><?php echo $form->numberField($model, 'incoming_port', array('class' => 'form-control ', 'placeholder' => 'Port'));?></div>
        </div>
        <div class="row">
            <?php echo $form->error($model, 'incoming_port', array('class' => 'alert alert-danger')); ?>
        </div>
        <div class="row">
            <div class="col-xs-3 col-xs-offset-1"><?php echo $form->labelEx($model, 'incoming_socket_type', array('class' => ''));?></div>
            <div class="col-xs-3"><div class="select-style">
                <?php echo $form->dropDownList($model, 'incoming_socket_type', array('NULL' => 'None', 'ssl' => 'SSL', 'tls' => 'TLS'), array('class' => 'form-control '));?></div>
           </div>
        </div>
        <div class="row">
            <?php echo $form->error($model, 'incoming_socket_type', array('class' => 'alert alert-danger'));?>
        </div>
    </div>
    </div>
        <div class="header-row"></div>
        <div class="row"><div class="col-xs-6">
            <?php $this->widget('zii.widgets.jui.CJuiButton', array(
                'buttonType'=>'submit',
                'caption'=>'Save',
                'name'=>'emailbtn',
                'htmlOptions'=>array('class'=>'btn btn-aqua')
                )); ?>
            </div><div class="col-xs-6">
            <?php $this->widget('zii.widgets.jui.CJuiButton', array(
                'buttonType'=>'submit',
                'caption'=>'Cancel',
                'name'=>'cancelbtn',
                'htmlOptions'=>array('class'=>'btn btn-default')
                )); ?>
            </div>
        </div>
    </div>
    </div>
</div>
<?php $this->endWidget(); unset($form); ?>
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
</script>
<script type="text/javascript">
$('#NewMailbox_useremail').change(function() {
    var url = '<?php echo Yii::app()->createUrl('signup/GetEmailServerParams');?>';
    url = url + "&email=" + $("#NewMailbox_useremail").val();
    $.getJSON(url, function(data) {
        if (data.status == "A") {$('#serversettings').hide();} else {$('#serversettings').show();}
        if (data.status == "A" && data.incoming_auth !== "OTHER") {$('#username').hide();} else {$('#username').show();}
        $("#NewMailbox_incoming_hostname").val(data.incoming_hostname);
        $("#NewMailbox_incoming_port").val(data.incoming_port);
        $("#NewMailbox_incoming_socket_type").val(data.incoming_socket_type);
    })
});
</script>
