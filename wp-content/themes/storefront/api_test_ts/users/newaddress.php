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
if($_SERVER['REQUEST_METHOD'] == "POST") {
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
            $user->a_title = $data[a_title];
            $user->a_apartment = $data[a_apartment];
            $user->a_floor = $data[a_floor];
            $user->a_street = $data[a_street];
            $user->a_area = $data[a_area];
            $user->a_zip_code = $data[a_zip_code];
            $user->a_gender = $data[a_gender];
            $user->a_paci_number = $data[a_paci_number];
            $user->avenue  = $data[a_avenue];
            $user->a_status = $data[a_status];

            if($user->verify_token($user->uid,$vendor_id,$token)){
                $msg=$user->newaddress();

                if($msg==false){

                    // create array
                    $user_arr=array(
                        "status" => false,
                        "message" => "User User ID Not Found.. !",
                        "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                        "end point" => $endpoint1[0],
                        //"id" => $row['id'],
                        //"username" => $row['username']
                    );
                }
                else {
                    $user_arr=array(
                        //"value" => $stmt,
                        "status" => true,
                        "message" => $msg,
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

// make it json format
print_r(json_encode($user_arr));
?>