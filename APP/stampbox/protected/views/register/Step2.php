<?php
/* @var $this RegisterController */
/* @var $model Register */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Register -> Step2';

?>

<div class="col-md-6">
<?php 
    echo '<br><h2>Incoming mail server</h2>';
    
    $form = $this->beginWidget('CActiveForm', array('id' => 'Step2', 'htmlOptions' => array('class'=>"form register", 'role'=>"form"))); 

    echo $form->hiddenField($model, 'maildomain');
    echo $form->hiddenField($model, 'mailtype');
    echo $form->hiddenField($model, 'incoming_auth');
    
    //echo $form->labelEx($model,'incoming_hostname');
    echo $form->textField($model, 'incoming_hostname', array('class'=>'form-control', 'placeholder'=>'e-mail server name'));
    echo $form->error($model, 'incoming_hostname');
    
    echo $form->labelEx($model,'incoming_port');
    echo $form->numberField($model, 'incoming_port', array('class'=>'form-control', 'placeholder'=>'Port'));    
    
    echo $form->labelEx($model,'incoming_socket_type');
    echo '<div class="select-style">';
    echo $form->dropDownList($model, 'incoming_socket_type',
              array('NULL'=>'None', 'ssl' => 'SSL', 'tls' => 'TLS'));
    echo '</div>';
    
    $this->endWidget(); 
    unset($form);    
?>
</div>
<div class="col-md-6 darker">
<?php
    echo '<br><h2>Outgoing mail server</h2>';

    $form = $this->beginWidget('CActiveForm', array('id' => 'Step21', 'htmlOptions' => array('class'=>"form register", 'role'=>"form"))); 

    //echo $form->labelEx($model,'outgoing_hostname');
    echo $form->textField($model, 'outgoing_hostname', array('class'=>'form-control', 'placeholder'=>'e-mail server name'));
    echo $form->error($model, 'outgoing_hostname');
    
    echo $form->labelEx($model,'outgoing_port');
    echo $form->numberField($model, 'outgoing_port', array('class'=>'form-control', 'placeholder'=>'Port'));    
    
    echo $form->labelEx($model,'outgoing_socket_type');
    echo '<div class="select-style">';
    echo $form->dropDownList($model, 'outgoing_socket_type',
              array('NULL'=>'None', 'ssl' => 'SSL', 'tls' => 'TLS'));
    echo '</div><button type="submit" class="btn btn-default"></button></div>';

//echo Yii::app()->user->getFlash('info');
    $this->endWidget(); 
    unset($form);    
?>
