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
        <link type="text/css" rel="stylesheet" href="//fast.fonts.net/cssapi/49c66566-c451-4985-90f6-153b894ab04f.css">
        <link rel="stylesheet" href="css/main.css">
        <script src="scripts/vendor/d7100892.modernizr.js"></script>
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-55734110-1', 'auto');
          ga('send', 'pageview');

        </script>
</head>
<body class="logged-out">

    <div class="bg">
        <?php 
        if (date('d') % 2 == 0) {
            echo '<div class="a"></div>'; }
        else {
            echo '<div class="b"></div>'; }
        ?>    
    </div>

        <a class="logo" href="<?php echo Yii::app()->createUrl('site/index') ?>"></a>
        <div class="menu">
            <a href="<?php echo Yii::app()->createUrl('site/ResetPasswd')?>">Forgot your password?</a>
        </div>


        <div class="register-form">
            <h1>Create <b>your</b> first <b>Stampbox</b></h1>
            <div class="row step-a">
                <div class="col-md-6">
                    <div class="feature">
                        <p>Works with all popular e-mail providers</p>
                        <img src="images/register-feature-services.png">
                    </div>
                    <div class="feature">
                        <p>Works on all of your devices</p>
                        <img src="images/register-feature-devices.png">
                    </div>
                </div>
                <div class="col-md-6 darker">
                    <?php echo $content ?>
                </div>
            </div>
        </div>
 <!--       
    <script src="scripts/main.js"></script>
    <script src="scripts/plugins.js"></script>
 -->
</body>
</html>