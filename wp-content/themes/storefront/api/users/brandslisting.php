<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Authorization:Bearer");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// include database and object files
$url = $_SERVER['REQUEST_URI'];
$requesturl = explode('?', $url);
$endpoint = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
$endpoint1 = explode('.', $endpoint);
if($_SERVER['REQUEST_METHOD'] == "GET"){
    include_once '../config/database.php';
    include_once '../objects/user.php';
    include_once 'validate_token.php';
    //include_once 'woocommerce-client.php';
     //static $userid=1;
    // get database connection
    $database = new Database();
    $db = $database->getConnection();
     
    // prepare user object
    $user = new User($db);
    $validate = new Validate_token($db);
    $token = $user->getBearerToken();
    //$validate_token = $validate->validate_token($token);
    if(!empty($token)){
        $validate_token = $validate->validate_token($token);
    }else{
         $validate_token = 1;
    }
    if($validate_token){
            $user->brand_name = isset($_GET['brand_name']) ? $_GET['brand_name'] : die();
            //$user->woocommerce = $woocommerce;
               if($res_categories = $user->brandslisting()) {
                $user_arr=array(
                    "status" => true,
                    "message" => "Items found!",
                    "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                    "end point" => $endpoint1[0],
                    //"arguments" => $args,
                    "body" => $res_categories,
                );
            }
            else {
                $user_arr=array(
                    "status" => false,
                    "message" => "Items not found!",
                    "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                    "end point" => $endpoint1[0],
                    //"arguments" => $args,
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
    
} else{
    $user_arr=array(
                    "status" => false,
                    "message" => "Undefined access method!!",
                    "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                    "end point" => $endpoint1[0],
                );
}
// make it json format
print_r(json_encode($user_arr));
?>