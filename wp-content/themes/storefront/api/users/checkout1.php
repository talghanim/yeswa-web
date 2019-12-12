<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
 //static $userid=1;
// get database connection
$database = new Database();
$db = $database->getConnection();
// prepare user object
$user = new User($db);
$data = json_decode(file_get_contents('php://input'),true);
// set ID property of user to be edited 
$user->fid = $data[fid];  
//$user->productid = isset($_GET['productid']) ? $_GET['productid'] : die();
//$user->quantity = isset($_GET['quantity']) ? $_GET['quantity'] : die();
//$user->price = isset($_GET['price']) ? $_GET['price'] : die();
//$user->orderitemname = isset($_GET['orderitemname']) ? $_GET['orderitemname'] : die();
//$user->orderitemtype = isset($_GET['orderitemtype']) ? $_GET['orderitemtype'] : die();
//$user->shippingmethod = isset($_GET['shippingmethod']) ? $_GET['shippingmethod'] : die();
//$user->orderitemdetails = isset($_GET['orderitemdetails']) ? $_GET['orderitemdetails'] : die();
$user->line_meta = $data[product_meta]; 
$user->coupon = $data[coupon];
//print_r($user->line_meta);
/*
$user->billing_first_name = isset($_GET['billing_first_name']) ? $_GET['billing_first_name'] : die();
$user->billing_last_name = isset($_GET['billing_last_name']) ? $_GET['billing_last_name'] : die();
$user->billing_company = isset($_GET['billing_company']) ? $_GET['billing_company'] : die();
$user->billing_address_1 = isset($_GET['billing_address_1']) ? $_GET['billing_address_1'] : die();
$user->billing_address_2 = isset($_GET['billing_address_2']) ? $_GET['billing_address_2'] : die();
$user->billing_city = isset($_GET['billing_city']) ? $_GET['billing_city'] : die();
$user->billing_postcode = isset($_GET['billing_postcode']) ? $_GET['billing_postcode'] : die();
$user->billing_country = isset($_GET['billing_country']) ? $_GET['billing_country'] : die();
$user->billing_state = isset($_GET['billing_state']) ? $_GET['billing_state'] : die();
$user->billing_phone = isset($_GET['billing_phone']) ? $_GET['billing_phone'] : die();
$user->billing_email = isset($_GET['billing_email']) ? $_GET['billing_email'] : die();*/
$user->shipping_first_name = $data[shipping_first_name];
$user->shipping_last_name = $data[shipping_last_name];
$user->shipping_company = $data[shipping_company];
$user->shipping_address_1 = $data[shipping_address_1];
$user->shipping_address_2 = $data[shipping_address_2];
$user->shipping_city = $data[shipping_city];
$user->shipping_postcode = $data[shipping_postcode];
$user->shipping_country = $data[shipping_country]; 
$user->shipping_state = $data[shipping_state];

$url = $_SERVER['REQUEST_URI'];
$requesturl = explode('?', $url);
$endpoint = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
$endpoint1 = explode('.', $endpoint);
$args = array(
                'offers' => array(
                    'type' => 'integer',
                    'description' => 'Offer',
                ),
                'category' => array(
                    'type' => 'integer',
                    'description' => '',
                ),
                'newarrival' => array(
                    'type' => 'integer',
                    'description' => '',
                ),
                'category_name' => array(
                    'type' => 'string',
                    'description' => 'Name of the category',
                ),
        );
   if($result = $user->checkout()) {
    // get retrieved row
    //$row = $stmt->fetch(PDO::FETCH_ASSOC);
    // create array
    //if($user->editprofile()) {
       // $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $user_arr=array(
        "status" => true,
        "message" => "Order placed!",
        "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
        "end point" => $endpoint1[0],
        //"arguments" => $args,
        "body"  => $result,
        //"id" => $row['id'],
        //"username" => $row['username']
    );
}
else {
    $user_arr=array(
        "status" => false,
        "message" => "Failed to place an order!",
        "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
        "end point" => $endpoint1[0],
        //"arguments" => $args,
    );
}

// make it json format
print_r(json_encode($user_arr));
?>
