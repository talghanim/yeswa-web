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

            $url = $_SERVER['REQUEST_URI'];
            $requesturl = explode('?', $url);
            $requesturl1 = explode('.',$requesturl[0]); 
            $endpoint = (substr($requesturl1[0],strripos($requesturl1[0],'/') + 1)) . "." . $requesturl1[1];
            //print_r($requesturl1);

            //$endpoint = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
            $endpoint1 = explode('.', $endpoint);  
            // "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            
    if($validate_token){
            //$uploads = wp_upload_dir();  print_r($uploads);
            // set ID property of user to be edited
            //$user->u_id = $data[u_id];
            $user->vid = $data[vid];
            $user->image = $data[image];
            $user->p_name = $data[p_name];
            $user->p_desc = $data[p_desc];
            $user->regular_price = $data[regular_price];
            $user->sale_price = $data[sale_price];
            $user->price = $data[price];
            $user->category = $data[category];
            $user->brand = $data[brand];
            $user->color = $data[color];
            $user->size = $data[size];
            $user->sku = $data[sku];
            $user->product_type = $data[product_type];
            $user->outofstock = $data[outofstock];
            $user->site_url = "".site_url()."/wp-content/uploads/2019/02/";

            if($user->vender_product_detail_add()) {
                $user_arr=array(
                    "status" => true,
                    "message" => "Items Inserted Success!",
                    "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                    "end point" => $endpoint1[0],
                );
            }
            else {
                $user_arr=array(
                    "status" => false,
                    "message" => "Failed to insert an item!",
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
// make it json format
print_r(json_encode($user_arr));
?>
