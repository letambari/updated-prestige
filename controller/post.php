<?php
//include_once("../wp-includes/php_/check_login_status.php");
include_once("../wp-includes/php_/config.php");
include_once("nowpayments_api.php");
///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////
//////////// FUNCTION THAT GENERATE QR CODE ///////////////
///////////////////////////////////////////////////////////
function makeQr($string, $size='300'){
    //google api url
    $src = 'https://chart.googleapis.com/chart?';
    //size of qr code
    $src .='chs='.$size.'x'.$size;
    $src .='&cht=qr';
    //url-encoded string you want to change into a QR code 
    $src .='&chl='.$string;
    //encoding (optional)
    $src .='&choe=UTF-8';
    //make image
    return '<img src="'.$src.'" title="'.$string.'" />';
}
function makeQr2($string, $size='200'){
    //google api url
    $src = 'https://chart.googleapis.com/chart?';
    //size of qr code
    $src .='chs='.$size.'x'.$size;
    $src .='&cht=qr';
    //url-encoded string you want to change into a QR code 
    $src .='&chl='.$string;
    //encoding (optional)
    $src .='&choe=UTF-8';
    //make image
    return '<img src="'.$src.'" title="'.$string.'" />';
    //chart.googleapis.com/chart?chs=225x225&chld=L|2&cht=qr&chl=bitcoin:16bfJEM3cke7dbmPNoNvbMQRwmj5dbDgcf?amount=300%26label=btc
}
//global $secretKey,$headers;
//global $secretKeyNow,$headersNow;
//$secretKey = '691db28b88dba5ff5be009d6efbc5aa0acc59f81';
//$secretKeyNow = 'sk_live_b7e8c8fb15ee0a2e32e5d33f52ace290bab43c52';
//$headers = [
//    'Content-Type: application/json',
//    "X-API-Key: $secretKey"
//    ];
//
//$headersNow = [
//    'Accept: application/json',
//    "Authorization: Bearer $secretKeyNow"
//    ];
//function verifyWallet($coin, $wallet){
//    global $headers; 
//    $item = array("address"=>$wallet);
//    $data_get = array("item"=>$item);
//    $data = array("context"=>'',"data"=>$data_get);
//    // Data should be passed as json format
//    $data_json = json_encode($data);
//    $url = "https://rest.cryptoapis.io/blockchain-tools/$coin/testnet/addresses/validate?context=";
//    $result = initiatePostCall($data_json,$url,$headers);
//    return $result;
//}
///////////// initiate Post call
//function initiatePostCall($data_json,$url,$headers){    
//    $client = curl_init($url);
//    curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
//    // SET Method as a POST
//    curl_setopt($client,CURLOPT_POST,1);
//    // Pass user data in POST command
//    curl_setopt($client, CURLOPT_POSTFIELDS,$data_json);
//    curl_setopt($client, CURLOPT_HTTPHEADER, $headers);
//    $response = curl_exec($client);
//    $result = json_decode($response);
//    return $result;
//}
//////////////////////////////////////////////////
////////////////////////////////////////////////////////
if(isset($_GET["send_link"]) && isset($_POST["unameemail"])){
    $e = mysqli_real_escape_string($db_conx, $_POST['unameemail']);
    $sql = "SELECT id,unique_field,email FROM users WHERE username='$e' or email='$e' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $numrows = mysqli_num_rows($query);
    if($numrows > 0){
        while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
            $id = $row["id"];
            $unique = $row['unique_field'];
			$e = $row['email'];
        }
        $new_time = date("Y-m-d H:i:s");
        $sql = "UPDATE random_string SET date_updated ='$new_time' where id='$id' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $randA = randStrGen(30);
        $to = $e;
        $subject = "Re-set Password";
        $from = "$site_name_abbr <noreply@$site_link>";
        $body = '<b>Hello</b>,<br/>
            Your request to change your password has been received. Please confirm this action by on the link below <br/><br/>
                <a href="https://'.$site_link.'/reset-password/?q=true'.$unique.'&e='.$e.'&u='.$unique.'&reset-password='.$randA.'">'
                . 'https://'.$site_link.'/reset-password/?q=true'.$unique.'&e='.$e.'&u='.$unique.'&reset-password='.$randA.' </a><br/>'
                . '<span>If this was not you, please ignore this message and no changes will be made on your account.</span>';
        send_mail($to,$from,$subject,$body);
        echo 1;
        exit();
    } else {
        echo 2;
        exit();
    }
}
if(isset($_GET["reset-password"]) && isset($_POST['password']) ){
    $pas = $_POST['password'];
    $pas2 = $_POST['password2'];
    $u = mysqli_real_escape_string($db_conx, $_POST['__x']);
    if($pas === "" || $pas2 === ""||$u === ""){
        echo 2;
        exit();
    }else if($pas !== $pas2){
        echo 3;
        exit();
    }else{
        $sql = "SELECT id,email FROM users WHERE unique_field='$u' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $numrows = mysqli_num_rows($query);
        if($numrows > 0){
            while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
                $id = $row["id"];
                $e = $row['email'];
            }
            $sql = "SELECT randA,randB FROM random_string WHERE user_id='$id' LIMIT 1";
            $query = mysqli_query($db_conx, $sql);
            $row = mysqli_fetch_row($query);
            $randA = $row[0];
            $randB = $row[1];
            $cryptpass = md5($pas);
            $p_hash = "$randA$cryptpass$randB";
            $sql = "UPDATE users SET password ='$p_hash' where id='$id' LIMIT 1";
            $query = mysqli_query($db_conx, $sql);
            $to = $e;
            $subject = "Password Changed";
            $from = "$site_name_abbr <noreply@$site_link>";
            $body = '<b>Hello</b>, <br/> Your account password has been changed successfully<br/>
                 Your new password : <b>********</b>(what you typed in during re-set), 
                 please do not reveal this to a third party.<br/>
                 <span> Do not reply to this mail because it is not monitored, you can always contact us via support@'.$site_link.'.</span>';
            send_mail($to,$from,$subject,$body);
			$_SESSION[$site_cokie] = $u;
            echo 1;
            exit();
        } else {
            echo 4;
            exit();
        }
    }
}
//////////////////////////////////////////////////////////////////
//////// Script to post Contact us requests/questions////////////
/////////////////////////////////////////////////////////////////
if(isset($_GET["contact-home"]) && isset($_POST['form_email1']) && isset($_POST['form_message1'])){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $e = mysqli_real_escape_string($db_conx, $_POST['form_email1']);
    $name = mysqli_real_escape_string($db_conx, $_POST['form_name1']);
    $s = preg_replace('#[^a-z 0-9]#i', '', ucwords($_POST['form_subject1']));
    $m = mysqli_real_escape_string($db_conx, $_POST['form_message1']);
    $phone = mysqli_real_escape_string($db_conx, $_POST['phone']);
    if($e == "" || $m == ""|| $s == ""|| $name == ""){
        echo 2;
        exit();
    }else {  
	$new_time = date("Y-m-d H:i:s");
        $sql = "INSERT INTO message (f_name,email,subject, message,phone, created_at)VALUES('$name','$e','$s','$m','$phone','$new_time')";
        $query = mysqli_query($db_conx, $sql);
        if($query){
            $subject = $s;
            $from = $e;
            $to = "support@$site_link";
            $body = 'Name : '.$name.',<br/>Phone : '.$phone.',<br/>
                    E-mail : '.$e.',<br/>	
                    Subject : '.$s.',<br/>
                    Message: '.$m;
            send_mail($to,$from,$subject,$body);
            echo 1;
            mysqli_close($db_conx);
            exit();
        }  else {
            echo 3;
            mysqli_close($db_conx);
            exit();
        }
    }
}
///////////////////////////////////////////////////////////////
//////// deposit code///////////////////////////////////////
if(!isset($_SESSION[$site_cokie])){
    header("location: ../login");
    exit;
}
if(isset($_GET["user-reset-password"]) && isset($_POST['password']) ){
    $old_pas = md5($_POST['current_password']);
    $pas = $_POST['password'];
    $pas2 = $_POST['password2'];
    $u = mysqli_real_escape_string($db_conx, $_SESSION[$site_cokie]);
    if($pas === "" || $pas2 === ""||$u === ""||$old_pas === ""){
        echo 2;
        exit();
    }else if($pas !== $pas2){
        echo 3;
        exit();
    }else{
        
        $sql = "SELECT id,full_name,email,password FROM users WHERE unique_field='$u' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $numrows = mysqli_num_rows($query);
        if($numrows > 0){
            while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
                $id = $row["id"];
                $f_n = $row['full_name'];
                $e = $row['email'];
                $db_pass_str = $row['password'];
            }
            $sql = "SELECT randA,randB FROM random_string WHERE user_id='$id' LIMIT 1";
            $query = mysqli_query($db_conx, $sql);
            $row = mysqli_fetch_row($query);
            $randA = $row[0];
            $randB = $row[1];
            $p_hash = "$randA$old_pas$randB";
            if($p_hash != $db_pass_str){
                echo 31; //echo "failed. Invalid Password.";
                exit();
            }    
            $randA = randStrGen(10);
            $randB = randStrGen(10);
            $cryptpass = md5($pas);
            $p_hash = "$randA$cryptpass$randB";
            $sql = "UPDATE users SET password ='$p_hash' where id='$id' LIMIT 1";
            $query = mysqli_query($db_conx, $sql);
            if($query){
                $sql = "UPDATE random_string SET randA ='$randA',randB ='$randB' where user_id='$id' LIMIT 1";
                $query = mysqli_query($db_conx, $sql);
                /////////////
                $sql = "UPDATE member_count SET password ='$pas' where email='$e' LIMIT 1";
                $query = mysqli_query($db_conx, $sql);
                /////////////////////////////////
                if($query){
                    echo 1;
                    $to = $e;
                    $subject = "Password Changed";
                    $from = "$site_name_abbr <noreply@$site_link>";
                    $body = 'Hello '.$f_n.',<br/><br/>
                        This email is to confirm that your account password has been successfully changed. <br/>
                        If you did not request a password change, please contact us immediately.<br/><br/>';
                    send_mail($to,$from,$subject,$body);
                }
            }      
            exit();
        } else {
            echo 4;
            exit();
        }
    }
}
////////// google authentocator
if(isset($_GET["google_auth"]) && isset($_POST['code']) ){
    $code_form = mysqli_real_escape_string($db_conx, $_POST['code']);
    $code_secret = mysqli_real_escape_string($db_conx, $_POST['code_secret']);
    $u = mysqli_real_escape_string($db_conx, $_SESSION[$site_cokie]);
    if($code_form === "" || $code_secret === ""||$u === ""){
        echo 2;
        exit();
    }else{
        
        $sql = "SELECT id,full_name,email,password FROM users WHERE unique_field='$u' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $numrows = mysqli_num_rows($query);
        if($numrows > 0){
            while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
                $id = $row["id"];
                $f_n = $row['full_name'];
                $e = $row['email'];
                $db_pass_str = $row['password'];
            }
            ///////// here we get code from google
            include_once '2fa.php';
            $g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
            $secret = $code_secret;
            $google_code =  $g->getCode($secret);
//            echo $google_code.' '.$secret.' '.$code_form.' ';
            if ($g->checkCode($secret, $code_form)) {
                $sql = "UPDATE random_string SET google_auth ='1',google_secret ='$secret' where user_id='$id' LIMIT 1";
                $query = mysqli_query($db_conx, $sql);
                if($query){
                    echo 1;
                    exit();
                }else{
                    echo mysqli_error($db_conx);
                    exit();
                }
            }else {
                echo 3;
                exit();
            }
        } else {
            echo 4;
            exit();
        }
    }
}
////////////////////////////
function getLocalCurrencies(){
    global $db_conx;
    $sql = "SELECT nowCurrency FROM update_control WHERE id='1' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $row = mysqli_fetch_row($query);
    $result = json_decode($row[0], true);
    return $result;
}
function getLocalWalletSymbol($payment_method){
    global $db_conx;
    $sql = "SELECT wallet_symbol FROM wallets WHERE user_id='1' and wallet_name='$payment_method'  LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $row = mysqli_fetch_row($query);
    $result = $row[0];
    return $result;
}
if(isset($_GET["deposit"]) && isset($_POST['amount'])){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $payment_method = mysqli_real_escape_string($db_conx, $_POST['fund_method']);
    $amount = mysqli_real_escape_string($db_conx, $_POST['amount']);
//    $alt_coin = '';
    $unique = $_SESSION[$site_cokie];
    $nowPaymentCurrency = getLocalCurrencies();
    if($amount == "" || $payment_method == ""){
        echo 2;
        exit();
    }else{
        // DUPLICATE DATA CHECKS 
        $sql = "SELECT id,email,full_name,username FROM users WHERE unique_field='$unique' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $unique_check = mysqli_num_rows($query);
        if ($unique_check < 0){ 
            echo 3;
            exit();
        }else{
            $row = mysqli_fetch_row($query);
            $id = $row[0];
            $e = $row[1];
            $f_n = ucwords($row[2]);
            $uname = $row[3];
            if(''== $f_n){
                $f_n = ucwords($uname);
            }
            $sql = "SELECT currency FROM account WHERE user_id='$id' LIMIT 1";
            $query = mysqli_query($db_conx, $sql);
            $row = mysqli_fetch_row($query);
            $currency_main = explode("|", $row[0]);
            $currency = $currency_main[0];
            $symbol = end($currency_main);
//            if($amount < 500){
//                echo 33;
//                exit();
//            }
            // if($active < 1 && $plan == 'Starter' && $amount < 100){
                // echo 'Error!!! Your first deposit on your current plan can not be below 100 USD.';
                // exit();
            // }else if($active < 1 && $plan == 'Regular' && $amount < 5000){
                // echo 'Error!!! Your first deposit on your current plan can not be below 5000 USD.';
                // exit();
            // }else if($active < 1 && $plan == 'V.I.P' && $amount < 10000){
                // echo 'Error!!! Your first deposit on your current plan can not be below 10000 USD.';
                // exit();
            // }
            // END FORM DATA ERROR HANDLING
            // Begin Insertion of data into the database
            $new_time = date("Y-m-d H:i:s");
            $sql = "update account set request_deposite='1',updated_at='$new_time' where user_id='$id' limit 1";
            $query = mysqli_query($db_conx, $sql);
            if($query){ 
                /////////////////////
                $randA = randNumGen(15);
                $unique = $randA;
                ////////////////////////
                $payment_id = '';
                $sql = "SELECT nowPayStatus FROM update_control WHERE id='1' LIMIT 1";
                $query = mysqli_query($db_conx, $sql);
                $row = mysqli_fetch_row($query);
                $server_status = $row[0];
                $nowPaymentWorked = 0;
                if($server_status == '1'){
                    $wallet_currency = getLocalWalletSymbol($payment_method);
                    if($wallet_currency == 'usdt'){
                        $a = str_replace('(', '', $payment_method);
                        $wallet_currency = str_replace(')', '', $a);
                    }else if($wallet_currency == 'bnb'){
                        $wallet_currency = 'bnbbsc';
                    }
                    // first we check if the pair is on NowPayment
                    if(in_array(strtolower($wallet_currency),$nowPaymentCurrency)){
                        $createPayment = createPayment($amount, 'usd', $wallet_currency, $unique);
                        if(isset($createPayment->status) && $createPayment->statusCode == 400){
                            echo $createPayment->statusCode.'|||'.$createPayment->message;
                            exit();
                        }
                        $payment_status = $createPayment->payment_status;
                        if($payment_status == 'waiting'){
                            $wallet = $createPayment->pay_address;
                            $payment_id = $createPayment->payment_id;
                            $nowPaymentWorked = 1;
                        }

                    }                
                }
                /////////////////////////////
                $sql = "INSERT INTO transactions (user_id,unique_field,transaction_type,payment_method,amount, status, created_at)";
                $sql .= "VALUES('$id','$unique','Deposit','$payment_method','$amount','1','$new_time')";
                $query = mysqli_query($db_conx, $sql);                
                //// mail member /////
                $subject = "#$unique Deposit Request";
                $from = "$site_name_abbr <noreply@$site_link>";
                if(isset($_POST['coinname']) && ''!=$_POST['coinname']){
                    $alt_coin = mysqli_real_escape_string($db_conx, $_POST['coinname']);
                    $body = 'Dear  <b> '.$f_n.' </b>,<br/><br/>Thank you for choosing '.$site_name_abbr.'. Here\'s a summary of your order.<br/><br/>'
                            . '<b>Order Details : </b><br/> '
                            . 'Order Date:  <b>'.$new_time.'</b><br/>'
                            . 'Order Number:  <b>'.$unique.'</b><br/>'
                            . 'Order A&#847;mount:  <b>'.$symbol.$amount.'</b><br/>'
                            . 'P&#847;ayment Source:  <b>'.$alt_coin.'</b><br/>'
                            . 'Username:  <b>'.$uname.'</b><br/>'
                            . '<br/>
                            Details on how to make payments is being processed and will be sent to you shortly.<br/>
                            After payment, please send other relevant details to our support team.<br/>
                            This is an auto-generated mail, please do not reply. For enquiries on our 
                            product and services, please email support@'.$site_link.'<br/>';
                    send_mail($e,$from,$subject,$body);
                    //// mail web master /////
                    $to = 'support@'.$site_link;
                    $body = 'Hello Boss, a member has requested to fund their account, details below:<br/>
                            Transaction Id : '.$unique.'<br/>E-mail : '.$e.'<br/>
                            A&#847;mount :'.$symbol.$amount.'  <br/> Prefered P&#847;ayment Method : '.$alt_coin.'.<br/>
                            send them payment details from support@'.$site_link.' mail account ASAP';
                    send_mail($to,$from,$subject,$body);
                    echo 1;
                    exit();
                }elseif($payment_method !='Bank Wire'){                    
//                    $sql = "SELECT wallet_address FROM wallets WHERE user_id='1' and wallet_name='$payment_method'  LIMIT 1";
//                    $query = mysqli_query($db_conx, $sql);
//                    $row = mysqli_fetch_row($query);
//                    $wallet = $row[0];	
                    // get server status for NowPayments
                    if($nowPaymentWorked == 0){
                        $sql = "SELECT wallet_address FROM wallets WHERE user_id='1' and wallet_name='$payment_method'  LIMIT 1";
                        $query = mysqli_query($db_conx, $sql);
                        $row = mysqli_fetch_row($query);
                        $wallet = $row[0];
                    }
                    ////////////////////////////
                    $sql = "update transactions set payment_id='$payment_id',payment_details='$wallet' where unique_field='$unique' and user_id='$id' limit 1";
                    $query = mysqli_query($db_conx, $sql);
                    ///////////////////////////////////////
                    $new_time = date("Y-m-d H:i:s");
                    $body = 'Dear  <b> '.$f_n.' </b>,<br/><br/>Thank you for choosing '.$site_name_abbr.'. Here\'s a summary of your order.<br/><br/>'
                            . '<ul><b>Order Details : </b></ul><br/><br/> '
                            . 'Order Date:  <b>'.$new_time.'</b><br/>'
                            . 'Order Number:  <b>'.$unique.'</b><br/>'
                            . 'Order A&#847;mount:  <b>'.$symbol.$amount.'</b><br/>'
                            . 'P&#847;ayment Source:  <b>'.$payment_method.'</b><br/>'
                            . 'Username:  <b>'.$uname.'</b><br/>'
                            . '<br/>
                            P&#847;ayments should be made to the following wallet address <br/><br/>
                            <a href="#"> '.makeQr2($wallet).'<br/><b> '.$wallet.' </b></a>,<br/><br/>
                            Transaction details are to be sent to <b>support@'.$site_link.'</b><br/>
                            <span class="text-danger">Please do not reply to this mail as it is not monitored, 
                            any querry should be sent to <b>support@'.$site_link.'</b></span><br/>';
                    send_mail($e,$from,$subject,$body);
                    //// mail web master /////
                    $to = 'support@'.$site_link;
                    $body = 'Hello Boss, a member has requested to fund their account, details below:<br/>
                            Deposit code : '.$unique.'<br/>E-mail : '.$e.'<br/>
                            A&#847;mount :  '.$symbol.$amount.'  <br/> Prefered P&#847;ayment Method : '.$payment_method.'.<br/>
                                P&#847;ayment address has been sent automatically to them';
                    send_mail($to,$from,$subject,$body);
                    /// qrCode||Wallet||Invcoice code||Currency amount|| method
                    echo '0|||'.makeQr($wallet,200).'||'.$wallet.'||'.$unique.'||'.$symbol.$amount.'||'. ucwords($payment_method).'||'.$payment_id;
                    exit();
                }else{
                    $body = 'Dear  <b> '.$f_n.' </b>,<br/><br/>Thank you for choosing '.$site_name_abbr.' Here\'s a summary of your order.<br/><br/>'
                            . '<ul><b>Order Details : </b></ul><br/><br/> '
                            . 'Order Date:  <b>'.$new_time.'</b><br/>'
                            . 'Order Number:  <b>'.$unique.'</b><br/>'
                            . 'Order A&#847;mount:  <b>'.$symbol.$amount.'</b><br/>'
                            . 'P&#847;ayment Source:  <b>'.$payment_method.'</b><br/>'
                            . 'Username:  <b>'.$uname.'</b><br/>'
                            . '<br/>
                            Details on how to make payments is being processed and will be forwarded to you shortly,<br/>
                            after which on completing the transaction, relevant details are to be sent to our support team <b>support@'.$site_link.'</b><br/>
                            <span class="text-danger">Please do not reply to this mail as it is not monitored, any querry should be sent to <b>support@'.$site_link.'</b>.</span><br/>';
                    send_mail($e,$from,$subject,$body);
                    //// mail web master /////
                    $to = 'support@'.$site_link;
                    $body = 'Hello Boss, a member has requested to fund their account, details below:<br/>
                            Transaction Id : '.$unique.'<br/>E-mail : '.$e.'<br/>
                            A&#847;mount :'.$symbol.$amount.'  <br/> Prefered P&#847;ayment Method : '.$payment_method.'.<br/>
                            send them payment details from support@'.$site_link.' mail account ASAP';
                    send_mail($to,$from,$subject,$body);
                    echo 1;
                exit();
                }
                
            }
        }
         
    }
}
///////////
//check deposit status
//////////////////////////////////////////////////////////////////////////
if(isset($_GET['check_payment_status']) && isset($_POST['payment_id']) && $_POST['payment_id'] != "" ){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $payment_id = mysqli_real_escape_string($db_conx, $_POST['payment_id']);
    $payment_data = getPaymentStatus($payment_id);
    $payment_Status = $payment_data->payment_status;
    /// first we check the transaction status in the local DB    
    $sql = "select user_id,status,amount from transactions where transaction_type='Deposit' and payment_id='$payment_id'";
    $query = mysqli_query($db_conx, $sql);
    if(mysqli_num_rows($query) > 0){
        $row = mysqli_fetch_row($query);
        $id = $row[0];
        $status = $row[1];
        $a = $row[2];        
        if($status == 1){
            if($payment_Status == 'waiting'){
                echo 0;
                exit();
            }else if($payment_Status == 'confirming'){
                echo 1;
                exit();
            }elseif($payment_Status == 'confirmed' || $payment_Status == 'sending' ){
                $tran_id = $payment_id;
                $new_time = date("Y-m-d H:i:s");
                $sql = "update account set active='1',balance=(balance + '$a'),last_deposite='$a',total_deposite=(total_deposite + '$a'),updated_at='$new_time' where user_id='$id' limit 1";
                $query = mysqli_query($db_conx, $sql);
                if(!$query){
                    echo mysqli_error($query);
                    mysqli_close($db_conx);
                    exit();
                }
                $sql = "update transactions set status='2',completed_at='$new_time' where payment_id='$tran_id' and user_id='$id' limit 1";
                $query = mysqli_query($db_conx, $sql);
                if(!$query){
                    echo mysqli_error($query);
                    mysqli_close($db_conx);
                    exit();
                }
                ////////////////
                $sql = "SELECT email,full_name,sponsor  FROM users WHERE id='$id' LIMIT 1";
                $query = mysqli_query($db_conx, $sql);
                if(!$query){
                    echo mysqli_error($query);
                    mysqli_close($db_conx);
                    exit();
                }
                $row = mysqli_fetch_row($query);
                $email = $row[0];
                $fn = $row[1];
                $sponsor = $row[2];
                $s_sponsor = '';
                if($sponsor !=="" ){
                    $sql = "SELECT id,sponsor,full_name from users where email='$sponsor' limit 1";
                    $query = mysqli_query($db_conx, $sql);
                    if(!$query){
                        echo mysqli_error($query);
                        mysqli_close($db_conx);
                        exit();
                    }
                    $row = mysqli_fetch_row($query);
                    $s_id = $row[0];
                    $s_sponsor = $row[1];
                    $s_fn = $row[2];
                    $sql = "SELECT referal_percent from referral where user_id='$s_id' limit 1";
                    $query = mysqli_query($db_conx, $sql);
                    if(!$query){
                        echo mysqli_error($query);
                        mysqli_close($db_conx);
                        exit();
                    }
                    $row = mysqli_fetch_row($query);
                    $referal_rate = $row[0];
                    $referal_amount = ($a*$referal_rate);
                    $sql = "update referral set referal_amount=(referal_amount+'$referal_amount'),total_referal_amount=(total_referal_amount+'$referal_amount') where user_id='$s_id' limit 1";
                    $query = mysqli_query($db_conx, $sql);
                    if(!$query){
                        echo mysqli_error($query);
                        mysqli_close($db_conx);
                        exit();
                    }
                    /////////// here we check if he has a wallet already first
                    //////////////////////////////////////////////////
                    $randA = randNumGen(15);
                    $Tunique = $randA;
                    $new_time = date("Y-m-d H:i:s");
                    $sql = "INSERT INTO transactions (user_id,unique_field,transaction_type,payment_details,amount, status, created_at)";
                    $sql .= "VALUES('$s_id','$Tunique','Referral Bonus','E-wallet','$referal_amount','2','$new_time')";
                    $query = mysqli_query($db_conx, $sql);
                    if(!$query){
                        echo mysqli_error($query);
                        mysqli_close($db_conx);
                        exit();
                    }
                    /////// now we mail sponsor
                    $subject = "Referral Bonus";
                    $from = "$site_name_abbr <support@$site_link>";
                    $to = $sponsor;
                    $body = 'Hello, your referral bonus has been credited to your account:<br/>
                            Transaction code : '.$Tunique.'<br/>E-mail : '.$sponsor.'<br/>
                            A&#847;mount :  $'.$referal_amount.'  <br/>';
                    send_mail($to,$from,$subject,$body);
                }
                //////// here we check if their sponsor has a sponsor and to kow if they have paid them the second level two percent
                /////////////////////////////////////////////////////////////////////////////////////////
                $sql = "select id from transactions where status='1' and user_id='$id' and transaction_type='Deposit'";
                $query = mysqli_query($db_conx, $sql);
                if(!$query){
                    echo mysqli_error($query);
                    mysqli_close($db_conx);
                    exit();
                }
                $sql = "update account set request_deposite='0' where user_id='$id' limit 1";
                if(mysqli_num_rows($query) > 0){
                    $sql = "update account set request_deposite='1' where user_id='$id' limit 1";
                }
                $query = mysqli_query($db_conx, $sql);
                if($query){ 
                    //// mail member /////
                    $subject = "Account Credited";
                    $from = "$site_name_abbr <support@$site_link>";
                    $body = 'Hello,<br> this is to notify you that your 
                        account has been credited with the sum of $'.$a.'<br/>';
                    send_mail($email,$from,$subject,$body);
                } 
                echo 2;
                mysqli_close($db_conx);
                exit(); 
            }else{
                echo $payment_Status;
                exit();
            }
        }else if($status == 2){
            echo 2;
            exit();
        }else{
            echo 3;
            exit();
        }
    }
    exit();
}
/////////////// here is the purchase investment script
//
if(isset($_GET["purchase_portfolio"]) && isset($_POST['plan']) && isset($_POST['amount'])){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $purchase_plan = mysqli_real_escape_string($db_conx, $_POST['plan']);
    $amount = mysqli_real_escape_string($db_conx, $_POST['amount']);
//    $plan_duration = '';
//    if(isset($_POST['plan_duration'])){
//        $plan_duration = mysqli_real_escape_string($db_conx, $_POST['plan_duration']);
//    }    
    $unique = $_SESSION[$site_cokie];
    if($purchase_plan == "" || $amount == ""){
        echo 2;
        exit();
    }else{
        $days = 90;
        $inv_type = 0;
        $ref_bonus = 0.05;
        $daily_roi = 0.01;
        $days_duration = 90;
        $min = 100;
        if($purchase_plan == "Pack 104"){
            $daily_roi = 0.012;
            $min = 1000;
            $inv_type = 1;
            $days_duration = 120;
        }
        $monthly_roi = $daily_roi * $days;
        ////////// we check if their amount is less than the minimum or greater than maximum
        if($amount < $min){
            echo 22;
            exit();
        }
        $fund_currency = 'USD|$';
        $currency_main = explode("|", $fund_currency);
        $currency = $currency_main[0];
        $symbol = end($currency_main);
        // DUPLICATE DATA CHECKS 
        $sql = "SELECT id,email,full_name FROM users WHERE unique_field='$unique' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $unique_check = mysqli_num_rows($query);
        if ($unique_check < 1){ 
            echo 3;
            exit();
        }else{
            $row = mysqli_fetch_row($query);
            $id = $row[0];
            $e = $row[1];
            $f_n = ucwords($row[2]);
            ///////////// here we check if they have funds to make the purchase
            $sql = "SELECT balance from account where user_id='$id' and active='1' limit 1";
            $query = mysqli_query($db_conx, $sql);
            if(mysqli_num_rows($query) < 1){ 
                echo 31;
                exit();
            }
            $row = mysqli_fetch_row($query);
            $t_balance = $row[0];
            //// now we check if their amount is greater than balance
            if($amount > $t_balance){
                echo 32;
                exit();
            }
            /////////// now we insert into the slot_order table
            $randA = randNumGen(10);
            $unique_invest = $randA;
            $new_time = date("Y-m-d H:i:s");
            $sql = "INSERT INTO plan_account (user_id,plan_unique,active,plan,daily_profit_rate,monthly_profit_rate,"
                    . "last_deposite,total_deposite,balance,compound,plan_duration,created_at)";
            $sql .= " VALUES ('$id','$unique_invest','1','$purchase_plan','$daily_roi','$monthly_roi','$amount','$amount',"
                    . "'$amount','$inv_type','$days_duration','$new_time')";
            $query = mysqli_query($db_conx, $sql);
            if(!$query){
                echo mysqli_error($db_conx);
                exit();
            }
            // Here we add the transaction into the transaction table
            $randA = randNumGen(10);
            $trans_unique = $randA;
            $sql = "INSERT INTO transactions (user_id,unique_field,plan_unique,transaction_type,payment_method,amount, status, created_at, completed_at)";
            $sql .= "VALUES('$id','$trans_unique','$unique_invest','Portfolio Purchase','Wallet Balance','$amount','2','$new_time','$new_time')";
            $query = mysqli_query($db_conx, $sql);
            if(!$query){
                echo mysqli_error($db_conx);
                exit();
            }
            ///////// here we update the account table to minus the funds from balance
            $sql = "update account set active='1',balance=(balance - '$amount'),last_update='$new_time' where user_id='$id'  limit 1";
            $query = mysqli_query($db_conx, $sql);
            if(!$query){
                echo mysqli_error($db_conx);
                mysqli_close($db_conx);
                exit();
            }
            /////////////////////////////////
            if($query){
                //// mail member /////
                $subject = "$purchase_plan Purchase ";
                $from = "$site_name_abbr <noreply@$site_link>";
                $new_time = date("Y-m-d H:i:s");
                $body = 'Hello  <b> '.$f_n.' </b>, your '.$purchase_plan.' order has been successfully processed.<br/><br/>'
                        . '<h3>Order Details :</h3><br/>'
                        . 'Order Date:  <b>'.$new_time.'</b><br/>'
                        . 'Order Number:  <b>'.$unique_invest.'</b><br/>'
                        . 'Referrence:  <b>'.$trans_unique.'</b><br/>'
                        . 'Investment Portfolio:  <b>'.$purchase_plan.'</b><br/>'
                        . 'Order A&#847;mount:  <b>'.$symbol.$amount.'</b><br/>'
                        . 'P&#847;ayment Source:  <b>Account Balance</b><br/>'
                        . '<br/>
                        This investment is now live on your account. Login to your dashoard for more information.<br/>
                        <span class="text-danger">Do not reply to this mail as it is not monitored.</span><br/>';
                send_mail($e,$from,$subject,$body);
                //// mail web master /////
                $to = 'support@'.$site_link;
                $body = 'Hello Boss, a member has made an investment. Details below:<br/>'
                        . 'Order Date:  <b>'.$new_time.'</b><br/>'
                        . 'Order Number:  <b>'.$unique_invest.'</b><br/>'
                        . 'Investment Plan:  <b>'.$purchase_plan.'</b><br/>'
                        . 'Order A&#847;mount:  <b>'.$symbol.$amount.'</b><br/>'
                        . 'P&#847;ayment Source:  <b>Account Balance</b><br/>';
                send_mail($to,$from,$subject,$body);
                echo 1;
                exit();
            }else{
                echo mysqli_error($db_conx);
                exit();
            }
                         
            
        }
         
    }
}
////////////////////////////
if(isset($_GET["top_up"]) && isset($_GET['portfolio']) && $_GET['portfolio'] != ''){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $id = mysqli_real_escape_string($db_conx, $_GET['portfolio']);
    $amount = mysqli_real_escape_string($db_conx, $_POST['top_up_amount_'.$id]);
    $portfolio_id = mysqli_real_escape_string($db_conx, $_POST['plan_id_'.$id]);
    $unique = $_SESSION[$site_cokie];
    if($portfolio_id == "" || $amount == ""){
        echo 2;
        exit();
    }else{
        // DUPLICATE DATA CHECKS 
        $sql = "SELECT id,email,full_name,username FROM users WHERE unique_field='$unique' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $unique_check = mysqli_num_rows($query);
        if ($unique_check < 1){ 
            echo 3;
            exit();
        }else{
            $row = mysqli_fetch_row($query);
            $id = $row[0];
            $e = $row[1];
            $f_n = ucwords($row[2]);
            $uname = $row[3];
            if(''== $f_n){
                $f_n = ucwords($uname);
            }
            // DUPLICATE DATA CHECKS if the porfolio exists and still active
            $sql = "SELECT id FROM plan_account WHERE  user_id='$id' and plan_unique='$portfolio_id' and active='1' LIMIT 1";
            $query = mysqli_query($db_conx, $sql);
            $unique_check = mysqli_num_rows($query);
            if ($unique_check < 1){ 
                echo 4;
                exit();
            }
            //////// we check the account balance
            $sql = "SELECT active,currency,balance FROM account WHERE  user_id='$id' and active='1' LIMIT 1";
            $query = mysqli_query($db_conx, $sql);
            $row = mysqli_fetch_row($query);
            $active = $row[0];
            $currency_main = explode("|", $row[1]);
            $currency = $currency_main[0];
            $symbol = end($currency_main);
            $balance = $row[2];
             if($active < 1 ){
                 echo 5;
                 exit();
             }else if($balance < $amount){
                    echo 6;
                    exit();
             }
            // END FORM DATA ERROR HANDLING
            // Begin Insertion of data into the database
            $new_time = date("Y-m-d H:i:s");
            $sql = "update plan_account set active='1',balance=(balance + '$amount'),last_deposite='$amount',total_deposite=(total_deposite + '$amount'),last_update='$new_time' where user_id='$id' and plan_unique='$portfolio_id' limit 1";
            $query = mysqli_query($db_conx, $sql);
            if($query){ 
                $randA = randNumGen(15);
                $unique = $randA;
                $payment_method = 'Wallet Balance';
                $sql = "INSERT INTO transactions (user_id,plan_unique,unique_field,transaction_type,payment_method,amount, status, created_at)";
                $sql .= "VALUES('$id','$portfolio_id','$unique','Portfolio Top-Up','$payment_method','$amount','2','$new_time')";
                $query = mysqli_query($db_conx, $sql);
                //// mail member /////
                $subject = "Portfolio Top-Up";
                $from = "$site_name_abbr <noreply@$site_link>";
                $body = 'Dear  <b> '.$f_n.' </b>,<br/><br/>Your Portfolio Top-Up was successful. <br/><br/>'
                        . '<b>Order Details : </b><br/> '
                        . 'Date:  <b>'.$new_time.'</b><br/>'
                        . 'Portfolio Reference:  <b>'.$portfolio_id.'</b><br/>'
                        . 'Transaction Reference:  <b>'.$unique.'</b><br/>'
                        . 'Order A&#847;mount:  <b>'.$symbol.$amount.'</b><br/>'
                        . 'P&#847;ayment Source:  <b>'.$payment_method.'</b><br/>'
                        . '<br/>
                        More details can be found on your dashboard.<br/>
                        This is an auto-generated mail, please do not reply. For enquiries on our 
                        product and services, please email support@'.$site_link.'<br/>';
                send_mail($e,$from,$subject,$body);
                //// mail web master /////
                $to = 'support@'.$site_link;
                $body = 'Hello Boss, a member has topped up their account, details below:<br/>
                        Transaction Id : '.$unique.'<br/>E-mail : '.$e.'<br/>
                        A&#847;mount :'.$symbol.$amount.'  <br/> Prefered P&#847;ayment Method : '.$payment_method.'.<br/>';
                send_mail($to,$from,$subject,$body);
                echo 1;
                exit();
            }else{
                echo mysqli_error($db_conx);
                exit();
            }
        }
         
    }
}
///////////////// script for withdrawal request /////////////////
//////////////////////////////////////////////////////////////////////////
if(isset($_GET["withdraw"]) && isset($_POST['amount'])){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $withdraw_from = mysqli_real_escape_string($db_conx, $_POST['plan_id']);
    $amount = mysqli_real_escape_string($db_conx, $_POST['amount']);
    $payment_method = mysqli_real_escape_string($db_conx, $_POST['withdraw_method']);
    $withdraw_details = mysqli_real_escape_string($db_conx, $_POST['withdraw_details']);
    $unique = $_SESSION[$site_cokie];
    if($amount == ""||$payment_method==""||$withdraw_details==""||$withdraw_from==""){
        echo 2;
        exit();
    }else{
        // DUPLICATE DATA CHECKS 
        $sql = "SELECT id,email,active FROM users WHERE unique_field='$unique' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $unique_check = mysqli_num_rows($query);
        if($unique_check < 0){ 
            echo 3;
            exit();
        }else{
            $row = mysqli_fetch_row($query);
            $id = $row[0];
            $e = $row[1];
            $active = $row[2];
            if($active < 1){ 
                echo 4;
                exit();
            }else{
                $sql = "SELECT profit,total_deposite,balance,currency,compound from plan_account where user_id='$id' and plan_unique='$withdraw_from' limit 1";
                if($withdraw_from == 'Wallet'){
                    $sql = "SELECT profit,total_deposite,balance,currency from account where user_id='$id' limit 1";
                }
                $query = mysqli_query($db_conx, $sql);
                if(!$query){
                    echo mysqli_error($db_conx);
                    exit();
                }
                $row = mysqli_fetch_row($query);
                $t_profit = $row[0];                
                $t_amount = $row[1];
                $b_amount = $row[2];
                $currency_main = explode("|", $row[3]);
                $currency = $currency_main[0];
                $symbol = end($currency_main);
                $compound = 0;
                if($withdraw_from != 'Wallet'){
                    $compound = $row[4];
                }
                if ($amount > $b_amount){ 
                    echo 5;
                    exit();
                }else if ('1' == $compound && $amount > $t_profit){ 
                    echo 5;
                    exit();
                }else if ($amount < 1 && 'USD' == $currency){ 
                    echo 6;
                    exit();
                }else{
                    // we check if the wallet is valid                    
//                    $pm = strtolower($payment_method);
//                    if($pm == 'bitcoin'||$pm == 'bitcoin cash'||$pm == 'ethereum'||$pm == 'litecoin'||$pm == 'doge'||$pm == 'ripple'||
//                            $pm == 'binance coin'||$pm == 'usdt(erc20)'||$pm == 'usdt(trc20)'){
//                        $coin = $payment_method;
//                        if($pm == 'bitcoin cash'){
//                            $coin = 'bitcoin-cash';
//                        }else if($pm == 'doge'){
//                            $coin = 'dogecoin';
//                        }else if($pm == 'ripple'){
//                            $coin = 'xrp';
//                        }else if($pm == 'usdt(trc20)'){
//                            $coin = 'tron';
//                        }else if($pm == 'binance coin'||$pm == 'usdt(erc20)'){
//                            $coin = 'binance-smart-chain';
//                        }
//                        $veryfiWallet = verifyWallet($coin, $withdraw_details);
////                        echo $veryfiWallet->error->message;address
//                        if($veryfiWallet->data->item->isValid === true){
//                            echo 'true';
//                        }else{
//                            echo 'false ' .$coin;
//                        }
//                        echo $veryfiWallet->data->item->address;
////                        echo $veryfiWallet->data->item->isValid;
//                        exit();
//                        bitcoin 
//                    bitcoin-cash 
//                    litecoin 
//                    dogecoin 
//                    dash 
//                    ethereum 
//                    ethereum-classic 
//                    xrp 
//                    zilliqa 
//                    binance-smart-chain 
//                    zcash tron
//                    }
                    $new_time = date("Y-m-d H:i:s");
                    // Begin Insertion of data into the database
                    $sql = "update plan_account set request_withdraw='1',updated_at='$new_time' where user_id='$id' and plan_unique='$withdraw_from' limit 1";
                    if($withdraw_from == 'Wallet'){
                        $sql = "update account set request_withdraw='1',updated_at='$new_time' where user_id='$id' limit 1";
                    }
                    $query = mysqli_query($db_conx, $sql);
                    if($query){ 
                        $randA = randNumGen(15);
                        $unique = $randA;
                        $sql = "INSERT INTO transactions (user_id,unique_field,plan_unique,transaction_type,payment_method,payment_details,amount, status, created_at)";
                        $sql .= "VALUES('$id','$unique','$withdraw_from','Withdrawal','$payment_method','$withdraw_details','$amount','1','$new_time')";
                        $query = mysqli_query($db_conx, $sql);
                        if(!$query){
                            echo mysqli_error($db_conx);
                            exit();
                        }
                        //// mail member /////
                        $subject = "Withdrawal request";
                        $from = "$site_name_abbr <noreply@$site_link>";                        
                        $body = 'Hello,<br> '
                                . 'your withdrawal request of <b>'.$symbol.$amount.'</b> on your account has been received and 
                                    is being processed.  <br/>
                            Transaction Id <b>'.$unique.'</b><br/>
                            Referrence <b>'.$withdraw_from.'</b><br/>
                            Withdrawal Method : <b>'.$payment_method.'</b>.<br/>
                            Details : <b>'.$withdraw_details.'</b>.<br/>
                                Note that payment and processing may take up to 1-3 days, we employ your patient.
                            <span class="text-danger">Please do not reply to this mail as it is not monitored, any querry should be sent to <b>support@'.$site_link.'</b>.</span><br/>';
                    send_mail($e,$from,$subject,$body);
                    //// mail web master /////
                        //// mail web master /////
                        $to = 'support@'.$site_link;
                        $body = 'Hello Boss, a member has requested to withdraw funds from their account, details below:<br/>
                                Transaction Id : '.$unique.'<br/>
                                E-mail : '.$e.'<br/>
                                A&#847;mount to withdraw : '.$symbol.$amount.'  <br/> Total A&#847;mount deposited : '.$symbol.$t_amount.'  <br/>
                                 Total Balance : '.$symbol.$b_amount.'  <br/>other details <br/>
                                    Withdrawal channel : <b>'.$payment_method.'</b>.<br/>
                                   Details : <b>'.$withdraw_details.'</b>.<br/>';
                        send_mail($to,$from,$subject,$body);
                        echo 1;
                        exit();         
                    }else{
                        echo mysqli_error($db_conx);
                        exit();
                    }
                }
            }
        }
         
    }
}
///////// chekck balance on their accounts, mined or deposited/////////
//////////////////////////////////////////////////////////////////////////
if(isset($_POST['function']) && $_POST['function'] == "check_balance" and isset($_POST['from'])){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $section = mysqli_real_escape_string($db_conx, $_POST['from']);
    if($section == 'mined'){
        $sql = "SELECT profit FROM account WHERE user_id='$profile_id' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $row = mysqli_fetch_row($query);
        $amount = $row[0];
        if($amount < 50){
            echo 'You do not have up to the minimum withdraw (50 USD)';
            exit();
        }else{
            echo 'success';
            exit();
        }
    }else if($section == 'deposited'){
        $sql = "SELECT total_deposite FROM account WHERE user_id='$profile_id' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $row = mysqli_fetch_row($query);
        $amount = $row[0];
        if($amount < 10){
            echo 'You do not have up to the minimum withdraw (10 USD)';
            exit();
        }else{
            echo 'success';
            exit();
        }
    }else{echo '0';exit();}
}
///////////////// send code
if(isset($_GET["send_mail"]) && isset($_POST["user_unique"])){
    $unique = mysqli_real_escape_string($db_conx, $_POST['user_unique']);
    $sql = "SELECT id,email FROM users WHERE unique_field='$unique' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $numrows = mysqli_num_rows($query);
    if($numrows > 0){
        while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
            $id = $row["id"];
            $e = $row['email'];
        }         
        $new_time = date("Y-m-d H:i:s");
        $pin = randNumGen(6);
        $sql = "UPDATE random_string SET pin='$pin',date_updated ='$new_time' where user_id='$id' LIMIT 1";        
        $query = mysqli_query($db_conx, $sql);
        if(!$query){
            echo mysqli_error($db_conx);
            exit();
        }
        $to = $e;
        $subject = "Authentication Code";
        $from = "$site_name_abbr <auth@$site_link>";
        $body = '<b>Hello</b>,<br/>
            Your two factor Authentication code is:<br/> <b>'.$pin.'</b><br/>'
            . '<span>If this was not you, please ignore this message and no changes will be made on your account.</span>';
        send_mail($to,$from,$subject,$body);
        echo 1;
        exit();
    } else {
        echo 2;
        exit();
    }
}
//////////////////////////////////////////////////////////////////////////
//////// 
//////////////////////////////////////////////////////////////////////////
if(isset($_GET['update_seen']) && isset($_POST['update_email_seen']) && $_POST['update_email_seen'] !=''){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $email = mysqli_real_escape_string($db_conx, $_POST['update_email_seen']);
    $sql = "SELECT id FROM users WHERE email='$email' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    if(mysqli_num_rows($query)>0){
        $new_time = date("Y-m-d H:i:s");
        $sql = "update users set notescheck='$new_time' where email='$email' limit 1";
        $query = mysqli_query($db_conx, $sql);
        if($query){
            echo '1';
        }
    }else{echo '0';}
    exit();
}
