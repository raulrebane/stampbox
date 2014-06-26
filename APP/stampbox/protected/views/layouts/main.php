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
        <meta name="viewport" content="width=device-width">
        <link type="text/css" rel="stylesheet" href="http://fast.fonts.net/cssapi/49c66566-c451-4985-90f6-153b894ab04f.css">
        <link rel="stylesheet" href="styles/bc1ea147.main.css">
        <script src="scripts/vendor/d7100892.modernizr.js"></script>
    </head>
    <body class="logged-in">
        <div id="wrapper">

            <!-- Sidebar -->
            <div id="sidebar-wrapper">
                <div class="logo"></div>
                <ul class="sidebar-nav main-menu">
                    <li class="active"><a href="<?php echo Yii::app()->createUrl('site/index') ?>" >Dashboard</a>
                    </li>
                    <li><a href="#">Account statement</a>
                    </li>
                    <li><a href="#">E-mail Accounts</a>
                    </li>
                    <li><a href="#">Invitations</a>
                    </li>
                </ul>
                <ul class="sidebar-nav user-menu">
                    <li><a href="#" class="btn btn-aqua">Buy stamps</a>
                    </li>
                    <li><a href="#">Account Settings</a>
                    </li>
                    <li><a href="login.html">Logout</a>
                    </li>
                </ul>
            </div>

        <div id="page-content-wrapper">
        <div class="dashboard inset">
            <div class="row">
                <div class="col-md-12" id="menu-toggle"><i class="icon-menu"></i></div>
                <?php echo $content ?>
            </div>
        </div>
        </div>
        </div>
</body>
</html>
