<?php
/* @var $this RegisterController */
/* @var $model Register */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Register -> Step1';
echo CHtml::errorSummary($model, '', '', array('class'=>"m-flash"));
?>
<div class="register-form">
    <h1><b>Stampbox</b> your e-mail in <b>4</b> easy steps</h1>
    <div class="row step-a">
        <div class="col-md-6">
            <div class="feature">
                <p>Works with all popular e-mail providers</p>
                <img src="images/register-feature-services.png">
            </div>
            <div class="feature">
                <p>Works on all of your devices</p>
                <img src="images/register-feature-devices.png">
            </div>
        </div>
        <div class="col-md-6 darker">

<?php        
        $form = $this->beginWidget('CActiveForm',array(
            'id' => 'Step1',
        'htmlOptions' => array('class'=>"register", 'role'=>"form"),
        'enableAjaxValidation'=>true)); 
        echo $form->labelEx($model,'useremail', array('class'=>'col-xs-12'));
        echo $form->EmailField($model, 'useremail', array('class'=>'form-control col-xs-12', 'placeholder'=>'Enter email'));
        echo $form->error($model, 'useremail',array('validateOnChange'=>true));

        echo $form->labelEx($model, 'userpassword', array('class'=>'col-xs-12'));
        echo $form->passwordField($model, 'userpassword', array('class'=>'form-control col-xs-12', 'placeholder'=>'Choose password'));
        //echo $form->error($model, 'emailpassword', '', FALSE);

        echo $form->checkBox($model, 'agreewithterms', array('class'=>'col-xs-1'));
        echo $form->labelEx($model, 'agreewithterms', array('class'=>'col-xs-11'));
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
