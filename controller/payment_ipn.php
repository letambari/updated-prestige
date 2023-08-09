<?php
include_once("../wp-includes/php_/config.php");
//////////////////////////////////////////////////////////////////////////
// to confirm payments
if(isset($_POST['payment_id']) && isset($_POST['network']) && $_POST['network'] != ""){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $payment_id = mysqli_real_escape_string($db_conx, $_POST['payment_id']);
    $payment_Status = mysqli_real_escape_string($db_conx, $_POST['payment_status']);
    /// first we check the transaction status in the local DB
    $sql = "select user_id,status,amount from transactions where transaction_type='Deposit' and payment_id='$payment_id' limit 1";
    $query = mysqli_query($db_conx, $sql);
    if(mysqli_num_rows($query) > 0){
        $row = mysqli_fetch_row($query);
        $id = $row[0];
        $status = $row[1];
        $a = $row[2];  
        if($status == 1){
            if($payment_Status == 'confirmed' || $payment_Status == 'sending' ){
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
                        account has been credited with the sum of $'.$a.'<br/>';
                    send_mail($email,$from,$subject,$body);
                } 
                echo 2;
                mysqli_close($db_conx);
                exit(); 
            }else{
                echo 3;
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
}