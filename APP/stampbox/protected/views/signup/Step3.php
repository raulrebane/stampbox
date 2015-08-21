<?php
/* @var $this RegisterController */
/* @var $model Register */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Register -> Step3';
?>
<div class="register-form">
    <h2>IMAP settings for <b><?php echo Yii::app()->user->name ?></b></h2>
    <div class="row step-a">
        <div class="col-md-12 darker">
            <?php $form = $this->beginWidget('CActiveForm', array('id' => 'Step3', 'htmlOptions' => array('class'=>"register", 'role'=>"form"))); 
            
            echo $form->hiddenField($model, 'maildomain');
            echo $form->hiddenField($model, 'mailtype');
            echo $form->hiddenField($model, 'incoming_auth');

            if ($model->registereddomain->incoming_auth !== 'EMAIL') {
                echo $form->labelEx($model,'emailusername');
                echo $form->textField($model, 'emailusername', array('class'=>'form-control', 'placeholder'=>'e-mail login name'));
                echo $form->error($model, 'emailusername');
            }
            else {
            }
            
            echo '<div class="row">';
            echo $form->labelEx($model,'emailpassword', array('class'=>'col-sm-4'));
            echo $form->textField($model, 'emailpassword', array('class'=>'col-sm-6', 'placeholder'=>'password for e-mail'));
            echo '</div><div class="row">';
            echo $form->error($model, 'emailpassword');
            echo '</div>';
            
            echo '<div class="row">';
            echo $form->labelEx($model,'incoming_hostname', array('class'=>'col-sm-4'));
            echo $form->textField($model, 'incoming_hostname', array('class'=>'col-sm-6', 'placeholder'=>'e-mail server name'));
            echo '</div><div class="row">';
            echo $form->error($model, 'incoming_hostname');
            echo '</div>';

            echo '<div class="row">';
            echo $form->labelEx($model,'incoming_port', array('class'=>'col-sm-4'));
            echo $form->numberField($model, 'incoming_port', array('class'=>'col-sm-6', 'placeholder'=>'Port'));    
            echo '</div><div class="row">';
            echo $form->error($model, 'incoming_port');
            echo '</div>';

            echo '<div class="row">';
            echo $form->labelEx($model,'incoming_socket_type', array('class'=>'col-sm-4'));
            echo '<div class="select-style">';
            echo $form->dropDownList($model, 'incoming_socket_type',
                      array('NULL'=>'None', 'ssl' => 'SSL', 'tls' => 'TLS'), array('class'=>'col-sm-6'));
            echo '</div>';
            echo '</div><div class="row">';
            echo $form->error($model, 'incoming_socket_type');
            echo '</div>';

            ?>
            <button type="submit" class="btn btn-default"></button>
        </div>
    </div>
</div>
<?php
    $this->endWidget(); 
    unset($form);    
?>
