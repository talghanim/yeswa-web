<?php  
header("Content-Type: application/json; charset=UTF-8");
header("Authorization:Bearer");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// include database and object files
$url = $_SERVER['REQUEST_URI'];
$requesturl = explode('?', $url);
$endpoint = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
$endpoint1 = explode('.', $endpoint); 
if($_SERVER['REQUEST_METHOD'] == "POST"){ 
    include_once '../config/database.php'; 
    include_once '../objects/user.php';  
    include_once 'validate_token.php';
    include_once 'woocommerce-client.php';  
    //header('Content-Type: application/json');
    // get database connection
    $database = new Database();
    $db = $database->getConnection();
     
    // prepare user object
    $user = new User($db);
    $validate = new Validate_token($db);
    $token = $user->getBearerToken();
    $validate_token = $validate->validate_token($token);
    $data = json_decode(file_get_contents('php://input'),true);
    $user->woocommerce = $woocommerce;
	
            $url = $_SERVER['REQUEST_URI'];
            $requesturl = explode('?', $url);
            $requesturl1 = explode('.',$requesturl[0]); 
            $endpoint = (substr($requesturl1[0],strripos($requesturl1[0],'/') + 1)) . "." . $requesturl1[1];

            $endpoint1 = explode('.', $endpoint);  
           
	
    if($validate_token){
            $log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
            "User: ".print_r($_POST,true).PHP_EOL.
            "FILES: ".print_r($_FILES,true).PHP_EOL.
            "-------------------------".PHP_EOL;
            //Save string to log, use FILE_APPEND to append.
		
            file_put_contents(dirname(__FILE__).'/log/'.time().'.log', $log,FILE_APPEND);
            //  $user->u_id = $data[u_id];
                $user->vid = $_POST[vid];  //print_r($_FILES); die;
                for ($i=1; $i < 30 ; $i++) { 
                    if(!empty($_FILES[image.$i])){
                            $user->image[$i]=$_FILES[image.$i];
                    }
                }	
				$error = 0;
				foreach($user->image as $key => $value){
					if($value["size"] == 0 || $value["size"] == ''){
						$error = 1;
						break;
					}
				}
                $user->p_id = $_POST[p_id];
                if($user->verify_token($user->uid,$user->vid,$token)){
					if($error == 0){
						if($result=$user->upload_product_image()) {
							$user_arr=array(
								"status" => true,
								"message" => "Product images updated successfully!",
								"request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
								"end point" => $endpoint1[0],
								"body"=>$result,
							);
						}
						else {
							$user_arr=array(
								"status" => false,
								"message" => "Failed to update product images!",
								"request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
								"end point" => $endpoint1[0],
							);
						}
					} else {
						$user_arr=array(
								"status" => false,
								"message" => "Failed to upload, Images are empty",
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
// make it json format
print_r(json_encode($user_arr));
?>
