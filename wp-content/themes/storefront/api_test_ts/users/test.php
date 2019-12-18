<?php 
//header("Content-Type: application/json; charset=UTF-8");
//header("Authorization:Bearer");

header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// include database and object files
$url = $_SERVER['REQUEST_URI'];
$requesturl = explode('?', $url);
$endpoint = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
$endpoint1 = explode('.', $endpoint);

//if($_SERVER['REQUEST_METHOD'] == "GET") {}
include_once '../config/database.php';
include_once 'validate_token.php';

require_once ('../vendor/autoload.php');

require_once('../vendor/automattic/woocommerce/src/WooCommerce/Client.php');
require_once('../vendor/automattic/woocommerce/src/WooCommerce/HttpClient/BasicAuth.php');
require_once('../vendor/automattic/woocommerce/src/WooCommerce/HttpClient/HttpClient.php');
require_once('../vendor/automattic/woocommerce/src/WooCommerce/HttpClient/HttpClientException.php');
require_once('../vendor/automattic/woocommerce/src/WooCommerce/HttpClient/OAuth.php');
require_once('../vendor/automattic/woocommerce/src/WooCommerce/HttpClient/Options.php');
require_once('../vendor/automattic/woocommerce/src/WooCommerce/HttpClient/Request.php');
require_once('../vendor/automattic/woocommerce/src/WooCommerce/HttpClient/Response.php');
//use Automattic\WooCommerce\HttpClient\HttpClientException;
use Automattic\WooCommerce\Client;
//use Automattic\WooCommerce\HttpClient;

$woocommerce = new Client(
    'http://localhost/yeswa', // Your store URL
    'ck_a0577254e53dbbeb66062c3c23d17d48dc578c06', // Your consumer key
    'cs_13763e1432cdf51e28eca10fa321ac56243f69f7', // Your consumer secret
    [
        'wp_api' => true,
        'verify_ssl'=>false,
        'query_string_auth' => true,
        'version' => 'wc/v2',
    ]
);
echo 'test';
/*print_r($_FILES);
if (isset($_FILES['image'])) {
    $filename = $_FILES['image']['tmp_name'];
    list($width, $height) = getimagesize($filename);
    //echo $width; 
	//echo $height;
}
$size=$_FILES[image][size];
echo $size;
$handle = fopen($_FILES["image"]["tmp_name"], 'r');
print_r($handle);
print_r(wp_get_attachment_image(150));
if (copy($_FILES['image']['tmp_name'], 'm3-26.jpg'))
	echo 'true';
else
	echo 'false';
print_r($img);
$file = '/home/sroot/Downloads/p2-225x300.jpg';
$filename = basename($file);
$upload_file = wp_upload_bits($filename, $size, file_get_contents($file));
print_r($upload_file);
file_put_contents( $upload_file, 'm3-26.jpg' );
//print_r(file_get_contents('m3-26.jpg'));
print_r( $upload_file[url]);
if (move_uploaded_file(file_get_contents('m3-26.jpg'), $upload_file[url] )) { 
    echo "success"; 
} else {
    echo "error";
}
die();
print_r($_POST);
//$data = json_decode(file_get_contents('php://input'),true);  //print_r($data);
		//$username = $data[image];
//print_r(file_get_contents('php://input'));
print_r( wp_get_object_terms( 1748,  'pa_brand' ) );
$file_path_str = $file = '/home/sroot/Downloads/p2-225x300.jpg';
/*$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, ''.$file_path_str.'');
curl_setopt($ch, CURLOPT_HTTPGET, 1);
curl_setopt ($ch, CURLOPT_HEADER, 0);
curl_setopt ($ch, CURLOPT_USERAGENT, sprintf("Mozilla/%d.0",rand(4,5)));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$curl_response_res = curl_exec ($ch);*/
/*print_r($curl_response_res);
print_r($file);
/*curl_close ($ch);*/

/*$string = get_include_contents($file);
print_r($string);
die();
//print_r($woocommerce->get('products/categories/43'));

//print_r(var_dump($results));
/*$query = new WC_Coupon_Query( array(
    'limit' => 1,
    'orderby' => 'date',
    'order' => 'DESC',
) );*/
//print_r($woocommerce->get('coupons'));
//print_r($woocommerce->get('products/categories'));
//print_r($woocommerce->get('products/attributes'));
//print_r($woocommerce->get('products/attributes/3/terms'));
 //print_r($woocommerce->get('products'));
//print_r($woocommerce->get('products','attribute'=>'pa_color')); 
/*$query = [
    'date_min' => '2016-05-03', 
    'date_max' => '2016-05-04'
];

print_r($woocommerce->get('reports/sales', $query));*/
//print_r($woocommerce->get('settings'));

/*$data = [
    'email' => 'john1.doe@example112.com',
    'username' => 'john.doe1121'
];
//print_r($data);
$user_arr=array(
                    "status" => false,
                    "message" => "Undefined access method!!",
                    "request url" => 'BaseUrl/'. $endpoint.'?'.$requesturl[1],
                    "end point" => $endpoint1[0],
                );
//print_r(json_encode($user_arr));
print_r($woocommerce->post('customers', $data));
echo "test";*/
/*$query = [
    'customer' => 1,
    'fields' => 'id',
    'status' => 'completed'
];

$order = $woocommerce->get('orders',$query);
print_r($order); */
/*$data = [
    'order' => [
        'payment_details' => [
            'method_id' => 'bacs',
            'method_title' => 'Direct Bank Transfer',
            'paid' => true
        ],
        'billing_address' => [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address_1' => '969 Market',
            'address_2' => '',
            'city' => 'San Francisco',
            'state' => 'CA',
            'postcode' => '94103',
            'country' => 'US',
            'email' => 'john.doe@example.com',
            'phone' => '(555) 555-5555'
        ],
        'shipping_address' => [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address_1' => '969 Market',
            'address_2' => '',
            'city' => 'San Francisco',
            'state' => 'CA',
            'postcode' => '94103',
            'country' => 'US'
        ],
        'customer_id' => 1,
        'line_items' => [
            [
                'product_id' => 326,
                'quantity' => 2,
                'variations' => [
                    'pa_color' => 'Black'
                ]
            ],
            [
                'product_id' => 809,
                'quantity' => 1
            ]
        ],
        'shipping_lines' => [
            [
                'method_id' => 'flat_rate',
                'method_title' => 'Flat Rate',
                'total' => 10
            ]
        ]
    ]
];

print_r($woocommerce->post('orders', $data)); */
/*$data = [
    'regular_price' => '24.54'
];
print_r($data);
print_r($woocommerce->put('products/809', $data));*/
//print_r(the_field('attribute_image'));
/*$queriedObject = get_queried_object(); print_r($queriedObject);
echo get_field('attribute_image','pa_brand'.$queriedObject->term_id);*/
//print_r($field);
//$field = get_field_object('attribute_image',34);
//echo $field['label'];
//print_r($woocommerce->get('products/attributes/3/terms'));
//print_r(get_term_by('name','HRX','pa_brand'));

