<?php
/* @var $this RegisterController */
/* @var $model Register */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Register -> Step2';
$this->widget('bootstrap.widgets.TbAlert', array(
        'block'=>true, // display a larger alert block?
        'fade'=>true, // use transitions?
        'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
        'alerts'=>array( // configurations per alert type
            'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
        ),
    ));

if (isset($model->top_senders))
{
$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'id' => 'Step2',
        'type'=>'horizontal',
        'htmlOptions' => array('class' => 'span8'), 
        )
    );     

    $gridDataProvider = new CArrayDataProvider($model->top_senders, 
            array( 'keyField'=>'e-mail',     
                   'sort'=>array('attributes'=>array('Name', 'e-mail', 'rcount',))
                ,));    
    $gridDataProvider->getPagination()->setPageSize(100);
//    $gridDataProvider->setData($model->top_senders);
    $gridColumns = array(
        array(
            'id' => 'selectedIds',
            'class' => 'CCheckBoxColumn',
            'selectableRows'=>100,
            'header'=>'Invite',
            'name'=>'e-mail',
            ),
        array('name'=>'Name', 'header'=>'Name'),
        array('name'=>'e-mail', 'header'=>'E-mail'),
	array('name'=>'rcount', 'header'=>'# of mails'),
        );
//        var_dump($gridColumns);
//        var_dump($gridDataProvider);
    echo '<div class="box span12 stampwhite"><div class="content-colauto">';
    $this->widget('bootstrap.widgets.TbGridView',array(
        'id'=>'invitation-grid',
        'type'=>'bordered',
        'enablePagination'=>TRUE,
        'dataProvider' => $gridDataProvider,
        'template' => "{items}",
        'columns'=>$gridColumns));

    $this->widget('bootstrap.widgets.TbButton',array('buttonType' => 'submit', 'label' => 'Invite'));

    $this->endWidget(); 
    unset($form);
    echo '</div></div>';
    }
else
{
//echo Yii::app()->user->getFlash('info');


$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'id' => 'Step2',
        'type'=>'horizontal',
        'htmlOptions' => array('class' => 'well span8'), // for inset effect
        )
    ); 

        echo $form->textFieldRow($model, 'maildomain', array('class' => 'span5'));
        echo $form->textFieldRow($model, 'incoming_hostname', array('class' => 'span5'));
        echo $form->numberFieldRow($model, 'incoming_port', array('class' => 'span5'));
        echo $form->textFieldRow($model, 'e_mail_username', array('class' => 'span5'));
        echo $form->passwordFieldRow($model, 'e_mail_password', array('class' => 'span5'));

$this->widget('bootstrap.widgets.TbButton',array('buttonType' => 'submit', 'label' => 'Get Contacts'));
$this->endWidget(); 
unset($form);
}
?>
