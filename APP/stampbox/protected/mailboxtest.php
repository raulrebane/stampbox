<?php
    /* connect to gmail */
    $hostname = '{imap.gmail.com:993/ssl/novalidate-cert}';
    $username = '';
    $password = '';

    /* try to connect */
    $inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
    
    echo 'inbox opened';

    /* grab emails */
    $emails = imap_search($inbox,'ALL');

    /* if emails are returned, cycle through each... */
    if($emails) {

      /* begin output var */
      $output = '';
      $top_senders = array();

      /* put the newest emails on top */
//      rsort($emails);

      /* for every email... */
      foreach($emails as $email_number) {

        /* get information specific to this email */
        $overview = imap_fetch_overview($inbox,$email_number,0);
//        $message = imap_fetchbody($inbox,$email_number,2);

	if (array_key_exists($overview[0]->from,$top_senders))
		{ $top_senders[imap_utf8($overview[0]->from)] = $top_senders[$overview[0]->from] + 1; }
	else
		{$top_senders[imap_utf8($overview[0]->from)] = 1; }

        /* output the email header information */
        //$output.= '<div class="toggler '.($overview[0]->seen ? 'read' : 'unread').'">';
//        $output.= '<span class="subject">'.$overview[0]->subject.'</span> ';
//        $output.= '<span class="from">'.$overview[0]->from.'</span>';
//        $output.= '<span class="date">on '.$overview[0]->date.'</span>';
//        $output.= '</div>';

        /* output the email body */
//        $output.= '<div class="body">'.$message.'</div>';
      }

//      echo $output;
	
	var_dump($top_senders);
    } 

    /* close the connection */
    imap_close($inbox);
    echo 'inbox closed';
    ?>
