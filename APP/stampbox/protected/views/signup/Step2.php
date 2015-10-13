<?php
/* @var $this RegisterController */
/* @var $model Register */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Register -> Step2';
?>
<div class="register-form">
    <h2>Your e-mail <b><?php echo Yii::app()->user->name ?></b> is now registered</h2>
    <div class="row step-a">
        <div class="col-md-6">
            <div class="feature">
                <br><h3>To get you started with stampbox service we have credited your account with 100 <b>free</b> stamps.</h3>
            </div>
            <div class="feature">
                Every time someone send you stamped e-mail you can earn real money.<br>
                To subscribe to receiving money from stamped e-mails sent to you, you need to configure IMAP access 
                for stampbox service to you e-mail account.
            </div>
        </div>
        <div class="col-md-6 darker">
            <?php
            $form = $this->beginWidget('CActiveForm', array('id' => 'Step2', 'htmlOptions' => array('class' => "register", 'role' => "form")));
            ?>
            <div class="row">
                <?php 
                echo $form->labelEx($model, 'sendingservice', array('class' => 'col-xs-4')); 
                echo $form->checkBox($model, 'sendingservice');
                ?>
            </div>
            <div class="row">
                <div class="col-xs-offset-3">This service enables sending stamped e-mails to other users.<br></div>
            </div>
            <div class="row">
                <?php 
                echo $form->labelEx($model, 'receivingservice', array('class' => 'col-xs-4')); 
                echo $form->checkBox($model, 'receivingservice');
                ?>
            </div>
            <div class="row">
                <div class="col-xs-offset-3">This service enables receiving credits for e-mails that are stamped and 
                    sent to you. You can receive up to 0.79 EUR for each e-mail.<br></div>
            </div>
            <div class="row">
                <?php 
                echo $form->labelEx($model, 'sortingservice', array('class' => 'col-xs-4')); 
                echo $form->checkBox($model, 'sortingservice');
                ?>
            </div>
            <div class="row">
                <div class="col-xs-offset-3">This service sorts automatically incoming e-mail between inbox and no-stamp-emails 
                    folder based on whether e-mail is stamped or not.<br></div>
            </div>
            <button type="submit" class="btn btn-default"></button>
        </div>
    </div>
</div>
<?php
$this->widget('ext.ibutton.IButton', array('selector'  => ':checkbox',
    'options' =>array('labelOn'=>'Yes','labelOff'=>'No')));
$this->endWidget();
//unset($form);
?>
