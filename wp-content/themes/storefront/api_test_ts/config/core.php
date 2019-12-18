<?php	
// show error reporting
error_reporting(E_ALL);
 
// set your default time-zone
date_default_timezone_set('Asia/Manila');
 
// variables used for jwt
//$url = get_option('siteurl');	
$key = "example_key";
$iss = 'http://yeswa.theclientdemos.com';		 
$aud = 'http://yeswa.theclientdemos.com';
$iat = time();			
$nbf = ($iat + 2);
// expire token after 3month from the date of creation   	
$exp = $iat+7921721;
?>
