<?php
header("Content-Type: application/json; charset=UTF-8");
header("Authorization:Bearer");
header("Access-Control-Allow-Methods: GET,POST");
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
     //static $userid=1;
    // get database connection
    $database = new Database();
    $db = $database->getConnection();
     
    // prepare user object
    $user = new User($db);
    $validate = new Validate_token($db);
    $token = $user->getBearerToken();
    $validate_token = $validate->validate_token($token);
    // set ID property of user to be edited
    if($validate_token){  
        $log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
            "User: ".print_r($_POST,true).PHP_EOL.
            "FILES: ".print_r($_FILES,true).PHP_EOL.
            "-------------------------".PHP_EOL;
            //Save string to log, use FILE_APPEND to append.
            file_put_contents(dirname(__FILE__).'/log/'.date("yeswa_j.n.Y").'.log', $log, FILE_APPEND);
        $data = json_decode(file_get_contents('php://input'),true);
        $user->fid = $_POST[uid];
        $user->fusername = $_POST[fusername];
        $user->fuseremail = $_POST[fuseremail];
        $user->fuserbio = $_POST[fuserbio]; 
        $user->fuserphone = $_POST[fuserphone]; 
        $user->fuserbirthdate = $_POST[fuserbirthdate];
        $user->fusercivil = $_POST[fusercivil];
        $user->fusergender = $_POST[fusergender];
        for ($i=1; $i < 100 ; $i++) { 
            if(!empty($_FILES[image.$i])){ 
                    $user->image[$i]=$_FILES[image.$i]; 
					$i++;
            }
        } //print_r($user->image); die;
        if($user->verify_token($user->fid,$vendor_id,$token)){
			$result = $user->editprofile();
               if($result['status'] == 'true') {
                $user_arr=array(
                    "status" => true,
                    "message" => "Update Successful !",
                    "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                    "end point" => $endpoint1[0],
                    //"id" => $row['id'],
                    //"username" => $row['username']
                );
            }
            else {
                $user_arr=array(
                    "status" => false,
                    "message" => $result['msg'],
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
} elseif ($_SERVER['REQUEST_METHOD'] == "GET") {
        include_once '../config/database.php';
        include_once '../objects/user.php';
        include_once 'validate_token.php';
         //static $userid=1;
        // get database connection
        $database = new Database();
        $db = $database->getConnection();
         
        // prepare user object
        $user = new User($db);
        $validate = new Validate_token($db);
        $token = $user->getBearerToken();
        $validate_token = $validate->validate_token($token);
        // set ID property of user to be edited
        if($validate_token){
            $user->fid = isset($_GET['uid']) ? $_GET['uid'] : die();
            if($user->verify_token($user->fid,$vendor_id,$token)){
                $result = $user->editprofile();
                   if($result) {
                    $user_arr=array(
                        "status" => true,
                        "message" => "User deatils found !",
                        "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                        "end point" => $endpoint1[0],
                        "body"      => $result,
                    );
                }
                else {
                    $user_arr=array(
                        "status" => false,
                        "message" => "User deatils not found!!",
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
    } 

else{
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
