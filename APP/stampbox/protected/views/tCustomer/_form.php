<?php
/* @var $this TCustomerController */
/* @var $model TCustomer */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Register';
Yii::app()->user->setFlash('info', 'Fields marked with * are required');
//echo Yii::app()->user->getFlash('info');
$this->widget('bootstrap.widgets.TbAlert', array(
        'block'=>true, // display a larger alert block?
        'fade'=>true, // use transitions?
        'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
        'alerts'=>array( // configurations per alert type
            'info'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
        ),
    ));

$form = $this->beginWidget(
'bootstrap.widgets.TbActiveForm',
array(
'id' => 'horizontalForm',
'type'=>'horizontal',
'htmlOptions' => array('class' => 'well span-4'), // for inset effect
)
); 



 echo $form->errorSummary($model);

        echo $form->textFieldRow($model, 'username', array('class' => 'span3'));
        echo $form->textFieldRow($model, 'firstname', array('class' => 'span3'));
        echo $form->textFieldRow($model, 'lastname', array('class' => 'span3'));
        echo $form->passwordFieldRow($model, 'password', array('class' => 'span3'));
        echo $form->passwordFieldRow($model, 'password1', array('class' => 'span6'));
        echo $form->dropDownListRow($model, 'preferred_lang', array('English', 'Estonian', 'Finnish'), array('class' => 'span2'));

?>
        <div class="form-actions">        
            <?php $this->widget('bootstrap.widgets.TbButton',array('buttonType' => 'submit', 'label' => 'Register')); ?>
        </div>

<?php
$this->endWidget(); 
unset($form);

?>