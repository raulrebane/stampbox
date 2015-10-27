<?php $form=$this->beginWidget('CActiveForm',array('id'=>'usermailbox-form','enableAjaxValidation'=>true,)); 
?>
<div id="p-usermailbox" class="row">
    <div class="col-xs-6">
    <div class="widget widget-accounts"><div class="title">Setup new e-mail</div>
        <div class="row">
            <?php 
            echo $form->labelEx($model,'useremail', array('class' => 'col-xs-3'));
            echo $form->textField($model, 'useremail', array('class'=>'form-control', 'placeholder'=>'e-mail address'));
            ?>
        </div>
        <div class="row">
            <?php 
            echo $form->error($model, 'useremail');
            ?>
        </div>
        <div class="header-row">Select services:</div>
        <div class="row">
            <?php 
            echo $form->labelEx($model, 'sendingservice', array('class' => 'col-xs-3')); 
            echo $form->checkBox($model, 'sendingservice');
            ?>
        </div>
        <div class="row">
            <div class="col-xs-offset-3">This service enables sending stamped e-mails to other users.<br></div>
        </div>
        <div class="row">
            <?php 
            echo $form->labelEx($model, 'receivingservice', array('class' => 'col-xs-3')); 
            echo $form->checkBox($model, 'receivingservice');
            ?>
        </div>
        <div class="row">
            <div class="col-xs-offset-3">This service enables receiving credits for e-mails that are stamped and 
                sent to you. You can receive up to 0.79 EUR for each e-mail.<br></div>
        </div>
        <div class="row">
            <?php 
            echo $form->labelEx($model, 'sortingservice', array('class' => 'col-xs-3')); 
            echo $form->checkBox($model, 'sortingservice');
            ?>
        </div>
        <div class="row">
            <div class="col-xs-offset-3">This service sorts automatically incoming e-mail between inbox and no-stamp-emails 
                folder based on whether e-mail is stamped or not.<br></div>
        </div>
        <div class="header-row"></div>
        <?php $this->widget('zii.widgets.jui.CJuiButton', array(
			'buttonType'=>'submit',
			'caption'=>'Save',
                        'name'=>'emailbtn',
                        'htmlOptions'=>array('class'=>'btn btn-aqua')
            )); ?>
        <?php $this->widget('zii.widgets.jui.CJuiButton', array(
			'buttonType'=>'submit',
			'caption'=>'Cancel',
                        'name'=>'emailbtn',
                        'htmlOptions'=>array('class'=>'btn btn-default')
            )); ?>
    </div>
    </div>
</div>
<?php 
$this->widget('ext.ibutton.IButton', array('selector'  => ':checkbox',
    'options' =>array('labelOn'=>'Yes','labelOff'=>'No')));
$this->endWidget(); 
?>