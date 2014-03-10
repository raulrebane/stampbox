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
            'select'=> array('count(invited_email) as invitedtotal, count(invited_when) as invited'),
            'from' => 'ds.t_invitations',
            'where'=> 'customer_id=:1',
            'params' => array(':1'=>Yii::app()->user->getId()),
        ))->queryRow();
        echo '<div class="box col2 stampwhite"><div class="content-col2">';
            $this->widget('bootstrap.widgets.TbLabel', array(
                'type'=>'success', // 'success', 'warning', 'important', 'info' or 'inverse'
                'label'=>'You have:',));
        echo '<br><h1>' .$stampcount['count'] .'<small> Stamps left</small></h1></div></div>
              <div class="box col1 col1-link stampcolor" onclick="location.href=\'/stampbox/index.php?r=/shop/buystamps\';">
              <div class="content-col1-center">Buy stamps</div></div>
              <div class="box col1 stampyellow" onclick="location.href=\'/stampbox/index.php?r=/tCustomer/changepsw\';">
              <div class="content-col1-center">Change password</div></div>
              <div class="box col2 stampwhite"><div class="content-col2">';
              $this->widget('bootstrap.widgets.TbLabel', array(
                'type'=>'success', // 'success', 'warning', 'important', 'info' or 'inverse'
                'label'=>'Invited:',));        
        echo '<h1>' .$invitationcount["invited"] .' of ' .$invitationcount["invitedtotal"] .'</h1></div></div>
    <div class="box col5 stampblue">Blue</div>
    <div class="box col1 stampgreen">Green</div>
    <div class="box col1 stampyellow">Yellow</div>';
}
?>