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
mysql_query("insert into ToBeSent ( name,password , email) values ('$_POST[name]', '$_POST[password]' ,'$_POST[email]')");
$_COOKIE['user'] = $_POST['user'];

mysql_close();


?>