<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta>
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <link href="/stampbox/css/sticky-footer.css" rel="stylesheet">
    <link href="/stampbox/css/stampbox.css" rel="stylesheet">
</head>
<body>
    <style type="text/css">
        body {
            background-color: #E6E2DF;
            background-position: center center !important;
            background-repeat: no-repeat !important;
            background-attachment: fixed !important;
            -webkit-background-size: cover !important;
            -moz-background-size: cover !important;
            -o-background-size: cover !important;
            background-size: cover !important;
            background-image: url(/stampbox/images/mails.jpg);
        }
        #footer {
            background-color: #FFF;
            -webkit-box-shadow: -1px -3px 4px rgba(0, 0, 0, 0.065) !important;
            -moz-box-shadow: -1px -3px 4px rgba(0, 0, 0, 0.065) !important;
            box-shadow: -1px -3px 4px rgba(0, 0, 0, 0.065) !important;
        }
</style>
<div class="navbar-wrapper">
<div class="container">
    <?php $this->widget('bootstrap.widgets.TbNavbar',array(
                    'type'=>'inverse',
                    'fixed'=> false,
                    'brandOptions'=>array('<img'=>'/stampbox/images/SB_logo_01.png"</img>',),
                    'brand'=>CHtml::encode(Yii::app()->name),
                    'brandUrl'=> 'index.php?r=/site/index',
                    'collapse'=>true,
                    'items'=>array(
                        array('class'=>'bootstrap.widgets.TbMenu',
                            'items'=>array(
                                array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
                                array('label'=>'Contact', 'url'=>array('/site/contact')),
                            ),
                        'htmlOptions'=>array('class'=>'pull-left'),
                        ),
                    '<form class="navbar-form pull-right">
                    <a href="index.php?r=site/login" class="btn">Login</a>
                    <a href="index.php?r=register/step1" class="btn btn-success">Sign up for free</a>
                    </form>',  
                    array('class'=>'bootstrap.widgets.TbMenu',
                        'items'=>array(
                            array('label'=>'Logout', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
                        ),
                        'htmlOptions'=>array('class'=>'pull-right'),
                    ),
                    ),
    )); ?>
</div></div> 
<div class="container">
    <?php echo $content ?>
</div>

<div id="footer">
    <div class="container">
        <p class="pull-right"><a href="#">Back to top</a></p>
        <p>© 2013 Good Wind Communications<br><a href="#">Privacy</a> <br> <a href="#">Terms</a></p>
    </div>
</div>
</body>
</html>
