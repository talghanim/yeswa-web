<?php 	
header("Content-Type: application/json; charset=UTF-8");

header("Access-Control-Allow-Methods: POST");
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

	$database = new Database();
	$db = $database->getConnection();
	 
	$user = new User($db);
	$token = $user->getBearerToken();
  	if(empty($token)){
	
	$user->username = isset($_GET['username']) ? $_GET['username'] : die();
    $user->email = isset($_GET['email']) ? $_GET['email'] : die();
	$user->mobile = isset($_GET['mobile']) ? $_GET['mobile'] : die();          	
	            if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
			            	$result = $user->vendor_validate(); 
			            	if($result['status'] == 'true')
			            		$status = true;
			            	else
			            		$status = false;	
							if(is_array($result)){
								$user_arr=array(
								"status" => $status,
								//"message" => "Successfully Signup!",
								"request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
								"end point" => $endpoint1[0],
								"body" => array ('message' => $result['message'])
								);
							} else {
								$user_arr = array(
								"status" => false,
								"message" => $result,
								"request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
								"end point" => $endpoint1[0],
								);
							}
				} else{
					$user_arr = array(
						"status" => false,
						"message" => "Invalid Email!!",
						"request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
						"end point" => $endpoint1[0],
						);
				}
	} else {
  					$user_arr=array(
                    "status" => false,
                    "message" => "Undefined authorization!!",
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