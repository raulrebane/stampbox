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
                Every time someone send you stamped e-mail you can earn real money.<br>
                To subscribe to receiving money from stamped e-mails sent to you, you need to configure IMAP access 
                for stampbox service to you e-mail account.
                <?php $form = $this->beginWidget('CActiveForm',array(
                    'id' => 'Step2Continue',
                    'htmlOptions' => array('class' => 'form', 'role'=>'form'),));
                ?>
                <button type="submit" name="Continue" class="btn btn-aqua">Setup IMAP access</button>
                <?php $this->endWidget(); unset($form);?>
            </div>
            <div class="feature">
                <br><h3>To get you started with stampbox service we have credited your account with 100 <b>free</b> stamps.</h3>
            </div>
        </div>
        <div class="col-md-6 darker">
            <div class="feature">
                You can configure IMAP access later from your service overview page or from the mailbox menu.
                <?php $form = $this->beginWidget('CActiveForm',array(
                    'id' => 'Step2Skip',
                    'htmlOptions' => array('class' => 'form', 'role'=>'form'),));
                ?>
                <button type="submit" name="Skip" class="btn btn-default">Skip configuration</button>
                <?php $this->endWidget(); unset($form);?>
            </div>
        </div>
    </div>
</div>
