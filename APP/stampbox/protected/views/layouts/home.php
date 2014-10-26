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

  ga('create', 'UA-55734110-1', 'auto');
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
                    array('label'=>'Overview', 'url'=>array('/site/index')),
                    array('label'=>'Account statement', 'url'=>array('/account/statement')),
                    array('label'=>'E-mail accounts', 'url'=>array('/usermailbox/index')),
                    array('label'=>'Whitelist', 'url'=>array('/whitelist/index')),
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
                        <a href="private.html" class="item active">Home</a>
                        <a href="business.html" class="item">Pricing</a>
                        <a href="pricing.html" class="item">Terms & conditions</a>
                        <a href="help.html" class="item">Help</a>
                        <a href="<?php echo Yii::app()->createUrl('site/login')?>" class="btn btn-aqua login">Log in</a>
                        <a href="<?php echo Yii::app()->createUrl('register/Step1')?>" class="btn btn-aqua signup">Sign up for free</a>
                    </div>  
                </div>
            </div>
            <div class="row hero text-center">
                <div class="col-sm-1"></div>
                <div class="col-sm-10 hero">
                    <h1><b>Stampbox</b> - the email courier</h1>
                    <h2>The only way to make emails worth your time</h2>
                    <div class="menu visible-xs">
                        <a href="login.html" class="btn btn-aqua login">Log in</a>
                        <a href="signup.html" class="btn btn-aqua signup">Sign up for free</a>
                    </div>
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="row video">
                <div class="col-sm-1"></div>
                <div class="col-sm-10">
                    <div class="video-holder">
                        <img src="images/314e0d29.home_video_bg.png">
                    </div>
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="row feature">
                <div class="col-sm-1"></div>
                <div class="col-sm-6">
                    <h3>Why free is not always good</h3>
                    <img src="images/33ecda06.home_feature_a.jpg" class="visible-xs">
                    <p>In today’s world the main channel of communications  is email as it is comfortable and <b>"free"</b>.</p>
                    <p>This has led to a situation where your inboxes are filled with "noise" and it takes immense time to go through, analyse and digest. <b>Time is still a very valuable asset!</b></p>
                </div>
                <div class="col-sm-4 hidden-xs">
                    <img src="images/33ecda06.home_feature_a.jpg">
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="row split">
                <div class="col-xs-2 col-sm-3"></div>
                <div class="col-xs-8 col-sm-6 line"></div>
                <div class="col-xs-2 col-sm-3"></div>
            </div>
            <div class="row feature">
                <div class="col-sm-1"></div>
                <div class="col-sm-4 hidden-xs">
                    <img src="images/f38e9ccf.home_feature_b.jpg">
                </div>
                <div class="col-sm-6">
                    <h3>How to filter important from not-so-important?</h3>
                    <img src="images/f38e9ccf.home_feature_b.jpg" class="visible-xs">
                    <p>The filter is money. If the sender is in great need of passing on the message, then to guarantee that you would find time to read the letter, the time you spend has to be compensated.</p>
                </div>
                <div class="col-sm-1"></div>
            </div>
        </div>
        
        <div class="wide">
            <div class="container">
                <div class="row">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10">
                        <h2>There is a solution.</h2>
                        <h1><b>Stampbox</b> – the email courier</h1>
                        <p>The senders attach a paid digital stamp to their letters and Stampbox organizes your maibox according to the stamps. All digitally stamped emails will land in your inbox. Emails without digital stamps will be automatically filtered into no-stamp-box folder.</p>
                    </div>
                    <div class="col-sm-1"></div>
                </div>
            </div>
        </div>

        <div class="container footer">
            <div class="row">
                <div class="col-sm-12 text-center">
                     <img src="images/d125a1fc.home_footer_icon.png">
                     <h1>From now on all the important messages are delivered.</h1>
                     <div class="line"></div>
                </div>
            </div>
        </div>
    <script src="scripts/44101f0b.main.js"></script>
    <script src="scripts/a1187778.plugins.js"></script>

</body>
</html>
