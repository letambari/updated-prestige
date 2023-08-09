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
$display = '';
if($user_type !== 'member'){
//            include_once("functions.php");            
	
                // to get members who have deposited already ////
                ////////////////////////////////////////////////                
                $display .= '<div class="col-xs-12 col-sm-12 " id="deposit_tab">
        <div class="card mb-3">
                <div class="card-header card-header-stretch">
                    <div class="card-title">
                        <h3>Funded Accounts</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-row-bordered table-flush align-middle gy-6 table-hover "  cellspacing="0">
                            <thead class="border-bottom border-gray-200 fs-6 fw-bold bg-lighten">
                                <tr >
                                    <th width="8%">#</th>
                                    <th>Member Details</th> 
                                    <th>Earnings</th>
                                    <th>Deposit</th>
                                    <th>Withdrawal</th>
                                    <th>Balance</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot  class="border-bottom border-gray-200 fs-6 fw-bold bg-lighten">
                                <tr >
                                    <th width="8%">#</th>
                                    <th>Member Details</th>
                                    <th>Earnings</th>
                                    <th>Deposit</th>
                                    <th>Withdrawal</th>
                                    <th>Balance</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>';
                $sql = "SELECT id,unique_field,full_name,email,sponsor,payment_date FROM users WHERE active='1' order by payment_date";
                $query = mysqli_query($db_conx, $sql);
                $numrows = mysqli_num_rows($query);
                if($numrows > 0){
                    $count = 0;
                    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                        $count += 1;
                        $id = $row['id'];
                        $unique_id = $row['unique_field'];
                        $fname = $row['full_name'];
                        $email = $row['email'];
                        $sponsor = $row['sponsor'];
                        $date = $row['payment_date'];
                        $sql1 = "SELECT * FROM account WHERE user_id='$id' limit 1";
                        $query1 = mysqli_query($db_conx, $sql1);
                        $numrows1 = mysqli_num_rows($query1);
                        if($numrows1 > 0){
                            while ($row1 = mysqli_fetch_array($query1, MYSQLI_ASSOC)) {                            
                                $profit = $row1['profit'];
                                $currency_main = explode("|", $row1['currency']);
                                $currency = $currency_main[0];
                                $symbol = end($currency_main);
                                $auto_update= $row1['auto_update'];
                                $update_btn ='<button class="btn btn-sm btn-primary  donate_btn"  id="'.$id.'_update"
                                    onclick="update_switch(\''.$id.'\',\'1\',\'acct\')" >
                                        ON UPDATE.</button>';
                                if($auto_update > 0){
                                    $update_btn ='<button class="btn btn-sm btn-danger  donate_btn"  id="'.$id.'_update"
                                    onclick="update_switch(\''.$id.'\',\'0\',\'acct\')" >
                                        OFF UPDATE.</button>';
                                }
                                $last_withdrawal = $row1['last_withdrawal'];
                                $total_withdrawal = $row1['total_withdrawal'];
                                $last_deposite = $row1['last_deposite'];
                                $total_deposite = $row1['total_deposite'];
                                $balance = $row1['balance'];
                                
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
                                        
                                   </td>
                                    <td>
                                        <b>Sponsor</b><br/>'.$sponsor.'<br/><br/>
                                        <b>Referral Bonus</b><br/> 
                                        <input type="text" id="'.$unique_id.'_b" value="'.$bonus.'" style="width:100px" placeholder="Referal bonus" />
                                        <br/>
                                        <b>Currency</b><br/>'.$currency.' ('.$symbol.')<br/>
                                           <select class="input-material" name="currency" id="'.$unique_id.'_currency">
                                                   <option value="'.$currency.'|'.$symbol.'" >'.$currency.' ('.$symbol.')</option>
                                                   <option value="">Select Currency</option>
                                                   <option value="USD|$">USD $</option>
                                                   <option value="EURO|€">EURO €</option>
                                                   <option value="POUND|£">POUND £</option>
                                           </select><br/><br/>   
                                    </td>
                                    <td><b>Last Deposit</b><br/> 
                                    <input type="text" id="'.$unique_id.'_c" value="'.$last_deposite.'" style="width:100px" placeholder="Last Deposit" />
                                        <br/><br/>
                                        <b>Total Deposit</b><br/>
                                        <input type="text" id="'.$unique_id.'_d" value="'.$total_deposite.'" style="width:100px" placeholder="Total Deposit" /><br/> </td>

                                    <td><b>Last Withdrawal</b><br/> 
                                    <input type="text" id="'.$unique_id.'_e" value="'.$last_withdrawal.'" style="width:100px" placeholder="Last Withdrawal" />
                                        <br/><br/>
                                        <b>Total Withdrawal</b><br/>
                                        <input type="text" id="'.$unique_id.'_f" value="'.$total_withdrawal.'" style="width:100px" placeholder="Total Withdrawal" /></td>
                                    <td>
                                        <b>Balance</b><br/>
                                        <input type="text" id="'.$unique_id.'_g" value="'.$balance.'" style="width:100px" placeholder="balance " /><br/><br/>
                                        
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-success mb-1 donate_btn"  id="'.$unique_id.'_ua"
                                        onclick="update_mem_acct(\''.$unique_id.'\',\''.$email.'\')" >
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
        $display ='<div class="col-sm-10 col-md-10" style=";min-height:700px" >
                    <h3 style="" >Send Payment Details to Member</h3>
                    <form method="post"  onsubmit="return false;" >
                        <div class="form-group">
                            <div class="col-sm-3 col-md-3">
                                <label for="email">Email</label>
                            </div>
                            <div class="col-sm-9 col-md-9">
                                <input type="email" name="email" id="email" value="'.$email.'" size="40"
                                    onkeyup="restrict(\'email\')" onfocus="emptyElement(\'status\')" required '.$con.' class="form-control"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3 col-md-3">
                                <label for="uname">Name</label>
                            </div>
                            <div class="col-sm-9 col-md-9">
                                <input type="text" name="uname" id="uname" value="'.$name.'" size="40"
                                    onkeyup="restrict(\'uname\')" onfocus="emptyElement(\'status\')" required '.$con.' class="form-control"  />
                            </div>
                        </div>
                        <div class="form-group">
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
                        <div class="form-group">
                            <div class="col-sm-3 col-md-3">
                                <label for="amount">Amount (USD)</label>
                            </div>
                            <div class="col-sm-9 col-md-9">
                                <input type="text" name="amount" id="amount" value="'.$amount.'" class="form-control" 
                                onkeyup="restrict(\'amount\')" onfocus="emptyElement(\'status\')" required '.$con.'
                                placeholder="e.g: 500" />                                                
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3 col-md-3">
                                <label for="payment">Payment Details</label>
                            </div>
                            <div class="col-sm-9 col-md-9">
                                <textarea id="payment_details" name="payment_details" value="" onfocus="emptyElement(\'status\')" 
                                required class="form-control"  ></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-9 col-md-9 col-sm-offset-3 col-md-offset-3">
                               <button class=" btn btn-sm btn-primary" id="fund_btn2" onclick="securePaymentDetails()" >
                                    Submit Details!!!
                                </button>
                                <div class="" style="" id="status"></div>
                            </div>
                        </div>       
                    </form>
              </div>';
    }
    else if(isset($_GET['action']) && $_GET['action']==='sendMail'){
        $con = '';
        $email = '';
        $name = '';
        if(isset($_GET['email']) && isset($_GET['name'])){
            $email = $_GET['email'];
            $name = $_GET['name'];
        }
        if($_GET['name'] !=''){
            $con = 'readonly';
        }
        $display ='<div class="col-sm-10 col-md-10" style=";min-height:700px" >
                    <h3 style="">Send Payment Details to Member</h3>
                    <form method="post"  onsubmit="return false;" >
                        <div class="form-group">
                            <div class="col-sm-3 col-md-3">
                                <label for="email">Email</label>
                            </div>
                            <div class="col-sm-9 col-md-9">
                                <input type="email" name="email" id="email" value="'.$email.'" size="40"
                                    onkeyup="restrict(\'email\')" onfocus="emptyElement(\'statusText\')" required '.$con.' class="form-control"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3 col-md-3">
                                <label for="uname">Name</label>
                            </div>
                            <div class="col-sm-9 col-md-9">
                                <input type="text" name="uname" id="uname" value="'.$name.'" size="40"
                                    onkeyup="restrict(\'name\')" onfocus="emptyElement(\'statusText\')" required '.$con.' class="form-control"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3 col-md-3">
                                <label for="subject">Subject</label>
                            </div>
                            <div class="col-sm-9 col-md-9">
                                <input type="text" name="subject" id="subject"  size="40"
                                    onkeyup="restrict(\'subject\')" onfocus="emptyElement(\'statusText\')" required class="form-control"  />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3 col-md-3">
                                <label for="message">Message Body</label>
                            </div>
                            <div class="col-sm-9 col-md-9">
                                <textarea id="messageBody" name="messageBody" onfocus="emptyElement(\'statusText\')" required class="form-control" 
                                placeholder="Just enter the body of your e-mail"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-9 col-md-9 col-sm-offset-3 col-md-offset-3">
                               <button class=" btn btn-sm btn-primary" id="mail_btn2" onclick="secureMail()" >
                                    Submit Mail!!!
                                </button>
                                <div class="" style="" id="statusText"></div>
                            </div>
                        </div>       
                    </form>
              </div>';
    }
    else if(isset($_GET['action']) && $_GET['action']==='wallet'){
        
        $display ='<div class="col-sm-10 col-md-10 col-md-offset-1 col-sm-offset-1" style=";min-height:700px" >
		<div class="card mb-3">
        <div class="card-header">
			<i class="fa fa-list"></i> SIte wallet addresses
		</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table-striped" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>#</th>
				  <th>Wallet Name</th>
                  <th>Wallet Address</th>
                  <th>Date Updated</th>
				  <th>Action</th>
                </tr>
              </thead>
              <tbody>';
			  $sql = "SELECT * FROM wallets WHERE user_id='1' order by id asc limit 4";
                $query = mysqli_query($db_conx, $sql);
                $numrows = mysqli_num_rows($query);
                if($numrows > 0){                    
                    $count = 0;
                    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                        $count += 1; 
						$wallet_id = $row['id'];
						$wallet_name = $row['wallet_name'];
						$wallet_address = $row['wallet_address'];
						$date_added = $row['date_added'];
						if($wallet_name == 'btc'){
							$wallet_name = 'Bitcoin';
						}else if($wallet_name == 'bch'){
							$wallet_name = 'Bitcoin Cash';
						}else if($wallet_name == 'ltc'){
							$wallet_name = 'Litecoin';
						}else{
							$wallet_name = 'Ethereum';
						}
						$display .='<tr>
							<td> '.$count.'</td>
							<td> '.$wallet_name.'</td>
							<td> <input type="text" id="'.$wallet_id.'_wallet" value="'.$wallet_address.'" class="form-control" placeholder="Wallet Address" /></td>
							<td> '.$date_added.'</td>
							<td> <button class="btn btn-sm btn-success m-1 donate_btn"  id="'.$wallet_id.'_wallet_btn"
                                onclick="update_wallet(\''.$wallet_id.'\')" >
                                    Update Wallet</button></td>
						</tr>';
						
					}
				}else{
					$display .='no wallet added yet';
				}
              $display .='</tbody>
            </table>
          </div>
        </div>
        <div class="card-footer small text-muted"></div>
      </div></div>';
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
