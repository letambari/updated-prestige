<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set('Africa/Lagos');
//if (isset($_SERVER['REMOTE_ADDR'])) die('Permission denied.');
include_once("../wp-includes/phasxkl/functions.php");
include_once("../wp-includes/phasxkl/db_conx.php");
/////////////////// here we check to delete accounts that have been suspended for seven days //////////////

$sql = "SELECT first_name,email,phone  FROM users where active='1' order by id asc";
$query11 = mysqli_query($db_conx, $sql);
$numrows=  mysqli_num_rows($query11);
if($numrows > 0){
    $count = 0;
	while($row = mysqli_fetch_array($query11, MYSQLI_ASSOC)) {
            $count ++;
            $user_fn = $row['first_name'];
            $user_email = $row['email'];
            $user_phone = $row['phone'];
            $subject = "Investment Plan Review";
            $from = "Big Trade Crypto <support@bigtradecryptoworldlimited.com>";
            $body = 'Hello '.$user_fn.',<br/>
                Following some recent upgrades to the bitcoin network and changes made on our trading platform, 
                the Board of Directors at Big Trade Crypto World Limited (BTCWL) has come to a conclusion to 
                review our Investment plans to suit what can be obtainable from this global company.<br/>
                Without doubt you have come to realize how reliant the company has been so far and the level of consistency 
                we have maintained with both our weekly payouts and total capital investment returns for a couple of years 
                and still counting.<br/>In these past months the company has recorded minimal loss and our professional traders 
                are working to maintain that record and make sure your investments and life savings are save with us.<br/>
                You are adviced to view our "Investment plans" section on the company\'s website to see the changes made to 
                some investment plans. Please note that this was done to maintain stability in our platform, but all other 
                activities still remain unchanged.<br/>
                Thanks for your patience and understanding. Your welfare and safety still remains our uttermost concern<br/>
                Kind Regards.

                Big Trade Crypto World Limited ';
            send_mail($user_email,$from,$subject,$body);
    }
    echo $count ." users were sent messages <br/>";
}
