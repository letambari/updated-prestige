<?php
include_once("../controller/dependent.php");
// Initialize any variables that the page might echo

$js='';
$sql = "SELECT auto FROM update_control where id='1' LIMIT 1";
$query = mysqli_query($db_conx, $sql);
if(mysqli_num_rows($query) < 1){
	/////////// add the control ////////////////
	$sql = "INSERT INTO update_control (auto)";
	$sql .= "VALUES('1')";
	$query = mysqli_query($db_conx, $sql);
        echo 'success';
}
if(isset($_POST['update_btn']) && isset($_POST['control'])){
    $control = $_POST['control'];
    $sql = "UPDATE update_control set auto='$control' WHERE  id='1' ";
    $query = mysqli_query($db_conx, $sql);
} 
///////////// Individual Update /////////
if(isset($_POST['function']) && isset($_POST['id']) && isset($_POST['value'])){
    $control = $_POST['value'];
    $id = $_POST['id'];
    $sql = "UPDATE account set auto_update='$control' WHERE  user_id='$id' limit 1 ";
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

// variables for those who are to pay 
if(!isset($_SESSION[$site_cokie])){
    header("location: ../login");
    exit;
}else{
    if($user_type == 'member'){
        header("location: ../dashboard");
        exit;
    }
    $identifier = $_SESSION[$site_cokie];
    $sql = "SELECT id,verified FROM users WHERE unique_field='$identifier' LIMIT 1";
    $user_query = mysqli_query($db_conx, $sql);
    // Now make sure that user exists in the table
    $numrows = mysqli_num_rows($user_query);
    if($numrows < 1){
        session_start();
        // Set Session data to an empty array
        $_SESSION = array();
        // Expire their cookie files
        if(isset($_COOKIE[$site_cokie])) {
            setcookie($site_cokie, '', strtotime( '-5 days' ), "/");
        }
        session_unset(); 
        // Destroy the session variables
        session_destroy();
        header("location: ../login");
        exit();	
    }
}
// Select the member from the users table
$sql = "SELECT * FROM users WHERE unique_field='$identifier' LIMIT 1";
$user_query = mysqli_query($db_conx, $sql);
// Now make sure that user exists in the table
$numrows = mysqli_num_rows($user_query);
if($numrows < 1){
    header("location: ../login");
    exit();	
}
// Fetch the user row from the query above
while ($row = mysqli_fetch_array($user_query, MYSQLI_ASSOC)) {
    $f_n = $row['full_name'];
    $email = $row['email'];
    $tel = $row['phone'];
}
if($user_type == 'member'){
       header("location: ../dashboard");
    exit();
}
if($user_type !== 'member'){
//            include_once("functions.php");            
	$display = '';
        if($user_type == 'admin'){
                //////////////// to get members who request to withdraw /////////////
                $display .='<div class="col-xs-12 col-sm-12 " id="withdrawal_tab">
        <div class="card">
                <div class="card-header card-header-stretch">
                    <div class="card-title">
                        <h3>Members who have requested to Withdraw Funds  (Withdraw Request Tab)</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-head-bg-danger  table-striped table-bordered"   width="100%" cellspacing="0">
                            <thead >
                                <tr >
                                    <th >S/n</th>
                                    <th>Details</th>
                                    <th>Transactin Details</th>
                                    <th>Payment Details</th> 
                                    <th>Account Details</th>
                                    <th>Sub Funds</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
							<tfoot>
                                <tr >
                                    <th >S/n</th>
                                    <th>Details</th>
                                    <th>Transactin Details</th>
                                    <th>Payment Details</th> 
                                    <th>Account Details</th>
                                    <th>Sub Funds</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>';
                $sql = "SELECT user_id,payment_method,plan_unique,payment_details,amount,unique_field,created_at FROM transactions WHERE status='1' ";
                $query = mysqli_query($db_conx, $sql);
                $numrows = mysqli_num_rows($query);
                if($numrows > 0){
                    $count = 0;
                    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) { 
                        $count += 1;
                        $id = $row['user_id'];  
                        $payment_method = $row['payment_method'];
                        $amount = $row['amount'];                        
                        $withdraw_from = $row['plan_unique'];
                        $payment_details = $row['payment_details'];
                        $tran_id = $row['unique_field'];
                        $time = $row['created_at'];                        
                        $sql1 = "SELECT profit,currency,balance FROM account WHERE user_id='$id' and request_withdraw='1' order by last_update limit 1";
                        if($withdraw_from != 'Wallet'){
                            $sql3 = "SELECT plan from plan_account where user_id='$id' and plan_unique='$withdraw_from' limit 1";
                            $query3 = mysqli_query($db_conx, $sql3);
                            if(mysqli_num_rows($query3) > 0){
                                $row3 = mysqli_fetch_row($query3);
                                $withdraw_from = $row3[0] . "<br/>($withdraw_from)";
                            }
                            $sql1 = "SELECT profit,currency,balance FROM plan_account WHERE user_id='$id' and plan_unique='$withdraw_from' and request_withdraw='1' order by last_update limit 1";
                        }    
                        $plan = $withdraw_from;
                        $query1 = mysqli_query($db_conx, $sql1);                     
                        while ($row1 = mysqli_fetch_array($query1, MYSQLI_ASSOC)) {  
                            $profit = $row1['profit'];
                            $balance = $row1['balance'];
                            $currency_main = explode("|", $row1['currency']);
                            $currency = $currency_main[0];
                            $symbol = end($currency_main);
                            $sql2 = "SELECT full_name,email,sponsor FROM users WHERE id='$id' limit 1";
                            $query2 = mysqli_query($db_conx, $sql2);
                            $row2 = mysqli_fetch_row($query2);
                            $fname = $row2[0];
                            $email = $row2[1];
                            $sponsor = $row2[2];
                            $status_dis = '<span class="badge badge-warning">Pending</span>';
                            $display .='<tr>
                                <td > '.$count.'</td>
                                <td>
                                    <b>E-mail</b><br/> '.$email.'<br/><br/>
                                    <b>Plan</b><br/> '.$plan.'<br/><br/>
                                    <b>Sponsor</b><br/> '.$sponsor.'
                                </td>
                                <td > 
                                    ID: <br/>'.$tran_id.'<br/><br/>
                                    <b>Amount</b><br/> $'.$amount.'<br/><br/>
                                    
                                    <b>Withdraw From:</b><br/> '.$withdraw_from.'
                                </td>
                                <td >
                                    Method:<br/>'.$payment_method.'<br/><br/>
                                    <b>Address</b><br/>'.$payment_details.'<br/><br/>
                                    <b>Date</b>:<br/> '.$time.'
                                </td>
                                <td > 
                                    <b>Total Profit</b><br/>'.$symbol.$profit.'<br/><br/>
                                    <b>Balance</b><br/> '.$symbol.$balance.'<br/><br/>
                                    <b>Status</b> '.$status_dis.'
                                </td>
                                <td ><input type="text" id="'."$email$count".'w"  value="'.preg_replace("/[^0-9.]/", "",$amount).'" style="margin-right:10px; width:100px" placeholder="enter Amount" />
                                    </td>
                                <td>
                                    <button class="btn btn-success btn-sm mb-1" id="'.$tran_id.'_aw" onclick="withdraw_member(\''."$email$count".'w\',\''.$email.'\',\''."$tran_id".'\')" >Withdraw</button>
                                    <br/> 
                                    <button id="'.$tran_id.'_cw" onclick="withdraw_cancel(\''.$email.'\',\''."$tran_id".'\')" class="btn btn-danger btn-sm">Cancel</button>
                                </td>
                            </tr>';
                        }
                    }
                    
                }else{
                $display .='<tr>
                                <td></td> <td colspan="6"> This list is empty for now. .</td>
                        </tr>';
                }
                $display .= '</tbody>
                    </table></div></div>
                    <div class="card-footer small text-muted"></div></div></div>';
                }
                
}
?>
<?php 
/////////////////////////////////////////////
    $title ='Withdrawal Request | '.$site_name;
    $title2 ='Withdrawal Request';
    $keyword ='';
    $discription = ''; 
    $dash = '';
    $dep = '';
    $pricing = '';
    $pur_invest = '';
    $my_invest = '';
    $with = '';
    $act_his = '';
    $ref = '';
    $acct_pro = ''; 
    $acct_settings = ''; 
    $acct_security = '';
    $suppt = '';
    include_once '../user_header.php';
/////////////////////////////////////////////
?>
<?php include_once 'admin_body.php';?>