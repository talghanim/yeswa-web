<?php

header("Content-Type: application/json; charset=UTF-8");
header("Authorization:Bearer");
header("Access-Control-Allow-Methods: GET");
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
    // get database connection 
    $database = new Database();
    $db = $database->getConnection();
    $color1=array();
    // prepare user object
    $user = new User($db);
    $validate = new Validate_token($db);
    $token = $user->getBearerToken();
    //$validate_token = $validate->validate_token($token);
    if(!empty($token)){
        $validate_token = $validate->validate_token($token);
    } else {
        $validate_token = 1;
    }
    
    if($validate_token){
        $user->uid = isset($_GET['uid']) ? $_GET['uid'] : '' ;
        $user->minprice = (isset($_GET['minprice']) && !empty($_GET['minprice'])) ? $_GET['minprice'] : '' ;
        $user->maxprice = (isset($_GET['maxprice']) && !empty($_GET['maxprice'])) ? $_GET['maxprice'] : '' ;
        $user->color = (isset($_GET['color']) && !empty($_GET['color'])) ? $_GET['color'] : array() ;
        $user->size = (isset($_GET['size']) && !empty($_GET['size'])) ? $_GET['size'] : array() ;
        $user->brand = (isset($_GET['brand']) && !empty($_GET['brand'])) ? $_GET['brand'] : array() ;
        $user->popularity = (isset($_GET['popularity']) && !empty($_GET['popularity'])) ? $_GET['popularity'] : 'none' ;   

        $productdetailfetch=array();

        $row=$user->productfilter();

        foreach ($row as $key => $value) {
            $product = $user->getallproduct($value);

            if(!empty($product)){
                $productdetailfetch[] = $product;
            }
        }

        $productdetailfetch1 = array();
        foreach ($productdetailfetch as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $productdetailfetch1[] = $value1;
            }
        }
        
        if(!empty($productdetailfetch)) {
            $user_arr=array(
                "status" => true,
                "message" => "Item found!",
                "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                "end point" => $endpoint1[0],
                "body" => $productdetailfetch1,
            );
        } else {
            $user_arr=array(
                "status" => false,
                "message" => "Item not found!",
                "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                "end point" => $endpoint1[0],
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