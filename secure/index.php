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
$display = '';
if($user_type !== 'member'){
//            include_once("functions.php");            
	
                // to get members who have deposited already ////
                ////////////////////////////////////////////////                
                $display .= '<div class="col-xs-12 col-sm-12 " id="deposit_tab">
        <div class="card mb-3">
                <div class="card-header card-header-stretch">
                    <div class="card-title">
                        <h3>Active Investment</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-head-bg-info  table-striped table-bordered"  cellspacing="0">
                            <thead class="border-bottom border-gray-200 fs-6 fw-bold bg-lighten">
                                <tr >
                                    <th class="">#</th>
                                    <th class=" px-0">Member Details</th> 
                                    <th class="">Portfolio Details</th>
                                    <th class="">Profit</th>
                                    <th class="">Invested</th>
                                    <th class="">Withdrawal</th>
                                    <th class="">Balance</th>
                                    <th class=" ps-0">Action</th>
                                </tr>
                            </thead>
                            <tfoot  class="border-bottom border-gray-200 fs-6 fw-bold bg-lighten">
                                <tr >
                                    <th class="">#</th>
                                    <th class=" px-0">Member Details</th> 
                                    <th class="">Portfolio Details</th>
                                    <th class="">Profit</th>
                                    <th class="">Invested</th>
                                    <th class="">Withdrawal</th>
                                    <th class="">Balance</th>
                                    <th class=" ps-0">Action</th>
                                </tr>
                            </tfoot>
                            <tbody>';
                $sql = "SELECT id,unique_field,full_name,email,sponsor,payment_date FROM users WHERE active='1' order by payment_date";
                $query = mysqli_query($db_conx, $sql);
                $numrows = mysqli_num_rows($query);
                if($numrows > 0){
                    $count = 0;
                    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                        
                        $id = $row['id'];
                        $unique_id = $row['unique_field'];
                        $fname = $row['full_name'];
                        $email = $row['email'];
                        $sponsor = $row['sponsor'];
                        
                        $sql1 = "SELECT * FROM plan_account WHERE user_id='$id' and active='1'";
                        $query1 = mysqli_query($db_conx, $sql1);
                        $numrows1 = mysqli_num_rows($query1);
                        if($numrows1 > 0){
                            while ($row1 = mysqli_fetch_array($query1, MYSQLI_ASSOC)) {   
                                $count += 1;
                                $plan_unique = $row1['plan_unique'];
                                $profit = $row1['profit'];
                                $current_week_profit = $row1['current_week_profit'];
                                $sector = $row1['sector'];
                                $plan = $row1['plan'];                                
                                $currency_main = explode("|", $row1['currency']);
                                $currency = $currency_main[0];
                                $symbol = end($currency_main);
                                $compound= $row1['compound'];
                                $date = $row1['created_at'];
                                $acct_type='<span class="badge badge-primary">Normal</span>';
                                $acct_type_val='0';
                                if($compound ==='1'){
                                    $acct_type = '<span class="badge badge-success">Compounding</span>';
                                        $acct_type_val='1';
                                }
                                $auto_update= $row1['auto_update'];
                                $update_btn ='<button class="btn waves-effect waves-float waves-light btn-sm  btn-primary  donate_btn"  id="'.$id.'_update"
                                    onclick="update_switch(\''.$id.'\',\'1\',\''.$plan_unique.'\')" >
                                        ON UPDATE.</button>';
                                if($auto_update > 0){
                                    $update_btn ='<button class="btn waves-effect waves-float waves-light btn-sm btn-danger  donate_btn"  id="'.$id.'_update"
                                    onclick="update_switch(\''.$id.'\',\'0\',\''.$plan_unique.'\')" >
                                        OFF UPDATE.</button>';
                                }
                                $last_withdrawal = $row1['last_withdrawal'];
                                $total_withdrawal = $row1['total_withdrawal'];
                                $last_deposite = $row1['last_deposite'];
                                $total_deposite = $row1['total_deposite'];
                                $balance = $row1['balance'];
                                $days = 5;
                                $d_profit_rate = $row1['daily_profit_rate'];
                                $w_profit_rate = $d_profit_rate * $days;
                                $m_profit_rate = $row1['monthly_profit_rate'];
                                $sql2 = "SELECT referal_amount FROM referral WHERE user_id='$id' LIMIT 1";
                                $query2 = mysqli_query($db_conx, $sql2);
                                $row2 = mysqli_fetch_array($query2, MYSQLI_ASSOC);
                                $bonus = $row2['referal_amount'];
                                $display .='<tr>
                                    <td>'.$count.'</td>
                                    <td>
                                        <b>Member ID</b><br/>'.$unique_id.'<br/><br/>
                                        <b>Full Name</b><br/>'.$fname.'<br/><br/>
                                        <b>E-mail</b><br/>'.$email.'<br/><br/>
                                        <b>Sponsor</b><br/>'.$sponsor.'<br/><br/>
                                    </td>
                                    <td>
                                        <b>ID</b><br/>'.$plan_unique.'<br/><br/>
                                        <b>Plan</b><br/>'.$plan.'<br/>
                                        <select class="input-material" name="acct_plan" id="'.$plan_unique.'_trade_plan">
                                            <option value="'.$plan.'" >'.$plan.'</option>
                                            <option  value="">Investment Plan</option>
                                            <option value="Crypto IRA">Crypto IRA</option>
                                            <option value="PCAP (Tier 1)">PCAP (Tier 1)</option>
                                            <option value="PCAP (Tier 2)">PCAP (Tier 2)</option>
                                            <option value="PCAP (Tier 3)">PCAP (Tier 3)</option>
                                            <option value="PCAP (Tier 4)">PCAP (Tier 4)</option>                                                        
                                            <option value="PSP (Tier 1)">PSP (Tier 1)</option>
                                            <option value="PSP (Tier 2)">PSP (Tier 2)</option>
                                            <option value="PSP (Tier 3)">PSP (Tier 3)</option>
                                            <option value="PSP (Tier 4)">PSP (Tier 4)</option>                                                        
                                            <option value="PCPP (Tier 1)">PCPP (Tier 1)</option>
                                            <option value="PCPP (Tier 2)">PCPP (Tier 2)</option>
                                            <option value="PCPP (Tier 3)">PCPP (Tier 3)</option>
                                            <option value="PCPP (Tier 4)">PCPP (Tier 4)</option>
                                        </select><br/><br/>
                                           <b>Currency</b><br/>'.$currency.' ('.$symbol.')<br/>
                                           <select class="input-material" name="currency" id="'.$plan_unique.'_currency">
                                                   <option value="'.$currency.'|'.$symbol.'" >'.$currency.' ('.$symbol.')</option>
                                                   <option value="">Select Currency</option>
                                                   <option value="USD|$">USD $</option>
                                                   <option value="EURO|€">EURO €</option>
                                                   <option value="POUND|£">POUND £</option>
                                           </select><br/><br/>                                       
                                        <b>Account Type</b>
                                        '.$acct_type.' <br/>
                                            <select class="input-material" name="acct_type" id="'.$plan_unique.'_acct">
                                                    <option value="'.$acct_type_val.'" >'.$acct_type.'</option>
                                                    <option value="">Swith  Account</option>
                                                    <option value="0">Normal</option>
                                                    <option value="1">Compounding</option>
                                            </select>
                                    </td>
                                    <td>
                                        <b>Profit</b><br/> 
                                        <input type="text" id="'.$plan_unique.'_profit" value="'.$profit.'" style="width:50%" placeholder="Profit" />
                                        <br/><br/>
                                        <b>Referral Bonus</b><br/> 
                                        <input type="text" id="'.$plan_unique.'_b" value="'.$bonus.'" style="width:100px" placeholder="Referal bonus" />
                                        <br/>
                                        <br/>
                                        <b>Weekly Profit Rate</b><br/>
                                        <select id="'.$plan_unique.'_adpr"  style="width:200px" >
                                            <option value="'.$d_profit_rate.'" >'.$d_profit_rate *100*$days .'%</option>
                                            <option value=""> Weekly profit rate </option>
                                            <option value="0.008">4%</option>
                                            <option value="0.010">5%</option>
                                            <option value="0.013">6.5%</option>
                                            <option value="0.012">6%</option>
                                            <option value="0.016">8%</option>
                                            <option value="0.02">10%</option>
                                            <option value="0.024">12%</option>
                                            <option value="0.028">14%</option>
                                            <option value="0.032">16%</option> 
                                            <option value="0.036">18%</option>
                                            <option value="0.04">20%</option>
                                            <option value="0.046">23%</option>
                                            <option value="0.05">25%</option>
                                            <option value="0.056">28%</option>
                                            <option value="0.06">30%</option>   
                                        </select>
                                        <br/>
                                        <br/>
                                        <b>Monthly Profit Rate</b><br/>
                                        <b>'.$m_profit_rate *100 .'%</b>
                                    </td>
                                    <td>
                                        <b>Last Deposit</b>
                                        <br/> 
                                        <input type="text" id="'.$plan_unique.'_c" value="'.$last_deposite.'" style="width:100px" placeholder="Last Deposit" />
                                        <br/><br/>
                                        <b>Total Deposit</b>
                                        <br/> 
                                        <input type="text" id="'.$plan_unique.'_d" value="'.$total_deposite.'" style="width:100px" placeholder="Total Deposit" /><br/> 
                                    </td>
                                    <td>
                                        <b>Last Withdrawal</b><br/> 
                                        <input type="text" id="'.$plan_unique.'_e" value="'.$last_withdrawal.'" style="width:100px" placeholder="Last Withdrawal" />
                                        <br/><br/>
                                        <b>Total Withdrawal</b>
                                        <input type="text" id="'.$plan_unique.'_f" value="'.$total_withdrawal.'" style="width:100px" placeholder="Total Withdrawal" />
                                    </td>
                                    <td>
                                        <b>Balance</b><br/>
                                        <input type="text" id="'.$plan_unique.'_g" value="'.$balance.'" style="width:100px" placeholder="balance " /><br/><br/>
                                        <b>Created At:</b><br/>
                                        '.$date.'<br/>
                                    </td>
                                    <td>
                                        <button class="btn btn-success waves-effect waves-float waves-light btn-sm mb-3 donate_btn"  id="'.$plan_unique.'_ua"
                                        onclick="update_port_acct(\''.$plan_unique.'\',\''.$email.'\')" >
                                        Update </button><br/>
                                        '.$update_btn.'
                                    </td>
                                </tr>';
                            }
                        }
                            
                    }
                    
                }else{
                $display .='<tr>
                                <td></td> <td colspan="6"> This list is empty for now..</td>
                        </tr>';
                }
                $display .= '</tbody>
                    </table></div></div>
                    <div class="card-footer small text-muted"></div></div></div>';
    if(isset($_GET['action']) && $_GET['action']==='sendPaymentDetails'){
        $con = '';
        $email = '';
        $amount = '';
        $name = '';
        if(isset($_GET['email']) && isset($_GET['amount']) && isset($_GET['name'])){
            $con = 'readonly';
            $email = $_GET['email'];
            $amount = $_GET['amount'];
            $name = $_GET['name'];
        }
        $display ='<div class="col-sm-12 col-md-12" style="" >
                    <div class="card">
                        <div class="card-header card-header-stretch">
                            <div class="card-title">
                                <h3>Send Payment Details</h3>
                            </div>
                        </div>
                        <div class="card-body">
                    <form method="post"  onsubmit="return false;" >
                        <div class="form-group mb-5">
                            <div class="col-sm-3 col-md-3">
                                <label for="email">Email</label>
                            </div>
                            <div class="col-sm-9 col-md-9">
                                <input type="email" name="email" id="email" value="'.$email.'" size="40"
                                    onkeyup="restrict(\'email\')" onfocus="emptyElement(\'status\')" required '.$con.' class="form-control"  />
                            </div>
                        </div>
                        <div class="form-group mb-5">
                            <div class="col-sm-3 col-md-3">
                                <label for="uname">Name</label>
                            </div>
                            <div class="col-sm-9 col-md-9">
                                <input type="text" name="uname" id="uname" value="'.$name.'" size="40"
                                    onkeyup="restrict(\'uname\')" onfocus="emptyElement(\'status\')" required '.$con.' class="form-control"  />
                            </div>
                        </div>
                        <div class="form-group mb-5">
                            <div class="col-sm-3 col-md-3">
                                <label for="deposit">Preferred Deposit Method</label>
                            </div>
                            <div class="col-sm-9 col-md-9">
                                <select name="deposit_method" id="deposit_method" onfocus="emptyElement(\'status\')" required class="form-control" >
                                    <option value="">---</option>
                                    <option value="Western Union">Western Union</option>
                                    <option value="MoneyGram">MoneyGram</option>
                                    <option value="Bank Wire">Bank Wire</option>
                                    <option value="Bitcoin">Bitcoin</option>
                                    <option value="Ethereum">Ethereum</option>
                                    <option value="Perfect Money">Perfect Money</option>
                                    <option value="Skrill">Skrill</option>
                                    <option value="PayPal">PayPal</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group mb-5">
                            <div class="col-sm-3 col-md-3">
                                <label for="amount">Amount (USD)</label>
                            </div>
                            <div class="col-sm-9 col-md-9">
                                <input type="text" name="amount" id="amount" value="'.$amount.'" class="form-control" 
                                onkeyup="restrict(\'amount\')" onfocus="emptyElement(\'status\')" required '.$con.'
                                placeholder="e.g: 500" />                                                
                            </div>
                        </div>
                        <div class="form-group mb-5">
                            <div class="col-sm-3 col-md-3">
                                <label for="payment">Payment Details</label>
                            </div>
                            <div class="col-sm-9 col-md-9">
                                <textarea id="payment_details" name="payment_details" value="" onfocus="emptyElement(\'status\')" 
                                required class="form-control"  ></textarea>
                            </div>
                        </div>
                        <div class="form-group mb-5">
                            <div class="col-sm-9 col-md-9 col-sm-offset-3 col-md-offset-3">
                               <button class=" btn btn-primary" id="fund_btn2" onclick="securePaymentDetails()" >
                                    Submit Details!!!
                                </button>
                                <div class="" style="" id="status"></div>
                            </div>
                        </div>       
                    </form>
              </div></div></div>';
    }
    else if(isset($_GET['action']) && $_GET['action']==='sendMail'){
        $con = '';
        $email = '';
        $name = '';
        if(isset($_GET['email']) && isset($_GET['name'])){
            $email = $_GET['email'];
            $name = $_GET['name'];
        }
        $display ='<div class="col-sm-12 col-md-12" style="" >
                    <div class="card">
                        <div class="card-header card-header-stretch">
                            <div class="card-title">
                                <h3>Send Mail</h3>
                            </div>
                        </div>
                        <div class="card-body">
                    <form method="post"  onsubmit="return false;" >
                        <div class="form-group mb-5">
                            <div class="col-sm-3 col-md-3">
                                <label for="email">Email</label>
                            </div>
                            <div class="col-sm-9 col-md-9">
                                <input type="email" name="email" id="email" value="'.$email.'" size="40"
                                    onkeyup="restrict(\'email\')" onfocus="emptyElement(\'statusText\')" required '.$con.' class="form-control"  />
                            </div>
                        </div>
                        <div class="form-group mb-5">
                            <div class="col-sm-3 col-md-3">
                                <label for="uname">Name</label>
                            </div>
                            <div class="col-sm-9 col-md-9">
                                <input type="text" name="uname" id="uname" value="'.$name.'" size="40"
                                    onkeyup="restrict(\'name\')" onfocus="emptyElement(\'statusText\')" required '.$con.' class="form-control"  />
                            </div>
                        </div>
                        <div class="form-group mb-5">
                            <div class="col-sm-3 col-md-3">
                                <label for="subject">Subject</label>
                            </div>
                            <div class="col-sm-9 col-md-9">
                                <input type="text" name="subject" id="subject"  size="40"
                                    onkeyup="restrict(\'subject\')" onfocus="emptyElement(\'statusText\')" required class="form-control"  />
                            </div>
                        </div>
                        <div class="form-group mb-5">
                            <div class="col-sm-3 col-md-3">
                                <label for="message">Message Body</label>
                            </div>
                            <div class="col-sm-9 col-md-9">
                                <textarea id="messageBody" name="messageBody" onfocus="emptyElement(\'statusText\')" required class="form-control" 
                                placeholder="Just enter the body of your e-mail"></textarea>
                            </div>
                        </div>
                        <div class="form-group mb-5">
                            <div class="col-sm-9 col-md-9 col-sm-offset-3 col-md-offset-3">
                               <button class=" btn btn-primary" id="mail_btn2" onclick="secureMail()" >
                                    Submit Mail!!!
                                </button>
                                <div class="" style="" id="statusText"></div>
                            </div>
                        </div>       
                    </form>
              </div></div></div>';
    }
    else if(isset($_GET['action']) && $_GET['action']==='wallet'){
        
        $display ='<div class="col-sm-12 col-md-12  col-lg-12 " >
                    <div class="card ">
                        <div class="card-header card-header-stretch">
                            <div class="card-title">
                                <h3>Site wallet addresses</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                              <table class="table table-head-bg-info  table-striped table-bordered" cellspacing="0">
                                <thead>
                                  <tr>
                                      <th>#</th>
                                      <th>Wallet Name</th>
                                      <th>Wallet Address</th>
                                      <th>Controls</th>
                                      <th>Updated By</th>
                                      <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>';
                $sql = "SELECT * FROM wallets WHERE user_id='1' and wallet_type='Admin' order by id asc";
                $query = mysqli_query($db_conx, $sql);
                $numrows = mysqli_num_rows($query);
                if($numrows > 0){                    
                    $count = 0;
                    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                        $count += 1; 
                        $wallet_id = $row['id'];
                        $wallet_name = $row['wallet_name'];
                        $wallet_symbol = $row['wallet_symbol'];
                        $wallet_address = $row['wallet_address'];
                        $date_added = $row['date_added'];
                        $dep_display = $row['dep_display'];
                        $with_display = $row['with_display'];
                        $wall_username = $row['username'];
                        $dep_display_con ='<select class="input-material" name="'.$wallet_id.'_dep_display" id="'.$wallet_id.'_dep_display">                                                
                                                <option value="0" selected>Disabled</option>
                                                <option value="1">Enables</option>
                                        </select>';
                        if($dep_display ==1){
                            $dep_display_con ='<select class="input-material" name="'.$wallet_id.'_dep_display" id="'.$wallet_id.'_dep_display">                                                
                                                <option value="0" >Disabled</option>
                                                <option value="1" selected>Enabled</option>
                                        </select>';
                        }
                        $with_display_con ='<select class="input-material" name="'.$wallet_id.'_with_display" id="'.$wallet_id.'_with_display">                                                
                                                <option value="0" selected>Disabled</option>
                                                <option value="1">Enables</option>
                                        </select>';
                        if($with_display ==1){
                            $with_display_con ='<select class="input-material" name="'.$wallet_id.'_with_display" id="'.$wallet_id.'_with_display">                                                
                                                <option value="0" >Disabled</option>
                                                <option value="1" selected>Enabled</option>
                                        </select>';
                        }
                        $display .='<tr>
                                <td> '.$count.'</td>
                                <td> '.ucwords($wallet_name).'('.$wallet_symbol.')</td>
                                <td> <input type="text" id="'.$wallet_id.'_wallet" value="'.$wallet_address.'" class="form-control" placeholder="Wallet Address" /></td>
                                <td>
                                    Deposit:<br/> '.$dep_display_con.'<br/><br/>
                                    Withdrawal:<br/> '.$with_display_con.'
                                </td>
                                <td> 
                                    '.$wall_username.'<br/><br/>
                                    Date: <br/>'.$date_added.'</td>
                                <td> 
                                    <button class="btn btn-success donate_btn m-3"  id="'.$wallet_id.'_w_btn"
                                    onclick="update_wallet(\''.$wallet_id.'\')" >
                                    <em class="fa fa-save"></em></button>
                                    <button class="btn btn-danger m-3 donate_btn"  id="'.$wallet_id.'_remove_btn"
                                        onclick="remove_wallet(\''.$wallet_id.'\')" >
                                    <em class="fa fa-trash-alt"></em></button>
                                </td>
                            </tr>';
						
                        }
                    }else{
                        $display .='no wallet added yet';
                    }
              $display .='</tbody>
            </table>
          </div>
        </div></div>
      </div>';
    }
}
?>
<?php 
/////////////////////////////////////////////
    $title ='Site Manager | '.$site_name;
    $title2 ='Manager';
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
<!--begin::Content-->
<?php include_once 'admin_body.php';?>
                

