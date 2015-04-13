<?php
/* @var $this RegisterController */
/* @var $model Register */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Register -> Step1';
echo CHtml::errorSummary($model, '', '', array('class'=>"m-flash"));
?>
<div class="register-form">
    <h1>Create <b>your</b> first <b>Stampbox</b> in <b>4</b> steps</h1>
    <div class="row step-a">
        <div class="col-md-6">
            <div class="steps">
            <div class="step active step-1">
                <span>1</span>
                <small>Register e-mail</small>
            </div>
            <div class="step step-2">
                <span>2</span>
                <small>IMAP mail server</small>
            </div>
            <div class="step step-3">
                <span>3</span>
                <small>e-mail sending server</small>
            </div>
            <div class="step step-4">
                <span>4</span>
                <small>Invite friends</small>
            </div>
        </div>
<!--        <div class="feature">
            <p>Works with all popular e-mail providers</p>
            <img src="images/register-feature-services.png">
        </div>
        <div class="feature">
            <p>Works on all of your devices</p>
            <img src="images/register-feature-devices.png">
        </div>
-->
            Stampbox works as add-on to your existing e-mail mailbox and you need to configure for our server 
            to access your mailbox.<br>
            <ul>
                <li><b>E-mail</b> - Enter your e-mail address</li>
                <li><b>E-mail username</b> - Typically your e-mail username is the same as your e-mail, but in some cases your e-mail username
                is different from your e-mail address. Use the same what you use when you log into your e-mail account. You can change this information 
                later.</li>
                <li><b>E-mail password</b> - Enter your password that you use to log into your e-mail. We take security of your password very seriously and store it in 
                encrypted format. Your password is safe with us. You can read more about security here.</li>
            </ul>
        </div>
        <div class="col-md-6 darker">

<?php        
        $form = $this->beginWidget('CActiveForm',array(
            'id' => 'Step1',
        'htmlOptions' => array('class'=>"register", 'role'=>"form"),
        'enableAjaxValidation'=>true)); 

        echo $form->labelEx($model,'useremail');
        echo $form->EmailField($model, 'useremail', array('class'=>'form-control', 'placeholder'=>'Enter email'));
        echo $form->error($model, 'useremail',array('validateOnChange'=>true));
        
        echo $form->labelEx($model, 'emailusername');
        echo $form->textField($model, 'emailusername', array('class'=>'form-control', 'placeholder'=>'E-mail account username'));
        //echo $form->error($model, 'emailusername', '', FALSE);

        echo $form->labelEx($model, 'emailpassword');
        echo $form->passwordField($model, 'emailpassword', array('class'=>'form-control', 'placeholder'=>'E-mail password'));
        //echo $form->error($model, 'emailpassword', '', FALSE);
        
        echo $form->labelEx($model, 'agreewithterms');
        echo $form->checkBox($model, 'agreewithterms', array('class'=>'push-left inline'));
        //echo $form->labelEx($model, 'agreewithterms');
        //echo $form->error($model, 'agreewithterms', '', FALSE);
?>
            <!--        <div class="form-actions">        
-->         
            <button type="submit" class="btn btn-default"></button>
<!--        </div>
-->
        </div>
    </div>
</div>
<?php
$this->endWidget(); 
//unset($form);