<?php
global $secretKey,$headers;
$secretKey = 'TA6AWQM-57PMC2P-QKBHYAR-5JWFMK5';
$email = 'feltonassets@proton.me';
$password = 'Dredre1993$$';
$headers = [
    'Content-Type: application/json; charset=utf-8',
    'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0',
    "x-api-key: $secretKey"
    ];
////////////////
function getStatus(){
    global $headers;    
    $url = "https://api.nowpayments.io/v1/status";
    $result = initiateGetCall($url,$headers);
    return $result;
//    {
//  "message": "OK"
//}
}
////////////////
function getMinimumPayment(){
    global $headers;    
    $url = "https://api.nowpayments.io/v1/min-amount?currency_from=usd&currency_to=usdttrc20&fiat_equivalent=usd";
    $result = initiateGetCall($url,$headers);
    return $result;
//    {
//  "message": "OK"
//}
}
////////////////
function getCurrencies(){
    global $headers;    
    $url = "https://api.nowpayments.io/v1/currencies";
    $result = initiateGetCall($url,$headers);
    return $result;
//    {
//  "currencies": [
//    "btg",
//    "eth",
//    "xmr"]
//          
//    }
}
function getCoinValue( $amount, $currencyTo , $currencyFrom = 'usd'){
    global $headers;    
    $url = "https://api.nowpayments.io/v1/estimate?amount=$amount&currency_from=$currencyFrom&currency_to=$currencyTo";
    $result = initiateGetCall($url,$headers);
    return $result;
//    {
//  "currency_from": "usd",
//  "amount_from": 3999.5,
//  "currency_to": "btc",
//  "estimated_amount": 0.17061637
//}
}
function getPaymentStatus( $paymentId){
    global $headers;    
    $url = "https://api.nowpayments.io/v1/payment/$paymentId";
    $result = initiateGetCall($url,$headers);
    return $result;
//{
//  "payment_id": 5524759814,
//  "payment_status": "finished",
//  "pay_address": "TNDFkiSmBQorNFacb3735q8MnT29sn8BLn",
//  "price_amount": 5,
//  "price_currency": "usd",
//  "pay_amount": 165.652609,
//  "actually_paid": 180,
//  "pay_currency": "trx",
//  "order_id": "RGDBP-21314",
//  "order_description": "Apple Macbook Pro 2019 x 1",
//  "purchase_id": "4944856743",
//  "created_at": "2020-12-16T14:30:43.306Z",
//  "updated_at": "2020-12-16T14:40:46.523Z",
//  "outcome_amount": 178.9005,
//  "outcome_currency": "trx"
//}
}

//////////////////////
function authToken($email, $password){
    global $headers; 
    $data = array("email"=>$email,"password"=>$password);
    // Data should be passed as json format
    $data_json = json_encode($data);
    $url = "https://api.nowpayments.io/v1/auth";
    $result = initiatePostCall($data_json,$url,$headers);
    return $result;
//    {
//  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjU4MjYyNTkxMTUiLCJpYXQiOjE2MDUyODgzODQsImV4cCI6MTYwNTI4ODY4NH0.bk8B5AjoTt8Qfm1zHJxutAtgaTGW-2j67waGQ2DUHUI"
//}
}
//////////////////////
function createPayment($amount, $cur, $payCur, $transId){
    global $headers; 
    $ipn = 'https://feltonassets.com/controller/payment_ipn.php';
    $data = array("price_amount"=>$amount,"price_currency"=>$cur,"pay_currency"=>$payCur,"ipn_callback_url"=>$ipn,"order_id"=>$transId);
    // Data should be passed as json format
    $data_json = json_encode($data);
    $url = "https://api.nowpayments.io/v1/payment";
    $result = initiatePostCall($data_json,$url,$headers);
    return $result;
//{
//  "payment_id": "5745459419",
//  "payment_status": "waiting", confirming ,confirmed ,
//  "pay_address": "3EZ2uTdVDAMFXTfc6uLDDKR6o8qKBZXVkj",
//  "price_amount": 3999.5,
//  "price_currency": "usd",
//  "pay_amount": 0.17070286,
//  "pay_currency": "btc",
//  "order_id": "RGDBP-21314",
//  "order_description": "Apple Macbook Pro 2019 x 1",
//  "ipn_callback_url": "https://nowpayments.io",
//  "created_at": "2020-12-22T15:00:22.742Z",
//  "updated_at": "2020-12-22T15:00:22.742Z",
//  "purchase_id": "5837122679",
//  "amount_received": null,
//  "payin_extra_id": null,
//  "smart_contract": "",
//  "network": "btc",
//  "network_precision": 8,
//  "time_limit": null,
//  "burning_percent": null,
//  "expiration_estimate_date": "2020-12-23T15:00:22.742Z"
//}
}
/////////// initiate Post call
function initiatePostCall($data_json,$url,$headers){    
    $client = curl_init($url);
    curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
    // SET Method as a POST
    curl_setopt($client,CURLOPT_POST,1);
    // Pass user data in POST command
    curl_setopt($client, CURLOPT_POSTFIELDS,$data_json);
    curl_setopt($client, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($client);
    if (curl_errno($client)) {
        $response = curl_error($client);
    }
    $result = json_decode($response);
    return $result;
}
/////////// initiate Get call
function initiateGetCall($url,$headers){    
    $client = curl_init($url);
    curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($client, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($client);
    $result = json_decode($response);
    return $result;
}
