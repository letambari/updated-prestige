#!/usr/bin/php
<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');
//date_default_timezone_set('Europe/London');
//if (isset($_SERVER['REMOTE_ADDR'])) die('Permission denied.');
include_once("db_conx.php");
global $site_name,$site_link;
$site_name = 'Felton Asset Management Limited';
$site_name_abbr = 'Felton Asset Management';
$site_name_abbr2 = 'Felton Assets';
$site_link = 'feltonassets.com';
/////////////////// here we add their daily profit to their accounts //////////////
//$new_time = date("Y-m-d H:i:s", strtotime('-168 hours'));
$sql="select auto from update_control where id='1'";
$query = mysqli_query($db_conx, $sql);
$row = mysqli_fetch_row($query);
$control = $row[0];
if($control == 1){
    /////////////////// here we add their daily profit to their accounts //////////////
    $sql = "SELECT * FROM plan_account where active='1' and balance>'0' order by id asc";
    $query11 = mysqli_query($db_conx, $sql);
    $numrows=  mysqli_num_rows($query11);
    if($numrows > 0){
        $count = 0;
        $new_time = date("Y-m-d H:i:s");
        $container = 'Members that were updated for daily update on '.$new_time.'\n\n\n';
        while($row = mysqli_fetch_array($query11, MYSQLI_ASSOC)) {
            $count ++;
            $user_id = $row['user_id'];
            $plan_unique = $row['plan_unique'];
            $plan = $row['plan'];
            $auto_update = $row['auto_update'];
            $daily_profit_rate = $row['daily_profit_rate'];
            $compound= $row['compound'];
            $balance= $row['balance'];
            $current_week_profit= $row['current_week_profit'];
            $day_count= $row['day_count'];
            $total_deposite= $row['total_deposite'];
            $sql = "SELECT * FROM users WHERE id='$user_id' LIMIT 1";
            $user_query = mysqli_query($db_conx, $sql);
            while ($row = mysqli_fetch_array($user_query, MYSQLI_ASSOC)) {
                $f_n = ucwords($row['full_name']);
                $uname = $row['username'];
                $email = $row['email'];
                $tel = $row['phone']; 
            }
            if($auto_update == 1){
                $day_count ++;
                $account_type = "Normal";
                if($compound == 1){
                    $account_type = "Compounding";
                    $profit = $daily_profit_rate * $balance;
                }elseif($compound == 0){
                    $profit = $daily_profit_rate * $total_deposite;
                }
                $sql = "update plan_account set profit=(profit +'$profit'),day_count=(day_count + '1'),current_week_profit=(current_week_profit +'$profit'),last_update='$new_time' where user_id='$user_id' and plan_unique='$plan_unique' limit 1";
                $query = mysqli_query($db_conx, $sql);
                if($day_count >= 20){
                    $current_week_profit += $profit;
                    $balance += $current_week_profit;
                    $sql = "update plan_account set active='2',current_week_profit='0',balance=(balance +'$current_week_profit'),last_update='$new_time' where user_id='$user_id' and plan_unique='$plan_unique' limit 1";
                    $query = mysqli_query($db_conx, $sql);
                    /////////////////////
                    $sql = "update account set balance=(balance + '$balance'),last_update='$new_time' where user_id='$user_id'  limit 1";
                    $query = mysqli_query($db_conx, $sql);
                    //////////////////////////////                    
                    $randA = randNumGen(15);
                    $Tunique = $randA;
                    $new_time2 = date("Y-m-d H:i:s");
                    $sql = "INSERT INTO transactions (user_id,unique_field,plan_unique,transaction_type,payment_method,payment_details,amount, status, created_at, completed_at)";
                    $sql .= "VALUES('$user_id','$Tunique','$plan_unique','Portfolio Pay-Out','USDT','E-wallet','$balance','2','$new_time','$new_time2')";
                    $query = mysqli_query($db_conx, $sql);
                    ///////////////////////////////////
                    //// mail member /////
                    $subject = "Portfolio Pay-Out ";
                    $from = "$site_name_abbr <noreply@$site_link>";
                    $body = 'Hello  <b> '.$f_n.' </b>, your '.$plan.' order has been successfully Paid Out.<br/><br/>'
                            . '<h3>Order Details :</h3><br/>'
                            . 'Order Date:  <b>'.$new_time.'</b><br/>'
                            . 'Referrence:  <b>'.$Tunique.'</b><br/>'
                            . 'Portfolio Number:  <b>'.$plan_unique.'</b><br/>'
                            . 'Investment Portfolio:  <b>'.$plan.'</b><br/>'
                            . 'Order Amount:  <b>$'.$balance.'</b><br/>'
                            . 'Paid To:  <b>Account Balance</b><br/>'
                            . '<br/>
                            This investment is now expired on your account. Login to your dashoard for more information.<br/>
                            <span class="text-danger">Do not reply to this mail as it is not monitored.</span><br/>';
                    send_mail($email,$from,$subject,$body);
                }
            }
            $container .= 'Username: '.$uname.'\n'
                    .'Account Type: '.$account_type.'\n'
                    .'Total Balance: $'.$balance.'\n'
                    .'Total deposit: $'.$total_deposite.'\n'
                    .'Profit Rate: $'.$daily_profit_rate.'\n'
                    .'Profit Added: $'.$profit.'\n\n\n';
        }
        echo $container;
        echo $count ." users were updated <br/>";
    }
}
function randNumGen($len){
	$result = "";
    $chars = "193827654";
    $charArray = str_split($chars);
    for($i = 0; $i < $len; $i++){
	    $randItem = array_rand($charArray);
	    $result .=$charArray[$randItem];
    }
    return $result;
}
function send_mail($to,$from,$subject,$body){
    global $site_link,$site_name;
    $message ='<!DOCTYPE html><html lang="en-gb" dir="ltr"><head><meta charset="UTF-8"><title>'.$subject.'</title><meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://'.$site_link.'/assets/css/bootstrap.css" rel="stylesheet" type="text/css">
<link rel="Stylesheet" href="https://'.$site_link.'/assets/css/custom.css"/>
</head><body id="main" style="padding:20px;"> <div class="container">   <div class="row">
        <div class="col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-xs-12 jumbotron" style="padding-top:40px">
        <img src="https://'.$site_link.'/assets/images/logo.png" alt="'.$site_name.'" style="width:200px" class="pull-left img-responsive"/><br/>'.$body.'<br/><br/><br/><br/>                    
    <footer style="background: #191919; padding:40px; color:#999999"><br/>Kind Regards,<br/> <a href="https://'.$site_link.'" target="_blanc">'.$site_name.'</a><br/>&nbsp;&nbsp;</footer></div></div></div>
</body></html>';
    $headers = "From: $from\r\n";    
    $headers .= "Reply-To: $from \r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\n";
    $headers .= "X-Mailer: PHP \r\n";
    mail($to, $subject, $message, $headers);
}