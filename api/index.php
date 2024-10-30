<?php

$server_key = "SB-Mid-server-xHFyutJ6qbcKuqi8aSSs02_8";
$is_prod = false;
$api_url = $is_prod?'https://app.midtrans.com/snap/v1/transactions':'https://app.sandbox.midtrans.com/snap/v1/transactions';

if (!strpos($_SERVER['REQUEST_URL'],'/charge')){
    http_response_code(404);
    echo "Wrong path, make sure again your path";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(404);
    echo 'Page not found';
    exit();
}

$request_body = file_get_contents('php://input');
header('Content-Type: application/json');

$charge_result = chargeApi($api_url,$server_key,$request_body);
http_response_code($charge_result['http_code']);

echo $charge_result['body'];

function chargeApi($api_url,$server_key,$request_body){
     $ch = curl_init();
     $curl_option = array(
        CURLOPT_URL => $api_url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_HTTPHEADER => array(
            'Content_Type: application/json',
            'Accept: application/json',
            'Authorization: Basic '. base64_decode($server_key)
        ),
        CURLOPT_POSTFIELD => $request_body
     ); 

     curl_setopt_array($ch,$curl_option);
     $result = array(
        'body' => curl_exec($ch),
        'http_code' => curl_getinfo($sc,CURLINFO_HTTP_CODE)
     );
    return $result;
}
?>