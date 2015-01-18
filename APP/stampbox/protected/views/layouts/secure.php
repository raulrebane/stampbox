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
    <body class="logged-in">
        <div id="wrapper">

            <!-- Sidebar -->
            <div id="sidebar-wrapper">
                <a class="logo" href="<?php echo Yii::app()->createUrl('site/dashboard') ?>"></a>
                <?php $this->widget('zii.widgets.CMenu',array(
                    'items'=>array(
                        array('label'=>'Overview', 'url'=>array('/site/index')),
                        array('label'=>'Account statement', 'url'=>array('/account/statement')),
                        array('label'=>'E-mail accounts', 'url'=>array('/usermailbox/index')),
                        array('label'=>'Whitelist', 'url'=>array('/whitelist/index')),
                        array('label'=>'Invite', 'url'=>array('/invite/index')),
                        array('label'=>'Buy stamps', 'url'=>array('/shop/buy')),
                        ),
                    'htmlOptions' => array('class'=>'sidebar-nav main-menu',
                        ),
                    )); ?>
                <ul class="sidebar-nav user-menu">
                    <li><a href="<?php echo Yii::app()->createUrl('site/logout') ?>" class="btn btn-aqua">Logout</a>
                    </li>
                </ul>
            </div>

            <div id="page-content-wrapper">
                <div class="dashboard inset">
                    <div class="row">
                        <div class="col-md-12" id="menu-toggle"><i class="icon-menu"></i></div>
                        <div class="col-md-12">
                        <!-- content START -->
                            <?php echo $content ?>
                        <!-- content END -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="scripts/main.js"></script>
        <script src="scripts/plugins.js"></script>
        <script src="scripts/stampbox-flipswitch.js"></script>
    </body>
</html>