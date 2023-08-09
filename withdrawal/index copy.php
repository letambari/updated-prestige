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
    $title ='Withdrawal | '.$site_name;
    $title2 ='Withdrawal';
    $keyword ='';
    $discription = '';  
    $dash = '';
    $dep = '';
    $pricing = '';
    $pur_invest = '';
    $my_invest = '';
    $with = 'active';
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
                    <div id="kt_app_content_container" class="app-container container-fluid">
                        <div class="card mb-6 mb-xl-9">
                            <div class="card-body pt-9 pb-0">
                                <!--begin::Details-->
                                <div class="d-flex flex-wrap flex-sm-nowrap mb-6">
                                    <!--begin::Wrapper-->
                                    <div class="flex-grow-1">
                                        <!--begin::Head-->
                                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                            <!--begin::Details-->
                                            <div class="d-flex flex-column">
                                                <!--begin::Status-->
                                                <div class="d-flex align-items-center mb-1">
                                                    <a href="#" class="text-gray-800 text-hover-primary fs-2 fw-bold me-3">Overview</a>
                                                </div>
                                                <!--end::Status-->
                                            </div>
                                            <!--end::Details-->
                                        </div>
                                        <!--end::Head-->
                                        <!--begin::Info-->
                                        <div class="d-flex flex-wrap justify-content-start">
                                            <!--begin::Stats-->
                                            <div class="d-flex flex-wrap">
                                                <!--begin::Stat-->
                                                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                    <!--begin::Number-->
                                                    <div class="d-flex align-items-center">
                                                        <div class="fs-4 fw-bold"><?php echo $symbol.number_format($invested,2);?></div>
                                                    </div>
                                                    <!--end::Number-->
                                                    <!--begin::Label-->
                                                    <div class="fw-semibold fs-6 text-gray-400">
                                                        Invested
                                                    </div>
                                                    <!--end::Label-->
                                                </div>
                                                <!--end::Stat-->
                                                <!--begin::Stat-->
                                                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                    <!--begin::Number-->
                                                    <div class="d-flex align-items-center">
                                                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr065.svg-->
                                                        <span class="svg-icon svg-icon-3 svg-icon-success me-2">
                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor"></rect>
                                                                <path
                                                                    d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
                                                                    fill="currentColor"
                                                                ></path>
                                                            </svg>
                                                        </span>
                                                        <!--end::Svg Icon-->
                                                        <div class="fs-4 fw-bold counted" data-kt-countup="true" 
                                                             data-kt-countup-value="<?php echo $t_profit;?>" data-kt-initialized="1">
                                                                 <?php echo $symbol.number_format($t_profit,2);?>
                                                        </div>
                                                    </div>
                                                    <!--end::Number-->
                                                    <!--begin::Label-->
                                                    <div class="fw-semibold fs-6 text-gray-400">Earnings</div>
                                                    <!--end::Label-->
                                                </div>
                                                <!--end::Stat-->
                                                <!--begin::Stat-->
                                                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                    <!--begin::Number-->
                                                    <div class="d-flex align-items-center">                                                        
                                                        <div class="fs-4 fw-bold counted" data-kt-countup="true" 
                                                             data-kt-countup-value="<?php echo $t_withdraw;?>" 
                                                             data-kt-countup-prefix="$" data-kt-initialized="1">
                                                            <?php echo $symbol.number_format($t_withdraw,2);?>
                                                        </div>
                                                    </div>
                                                    <!--end::Number-->
                                                    <!--begin::Label-->
                                                    <div class="fw-semibold fs-6 text-gray-400">Withdrawal</div>
                                                    <!--end::Label-->
                                                </div>
                                                <!--end::Stat-->
                                                <!--begin::Stat-->
                                                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                    <!--begin::Number-->
                                                    <div class="d-flex align-items-center">
                                                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr065.svg-->
                                                        <span class="svg-icon svg-icon-3 svg-icon-success me-2">
                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor"></rect>
                                                                <path
                                                                    d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
                                                                    fill="currentColor"
                                                                ></path>
                                                            </svg>
                                                        </span>
                                                        <!--end::Svg Icon-->
                                                        <div class="fs-4 fw-bold counted" data-kt-countup="true" 
                                                             data-kt-countup-value="<?php echo $t_balance;?>" data-kt-initialized="1">
                                                                <?php echo $symbol.number_format($t_balance,2);?>
                                                        </div>
                                                    </div>
                                                    <!--end::Number-->
                                                    <!--begin::Label-->
                                                    <div class="fw-semibold fs-6 text-gray-400">Balance</div>
                                                    <!--end::Label-->
                                                </div>
                                                <!--end::Stat-->
                                                <!--begin::Stat-->
                                                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                                    <!--begin::Number-->
                                                    <div class="d-flex align-items-center">
                                                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr065.svg-->
                                                        <span class="svg-icon svg-icon-3 svg-icon-success me-2">
                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor"></rect>
                                                                <path
                                                                    d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
                                                                    fill="currentColor"
                                                                ></path>
                                                            </svg>
                                                        </span>
                                                        <!--end::Svg Icon-->
                                                        <div class="fs-4 fw-bold counted" data-kt-countup="true" 
                                                             data-kt-countup-value="<?php echo $total_bal;?>" data-kt-initialized="1">
                                                                <?php echo $symbol.number_format($total_bal,2);?>
                                                        </div>
                                                    </div>
                                                    <!--end::Number-->
                                                    <!--begin::Label-->
                                                    <div class="fw-semibold fs-6 text-gray-400">Wallet Balance</div>
                                                    <!--end::Label-->
                                                </div>
                                                <!--end::Stat-->
                                            </div>
                                            <!--end::Stats-->
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <!--end::Details-->
                                <div class="separator"></div>
                            </div>
                        </div>
                        <!--begin::Row-->
                        <div class="row g-5 g-xl-8 mb-15">
                            <div class="col-xl-12">
                                <div class="card card-flush " id="">
                                    <!--begin::Card header-->
                                    <div class="card-header pt-7" id="">
                                        <!--begin::Card title-->
                                        <div class="card-title">
                                            <h2>
                                                <?php echo $title2;?>
                                            </h2>
                                        </div>
                                        <!--end::Card title-->
                                    </div>
                                    <!--end::Card header-->
                                    <!--begin::Card body-->
                                    <div class="card-body pt-5">
                                        <div class="stepper stepper-links d-flex flex-column gap-5" id="kt_modal_top_up_wallet_stepper" data-kt-stepper="true">
                                            <!--begin::Nav-->
                                            <div class="stepper-nav justify-content-center py-2">
                                                <!--begin::Step 1-->
                                                <div class="stepper-item current" data-kt-stepper-element="nav" id="with_step_one_head">
                                                    <h3 class="stepper-title">Amount</h3>
                                                </div>
                                                <!--end::Step 1-->
                                                <!--begin::Step 2-->
                                                <div class="stepper-item" data-kt-stepper-element="nav" id="with_step_two_head">
                                                    <h3 class="stepper-title">Currency</h3>
                                                </div>
                                                <!--end::Step 2-->
                                                <!--begin::Step 3-->
                                                <div class="stepper-item" data-kt-stepper-element="nav" id="with_step_three_head">
                                                    <h3 class="stepper-title">Withdraw Wallet</h3>
                                                </div>
                                                <!--end::Step 3-->
                                                <!--begin::Step 4-->
                                                <div class="stepper-item" data-kt-stepper-element="nav" id="with_step_four_head">
                                                    <h3 class="stepper-title">Completed</h3>
                                                </div>
                                                <!--end::Step 4-->
                                            </div>
                                            <!--end::Nav-->
                                            <!--begin::Form-->
                                            <form class="mx-auto w-100 mw-600px pt-15 pb-10 fv-plugins-bootstrap5 fv-plugins-framework" 
                                                novalidate="novalidate" onsubmit="return false;" method="POST" id="withdraw_form" autocomplete="off">
                                                <!--begin::Step 1-->
                                                <div class="current" data-kt-stepper-element="content" id="with_step_one">
                                                    <!--begin::Wrapper-->
                                                    <div class="w-100">
                                                        <!--begin::Heading-->
                                                        <?php 
                                                            $sql = "SELECT plan_unique,plan,profit FROM plan_account WHERE user_id='$profile_id' and active='1' order by id asc";
                                                            $query = mysqli_query($db_conx, $sql);
                                                            $numrows = mysqli_num_rows($query);
                                                            $display ='<option value="">none selected</option>';                                                                    
                                                            if($numrows > 0){  
                                                                $display='';
                                                                $count = 0;
                                                                while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                                                                    $count += 1; 
                                                                    $plan_unique = $row['plan_unique'];
                                                                    $plan = $row['plan'];
                                                                    $profit = $row['profit'];
                                                                    $display .='<option value="'.$plan_unique.'-'.$profit.'">'.strtoupper($plan).' - '. $symbol.number_format($profit,2).'</option>';
                                                                }
                                                            }
                                                        ?>
                                                        <!--begin::Input group-->
                                                        <div class="mb-10 fv-row fv-plugins-icon-container">
                                                            <!--begin::Label-->
                                                            <label class="form-label mb-3">
                                                                <span class="required">Withdraw From</span>
                                                            </label>
                                                            <!--end::Label-->
                                                            <!--begin::Input-->
                                                            <select name="withdraw_from" id="withdraw_from"  class="form-control " required onchange="select_from(this.value);">
                                                                <option value="" selected>- Select-</option>
                                                                <?php echo $display;?>
                                                                <option value="Wallet-<?php echo $total_bal;?>">Wallet Balance - <?php echo $symbol.number_format($total_bal,2);?></option>
                                                            </select>
                                                            <!--end::Input-->
                                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                                        </div>
                                                        <!--end::Input group-->
                                                        <!--begin::Input group-->
                                                        <div class="mb-10 fv-row fv-plugins-icon-container">
                                                            <!--begin::Label-->
                                                            <label class="form-label mb-3">
                                                                <span class="required">Amount</span>
                                                            </label>
                                                            <!--end::Label-->
                                                            <!--begin::Input-->
                                                            <input type="text" class="form-control form-control-lg form-control-solid"  required=""
                                                                name="amount" id="amount" placeholder="Amount (to the nearest dollar)" autocomplete="off"  />
                                                            <input type="hidden" class="form-control " 
                                                                   name="with_amount" id="with_amount" value="" />
                                                            <input type="hidden" class="form-control " 
                                                                   name="plan_id" id="plan_id" value="" />
                                                            <!--end::Input-->
                                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                                        </div>
                                                        <!--end::Input group-->
                                                        <!--begin::Input group-->
                                                        <div class="mb-10">
                                                            <!--begin::Label-->
                                                            <label class="required fw-semibold fs-6 mb-5">Currency Type</label>
                                                            <!--end::Label-->
                                                            <!--begin::Row-->
                                                            <div class="row row-cols-1 row-cols-md-2 g-5">
                                                                <!--begin::Col-->
                                                                
                                                                <!--begin::Col-->
                                                                <div class="col">
                                                                    <!--begin::Option-->
                                                                    <input type="radio" class="btn-check" name="currency_type" value="crypto" id="kt_radio_buttons_2_option_2" checked="" />
                                                                    <label class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center h-100" for="kt_radio_buttons_2_option_2">
                                                                        <!--begin::Svg Icon | path: icons/duotune/finance/fin009.svg-->
                                                                        <span class="svg-icon svg-icon-3hx svg-icon-primary">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <path
                                                                                    opacity="0.3"
                                                                                    d="M15.8 11.4H6C5.4 11.4 5 11 5 10.4C5 9.80002 5.4 9.40002 6 9.40002H15.8C16.4 9.40002 16.8 9.80002 16.8 10.4C16.8 11 16.3 11.4 15.8 11.4ZM15.7 13.7999C15.7 13.1999 15.3 12.7999 14.7 12.7999H6C5.4 12.7999 5 13.1999 5 13.7999C5 14.3999 5.4 14.7999 6 14.7999H14.8C15.3 14.7999 15.7 14.2999 15.7 13.7999Z"
                                                                                    fill="currentColor"
                                                                                ></path>
                                                                                <path
                                                                                    d="M18.8 15.5C18.9 15.7 19 15.9 19.1 16.1C19.2 16.7 18.7 17.2 18.4 17.6C17.9 18.1 17.3 18.4999 16.6 18.7999C15.9 19.0999 15 19.2999 14.1 19.2999C13.4 19.2999 12.7 19.2 12.1 19.1C11.5 19 11 18.7 10.5 18.5C10 18.2 9.60001 17.7999 9.20001 17.2999C8.80001 16.8999 8.49999 16.3999 8.29999 15.7999C8.09999 15.1999 7.80001 14.7 7.70001 14.1C7.60001 13.5 7.5 12.8 7.5 12.2C7.5 11.1 7.7 10.1 8 9.19995C8.3 8.29995 8.79999 7.60002 9.39999 6.90002C9.99999 6.30002 10.7 5.8 11.5 5.5C12.3 5.2 13.2 5 14.1 5C15.2 5 16.2 5.19995 17.1 5.69995C17.8 6.09995 18.7 6.6 18.8 7.5C18.8 7.9 18.6 8.29998 18.3 8.59998C18.2 8.69998 18.1 8.69993 18 8.79993C17.7 8.89993 17.4 8.79995 17.2 8.69995C16.7 8.49995 16.5 7.99995 16 7.69995C15.5 7.39995 14.9 7.19995 14.2 7.19995C13.1 7.19995 12.1 7.6 11.5 8.5C10.9 9.4 10.5 10.6 10.5 12.2C10.5 13.3 10.7 14.2 11 14.9C11.3 15.6 11.7 16.1 12.3 16.5C12.9 16.9 13.5 17 14.2 17C15 17 15.7 16.8 16.2 16.4C16.8 16 17.2 15.2 17.9 15.1C18 15 18.5 15.2 18.8 15.5Z"
                                                                                    fill="currentColor"
                                                                                ></path>
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                        <span class="d-block fw-semibold text-start">
                                                                            <span class="text-dark fw-bold d-block fs-3">Crypto</span>
                                                                            <span class="text-muted fw-semibold fs-6">Withdraw funds using crypto currency.</span>
                                                                        </span>
                                                                    </label>
                                                                    <!--end::Option-->
                                                                </div>
                                                                <!--end::Col-->
                                                                <div class="col">
                                                                    <!--begin::Option-->
                                                                    <input type="radio" class="btn-check" name="currency_type" value="" id="kt_radio_buttons_2_option_1" disabled="" />
                                                                    <label class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex align-items-center h-100" for="kt_radio_buttons_2_option_1">
                                                                        <!--begin::Svg Icon | path: icons/duotune/finance/fin010.svg-->
                                                                        <span class="svg-icon svg-icon-3hx svg-icon-primary">
                                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <path opacity="0.3" d="M12.5 22C11.9 22 11.5 21.6 11.5 21V3C11.5 2.4 11.9 2 12.5 2C13.1 2 13.5 2.4 13.5 3V21C13.5 21.6 13.1 22 12.5 22Z" fill="currentColor"></path>
                                                                                <path
                                                                                    d="M17.8 14.7C17.8 15.5 17.6 16.3 17.2 16.9C16.8 17.6 16.2 18.1 15.3 18.4C14.5 18.8 13.5 19 12.4 19C11.1 19 10 18.7 9.10001 18.2C8.50001 17.8 8.00001 17.4 7.60001 16.7C7.20001 16.1 7 15.5 7 14.9C7 14.6 7.09999 14.3 7.29999 14C7.49999 13.8 7.80001 13.6 8.20001 13.6C8.50001 13.6 8.69999 13.7 8.89999 13.9C9.09999 14.1 9.29999 14.4 9.39999 14.7C9.59999 15.1 9.8 15.5 10 15.8C10.2 16.1 10.5 16.3 10.8 16.5C11.2 16.7 11.6 16.8 12.2 16.8C13 16.8 13.7 16.6 14.2 16.2C14.7 15.8 15 15.3 15 14.8C15 14.4 14.9 14 14.6 13.7C14.3 13.4 14 13.2 13.5 13.1C13.1 13 12.5 12.8 11.8 12.6C10.8 12.4 9.99999 12.1 9.39999 11.8C8.69999 11.5 8.19999 11.1 7.79999 10.6C7.39999 10.1 7.20001 9.39998 7.20001 8.59998C7.20001 7.89998 7.39999 7.19998 7.79999 6.59998C8.19999 5.99998 8.80001 5.60005 9.60001 5.30005C10.4 5.00005 11.3 4.80005 12.3 4.80005C13.1 4.80005 13.8 4.89998 14.5 5.09998C15.1 5.29998 15.6 5.60002 16 5.90002C16.4 6.20002 16.7 6.6 16.9 7C17.1 7.4 17.2 7.69998 17.2 8.09998C17.2 8.39998 17.1 8.7 16.9 9C16.7 9.3 16.4 9.40002 16 9.40002C15.7 9.40002 15.4 9.29995 15.3 9.19995C15.2 9.09995 15 8.80002 14.8 8.40002C14.6 7.90002 14.3 7.49995 13.9 7.19995C13.5 6.89995 13 6.80005 12.2 6.80005C11.5 6.80005 10.9 7.00005 10.5 7.30005C10.1 7.60005 9.79999 8.00002 9.79999 8.40002C9.79999 8.70002 9.9 8.89998 10 9.09998C10.1 9.29998 10.4 9.49998 10.6 9.59998C10.8 9.69998 11.1 9.90002 11.4 9.90002C11.7 10 12.1 10.1 12.7 10.3C13.5 10.5 14.2 10.7 14.8 10.9C15.4 11.1 15.9 11.4 16.4 11.7C16.8 12 17.2 12.4 17.4 12.9C17.6 13.4 17.8 14 17.8 14.7Z"
                                                                                    fill="currentColor"
                                                                                ></path>
                                                                            </svg>
                                                                        </span>
                                                                        <!--end::Svg Icon-->
                                                                        <span class="d-block fw-semibold text-start">
                                                                            <span class="text-dark fw-bold d-block fs-3">Cash</span>
                                                                            <span class="text-muted fw-semibold fs-6" title="coming soon">Withdraw funds using cash from one of your saved payment options.</span>
                                                                        </span>
                                                                    </label>
                                                                    <!--end::Option-->
                                                                </div>
                                                                <!--end::Col-->
                                                            </div>
                                                            <!--end::Row-->
                                                        </div>
                                                        <!--end::Input group-->
                                                        <div class="mb-10 fv-row fv-plugins-icon-container">
                                                            <!--end::Input-->
                                                            <div class="" style="width: 100%" id="status_step_one"></div>
                                                        </div>
                                                        <!--begin::Input group-->
                                                        <div class=" fv-row fv-plugins-icon-container text-right" style=" margin-top: 50px; float: right">
                                                            <button type="button" class="btn btn-lg btn-primary" onclick="withdrawal_step_one('one','two');" >
                                                                Continue
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
                                                <!--begin::Step 2-->
                                                <div data-kt-stepper-element="content" id="with_step_two">
                                                    <!--begin::Wrapper-->
                                                    <div class="w-100">
                                                        <!--begin::Heading-->
                                                        <div class="pb-10 pb-lg-12">
                                                            <!--begin::Title-->
                                                            <h1 class="fw-bold text-dark">Currency</h1>
                                                            <!--end::Title-->
                                                            <!--begin::Description-->
                                                            <div class="text-muted fw-semibold fs-6">
                                                                If you need more info, please contact us via 
                                                                <a href="../support/" class="link-primary fw-bold" target="_blank">Help Page</a>.
                                                            </div>
                                                            <!--end::Description-->
                                                        </div>
                                                        <!--end::Heading-->
                                                        <!--begin::Crypto options-->
                                                        <div data-kt-modal-top-up-wallet-option="crypto">
                                                            <!--begin::Input group-->
                                                            <div class="mb-10">
                                                                <?php 
                                                                    $sql = "SELECT wallet_symbol,wallet_name FROM wallets WHERE user_id='1' and username='Admin' and with_display='1' order by id asc";
                                                                    $query = mysqli_query($db_conx, $sql);
                                                                    $numrows = mysqli_num_rows($query);
                                                                    $display ='<option value="">none selected</option>';                                                                    
                                                                    if($numrows > 0){  
                                                                        $display='';
                                                                        $count = 0;
                                                                        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                                                                            $count += 1; 
                                                                            $wallet_symbol = $row['wallet_symbol'];
                                                                            $wallet_name = $row['wallet_name'];
                                                                            $checked = '';
                                                                            if($count == 1){
                                                                                $checked = 'checked="checked"';
                                                                            }
                                                                            $display .='<!--begin::Col-->
                                                                                    <div class="col">
                                                                                        <!--begin::Option-->
                                                                                        <input type="radio" class="btn-check" name="withdraw_method" value="'.$wallet_name.'" id="coin_option_'.$count.'" '.$checked.' />
                                                                                        <label class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex flex-column flex-center gap-5 h-100" for="coin_option_'.$count.'">
                                                                                            <!--begin::Icon-->
                                                                                            <img src="../assets_inside/img/svg-icons/'.strtoupper($wallet_symbol).'.svg" alt="" class="w-50px" />
                                                                                            <!--end::Icon-->
                                                                                            <!--begin::Label-->
                                                                                            <div class="fs-5 fw-bold">'. ucwords($wallet_name).'</div>
                                                                                            <!--end::Label-->
                                                                                        </label>
                                                                                    </div>
                                                                                    <!--end::Col-->';
//                                                                            $display .='<option value="'.$wallet_name.'">'.strtoupper($wallet_symbol).'</option>';
                                                                        }
                                                                    }
                                                                ?>
                                                                <!--begin::Label-->
                                                                <label class="required fs-6 fw-semibold mb-2">Select a coin</label>
                                                                <!--End::Label-->
                                                                <!--begin::Row-->
                                                                <div class="row row-cols-2 row-cols-md-4 g-5">
                                                                    <?php echo $display;?>
                                                                </div>
                                                                <!--end::Row-->
                                                            </div>
                                                            <!--end::Input group-->
                                                        </div>
                                                        <!--end::Crypto options-->
                                                        <!--end::Input group-->
                                                        <div class="mb-10 fv-row fv-plugins-icon-container">
                                                            <!--end::Input-->
                                                            <div class="" style="width: 100%" id="status_step_two"></div>
                                                        </div>
                                                        <!--begin::Input group-->
                                                        <div class=" fv-row fv-plugins-icon-container text-right" style=" margin-top: 50px;">
                                                            <button type="button" class="btn btn-lg btn-light-primary me-3" onclick="withdrawal_step_prev('two','one');">
                                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr063.svg-->
                                                                <span class="svg-icon svg-icon-3 me-1">
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <rect opacity="0.5" x="6" y="11" width="13" height="2" rx="1" fill="currentColor"></rect>
                                                                        <path
                                                                            d="M8.56569 11.4343L12.75 7.25C13.1642 6.83579 13.1642 6.16421 12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75L5.70711 11.2929C5.31658 11.6834 5.31658 12.3166 5.70711 12.7071L11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25C13.1642 17.8358 13.1642 17.1642 12.75 16.75L8.56569 12.5657C8.25327 12.2533 8.25327 11.7467 8.56569 11.4343Z"
                                                                            fill="currentColor"
                                                                        ></path>
                                                                    </svg>
                                                                </span>
                                                                <!--end::Svg Icon-->Back
                                                            </button>
                                                            <button type="button" class="btn btn-lg btn-primary" onclick="withdrawal_step_one('two','three');"
                                                                    style="float: right"  >
                                                                Continue &nbsp;
                                                                <span class="svg-icon svg-icon-3 ms-1 me-0">
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="currentColor"></rect>
                                                                        <path
                                                                            d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z"
                                                                            fill="currentColor"
                                                                        ></path>
                                                                    </svg>
                                                                </span>
                                                            </button>
                                                        </div>
                                                        <!--end::Input group-->
                                                    </div>
                                                    <!--end::Wrapper-->
                                                </div>
                                                <!--end::Step 2-->
                                                <!--begin::Step 3-->
                                                <div data-kt-stepper-element="content" id="with_step_three">
                                                    <!--begin::Wrapper-->
                                                    <div class="w-100">
                                                        <!--begin::Heading-->
                                                        <div class="pb-10 pb-lg-12">
                                                            <!--begin::Title-->
                                                            <h1 class="fw-bold text-dark">Withdraw Wallet</h1>
                                                            <!--end::Title-->
                                                            <!--begin::Description-->
                                                            <div class="text-muted fw-semibold fs-6">
                                                                If you need more info, please contact us via 
                                                                <a href="../support/" class="link-primary fw-bold" target="_blank">Help Page</a>.
                                                            </div>
                                                            <!--end::Description-->
                                                        </div>
                                                        <!--end::Heading-->
                                                        <div class="mb-10 fv-row fv-plugins-icon-container">
                                                            <!--begin::Label-->
                                                            <label class="form-label mb-3">
                                                                <span class="required">Receiving Address</span>
                                                            </label>
                                                            <!--end::Label-->
                                                            <!--begin::Input-->
                                                            <input type="text" class="form-control form-control-lg form-control-solid"  required=""
                                                                name="withdraw_details" id="withdraw_details" placeholder="Enter receiving address" autocomplete="off"  />
                                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                                        </div>
                                                        <div class="mb-10 fv-row fv-plugins-icon-container">
                                                            <!--end::Input-->
                                                            <div class="" style="width: 100%" id="status_step_three"></div>
                                                        </div>
                                                        <!--end::Input group-->
                                                        <!--begin::Input group-->
                                                        <div class=" fv-row fv-plugins-icon-container text-right" style=" margin-top: 50px;">
                                                            <button type="button" class="btn btn-lg btn-light-primary me-3" onclick="withdrawal_step_prev('three','two');">
                                                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr063.svg-->
                                                                <span class="svg-icon svg-icon-3 me-1">
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <rect opacity="0.5" x="6" y="11" width="13" height="2" rx="1" fill="currentColor"></rect>
                                                                        <path
                                                                            d="M8.56569 11.4343L12.75 7.25C13.1642 6.83579 13.1642 6.16421 12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75L5.70711 11.2929C5.31658 11.6834 5.31658 12.3166 5.70711 12.7071L11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25C13.1642 17.8358 13.1642 17.1642 12.75 16.75L8.56569 12.5657C8.25327 12.2533 8.25327 11.7467 8.56569 11.4343Z"
                                                                            fill="currentColor"
                                                                        ></path>
                                                                    </svg>
                                                                </span>
                                                                <!--end::Svg Icon-->Back
                                                            </button>
                                                            <button type="submit" class="btn btn-lg btn-primary" onclick="withdraw_funds('three','four');"
                                                                    style="float: right"  id="withdraw_btn">
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
                                                <!--end::Step 3-->
                                                <!--begin::Step 5-->
                                                <div data-kt-stepper-element="content" id="with_step_four">
                                                    <!--begin::Wrapper-->
                                                    <div class="w-100">
                                                        <!--begin::Heading-->
                                                        <div class="pb-12 text-center">
                                                            <!--begin::Title-->
                                                            <h1 class="fw-bold text-dark mb-10">Request Successfully Sent!</h1>
                                                            <!--end::Title-->
                                                            <!--begin::Description-->
                                                            <div class="fw-semibold text-muted fs-4">
                                                                You will receive an email once request is processed and confirmed!
                                                            </div>
                                                            <!--end::Description-->
                                                        </div>
                                                        <!--end::Heading-->
                                                        <!--begin::Actions-->
                                                        <div class="d-flex flex-center">
                                                            <a href="../withdrawal/" class="btn btn-lg btn-light me-3" >Make New Request</a>
                                                            <a href="../dashboard/" class="btn btn-lg btn-primary" >Dashboard</a>
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
                                            </form>
                                            <!--end::Form-->
                                        </div>
                                    </div>
                                </div>
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
                                            <span class="card-label fw-bold text-dark">Withdrawal History</span>
                                            <span class="text-gray-400 mt-1 fw-semibold fs-6"></span>
                                        </h3>
                                        <!--end::Title-->
                                        <!--begin::Toolbar-->
                                        <div class="card-toolbar">
                                            <ul class="nav" id="kt_chart_widget_8_tabs">
                                                <li class="nav-item">
                                                    <a
                                                        class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light fw-bold px-4 me-1 active"
                                                        data-bs-toggle="tab"
                                                        id="kt_chart_widget_8_month_toggle"
                                                        href="../account-history/"
                                                    >
                                                        All Transactions
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <!--end::Toolbar-->
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
                                                    <?php echo $pay_transaction;?>
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