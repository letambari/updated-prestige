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
    $title ='Dashboard | '.$site_name;
    $title2 ='Dashboard';
    $keyword ='';
    $discription = ''; 
    $dash = 'active';
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
    $class_link = '';
    include_once '../user_header.php'; 
?>
<!-- Main Content-->
                        <!-- Page Header -->
                        <div class="page-header">
                            <div>
                                <h2 class="main-content-title tx-24 mg-b-5">Hello <?php echo $f_n;?>!</h2>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="../dashboard/">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><?php echo $title2;?></li>
                                </ol>
                            </div>
                            <div class="d-flex">
                                <div class="justify-content-center">
                                    <a href="../deposit/" class="btn btn-primary my-2 btn-icon-text"><i class="fe fe-database mr-2"></i> Deposit</a>
                                </div>
                            </div>
                        </div>
                        <!-- End Page Header -->
                        <!--Row-->
                        <div class="row row-sm">
                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                                <div class="card custom-card">
                                    <div class="card-body">
                                        <div class="card-item">
                                            <div class="card-item-icon card-icon">
                                                <svg class="text-primary" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                                                    <path d="M0 0h24v24H0V0z" fill="none" />
                                                    <path
                                                        d="M12 4c-4.41 0-8 3.59-8 8s3.59 8 8 8 8-3.59 8-8-3.59-8-8-8zm1.23 13.33V19H10.9v-1.69c-1.5-.31-2.77-1.28-2.86-2.97h1.71c.09.92.72 1.64 2.32 1.64 1.71 0 2.1-.86 2.1-1.39 0-.73-.39-1.41-2.34-1.87-2.17-.53-3.66-1.42-3.66-3.21 0-1.51 1.22-2.48 2.72-2.81V5h2.34v1.71c1.63.39 2.44 1.63 2.49 2.97h-1.71c-.04-.97-.56-1.64-1.94-1.64-1.31 0-2.1.59-2.1 1.43 0 .73.57 1.22 2.34 1.67 1.77.46 3.66 1.22 3.66 3.42-.01 1.6-1.21 2.48-2.74 2.77z"
                                                        opacity=".3"
                                                    />
                                                    <path
                                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z"
                                                    />
                                                </svg>
                                            </div>
                                            <div class="card-item-title mb-2">
                                                <label class="main-content-label tx-13 font-weight-bold mb-1">Total Deposit</label>
                                            </div>
                                            <div class="card-item-body">
                                                <div class="card-item-stat">
                                                    <h4 class="font-weight-bold"><?php echo $symbol.number_format($invested,2);?></h4>
                                                    <small></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
                                <div class="card custom-card">
                                    <div class="card-body">
                                        <div class="card-item">
                                            <div class="card-item-icon card-icon">
                                                <svg class="text-primary" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                                                    <path d="M0 0h24v24H0V0z" fill="none" />
                                                    <path
                                                        d="M12 4c-4.41 0-8 3.59-8 8s3.59 8 8 8 8-3.59 8-8-3.59-8-8-8zm1.23 13.33V19H10.9v-1.69c-1.5-.31-2.77-1.28-2.86-2.97h1.71c.09.92.72 1.64 2.32 1.64 1.71 0 2.1-.86 2.1-1.39 0-.73-.39-1.41-2.34-1.87-2.17-.53-3.66-1.42-3.66-3.21 0-1.51 1.22-2.48 2.72-2.81V5h2.34v1.71c1.63.39 2.44 1.63 2.49 2.97h-1.71c-.04-.97-.56-1.64-1.94-1.64-1.31 0-2.1.59-2.1 1.43 0 .73.57 1.22 2.34 1.67 1.77.46 3.66 1.22 3.66 3.42-.01 1.6-1.21 2.48-2.74 2.77z"
                                                        opacity=".3"
                                                    />
                                                    <path
                                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z"
                                                    />
                                                </svg>
                                            </div>
                                            <div class="card-item-title mb-2">
                                                <label class="main-content-label tx-13 font-weight-bold mb-1">Earnings</label>
                                            </div>
                                            <div class="card-item-body">
                                                <div class="card-item-stat">
                                                    <h4 class="font-weight-bold"><?php echo $symbol.number_format($t_profit,2);?></h4>
                                                    <small></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-3">
                                <div class="card custom-card">
                                    <div class="card-body">
                                        <div class="card-item">
                                            <div class="card-item-icon card-icon">
                                                <svg class="text-primary" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                                                    <path d="M0 0h24v24H0V0z" fill="none" />
                                                    <path
                                                        d="M12 4c-4.41 0-8 3.59-8 8s3.59 8 8 8 8-3.59 8-8-3.59-8-8-8zm1.23 13.33V19H10.9v-1.69c-1.5-.31-2.77-1.28-2.86-2.97h1.71c.09.92.72 1.64 2.32 1.64 1.71 0 2.1-.86 2.1-1.39 0-.73-.39-1.41-2.34-1.87-2.17-.53-3.66-1.42-3.66-3.21 0-1.51 1.22-2.48 2.72-2.81V5h2.34v1.71c1.63.39 2.44 1.63 2.49 2.97h-1.71c-.04-.97-.56-1.64-1.94-1.64-1.31 0-2.1.59-2.1 1.43 0 .73.57 1.22 2.34 1.67 1.77.46 3.66 1.22 3.66 3.42-.01 1.6-1.21 2.48-2.74 2.77z"
                                                        opacity=".3"
                                                    />
                                                    <path
                                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z"
                                                    />
                                                </svg>
                                            </div>
                                            <div class="card-item-title mb-2">
                                                <label class="main-content-label tx-13 font-weight-bold mb-1">Withdraw</label>
                                                <span class="d-block tx-12 mb-0 text-muted"></span>
                                            </div>
                                            <div class="card-item-body">
                                                <div class="card-item-stat">
                                                    <h4 class="font-weight-bold"><?php echo $symbol.number_format($t_withdraw,2);?></h4>
                                                    <small></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12 col-lg-12 col-xl-3">
                                <div class="card custom-card">
                                    <div class="card-body">
                                        <div class="card-item">
                                            <div class="card-item-icon card-icon">
                                                <svg class="text-primary" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                                                    <path d="M0 0h24v24H0V0z" fill="none" />
                                                    <path
                                                        d="M12 4c-4.41 0-8 3.59-8 8s3.59 8 8 8 8-3.59 8-8-3.59-8-8-8zm1.23 13.33V19H10.9v-1.69c-1.5-.31-2.77-1.28-2.86-2.97h1.71c.09.92.72 1.64 2.32 1.64 1.71 0 2.1-.86 2.1-1.39 0-.73-.39-1.41-2.34-1.87-2.17-.53-3.66-1.42-3.66-3.21 0-1.51 1.22-2.48 2.72-2.81V5h2.34v1.71c1.63.39 2.44 1.63 2.49 2.97h-1.71c-.04-.97-.56-1.64-1.94-1.64-1.31 0-2.1.59-2.1 1.43 0 .73.57 1.22 2.34 1.67 1.77.46 3.66 1.22 3.66 3.42-.01 1.6-1.21 2.48-2.74 2.77z"
                                                        opacity=".3"
                                                    />
                                                    <path
                                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z"
                                                    />
                                                </svg>
                                            </div>
                                            <div class="card-item-title mb-2">
                                                <label class="main-content-label tx-13 font-weight-bold mb-1">Wallet Balance</label>
                                                <span class="d-block tx-12 mb-0 text-muted"></span>
                                            </div>
                                            <div class="card-item-body">
                                                <div class="card-item-stat">
                                                    <h4 class="font-weight-bold"><?php echo $symbol.number_format($t_balance,2);?></h4>
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
                            <div class="col-sm-12 col-lg-12 col-xl-8">

                        

                                <!--row-->
                                <div class="row row-sm">
                                    <div class="col-sm-12 col-lg-12 col-xl-12">
                                        <div class="card custom-card overflow-hidden">
                                            <div class="card-header border-bottom-0">
                                                <div>
                                                    <label class="main-content-label mb-2">Revenue</label>
                                                    <span class="d-block tx-12 mb-0 text-muted">
                                                        This is a representation of total invested and total earned for each package invested.
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="card-body pl-0">
                                                <div class>
                                                    <div class="container">
                                                        <script>
                                                            var xPlanDate = <?php echo json_encode($xPlanDate); ?>;
                                                            var yPlanDep = <?php echo json_encode($yPlanDep); ?>;
                                                            var yPlanBal = <?php echo json_encode($yPlanBal); ?>;
                                                            var all_summarry = [...yPlanDep, ...yPlanBal];
                                                            let SummaryMinValue = Math.min(...all_summarry);
                                                            let SummaryMaxValue = Math.max(...all_summarry);
                                                            var sum = 0;
                                                            all_summarry.forEach((number) => {
                                                              sum += number;
                                                            });
                                                            var SummaryAvg = 1000;
                                                        </script>
                                                        <canvas id="chartLine" class="chart-dropshadow2 ht-250"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Row end -->
                            </div>
                        </div>
                        <!-- Row end -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card custom-card">
                                    <div class="card-body">
                                        <div>
                                            <h6 class="main-content-label mb-1">Active Investment</h6>
                                            <p class="text-muted card-sub-title">
                                                Last 5 records
                                            </p>
                                        </div>
                                        <div class="table-responsive">
                                            <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <table class="table dataTable no-footer" id="example1" role="grid" aria-describedby="example1_info">
                                                            <thead>
                                                                <tr role="row">
                                                                    <th >
                                                                        #
                                                                    </th>
                                                                    <th class="" >Portfolio</th>
                                                                    <th class="" >Capital</th>
                                                                    <th class="" >Profit</th>
                                                                    <th class="" >Revenue</th>
                                                                    <th class="" >Created</th>
                                                                    <th class="" >Expires</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr role="row" class="odd">
                                                                    <?php echo $invest_transaction_Dash;?>
                                                                </tr>
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
                        <!-- End Row -->

                    

        <?php include_once '../user_footer.php'; ?>     
        <!-- Internal Morris js -->
        <script src="../assets_inside/plugins/raphael/raphael.min.js"></script>
        <script src="../assets_inside/plugins/morris.js/morris.min.js"></script>

        <!-- Circle Progress js-->
        <script src="../assets_inside/js/circle-progress.min.js"></script>
        

        <!-- Internal Dashboard js-->
        <script src="../assets_inside/js/index.js?v=<?php echo $ver;?>"></script>
        <script>
            $(document).ready(function(){
                    $(".invest_li_con").each(function() {
                    var dataSet = $(this).data("set");
                    var dataSetValues = dataSet.split("|");

                    // Assign the separated values to individual variables
                    var inActive = dataSetValues[0];
                    var timeDiff = dataSetValues[1];
                    var inPlanId = dataSetValues[2];

                    // Use the variables as needed
                    var divId = inPlanId+'_timer'; // ID of the target div
                    if(inActive==1 ){
                            displayCountdown(timeDiff, divId);
                    }

              });
            });
            function displayCountdown(timeDiff, divId) {
                    var countdownElement = document.getElementById(divId);
                    $("#"+divId).css('display','block');
                    // Countdown function
                    function countdown() {
                            var remainingSeconds = timeDiff % 60;
                            var remainingMinutes = Math.floor((timeDiff / 60) % 60);
                            var remainingHours = Math.floor(timeDiff / 3600);

                            // Display remaining hours, minutes, and seconds
                            countdownElement.innerHTML =
                                    remainingHours + "h " +
                                    remainingMinutes + "m " +
                                    remainingSeconds + "s";

                            // Decrement time difference by 1 second
                            timeDiff--;

                            if (timeDiff >= 0) {
                                    // Call the countdown function again after 1 second (1000 milliseconds)
                                    setTimeout(countdown, 1000);
                            } else {
                                    countdownElement.innerHTML = '<a class="badge badge-danger" href="javascript:void(0)">Expired</a>';
                            }
                    }

                    // Start the countdown
                    countdown();
            }

        </script>
    </body>

</html>