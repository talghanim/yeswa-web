<?php
require_once ('../vendor/autoload.php');

use Automattic\WooCommerce\Client;
$host = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";	
$woocommerce = new Client(
    ''.site_url().'', // Your store URL    
    'ck_501ce4bf578e8ae4b1c6446cdd28d725d08dc780', // Your consumer key
    'cs_fb093eef978114bf6bc3fbb4570eeae391fbab26', // Your consumer secret
    [
        'wp_api' => true, // Enable the WP REST API integration
        'version' => 'wc/v2'// WooCommerce WP REST API version
    ]
);