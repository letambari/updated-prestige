#!/usr/bin/php
<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');
//date_default_timezone_set('Europe/London');
if (isset($_SERVER['REMOTE_ADDR'])) die('Permission denied.');
include_once("db_conx.php");
/////////////////// cron should run every 10 minutes
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
$server_status = getStatus();
$data = 0;
if($server_status->message == 'OK'){
    $data = 1;
}
$sql = "update update_control set nowPayStatus='$data' where id='1' limit 1";
$query = mysqli_query($db_conx, $sql);