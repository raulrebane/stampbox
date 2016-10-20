<?php
Yii::app()->clientScript->registerCoreScript('jquery');     
Yii::app()->clientScript->registerCoreScript('jquery.ui');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div class="row">
    <div class="col-md-12">
    <ul class="nav nav-tabs" data-tabs="tabs" role="tablist" id="Invitetabs">
        <li role="presentation" class="active"><a href="Whitelisttab" aria-controls="Whitelisttab" role="tab" data-toggle="tab">Whitelisted e-mails</a></li>
        <li role="presentation"><a href="Addtab" aria-controls="Addtab" role="tab" data-toggle="tab">Add from contacts</a></li>
        <li>
            <div class="dashboard-form">
                <!--<div class="navbar-form navbar-left">-->
                <?php 
                 $form = $this->beginWidget('CActiveForm',array(
                    'id' => 'Whitelist',
                    'htmlOptions' => array('class' => 'form', 'role'=>'form'),));
                    $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
                        'model'=>$model,
                        'name'=>'e_mail',
                        'htmlOptions' => array('placeholder'=>'Enter e-mail', 'style'=>'width:auto;margin-left:15px;'),
                        //'class'=>'form-control',
                        'value'=>'',
                        'source'=>$this->createUrl('whitelist/Autocomplete'),
                        // additional javascript options for the autocomplete plugin
                        'options'=>array('showAnim'=>'fold',),
                    ));
                ?>  
                <button type="submit" class="btn btn-aqua">Add to whitelist</button>
            </div>
        </li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="Whitelisttab">
        <div class="widget widget-activity">
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
                    'htmlOptions'=>array('class'=>''),
                    'dataProvider' => $dataProvider,
                    'columns'=>$gridColumns));
                ?>
            </div>
        </div>
        </div>
    </div>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane" id="Addtab">
        <div class="widget widget-activity">
            <div class="content">
                <div class="row"><div class="col-xs-offset-1"><button type="submit" name="invite" class="btn btn-aqua">Whitelist selected</button></div></div>
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
                            'header'=>'',
                            'name'=>'invited_email',
                            //'checked'=>'($row<100 AND $data["invite"] <> "Y")'
                            ),
                        array('name'=>'name', 'header'=>'Name'),
                        array('name'=>'invited_email', 'header'=>'E-mail'),
                    );
                    $this->widget('zii.widgets.grid.CGridView',array(
                        'id'=>'Whitelistgrid',
                        'enablePagination'=>FALSE,
                        //'hideHeader'=>TRUE,
                        'template' => '{items}',
                        'htmlOptions'=>array('class'=>''),
                        'dataProvider' => $contactDataProvider,
                        'columns'=>$gridColumns
                    )); 
                    ?>
                <div class="row"><div class="col-xs-offset-1"><button type="submit" name="invite" class="btn btn-aqua">Whitelist selected</button></div></div>
            </div>
          </div>
        </div>
    </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script>
$(document).on('hide.bs.tab', 'a[data-toggle="tab"]', function (e) {
    var contentId = $(e.target).attr("href");
    var tab = document.getElementById($(e.target).attr("href"));
    $(tab).removeClass('active');
    });
$(document).on('show.bs.tab', 'a[data-toggle="tab"]', function (e) {
    var contentId = $(e.target).attr("href");
    var tab = document.getElementById($(e.target).attr("href"));
    $(tab).addClass('active');
    });
</script>
