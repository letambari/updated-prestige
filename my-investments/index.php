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
    $title ='My Investments | '.$site_name.'';
    $title2 ='My Investments';
    $keyword ='';
    $discription = '';  
    $dash = '';
    $dep = '';
    $pricing = '';
    $pur_invest = '';
    $my_invest = 'active';
    $with = '';
    $act_his = '';
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
$container = '<div class="col-lg-6 col-xxl-6">
                <!--begin::Budget-->
                <div class="card ">
                    <div class="card-body p-9">
                        <div class="fs-4 fw-semibold text-gray-400 mb-7">No data to display</div>
                    </div>
                </div>
                <!--end::Budget-->
            </div>';
$my_active = 'active';
$active_active = '';
$expire_active = '';
$canceled_active = '';
$sql = "SELECT * FROM plan_account WHERE user_id='$profile_id' order by id desc";
$query = mysqli_query($db_conx, $sql);
// Now make sure that user exists in the table
$numrows = mysqli_num_rows($query);
$in_count = 0;
$m_count = 0;$a_count = 0;$e_count = 0;$c_count = 0;
$a_invested = 0;$e_invested = 0;
$a_profit = 0;$e_profit = 0;
$a_rev = 0;$e_rev = 0;
if ($numrows > 0) {
    $container = '';    $active_investment = '';
    $expire_investment = '';    $canceled_investment = '';   
    // Fetch the user row from the query above
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        $in_count += 1;
        $m_count += 1;
        $in_plan_id = $row['plan_unique'];
        $in_plan = $row['plan'];
        $in_profit = $row['profit'];
        $in_total_withdrawal = $row['total_withdrawal'];
        $in_total_deposite = $row['total_deposite'];
        $in_active = $row['active'];
        $in_plan_duration = $row['plan_duration'];
        $in_day_count = $row['day_count'];
        $in_balance = $row['balance'];
        $in_created_at = date('D d, M Y', strtotime($row['created_at']));
        $maturity_time = date('D d, M Y', strtotime($row['created_at']. " + $in_plan_duration days"));
        $in_status_dis = '<span class="badge badge-light-warning">Expired</span>';
        $color = 'danger';
        $direction = 'down';
        $class = 'in_expired in_class';
        if ($in_active == 0) {
            $in_status_dis = '<span class="badge badge-light-danger">Canceled</span>';
        } elseif ($in_active == 1) {
            $in_status_dis = '<span class="legend bg-primary rounded-circle"></span> Active';
            $a_invested += $in_total_deposite;
            $a_profit +=$in_profit;
            $a_rev += $in_total_deposite + $in_profit;
            $color = 'success';
            $direction = 'up';
            $class = 'in_active in_class';
        }elseif ($in_active == 2) {
            $in_status_dis = '<span class="legend bg-danger rounded-circle"></span> Expired';
            $e_invested += $in_total_deposite;
            $e_profit +=$in_profit;
            $e_rev += $in_total_deposite + $in_profit;
        }
        $percent = ($in_day_count / $in_plan_duration) * 100;
        $con=
            '<div class="col-sm-12 col-md-6 col-lg-6 col-xl-6 '.$class.'">
                    <div class="card custom-card overflow-hidden">
                        <div class="card-header border-bottom-0 pb-0">
                            <div>
                                <div class="d-flex">
                                    <label class="main-content-label my-auto pt-2">'.$in_plan.'</label>
                                    <div class="ml-auto mt-3 d-flex">
                                        <div class="mr-3 d-flex text-muted tx-13">
                                            '.$in_status_dis.'
                                        </div>
                                    </div>
                                </div>
                                <span class="d-block tx-12 mt-2 mb-0 text-muted"> Reference: #'.$in_plan_id.'</span>
                                <span class="tx-13 text-info">Day '.$in_day_count.' of '.$in_plan_duration.'</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-4 my-auto">
                                    <h6 class="mb-3 font-weight-normal">Capital</h6>
                                    <div class="text-left">
                                        <h3 class="font-weight-bold mr-3 mb-2 text-primary">
                                            '.$symbol.number_format($in_total_deposite,2) .'
                                        </h3>
                                        <p class="tx-13 my-auto text-muted">&nbsp;</p>
                                    </div>
                                </div>
                                <div class="col-md-4 my-auto">
                                    <h6 class="mb-3 font-weight-normal">Earnings</h6>
                                    <div class="text-left">
                                        <h3 class="font-weight-bold mr-3 mb-2 text-primary">
                                            '.$symbol.number_format($in_profit,2) .'
                                        </h3>
                                        <p class="tx-13 my-auto text-muted">
                                            &nbsp;
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-4 my-auto">
                                    <h6 class="mb-3 font-weight-normal">Revenue</h6>
                                    <div class="text-left">
                                        <h3 class="font-weight-bold mr-3 mb-2 text-primary">
                                            '.$symbol.number_format($in_total_deposite+$in_profit,2) .'
                                        </h3>
                                        <p class="tx-13 my-auto text-muted">&nbsp;</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="expansion-value d-flex tx-inverse">
                                        <span class="tx-13 text-muted">Start: '.$in_created_at.'</span>
                                        <span class="tx-13 text-muted ml-auto">End: '.$maturity_time.'</span>
                                    </div>
                                    <div class="expansion-value d-flex tx-inverse">
                                        <strong class="ml-auto"><i class="fas fa-caret-'.$direction.' mr-1 text-'.$color.'"></i>'.number_format($percent,2).'%</strong>
                                    </div>
                                    <div class="progress">
                                        <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="'.number_format($percent,2).'"  style="width:'.number_format($percent,2).'%" class="progress-bar progress-bar-xs  bg-'.$color.'" role="progressbar"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
        $container .= $con;
        if ($in_active == 0) {
            $canceled_investment .= $con;
            $c_count += 1;
        } elseif ($in_active == 1) {
            $active_investment .= $con;
            $a_count += 1;
        }elseif ($in_active == 2) {
            $expire_investment .= $con;
            $e_count += 1;
        }
    }
}
if($a_count < 1){
    $active_investment = '<div class="col-lg-6 col-xxl-6">
                <!--begin::Budget-->
                <div class="card ">
                    <div class="card-body p-9">
                        <div class="fs-4 fw-semibold text-gray-400 mb-7">No data to display</div>
                    </div>
                </div>
                <!--end::Budget-->
            </div>';
}
if($c_count < 1){
    $canceled_investment = '<div class="col-lg-6 col-xxl-6">
                <!--begin::Budget-->
                <div class="card ">
                    <div class="card-body p-9">
                        <div class="fs-4 fw-semibold text-gray-400 mb-7">No data to display</div>
                    </div>
                </div>
                <!--end::Budget-->
            </div>';
}
if($e_count < 1){
    $expire_investment = '<div class="col-lg-6 col-xxl-6">
                <!--begin::Budget-->
                <div class="card ">
                    <div class="card-body p-9">
                        <div class="fs-4 fw-semibold text-gray-400 mb-7">No data to display</div>
                    </div>
                </div>
                <!--end::Budget-->
            </div>';
}
if(isset($_GET['type']) && $_GET['type'] != ''){
    $type = mysqli_real_escape_string($db_conx, $_GET['type']);
    $my_active = '';    $active_active = '';    $expire_active = '';    $canceled_active = '';
    if($type == 'active'){
        $active_active = 'active';
        $container = $active_investment;
    }else if($type == 'expired'){
        $expire_active = 'active';
        $container = $expire_investment;
    }else if($type == 'canceled'){
        $canceled_active = 'active';
        $container = $canceled_investment;
    }else{
        $my_active = 'active';
    }
}


?>
			<!-- Mobile-header closed -->
            <!-- Page Header -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5"><?php echo $title2;?></h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="../dashboard/">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $title2;?></li>
                    </ol>
                </div>
                <div class="d-flex">
                    <div class="justify-content-center">
                        <button class="btn btn-primary my-2 btn-icon-text">
                            Wallet: <?php echo $symbol.number_format($t_balance,2);?>
                        </button>
                    </div>
                </div>
            </div>
            <!-- End Page Header -->
            <div class="row row-sm">
                <div class="col-sm-6 col-md-6 col-xl-3">
                    <div class="card custom-card">
                        <div class="card-body">
                            <p class="mb-1 tx-inverse">Number Of Packages</p>
                            <div>
                                <h4 class="dash-25 mb-2"><?php echo $investment_count;?></h4>
                            </div>
                            <?php 
                                $a_percent = $e_percent = 0;
                                if($investment_count>0){
                                    $a_percent = ($a_count / $investment_count) * 100;
                                    $e_percent = ($e_count / $investment_count) * 100;
                                }
                            ?>
                            <div class="expansion-value d-flex tx-inverse">
                                <strong><i class="fas fa-caret-up mr-1 text-success"></i> <?php echo number_format($a_percent,2);?>%</strong>
                                <strong class="ml-auto"><i class="fas fa-caret-down mr-1 text-danger"></i><?php echo number_format($e_percent,2);?>%</strong>
                            </div>
                            <div class="progress">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo number_format($a_percent,2);?>" style="width:<?php echo number_format($a_percent,2);?>%" class="progress-bar progress-bar-xs " role="progressbar"></div>
                            </div>
                            <div class="expansion-label d-flex text-muted">
                                <span>Active</span>
                                <span class="ml-auto">Expired</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-xl-3">
                    <div class="card custom-card">
                        <div class="card-body">
                            <p class="mb-1 tx-inverse">Capital</p>
                            <div>
                                <h4 class="dash-25 mb-2">
                                    <?php echo $symbol.number_format($invested,2);?>
                                </h4>
                            </div>
                            <?php 
                                $a_percent = $e_percent = 0;
                                if($invested>0){
                                    $a_percent = ($a_invested / $invested) * 100;
                                    $e_percent = ($e_invested / $invested) * 100;
                                }
                            ?>
                            <div class="expansion-value d-flex tx-inverse">
                                <strong><i class="fas fa-caret-up mr-1 text-success"></i> <?php echo number_format($a_percent,2);?>%</strong>
                                <strong class="ml-auto"><i class="fas fa-caret-down mr-1 text-danger"></i><?php echo number_format($e_percent,2);?>%</strong>
                            </div>
                            <div class="progress">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo number_format($a_percent,2);?>"  style="width:<?php echo number_format($a_percent,2);?>%" class="progress-bar progress-bar-xs  bg-secondary" role="progressbar"></div>
                            </div>
                            <div class="expansion-label d-flex text-muted">
                                <span>Active</span>
                                <span class="ml-auto">Expired</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-xl-3">
                    <div class="card custom-card">
                        <div class="card-body">
                            <p class="mb-1 tx-inverse">Earnings</p>
                            <div>
                                <h4 class="dash-25 mb-2">
                                    <?php echo $symbol.number_format($t_profit,2);?>
                                </h4>
                            </div>
                            <?php 
                                $a_percent = $e_percent = 0;
                                if($t_profit>0){
                                    $a_percent = ($a_profit / $t_profit) * 100;
                                    $e_percent = ($e_profit / $t_profit) * 100;
                                }
                            ?>
                            <div class="expansion-value d-flex tx-inverse">
                                <strong><i class="fas fa-caret-up mr-1 text-success"></i> <?php echo number_format($a_percent,2);?>%</strong>
                                <strong class="ml-auto"><i class="fas fa-caret-down mr-1 text-danger"></i><?php echo number_format($e_percent,2);?>%</strong>
                            </div>
                            <div class="progress">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo number_format($a_percent,2);?>"  style="width:<?php echo number_format($a_percent,2);?>%" class="progress-bar progress-bar-xs  bg-secondary" role="progressbar"></div>
                            </div>
                            <div class="expansion-label d-flex text-muted">
                                <span>Active</span>
                                <span class="ml-auto">Expired</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-xl-3">
                    <div class="card custom-card">
                        <div class="card-body">
                            <p class="mb-1 tx-inverse">Total Revenue</p>
                            <div>
                                <h4 class="dash-25 mb-2">
                                    <?php echo $symbol.number_format($a_rev + $e_rev,2);?>
                                </h4>
                            </div>
                            <?php 
                                $t_rev = $a_rev + $e_rev;
                                $a_percent = $e_percent = 0;
                                if($t_rev>0){
                                    $a_percent = ($a_rev / $t_rev) * 100;
                                    $e_percent = ($e_rev / $t_rev) * 100;
                                }
                            ?>
                            <div class="expansion-value d-flex tx-inverse">
                                <strong><i class="fas fa-caret-up mr-1 text-success"></i> <?php echo number_format($a_percent,2);?>%</strong>
                                <strong class="ml-auto"><i class="fas fa-caret-down mr-1 text-danger"></i><?php echo number_format($e_percent,2);?>%</strong>
                            </div>
                            <div class="progress">
                                <div aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo number_format($a_percent,2);?>"  style="width:<?php echo number_format($a_percent,2);?>%" class="progress-bar progress-bar-xs  bg-secondary" role="progressbar"></div>
                            </div>
                            <div class="expansion-label d-flex text-muted">
                                <span>Active</span>
                                <span class="ml-auto">Expired</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row row-sm">
                <div class="col-sm-12 col-md-12 col-xl-12 mb-3">
                    <div class="dropdown">
                        <button aria-expanded="false" aria-haspopup="true" class="btn ripple btn-primary dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" type="button">
                            <i class="fe fe-filter mr-2"></i> Filter 
                        </button>
                        <div  class="dropdown-menu tx-13">
                                <a class="dropdown-item filter_nav" data-value="0" href="javascript:void(0)">All (<?php echo $m_count;?>)</a>
                                <a class="dropdown-item filter_nav" data-value="1" href="javascript:void(0)">Active Packs (<?php echo $a_count;?>)</a>
                                <a class="dropdown-item filter_nav" data-value="2" href="javascript:void(0)">Expired (<?php echo $e_count;?>)</a>
                        </div>
                    </div>
                </div>
                <?php echo $container;?>
            </div>



		<!-- Main Footer-->
		<?php include_once '../user_footer.php'; ?>
                <script>
                    $(document).ready(function(){
                        $(".filter_nav").click(function(){
                            var value = $(this).data("value");
                            var display = 'all';
                            if(value == 1){
                                display = 'in_active';
                            }else if(value == 2){
                                display = 'in_expired';
                            }
                            sort_portfolio(display);
                        });
                        function sort_portfolio(display){
                            $(".in_class").css('display','block');
                            if(display != 'all'){
                                $(".in_class").css('display','none');
                                $("."+display).css('display','block');
                            }
                        }                    
                    });

                </script>
</body>

</html>