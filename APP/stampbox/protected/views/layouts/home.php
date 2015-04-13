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
        <link type="text/css" rel="stylesheet" href="http://fast.fonts.net/cssapi/49c66566-c451-4985-90f6-153b894ab04f.css">
        <link rel="stylesheet" href="styles/ab2cba35.main.css">
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

            <div class="mobile-menu-trigger">
            <span class="bar bar-1"></span>
            <span class="bar bar-2"></span>
            <span class="bar bar-3"></span>
            </div>
<!--
        <div class="mobile-menu">
                <a href="private.html" class="item active">For private</a>
                <a href="business.html" class="item">For business</a>
                <a href="pricing.html" class="item">Pricing</a>
                <a href="help.html" class="item">Help</a>
                <a href="login.html" class="btn btn-aqua login">Log in</a>
                <a href="signup.html" class="btn btn-aqua signup">Sign up for free</a>
            </div>
-->
        <div class="mobile-menu">
            <?php $this->widget('zii.widgets.CMenu',array(
                'items'=>array(
                    array('label'=>'Home', 'url'=>array('/site/index')),
                    array('label'=>'Pricing', 'url'=>array('/site/pricing')),
                    array('label'=>'Terms & conditions', 'url'=>array('/site/terms')),
                    array('label'=>'Help', 'url'=>array('/site/help')),
                    array('label'=>'Log in', 'url'=>array('/site/login')),
                    array('label'=>'Sign up for free', 'url'=>array('/register/Step1')),
                ),
                'htmlOptions' => array('class'=>'item'),
            )); ?>
        </div>

        <div class="container">
            <div class="row header">
                <div class="col-md-2">
                    <div class="logo"></div>
                </div>
                <div class="col-md-10 text-right">
                    <div class="menu">
                        <?php 
                        /*
                         $this->widget('zii.widgets.CMenu',array(
                            'items'=>array(
                                array('label'=>'Home', 'url'=>array('/site/index')),
                                array('label'=>'Pricing', 'url'=>array('/site/pricing')),
                                array('label'=>'Terms & conditions', 'url'=>array('/site/terms')),
                                array('label'=>'Help', 'url'=>array('/site/help'))),
                            'htmlOptions' => array('class'=>'item'),
                        ));
                         */
                        ?>
                        <a href="<?php echo Yii::app()->createUrl('site/index')?>" class="item active">Home</a>
                        <a href="<?php echo Yii::app()->createUrl('site/pricing')?>" class="item">Pricing</a>
                        <a href="<?php echo Yii::app()->createUrl('site/terms')?>" class="item">Terms & conditions</a>
                        <a href="<?php echo Yii::app()->createUrl('site/help')?>" class="item">Help</a>
                        <a href="<?php echo Yii::app()->createUrl('site/login')?>" class="btn btn-aqua login">Log in</a>
                        <a href="<?php echo Yii::app()->createUrl('register/Step1')?>" class="btn btn-aqua signup">Sign up for free</a>
                    </div>  
                </div>
            </div>
        <?php echo $content ?>
            
    <script src="scripts/44101f0b.main.js"></script>
    <script src="scripts/a1187778.plugins.js"></script>

</body>
</html>
