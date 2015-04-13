<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div id="p-whitelist" class="row">
    <div class="col-md-6 m-add">
    <h1>Add e-mail to list</h1>
        <?php 
         $form = $this->beginWidget('CActiveForm',array(
            'id' => 'Whitelist',
            'htmlOptions' => array('class' => 'form', 'role'=>'form'),));
            $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                'model'=>$model,
                'name'=>'e_mail',
                //'class'=>'form-control',
                'value'=>'',
                'source'=>$this->createUrl('whitelist/Autocomplete'),
                // additional javascript options for the autocomplete plugin
                'options'=>array('showAnim'=>'fold',),
            ));
            echo '<button type="submit" class="btn btn-aqua">Add to whitelist</button>';
        $this->endWidget();
        ?>
    </div>
    <div class="col-md-6 m-add">
        <?php
            $form = $this->beginWidget('CActiveForm',array(
                'id' => 'WhitelistD',
                //'type'=>'horizontal',
                'htmlOptions' => array('class'=>'form', 'role'=>'form'),
                ));               
        ?>
        <!-- Button trigger modal -->
        <h1>Add from contacts</h1>
        <button type="button" class="btn btn-primary btn-aqua" data-toggle="modal" data-target="#Whitelistdialog">
            Add
        </button>

        <!-- Modal -->
        <div class="modal fade" id="Whitelistdialog" tabindex="-1" role="dialog" aria-labelledby="Whitelistlabel" 
             aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="Whitelistlabel">Add mailbox contacts to whitelist</h4>
            </div>
            <div class="modal-body">
                <?php
                    $sort = new CSort();
                    $sort->attributes = array('invited_email','name');
                    $sort->defaultOrder=array('name'=>CSort::SORT_DESC);
                    
                    $contactDataProvider=new CActiveDataProvider('Invitations', array(
                        'criteria'=>array('condition'=>'customer_id='.Yii::app()->user->getId()),
                        'sort'=>$sort, 'pagination'=>array('pageSize'=>1000,)));
                    $gridColumns = array(
                        array(
                            'id' => 'selectedIds',
                            'class' => 'CCheckBoxColumn',
                            'selectableRows'=>1000,
                            'header'=>'Invite',
                            'name'=>'invited_email',
                            'disabled'=>function($data) {if ($data['invite']==='Y') return TRUE; else return FALSE;},
                            //'checked'=>'($row<100 AND $data["invite"] <> "Y")'
                            ),
                        array('name'=>'name', 'header'=>'Name'),
                        array('name'=>'invited_email', 'header'=>'E-mail'),
                    );
                    $this->widget('zii.widgets.grid.CGridView',array(
                        'id'=>'invitation-grid',
                        'enablePagination'=>FALSE,
                        //'hideHeader'=>TRUE,
                        'template' => '{items}',
                        'htmlOptions'=>array('class'=>'content'),
                        'dataProvider' => $contactDataProvider,
                        'columns'=>$gridColumns
                    )); 
                ?>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" name="whitelistsubmit" class="btn btn-aqua">Add selected</button>
              </div>
            </div>
          </div>
        </div>
    <?php $this->endWidget(); ?>
    </div>
</div>
    
 <div class="col-md-12">
    <div class="widget widget-activity"><div class="title">My whitelist</div>
    <div class="content">
        <?php 
        
        $gridColumns = array(
            array('name'=>'e_mail', 'htmlOptions'=>array('class'=>'email')),
            array('class'=>'CButtonColumn','template'=>'{delete}', 'buttons'=>array('delete' => array(
                    'label'=>'Delete',
                    'imageUrl'=>Yii::app()->request->baseUrl.'/images/btn-delete.png',
                    'url'=>'Yii::app()->createUrl("whitelist/delete", array("email"=>$data->e_mail))')
                )),
            );
        $this->widget('zii.widgets.grid.CGridView',array(
            'enablePagination'=>FALSE,
            //'hideHeader'=>False,
            'template' => '{items}',
            'htmlOptions'=>array('class'=>'content'),
            'dataProvider' => $dataProvider,
            'columns'=>$gridColumns));
        ?>
    </div>
    </div>
</div>

