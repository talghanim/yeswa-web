<?php
header("Content-Type: application/json; charset=UTF-8");
header("Authorization:Bearer");
header("Access-Control-Allow-Methods: GET,POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");  
// get database connection 
// get database connection
$url = $_SERVER['REQUEST_URI'];
$requesturl = explode('?', $url);
$endpoint = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
$endpoint1 = explode('.', $endpoint);
	include_once '../config/database.php';
	include_once '../objects/user.php';
	include_once 'validate_token.php';
	 
	$database = new Database();
	$db = $database->getConnection();
	 
	$user = new User($db);
	$user->v_id = $_GET['uid'];
		$user->v_id = $_REQUEST['uid'];
	$validate = new Validate_token($db);
	$token = $user->getBearerToken();
	$validate_token = $validate->validate_token($token);	

if($_SERVER['REQUEST_METHOD'] == "GET"){ 
	$user->v_id = $_GET['uid'];
	$user->update = 0;
	//$data = json_decode(file_get_contents('php://input'),true);
	if($validate_token){	
			if($user->verify_token($user->v_id,$vendor_id,$token)){
					if($vendor_detail = $user->vendor_profile()){
					$user_arr = array(
					"status" => true,
					"message" => "User details found!",
					"request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
					"end point" => $endpoint1[0],
					"body" => $vendor_detail,
					);
					}
					else {
					$user_arr = array(
					"status" => false,
					"message" => "User details not found!",
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
	    $user_arr = array(
	                "status" => false,
	                "message" => "Invalid access Method!!",
	                "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
	                "end point" => $endpoint1[0],
	            );
	}
} elseif ($_SERVER['REQUEST_METHOD'] == "POST") { 
	if($validate_token){
		$log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
            "User: ".print_r($_POST,true).PHP_EOL.
            "FILES: ".print_r($_FILES,true).PHP_EOL.
            "-------------------------".PHP_EOL;
            //Save string to log, use FILE_APPEND to append.
            file_put_contents(dirname(__FILE__).'/log/'.date("yeswa_j.n.Y").'.log', $log, FILE_APPEND);
            
		$user->update = 1;
		$user->v_id = $_REQUEST['uid'];
		$user->v_id = $_POST['uid'];
		$user->update = 1;
		$user->uid = $_POST[uid];
		$user->display_name = $_POST[display_name]; 
		$name = explode(' ', $_POST[display_name]);
		$user->first_name = $name[0];
		$user->last_name = $name[1];
		$user->user_email = $_POST[email];
		$user->billing_phone = $_POST[mobile];
		$user->billing_company = $_POST[brand_name];
		$user->a_civil_id = $_POST[civil_id];
		$user->a_gender = $_POST[gender];
		$user->a_birthdate = $_POST[birthdate];
		for ($i=1; $i < 100 ; $i++) { 
			if(!empty($_FILES[image.$i])){
					$user->image[$i]=$_FILES[image.$i];
			}
		}
		$user->a_address = $_POST[address];
		$user->show_location = $_POST[show_location];
		
		
		

		$ch = curl_init();
	            $address = str_replace('&','',str_replace('#','',$user->a_address));
	            $formattedAddr = str_replace(' ','+',$address);	//print_r($formattedAddr);
	            curl_setopt($ch, CURLOPT_URL, 'https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&key=AIzaSyDC3gn4XhXTHyH6SEst1WqEcEdIiWs59PM'); 
	            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	            $output = curl_exec($ch); 
	            $output = json_decode($output);
	            
	            // handle error; error output
	            if(curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {	
	                $user->lat = 0;
	                $user->long = 0;
	            }else{		
	                    $user->lat  = $output->results[0]->geometry->location->lat; 	//print_r($user->lat);
	                    $user->long = $output->results[0]->geometry->location->lng;
	                    //Return latitude and longitude of the given address
	                    if(empty($user->a_address) || (empty($user->lat) && empty($user->long))){
	                        $user->lat = 0;
	                        $user->long = 0;
	                    } 
	            } 
			if($user->vendor_profile()){
			$user_arr = array(
			"status" => true,
			"message" => "User details updated sucessfully!",
			"request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
			"end point" => $endpoint1[0],
			//"body" => $vendor_detail,
			);
			}
			else {
			$user_arr = array(
			"status" => false,
			"message" => "User details not found!",
			"request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
			"end point" => $endpoint1[0],
			);
			}
	} else {
	    $user_arr = array(
	                "status" => false,
	                "message" => "Invalid access Method!!",
	                "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
	                "end point" => $endpoint1[0],
	            );
	}

}

else {
    $user_arr = array(
                    "status" => false,
                    "message" => "Undefined access method!!",
                    "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                    "end point" => $endpoint1[0],
                );
}
print_r(json_encode($user_arr));

?>