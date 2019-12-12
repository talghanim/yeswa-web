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

    $database = new Database();
    $db = $database->getConnection();
    // prepare user object
    $user = new User($db);
    $validate = new Validate_token($db);
    $token = $user->getBearerToken();
    $validate_token = $validate->validate_token($token);
    $data = json_decode(file_get_contents('php://input'),true);


    $urls = $_SERVER['REQUEST_URI'];
    $requesturl = explode('?', $urls);
    $endpoint = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
    $endpoint1 = explode('.', $endpoint);



    if($validate_token){
                // set ID property of user to be edited
                $user->fid = $data[fid];

                $user->orderitemname = $data[orderitemname];
                $user->orderitemtype = $data[orderitemtype];

                $user->line_meta = $data[line_meta];
                $user->shipping = $data[shipping];
                $user->coupon = $data[coupon];

                $user->payment = $data[payment];

                $user->billing_first_name = $data[billing_first_name];
                $user->billing_last_name = $data[billing_last_name];
                $user->billing_company = $data[billing_company];
                $user->billing_address_1 = $data[billing_address_1];
                $user->billing_address_2 = $data[billing_address_2];
                $user->billing_city = $data[billing_city];
                $user->billing_postcode = $data[billing_postcode];
                $user->billing_country = $data[billing_country];
                $user->billing_state = $data[billing_state];
                $user->billing_phone = $data[billing_phone];
                $user->billing_email = $data[billing_email];
                $user->shipping_first_name = $data[shipping_first_name];
                $user->shipping_last_name = $data[shipping_last_name];
                $user->shipping_company = $data[shipping_company];
                $user->shipping_address_1 = $data[shipping_address_1];
                $user->shipping_address_2 = $data[shipping_address_2];
                $user->shipping_city = $data[shipping_city];
                $user->shipping_postcode = $data[shipping_postcode];
                $user->shipping_country = $data[shipping_country];
                $user->shipping_state = $data[shipping_state];

                $user->_order_currency = $data[order_currency];
                $user->_cart_discount = $data[cart_discount];
                $user->_cart_discount_tax = $data[cart_discount_tax];
                $user->_order_shipping = $data[order_shipping];
                $user->_order_shipping_tax = $data[order_shipping_tax];
                $user->_order_tax = $data[order_tax];
                $user->_order_total = $data[order_total];
                $user->_prices_include_tax = $data[prices_include_tax];              

                $user->date = date('Y-m-d H:i:s');
                $user->date_gmt = gmdate('Y-m-d H:i:s');        
                $user->post_title ="Order &ndash;" . date('F d,Y @ h:i A'); 
                $user->post_status = "wc-on-hold";
                $user->ping_status = "closed";
                $user->post_password = "order_"."post_password"; 
                $user->post_name = "order-" . gmdate('M-d-Y-hi-a'); 
                $user->to_ping = " ";
                $user->pinged = " ";
                $user->post_modified = date('Y-m-d H:i:s');
                $user->post_modified_gmt = gmdate('Y-m-d H:i:s');
                $user->post_content_filtered = " ";
                $user->post_type = "shop_order";
                $user->comment_count = 1;
                $query = "SELECT ID FROM ya_posts ORDER BY ID DESC LIMIT 1";
                $stmt = $database->conn->prepare($query);
                $stmt->execute();
                $user->guid_url_id1 = $stmt->fetch(PDO::FETCH_ASSOC);
                $user->guid_url_id = $user->guid_url_id1['ID'] + 1;
                //print_r($user->guid_url_id);   
                $user->url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";   
                $user->guid = "".site_url()."/?post_type=".$user->post_type."&#038;p=".$user->guid_url_id.""; //http://localhost/yeswa/?post_type=shop_order&#038;p=141
                function getRealIpAddr()
                {
                    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
                    {
                      $ip=$_SERVER['HTTP_CLIENT_IP'];
                    }
                    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
                    {
                      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
                    }
                    else
                    {
                      $ip=$_SERVER['REMOTE_ADDR'];
                    }
                    return $ip;
                }
                $user->_customer_ip_address = getRealIpAddr(); //$_SERVER[REMOTE_ADDR];
                $user->_customer_user_agent = $_SERVER[HTTP_USER_AGENT]; //Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.81 Safari/537.36
                $user->_created_via = 'checkout';   // checkout
                $user->_date_completed = '';
                $user->_completed_date = '';
                $user->_date_paid = '';
                /*if($user->payment[paid] == 1){
                    $user->_paid_date = date('Y-m-d H:i:s');
                } else {
                   $user->_paid_date = '';
                }*/
                $user->_paid_date = '';
                $user->_cart_hash = '0670457b626dbd8c71b7828d93c8e203';   

                $user->_order_version = "3.5.2";
                $user->_recorded_sales = "yes";                  
                $user->_recorded_coupon_usage_counts = "yes";    
                $user->_order_stock_reduced = "yes";            
                $user->_edit_lock = "1550493905:2";

                if($user->placeorder()) {
                    $user_arr=array(
                        "status" => true,
                        "message" => "Order placed !",
                        "request url" => 'BaseUrl/'. $endpoint.$requesturl[1],
                        "end point" => $endpoint1[0],
                    );
                }
                else {
                    $user_arr=array(
                        "status" => false,
                        "message" => "Failed to place an order!",
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
// make it json format
print_r(json_encode($user_arr));
?>