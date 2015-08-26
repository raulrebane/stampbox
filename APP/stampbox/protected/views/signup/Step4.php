<?php
/* @var $this RegisterController */
/* @var $model Register */
/* @var $form CActiveForm */

$this->pageTitle = Yii::app()->name . ' - Register -> Step4';
?>
<div class="register-form">
    <h2>Services configuration for <b><?php echo Yii::app()->user->name ?></b></h2>
    <div class="row step-a">
        <div class="col-md-12">
            <?php
            $form = $this->beginWidget('CActiveForm', array('id' => 'Step4', 'htmlOptions' => array('class' => "register", 'role' => "form")));
            ?>
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
                 
            <button type="submit" class="btn btn-default"></button>
            <div class="help">Help</div>
        </div>
    </div>
</div>
<?php
$this->widget('ext.ibutton.IButton', array('selector'  => ':checkbox',
    'options' =>array('labelOn'=>'Yes','labelOff'=>'No')));
$this->endWidget();
unset($form);
?>
