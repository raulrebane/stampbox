<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var TbActiveForm $form */

$this->pageTitle=Yii::app()->name . ' - Login';

$form = $this->beginWidget(
'bootstrap.widgets.TbActiveForm',
array(
'id' => 'verticalForm',
'htmlOptions' => array('class' => 'well span4 pull-right'), // for inset effect
)
); 

echo $form->textFieldRow($model, 'username', array('class' => 'span3'));
echo $form->passwordFieldRow($model, 'password', array('class' => 'span3'));
echo $form->checkboxRow($model, 'rememberMe');
$this->widget(
'bootstrap.widgets.TbButton',
array('buttonType' => 'submit', 'label' => 'Login')
);

$this->endWidget(); 
unset($form);

?>

