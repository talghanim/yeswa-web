<?php
require_once ('../vendor/autoload.php');

use Automattic\WooCommerce\Client;
//$host = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";	
//echo "<pre>"; print_r(site_url()); echo "<br/>";
$url = site_url();
$woocommerce = new Client(
    $url, // Your store URL    
    'ck_8dce4ce56de21ee47d30a362bd01f9789e936932', // Your consumer key
    'cs_d528c129e45ff10d9cce22c49c787d6ba0804570', // Your consumer secret
    [
        'wp_api' => true, // Enable the WP REST API integration
        'version' => 'wc/v2',// WooCommerce WP REST API version
		//'ssl_verify' => 'false',
    ]
);

//echo "<pre>"; print_r($woocommerce); echo "<br/>";

