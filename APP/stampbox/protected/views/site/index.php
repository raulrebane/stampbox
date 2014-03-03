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
?>
