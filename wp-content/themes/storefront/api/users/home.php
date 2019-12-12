<?php  
header("Content-Type: application/json; charset=UTF-8");
header("Authorization:Bearer");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$url = $_SERVER['REQUEST_URI'];
$requesturl = explode('?', $url);
$endpoint = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
$endpoint1 = explode('.', $endpoint);
//echo site_url();
if($_SERVER['REQUEST_METHOD'] == "GET"){ 
    include_once '../config/database.php';
	include_once 'woocommerce-client.php'; 
    include_once 'validate_token.php'; 	
    include_once '../objects/user.php';
    $database = new Database();
    $db = $database->getConnection();
    $user = new User($db);
    $validate = new Validate_token($db);
    $token = $user->getBearerToken();
    //$validate_token = $validate->validate_token($token);
    if(!empty($token)){
        $validate_token = $validate->validate_token($token);
    } else {
        $validate_token = 1;
    }
    //$data = json_decode(file_get_contents('php://input'),true);
    if($validate_token){ 
    $user->offers = isset($_GET['offers']) ? $_GET['offers'] : die();
    $user->category = isset($_GET['category']) ? $_GET['category'] : die();
    $user->newarrival = isset($_GET['newarrival']) ? $_GET['newarrival'] : die();
    $user->category_name = isset($_GET['category_name']) ? $_GET['category_name'] : die();
    $user->brand = isset($_GET['brand']) ? $_GET['brand'] : die();
    $user->offer_id = isset($_GET['offer_id']) ? $_GET['offer_id'] : die();
    $user->uid = isset($_GET['uid']) ? $_GET['uid'] : die();
    $user->woocommerce = $woocommerce;
        if($result = $user->home()) {
            $user_arr   =  array(
                "status" => true,
                "message" => "Items found!",
                "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                "end point" => $endpoint1[0],
                //"arguments" => $args,
                "body" => $result,
            );
        }
        else {
            $user_arr   =  array(
                "status" => false,
                "message" => "Items not found!",
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
    $user_arr=array(
                    "status" => false,
                    "message" => "Undefined access method!!",
                    "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                    "end point" => $endpoint1[0],
                );
}
print_r(json_encode($user_arr));
?>