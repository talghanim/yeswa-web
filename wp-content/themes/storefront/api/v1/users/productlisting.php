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
    }
    // set ID property of user to be edited
    
    if($validate_token){
        $user->uid = isset($_GET['uid']) ? $_GET['uid'] : die();
        $user->find = isset($_GET['find']) ? $_GET['find'] : die();
		$user->page = isset($_GET['page']) ? $_GET['page'] : die();
		$result = $user->productlisting();
           if(!empty($result)) {
            $user_arr=array(
                "status" => true,
                "message" => "Item found!",
                "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                "end point" => $endpoint1[0],
                "body" => $result['data'],
                "total_record" => $result['total_record'],
                "total_pages" => $result['total_pages'],
            );
        } else {
            $user_arr=array(
                "status" => false,
                "message" => "Item not found!",
                "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                "end point" => $endpoint1[0],
				"body" => $result,
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
