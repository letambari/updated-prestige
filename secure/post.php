<?php
include_once("../wp-includes/php_/config.php");
if(!isset($profile_id) || $user_type=='member'){
    echo 'Access Denied';
    exit;
}
//////////////////////////////////////////////////////////////////////////
if(isset($_POST['function']) && $_POST['function'] == "fm"){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $a = mysqli_real_escape_string($db_conx, $_POST['a']);
    $email = mysqli_real_escape_string($db_conx, $_POST['m']);
    $tran_id = mysqli_real_escape_string($db_conx, $_POST['unq']);
    if($a == ""){
        echo "amount can not be empty.";
        mysqli_close($db_conx);
        exit();
    }else{
        // DUPLICATE DATA CHECKS 
        $sql = "SELECT id,full_name,active,sponsor  FROM users WHERE email='$email' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        if(!$query){
            echo mysqli_error($query);
            mysqli_close($db_conx);
            exit();
        }
        $row = mysqli_fetch_row($query);
        $id = $row[0];
        $fn = $row[1];
        $active = $row[2];
        $sponsor = $row[3];
        $sql = "SELECT balance,total_deposite,currency from account where user_id='$id' limit 1";
        $query = mysqli_query($db_conx, $sql);
        if(!$query){
            echo mysqli_error($query);
            mysqli_close($db_conx);
            exit();
        }
        $row = mysqli_fetch_row($query);
        $balance = $row[0];
        $t_deposit = $row[1];
        $currency_main = explode("|", $row[2]);
        $currency = $currency_main[0];
        $symbol = end($currency_main);
        //////////// heere we check if the transaction is still in waiting 
        $sql = "select id from transactions where status!='1' and user_id='$id' and transaction_type='Deposit' and unique_field='$tran_id'";
        $query = mysqli_query($db_conx, $sql);
        if(mysqli_num_rows($query) > 0){
            echo 'Transaction already completed';
            mysqli_close($db_conx);
            exit();
        }
        // END FORM DATA ERROR HANDLING
        // Begin Insertion of data into the database
        $new_time = date("Y-m-d H:i:s");
        $sql = "update users set payment_date='$new_time' where id='$id' limit 1";
        if($active < 1 ){
            $sql = "update users set active='1',payment_date='$new_time' where id='$id' limit 1";
        }
        $query = mysqli_query($db_conx, $sql);
        if(!$query){
            echo mysqli_error($query);
            mysqli_close($db_conx);
            exit();
        }
        $sql = "update account set active='1',balance=(balance + '$a'),last_deposite='$a',total_deposite=(total_deposite + '$a'),updated_at='$new_time' where user_id='$id' limit 1";
        $query = mysqli_query($db_conx, $sql);
        if(!$query){
            echo mysqli_error($query);
            mysqli_close($db_conx);
            exit();
        }
        $sql = "update transactions set status='2',completed_at='$new_time' where unique_field='$tran_id' and user_id='$id' limit 1";
        $query = mysqli_query($db_conx, $sql);
        if(!$query){
            echo mysqli_error($query);
            mysqli_close($db_conx);
            exit();
        }
//        if($sponsor !==""){
//            $sql = "SELECT id from users where email='$sponsor' limit 1";
//            $query = mysqli_query($db_conx, $sql);
//            $row = mysqli_fetch_row($query);
//            $s_id = $row[0];
//            $sql = "SELECT referal_amount,total_referal_amount,referal_percent from referral where user_id='$s_id' limit 1";
//            $query = mysqli_query($db_conx, $sql);
//            $row = mysqli_fetch_row($query);
//            $referal_amount = $row[0];
//            $total_referal_amount = $row[1];
//            $referal_rate = $row[2];
//            $referal_amount += ($a*$referal_rate);
//            $total_referal_amount += ($a*$referal_rate);
//            $sql = "update referral set referal_amount='$referal_amount',total_referal_amount='$total_referal_amount' where user_id='$s_id' limit 1";
//            $query = mysqli_query($db_conx, $sql);
//        }
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
                    Amount :  $'.$referal_amount.'  <br/>';
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
                account has been credited with the sum of '.$symbol.$a.'<br/>';
            if($active < 1 ){
                $body = 'Hello,<br> this is to notify you that your 
                account has been credited with the sum of '.$symbol.$a.'<br/>';
            }
            send_mail($email,$from,$subject,$body);
            echo 'success';
            mysqli_close($db_conx);
            exit();           
        } 
    }
}
////////// to Cancel account funding request 
//////////////////////////////////////////////////////////////////////////
if(isset($_POST['function']) && $_POST['function'] == "fund_cancel"){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $email = mysqli_real_escape_string($db_conx, $_POST['a']);
    $unq = mysqli_real_escape_string($db_conx, $_POST['unq']);
        // DUPLICATE DATA CHECKS 
        $sql = "SELECT id,full_name  FROM users WHERE email='$email' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $row = mysqli_fetch_row($query);
        $id = $row[0];
        $fn = $row[1];
        //////////// heere we check if the transaction is still in waiting 
        $sql = "select id from transactions where status='1' and user_id='$id' and transaction_type='Deposit' and unique_field='$unq'";
        $query = mysqli_query($db_conx, $sql);
        if(mysqli_num_rows($query) < 1){
            echo 'Transaction already completed';
            mysqli_close($db_conx);
            exit();
        }
        // END FORM DATA ERROR HANDLING
        // Begin Insertion of data into the database
        $new_time = date("Y-m-d H:i:s");
        $sql = "update account set request_deposite='0',updated_at='$new_time' where user_id='$id' limit 1";
        $query = mysqli_query($db_conx, $sql);
        $sql = "update transactions set status='0',completed_at='$new_time' where unique_field='$unq' and user_id='$id' and transaction_type='Deposit' limit 1";
        $query = mysqli_query($db_conx, $sql);
        $sql = "select id from transactions where status='1' and user_id='$id' and transaction_type='Deposit'";
        $query = mysqli_query($db_conx, $sql);
        if(mysqli_num_rows($query) > 0){
            $sql = "update account set request_deposite='1' where user_id='$id' limit 1";
            $query = mysqli_query($db_conx, $sql);
        }
        echo 'success';
        mysqli_close($db_conx);
        exit();
}
////////// to withdraw funds from members account 
//////////////////////////////////////////////////////////////////////////
if(isset($_POST['function']) && $_POST['function'] == "withdraw_m"){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $a = mysqli_real_escape_string($db_conx, $_POST['a']);
    $email = mysqli_real_escape_string($db_conx, $_POST['m']);
    $unq = mysqli_real_escape_string($db_conx, $_POST['uq']);
    if($a == ""){
        echo "amount can not be empty.";
        mysqli_close($db_conx);
        exit();
    }else{
        // DUPLICATE DATA CHECKS 
        $sql = "SELECT id,full_name  FROM users WHERE email='$email' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        if(!$query){
            echo mysqli_error($db_conx);
            exit();
        }
        $row = mysqli_fetch_row($query);
        $id = $row[0];
        $fn = $row[1];
        //////////// heere we check if the transaction is still in waiting 
        $sql = "select id,plan_unique,status from transactions where user_id='$id' and transaction_type='Withdrawal' and unique_field='$unq'";
        $query = mysqli_query($db_conx, $sql);
        if(mysqli_num_rows($query) < 1){
            echo 'Transaction already completed';
            mysqli_close($db_conx);
            exit();
        }
        $query = mysqli_query($db_conx, $sql);
        $row = mysqli_fetch_row($query);
        $withdraw_from = $row[1];
        $status = $row[2];
        if($status != 1){
            echo 'Transaction already completed';
            mysqli_close($db_conx);
            exit();
        }
        /////////////////////////////////////////////////
        $sql = "SELECT profit,total_withdrawal,total_deposite,balance,currency from plan_account where user_id='$id' and plan_unique='$withdraw_from' limit 1";
        if($withdraw_from == 'Wallet'){
            $sql = "SELECT profit,total_withdrawal,total_deposite,balance,currency from account where user_id='$id' limit 1";
        }
        $query = mysqli_query($db_conx, $sql);
        if(!$query){
            echo mysqli_error($db_conx);
            exit();
        }
        $row = mysqli_fetch_row($query);
        $profit = $row[0];
        $t_withdraw = $row[1];
        $total_deposite = $row[2];
        $balance = $row[3];
        $currency_main = explode("|", $row[4]);
        $currency = $currency_main[0];
        $symbol = end($currency_main);
        if($a > $profit && $withdraw_from != 'Wallet'){
            echo 'You can not pay a client more than their profit';
            exit();
        }
        // END FORM DATA ERROR HANDLING
        // Begin Insertion of data into the database
        $new_time = date("Y-m-d H:i:s");
        $sql = "update plan_account set profit=(profit - '$a'),last_withdrawal='$a',total_withdrawal=(total_withdrawal + '$a'),balance=(balance - '$a'),request_withdraw='0',last_update='$new_time' where user_id='$id' and plan_unique='$withdraw_from' limit 1";
        if(floatval($balance-$a) < floatval($total_deposite) ){
            $sql = "update plan_account set profit=(profit - '$a'),last_withdrawal='$a',total_withdrawal=(total_withdrawal + '$a'),request_withdraw='0',last_update='$new_time' where user_id='$id' and plan_unique='$withdraw_from' limit 1";        
        }
        if($withdraw_from == 'Wallet'){
            $sql = "update account set last_withdrawal='$a',total_withdrawal=(total_withdrawal + '$a'),balance=(balance - '$a'),request_withdraw='0',last_update='$new_time' where user_id='$id' limit 1";
//            if(floatval($balance-$a) < floatval($total_deposite) ){
//                $sql = "update account set profit=(profit - '$a'),last_withdrawal='$a',total_withdrawal=(total_withdrawal + '$a'),request_withdraw='0',last_update='$new_time' where user_id='$id' limit 1";        
//            }
        }
        $query = mysqli_query($db_conx, $sql);
        if(!$query){
            echo mysqli_error($db_conx);
            exit();
        }
        $sql = "update transactions set status='2',completed_at='$new_time' where unique_field='$unq' and user_id='$id' limit 1";
        $query = mysqli_query($db_conx, $sql);
        if(!$query){
            echo mysqli_error($db_conx);
            exit();
        }
        $sql = "select id from transactions where status='1' and user_id='$id' and transaction_type='Withdrawal'";
        $query = mysqli_query($db_conx, $sql);
        if(mysqli_num_rows($query) > 0){
            $sql = "update plan_account set request_withdraw='1' where user_id='$id' and plan_unique='$withdraw_from' limit 1";  
            if($withdraw_from == 'Wallet'){
                $sql = "update account set request_withdraw='1' where user_id='$id' limit 1";
            }
            $query = mysqli_query($db_conx, $sql);
        }
        //// mail member /////
        $subject = "Withdrawal Completed";
        $from = "$site_name_abbr <noreply@$site_link>";                        
        $body = 'Hello,<br> '
                . 'your withdrawal request has been processed.  <br/>
            Transaction Id <b>'.$unq.'</b><br/>
            Amount : <b>'.$symbol.$a.'</b>.<br/>
            Date : <b>'.$new_time.'</b>.<br/><br/>
            <span class="text-danger">Please do not reply to this mail as it is not monitored, any querry should be sent to <b>support@'.$site_link.'</b>.</span><br/>';
        send_mail($email,$from,$subject,$body);
        echo 'success';
        mysqli_close($db_conx);
        exit();
    }
}
////////// to Cancel withdraw funds request 
//////////////////////////////////////////////////////////////////////////
if(isset($_POST['function']) && $_POST['function'] == "withdraw_cancel"){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $email = mysqli_real_escape_string($db_conx, $_POST['a']);
    $unq = mysqli_real_escape_string($db_conx, $_POST['unq']);
    // DUPLICATE DATA CHECKS 
    $sql = "SELECT id,email,full_name  FROM users WHERE email='$email' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $row = mysqli_fetch_row($query);
    $id = $row[0];
    $fn = $row[1];
    //////////// here we check if the transaction is still in waiting 
    $sql = "select id from transactions where status='1' and user_id='$id' and transaction_type='Withdrawal' and unique_field='$unq'";
    $query = mysqli_query($db_conx, $sql);
    if(mysqli_num_rows($query) < 1){
        echo 'Transaction already cancelled';
        mysqli_close($db_conx);
        exit();
    }
    // END FORM DATA ERROR HANDLING
    // Begin Insertion of data into the database
    $new_time = date("Y-m-d H:i:s");
    $sql = "update account set request_withdraw='0',updated_at='$new_time' where user_id='$id' limit 1";
    $query = mysqli_query($db_conx, $sql);
    $sql = "update transactions set status='0',completed_at='$new_time' where unique_field='$unq' and user_id='$id' limit 1";
    $query = mysqli_query($db_conx, $sql);
    $sql = "select id from transactions where status='1' and user_id='$id' and transaction_type='Withdrawal'";
    $query = mysqli_query($db_conx, $sql);
    if(mysqli_num_rows($query) > 0){
        $sql = "update account set request_withdraw='1' where user_id='$id' limit 1";
        $query = mysqli_query($db_conx, $sql);
    }
    echo 'success';
    mysqli_close($db_conx);
    exit();
}

/////////////////////////////////////////
if(isset($_POST['function']) && $_POST['function'] == "update_m_portfolio"){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $port_id = mysqli_real_escape_string($db_conx, $_POST['port']);
    $trade_plan = mysqli_real_escape_string($db_conx, $_POST['trade_plan']);
    $profit = mysqli_real_escape_string($db_conx, $_POST['pr']);
    $currency = mysqli_real_escape_string($db_conx, $_POST['currency']);
    $acct_type = mysqli_real_escape_string($db_conx, $_POST['acct_type']);
    $daily_profit_rate = mysqli_real_escape_string($db_conx, $_POST['dpr']);
    $ref_bonus = mysqli_real_escape_string($db_conx, $_POST['refbonus']);
    $last_deposit = mysqli_real_escape_string($db_conx, $_POST['ld']);
    $t_deposit = mysqli_real_escape_string($db_conx, $_POST['td']);
    $l_withdraw = mysqli_real_escape_string($db_conx, $_POST['lw']);
    $t_withdraw = mysqli_real_escape_string($db_conx, $_POST['tw']);
    $bal = mysqli_real_escape_string($db_conx, $_POST['bal']);
    $un = mysqli_real_escape_string($db_conx, $_POST['un']);
    if($port_id == ""||$trade_plan == ""||$profit == ""||$ref_bonus == ""||$last_deposit == ""||$t_deposit == ""||$l_withdraw == ""||$t_withdraw == ""){
        echo "all fields must be filled.";
        mysqli_close($db_conx);
        exit();
    }else{
        $sql = "SELECT id FROM users WHERE email='$un' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $numrows1 = mysqli_num_rows($query);
        if($numrows1 < 1){
            echo "User Not Found.";
            mysqli_close($db_conx);
            exit();
        }
        $row = mysqli_fetch_row($query);
        $id = $row[0];
        // END FORM DATA ERROR HANDLING
        // Begin Insertion of data into the database
        $new_time = date("Y-m-d H:i:s");
        $days = 20;
        // 20days
        $plan = substr($trade_plan,1,8);
        $start = strpos($plan,"(")+1;
        $end = 6;
        $plan_tire = substr($plan,$start,$end);
        $referal_rate = 0.1;
        $daily_roi = 0.01;        
        if($plan_tire == "Tier 2"){
            $daily_roi = 0.0125;
        }else if($plan_tire == "Tier 3"){
            $daily_roi = 0.015;
        }else if($plan_tire == "Tier 4"){
            $daily_roi = 0.0175;
        }
        $monthly_roi = $daily_roi * $days;
        $sql = "update plan_account set plan='$trade_plan',compound='$acct_type',daily_profit_rate='$daily_roi'"
                . ",monthly_profit_rate='$monthly_roi',last_deposite='$last_deposit',total_deposite='$t_deposit',"
                . "last_withdrawal='$l_withdraw',total_withdrawal='$t_withdraw',profit='$profit',currency='$currency',"
                . "balance='$bal',last_update='$new_time' where user_id='$id' and plan_unique='$port_id' limit 1";
        $query = mysqli_query($db_conx, $sql);
        $sql = "update referral set referal_amount='$ref_bonus',referal_percent='$referal_rate' where user_id='$id' limit 1";
        $query = mysqli_query($db_conx, $sql);
        if($query){
            echo 'success';
            mysqli_close($db_conx);
            exit(); 
        }
        echo mysqli_error($db_conx);
        exit();
        mysqli_close($db_conx);
    }
}
/////////////////////////////////////////
if(isset($_POST['function']) && $_POST['function'] == "update_m_acct"){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $currency = mysqli_real_escape_string($db_conx, $_POST['currency']);
    $ref_bonus = mysqli_real_escape_string($db_conx, $_POST['refbonus']);
    $last_deposit = mysqli_real_escape_string($db_conx, $_POST['ld']);
    $t_deposit = mysqli_real_escape_string($db_conx, $_POST['td']);
    $l_withdraw = mysqli_real_escape_string($db_conx, $_POST['lw']);
    $t_withdraw = mysqli_real_escape_string($db_conx, $_POST['tw']);
    $bal = mysqli_real_escape_string($db_conx, $_POST['bal']);
    $un = mysqli_real_escape_string($db_conx, $_POST['un']);
    if($ref_bonus == ""||$last_deposit == ""||$t_deposit == ""||$l_withdraw == ""||$t_withdraw == ""){
        echo "all fields must be filled.";
        mysqli_close($db_conx);
        exit();
    }else{
        $sql = "SELECT id  FROM users WHERE email='$un' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $row = mysqli_fetch_row($query);
        $id = $row[0];
        // END FORM DATA ERROR HANDLING
        // Begin Insertion of data into the database
        $new_time = date("Y-m-d H:i:s");
        $sql = "update account set last_deposite='$last_deposit',total_deposite='$t_deposit',last_withdrawal='$l_withdraw',"
                . "total_withdrawal='$t_withdraw',currency='$currency',"
                . "balance='$bal',last_update='$new_time' where user_id='$id' limit 1";
        $query = mysqli_query($db_conx, $sql);
        $sql = "update referral set referal_amount='$ref_bonus' where user_id='$id' limit 1";
        $query = mysqli_query($db_conx, $sql);
        if($query){
            echo 'success';
            mysqli_close($db_conx);
            exit(); 
        }
        echo mysqli_error($db_conx);
        exit();
        mysqli_close($db_conx);
    }
}
///////////// Individual Update /////////
if(isset($_POST['function']) && $_POST['function']=='update_btn' && isset($_POST['id']) && isset($_POST['value'])){
    $control = mysqli_real_escape_string($db_conx, $_POST['value']);
    $id = mysqli_real_escape_string($db_conx, $_POST['id']);
    $tbl = $_POST['tbl'];
    $sql = "UPDATE account set auto_update='$control' WHERE  user_id='$id' limit 1 ";
    if($tbl!='acct'){
        $sql = "UPDATE plan_account set auto_update='$control' WHERE  user_id='$id' and plan_unique='$tbl' limit 1 ";
    }
    $query = mysqli_query($db_conx, $sql);
    if($query){ 
        echo 1;
        exit();
    }else{
        echo mysqli_error($db_conx);
        mysqli_close($db_conx);
        exit();
    }
} 
if(isset($_POST['function']) && $_POST['function'] =='paymentdetails'){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $e = mysqli_real_escape_string($db_conx, $_POST['e']);
    $d = $_POST['d'];
    $a = mysqli_real_escape_string($db_conx, $_POST['a']);
    $p = $_POST['p'];
    if($e == "" || $a == ""  || $d == ""|| $p == ""){
        echo "The form submission is missing values.";
        exit();
    }else {
        $unique = $_SESSION[$site_cokie];
        $sql = "SELECT full_name,email  FROM users WHERE unique_field='$unique' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $row = mysqli_fetch_row($query);
        $fname = $row[0];
        $email = $row[1];
            $subject = "Funding  Details";
            $from = "$site_name_abbr <support@$site_link>";
            $body = 'Hello,<br/>
                this is an e-mail response to your request of making a deposit of <b>$'.$a.'</b>,<br/>
                Payment Method : '.$d.',<br/>
                Payment Details : '.$p.',<br/><br/>
                Note that it takes 0 - 48 hours depending on the mode of deposit for your funds to 
                reflect on your account.';
            send_mail($e,$from,$subject,$body);
            $body = 'Hello, an admin with the following details<br/>
                e-mail address:'.$email.' <br/>
                First Name : '.$fname.' ,<br/>
                Sent the following payment details to a client with <br/>
                E-mail : '.$e.'<br/>
                <b> Payment Details </b><br/>
                Amount : '.$a.',<br/>
                Payment Method : '.$d.',<br/>
                Payment Details : '.$p.',<br/><br/>';
            send_mail("support@$site_link",$email,$subject,$body);
            echo "success";
            mysqli_close($db_conx);
            exit();
        }
}
if(isset($_POST['function']) && $_POST['function'] =='sendMail'){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $e = mysqli_real_escape_string($db_conx, $_POST['e']);
    $m = $_POST['m'];
    $s = preg_replace('#[^a-z 0-9]#i', '', ucwords($_POST['s']));
    $n = preg_replace('#[^a-z 0-9]#i', '', ucwords($_POST['n']));
    if($e == "" || $m == ""  || $s == ""|| $n == ""){
        echo "The form submission is missing values.";
        exit();
    }else {
            $from = "$site_name_abbr <support@$site_link>";
            $body = $m;
            send_mail($e,$from,$s,$body);
            echo "success";
            mysqli_close($db_conx);
            exit();
        }
}
////////// to update sponsor emails
//////////////////////////////////////////////////////////////////////////
if(isset($_POST['function']) && $_POST['function'] == "Update_sponsor"){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $unique_code = mysqli_real_escape_string($db_conx, $_POST['unique_code']);
    $s_email = mysqli_real_escape_string($db_conx, $_POST['s_email']);
    // DUPLICATE DATA CHECKS 
    if(''==$s_email){
        $sql = "update users set sponsor='$s_email' where unique_field='$unique_code' limit 1";
        $query = mysqli_query($db_conx, $sql);
        if($query){
            echo 'success';
        }else{
            echo mysqli_error($db_conx);
        } 
        mysqli_close($db_conx);
    exit();
    }
    // DUPLICATE DATA CHECKS 
    $sql = "SELECT id  FROM users WHERE unique_field='$unique_code' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    if(mysqli_num_rows($query) < 1){
        echo 'Account does not exist';
        exit();
    }
    // DUPLICATE DATA CHECKS 
    $sql = "SELECT id  FROM users WHERE email='$s_email' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    if(mysqli_num_rows($query) < 1){
        echo 'Sponsor does not exist';
        exit();
    }
    // END FORM DATA ERROR HANDLING
    $sql = "update users set sponsor='$s_email' where unique_field='$unique_code' limit 1";
    $query = mysqli_query($db_conx, $sql);
    if($query){
        echo 'success';
    }else{
        echo mysqli_error($db_conx);
    }    
    mysqli_close($db_conx);
    exit();
}
//////////////////////////////////////////////////////////////////////////
if(isset($_POST['function']) && $_POST['function'] == "Update_wallet"){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $id = mysqli_real_escape_string($db_conx, $_POST['id']);
    $address = mysqli_real_escape_string($db_conx, $_POST['w_address']);
    $dep_disp = mysqli_real_escape_string($db_conx, $_POST['dep_disp']);
    $with_disp = mysqli_real_escape_string($db_conx, $_POST['with_disp']);
    // DUPLICATE DATA CHECKS 
    $unique = $_SESSION[$site_cokie]; 
    if(''==$address){
        echo 'Wallet Address can not be empty...';
        exit();
    }
    $sql = "SELECT id,email FROM users WHERE unique_field='$unique' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $unique_check = mysqli_num_rows($query);
    ////////////////////////
    $row = mysqli_fetch_row($query);
    $u_id = $row[0];
    $email = $row[1];
    // END FORM DATA ERROR HANDLING
    // Begin Insertion of data into the database
    $new_time = date("Y-m-d H:i:s");
    $sql = "update wallets set wallet_address='$address',dep_display='$dep_disp',with_display='$with_disp',username='$email',date_added='$new_time' where id='$id' and wallet_type='Admin' limit 1";
    $query = mysqli_query($db_conx, $sql);
    if($query){
        echo 'success';
        mysqli_close($db_conx);
        exit();
    }else{
        echo mysqli_error($db_conx);
        mysqli_close($db_conx);
        exit();
    }
            
}
////////// to remove wallet from site 
//////////////////////////////////////////////////////////////////////////
if(isset($_POST['function']) && $_POST['function'] == "remove_wallet"){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $id = mysqli_real_escape_string($db_conx, $_POST['id']);
    // DUPLICATE DATA CHECKS 
    $sql = "SELECT wallet_address  FROM wallets WHERE id='$id'  AND wallet_type='Admin' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    if(mysqli_num_rows($query) < 1){
        echo 'Wallet does not exist';
        exit();
    }
    $row = mysqli_fetch_row($query);
    $address = $row[0];
    /////////////////////////////////////////
    $unique = $_SESSION[$site_cokie]; 
    $sql = "SELECT id,email FROM users WHERE unique_field='$unique' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $unique_check = mysqli_num_rows($query);
    ////////////////////////
    $row = mysqli_fetch_row($query);
    $u_id = $row[0];
    $email = $row[1];
    /////////////////////////////////////////////
    mysqli_query($db_conx, "DELETE FROM wallets WHERE id='$id' AND wallet_type='Admin' LIMIT 1");
    echo 'success';
    mysqli_close($db_conx);
    exit();
}
  ///////////////////////////////////////////////////
////////// to remove member from site 
//////////////////////////////////////////////////////////////////////////
if(isset($_POST['function']) && $_POST['function'] == "remove_member"){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $e = mysqli_real_escape_string($db_conx, $_POST['e']);
        // DUPLICATE DATA CHECKS 
        $sql = "SELECT id,email  FROM users WHERE email='$e' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        if(mysqli_num_rows($query) < 1){
            echo 'Account does not exist';
            exit();
        }
		$row = mysqli_fetch_row($query);
		$id = $row[0];
		$username = $row[1];
	  $userFolder = "../wp-includes/users/$username";
	  if(is_dir($userFolder)) {
                rmdir($userFolder);
            }
			mysqli_query($db_conx, "DELETE FROM users WHERE id='$id' AND email='$username' LIMIT 1");
			mysqli_query($db_conx, "DELETE FROM account WHERE user_id='$id'  LIMIT 1");
			mysqli_query($db_conx, "DELETE FROM referral WHERE user_id='$id'  LIMIT 1");
			mysqli_query($db_conx, "DELETE FROM random_string WHERE user_id='$id'  LIMIT 1");
        echo 'success';
        mysqli_close($db_conx);
        exit();
}
  ///////////////////////////////////////////////////
 //  Scripts to swap account  //
///////////////////////////////////////////////////
if(isset($_POST['function']) && $_POST['function'] == "verify_account"){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $id =mysqli_real_escape_string($db_conx, $_POST['u']);
    $c =mysqli_real_escape_string($db_conx, $_POST['code']);
    if( $id == ""){
        echo "Error, please reload the page and try again.";
        exit();
    }else{
        $new_time = date("Y-m-d H:i:s");
        $sql = "UPDATE users set active='$c',payment_date='$new_time' WHERE id='$id' limit 1";
        $query = mysqli_query($db_conx, $sql);
        if($query){
            $sql = "UPDATE account set active='$c' WHERE user_id='$id' limit 1";
            $query = mysqli_query($db_conx, $sql);
            if($query){
                echo 'success';
                exit();
                mysqli_close($db_conx);
            }                
        }else{
            echo 'Reload the page and try again.';
            exit();
            mysqli_close($db_conx);
        }
    }
}
///////////// Make Admin /////////
if(isset($_POST['function']) && $_POST['function'] == "admin_swap" && isset($_POST['u']) && isset($_POST['value'])){
    $control = mysqli_real_escape_string($db_conx, $_POST['value']);
    $id = mysqli_real_escape_string($db_conx, $_POST['u']);
    if($control == 1){
        $sql = "UPDATE users set user_type='admin' WHERE  id='$id' limit 1 ";
        $query = mysqli_query($db_conx, $sql);
    }else{
        $sql = "UPDATE users set user_type='member' WHERE  id='$id' limit 1 ";
        $query = mysqli_query($db_conx, $sql);
    }    
    if($query){ 
        echo 'success';
        exit();
    }else{
        echo mysqli_error($db_conx);
        mysqli_close($db_conx);
        exit();
    }
} 