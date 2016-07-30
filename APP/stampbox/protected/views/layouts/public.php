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
        <script src="scripts/vendor/d7100892.modernizr.js"></script>
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
                            array('label'=>'Log in', 'url'=>array('/site/login'), 'linkOptions' => array('class'=>'btn btn-aqua login')),
                            array('label'=>'Sign up', 'url'=>array('/signup/step1'), 'linkOptions' => array('class'=>'btn btn-aqua signup')),
                        ),
                        'htmlOptions' => array('class'=>'menu')
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="container">
            <?php echo $content ?>
        </div>
        
    <script src="scripts/main.js"></script>
    <script src="scripts/plugins.js"></script>

</body>
</html>
