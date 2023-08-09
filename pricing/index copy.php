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
if(isset($_GET['select_plan']) && isset($_POST['plan'])){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $plan_code = mysqli_real_escape_string($db_conx, $_POST['plan']);
    $unique = $_SESSION[$site_cokie];
    if($plan_code == "" ){
        echo 2;
        exit();
    }else{
        // DUPLICATE DATA CHECKS 
        $sql = "SELECT id,email,full_name,username FROM users WHERE unique_field='$unique' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $unique_check = mysqli_num_rows($query);
        if ($unique_check < 0){ 
            echo 3;
            exit();
        }else{
            $row = mysqli_fetch_row($query);
            $id = $row[0];
            $e = $row[1];
            $f_n = ucwords($row[2]);
            $uname = $row[3];
            if(''== $f_n){
                $f_n = ucwords($uname);
            }
            $sql = "SELECT active,plan,currency FROM account WHERE user_id='$id' LIMIT 1";
            $query = mysqli_query($db_conx, $sql);
            $row = mysqli_fetch_row($query);
            $active = $row[0];
            $db_plan = $row[1];
            $currency_main = explode("|", $row[2]);
            $currency = $currency_main[0];
            $symbol = end($currency_main);
            if($db_plan !='' || $active == 1){
                echo 4;
                exit();
            }
            $plan = 'Pawn';
            $daily_profit_rate = 0.008;
            $monthly_profit_rate = 0.16;
            $referal_rate = 0.05;
            if($plan_code == '78476358736238'){
                $plan = 'Bishop';
                $daily_profit_rate = 0.016;
                $monthly_profit_rate = 0.32;
                $referal_rate = 0.06;
            }elseif($plan_code == '83436275876387'){
                // 20days
                $plan = 'Knight';
                $daily_profit_rate = 0.024;
                $monthly_profit_rate = 0.48;
                $referal_rate = 0.08;
            }elseif($plan_code == '63588377348762'){
                // 20days
                $plan = 'Rook';
                $daily_profit_rate = 0.03;
                $monthly_profit_rate = 0.6;
                $referal_rate = 0.1;
            }elseif($plan_code == '48765883773632'){
                // 20days
                $plan = 'Crypto IRA';
                $daily_profit_rate = 0.013;
                $monthly_profit_rate = 0.26;
                $referal_rate = 0.1;
            }
            // END FORM DATA ERROR HANDLING
            // Begin Insertion of data into the database
            $new_time = date("Y-m-d H:i:s");
            $sql = "update account set plan='$plan',daily_profit_rate='$daily_profit_rate',monthly_profit_rate='$monthly_profit_rate',updated_at='$new_time' where user_id='$id' limit 1";
            $query = mysqli_query($db_conx, $sql);
            if(!$query){
                echo mysqli_error($db_conx);
                exit();
            }
            $sql = "update referral set referal_percent='$referal_rate' where user_id='$id' limit 1";
            $query = mysqli_query($db_conx, $sql);
            if(!$query){
                echo mysqli_error($db_conx);
                exit();
            }
            echo 1;
            exit();
        }
         
    }
}
//$btn_disable = 'disabled';
//if($plan ==''){
//    $btn_disable = '';
//}
?>
<?php 
    $title ='Investment Pricing | '.$site_name;
    $title2 ='Pricing';
    $keyword ='Investment Pricing,Pricing at '.$site_name.', what we offer, '.$site_link;
    $discription = 'Our Investment Packages: Starter, Investor, and Ultimate.'; 
    $dash = '';
    $dep = '';
    $pricing = 'active';
    $pur_invest = '';
    $my_invest = '';
    $with = '';
    $act_his = '';
    $ref = '';
    $acct_pro = ''; 
    $acct_settings = ''; 
    $acct_security = '';
    $suppt = '';
    include_once '../user_header copy.php'; 
?>

                <!--begin::Content-->
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <!--begin::Content container-->
                                <div id="kt_app_content_container" class="app-container container-xxl">
                                    <!--begin::Pricing card-->
                                    <div class="card" id="">
                                        <!--begin::Card body-->
                                        <div class="card-body p-lg-17">
                                            <!--begin::Plans-->
                                            <div class="d-flex flex-column">
                                                <!--begin::Heading-->
                                                <div class="mb-13 text-center">
                                                    <h1 class="fs-2hx fw-bold mb-5">PREMIUM CRYPTO ASSET PICKS</h1>
                                                    <div class="text-gray-400 fw-semibold fs-5" style=" display:  none">If you need more info about our pricing, please check 
                                                        <a href="#" class="link-primary fw-bold">Pricing Guidelines</a>.
                                                    </div>
                                                </div>
                                                <!--end::Heading-->
                                                <!--begin::Row-->
                                                <div class="row g-10">
                                                    <!--end::Col-->
                                                    <div class="col-xl-12">
                                                        <div class=" ">
                                                            <b>Terms & Conditions:</b> <br/>
                                                            0% Withdrawal Fee,
                                                            8% Management Fee (paid quarterly) <br/>
                                                            8% Upgrade Bonus,
                                                            10% Referral Bonus <br/>
                                                            30 Days Renewable Trading Contract.
                                                        </div>
                                                    </div>
                                                    <!--begin::Col-->
                                                    <div class="col-xl-3">
                                                        <div class="d-flex h-100 align-items-center">
                                                            <!--begin::Option-->
                                                            <div class="w-100 d-flex flex-column flex-center rounded-3 bg-light bg-opacity-75 py-15 px-10">
                                                                <!--begin::Heading-->
                                                                <div class="mb-7 text-center">
                                                                    <!--begin::Title-->
                                                                    <h1 class="text-dark mb-5 fw-bolder">PCAP (Tier 1)</h1>
                                                                    <!--end::Title-->
                                                                    <!--begin::Price-->
                                                                    <div class="text-center">
                                                                        <span class="mb-2 text-primary">$</span>
                                                                        <span class="fs-3x fw-bold text-primary" >1K</span>
                                                                    </div>
                                                                    <!--end::Price-->
                                                                </div>
                                                                <!--end::Heading-->
                                                                <!--begin::Features-->
                                                                <div class="w-100 mb-10">
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Min: $1000
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Max: $50,999
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Monthly APY: 20%
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                </div>
                                                                <!--end::Features-->
                                                                <!--begin::Select-->
                                                                <button class="btn btn-primary er fs-6 px-8 py-4" 
                                                                        onclick="select_portfolio('PCAP (Tier 1)','Tier 1','subscription_modal')">Select</button>
                                                                <!--end::Select-->
                                                            </div>
                                                            <!--end::Option-->
                                                        </div>    
                                                    </div>
                                                    <!--end::Col-->
                                                    <!--begin::Col-->
                                                    <div class="col-xl-3">
                                                        <div class="d-flex h-100 align-items-center">
                                                            <!--begin::Option-->
                                                            <div class="w-100 d-flex flex-column flex-center rounded-3 bg-light bg-opacity-75 py-15 px-10">
                                                                <!--begin::Heading-->
                                                                <div class="mb-7 text-center">
                                                                    <!--begin::Title-->
                                                                    <h1 class="text-dark mb-5 fw-bolder">PCAP (Tier 2)</h1>
                                                                    <!--end::Title-->
                                                                    <!--begin::Price-->
                                                                    <div class="text-center">
                                                                        <span class="mb-2 text-primary">$</span>
                                                                        <span class="fs-3x fw-bold text-primary" >51K</span>
                                                                    </div>
                                                                    <!--end::Price-->
                                                                </div>
                                                                <!--end::Heading-->
                                                                <!--begin::Features-->
                                                                <div class="w-100 mb-10">
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Min: $51,000
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Max: $210,999
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Monthly APY: 25%
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                </div>
                                                                <!--end::Features-->
                                                                <!--begin::Select-->
                                                                <button class="btn btn-primary er fs-6 px-8 py-4" 
                                                                        onclick="select_portfolio('PCAP (Tier 2)','Tier 2','subscription_modal')">Select</button>
                                                                <!--end::Select-->
                                                            </div>
                                                            <!--end::Option-->
                                                        </div>
                                                    </div>
                                                    <!--end::Col-->
                                                    <!--begin::Col-->
                                                    <div class="col-xl-3">
                                                        <div class="d-flex h-100 align-items-center">
                                                            <!--begin::Option-->
                                                            <div class="w-100 d-flex flex-column flex-center rounded-3 bg-light bg-opacity-75 py-15 px-10">
                                                                <!--begin::Heading-->
                                                                <div class="mb-7 text-center">
                                                                    <!--begin::Title-->
                                                                    <h1 class="text-dark mb-5 fw-bolder">PCAP (Tier 3)</h1>
                                                                    <!--end::Title-->
                                                                    <!--begin::Price-->
                                                                    <div class="text-center">
                                                                        <span class="mb-2 text-primary">$</span>
                                                                        <span class="fs-3x fw-bold text-primary" >211K</span>
                                                                    </div>
                                                                    <!--end::Price-->
                                                                </div>
                                                                <!--end::Heading-->
                                                                <!--begin::Features-->
                                                                <div class="w-100 mb-10">
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Min: $211,000
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Max: $500,999
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Monthly APY: 30%
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                </div>
                                                                <!--end::Features-->
                                                                <!--begin::Select-->
                                                                <button class="btn btn-primary er fs-6 px-8 py-4" 
                                                                        onclick="select_portfolio('PCAP (Tier 3)','Tier 3','subscription_modal')">Select</button>
                                                                <!--end::Select-->
                                                            </div>
                                                            <!--end::Option-->
                                                        </div>
                                                    </div>
                                                    <!--end::Col-->
                                                    <!--begin::Col-->
                                                    <div class="col-xl-3">
                                                        <div class="d-flex h-100 align-items-center">
                                                            <!--begin::Option-->
                                                            <div class="w-100 d-flex flex-column flex-center rounded-3 bg-light bg-opacity-75 py-15 px-10">
                                                                <!--begin::Heading-->
                                                                <div class="mb-7 text-center">
                                                                    <!--begin::Title-->
                                                                    <h1 class="text-dark mb-5 fw-bolder">PCAP (Tier 4)</h1>
                                                                    <!--end::Title-->
                                                                    <!--begin::Price-->
                                                                    <div class="text-center">
                                                                        <span class="mb-2 text-primary">$</span>
                                                                        <span class="fs-3x fw-bold text-primary" >501K</span>
                                                                    </div>
                                                                    <!--end::Price-->
                                                                </div>
                                                                <!--end::Heading-->
                                                                <!--begin::Features-->
                                                                <div class="w-100 mb-10">
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Min: $501,000
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Max: none
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Monthly APY: 35%+
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                </div>
                                                                <!--end::Features-->
                                                                <!--begin::Select-->
                                                                <button class="btn btn-primary er fs-6 px-8 py-4" 
                                                                        onclick="select_portfolio('PCAP (Tier 4)','Tier 4','subscription_modal')">Select</button>
                                                                <!--end::Select-->
                                                            </div>
                                                            <!--end::Option-->
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                <!--end::Row-->
                                            </div>
                                            <!--end::Plans-->
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <!--end::Pricing card-->
                                    <!--begin::Pricing card-->
                                    <div class="card" id="" style=" margin-top: 50px">
                                        <!--begin::Card body-->
                                        <div class="card-body p-lg-17">
                                            <!--begin::Plans-->
                                            <div class="d-flex flex-column">
                                                <!--begin::Heading-->
                                                <div class="mb-13 text-center">
                                                    <h1 class="fs-2hx fw-bold mb-5">PREMIUM STOCK PICKS </h1>
                                                    <div class="text-gray-400 fw-semibold fs-5" style=" display:  none">If you need more info about our pricing, please check 
                                                        <a href="#" class="link-primary fw-bold">Pricing Guidelines</a>.
                                                    </div>
                                                </div>
                                                <!--end::Heading-->
                                                <!--begin::Row-->
                                                <div class="row g-10">
                                                    <!--end::Col-->
                                                    <div class="col-xl-12">
                                                        <div class=" ">
                                                            <b>Terms & Conditions:</b> <br/>
                                                            0% Withdrawal Fee,
                                                            8% Management Fee (paid quarterly) <br/>
                                                            8% Upgrade Bonus,
                                                            10% Referral Bonus <br/>
                                                            30 Days Renewable Trading Contract.
                                                        </div>
                                                    </div>
                                                    <!--begin::Col-->
                                                    <div class="col-xl-3">
                                                        <div class="d-flex h-100 align-items-center">
                                                            <!--begin::Option-->
                                                            <div class="w-100 d-flex flex-column flex-center rounded-3 bg-light bg-opacity-75 py-15 px-10">
                                                                <!--begin::Heading-->
                                                                <div class="mb-7 text-center">
                                                                    <!--begin::Title-->
                                                                    <h1 class="text-dark mb-5 fw-bolder">PSP (Tier 1)</h1>
                                                                    <!--end::Title-->
                                                                    <!--begin::Price-->
                                                                    <div class="text-center">
                                                                        <span class="mb-2 text-primary">$</span>
                                                                        <span class="fs-3x fw-bold text-primary" >1K</span>
                                                                    </div>
                                                                    <!--end::Price-->
                                                                </div>
                                                                <!--end::Heading-->
                                                                <!--begin::Features-->
                                                                <div class="w-100 mb-10">
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Min: $1000
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Max: $50,999
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Monthly APY: 20%
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                </div>
                                                                <!--end::Features-->
                                                                <!--begin::Select-->
                                                                <button class="btn btn-primary er fs-6 px-8 py-4" 
                                                                        onclick="select_portfolio('PSP (Tier 1)','Tier 1','subscription_modal')">Select</button>
                                                                <!--end::Select-->
                                                            </div>
                                                            <!--end::Option-->
                                                        </div>    
                                                    </div>
                                                    <!--end::Col-->
                                                    <!--begin::Col-->
                                                    <div class="col-xl-3">
                                                        <div class="d-flex h-100 align-items-center">
                                                            <!--begin::Option-->
                                                            <div class="w-100 d-flex flex-column flex-center rounded-3 bg-light bg-opacity-75 py-15 px-10">
                                                                <!--begin::Heading-->
                                                                <div class="mb-7 text-center">
                                                                    <!--begin::Title-->
                                                                    <h1 class="text-dark mb-5 fw-bolder">PSP (Tier 2)</h1>
                                                                    <!--end::Title-->
                                                                    <!--begin::Price-->
                                                                    <div class="text-center">
                                                                        <span class="mb-2 text-primary">$</span>
                                                                        <span class="fs-3x fw-bold text-primary" >51K</span>
                                                                    </div>
                                                                    <!--end::Price-->
                                                                </div>
                                                                <!--end::Heading-->
                                                                <!--begin::Features-->
                                                                <div class="w-100 mb-10">
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Min: $51,000
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Max: $210,999
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Monthly APY: 25%
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                </div>
                                                                <!--end::Features-->
                                                                <!--begin::Select-->
                                                                <button class="btn btn-primary er fs-6 px-8 py-4" 
                                                                        onclick="select_portfolio('PSP (Tier 2)','Tier 2','subscription_modal')">Select</button>
                                                                <!--end::Select-->
                                                            </div>
                                                            <!--end::Option-->
                                                        </div>
                                                    </div>
                                                    <!--end::Col-->
                                                    <!--begin::Col-->
                                                    <div class="col-xl-3">
                                                        <div class="d-flex h-100 align-items-center">
                                                            <!--begin::Option-->
                                                            <div class="w-100 d-flex flex-column flex-center rounded-3 bg-light bg-opacity-75 py-15 px-10">
                                                                <!--begin::Heading-->
                                                                <div class="mb-7 text-center">
                                                                    <!--begin::Title-->
                                                                    <h1 class="text-dark mb-5 fw-bolder">PSP (Tier 3)</h1>
                                                                    <!--end::Title-->
                                                                    <!--begin::Price-->
                                                                    <div class="text-center">
                                                                        <span class="mb-2 text-primary">$</span>
                                                                        <span class="fs-3x fw-bold text-primary" >211K</span>
                                                                    </div>
                                                                    <!--end::Price-->
                                                                </div>
                                                                <!--end::Heading-->
                                                                <!--begin::Features-->
                                                                <div class="w-100 mb-10">
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Min: $211,000
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Max: $500,999
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Monthly APY: 30%
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                </div>
                                                                <!--end::Features-->
                                                                <!--begin::Select-->
                                                                <button class="btn btn-primary er fs-6 px-8 py-4" 
                                                                        onclick="select_portfolio('PSP (Tier 3)','Tier 3','subscription_modal')">Select</button>
                                                                <!--end::Select-->
                                                            </div>
                                                            <!--end::Option-->
                                                        </div>
                                                    </div>
                                                    <!--end::Col-->
                                                    <!--begin::Col-->
                                                    <div class="col-xl-3">
                                                        <div class="d-flex h-100 align-items-center">
                                                            <!--begin::Option-->
                                                            <div class="w-100 d-flex flex-column flex-center rounded-3 bg-light bg-opacity-75 py-15 px-10">
                                                                <!--begin::Heading-->
                                                                <div class="mb-7 text-center">
                                                                    <!--begin::Title-->
                                                                    <h1 class="text-dark mb-5 fw-bolder">PSP (Tier 4)</h1>
                                                                    <!--end::Title-->
                                                                    <!--begin::Price-->
                                                                    <div class="text-center">
                                                                        <span class="mb-2 text-primary">$</span>
                                                                        <span class="fs-3x fw-bold text-primary" >501K</span>
                                                                    </div>
                                                                    <!--end::Price-->
                                                                </div>
                                                                <!--end::Heading-->
                                                                <!--begin::Features-->
                                                                <div class="w-100 mb-10">
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Min: $501,000
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Max: none
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Monthly APY: 35%+
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                </div>
                                                                <!--end::Features-->
                                                                <!--begin::Select-->
                                                                <button class="btn btn-primary er fs-6 px-8 py-4" 
                                                                        onclick="select_portfolio('PSP (Tier 4)','Tier 4','subscription_modal')">Select</button>
                                                                <!--end::Select-->
                                                            </div>
                                                            <!--end::Option-->
                                                        </div>
                                                    </div>
                                                    <!--end::Col-->
                                                </div>
                                                <!--end::Row-->
                                            </div>
                                            <!--end::Plans-->
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <!--end::Pricing card-->
                                    <!--begin::Pricing card-->
                                    <div class="card" id="" style=" margin-top: 50px">
                                        <!--begin::Card body-->
                                        <div class="card-body p-lg-17">
                                            <!--begin::Plans-->
                                            <div class="d-flex flex-column">
                                                <!--begin::Heading-->
                                                <div class="mb-13 text-center">
                                                    <h1 class="fs-2hx fw-bold mb-5">PREMIUM CURRENCY PAIR PICKS</h1>
                                                    <div class="text-gray-400 fw-semibold fs-5" style=" display:  none">If you need more info about our pricing, please check 
                                                        <a href="#" class="link-primary fw-bold">Pricing Guidelines</a>.
                                                    </div>
                                                </div>
                                                <!--end::Heading-->
                                                <!--begin::Row-->
                                                <div class="row g-10">
                                                    <!--end::Col-->
                                                    <div class="col-xl-12">
                                                        <div class=" ">
                                                            <b>Terms & Conditions:</b> <br/>
                                                            0% Withdrawal Fee,
                                                            8% Management Fee (paid quarterly) <br/>
                                                            8% Upgrade Bonus,
                                                            10% Referral Bonus <br/>
                                                            30 Days Renewable Trading Contract.
                                                        </div>
                                                    </div>
                                                    <!--begin::Col-->
                                                    <div class="col-xl-3">
                                                        <div class="d-flex h-100 align-items-center">
                                                            <!--begin::Option-->
                                                            <div class="w-100 d-flex flex-column flex-center rounded-3 bg-light bg-opacity-75 py-15 px-10">
                                                                <!--begin::Heading-->
                                                                <div class="mb-7 text-center">
                                                                    <!--begin::Title-->
                                                                    <h1 class="text-dark mb-5 fw-bolder">PCPP (Tier 1)</h1>
                                                                    <!--end::Title-->
                                                                    <!--begin::Price-->
                                                                    <div class="text-center">
                                                                        <span class="mb-2 text-primary">$</span>
                                                                        <span class="fs-3x fw-bold text-primary" >1K</span>
                                                                    </div>
                                                                    <!--end::Price-->
                                                                </div>
                                                                <!--end::Heading-->
                                                                <!--begin::Features-->
                                                                <div class="w-100 mb-10">
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Min: $1000
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Max: $50,999
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Monthly APY: 20%
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                </div>
                                                                <!--end::Features-->
                                                                <!--begin::Select-->
                                                                <button class="btn btn-primary er fs-6 px-8 py-4" 
                                                                        onclick="select_portfolio('PCPP (Tier 1)','Tier 1','subscription_modal')">Select</button>
                                                                <!--end::Select-->
                                                            </div>
                                                            <!--end::Option-->
                                                        </div>    
                                                    </div>
                                                    <!--end::Col-->
                                                    <!--begin::Col-->
                                                    <div class="col-xl-3">
                                                        <div class="d-flex h-100 align-items-center">
                                                            <!--begin::Option-->
                                                            <div class="w-100 d-flex flex-column flex-center rounded-3 bg-light bg-opacity-75 py-15 px-10">
                                                                <!--begin::Heading-->
                                                                <div class="mb-7 text-center">
                                                                    <!--begin::Title-->
                                                                    <h1 class="text-dark mb-5 fw-bolder">PCPP (Tier 2)</h1>
                                                                    <!--end::Title-->
                                                                    <!--begin::Price-->
                                                                    <div class="text-center">
                                                                        <span class="mb-2 text-primary">$</span>
                                                                        <span class="fs-3x fw-bold text-primary" >51K</span>
                                                                    </div>
                                                                    <!--end::Price-->
                                                                </div>
                                                                <!--end::Heading-->
                                                                <!--begin::Features-->
                                                                <div class="w-100 mb-10">
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Min: $51,000
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Max: $210,999
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Monthly APY: 25%
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                </div>
                                                                <!--end::Features-->
                                                                <!--begin::Select-->
                                                                <button class="btn btn-primary er fs-6 px-8 py-4" 
                                                                        onclick="select_portfolio('PCPP (Tier 2)','Tier 2','subscription_modal')">Select</button>
                                                                <!--end::Select-->
                                                            </div>
                                                            <!--end::Option-->
                                                        </div>
                                                    </div>
                                                    <!--end::Col-->
                                                    <!--begin::Col-->
                                                    <div class="col-xl-3">
                                                        <div class="d-flex h-100 align-items-center">
                                                            <!--begin::Option-->
                                                            <div class="w-100 d-flex flex-column flex-center rounded-3 bg-light bg-opacity-75 py-15 px-10">
                                                                <!--begin::Heading-->
                                                                <div class="mb-7 text-center">
                                                                    <!--begin::Title-->
                                                                    <h1 class="text-dark mb-5 fw-bolder">PCPP (Tier 3)</h1>
                                                                    <!--end::Title-->
                                                                    <!--begin::Price-->
                                                                    <div class="text-center">
                                                                        <span class="mb-2 text-primary">$</span>
                                                                        <span class="fs-3x fw-bold text-primary" >211K</span>
                                                                    </div>
                                                                    <!--end::Price-->
                                                                </div>
                                                                <!--end::Heading-->
                                                                <!--begin::Features-->
                                                                <div class="w-100 mb-10">
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Min: $211,000
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Max: $500,999
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Monthly APY: 30%
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                </div>
                                                                <!--end::Features-->
                                                                <!--begin::Select-->
                                                                <button class="btn btn-primary er fs-6 px-8 py-4" 
                                                                        onclick="select_portfolio('PCPP (Tier 3)','Tier 3','subscription_modal')">Select</button>
                                                                <!--end::Select-->
                                                            </div>
                                                            <!--end::Option-->
                                                        </div>
                                                    </div>
                                                    <!--end::Col-->
                                                    <!--begin::Col-->
                                                    <div class="col-xl-3">
                                                        <div class="d-flex h-100 align-items-center">
                                                            <!--begin::Option-->
                                                            <div class="w-100 d-flex flex-column flex-center rounded-3 bg-light bg-opacity-75 py-15 px-10">
                                                                <!--begin::Heading-->
                                                                <div class="mb-7 text-center">
                                                                    <!--begin::Title-->
                                                                    <h1 class="text-dark mb-5 fw-bolder">PCPP (Tier 4)</h1>
                                                                    <!--end::Title-->
                                                                    <!--begin::Price-->
                                                                    <div class="text-center">
                                                                        <span class="mb-2 text-primary">$</span>
                                                                        <span class="fs-3x fw-bold text-primary" >501K</span>
                                                                    </div>
                                                                    <!--end::Price-->
                                                                </div>
                                                                <!--end::Heading-->
                                                                <!--begin::Features-->
                                                                <div class="w-100 mb-10">
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Min: $501,000
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Max: none
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Monthly APY: 35%+
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                </div>
                                                                <!--end::Features-->
                                                                <!--begin::Select-->
                                                                <button class="btn btn-primary er fs-6 px-8 py-4" 
                                                                        onclick="select_portfolio('PCPP (Tier 4)','Tier 4','subscription_modal')">Select</button>
                                                                <!--end::Select-->
                                                            </div>
                                                            <!--end::Option-->
                                                        </div>
                                                    </div>
                                                    <!--end::Col-->
                                                </div>
                                                <!--end::Row-->
                                            </div>
                                            <!--end::Plans-->
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <!--end::Pricing card-->
                                    <!--begin::Pricing card-->
                                    <div class="card" id="" style=" margin-top: 50px">
                                        <!--begin::Card body-->
                                        <div class="card-body p-lg-17">
                                            <!--begin::Plans-->
                                            <div class="d-flex flex-column">
                                                <!--begin::Heading-->
                                                <div class="mb-13 text-center">
                                                    <h1 class="fs-2hx fw-bold mb-5">IRA PICKS</h1>
                                                    
                                                </div>
                                                <!--end::Heading-->
                                                <!--begin::Row-->
                                                <div class="row g-10">
                                                    <!--end::Col-->
                                                    <div class="col-xl-12">
                                                        <div class=" ">
                                                            <b>Terms & Conditions:</b> <br/>
                                                            0% Withdrawal Fee,
                                                            8% Management Fee (paid quarterly) <br/>
                                                            8% Upgrade Bonus,
                                                            10% Referral Bonus <br/>
                                                            Contract duration: unlimited
                                                        </div>
                                                    </div>
                                                    <!--begin::Col-->
                                                    <div class="col-xl-4">
                                                        <div class="d-flex h-100 align-items-center">
                                                            <!--begin::Option-->
                                                            <div class="w-100 d-flex flex-column flex-center rounded-3 bg-light bg-opacity-75 py-15 px-10">
                                                                <!--begin::Heading-->
                                                                <div class="mb-7 text-center">
                                                                    <!--begin::Title-->
                                                                    <h1 class="text-dark mb-5 fw-bolder">Crypto IRA</h1>
                                                                    <!--end::Title-->
                                                                    <!--begin::Price-->
                                                                    <div class="text-center">
                                                                        <span class="mb-2 text-primary">$</span>
                                                                        <span class="fs-3x fw-bold text-primary" >50K</span>
                                                                    </div>
                                                                    <!--end::Price-->
                                                                </div>
                                                                <!--end::Heading-->
                                                                <!--begin::Features-->
                                                                <div class="w-100 mb-10">
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Min: $50,000
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Max: unlimited
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Daily ROI: 1.3%%
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            Monthly ROI: 26%
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                    <!--begin::Item-->
                                                                    <div class="d-flex align-items-center mb-5">
                                                                        <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">
                                                                            APY: 312%
                                                                        </span>
                                                                        <!--begin::Svg Icon | path: icons/duotune/general/gen043.svg-->
                                                                        <span class="svg-icon svg-icon-1 svg-icon-success">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                                <path
                                                                                    d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z"
                                                                                    fill="currentColor"
                                                                                />
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                    </div>
                                                                    <!--end::Item-->
                                                                </div>
                                                                <!--end::Features-->
                                                                <!--begin::Select-->
                                                                <button class="btn btn-primary er fs-6 px-8 py-4" 
                                                                        onclick="select_portfolio('Crypto IRA','Crypto IRA','subscription_modal')">Select</button>
                                                                <!--end::Select-->
                                                            </div>
                                                            <!--end::Option-->
                                                        </div>    
                                                    </div>
                                                    <!--end::Col-->
                                                </div>
                                                <!--end::Row-->
                                            </div>
                                            <!--end::Plans-->
                                        </div>
                                        <!--end::Card body-->
                                    </div>
                                    <!--end::Pricing card-->
                                </div>
                                <!--end::Content container-->
                            </div>
                            <!--end::Content-->
                            <div class="modal fade" id="subscription_modal" tabindex="-1" style="display: none;">
                                <!--begin::Modal dialog-->
                                <div class="modal-dialog modal-lg p-9">
                                    <!--begin::Modal content-->
                                    <div class="modal-content modal-rounded">
                                        <!--begin::Modal header-->
                                        <div class="modal-header py-7 d-flex justify-content-between">
                                            <!--begin::Modal title-->
                                            <h2>Subscribe to <span id="plan_name"></span> package</h2>
                                            <!--end::Modal title-->
                                            <!--begin::Close-->
                                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-kt-modal-action-type="close" 
                                                onclick="$('#subscription_modal').modal('hide');">
                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                                <span class="svg-icon svg-icon-1">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                            </div>
                                            <!--end::Close-->
                                        </div>
                                        <!--begin::Modal header-->
                                        <!--begin::Modal body-->
                                        <div class="modal-body scroll-y m-5">
                                            <div class="stepper stepper-links d-flex flex-column gap-5" id="kt_modal_top_up_wallet_stepper" data-kt-stepper="true">
                                                <!--begin::Nav-->
                                                <div class="stepper-nav justify-content-center py-2">
                                                    <!--begin::Step 1-->
                                                    <div class="stepper-item current" data-kt-stepper-element="nav" id="invest_step_one_head">
                                                        <h3 class="stepper-title">Amount</h3>
                                                    </div>
                                                    <!--end::Step 1-->
                                                    <!--begin::Step 4-->
                                                    <div class="stepper-item" data-kt-stepper-element="nav" id="invest_step_two_head">
                                                        <h3 class="stepper-title">Completed</h3>
                                                    </div>
                                                    <!--end::Step 4-->
                                                </div>
                                                <!--end::Nav-->
                                                <!--begin::Form-->
                                                <form class="mx-auto w-100 mw-600px pt-15 pb-10 fv-plugins-bootstrap5 fv-plugins-framework" 
                                                    novalidate="novalidate" onsubmit="return false;" method="POST" id="invest_form" autocomplete="off">
                                                    <!--begin::Step 1-->
                                                    <div class="current" data-kt-stepper-element="content" id="invest_step_one">
                                                        <!--begin::Wrapper-->
                                                        <div class="w-100">
                                                            <!--begin::Heading-->
                                                            <div class="pb-10 pb-lg-15">
                                                                <!--begin::Title-->
                                                                <h2 class="fw-bold d-flex align-items-center text-dark">
                                                                    Wallet Balance <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" aria-label="You will be charged the set amount from your wallet balance" data-kt-initialized="1"></i>
                                                                </h2>
                                                                <!--end::Title-->
                                                                <!--begin::Notice-->
                                                                <div class="text-muted fw-semibold fs-6">
                                                                    <?php echo $symbol.number_format($total_bal,2);?>
                                                                </div>
                                                                <!--end::Notice-->
                                                            </div>
                                                            <!--end::Heading-->
                                                            <!--begin::Input group-->
                                                            <div class="mb-10 fv-row fv-plugins-icon-container">
                                                                <!--begin::Label-->
                                                                <label class="form-label mb-3">
                                                                    <span class="required">Amount (minimum $<span id="min_amount">1,000</span>)
                                                                    </span>
                                                                </label>
                                                                <!--end::Label-->
                                                                <!--begin::Input-->
                                                                <input type="text" class="form-control form-control-lg form-control-solid" required=""
                                                                    name="amount" id="amount" placeholder="Amount (to the nearest dollar)" autocomplete="off"  />
                                                                <!--end::Input-->
                                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                                            </div>
                                                            <!--end::Input group-->
                                                            <div class="mb-10 fv-row fv-plugins-icon-container">
                                                                <!--end::Input-->
                                                                <div class="" style="width: 100%" id="status_step"></div>
                                                                <input type="hidden" class="form-control form-control-lg form-control-solid" 
                                                                       name="plan" id="plan" value="" />
                                                                <input type="hidden" class="form-control form-control-lg form-control-solid" 
                                                                       name="plan_tire" id="plan_tire" value="" />
                                                            </div>
                                                            <!--begin::Input group-->
                                                            <div class=" fv-row fv-plugins-icon-container text-right" style=" margin-top: 50px; float: right">
                                                                <button type="button" class="btn btn-lg btn-primary" id="sub_btn" onclick="purchase_portfolio();" >
                                                                    Submit
                                                                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr064.svg-->
                                                                    <span class="svg-icon svg-icon-3 ms-1 me-0">
                                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="currentColor"></rect>
                                                                            <path
                                                                                d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z"
                                                                                fill="currentColor"
                                                                            ></path>
                                                                        </svg>
                                                                    </span>
                                                                    <!--end::Svg Icon-->
                                                                </button>
                                                            </div>
                                                            <!--end::Input group-->
                                                        </div>
                                                        <!--end::Wrapper-->
                                                    </div>
                                                    <!--end::Step 1-->
                                                    <!--begin::Step 5-->
                                                    <div data-kt-stepper-element="content" id="invest_step_two">
                                                        <!--begin::Wrapper-->
                                                        <div class="w-100">
                                                            <!--begin::Heading-->
                                                            <div class="pb-12 text-center">
                                                                <!--begin::Title-->
                                                                <h1 class="fw-bold text-dark mb-10">Successful Portfolio Subscription!</h1>
                                                                <!--end::Title-->
                                                            </div>
                                                            <!--end::Heading-->
                                                            <!--begin::Actions-->
                                                            <div class="d-flex flex-center">
                                                                <a href="../dashboard/" class="btn btn-lg btn-light me-3" >Dashboard</a>
                                                                <a href="../my-investments/" class="btn btn-lg btn-primary" >View Portfolio</a>
                                                            </div>
                                                            <!--end::Actions-->
                                                            <!--begin::Illustration-->
                                                            <div class="text-center px-4">
                                                                <img src="../assets_inside/img/7.png" alt="" class="mww-100 mh-350px" />
                                                            </div>
                                                            <!--end::Illustration-->
                                                        </div>
                                                    </div>
                                                    <!--end::Step 5-->
                                                    <div></div>
                                                    <div></div>
                                                    <div></div>
                                                </form>
                                                <!--end::Form-->
                                            </div>
                                        </div>
                                        <!--begin::Modal body-->
                                    </div>
                                </div>
                            </div>
        <?php include_once '../user_footer copy.php'; ?>                
    </body>
</html>