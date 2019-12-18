<?php
header("Content-Type: application/json; charset=UTF-8");
header("Authorization:Bearer");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); 
// get database connection
$url = $_SERVER['REQUEST_URI'];
$requesturl = explode('?', $url);
$endpoint = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
$endpoint1 = explode('.', $endpoint);
if($_SERVER['REQUEST_METHOD'] == "GET"){
	include_once '../config/database.php';
	include_once '../objects/user.php';
	include_once 'validate_token.php';

	$database = new Database();
	$db = $database->getConnection();
	 
	$user = new User($db);
	$validate = new Validate_token($db);
	$token = $user->getBearerToken();
	$validate_token = $validate->validate_token($token);
	//$data = json_decode(file_get_contents('php://input'),true);

		$url = $_SERVER['REQUEST_URI'];
		$requesturl = explode('?', $url);
		$endpoint = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
		$endpoint1 = explode('.', $endpoint);
		
	if($validate_token){
		$user->vid = $_GET['vid'];


		if($user->verify_token($user->uid,$user->vid,$token)){
			if($vendor_account_details = $user->vendor_account()){
			$user_arr = array(
			"status" => true,
			"message" => "vendor details found!",
			"request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
			"end point" => $endpoint1[0],
			"body" => $vendor_account_details,
			);
			}
			else {
			$user_arr = array(
			"status" => false,
			"message" => "vendor details not found!",
			"request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
			"end point" => $endpoint1[0],
			);
			}
		} else {
            $user_arr   =  array(
                "status" => false,
                "message" => "Token not found!",
                "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                "end point" => $endpoint1[0],
                //"arguments" => $args,
            );
    } 
	} else {
	    $user_arr=array(
	                "status" => false,
	                "message" => "User not found!!",
	                "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
	                "end point" => $endpoint1[0],
	            );
	}
} else {
    $user_arr = array(
                    "status" => false,
                    "message" => "Undefined access method!!",
                    "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                    "end point" => $endpoint1[0],
                );
}

print_r(json_encode($user_arr));
?>