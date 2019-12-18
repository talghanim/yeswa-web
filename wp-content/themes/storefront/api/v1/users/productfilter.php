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
    $color1=array();
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
    
    if($validate_token){
        $user->uid = isset($_GET['uid']) ? $_GET['uid'] : '' ;
        $user->minprice = isset($_GET['minprice']) ? $_GET['minprice'] : 0 ;
        $user->maxprice = isset($_GET['maxprice']) ? $_GET['maxprice'] : 10000000 ;

        $size = isset($_GET['size']) ? $_GET['size'] : array() ;
        $size = str_replace('[', '', $size);
        $size = str_replace(']', '', $size);
        $data['size'] = explode(',', $size);

        $color = isset($_GET['color']) ? $_GET['color'] : array() ;
        $color = str_replace('[', '', $color);
        $color = str_replace(']', '', $color);
        $data['color'] = explode(',', $color);

        $brand = isset($_GET['brand']) ? $_GET['brand'] : array() ;
        $brand = str_replace('[', '', $brand);
        $brand = str_replace(']', '', $brand);
        $data['brand'] = explode(',', $brand);

        $user->popularity = isset($_GET['popularity']) ? $_GET['popularity'] : 'none' ;
        
        if(!empty($user->minprice )){
          $user->minprice = $user->minprice ;  
        } else {
            $user->minprice = 0;
        }
        if(!empty($user->maxprice)){
          $user->maxprice = $user->maxprice;  
        } else {
            $user->maxprice = 100000;
        }  

        if(empty($data['color'][0])){ 
            $query ="SELECT term_id FROM ya_term_taxonomy WHERE taxonomy='pa_color'"; //
                $stmt = $database->conn->prepare($query);
                $stmt->execute();
                
                        $user->color1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
        }else{
            $user->color = $data['color'];  
        }
        if(empty($data['size'][0])){ 
            $query ="SELECT term_id FROM ya_term_taxonomy WHERE taxonomy='pa_size'"; 
            $stmt = $database->conn->prepare($query);
            $stmt->execute();
            $user->size1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }else{
            $user->size = $data['size'] ;
        }
        if(empty($data['brand'][0])){
            $query ="SELECT term_id FROM ya_term_taxonomy WHERE taxonomy='pa_brand'"; 
            $stmt = $database->conn->prepare($query);
            $stmt->execute();
            $user->brand1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            
        }else{
            $user->brand = $data['brand'];
        }   

        $productdetailfetch=array();
        $row=$user->productfilter();

        foreach ($row as $key => $value) {
        $product = $user->getallproduct($value);
        if(!empty($product))
            $productdetailfetch[] = $product;
        }
        $productdetailfetch1 = array();
        foreach ($productdetailfetch as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $productdetailfetch1[] = $value1;
            }
        }
        
        if(!empty($productdetailfetch)) {
        $user_arr=array(
        "status" => true,
        "message" => "Item found!",
        "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
        "end point" => $endpoint1[0],
        "body" => $productdetailfetch1,
        );
        }
        else {
        $user_arr=array(
        "status" => false,
        "message" => "Item not found!",
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