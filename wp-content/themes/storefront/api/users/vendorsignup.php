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
if($_SERVER['REQUEST_METHOD'] == "POST"){
	include_once '../config/database.php'; 
	include_once '../objects/user.php'; 
	$table_namemeta = "ya_usermeta";
	$database = new Database();
	$db = $database->getConnection();
	 
	$user = new User($db);
	$token = $user->getBearerToken();
  	if(empty($token)){
	$data = json_decode(file_get_contents('php://input'),true);
				// set user property values
				$user->username = $data['username'];
				$user->first_name = $data['first_name'];
				$user->last_name = $data['last_name'];
				$user->email = $data['email'];
				$user->useremail = $data['email'];
				$user->password = $data['password'];
				$user->cpassword = $data['cpassword'];
				$user->mobile = $data['mobile'];
				$user->brand_name = $data['brand_name'];
				$user->cpassword = $data['cpassword'];
				$user->userpassword = $data['cpassword'];
				$user->civil_id = $data['civil_id'];
				$user->device_id = $data['device_id'];
				$user->device_type = $data['device_type'];
				$user->created = date('Y-m-d H:i:s');
				
				$user->a_gender = $data['gender'];
				$user->a_birthdate = $data['birthdate'];
				$user->a_address = $data['address'];
				$user->show_location = $data['show_location']; 

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
	            if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
	            	if( strcmp($user->password,$user->cpassword) == 0 ){
	            		if(preg_match("/[0-9]+[0-9]+\/[0-9]+\/[0-9][0-9][0-9][0-9]/",$user->a_birthdate)){
	            			$query = "SELECT * FROM ".$table_namemeta." WHERE meta_key='billing_phone' AND meta_value='".$user->mobile."'"; 
	            			$stmt = $db->prepare($query); 
	            			$stmt->execute();
	            			if($stmt->rowCount() == 0) { 
		            			$result = $user->vendor_signup(); 	
								if(is_array($result)){
									$user_arr=array(
									"status" => true,
									"message" => "Successfully Signup!",
									"request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
									"end point" => $endpoint1[0],
									"body" => $result
									);
								} else {
									$user_arr = array(
									"status" => false,
									"message" => $result,
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
										"message" => "Invalid birthdate!!",
										"request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
										"end point" => $endpoint1[0],
										);
						}	
	            	} else {
	            		$user_arr = array(
							"status" => false,
							"message" => "Password do not match!!",
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
                    //"value" => $stmt,
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