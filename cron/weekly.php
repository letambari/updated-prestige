#!/usr/bin/php
<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
//date_default_timezone_set('Europe/London');
//if (isset($_SERVER['REMOTE_ADDR'])) die('Permission denied.');
include_once("db_conx.php");
# +--------- Minute (0-59)                    | Output Dumper: >/dev/null 2>&1
# | +------- Hour (0-23)                      | Multiple Values Use Commas: 3,12,47
# | | +----- Day Of Month (1-31)              | Do every X intervals: */X  -> Example: */15 * * * *  Is every 15 minutes
# | | | +--- Month (1 -12)                    | Aliases: @reboot -> Run once at startup; @hourly -> 0 * * * *;
# | | | | +- Day Of Week (0-6) (Sunday = 0)   | @daily -> 0 0 * * *; @weekly -> 0 0 * * 0; @monthly ->0 0 1 * *;
# | | | | |                                   | @yearly -> 0 0 1 1 *;
# 0 15 * * * your.command.goes.here for 3pm daily
/////////////////// here we add their weekly profit to their accounts //////////////
//$new_time = date("Y-m-d H:i:s", strtotime('-168 hours'));
$sql = "SELECT user_id,current_week_profit,plan_unique,balance,compound  FROM plan_account where active='1' and balance>'0' order by id asc";
$query11 = mysqli_query($db_conx, $sql);
$numrows=  mysqli_num_rows($query11);
if($numrows > 0){
    $count = 0;
	while($row = mysqli_fetch_array($query11, MYSQLI_ASSOC)) {
            $count ++;
            $user_id = $row['user_id'];
            $plan_unique = $row['plan_unique'];
            $current_week_profit= $row['current_week_profit'];
            $balance= $row['balance'];
            $compound= $row['compound'];
            $sql = "update plan_account set current_week_profit='0',balance=(balance +'$current_week_profit') where user_id='$user_id' and plan_unique='$plan_unique' limit 1";
            $query = mysqli_query($db_conx, $sql);
    }
    echo $count ." users were  worked on  <br/>";
}
/// here we add their referral amount to their profit //////////////
$sql11 = "SELECT user_id,referal_amount  FROM referral where referal_amount>'0' order by id asc";
$query11 = mysqli_query($db_conx, $sql11);
$numrows=  mysqli_num_rows($query11);
if($numrows > 0){
    $count = 0;   
    while($row = mysqli_fetch_array($query11, MYSQLI_ASSOC)) {		
        $user_id = $row['user_id'];
        $referal_amount = $row['referal_amount']; 
        $count ++;
        $sql = "SELECT full_name,email  FROM users where id='$user_id' limit 1";
        $query1 = mysqli_query($db_conx, $sql);
        $row2 = mysqli_fetch_row($query1);        
        $fn= $row2[0];
        $email= $row2[1];
        $sql = "update account set profit=(profit +'$referal_amount') where user_id='$user_id' limit 1";
        $query = mysqli_query($db_conx, $sql);
        $sql = "update referral set referal_amount='0' where user_id='$user_id' limit 1";
        $query = mysqli_query($db_conx, $sql);
    }
    echo $count ." users were worked on <br/>";
}