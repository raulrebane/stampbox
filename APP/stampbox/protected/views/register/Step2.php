<?php
/* @var $this RegisterController */
/* @var $model Register */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Register -> Step2';

//echo Yii::app()->user->getFlash('info');
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
'id' => 'Step2',
'type'=>'horizontal',
'htmlOptions' => array('class' => 'well span-4'), // for inset effect
)
); 

// echo $form->errorSummary($model);

        echo $form->textFieldRow($model, 'maildomain', array('class' => 'span3'));
        echo $form->textFieldRow($model, 'incoming_hostname', array('class' => 'span3'));
        echo $form->numberFieldRow($model, 'incoming_port', array('class' => 'span3'));
        echo $form->textFieldRow($model, 'e_mail_username', array('class' => 'span3'));
        echo $form->passwordFieldRow($model, 'e_mail_password', array('class' => 'span3'));

?>
        <div class="form-actions">        
            <?php $this->widget('bootstrap.widgets.TbButton',array('buttonType' => 'submit', 'label' => 'Get Contacts')); ?>
        </div>

<?php
$this->endWidget(); 
unset($form);
?>
<br>
<?php
if (isset($model->top_senders))
{
    $gridDataProvider = new CArrayDataProvider($model->top_senders);
    $gridColumns = array(
        array('name'=>'e-mail', 'header'=>'E-mail'),
	array('name'=>'count', 'header'=>'# of mails'),);
            
    $this->widget('bootstrap.widgets.TbGridView',array('dataProvider' => $gridDataProvider,'template' => "{items}",'columns'=>$gridColumns));
}
?>
