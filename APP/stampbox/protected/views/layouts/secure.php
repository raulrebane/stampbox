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
        <link rel="stylesheet" href="css/main20150413.css">
        <script src="scripts/bootstrap.js"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-59363252-1', 'auto');
  ga('send', 'pageview');

</script>
    </head>
    <body class="home">
        <div class="menuheader">
            <div class="container">
            <div class="row header">
                <div class="col-xs-1">
                    <a class="" href="<?php echo Yii::app()->createUrl('site/index') ?>">
                        <img src="images/logo-white-trans.png" height="42" width="42">
                    </a>
                </div>
                <div class="col-xs-11">
                    <a class="btn btn-dark pull-right" style="margin-left: 10px;" href="<?php echo Yii::app()->createUrl('site/closeaccount') ?>">Close account</a>
                    <a class="btn btn-dark pull-right" href="<?php echo Yii::app()->createUrl('site/logout') ?>">Logout</a>
                </div>
            </div>
            </div>
        </div>    
        <div class="nav mainmenu">
        <div class="container">
            <div class="navbar-header">
                <button type="btn" class="navbar-toggle" data-toggle="collapse" data-target="#mainmenu">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span> 
                </button>
            </div>
            <div class="row header text-center">
                <?php $this->widget('zii.widgets.CMenu',array(
                    'items'=>array(
                        array('label'=>'Overview', 'url'=>array('/site/index')),
                        array('label'=>'Buy stamps', 'url'=>array('/shop/buy')),
                        array('label'=>'Invite', 'url'=>array('/invite/index')),
                        array('label'=>'Whitelist', 'url'=>array('/whitelist/index')),
                        array('label'=>'My activity', 'url'=>array('/account/statement')),
                        array('label'=>'Stampboxed e-mails', 'url'=>array('/usermailbox/index')),
                        array('label'=>'Help', 'url'=>array('/site/help')),
                        ),
                    'htmlOptions' => array('class'=>'nav navbar-nav collapse navbar-collapse mainmenu', 'id'=>'mainmenu' 
                        ),
                    )); ?>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="dashboard inset">
                <div class="row">
                    <div class="col-md-12">
                        <?php foreach(Yii::app()->user->getFlashes() as $key => $message) {
                            echo '<div class="alert alert-' .$key .'">' .$message ."</div>\n";
                        }

                        $usermessages = Message::model()->findAll('(customer_id=:1 and (page_id=:2 OR page_id = NULL) AND showcount > 0) OR customer_id = NULL', 
                                    array(':1'=>Yii::app()->user->getId(), ':2'=>Yii::app()->controller->getId() 
                                            .'/' .Yii::app()->controller->getAction()->getId()));
                        if ($usermessages) {
                            foreach ($usermessages as $message) {
                                echo '<div class="alert alert-dismissible alert-' .$message->message_type .'" data-id="'.$message->message_id .'">'
                                    .'<a class="close" data-dismiss="alert" aria-label="close">&times;</a>'
                                    ."$message->message</div>";
                                $message->showcount = $message->showcount-1;
                                $message->save();
                            }
                        }
                        echo $content 
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function($) {		
		$('.alert-dismissible').bind('closed.bs.alert', function () {
                    var id = $(this).data('id') || 0;
                    $.get('index.php?r=site/closemessage&message_id=' + escape(id));
			});
		});
        </script>
    </body>
</html>
