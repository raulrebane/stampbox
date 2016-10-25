<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Stampbox</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="stylesheet" href="css/main20150413.css">
        <script src="scripts/plugins.js"></script>
<?php
Yii::app()->clientScript->registerCoreScript('jquery');     
Yii::app()->clientScript->registerCoreScript('jquery.ui');
?>
        
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-59363252-1', 'auto');
  ga('send', 'pageview');

</script>
    </head>
    <body class="home">
        <div class="bg"></div>

        <!--<div class="mobile-menu-trigger">
            <span class="bar bar-1"></span>
            <span class="bar bar-2"></span>
            <span class="bar bar-3"></span>
        </div>-->

        <div class="container">
            <div class="row header">
                <div class="col-md-2">
                    <div class="logo" onclick="location.href='<?php echo Yii::app()->createUrl('site/index') ?>'"></div>
                </div>
                <div class="col-md-10 text-right">
                    <?php
                    $this->widget('zii.widgets.CMenu',array(
                        'items'=>array(
                            array('label'=>'Home', 'url'=>array('/site/index')),
                            //array('label'=>'Pricing', 'url'=>array('/site/pricing')),
                            array('label'=>'Terms & conditions', 'url'=>array('/site/terms')),
                            array('label'=>'Help', 'url'=>array('/site/help')),
                            array('label'=>'Log in', 'url'=>array('#'), 'linkOptions' => array('class'=>'btn btn-aqua login'), 
                                'itemOptions' => array('data-toggle' => 'modal', 'data-target' =>'#Login')),
                            array('label'=>'Sign up', 'url'=>array('#'), 'linkOptions' => array('class'=>'btn btn-aqua signup'),
                                'itemOptions' => array('data-toggle' => 'modal', 'data-target' =>'#Signup')),
                        ),
                        'htmlOptions' => array('class'=>'menu')
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row hero text-center">
                <div class="col-sm-1"></div>
                <div class="col-sm-10 hero">
                    <h1><b>Stampbox</b> - the email courier</h1>
                    <h2>The only way to make emails worth your time</h2>
                </div>
                <div class="col-sm-1"></div>
            </div>
            <?php echo $content ?>
        </div>
<div class="container">
        <!-- Modal Login-->
<div class="modal fade" id="Login" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
    <div class="modal-content">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="dialog-form" style="padding:20px;">
            <?php
                $model=new LoginForm;
                $form = $this->beginWidget('CActiveForm',array(
                    'id' => 'login-form','action' => Yii::app()->createUrl('site/login'), 
                    'htmlOptions' => array('class' => 'form', 'role'=>'form'),
                    'enableClientValidation'=>true,
                    'clientOptions' => array('validateOnSubmit' => true,'validateOnChange'=>false,
                        'afterValidate' => 'js:function(form, data, hasError) {
                            if (!hasError){
                                str = $("#login-form").serialize() + "&ajax=login-form";
                                $.ajax({type: "POST", url: "' . Yii::app()->createUrl('site/login') . '",
                                data: str,
                                dataType: "json",
                                beforeSend : function() {$("#login").attr("disabled",true);},
                                success: function(data, status) {
                                    if(data.authenticated) {
                                        window.location = data.redirectUrl;}
                                    else {
                                        $.each(data, function(key, value) {
                                            var div = "#"+key+"_em_";
                                            $(div).text(value);
                                            $(div).show();
                                            });
                                        $("#login").attr("disabled",false);
                                    }
                            },
                        });
                        return false;
                        }
                    }',
    ),
));?>
            <div class="form-group">
            <?php
                echo $form->emailField($model, 'username', array('class' => 'form-control', 'id'=>'email', 'placeholder'=>'Email'));
                echo $form->error($model, 'username', array('class' => 'alert alert-danger'));
                
                echo $form->passwordField($model, 'password', array('class' => 'form-control', 'placeholder'=>'Password'));
                echo $form->error($model, 'password', array('class' => 'alert alert-danger'));
            ?>  
            </div>
            <button type="submit" class="btn btn-aqua btn-block dialog-form-btn">Login</button>
            <?php $this->endWidget();?>
        </div>
    </div>
    </div>
</div>
    
<!-- Modal Signup-->
<div class="modal fade" id="Signup" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="dialog-form" style="padding:20px;">
            <?php
                $model=new Signup;
                $form = $this->beginWidget('CActiveForm',array(
                    'id' => 'signup-form','action' => Yii::app()->createUrl('signup/index'), 
                    'htmlOptions' => array('class' => 'form', 'role'=>'form'),
                    'enableClientValidation'=>true,
                    'clientOptions' => array('validateOnSubmit' => true,'validateOnChange'=>false,
                        'afterValidate' => 'js:function(form, data, hasError) {
                            if (!hasError){
                                str = $("#signup-form").serialize() + "&ajax=signup-form";
                                $.ajax({type: "POST", url: "' . Yii::app()->createUrl('signup/index') . '",
                                data: str,
                                dataType: "json",
                                beforeSend : function() {$("#signup").attr("disabled",true);},
                                success: function(data, status) {
                                    if(data.signupcomplete) {
                                        window.location = data.redirectUrl;}
                                    else {
                                        $.each(data, function(key, value) {
                                            var div = "#"+key+"_em_";
                                            $(div).text(value);
                                            $(div).show();
                                            });
                                        $("#login").attr("disabled",false);
                                    }
                            },
                        });
                        return false;
                        }
                    }',
    ),
));?>
            <div class="form-group">
                <div class="row"><div class="col-xs-12">
                <?php
                echo $form->EmailField($model, 'useremail', array('class'=>'', 'id'=>'useremail', 'style'=>'width:100%','placeholder'=>'Enter email'));
                echo $form->error($model, 'useremail',array('validateOnChange'=>true, 'class' => 'alert alert-danger'), TRUE);
                ?>
                </div></div>
                <div class="row"><div class="col-xs-12">
                <?php
                echo $form->passwordField($model, 'userpassword', array('class'=>'','style'=>'width:100%','placeholder'=>'Create Stampbox password'));
                echo $form->error($model, 'userpassword', array('class' => 'alert alert-danger'), FALSE);
                ?>
                </div></div>
                <div class="row">
                    <div class="col-xs-1"><?php echo $form->checkBox($model, 'agreewithterms', array('class'=>''));?></div>
                    <div class="col-xs-11"><?php echo $form->labelEx($model, 'agreewithterms', array('class'=>''));?></div>
                </div>
                <div class="row">
                    <?php echo $form->error($model, 'agreewithterms', array('class' => 'alert alert-danger'));?>
                </div>
        </div>
        <div class="row"><div class="col-xs-12">
        <button type="submit" class="btn btn-aqua btn-block dialog-form-btn">Sign up</button>
        </div></div>
        <?php $this->endWidget();?>
    </div>
    </div>
</div>
</body>
</html>
