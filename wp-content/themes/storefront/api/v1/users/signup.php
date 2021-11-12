<?php 
// required headers
//header("Access-Control-Allow-Origin: http://localhost/yeswa/wp-content/themes/storefront/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
$url = $_SERVER['REQUEST_URI'];
$requesturl = explode('?', $url);
$requesturl1 = explode('.',$requesturl[0]); 
$endpoint = (substr($requesturl1[0],strripos($requesturl1[0],'/') + 1)) . "." . $requesturl1[1];
$endpoint1 = explode('.', $endpoint);
if($_SERVER['REQUEST_METHOD'] == "POST"){
	include_once '../config/database.php';
	include_once '../objects/user.php';  
	include_once 'woocommerce-client.php';
	$table_namemeta = "ya_usermeta";
	$database = new Database();
	$db = $database->getConnection(); 
	$user = new User($db);
	// get posted data
  	
		$data = json_decode(file_get_contents('php://input'),true);  //print_r($data);
	    $log  = print_r($data, true);
        file_put_contents(dirname(__FILE__).'/log/signup_'.date("Y_m_d").'.log', $log, FILE_APPEND);
		$username = $data['username']; 
		$user->useremail = $data['email'];
		$user->userpassword = $data['password'];
		$user->usercpassword = $data['cpassword']; 
		$user->user_phone = $data['user_phone'];
		$user->device_id = $data['device_id'];
		$user->device_type = $data['device_type'];
		$user->woocommerce = $woocommerce;

		$user_name = explode(' ',$username); 
		$user->username = str_replace(" ","_",$username); 
		$user->user_firstname = $user_name[0];
		$user->user_lastname = $user_name[1];

		$user->country = (isset($data['country']) && !empty($data['country'])) ? $data['country'] : '' ;

		if($user->userpassword === $user->usercpassword){
			if (filter_var($user->useremail, FILTER_VALIDATE_EMAIL)) {
				$query = "SELECT * FROM ".$table_namemeta." WHERE meta_key='billing_phone' AND meta_value='".$user->user_phone."'"; 
	            $stmt = $db->prepare($query); 
	            $stmt->execute();
	            
	            if($stmt->rowCount() == 0) {
					$result = $user->create_user(); 
					if($result){ 
					 	$user_arr=array(
					        "status" => true,
					        "message" => "Successfully signup.",
					        "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
					        "end point" => $endpoint1[0],
					        "user_info" => $result,
					    );					   
					} else { 
					 		$user_arr=array(
						        "status" => false,
						        "message" => "Failed to signup!!",
						        "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
						        "end point" => $endpoint1[0],
					    	); 
					}
				} else {
	            		$user_arr = array(
										"status" => false,
										"message" => "Mobile number already exists!!",
										"request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
										"end point" => $endpoint1[0],
									);
	            	}
			} else {
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
			        "message" => "Passwords do not match!!",
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