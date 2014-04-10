<?php
/* @var $this SiteController */
if (Yii::app()->user->isGuest)
 { 
    $this->widget('bootstrap.widgets.TbCarousel', array(
    'items'=>array(
        array('image'=>'http://placehold.it/1200x400&text=The only way...',
                'label'=>'...to make e-mails worth your time.', 
                'caption'=>'In today\'s world the main channel of communications is e-mail as it is comfortable and "free"'
                .' to use. But time is money and your time spent on reading e-mails is your money. It\'s time to make it worth'
                . 'with <strong>Stampbox</strong> service.'),
        array('image'=>'http://placehold.it/1200x400&text=Why free is not...', 
                'label'=>'... always good', 
                'caption'=>'In today\'s world the main channel of communications is e-mail as it is comfortable and "free".'
                    .'This has led to a situation where the your Inboxes are filled with "noise" and just with excess information'
                    .' which takes immense time to go through, analyse and digest. Time is still a very valuable asset!'),
        array('image'=>'http://placehold.it/1200x400&text=How to filter...',
            'label'=>'... important from not-so-important?',
            'caption'=>'This filter is money. If the sender is in great need of passing on the message, then to guarantee '
            .' that you would find time for reading the letter, then the time spent by you has to be compensated for.'),
        array('image'=>'http://placehold.it/1200x400&text=There is a solution.',
            'label'=>'Stampbox.',
            'caption'=>'Stampmail is a service that enables you to organise your mailbox so that the senders must attach on'
            .' their letters paid digital stamps. Digitally stamped letters will all land in a folder named Stampbox. The letters'
            .' which are not stamped digitally are send to the "Low Priority Mail folder".'),
),)); 
 }
 else {
        $stampcount = Yii::app()->db->createCommand(array(
            'select'=> array('count(*)'),
            'from' => 'ds.t_stamps_issued',
            'where'=> 'issued_to=:1 and status=:2',
            'params' => array(':1'=>Yii::app()->user->getId(), ':2'=>'U'),
        ))->queryRow();
        $invitationcount = Yii::app()->db->createCommand(array(
            'select'=> array('count(invited_email) as invitedtotal, count(invite) as invited'),
            'from' => 'ds.t_invitations',
            'where'=> 'customer_id=:1',
            'params' => array(':1'=>Yii::app()->user->getId()),
        ))->queryRow();
        $lasttransactions = Yii::app()->db->createCommand(array(
            'select'=> array('*'),
            'from'=> 'ds.t_stamps_transactions',
            'where'=> 'customer_id = :1',
            'order'=> 'transaction_id desc',
            'limit'=> '10',
            'params'=> array(':1'=>Yii::app()->user->getId()),
        ))->queryAll();
        echo '<div class="box col2 stampwhite"><div class="content-col2">';
            $this->widget('bootstrap.widgets.TbLabel', array(
                'type'=>'success', // 'success', 'warning', 'important', 'info' or 'inverse'
                'label'=>'You have:',));
        echo '<br><h1>' .$stampcount['count'] .'<small> Stamps left</small></h1></div></div>
              <div class="box col1 col1-link stampcolor" onclick="location.href=\'#\';">
              <div class="content-col1-center"><p>Buy stamps</p></div></div>
              <div class="box col1 col1-link stampcolor" onclick="location.href=\'/stampbox/index.php?r=register/step2\';">
              <div class="content-col1-center"><p>Add e-mail</p></div></div>
              <div class="box col2 stampwhite"><div class="content-col2">';
              $this->widget('bootstrap.widgets.TbLabel', array(
                'type'=>'success', // 'success', 'warning', 'important', 'info' or 'inverse'
                'label'=>'Invited:',));        
        echo '<h1>' .$invitationcount["invited"] .' of ' .$invitationcount["invitedtotal"] .'</h1></div></div>
    <div class="box col1 col1-link stampyellow" onclick="location.href=\'/stampbox/index.php?r=/tCustomer/changepsw\';">
    <div class="content-col1-center">Change password</div></div>';
        echo '<div class="box span6 stampwhite"><div class="content-colauto">';
        $this->widget('bootstrap.widgets.TbLabel', array(
                'type'=>'success', // 'success', 'warning', 'important', 'info' or 'inverse'
                'label'=>'Last 10 transactions:',));
        $gridDataProvider = new CArrayDataProvider($lasttransactions, array('keyField'=>'transaction_id', ));  
        $gridColumns = array(
            array('name'=>'transaction_date', 'header'=>'Date'),
            array('name'=>'stamps', 'header'=>'Stamps'),
            array('name'=>'transaction_points', 'header'=>'Credits'),
            array('name'=>'description', 'header'=>'Description')
            );
        $this->widget('bootstrap.widgets.TbGridView',array(
            'id'=>'smallstatement-grid',
            'type'=>'bordered',
            'enablePagination'=>TRUE,
            'dataProvider' => $gridDataProvider,
            'template' => "{items}",
            'columns'=>$gridColumns));      
        echo '</div></div>';
        echo '<div class="box span6 stampwhite"><div class="content-colauto">';
        $this->widget('bootstrap.widgets.TbHighCharts',
            array(
                'options' => array(
                    'title'=>array('text'=>'Stamped vs. non-Stamped e-mails received', x=>-20),
                    'xAxis'=>array('categories'=>['30','20','10','1']),
                'series' => array(['data' => [1, 2, 3, 4, 5, 1, 2, 1, 4, 3, 1, 5]])
                )
            )
        );
        echo '</div></div>';
}
?>
