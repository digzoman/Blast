<?php
$database_url   = "localhost";
$database_user_name = "tazamed_impe";
$database_password = "x(JW-(=H0IsB";
$database_name  = "tazamed_impact_eng";
$consumer_key = '8ro5j61ufm7u';
$consumer_secret = 'lhZ7H7scalor6tiw';
$errors = fopen("errors.txt",'a');
$db = mysql_connect($database_url,$database_user_name, $database_password );
if (!$db) {
		die('Could not connect to database' . mysql_error());
}
mysql_select_db($database_name, $db);
$url = 	'http://api.linkedin.com/v1/people/~/mailbox';
$oauth = new OAuth($consumer_key, $consumer_secret);
$subject='38 VCs confirmed in NYC Thursday Feb. 7';
$message ='Please pay this forward by going to www.crowdlinker.com/thesoholoft and let your network in the tri-state area know about The Soho Loft and FP event in NYC next Thursday Feb. 7th that I want to attend: http://ideas-for-fund-raising-vc-angel-showcase-newyork.eventbrite.com.I thought your colleagues and friends running a firm or considering a start up or even expansion capital for growth or refinancing would appreciate hearing from you and seeing that 35 VCs are at this event exclusively looking to cut investment checks. This is the year\'s largest event out of 25 per year and these have been running for the last 11 years - it is by far the best event. Thanks for sharing this and looking forward to hearing from you on how your friends fared at the event.';
$result = mysql_query("select token,secret,member_id from tokens");
while($row = mysql_fetch_array($result))
{
$member = $row['member_id'];
$token = $row['token'];
$secret = $row['secret'];
$oauth->setToken($token,$secret);

$result = mysql_query("select * from ToBeSent where sender_id = '$member' and sent = 0 limit 100");
$i = 0;
$body = array();
$body['recipients'] = array();
$body['recipients']['values'] = array();
//$s = '{"recipients":{ "values": [';	 
while($row = mysql_fetch_array($result))
{
    mysql_query("update ToBeSent set sent = 1 where id = $row[id]");
		    $body['recipients']['values'][$i] = array();
		      $body['recipients']['values'][$i]['person'] =array();
             $body['recipients']['values'][$i]['person']['_path'] =  "/people/$row[reciever_id]";
$i++;			 
		

}
$body['subject'] = $subject;
$body['body'] = $message;
	$body = json_encode($body);
echo "$body <br/><br/>";
try
{
$oauth->fetch($url,  $body, OAUTH_HTTP_METHOD_POST, array(
    
    "x-li-format" => "json",
	  "Content-Type" => "application/json"));

//parse_str($oauth->getLastResponse(), $response);
//print_r($response);
}
catch(exception $a)
{
echo "caught";
fwrite($errors, print_r($a);
}
}
?>