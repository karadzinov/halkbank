<?php

return [
    'client_id' => env('NESTPAY_CLIENT_ID', '180000260'),
    'store_key' => env('NESTPAY_STORE_KEY', 'SKEY0260'),
    'store_type' => env('NESTPAY_STORE_TYPE', '3D_PAY_HOSTING'),
    'currency' => env('NESTPAY_CURRENCY', '807'),
    'lang' => env('NESTPAY_LANG', 'en'),
    'hash_algorithm' => 'ver3',
    'three_d_post_url' => env('NESTPAY_3D_POST_URL', 'https://torus-stage-halkbankmacedonia.asseco-see.com.tr/fim/est3Dgate'),
    'api_post_url' => env('NESTPAY_API_POST_URL', 'https://torus-hotfix.asseco-see.com.tr/fim/api'),
    'api_username' => env('NESTPAY_API_USERNAME', 'pilatesapi'),
    'api_password' => env('NESTPAY_API_PASSWORD', 'TEST68289301'),
];


