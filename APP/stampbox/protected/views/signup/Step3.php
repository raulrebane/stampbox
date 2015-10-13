<?php
/* @var $this RegisterController */
/* @var $model Register */
/* @var $form CActiveForm */

$this->pageTitle = Yii::app()->name . ' - Register -> Step3';
?>
<div class="register-form">
    <h2>IMAP settings for <b><?php echo Yii::app()->user->name ?></b></h2>
    <div class="row step-a">
        <div class="col-md-12">
            <?php
            $form = $this->beginWidget('CActiveForm', array('id' => 'Step3', 'htmlOptions' => array('class' => "register", 'role' => "form")));

            echo $form->hiddenField($model, 'maildomain');
            echo $form->hiddenField($model, 'mailtype');
            echo $form->hiddenField($model, 'incoming_auth');
            ?> 
            <div class="header-row">E-mail login settings</div>
            <?php
            if ($model->registereddomain == NULL OR $model->registereddomain->incoming_auth == 'OTHER') {
                echo $form->labelEx($model, 'emailusername', array('class' => 'col-xs-4'));
                echo $form->textField($model, 'emailusername', array('class' => 'form-control col-xs-8', 'placeholder' => 'e-mail login name'));
                echo $form->error($model, 'emailusername', array('class' => 'col-xs-offset-4'));
            } else {
                echo $form->labelEx($model, 'emailusername', array('class' => 'col-xs-4'));
                echo $form->textField($model, 'emailusername', array('class' => 'form-control col-xs-8', 'disabled' => true));
            }
            ?>
            <div class="row">
                <?php echo $form->labelEx($model, 'emailpassword', array('class' => 'col-xs-4')); ?>
                <?php echo $form->passwordField($model, 'emailpassword', array('class' => 'form-control col-xs-8', 'placeholder' => 'password for e-mail')); ?>
            </div>
            <div class="row">
                <?php echo $form->error($model, 'emailpassword', array('class' => 'col-xs-offset-4')); ?>
            </div>

            <div class="header-row">E-mail server settings</div>
            <div class="row">
                <?php echo $form->labelEx($model, 'incoming_hostname', array('class' => 'col-xs-4'));
                if ($model->registereddomain == NULL OR $model->registereddomain->status <> 'A') {
                    echo $form->textField($model, 'incoming_hostname', array('class' => 'form-control col-xs-8', 'placeholder' => 'e-mail server name'));
                } else {
                    echo $form->textField($model, 'incoming_hostname', array('class' => 'form-control col-xs-8', 'disabled' => true));
                } ?>
            </div>
            <div class="row">
                <?php echo $form->error($model, 'incoming_hostname', array('class' => 'col-xs-offset-4')); ?>
            </div>

            <div class="row">
                <?php echo $form->labelEx($model, 'incoming_port', array('class' => 'col-xs-4'));
                if ($model->registereddomain == NULL OR $model->registereddomain->status <> 'A') {
                    echo $form->numberField($model, 'incoming_port', array('class' => 'form-control col-xs-4', 'placeholder' => 'Port'));
                } else {
                    echo $form->numberField($model, 'incoming_port', array('class' => 'form-control col-xs-4', 'disabled' => true));
                }?>
            </div>
            <div class="row">
                <?php echo $form->error($model, 'incoming_port', array('class' => 'col-xs-offset-4')); ?>
            </div>

            <div class="row">
                <?php echo $form->labelEx($model, 'incoming_socket_type', array('class' => 'col-xs-4'));
                if ($model->registereddomain == NULL OR $model->registereddomain->status <> 'A') {
                    echo '<div class="select-style">';
                    echo $form->dropDownList($model, 'incoming_socket_type', array('NULL' => 'None', 'ssl' => 'SSL', 'tls' => 'TLS'), array('class' => 'form-control col-xs-6'));
                    echo '</div>';
                } else {
                    echo $form->textField($model, 'incoming_socket_type', array('class' => 'form-control col-xs-4', 'disabled' => true));
                }?>
            </div>
            <div class="row">
                <?php echo $form->error($model, 'incoming_socket_type', array('class' => 'col-xs-offset-4'));?>
            </div>
            <button type="submit" class="btn btn-active"></button>
            <?php $this->endWidget(); unset($form); ?>            
            <div class="help"><button type="button" class="btn btn-aqua" data-toggle="modal" data-target="#SignupHelpDlg">Help</button></div>
        </div>
    </div>
</div>

<!-- Modal -->
            <div class="modal fade" id="SignupHelpDlg" tabindex="-1" role="dialog" aria-labelledby="signuphelp" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header"></div>
                        <div class="modal-body">
                            <?php
                            ?>
                        </div>
                        <div class="modal-footer"></div>
                    </div>
                </div>
            </div>