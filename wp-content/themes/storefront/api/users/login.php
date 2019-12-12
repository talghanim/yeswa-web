<?php 
//header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// database connection will be here
// files needed to connect to database
  include_once '../config/core.php';
  include_once '../libs/php-jwt-master/src/BeforeValidException.php';
  include_once '../libs/php-jwt-master/src/ExpiredException.php';
  include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
  include_once '../libs/php-jwt-master/src/JWT.php';
  include_once '../config/database.php';
  include_once '../objects/user.php';

  //include_once 'add_token.php';

  use \Firebase\JWT\JWT;
$url = $_SERVER['REQUEST_URI'];
$requesturl = explode('?', $url);
$endpoint = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
$endpoint1 = explode('.', $endpoint); //print_r($_SERVER['REQUEST_METHOD']); 
if($_SERVER['REQUEST_METHOD'] == "POST"){ //echo "eEWWER"; }
  
  // get database connection
  $database = new Database();
  $db = $database->getConnection();
  $user = new User($db);
  $token = $user->getBearerToken();
  if(empty($token)){
  $data = json_decode(file_get_contents('php://input'),true);
  $log  = print_r($data, true);
  file_put_contents(dirname(__FILE__).'/log/login_'.date("Y_m_d").'.log', $log, FILE_APPEND);
  // set product property values
  $user->useremail = $data[email];
  $user->userpassword = $data[password];
  $user->vendor = $data[vendor];
  $user->device_id = $data[device_id];
  $user->device_type = $data[device_type];
  $email_exists = $user->emailExists(); //print_r($email_exists);
  if($email_exists){    
      //print_r($email_exists);
      $token = array(
         "iss" => $iss,
         "aud" => $aud,
         "iat" => $iat,
         "nbf" => $nbf,
		 "exp" => $exp,
         "data" => array (
             //"id" => $email_exists[ID],
             //"userlogin" => $email_exists[user_login],
             "useremail" => $user->useremail,
             "userpass" => $user->userpassword,
         )
      );
      
      $jwt = JWT::encode($token, $key);
      $user->add_token($jwt,$email_exists);
      $user_arr   =  array(
                  "message" => "Successful login.",
                  "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                  "end point" => $endpoint1[0],
                    //"id" => $email_exists[ID],
                    //"userlogin" => $email_exists[user_login],
                  "body" => $email_exists,
                  "token" => $jwt
        );
  }
  else {
    $user_arr   =  array( "message" => "Login failed.",
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