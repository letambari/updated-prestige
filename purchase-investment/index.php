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
    $title ='Purchase Investment | '.$site_name.'';
    $title2 ='Purchase Investment';
    $keyword ='';
    $discription = '';  
    $dash = '';
    $dep = '';
    $pricing = '';
    $pur_invest = 'active';
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
            <!-- Page Header -->
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

            <div class="row row-sm mb-5">
                <div class="col-xl-8 col-lg-12 col-md-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div>
                                <h6 class="main-content-label mb-1">Invest</h6>
                                <p class="text-muted card-sub-title">This form  helps you make fresh investments.</p>
                            </div>
                            <form onsubmit="return false;" method="POST" id="invest_form" autocomplete="off" data-parsley-validate="" novalidate="">
                                <div class="" id="invest_step_one">
                                    <div class="form-group">
                                        <label class="form-label">Select Portfolio: <span class="tx-danger">*</span></label>
                                        <select name="plan" id="plan" class="form-control" required onchange="select_plan(this.value);">
                                            <option value="" selected>- Select-</option>
                                            <option value="Pack 101">Pack 101</option>
                                            <option value="Pack 104">Pack 104 (compounding)</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Amount: <span class="tx-danger">*</span> (minimum $<span id="min_amount">100</span>) </label>
                                        <div class="input-group transparent-append">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"> <?php echo $symbol;?> </span>
                                            </div>
                                            <input type="text" class="form-control" min="100" placeholder="amount" aria-label="Amount (to the nearest dollar)" name="amount" id="amount" />
                                            <div class="input-group-append">
                                                <span class="input-group-text"> .00 </span>
                                            </div>
                                        </div>
                                            
                                    </div>
                                    <div class="form-group">
                                        <div class="" style="width: 100%;" id="status_step"></div>
                                    </div>
                                    
                                    <button class="btn ripple btn-main-primary" type="submit"  id="sub_btn">Submit</button>
                                </div>
                                <div style="display: none; padding: 40px 0" id="invest_step_two">
                                    <div class="card alert-message">
                                        <div class="card-body">
                                            <div class="text-center text-white">
                                                <svg class="alert-icons" enable-background="new 0 0 512 512" version="1.1" viewBox="0 0 512 512" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="m491.38 157.66c-13.15-30.297-31.856-57.697-55.598-81.439s-51.142-42.448-81.439-55.598c-31.529-13.686-64.615-20.625-98.338-20.625s-66.809 6.939-98.338 20.625c-30.297 13.15-57.697 31.856-81.439 55.598s-42.448 51.142-55.598 81.439c-13.686 31.529-20.625 64.615-20.625 98.338s6.939 66.809 20.625 98.338c13.149 30.297 31.855 57.697 55.598 81.439 23.742 23.742 51.142 42.448 81.439 55.598 31.529 13.686 64.615 20.625 98.338 20.625s66.809-6.939 98.338-20.625c30.297-13.15 57.697-31.856 81.439-55.598s42.448-51.142 55.598-81.439c13.686-31.529 20.625-64.615 20.625-98.338s-6.939-66.809-20.625-98.338zm-235.38 334.34c-127.92 0-236-108.08-236-236s108.08-236 236-236 236 108.08 236 236-108.08 236-236 236z"
                                                    ></path>
                                                    <path
                                                        d="m451.98 173.8c-10.87-25.256-26.363-48.044-46.049-67.729-19.686-19.686-42.473-35.179-67.729-46.049-26.249-11.298-53.904-17.026-82.197-17.026-38.462 0-78.555 13.134-115.94 37.981-4.6 3.057-5.851 9.264-2.794 13.863 3.057 4.6 9.264 5.85 13.863 2.794 34.1-22.66 70.365-34.638 104.88-34.638 104.62 0 193 88.383 193 193s-88.383 193-193 193-193-88.383-193-193c0-34.504 11.975-70.771 34.629-104.88 3.056-4.601 1.804-10.807-2.796-13.863-4.602-3.056-10.807-1.803-13.863 2.797-24.84 37.397-37.97 77.489-37.97 115.94 0 28.293 5.728 55.948 17.025 82.196 10.87 25.256 26.363 48.044 46.049 67.729 19.686 19.687 42.473 35.179 67.73 46.05 26.248 11.297 53.903 17.025 82.196 17.025s55.948-5.728 82.196-17.025c25.256-10.87 48.044-26.363 67.729-46.049 19.686-19.686 35.179-42.473 46.049-67.729 11.298-26.249 17.026-53.904 17.026-82.197s-5.728-55.948-17.025-82.196z"
                                                    ></path>
                                                    <path d="m115 105c-5.52 0-10 4.48-10 10s4.48 10 10 10 10-4.48 10-10-4.48-10-10-10z"></path>
                                                    <path
                                                        d="m374.28 177.72c-7.557-7.557-17.6-11.719-28.281-11.719s-20.724 4.162-28.281 11.719l-91.719 91.719-31.719-31.719c-7.557-7.557-17.6-11.719-28.281-11.719s-20.724 4.162-28.278 11.716c-7.559 7.553-11.722 17.597-11.722 28.284s4.163 20.731 11.719 28.281l60 60c7.557 7.557 17.601 11.719 28.281 11.719s20.724-4.162 28.281-11.719l120-120c7.559-7.553 11.722-17.597 11.722-28.284s-4.163-20.731-11.719-28.281zm-14.142 42.42-120 120c-3.78 3.779-8.801 5.861-14.139 5.861s-10.359-2.082-14.139-5.861l-60.003-60.003c-3.777-3.775-5.858-8.795-5.858-14.136s2.081-10.361 5.861-14.139c3.78-3.779 8.801-5.861 14.139-5.861s10.359 2.082 14.139 5.861l45.861 45.861 105.86-105.86c3.78-3.779 8.801-5.861 14.139-5.861s10.359 2.082 14.142 5.864c3.777 3.775 5.858 8.795 5.858 14.136s-2.081 10.361-5.861 14.139z"
                                                    ></path>
                                                </svg>
                                                <h3 class="mt-4 mb-3">Success</h3>
                                                <p class="tx-18 text-white-50">Package successfully subscribed and is active on your account.</p>
                                                <a href="../dashboard/" class="btn btn-success">Back to Home</a>
                                                <a href="../my-investments/" class="btn btn-light">View Portfolio</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>


            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card custom-card overflow-hidden">
                        <div class="card-body">
                            <div>
                                <h6 class="main-content-label mb-1">History</h6>
                            </div>
                            <div class="table-responsive">
                                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table dataTable no-footer" id="example1" role="grid" aria-describedby="example1_info">
                                                <thead>
                                                    <tr role="row">
                                                        <th class="wd-20p sorting_asc">
                                                            #
                                                        </th>
                                                        <th class="wd-20p sorting">Reference</th>

                                                        <th class="wd-15p sorting" >Portfolio ID</th>

                                                        <th class="wd-15p sorting" >Type</th>
                                                        <th class="wd-15p sorting" >Amount(USD)</th>
                                                        <th class="wd-20p sorting" >Channel</th>
                                                        <th class="wd-20p sorting" >Status</th>
                                                        <th class="wd-20p sorting" >Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php echo $purchase_transaction;?>
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



        <?php include_once '../user_footer.php'; ?>    
</body>

</html>
    