<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
        <!-- copied from Division design -->
        <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.css" rel="stylesheet">
        <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/carousel.css" rel="stylesheet">
        <style id="holderjs-style" type="text/css">.holderjs-fluid {font-size:16px;font-weight:bold;text-align:center;font-family:sans-serif;margin:0}</style>
    
        <script src="libs/jquery/js/jquery-2.0.3.min.js"></script>
        <script src="libs/bootstrap/js/bootstrap.min.js"></script>
        <script src="libs/holder/js/holder.js"></script>
        
 <!--       
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
<!--
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
-->
</head>

<body>
    <div class="navbar-wrapper">
        <div class="container">
        <div class="navbar navbar-inverse navbar-static-top">
        <div class="container">
        <div class="navbar-header">


<!--
              <a class="navbar-brand" href="#">Stampbox</a>
            </div>
            <div class="navbar-collapse collapse">
              <ul class="nav navbar-nav">
                <li><a href="#about">For Private</a></li>
                <li><a href="#about">For Business</a></li>
                <li><a href="#contact">Pricing</a></li>
                <li><a href="#contact">Help</a></li>
              </ul>
              
            </div>
          </div>
        </div>

      </div>
    </div>
-->
<!--	<div class="navbar-brand"><!-- mainmenu begins-->
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('encodeLabel' => false, 'label'=>'Stampbox', 'url'=>array('/site/index')),
				array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
				array('label'=>'Contact', 'url'=>array('/site/contact')),
				array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'My account', 'url'=>array('tCustomer/view&id='.Yii::app()->user->id), 'visible'=>!Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
			'lastItemCssClass'=>"navbar-right",
			'htmlOptions' => array('class'=>"nav navbar-nav"),
		)); ?>
	</div><!-- mainmenu -->
              <form class="navbar-form navbar-right">
                <button type="submit" class="btn btn-link">Login</button>
                <button type="submit" class="btn btn-success">Sign up for free</button>
              </form>
	</div>
	</div>
	</div>
	</div>

	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<div class="container marketing">
		<?php echo $content; ?>

        	<footer>
            		<p class="pull-right"><a href="#">Back to top</a></p>
            		<p>© 2013 Good Wind Communications · <a href="#">Privacy</a> · <a href="#">Terms</a></p>
        	</footer>
	</div>
</body>
</html>
