<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var TbActiveForm $form */

$this->pageTitle=Yii::app()->name . ' - Login';
echo CHtml::errorSummary($model, '', '', array('class'=>"m-flash"));
?>

<div class="login-form">
    <h1><b>Welcome</b> Back!</h1>
    <?php $form = $this->beginWidget(
        'CActiveForm',array(
            'id' => 'verticalForm',
            'htmlOptions' => array('class' => 'form', 'role'=>'form'),));
    ?>
        <div class="form-group">
            <?php
            echo $form->emailField($model, 'username', array('class' => 'form-control', 'id'=>'email', 'placeholder'=>'Enter email'));
            echo $form->passwordField($model, 'password', array('class' => 'form-control', 'placeholder'=>'Password'));
            ?>
        </div>
              <!--<div class="checkbox">
                <label>
                  <input type="checkbox"> Remember me
                </label>
              </div>-->
        <button type="submit" class="btn btn-default"></button>
        <?php $this->endWidget();

        //unset($form);
        ?>
</div>