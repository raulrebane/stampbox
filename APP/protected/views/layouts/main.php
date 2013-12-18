<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
   	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.css" rel="stylesheet">
        <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/carousel.css" rel="stylesheet">
        <style id="holderjs-style" type="text/css">.holderjs-fluid {font-size:16px;font-weight:bold;text-align:center;font-family:sans-serif;margin:0}</style>

        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-2.0.3.min.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.min.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/holder.js"></script>

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
        <script language="javascript">
                $(function(){
                $('.dropdown-toggle').dropdown();
                $('.dropdown input, .dropdown label').click(function (e) {e.stopPropagation();});
                });
        </script>

<body>
    <div class="navbar-wrapper">
      <div class="container">
        <div class="navbar navbar-inverse navbar-static-top">
        <div class="container">
        <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
	</div>
	<div class="navbar-collapse collapse">
	<!-- mainmenu begins-->
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('encodeLabel' => false, 'label'=>'Stampbox', 'url'=>array('/site/index')),
				array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
				array('label'=>'Contact', 'url'=>array('/site/contact')),
				array('label'=>'My account', 'url'=>array('tCustomer/view&id='.Yii::app()->user->id)),
				//array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				//array('label'=>'Logout', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
			'htmlOptions' => array('class'=>"nav navbar-nav"),
		)); ?>
		<?php
		  echo '<form class="navbar-form navbar-right">';
             	  if (Yii::app()->user->isGuest) {
		    echo '<a href="index.php?r=site/login" class="btn btn-link">Login</a>';
		    echo '<a href="index.php?r=tCustomer/create" class="btn btn-success">Sign up for free</a>';
              	    echo '</form>';
		} else {
		    echo '<a href="index.php?r=site/logout" class= "btn btn-success">Logout</a>';
		}
		?>
	</div>
	<!-- mainmenu -->

	</div>
	</div>
	</div>
	</div>
	<div class="container marketing">
		<?php echo $content; ?>

        	<footer>
            		<p class="pull-right"><a href="#">Back to top</a></p>
            		<p>© 2013 Good Wind Communications · <a href="#">Privacy</a> · <a href="#">Terms</a></p>
        	</footer>
	</div>
	<script language="javascript">
		$(function(){
   		$('.dropdown-toggle').dropdown();
   		$('.dropdown input, .dropdown label').click(function (e) {e.stopPropagation();});
		});
	</script>
</body>
</html>
