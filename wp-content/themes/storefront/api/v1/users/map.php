<?php
header("Authorization:Bearer");
header("Content-Type: application/json; charset=UTF-8");
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
     
    // prepare user object
    $user = new User($db);

    $validate = new Validate_token($db);
    $token = $user->getBearerToken();
    $validate_token = $validate->validate_token($token);

    if($validate_token){
    $user->lat = isset($_GET['lat']) ? $_GET['lat'] : die();
    $user->long = isset($_GET['long']) ? $_GET['long'] : die();
    if(!empty($user->lat) && !empty($user->long)){
    	$user->radius = 1;
    } else {
    	$user->radius = 0;
    }
    if($result = $user->map()){

        // create array
        $user_arr=array(
            "status" => true,
            "message" => "Locations found!",
            "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
            "end point" => $endpoint1[0],
            "body" => $result,
        );
    }
    else{
        $user_arr=array(
            //"value" => $stmt,
            "status" => false,
            "message" => "Locations not found!",
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