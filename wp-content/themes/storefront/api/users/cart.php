<?php  
header("Content-Type: application/json; charset=UTF-8");
header("Authorization:Bearer");
header("Access-Control-Allow-Methods: GET,POST,DELETE");
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
    include_once 'woocommerce-client.php';
    include_once 'cart-client.php'; 
     
    $database = new Database();
    $db = $database->getConnection();
     
    $user = new User($db);
    //$user->uid = $_GET['uid'];
    $validate = new Validate_token($db);
    $token = $user->getBearerToken();
    //$validate_token = $validate->validate_token($token);
    if(!empty($token)){
        $validate_token = $validate->validate_token($token);
    } else {
        $validate_token = 1;
    }
    $user->woocommerce = $woocommerce;
    $user->cocart = $cocart;    
if($_SERVER['REQUEST_METHOD'] == "GET"){ 
    //$data = json_decode(file_get_contents('php://input'),true);
    if($validate_token){
    //    if(empty($token) || $user->verify_token($user->uid,$user->vid,$token)){
            if($cart = $user->cart()){ 
            $user_arr = array(
            "status" => true,
            "message" => "User cart details found!",
            "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
            "end point" => $endpoint1[0],
            "body" => $cart,
            );
            }
            else {
            $user_arr = array(
            "status" => false,
            "message" => "User cart details not found!",
            "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
            "end point" => $endpoint1[0],
            );
            }
    /*     }   else {
            $user_arr   =  array(
                "status" => false,
                "message" => "Token not found!",
                "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                "end point" => $endpoint1[0],
                //"arguments" => $args,
            );
        }*/
    } else {
        $user_arr = array(
                    "status" => false,
                    "message" => "Invalid access Method!!",
                    "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                    "end point" => $endpoint1[0],
                );
    }
} elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user->data = json_decode(file_get_contents('php://input'),true);
    if($validate_token){
            if($vendor_detail = $user->cart()){ 
            $user_arr = array(
            "status" => true,
            "message" => "Updated cart!",
            "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
            "end point" => $endpoint1[0],
            "body" => $vendor_detail,
            );
            }
            else {
            $user_arr = array(
            "status" => false,
            "message" => "Failed to update cart!",
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
} elseif ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    $user->data = json_decode(file_get_contents('php://input'),true);
    if($validate_token){
            if($vendor_detail = $user->cart()){ 
            $user_arr = array(
            "status" => true,
            "message" => "Product deleted from cart!",
            "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
            "end point" => $endpoint1[0],
            "body" => $vendor_detail,
            );
            }
            else {
            $user_arr = array(
            "status" => false,
            "message" => "Failed to delete product from cart!",
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
                    "message" => "Invalid access Method!!",
                    "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                    "end point" => $endpoint1[0],
                );
    }
print_r(json_encode($user_arr));

?>