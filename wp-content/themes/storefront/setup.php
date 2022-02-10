<?php 
// Install:
// composer require automattic/woocommerce

// Setup:
require __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;

$woocommerce = new Client(
    'https://neswaapp.com', // Your store URL
    'ck_a0577254e53dbbeb66062c3c23d17d48dc578c06', //'consumer_key', // Your consumer key
    'cs_13763e1432cdf51e28eca10fa321ac56243f69f7', //'consumer_secret', // Your consumer secret
    [
        'version' => 'v3' // WooCommerce API version
    ]
);
?>