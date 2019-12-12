<?php
header("Authorization:Bearer");
header("Content-Type: application/json; charset=UTF-8");
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
    // get database connection
    $database = new Database();
    $db = $database->getConnection();
     
    // prepare user object
    $user = new User($db);

    $validate = new Validate_token($db);
    $token = $user->getBearerToken();
    $validate_token = $validate->validate_token($token);

    if($validate_token){
    $data = json_decode(file_get_contents('php://input'),true);

    // set ID property of user to be edited

    $user->uid = $data[uid];
    $user->billingcountry = $data[billingcountry];
    $user->language = $data[language];
    if($user->verify_token($user->uid,$vendor_id,$token)) {
        if($user->settingscreen()){

            // create array
            $user_arr=array(
                "status" => true,
                "message" => "Settings updated successfully!",
                "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                "end point" => $endpoint1[0],
                //"id" => $row['id'],
                //"username" => $row['username']
            );
        }
        else{
            $user_arr=array(
                //"value" => $stmt,
                "status" => false,
                "message" => "User ID Not Found.. !",
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
print_r(json_encode($user_arr));
?>