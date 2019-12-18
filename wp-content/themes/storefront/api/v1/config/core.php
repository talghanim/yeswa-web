<?php	
// show error reporting
error_reporting(E_ALL);
 
// set your default time-zone
date_default_timezone_set('Asia/Manila');
 
// variables used for jwt
//$url = get_option('siteurl');	
$key = "example_key";
$iss = 'http://www.yeswa.shop';		 
$aud = 'http://www.yeswa.shop';
$iat = time();			
$nbf = ($iat + 2);
// expire token after 3month from the date of creation   	
$exp = $iat+7921721;
?>
