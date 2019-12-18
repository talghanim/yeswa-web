<?php  
header("Content-Type: application/json; charset=UTF-8");
header("Authorization:Bearer");
header("Access-Control-Allow-Methods: GET,POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$url = $_SERVER['REQUEST_URI'];
$requesturl = explode('?', $url);
$endpoint = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
$endpoint1 = explode('.', $endpoint);
//echo site_url();
 
    include_once '../config/database.php';
    include_once '../objects/user.php';
    include_once 'validate_token.php';
    include_once 'woocommerce-client.php'; 

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
    if($_SERVER['REQUEST_METHOD'] == "GET"){
    $user->uid = isset($_GET['uid']) ? $_GET['uid'] : die(); 

    if($user->verify_token($user->uid,$vendor_id,$token)){
        if($result = $user->checkout()) {
            $user_arr   =  array(
                "status" => true,
                "message" => "User details found!",
                "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                "end point" => $endpoint1[0],
                //"arguments" => $args,
                "body" => $result,
            );
        }
        else {
            $user_arr   =  array(
                "status" => false,
                "message" => "User details not found!",
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
    } elseif($_SERVER['REQUEST_METHOD'] == "POST"){
        $data = json_decode(file_get_contents('php://input'),true);    
        $user->uid = $data[uid];
        $user->fullname = $data[fullname];  
        $user->area = $data[area];
        $user->house_no = $data[house_no];
        $user->block = $data[block];
        $user->street = $data[street];
        $user->paci_no = $data[paci_no];
        $user->avenue = $data[avenue];
        $user->zip_code = $data[zip_code];


            if($result = $user->checkout()) {
                $user_arr   =  array(
                    "status" => true,
                    "message" => "User details found!",
                    "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                    "end point" => $endpoint1[0],
                    //"arguments" => $args,
                    "body" => $result,
                );
            }
            else {
                $user_arr   =  array(
                    "status" => false,
                    "message" => "User details not found!",
                    "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                    "end point" => $endpoint1[0],
                    //"arguments" => $args,
                );
            }
    }
    else {
        $user_arr=array(
                    "status" => false,
                    "message" => "Undefined access method!!",
                    "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                    "end point" => $endpoint1[0],
                );
    }
} else {
    $user_arr=array(
                    "status" => false,
                    "message" => "User not found",
                    "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                    "end point" => $endpoint1[0],
                );
}
print_r(json_encode($user_arr));
?>