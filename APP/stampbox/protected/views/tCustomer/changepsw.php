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
'htmlOptions' => array('class' => 'well span4 pull-right'), // for inset effect
)
); 

echo $form->passwordFieldRow($model, 'oldpassword');
echo $form->passwordFieldRow($model, 'newpassword');
echo $form->passwordFieldRow($model, 'password1');

echo '<br>';
$this->widget('bootstrap.widgets.TbButton',array('buttonType' => 'submit', 'label' => 'Change password', 'type' => 'primary'));
$this->widget('bootstrap.widgets.TbButton',array('buttonType' => 'link', 'label' => 'Cancel', 'url'=>'index.php?r=site/index'));

$this->endWidget(); 
unset($form);

?>

