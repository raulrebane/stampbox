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
            $form = $this->beginWidget('CActiveForm', array('id' => 'Step3', 'htmlOptions' => array('class' => "register", 'role' => "form")));
            if ($model->registereddomain == NULL || $model->registereddomain->incoming_auth == 'OTHER') {
                echo $form->labelEx($model, 'emailusername', array('class' => 'col-xs-4'));
                echo $form->textField($model, 'emailusername', array('class' => 'form-control col-xs-8', 'placeholder' => 'e-mail login name'));
                echo $form->error($model, 'emailusername', array('class' => 'col-xs-offset-4'));
            } else {
                
            }
            ?>
            <div class="row">
                <?php echo $form->labelEx($model, 'emailpassword', array('class' => 'col-xs-4')); ?>
                <?php echo $form->passwordField($model, 'emailpassword', array('class' => 'form-control col-xs-8', 'placeholder' => 'password for e-mail')); ?>
            </div>
            <div class="row">
                <?php echo $form->labelEx($model, 'incoming_hostname', array('class' => 'col-xs-4'));?>
            </div>
            <div class="row">
                
            </div>

            <div class="row">
                <?php echo $form->labelEx($model, 'incoming_port', array('class' => 'col-xs-4'));
                if ($model->registereddomain->status <> 'A') {
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
                if ($model->registereddomain->status <> 'A') {
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
            <button type="submit" class="btn btn-default"></button>
            <div class="help">Help</div>
        </div>
    </div>
</div>
<?php
$this->endWidget();
unset($form);
?>
