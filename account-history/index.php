<?php
include_once("../controller/dependent.php");
// variables for those who are to pay 
if (!isset($_SESSION[$site_cokie])) {
    header("location: ../login");
    exit;
} else {
    $identifier = $_SESSION[$site_cokie];
    $sql = "SELECT id,verified FROM users WHERE unique_field='$identifier' LIMIT 1";
    $user_query = mysqli_query($db_conx, $sql);
    // Now make sure that user exists in the table
    $numrows = mysqli_num_rows($user_query);
    if ($numrows < 1) {
        session_start();
        // Set Session data to an empty array
        $_SESSION = array();
        // Expire their cookie files
        if (isset($_COOKIE[$site_cokie])) {
            setcookie($site_cokie, '', strtotime('-5 days'), "/");
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
if ($numrows < 1) {
    header("location: ../login");
    exit();
}

?> <?php
    $title = 'Transaction History | ' . $site_name;
    $title2 = 'Transaction History';
    $keyword = '';
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
    $class_link = '';
    include_once '../user_header.php';
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
if (isset($_GET['transactions']) && $_GET['transactions'] != '') {
    $type = mysqli_real_escape_string($db_conx, $_GET['transactions']);
    $all_active = '';
    $earn_active = '';
    $fund_active = '';
    $portfolio_active = '';
    $withdrawal_active = '';
    if ($type == 'earnings') {
        $earn_active = 'active';
        $page_title = 'Earnings';
        $sql = "SELECT * FROM transactions WHERE user_id='$profile_id' and (transaction_type='Profit' or transaction_type='Referral Bonus' or transaction_type='Portfolio Pay-Out') order by id desc";
    } else if ($type == 'funding') {
        $fund_active = 'active';
        $page_title = 'Deposit';
        $sql = "SELECT * FROM transactions WHERE user_id='$profile_id' AND transaction_type='Deposit' order by id desc";
    } else if ($type == 'porfolio') {
        $portfolio_active = 'active';
        $page_title = 'Portfolio';
        $sql = "SELECT * FROM transactions WHERE user_id='$profile_id' and (transaction_type='Portfolio Purchase' or transaction_type='Portfolio Top-Up') order by id desc";
    } else if ($type == 'withdrawal') {
        $withdrawal_active = 'active';
        $page_title = 'Withdrawal';
        $sql = "SELECT * FROM transactions WHERE user_id='$profile_id' AND transaction_type='Withdrawal' order by id desc";
    } else {
        $all_active = 'active';
    }
}
$query = mysqli_query($db_conx, $sql);
// Now make sure that user exists in the table
$numrows = mysqli_num_rows($query);
$count = 0;
if ($numrows > 0) {
    $transactions = '';
    $profit_transaction = '';
    $funding_transaction = '';
    $portfolio_transactions = '';
    $withdrawal_transactions = '';
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
        if ($status == '0') {
            $status_dis = '<span class="badge py-3 px-4 fs-7 badge-light-danger">Cancelled</span>';
        } elseif ($status == '1') {
            $status_dis = '<span class="badge py-3 px-4 fs-7 badge-light-warning">Processing</span>';
        }
        $transactions .= '<tr>
                        <td>' . $count . '</td>
                        <td>' . $transaction_id . '</td>
                        <td>' . $plan_unique . '</td>
                        <td>' . $transaction_type . '</td>
                        <td>' . $symbol . number_format($amount, 2) . '</td> 
                        <td>' . ucwords($payment_method) . '</td> 
                        <td>' . $status_dis . '</td>
                        <td> ' . date('H:ia D d, M Y', strtotime($created_at)) . '</td>         
                      </tr>';
    }
}
$all_count = 0;
$with_count = 0;
$port_count = 0;
$dep_count = 0;
$pro_count = 0;
$sql = "SELECT count(id) FROM transactions WHERE user_id='$profile_id' LIMIT 1";
$query = mysqli_query($db_conx, $sql);
if (mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_row($query);
    $all_count = $row[0];
}
$sql = "SELECT count(id) FROM transactions WHERE user_id='$profile_id' AND transaction_type='Withdrawal'  LIMIT 1";
$query = mysqli_query($db_conx, $sql);
if (mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_row($query);
    $with_count = $row[0];
}
$sql = "SELECT count(id) FROM transactions WHERE user_id='$profile_id' AND transaction_type='Deposit'  LIMIT 1";
$query = mysqli_query($db_conx, $sql);
if (mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_row($query);
    $dep_count = $row[0];
}
$sql = "SELECT count(id) FROM transactions WHERE user_id='$profile_id' and (transaction_type='Portfolio Purchase' or transaction_type='Portfolio Top-Up')  LIMIT 1";
$query = mysqli_query($db_conx, $sql);
if (mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_row($query);
    $port_count = $row[0];
}
$sql = "SELECT count(id) FROM transactions WHERE user_id='$profile_id' and (transaction_type='Profit' or transaction_type='Referral Bonus' or transaction_type='Portfolio Pay-Out')  LIMIT 1";
$query = mysqli_query($db_conx, $sql);
if (mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_row($query);
    $pro_count = $row[0];
}
///////////////////
?>
<!-- Mobile-header closed -->
<!-- Main Content-->

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2 class="main-content-title tx-24 mg-b-5"><?php echo $title2; ?></h2>

    </div>

</div>
<!-- End Page Header -->

<!--Row-->
<div class="row row-sm">
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
        <div class="card custom-card">
            <div class="card-body">
                <div class="card-item">
                    <div class="card-item-icon card-icon">
                        <svg class="text-primary" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                            <path d="M0 0h24v24H0V0z" fill="none" />
                            <path d="M12 4c-4.41 0-8 3.59-8 8s3.59 8 8 8 8-3.59 8-8-3.59-8-8-8zm1.23 13.33V19H10.9v-1.69c-1.5-.31-2.77-1.28-2.86-2.97h1.71c.09.92.72 1.64 2.32 1.64 1.71 0 2.1-.86 2.1-1.39 0-.73-.39-1.41-2.34-1.87-2.17-.53-3.66-1.42-3.66-3.21 0-1.51 1.22-2.48 2.72-2.81V5h2.34v1.71c1.63.39 2.44 1.63 2.49 2.97h-1.71c-.04-.97-.56-1.64-1.94-1.64-1.31 0-2.1.59-2.1 1.43 0 .73.57 1.22 2.34 1.67 1.77.46 3.66 1.22 3.66 3.42-.01 1.6-1.21 2.48-2.74 2.77z" opacity=".3" />
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z" />
                        </svg>
                    </div>
                    <div class="card-item-title mb-2">
                        <label class="main-content-label tx-10 font-weight-bold mb-1">All Transactions</label>
                    </div>
                    <div class="card-item-body">
                        <div class="card-item-stat">
                            <h4 class="font-weight-bold"><?php echo $all_count; ?></h4>
                            <small></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
        <div class="card custom-card">
            <div class="card-body">
                <div class="card-item">
                    <div class="card-item-icon card-icon">
                        <svg class="text-primary" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                            <path d="M0 0h24v24H0V0z" fill="none" />
                            <path d="M12 4c-4.41 0-8 3.59-8 8s3.59 8 8 8 8-3.59 8-8-3.59-8-8-8zm1.23 13.33V19H10.9v-1.69c-1.5-.31-2.77-1.28-2.86-2.97h1.71c.09.92.72 1.64 2.32 1.64 1.71 0 2.1-.86 2.1-1.39 0-.73-.39-1.41-2.34-1.87-2.17-.53-3.66-1.42-3.66-3.21 0-1.51 1.22-2.48 2.72-2.81V5h2.34v1.71c1.63.39 2.44 1.63 2.49 2.97h-1.71c-.04-.97-.56-1.64-1.94-1.64-1.31 0-2.1.59-2.1 1.43 0 .73.57 1.22 2.34 1.67 1.77.46 3.66 1.22 3.66 3.42-.01 1.6-1.21 2.48-2.74 2.77z" opacity=".3" />
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z" />
                        </svg>
                    </div>
                    <div class="card-item-title mb-2">
                        <label class="main-content-label tx-13 font-weight-bold mb-1">Earnings</label>
                    </div>
                    <div class="card-item-body">
                        <div class="card-item-stat">
                            <h4 class="font-weight-bold"> <?php echo $symbol . number_format($t_profit, 2); ?></h4>
                            <small></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
        <div class="card custom-card">
            <div class="card-body">
                <div class="card-item">
                    <div class="card-item-icon card-icon">
                        <svg class="text-primary" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                            <path d="M0 0h24v24H0V0z" fill="none" />
                            <path d="M12 4c-4.41 0-8 3.59-8 8s3.59 8 8 8 8-3.59 8-8-3.59-8-8-8zm1.23 13.33V19H10.9v-1.69c-1.5-.31-2.77-1.28-2.86-2.97h1.71c.09.92.72 1.64 2.32 1.64 1.71 0 2.1-.86 2.1-1.39 0-.73-.39-1.41-2.34-1.87-2.17-.53-3.66-1.42-3.66-3.21 0-1.51 1.22-2.48 2.72-2.81V5h2.34v1.71c1.63.39 2.44 1.63 2.49 2.97h-1.71c-.04-.97-.56-1.64-1.94-1.64-1.31 0-2.1.59-2.1 1.43 0 .73.57 1.22 2.34 1.67 1.77.46 3.66 1.22 3.66 3.42-.01 1.6-1.21 2.48-2.74 2.77z" opacity=".3" />
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z" />
                        </svg>
                    </div>
                    <div class="card-item-title mb-2">
                        <label class="main-content-label tx-13 font-weight-bold mb-1">Wallet Balance</label>
                        <span class="d-block tx-12 mb-0 text-muted"></span>
                    </div>
                    <div class="card-item-body">
                        <div class="card-item-stat">
                            <h4 class="font-weight-bold"><?php echo $symbol . number_format($total_bal, 2); ?></h4>
                            <small></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
<!--End row-->



<!--Row-->
<div class="row row-sm">
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
        <div class="card custom-card">
            <div class="card-body">
                <div class="card-item">
                    <div class="card-item-icon card-icon">
                        <svg class="text-primary" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                            <path d="M0 0h24v24H0V0z" fill="none" />
                            <path d="M12 4c-4.41 0-8 3.59-8 8s3.59 8 8 8 8-3.59 8-8-3.59-8-8-8zm1.23 13.33V19H10.9v-1.69c-1.5-.31-2.77-1.28-2.86-2.97h1.71c.09.92.72 1.64 2.32 1.64 1.71 0 2.1-.86 2.1-1.39 0-.73-.39-1.41-2.34-1.87-2.17-.53-3.66-1.42-3.66-3.21 0-1.51 1.22-2.48 2.72-2.81V5h2.34v1.71c1.63.39 2.44 1.63 2.49 2.97h-1.71c-.04-.97-.56-1.64-1.94-1.64-1.31 0-2.1.59-2.1 1.43 0 .73.57 1.22 2.34 1.67 1.77.46 3.66 1.22 3.66 3.42-.01 1.6-1.21 2.48-2.74 2.77z" opacity=".3" />
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z" />
                        </svg>
                    </div>
                    <div class="card-item-title mb-2">
                        <label class="main-content-label tx-10 font-weight-bold mb-1">Funding</label>
                    </div>
                    <div class="card-item-body">
                        <div class="card-item-stat">
                            <h4 class="font-weight-bold"><?php echo $dep_count; ?></h4>
                            <small></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
        <div class="card custom-card">
            <div class="card-body">
                <div class="card-item">
                    <div class="card-item-icon card-icon">
                        <svg class="text-primary" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                            <path d="M0 0h24v24H0V0z" fill="none" />
                            <path d="M12 4c-4.41 0-8 3.59-8 8s3.59 8 8 8 8-3.59 8-8-3.59-8-8-8zm1.23 13.33V19H10.9v-1.69c-1.5-.31-2.77-1.28-2.86-2.97h1.71c.09.92.72 1.64 2.32 1.64 1.71 0 2.1-.86 2.1-1.39 0-.73-.39-1.41-2.34-1.87-2.17-.53-3.66-1.42-3.66-3.21 0-1.51 1.22-2.48 2.72-2.81V5h2.34v1.71c1.63.39 2.44 1.63 2.49 2.97h-1.71c-.04-.97-.56-1.64-1.94-1.64-1.31 0-2.1.59-2.1 1.43 0 .73.57 1.22 2.34 1.67 1.77.46 3.66 1.22 3.66 3.42-.01 1.6-1.21 2.48-2.74 2.77z" opacity=".3" />
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z" />
                        </svg>
                    </div>
                    <div class="card-item-title mb-2">
                        <label class="main-content-label tx-13 font-weight-bold mb-1">Withdrawal</label>
                    </div>
                    <div class="card-item-body">
                        <div class="card-item-stat">
                            <h4 class="font-weight-bold"><?php echo $with_count; ?></h4>
                            <small></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
        <div class="card custom-card">
            <div class="card-body">
                <div class="card-item">
                    <div class="card-item-icon card-icon">
                        <svg class="text-primary" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                            <path d="M0 0h24v24H0V0z" fill="none" />
                            <path d="M12 4c-4.41 0-8 3.59-8 8s3.59 8 8 8 8-3.59 8-8-3.59-8-8-8zm1.23 13.33V19H10.9v-1.69c-1.5-.31-2.77-1.28-2.86-2.97h1.71c.09.92.72 1.64 2.32 1.64 1.71 0 2.1-.86 2.1-1.39 0-.73-.39-1.41-2.34-1.87-2.17-.53-3.66-1.42-3.66-3.21 0-1.51 1.22-2.48 2.72-2.81V5h2.34v1.71c1.63.39 2.44 1.63 2.49 2.97h-1.71c-.04-.97-.56-1.64-1.94-1.64-1.31 0-2.1.59-2.1 1.43 0 .73.57 1.22 2.34 1.67 1.77.46 3.66 1.22 3.66 3.42-.01 1.6-1.21 2.48-2.74 2.77z" opacity=".3" />
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z" />
                        </svg>
                    </div>
                    <div class="card-item-title mb-2">
                        <label class="main-content-label tx-13 font-weight-bold mb-1">Portfolio</label>
                        <span class="d-block tx-12 mb-0 text-muted"></span>
                    </div>
                    <div class="card-item-body">
                        <div class="card-item-stat">
                            <h4 class="font-weight-bold"><?php echo $port_count; ?></h4>
                            <small></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!--End row-->

<!-- Row -->
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="card custom-card card-body bg-gray-800 tx-white">
            <div class="card-body pb-0">


            </div>
        </div>
    </div>
</div>
<!-- End Row -->
<br><br>

<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card custom-card overflow-hidden">
            <div class="card-body">
                <div>
                    <h6 class="main-content-label mb-1"><?php echo $page_title; ?> History</h6>
                </div>
                <div class="table-responsive">
                    <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">

                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table dataTable no-footer" id="example1" role="grid" aria-describedby="example1_info">
                                    <thead>
                                        <tr role="row">
                                            <th class="wd-20p sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 184px;">
                                                #
                                            </th>
                                            <th class="wd-20p sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 184px;">Reference</th>

                                            <th class="wd-15p sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Age: activate to sort column ascending" style="width: 127.8px;">Portfolio ID</th>

                                            <th class="wd-15p sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Age: activate to sort column ascending" style="width: 127.8px;">Type</th>
                                            <th class="wd-15p sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Age: activate to sort column ascending" style="width: 127.8px;">Amount(USD)</th>
                                            <th class="wd-20p sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Salary: activate to sort column ascending" style="width: 127.8px;">Channel</th>
                                            <th class="wd-20p sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Salary: activate to sort column ascending" style="width: 127.8px;">Status</th>
                                            <th class="wd-20p sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Salary: activate to sort column ascending" style="width: 184px;">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo $transactions; ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- End Main Content-->

<!-- Main Footer-->
<?php include_once '../user_footer.php'; ?>

</body>

</html>