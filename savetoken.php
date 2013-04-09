<?php
echo get_include_path();
set_include_path('./HTTP/OAuth');
$database_url   = "localhost";
$database_user_name = "tazamed_impe";
$database_password = "x(JW-(=H0IsB";
$database_name  = "tazamed_impact_eng";
$db = mysql_connect($database_url,$database_user_name, $database_password );
if (!$db) {
		die('Could not connect to database' . mysql_error());
}
	mysql_select_db($database_name, $db);
	$consumer_key = '8ro5j61ufm7u';
	$consumer_secret = 'lhZ7H7scalor6tiw';
	
	$cookie_name = "linkedin_oauth_${consumer_key}";
	$credentials_json = $_COOKIE[$cookie_name]; // where PHP stories cookies
	$credentials = json_decode($credentials_json);
	print_r($_COOKIE);
	// validate signature
	if ($credentials->signature_version == 1) {
		if ($credentials->signature_order && is_array($credentials->signature_order)) {
			$base_string = '';
			// build base string from values ordered by signature_order
			foreach ($credentials->signature_order as $key) {
				if (isset($credentials->$key)) {
					$base_string .= $credentials->$key;
				} else {
					print "missing signature parameter: $key";
				}
			}
			// hex encode an HMAC-SHA1 string
			$signature =  base64_encode(hash_hmac('sha1', $base_string, $consumer_secret, true));
			// check if our signature matches the cookie's
			if ($signature == $credentials->signature) {
				print "signature validation succeeded<br>";
			} else {
				print "signature validation failed";    
			}
		} else {
			print "signature order missing";
		}
	} else {
		print "unknown cookie version";
	}
	$access_token_url = 'https://api.linkedin.com/uas/oauth/accessToken';

// init the client

$access_token = $credentials->access_token;
$oauth = new OAuth($consumer_key, $consumer_secret);
$oauth->fetch($access_token_url, array('xoauth_oauth2_access_token' => $access_token), OAUTH_HTTP_METHOD_POST);
parse_str($oauth->getLastResponse(), $response);
$ip = $_SERVER['REMOTE_ADDR'];
$token = $response['oauth_token'];
$secret = $response['oauth_token_secret'];
$member = $credentials->member_id;
mysql_query("insert into tokens (token,secret, name , member_id , ip , time) values('$token', '$secret' , '$_POST[name]' , '$member' ,'$ip' , now())");
echo mysql_error();

mysql_close();
?>