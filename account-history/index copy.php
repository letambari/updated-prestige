<?php
include_once("../controller/dependent.php");
// variables for those who are to pay 
if(!isset($_SESSION[$site_cokie])){
    header("location: ../login");
    exit;
}else{
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

?> <?php 
    $title ='Transaction History | '.$site_name;
    $title2 ='Transaction History';
    $keyword ='';
    $discription = '';  
    $dash = '';
    $dep = '';
    $pricing = '';
    $pur_invest = '';
    $my_invest = '';
    $with = '';
    $act_his = 'active';
    $ref = '';
    $acct_pro = ''; 
    $acct_settings = ''; 
    $acct_security = '';
    $suppt = '';
    include_once '../user_header copy.php'; 
?>
<?php
////////////////////////////////////////////////////
////////////// here we get all investment for my investment page
$transactions = '<tr>
                    <td colspan="6"> <strong >No record(s)..</strong></td>
                  </tr>';
$all_active = 'active';
$earn_active = '';
$fund_active = '';
$portfolio_active = '';
$withdrawal_active = '';
$page_title = 'Transaction';
$sql = "SELECT * FROM transactions WHERE user_id='$profile_id'  order by id desc";
if(isset($_GET['transactions']) && $_GET['transactions'] != ''){
    $type = mysqli_real_escape_string($db_conx, $_GET['transactions']);
    $all_active = '';    $earn_active = '';    $fund_active = '';    $portfolio_active = '';
    $withdrawal_active = '';
    if($type == 'earnings'){
        $earn_active = 'active';
        $page_title = 'Earnings';
        $sql = "SELECT * FROM transactions WHERE user_id='$profile_id' and (transaction_type='Profit' or transaction_type='Referral Bonus' or transaction_type='Portfolio Pay-Out') order by id desc";
    }else if($type == 'funding'){
        $fund_active = 'active';
        $page_title = 'Deposit';
        $sql = "SELECT * FROM transactions WHERE user_id='$profile_id' AND transaction_type='Deposit' order by id desc";
    }else if($type == 'porfolio'){
        $portfolio_active = 'active';   
        $page_title = 'Portfolio';
        $sql = "SELECT * FROM transactions WHERE user_id='$profile_id' and (transaction_type='Portfolio Purchase' or transaction_type='Portfolio Top-Up') order by id desc";
    }else if($type == 'withdrawal'){
        $withdrawal_active = 'active';
        $page_title = 'Withdrawal';
        $sql = "SELECT * FROM transactions WHERE user_id='$profile_id' AND transaction_type='Withdrawal' order by id desc";
    }else{
        $all_active = 'active';
    }
}
$query = mysqli_query($db_conx, $sql);
// Now make sure that user exists in the table
$numrows = mysqli_num_rows($query);
$count = 0;
if ($numrows > 0) {
    $transactions = '';    $profit_transaction = '';
    $funding_transaction = '';    $portfolio_transactions = ''; $withdrawal_transactions = '';   
    // Fetch the user row from the query above
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        $count += 1;        
        $transaction_id = $row['unique_field'];
        $plan_unique = $row['plan_unique'];
        $transaction_type = preg_replace('#[^a-zA-Z]#i', ' ', $row['transaction_type']);
        $payment_details = $row['payment_details'];
        $payment_method = $row['payment_method'];
        $amount = $row['amount'];
        $status = $row['status'];
        $created_at = $row['created_at'];
        $status_dis = '<span class="badge py-3 px-4 fs-7 badge-light-success">Completed</span>';
        if($status == '0'){
            $status_dis = '<span class="badge py-3 px-4 fs-7 badge-light-danger">Cancelled</span>';
        }elseif($status =='1'){
            $status_dis = '<span class="badge py-3 px-4 fs-7 badge-light-warning">Processing</span>';
        }
        $transactions .='<tr>
                        <td>'.$count.'</td>
                        <td>'.$transaction_id.'</td>
                        <td>'.$plan_unique.'</td>
                        <td>'.$transaction_type.'</td>
                        <td>'.$symbol.number_format($amount,2).'</td> 
                        <td>'.ucwords($payment_method).'</td> 
                        <td>'.$status_dis.'</td>
                        <td> '.date('H:ia D d, M Y', strtotime($created_at)).'</td>         
                      </tr>'; 
    }
}
$all_count = 0;
$with_count = 0;$port_count = 0;$dep_count = 0;$pro_count = 0;
$sql = "SELECT count(id) FROM transactions WHERE user_id='$profile_id' LIMIT 1";
$query = mysqli_query($db_conx, $sql);
if(mysqli_num_rows($query) > 0){
    $row = mysqli_fetch_row($query);
    $all_count = $row[0];
}
$sql = "SELECT count(id) FROM transactions WHERE user_id='$profile_id' AND transaction_type='Withdrawal'  LIMIT 1";
$query = mysqli_query($db_conx, $sql);
if(mysqli_num_rows($query) > 0){
    $row = mysqli_fetch_row($query);
    $with_count = $row[0];
}
$sql = "SELECT count(id) FROM transactions WHERE user_id='$profile_id' AND transaction_type='Deposit'  LIMIT 1";
$query = mysqli_query($db_conx, $sql);
if(mysqli_num_rows($query) > 0){
    $row = mysqli_fetch_row($query);
    $dep_count = $row[0];
}
$sql = "SELECT count(id) FROM transactions WHERE user_id='$profile_id' and (transaction_type='Portfolio Purchase' or transaction_type='Portfolio Top-Up')  LIMIT 1";
$query = mysqli_query($db_conx, $sql);
if(mysqli_num_rows($query) > 0){
    $row = mysqli_fetch_row($query);
    $port_count = $row[0];
}
$sql = "SELECT count(id) FROM transactions WHERE user_id='$profile_id' and (transaction_type='Profit' or transaction_type='Referral Bonus' or transaction_type='Portfolio Pay-Out')  LIMIT 1";
$query = mysqli_query($db_conx, $sql);
if(mysqli_num_rows($query) > 0){
    $row = mysqli_fetch_row($query);
    $pro_count = $row[0];
}
///////////////////
?>

                <!--begin::Content-->
                <div id="kt_app_content" class="app-content flex-column-fluid">
                    <!--begin::Content container-->
                    <div id="kt_app_content_container" class="app-container container-fluid">
                        <div class="card mb-6 mb-xl-9">
                            <div class="card-body pb-0">
                                <!--begin::Details-->
                                <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                                    <!--begin::Nav item-->
                                    <li class="nav-item mt-2">
                                        <a class="nav-link text-active-primary ms-0 me-10 py-5 <?php echo $all_active;?>" 
                                           href="../account-history/">
                                            All Transactions &nbsp; 
                                            <span class="badge py-3 px-4 fs-7 badge-light-primary"><?php echo $all_count;?></span>
                                        </a>
                                    </li>
                                    <!--end::Nav item-->
                                    <!--begin::Nav item-->
                                    <li class="nav-item mt-2">
                                        <a class="nav-link text-active-primary ms-0 me-10 py-5 <?php echo $earn_active;?>" 
                                           href="../account-history/?transactions=earnings">
                                            Earnings &nbsp;
                                            <span class="badge py-3 px-4 fs-7 badge-light-success"><?php echo $pro_count;?></span>
                                        </a>
                                    </li>
                                    <!--end::Nav item-->
                                    <!--begin::Nav item-->
                                    <li class="nav-item mt-2">
                                        <a class="nav-link text-active-primary ms-0 me-10 py-5 <?php echo $fund_active;?>" 
                                           href="../account-history/?transactions=funding">
                                            Funding &nbsp;
                                            <span class="badge py-3 px-4 fs-7 badge-light-warning"><?php echo $dep_count;?></span>
                                        </a>
                                    </li>
                                    <!--end::Nav item-->
                                    <!--begin::Nav item-->
                                    <li class="nav-item mt-2">
                                        <a class="nav-link text-active-primary ms-0 me-10 py-5 <?php echo $portfolio_active;?>" 
                                           href="../account-history/?transactions=porfolio">
                                            Portfolio &nbsp;
                                            <span class="badge py-3 px-4 fs-7 badge-light-danger"><?php echo $port_count;?></span>
                                        </a>
                                    </li>
                                    <!--end::Nav item-->
                                    <!--begin::Nav item-->
                                    <li class="nav-item mt-2">
                                        <a class="nav-link text-active-primary ms-0 me-10 py-5 <?php echo $withdrawal_active;?>" 
                                           href="../account-history/?transactions=withdrawal">
                                            Withdrawal &nbsp;
                                            <span class="badge py-3 px-4 fs-7 badge-light-warning"><?php echo $with_count;?></span>
                                        </a>
                                    </li>
                                    <!--end::Nav item-->
                                </ul>

                            </div>
                        </div>
                        <!--begin::Row-->
                        <div class="row gx-5 gx-xl-10">
                            <!--begin::Col-->
                            <div class="col-xxl-12 mb-xl-12">
                                <!--begin::Chart widget 8-->
                                <div class="card card-flush h-xl-100">
                                    <!--begin::Header-->
                                    <div class="card-header pt-5">
                                        <!--begin::Title-->
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label fw-bold text-dark"><?php echo $page_title;?> History</span>
                                            <span class="text-gray-400 mt-1 fw-semibold fs-6"></span>
                                        </h3>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Header-->
                                    <!--begin::Body-->
                                    <div class="card-body pt-6">
                                        <div class="table-responsive">
                                            <!--begin::Table-->
                                            <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">
                                                <!--begin::Table head-->
                                                <thead>
                                                    <tr class="fs-7 fw-bold text-gray-400 border-bottom-0">
                                                        <th class="pb-3">#</th>
                                                        <th class="pb-3 ">Reference</th>
                                                        <th class="pb-3 ">Portfolio ID</th>
                                                        <th class="pb-3 ">Type</th>
                                                        <th class="pb-3 ">Amount(<?php echo $currency; ?>)</th>
                                                        <th class="pb-3 ">Channel</th>
                                                        <th class="pb-3 ">Status</th>
                                                        <th class="pb-3 ">Date</th>
                                                    </tr>
                                                </thead>
                                                <!--end::Table head-->
                                                <!--begin::Table body-->
                                                <tbody>
                                                    <?php echo $transactions;?>
                                                </tbody>
                                                <!--end::Table body-->
                                            </table>
                                        </div>
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::Chart widget 8-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Row-->
                    </div>
                    <!--end::Content container-->
                </div>
                <!--end::Content-->
        <?php include_once '../user_footer copy.php'; ?>                
    </body>
</html>