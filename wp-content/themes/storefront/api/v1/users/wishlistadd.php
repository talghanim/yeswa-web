<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// include database and object files
include_once '../config/database.php';
include_once '../objects/user.php';
include_once 'validate_token.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare user object
$user = new User($db);
$data = json_decode(file_get_contents('php://input'),true);

$url = $_SERVER['REQUEST_URI'];
$requesturl = explode('?', $url);
$endpoint = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
$endpoint1 = explode('.', $endpoint);

// set ID property of user to be edited
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $validate = new Validate_token($db);
    $token = $user->getBearerToken();
    $validate_token = $validate->validate_token($token);
    //$data = json_decode(file_get_contents('php://input'),true);
    if($validate_token){
        $user->fid = $data[uid];
        $user->productid = $data[productid];
        $user->quantity = $data[quantity];
        $user->price = $data[price];
        $user->in_stock = $data[in_stock];
        $user->variationid = $data[variationid];
        $user->date = date('Y-m-d H:i:s');

           if($stmt=$user->addwishlist()) {  
            // get retrieved row
            //$row = $stmt->fetch(PDO::FETCH_ASSOC);
            // create array
            //if($user->editprofile()) {
               // $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $user_arr=array(
                //"value" => $row['wishlist_id'],
                "status" => true,
                "message" => $stmt,
                "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                "end point" => $endpoint1[0],
                //"id" => $row['id'],
                //"username" => $row['username']
            );
        }
        else {
            $user_arr=array(
                "status" => false,
                "message" => "Failed to add the item to wishlist!",
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
