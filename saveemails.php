<?php
$database_url   = "localhost";
$database_user_name = "tazamed_impe";
$database_password = "x(JW-(=H0IsB";
$database_name  = "tazamed_impact_eng";
$consumer_key = '8ro5j61ufm7u';
$db = mysql_connect($database_url,$database_user_name, $database_password );
if (!$db) {
		die('Could not connect to database' . mysql_error());
}
mysql_select_db($database_name, $db);
$cookie_name = "linkedin_oauth_${consumer_key}";
$credentials_json = $_COOKIE[$cookie_name]; // where PHP stories cookies
$credentials = json_decode($credentials_json);
$member = $credentials->member_id;
$ip = $_SERVER['REMOTE_ADDR'];
$_POST['names'] = explode(",", $_POST['names']);
$_POST['ids'] = explode(",", $_POST['ids']);
for($i = 0 ; $i < count($_POST['ids']) ;  $i++)
{
 $name = $_POST['names'][$i];
  $id =  $_POST['ids'][$i];
  mysql_query("insert into ToBeSent ( sender_id ,reciever_id , reciever, ip) values ('$member', '$id' ,'$name' , '$ip')");
  
}
mysql_close();
?>