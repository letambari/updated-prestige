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
        
            $display .='<div class="col-xs-12 col-sm-12 " id="request_tab">
        <div class="card">
                <div class="card-header card-header-stretch">
                    <div class="card-title">
                        <h3>Members who have requested to make deposite (Deposit Request Tab)</h3>
                    </div>
                </div>
                <div class="card-body" >
                    <div class="table-responsive">
                        <table  class="table  table-striped table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr >
                                    <th >#</th>
                                    <th >User Details</th>
                                    <th >Transaction Details</th>
                                    <th >Payment Method</th> 
                                    <th >Add Funds</th>
                                    <th >Action</th>
                                </tr>
                            </thead>
                                <tfoot>
                                <tr >
                                    <th >#</th>
                                    <th >User Details</th>
                                    <th >Transaction Details</th>
                                    <th >Payment Method</th> 
                                    <th >Add Funds</th>
                                    <th >Action</th>
                                </tr>
                                </tfoot>
                            <tbody>';
                $sql = "SELECT user_id,currency,updated_at FROM account WHERE request_deposite='1' order by updated_at desc";
                $query = mysqli_query($db_conx, $sql);
                $numrows = mysqli_num_rows($query);
                if($numrows > 0){
                    $count = 0;
                    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                        
                        $id = $row['user_id'];
                        $currency_main = explode("|", $row['currency']);
                        $currency = $currency_main[0];
                        $symbol = end($currency_main);
                        $time = $row['updated_at'];
                        $sql1 = "SELECT amount,unique_field,payment_method,created_at FROM transactions WHERE user_id='$id' and transaction_type='Deposit' and status = '1' ";
                        $query1 = mysqli_query($db_conx, $sql1);
                        while ($row1 = mysqli_fetch_array($query1, MYSQLI_ASSOC)) {  
                            $count += 1;
                            $amount = $row1['amount'];
                            $tran_id = $row1['unique_field'];
                            $payment_method = $row1['payment_method'];
                            $time = $row1['created_at'];
                            $sql2 = "SELECT full_name,email,sponsor FROM users WHERE id='$id' limit 1";
                            $query2 = mysqli_query($db_conx, $sql2);
                            $row2 = mysqli_fetch_row($query2);
                            $fname = $row2[0];
                            $email = $row2[1];
                            $sponsor = $row2[2];
                            if($sponsor ==''){
                                $sponsor = 'none';
                            }
                            $display .='<tr>
                                <td > '.$count.'</td>
                                <td >   Email: <br/>'.$email.'<br/><br/>
                                        Sponsor: <br/>'.$sponsor.'
                                </td>
                                <td > ID: <br/>'.$tran_id.'<br/><br/>
                                        Amount: <br/>'.$symbol. number_format(preg_replace("/[^0-9,.]/", "",$amount),2).'
                                </td>
                                <td > Method: '.$payment_method.' <br/><br/>
                                        Date: <br/>'.$time.'
                                </td>
                                <td >
                                    <input type="text" id="'."$email$count".'f" value="'.preg_replace("/[^0-9.]/", "",$amount) .'" style="margin-right:10px; width:150px" placeholder="enter Amount" />
                                </td>
                                <td >
                                    <button class="btn btn-success mb-1 btn-sm" id="'.$tran_id.'_fa" onclick="fund_member(\''."$email$count".'f\',\''.$email.'\',\''."$tran_id".'\')" >Fund Account</button>
                                    <button class="btn btn-danger mb-1 btn-sm" id="'.$tran_id.'_cf" onclick="fund_cancel(\''.$email.'\',\''."$tran_id".'\')" >Cancel</button>
                                    <br/> 
                                    <a href="../secure/?action=sendPaymentDetails&email='.$email.'&amount='.$amount.'&name='.$fname.'" class="btn btn-info btn-sm">Send Payment Details</a>
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
?>
<?php 
/////////////////////////////////////////////
    $title ='Deposit Request | '.$site_name;
    $title2 ='Deposit Request';
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