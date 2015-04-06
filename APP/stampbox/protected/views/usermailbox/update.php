<div id="p-usermailbox" class="row">
    <div class="col-md-4">
    <div class="widget widget-accounts"><div class="title">Change e-mail account</div>
    <div class="content">
        <?php $form=$this->beginWidget('CActiveForm',array(
                'id'=>'usermailbox-form',
                'enableAjaxValidation'=>false,
                'htmlOptions' => array('class'=>"form register"),
        )); 
        echo $form->labelEx($model,'e_mail');
        echo $form->textField($model, 'e_mail', array('class'=>'form-control', 'placeholder'=>'e-mail address', 'readonly'=>true));

        echo $form->labelEx($model,'e_mail_username');
        echo $form->textField($model, 'e_mail_username', array('class'=>'form-control', 'placeholder'=>'e-mail account username'));
        echo $form->error($model, 'e_mail_username');

        echo $form->labelEx($model,'e_mail_password');
        echo $form->textField($model, 'e_mail_password', array('class'=>'form-control', 'placeholder'=>'e-mail account password'));
        echo $form->error($model, 'e_mail_password');

        echo $form->labelEx($model,'status');
        echo $form->dropDownList($model, 'status', array('A' => 'Active', 'N' => 'Disabled'));
        echo $form->error($model, 'status');
        ?>
    <div class="form-actions">
	<?php $this->widget('zii.widgets.jui.CJuiButton', array(
			'buttonType'=>'submit',
			'caption'=>'Save',
                        'name'=>'emailbtn',
                        'htmlOptions'=>array('class'=>'btn btn-active')
		)); ?>
    </div>
    </div>
    </div>
    </div>
</div>
<?php $this->endWidget(); ?>