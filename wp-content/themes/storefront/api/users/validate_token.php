<?php
// required headers
//header("Content-Type: application/json; charset=UTF-8");
 
// required to decode jwt


include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;


class Validate_token {
    public function __construct($db){
        $this->conn = $db;
    }

    public function validate_token($value)
    { 	//print_r($value);
        include_once '../config/core.php';
        

        $jwt = $value; //print_r($key);
        if($jwt){
                    // if decode succeed, show user details
                    try {
                        // decode jwt
                        $decoded = JWT::decode($jwt, $key, array('HS256'));
                       /* echo json_encode(array(
                            "message" => "Access granted.",
                            //"data" => $decoded->data,
                        ));*/
                        return true;
                    }
                    // if decode fails, it means jwt is invalid
                    catch (Exception $e){
                        // tell the user access denied  & show error message
                        /*echo json_encode(array(
                            "message" => "Access denied.",
                            "error" => $e->getMessage(),
                        ));*/
                        return false;
                    }
                }
                // show error message if jwt is empty
                else{
                    // tell the user access denied
                    //echo json_encode(array("message" => "Access denied."));
                    return false;
                }   
                

    }
    
}


?>