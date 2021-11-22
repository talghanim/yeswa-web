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
     
    // prepare user object
    $user = new User($db);

    $validate = new Validate_token($db);
    $token = $user->getBearerToken();
    //$validate_token = $validate->validate_token($token);
    if(!empty($token)){
        $validate_token = $validate->validate_token($token);
    } else {
        $validate_token = 1;
    } //$data = json_decode(file_get_contents('php://input'),true);
    // set ID property of user to be edited
    if($validate_token){
        $user->uid = isset($_GET['uid']) ? $_GET['uid'] : die();
        $user->productid = isset($_GET['productid']) ? $_GET['productid'] : die();
        $user->display_full = isset($_GET['display_full']) ? $_GET['display_full'] : die();
        $user->cart = $user->cart();
           if($results = $user->product()) {
            //$data = $results->fetchAll(PDO::FETCH_ASSOC);
            //print_r($results);
            $user_arr=array(
                "status" => true,
                "message" => "Item found!",
                "request url" => 'BaseUrl/'.$requesturl[1],
                "end point" => $endpoint1[0],
                "body" => $results,
            );
        }
        else {
            $user_arr=array(
                "status" => false,
                "message" => "Item not found!",
                "request url" => 'BaseUrl/'. $endpoint.$requesturl[1],
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
