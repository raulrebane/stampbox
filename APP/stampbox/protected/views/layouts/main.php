<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta>
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <link href="/stampbox/css/sticky-footer.css" rel="stylesheet">
</head>
<body>
<!--    <div class="container">
        <img src ="/stampbox/images/SB_logo_01.png" />
    </div>
-->
<div class="navbar-wrapper"> 
<div class="container">
<?php $this->widget('bootstrap.widgets.TbNavbar',array(
	'type'=>'inverse',
        'fixed'=> false,
//        'brand'=>CHtml::encode('<img>src="/stampbox/images/SB_logo_01.png"></img>Stampbox'),
        'brandOptions'=>array('<img'=>'/stampbox/images/SB_logo_01.png"</img>',),
	'brand'=>CHtml::encode(Yii::app()->name),
	'brandUrl'=> 'index.php?r=/site/index',
	'collapse'=>true,
	'items'=>array(
		array(
			'class'=>'bootstrap.widgets.TbMenu',
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
            
            array(
			'class'=>'bootstrap.widgets.TbMenu',
			'items'=>array(
         			array('label'=>'Logout', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
                                   ),
			'htmlOptions'=>array('class'=>'pull-right'),
                        ),
	),
)); ?>
</div></div>   
    <div class="container">
        <?php if (!Yii::app()->user->isGuest)
            { echo '<row class="row-fluid"><column class="col1 span2">';
              $this->widget('bootstrap.widgets.TbMenu', array(
                'type'=>'list',
                'items'=>array(
                    array('label'=>'My account'),
                    array('label'=>'View', 'icon'=>'home', 'url'=>'index.php?r=tCustomer/view'),
                    array('label'=>'Change password', 'icon'=>'', 'url'=>'index.php?r=tCustomer/changepsw'),
                    array('label'=>'Reset password', 'icon'=>'pencil', 'url'=>'index.php?r=tCustomer/resetpsw'),
                    array('label'=>'My mailboxes'),
                    array('label'=>'View', 'icon'=>'user', 'url'=>'index.php?r=usermailbox/index'),
                    array('label'=>'Statistics', 'icon'=>'cog', 'url'=>'index.php?r=usermailbox/stats'),
                    array('label'=>'Help', 'icon'=>'flag', 'url'=>'#'),
                    array('label'=>'My stamps'),
                    array('label'=>'Buy', 'url'=>'#'),
                    array('label'=>'History', 'url'=>'index.php?r=StampsTransactions/index'),
                   ),
                ));
        echo '</column><column class="col2 span10">';
	echo $content;
        echo '</column></row>';
            }
        else { echo $content;}
	?>
    </div>
    <div id="footer"><div class="container">
        <p class="pull-right"><a href="#">Back to top</a></p>
        <p>© 2013 Good Wind Communications<br><a href="#">Privacy</a> <br> <a href="#">Terms</a></p>
        </div></div>
</div>
</body>
</html>
