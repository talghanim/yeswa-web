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
if($_SERVER['REQUEST_METHOD'] == "POST"){ 
    include_once '../config/database.php'; 
    include_once '../objects/user.php';  
    include_once 'validate_token.php';  
    //header('Content-Type: application/json');
    // get database connection
    $database = new Database();
    $db = $database->getConnection();
     
    // prepare user object
    $user = new User($db);
    $validate = new Validate_token($db);
    $token = $user->getBearerToken();
    $validate_token = $validate->validate_token($token);
    $data = json_decode(file_get_contents('php://input'),true);
    //print_r($data);
            $url = $_SERVER['REQUEST_URI'];
            $requesturl = explode('?', $url);
            $requesturl1 = explode('.',$requesturl[0]); 
            $endpoint = (substr($requesturl1[0],strripos($requesturl1[0],'/') + 1)) . "." . $requesturl1[1];
            //print_r($requesturl1);

            //$endpoint = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
            $endpoint1 = explode('.', $endpoint);  
            // "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            //$product = wc_get_product( 825 );
            
                
    if($validate_token){
            $log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
            "User: ".print_r($_POST,true).PHP_EOL.
            "FILES: ".print_r($_FILES,true).PHP_EOL.
            "-------------------------".PHP_EOL;
            //Save string to log, use FILE_APPEND to append.
            file_put_contents(dirname(__FILE__).'/log/'.date("yeswa_j.n.Y").'.log', $log, FILE_APPEND);
            //  $user->u_id = $data[u_id];
                $user->vid = $_POST[vid]; 
                for ($i=1; $i < 100 ; $i++) { 
                    if(!empty($_FILES[image.$i])){
                            $user->image[$i]=$_FILES[image.$i];
                    }
                }
                $user->p_id = $_POST[p_id];
                $user->p_name = $_POST[p_name];
                $user->p_desc = $_POST[p_desc];
                $user->quantity = $_POST[quantity];
                $user->regular_price = $_POST[regular_price];
                $user->sale_price = $_POST[sale_price];
                $user->pa_brand = explode(",", str_replace('"'," ", str_replace("]"," ", str_replace("["," ",str_replace("\\","",$_POST[pa_brand]) ))));
                $user->pa_color = explode(",", str_replace('"'," ", str_replace("]"," ", str_replace("["," ",str_replace("\\","",$_POST[pa_color]) ))));
                $user->pa_size = explode(",", str_replace('"'," ", str_replace("]"," ", str_replace("["," ",str_replace("\\","",$_POST[pa_size]) ))));
                $user->sku = $_POST[sku];
                $user->categories = explode(",", str_replace('"'," ", str_replace("]"," ", str_replace("["," ",str_replace("\\","",$_POST[categories]) )))); 
                
                if($user->verify_token($user->uid,$user->vid,$token)){
                    if($result=$user->update_prod()) {
                    $user_arr=array(
                        "status" => true,
                        "message" => "Product Updated Successfully!",
                        "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                        "end point" => $endpoint1[0],
                        "body"=>$result,
                    );
                }
                else {
                    $user_arr=array(
                        "status" => false,
                        "message" => "Failed to update product!",
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
