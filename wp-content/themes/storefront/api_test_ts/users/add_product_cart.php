<?php  
header("Content-Type: application/json; charset=UTF-8");
header("Authorization:Bearer");
header("Access-Control-Allow-Methods: POST");
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
    
    $validate = new Validate_token($db);
    $token = $user->getBearerToken();
    $validate_token = $validate->validate_token($token);

if($_SERVER['REQUEST_METHOD'] == "POST"){ 
    $data = json_decode(file_get_contents('php://input'),true);
                $user->uid = $data[uid];
                $user->product_id = $data[product_id]; 
                $user->quantity = $data[quantity]; 
                $user->variation_id = $data[variation_id];
                $user->variation = $data[variation];
                $user->cart_item_data = $data[cart_item_data];

    if($validate_token){
            if($user->add_product_cart()){ 
            $user_arr = array(
            "status" => true,
            "message" => "Sucessfully added product to cart!",
            "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
            "end point" => $endpoint1[0],
            "body" => $product_cart,
            );
            }
            else {
            $user_arr = array(
            "status" => false,
            "message" => "Error in adding product to cart!!",
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
}  else {
        $user_arr = array(
                    "status" => false,
                    "message" => "Invalid access Method!!",
                    "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                    "end point" => $endpoint1[0],
                );
    }
    
print_r(json_encode($user_arr));

?>