<?php
header("Content-Type: application/json; charset=UTF-8");
//header("Authorization:Bearer");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// include database and object files
$url = $_SERVER['REQUEST_URI'];
$requesturl = explode('?', $url);
$endpoint = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
$endpoint1 = explode('.', $endpoint);
if($_SERVER['REQUEST_METHOD'] == "PUT"){
        include_once '../config/database.php';
        include_once '../objects/user.php';
        //include_once 'validate_token.php';
         
        // get database connection
        $database = new Database();
        $db = $database->getConnection();
         
        // prepare user object
        $user = new User($db);
        $validate = new Validate_token($db);
        $token = $user->getBearerToken(); 
        $validate_token = $validate->validate_token($token);
        $data = json_decode(file_get_contents('php://input'),true);
	
        if($validate_token){
        //if($validate_token){
            $user->femail = $data['email'];
            $user->password = $data['password'];
            $user->confirm_password = $data['confirm_password'];

            if(strcmp($data['password'],$data['confirm_password']) == 0) {
                if($info = $user->reset_password()){
                    // create array
                    $user_arr=array(
                        "status" => true,
                        "message" => "Reset password has been updated successfully",
                        "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                        "end point" => $endpoint1[0],
                        //"data" => $info,
                        //"username" => $row['username']
                    );
                }else{
                    $user_arr=array(
                        //"value" => $stmt,
                        "status" => false,
                        "message" => "Email Not Found !",
                        "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                        "end point" => $endpoint1[0],
                    );
                }
            } else {
                $user_arr=array(
                    "status" => false,
                    "message" => "Password do not match",
                    "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                    "end point" => $endpoint1[0],
                );
            }
            
    } else {
        $user_arr=array(
                    //"value" => $stmt,
                    "status" => false,
                    "message" => "Undefined authorization!!",
                    "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                    "end point" => $endpoint1[0],
                );
    } 
} else{
    $user_arr=array(
                    "status" => false,
                    "message" => "Undefined access method!!",
                    "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                    "end point" => $endpoint1[0],
                );
}
// make it json format
print_r(json_encode($user_arr));
?>