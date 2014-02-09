<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$this->pageTitle=Yii::app()->name . ' - Change password';

$this->widget('bootstrap.widgets.TbAlert', array(
        'block'=>true, // display a larger alert block?
        'fade'=>true, // use transitions?
        'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
        'alerts'=>array( // configurations per alert type
            'error'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
        ),
    ));
$this->widget('bootstrap.widgets.TbAlert', array(
        'block'=>true, // display a larger alert block?
        'fade'=>true, // use transitions?
        'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
        'alerts'=>array( // configurations per alert type
            'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
        ),
    ));

$form = $this->beginWidget(
'bootstrap.widgets.TbActiveForm',
array(
'id' => 'verticalForm',
'type'=> 'vertical',
'htmlOptions' => array('class' => 'well span-4'), // for inset effect
)
); 

echo $form->passwordFieldRow($model, 'oldpassword', array('class' => 'span3'));
echo $form->passwordFieldRow($model, 'newpassword', array('class' => 'span3'));
echo $form->passwordFieldRow($model, 'password1', array('class' => 'span3'));

$this->widget(
'bootstrap.widgets.TbButton',
array('buttonType' => 'submit', 'label' => 'Change password')
);

$this->endWidget(); 
unset($form);

?>

