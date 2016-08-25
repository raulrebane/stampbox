<?php
$url = 'https://autoconfig.thunderbird.net/v1.1/';

$html = file_get_contents($url);

$count = preg_match_all('#href=\".*\..*\"#', $html, $files);
//var_dump($files);
$dbconn = pg_connect("host=localhost port=5432 dbname=stampbox user=raulr password=Wfd9epa4"); 

for ($i = 1; $i < $count; ++$i) {
    $files[0][$i] = substr($files[0][$i], 6, strlen($files[0][$i])-7);
    echo 'Parsing file: ' .$files[0][$i] ."\n";
    $xml = file_get_contents($url . $files[0][$i]);
    $xmlparser = xml_parser_create();
    $parsed = xml_parse_into_struct($xmlparser, $xml, $xmlvalues, $xmltags);
    if ($parsed == 0) {
      echo "XML parsing failed: " .$xml ."\n";
      continue;
    }
    $domains = array();
    $parsing_incoming = FALSE;
    $hostname = '';
    foreach ($xmlvalues as $xmltag) {
      //print_r($xmltag);
      if (array_key_exists('tag', $xmltag)) {
        if ($xmltag['tag'] == 'DOMAIN') {
          $domains[] = $xmltag['value'];
        }
        if ($xmltag['tag'] == 'INCOMINGSERVER' && $xmltag['type'] == 'open' && $xmltag['attributes']['TYPE'] == 'imap') {
          if ($hostname == '') { $parsing_incoming = TRUE; }
        }
        if ($xmltag['tag'] == 'INCOMINGSERVER' && $xmltag['type'] == 'close') {
          $parsing_incoming = FALSE;
        }
        if ($parsing_incoming == TRUE && $xmltag['tag'] == 'HOSTNAME') { $hostname = $xmltag['value']; }
        if ($parsing_incoming == TRUE && $xmltag['tag'] == 'PORT') { $port = $xmltag['value']; }
        if ($parsing_incoming == TRUE && $xmltag['tag'] == 'SOCKETTYPE') { 
	  switch($xmltag['value']) {
	    case "SSL": $sockettype = 'SSL';  break;
            case "STARTTLS": $sockettype = 'TLS';  break;
	    default : $sockettype = NULL;
          }
	}
        if ($parsing_incoming == TRUE && $xmltag['tag'] == 'USERNAME') { 
          switch($xmltag['value']) {
            case "%EMAILADDRESS%": $username = 'EMAIL';  break;
            case "%EMAILLOCALPART%": $username = 'USERNAME';  break;
            default : $username = 'MANUAL';
          }
        }
      }
    }
    foreach ($domains as $domain) {
      $mailboxconfig = pg_query($dbconn, "select * from ds.t_mailbox_config where maildomain = '" .$domain ."';");
      if (pg_num_rows($mailboxconfig) > 0) {
         echo "Mailbox $domain configuration already exists\n";
      }
      else {
         echo "Adding $domain to database\n";
	 $mbox['maildomain'] = $domain;
         $mbox['mailtype'] = 'IMAP';
         $mbox['incoming_hostname'] = $hostname;
         $mbox['incoming_port'] = $port;
         $mbox['incoming_socket_type'] = $sockettype;
         $mbox['incoming_auth'] = $username;
         $res = pg_insert($dbconn, 'ds.t_mailbox_config', $mbox);
      }
    }
    //print_r($domains);
    echo($hostname .' ' .$port .' ' .$sockettype .' ' .$username ."\n");
    unset($domains);
    xml_parser_free($xmlparser);

}

pg_close($dbconn);

?>
