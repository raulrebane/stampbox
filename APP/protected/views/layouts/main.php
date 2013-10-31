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

        <script src="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery-2.0.3.min.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/css/holder.js"></script>

<!--

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
-->

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
    <div class="navbar-wrapper">
        <div class="container">
        <div class="navbar navbar-inverse navbar-static-top">
        <div class="container">
        <div class="navbar-header">

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
	</div>
	<!-- mainmenu -->

	<?php
	/** Start Widget **/
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    		'id'=>'loginmodal',
    		'options'=>array(
        		'title'=>'Login',
        		'width'=>400,
        		'height'=>300,
        		'autoOpen'=>false,
        		'resizable'=>false,
        		'modal'=>true,
        		'overlay'=>array(
            			'backgroundColor'=>'#000',
            			'opacity'=>'0.5'
        			),
        		'buttons'=>array(
            			'OK'=>'js:function(){alert("OK");}',
            			'Cancel'=>'js:function(){$(this).dialog("close");}',
        			),
    			),
		));
		echo '<div class="form-group">
			<label for="exampleInputEmail1">Username</label>
    			<input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
  		</div>
  		<div class="form-group">
    			<label for="exampleInputPassword1">Password</label>
    			<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
  		</div>';
	$this->endWidget('zii.widgets.jui.CJuiDialog');	
	/** End Widget **/
	?>
	<?php
             if (Yii::app()->user->isGuest) {
		echo '<form class="navbar-form navbar-right">';
		echo '<a class="btn btn-link" onclick="$(\'#loginmodal\').dialog(\'open\'); return false;" href="#">Login</a>';
		//echo <a href="index.php?r=site/login" class="btn btn-link">Login</a>;
		echo '<a href="index.php?r=tCustomer/create" class="btn btn-success">Sign up for free</a>';
              	echo '</form>';
		} else {
		echo '<form class="navbar-form navbar-right">';
		echo '<a href="index.php?r=site/logout" class= "btn btn-success">Logout</a>';
		}
	?>
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
</body>
</html>
