<?php  echo "res";
require __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;

$woocommerce = new Client(
    'http://localhost/yeswa', 
    'ck_a0577254e53dbbeb66062c3c23d17d48dc578c06', 
    'cs_13763e1432cdf51e28eca10fa321ac56243f69f7',
    [
        'version' => 'wc/v3',
    ]
);

$result = $client->orders->get();
print_r($result); 