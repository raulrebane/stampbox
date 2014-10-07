<?php $form=$this->beginWidget('CActiveForm',array(
	'id'=>'usermailbox-form',
	'enableAjaxValidation'=>false,
)); 

    echo $form->labelEx($model,'e_mail');
    echo $form->textField($model, 'e_mail', array('class'=>'form-control', 'placeholder'=>'e-mail address'));
    echo $form->error($model, 'e_mail');

    echo $form->labelEx($model,'e_mail_username');
    echo $form->textField($model, 'e_mail_username', array('class'=>'form-control', 'placeholder'=>'e-mail account username'));
    echo $form->error($model, 'e_mail_username');

    echo $form->labelEx($model,'e_mail_password');
    echo $form->passwordField($model, 'e_mail_password', array('class'=>'form-control', 'placeholder'=>'e-mail account password'));
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
                        'htmlOptions'=>array('class'=>'btn btn-default')
		)); ?>
</div>

<?php $this->endWidget(); ?>