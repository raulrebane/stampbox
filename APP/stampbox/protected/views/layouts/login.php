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
        <link rel="stylesheet" href="styles/stampbox.main.css">
        <script src="scripts/vendor/d7100892.modernizr.js"></script>
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

        <div class="logo"></div>
        <div class="menu">
            <a href="/stampbox/index.php?r=site/resetpasswd">Forgot your password?</a>
            <a href="/stampbox/index.php?r=register/Step1"><span>Don't have account?</span> Join us here</a>
        </div>

        <?php echo $content ?>

 <!--       <script src="scripts/44101f0b.main.js"></script>
 -->
 <script src="scripts/a1187778.plugins.js"></script>
</body>
</html>