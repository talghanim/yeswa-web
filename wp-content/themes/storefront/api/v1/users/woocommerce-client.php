<?php
require_once ('../vendor/autoload.php');

use Automattic\WooCommerce\Client;
//$host = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";	
//echo "<pre>"; print_r(site_url()); echo "<br/>";
$url = site_url();
$woocommerce = new Client(
    $url, // Your store URL    
    'ck_8a11d8f7b43a96e5404f467f8a34aac34ffee448', // Your consumer key
    'cs_cf384823069036a874f1599cfbafff8dbe7417dd', // Your consumer secret
    [
        'wp_api' => true, // Enable the WP REST API integration
        'version' => 'wc/v2',// WooCommerce WP REST API version
		//'ssl_verify' => 'false',
    ]
);

//echo "<pre>"; print_r($woocommerce); echo "<br/>";

