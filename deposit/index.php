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
    $title = 'Fund Wallet | ' . $site_name . '';
    $title2 = 'Fund Wallet';
    $keyword = '';
    $discription = '';
    $dash = '';
    $dep = 'active';
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
    $class_link = '<link href="../assets_inside/css/style.bundle.css?v=' . $ver . '" rel="stylesheet" type="text/css" />';
    include_once '../user_header.php';
    ?>
<!-- End Main Header-->
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
    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
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
                        <label class="main-content-label tx-10 font-weight-bold mb-1">Invested</label>
                    </div>
                    <div class="card-item-body">
                        <div class="card-item-stat">
                            <h4 class="font-weight-bold"><?php echo $symbol . number_format($invested, 2); ?></h4>
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
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-3">
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
                        <label class="main-content-label tx-13 font-weight-bold mb-1">Withdraw</label>
                        <span class="d-block tx-12 mb-0 text-muted"></span>
                    </div>
                    <div class="card-item-body">
                        <div class="card-item-stat">
                            <h4 class="font-weight-bold"><?php echo $symbol . number_format($t_withdraw, 2); ?></h4>
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



<!--begin::Referred users-->
<!--begin::Card body-->
<!--begin::Card body-->
<div class="custom-card" style="background-color: white">
    <div class="card-body pt-5">
        <div class="stepper stepper-links d-flex flex-column gap-5" id="kt_modal_top_up_wallet_stepper" data-kt-stepper="true">
            <!--begin::Nav-->
            <div class="stepper-nav justify-content-center py-2">
                <!--begin::Step 1-->
                <div class="stepper-item current" data-kt-stepper-element="nav" id="dep_step_one_head">
                    <h3 class="stepper-title">Deposit Amount</h3>
                </div>
                <!--end::Step 1-->
                <!--begin::Step 2-->
                <div class="stepper-item" data-kt-stepper-element="nav" id="dep_step_two_head">
                    <h3 class="stepper-title">Currency</h3>
                </div>
                <!--end::Step 2-->
                <!--begin::Step 3-->
                <div class="stepper-item" data-kt-stepper-element="nav" id="dep_step_three_head">
                    <h3 class="stepper-title">Make Payment</h3>
                </div>
                <!--end::Step 3-->
                <!--begin::Step 4-->
                <div class="stepper-item" data-kt-stepper-element="nav" id="dep_step_four_head">
                    <h3 class="stepper-title">Completed</h3>
                </div>
                <!--end::Step 4-->
            </div>
            <!--end::Nav-->
            <!--begin::Form-->
            <form class="mx-auto w-100 mw-600px pt-15 pb-10 fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate" onsubmit="return false;" method="POST" id="deposite_form" autocomplete="off">
                <!--begin::Step 1-->
                <div class="current" data-kt-stepper-element="content" id="dep_step_one">
                    <!--begin::Wrapper-->
                    <div class="w-100">
                        <!--begin::Heading-->
                        <div class="pb-10 pb-lg-15">
                            <!--begin::Title-->
                            <h2 class="fw-bold d-flex align-items-center text-dark">
                                Set Amount to Fund <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" aria-label="You will be charged the set amount from your selected payment option" data-kt-initialized="1"></i>
                            </h2>
                            <!--end::Title-->
                            <!--begin::Notice-->
                            <div class="text-muted fw-semibold fs-6">
                                If you need more info, please contact us via
                                <a href="../support/" class="link-primary fw-bold" target="_blank">Help Page</a>.
                            </div>
                            <!--end::Notice-->
                        </div>
                        <!--end::Heading-->
                        <!--begin::Input group-->
                        <div class="mb-10 fv-row fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="form-label mb-3">
                                <span class="required">Fund Amount</span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" class="form-control form-control-lg form-control-solid" name="amount" id="amount" placeholder="Amount (to the nearest dollar)" autocomplete="off" />
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
                                                <path opacity="0.3" d="M15.8 11.4H6C5.4 11.4 5 11 5 10.4C5 9.80002 5.4 9.40002 6 9.40002H15.8C16.4 9.40002 16.8 9.80002 16.8 10.4C16.8 11 16.3 11.4 15.8 11.4ZM15.7 13.7999C15.7 13.1999 15.3 12.7999 14.7 12.7999H6C5.4 12.7999 5 13.1999 5 13.7999C5 14.3999 5.4 14.7999 6 14.7999H14.8C15.3 14.7999 15.7 14.2999 15.7 13.7999Z" fill="currentColor"></path>
                                                <path d="M18.8 15.5C18.9 15.7 19 15.9 19.1 16.1C19.2 16.7 18.7 17.2 18.4 17.6C17.9 18.1 17.3 18.4999 16.6 18.7999C15.9 19.0999 15 19.2999 14.1 19.2999C13.4 19.2999 12.7 19.2 12.1 19.1C11.5 19 11 18.7 10.5 18.5C10 18.2 9.60001 17.7999 9.20001 17.2999C8.80001 16.8999 8.49999 16.3999 8.29999 15.7999C8.09999 15.1999 7.80001 14.7 7.70001 14.1C7.60001 13.5 7.5 12.8 7.5 12.2C7.5 11.1 7.7 10.1 8 9.19995C8.3 8.29995 8.79999 7.60002 9.39999 6.90002C9.99999 6.30002 10.7 5.8 11.5 5.5C12.3 5.2 13.2 5 14.1 5C15.2 5 16.2 5.19995 17.1 5.69995C17.8 6.09995 18.7 6.6 18.8 7.5C18.8 7.9 18.6 8.29998 18.3 8.59998C18.2 8.69998 18.1 8.69993 18 8.79993C17.7 8.89993 17.4 8.79995 17.2 8.69995C16.7 8.49995 16.5 7.99995 16 7.69995C15.5 7.39995 14.9 7.19995 14.2 7.19995C13.1 7.19995 12.1 7.6 11.5 8.5C10.9 9.4 10.5 10.6 10.5 12.2C10.5 13.3 10.7 14.2 11 14.9C11.3 15.6 11.7 16.1 12.3 16.5C12.9 16.9 13.5 17 14.2 17C15 17 15.7 16.8 16.2 16.4C16.8 16 17.2 15.2 17.9 15.1C18 15 18.5 15.2 18.8 15.5Z" fill="currentColor"></path>
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                        <span class="d-block fw-semibold text-start">
                                            <span class="text-dark fw-bold d-block fs-3">Crypto</span>
                                            <span class="text-muted fw-semibold fs-6">Fund wallet using crypto currency.</span>
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
                                                <path d="M17.8 14.7C17.8 15.5 17.6 16.3 17.2 16.9C16.8 17.6 16.2 18.1 15.3 18.4C14.5 18.8 13.5 19 12.4 19C11.1 19 10 18.7 9.10001 18.2C8.50001 17.8 8.00001 17.4 7.60001 16.7C7.20001 16.1 7 15.5 7 14.9C7 14.6 7.09999 14.3 7.29999 14C7.49999 13.8 7.80001 13.6 8.20001 13.6C8.50001 13.6 8.69999 13.7 8.89999 13.9C9.09999 14.1 9.29999 14.4 9.39999 14.7C9.59999 15.1 9.8 15.5 10 15.8C10.2 16.1 10.5 16.3 10.8 16.5C11.2 16.7 11.6 16.8 12.2 16.8C13 16.8 13.7 16.6 14.2 16.2C14.7 15.8 15 15.3 15 14.8C15 14.4 14.9 14 14.6 13.7C14.3 13.4 14 13.2 13.5 13.1C13.1 13 12.5 12.8 11.8 12.6C10.8 12.4 9.99999 12.1 9.39999 11.8C8.69999 11.5 8.19999 11.1 7.79999 10.6C7.39999 10.1 7.20001 9.39998 7.20001 8.59998C7.20001 7.89998 7.39999 7.19998 7.79999 6.59998C8.19999 5.99998 8.80001 5.60005 9.60001 5.30005C10.4 5.00005 11.3 4.80005 12.3 4.80005C13.1 4.80005 13.8 4.89998 14.5 5.09998C15.1 5.29998 15.6 5.60002 16 5.90002C16.4 6.20002 16.7 6.6 16.9 7C17.1 7.4 17.2 7.69998 17.2 8.09998C17.2 8.39998 17.1 8.7 16.9 9C16.7 9.3 16.4 9.40002 16 9.40002C15.7 9.40002 15.4 9.29995 15.3 9.19995C15.2 9.09995 15 8.80002 14.8 8.40002C14.6 7.90002 14.3 7.49995 13.9 7.19995C13.5 6.89995 13 6.80005 12.2 6.80005C11.5 6.80005 10.9 7.00005 10.5 7.30005C10.1 7.60005 9.79999 8.00002 9.79999 8.40002C9.79999 8.70002 9.9 8.89998 10 9.09998C10.1 9.29998 10.4 9.49998 10.6 9.59998C10.8 9.69998 11.1 9.90002 11.4 9.90002C11.7 10 12.1 10.1 12.7 10.3C13.5 10.5 14.2 10.7 14.8 10.9C15.4 11.1 15.9 11.4 16.4 11.7C16.8 12 17.2 12.4 17.4 12.9C17.6 13.4 17.8 14 17.8 14.7Z" fill="currentColor"></path>
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                        <span class="d-block fw-semibold text-start">
                                            <span class="text-dark fw-bold d-block fs-3">Cash</span>
                                            <span class="text-muted fw-semibold fs-6" title="coming soon">Top up wallet using cash from one of your saved payment options.</span>
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
                            <button type="button" class="btn btn-lg btn-primary" onclick="deposit_step_one('one','two');">
                                Continue
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr064.svg-->
                                <span class="svg-icon svg-icon-3 ms-1 me-0">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="currentColor"></rect>
                                        <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="currentColor"></path>
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
                <div data-kt-stepper-element="content" id="dep_step_two">
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
                                $sql = "SELECT wallet_symbol,wallet_name FROM wallets WHERE user_id='1' and wallet_type='Admin' and dep_display='1' order by id asc";
                                $query = mysqli_query($db_conx, $sql);
                                $numrows = mysqli_num_rows($query);
                                $display = 'no currency';
                                if ($numrows > 0) {
                                    $display = '';
                                    $count = 0;
                                    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                                        $count += 1;
                                        $wallet_symbol = $row['wallet_symbol'];
                                        $wallet_name = $row['wallet_name'];
                                        $checked = '';
                                        if ($count == 1) {
                                            $checked = 'checked="checked"';
                                        }
                                        $display .= '<!--begin::Col-->
                                                    <div class="col">
                                                        <!--begin::Option-->
                                                        <input type="radio" class="btn-check" name="fund_method" value="' . $wallet_name . '" id="coin_option_' . $count . '" ' . $checked . ' />
                                                        <label class="btn btn-outline btn-outline-dashed btn-active-light-primary p-7 d-flex flex-column flex-center gap-5 h-100" for="coin_option_' . $count . '">
                                                            <!--begin::Icon-->
                                                            <img src="../assets_inside/img/svg-icons/' . strtoupper($wallet_symbol) . '.svg" alt="" class="w-50px" />
                                                            <!--end::Icon-->
                                                            <!--begin::Label-->
                                                            <div class="fs-5 fw-bold">' . ucwords($wallet_name) . '</div>
                                                            <!--end::Label-->
                                                        </label>
                                                    </div>
                                                    <!--end::Col-->';
                                        // $display .='<option value="'.$wallet_name.'">'.strtoupper($wallet_symbol).'</option>';
                                    }
                                }
                                ?>
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold mb-2">Select a coin</label>
                                <!--End::Label-->
                                <!--begin::Row-->
                                <div class="row row-cols-2 row-cols-md-4 g-5">
                                    <?php echo $display; ?>
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
                            <button type="button" class="btn btn-lg btn-light-primary me-3" onclick="deposit_step_prev('two','one');">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr063.svg-->
                                <span class="svg-icon svg-icon-3 me-1">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.5" x="6" y="11" width="13" height="2" rx="1" fill="currentColor"></rect>
                                        <path d="M8.56569 11.4343L12.75 7.25C13.1642 6.83579 13.1642 6.16421 12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75L5.70711 11.2929C5.31658 11.6834 5.31658 12.3166 5.70711 12.7071L11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25C13.1642 17.8358 13.1642 17.1642 12.75 16.75L8.56569 12.5657C8.25327 12.2533 8.25327 11.7467 8.56569 11.4343Z" fill="currentColor"></path>
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->Back
                            </button>
                            <button type="button" class="btn btn-lg btn-primary" onclick="deposit_funds('two','three');" style="float: right" id="funds_btn">
                                Continue &nbsp;
                                <span class="svg-icon svg-icon-3 ms-1 me-0">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="currentColor"></rect>
                                        <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="currentColor"></path>
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
                <div data-kt-stepper-element="content" id="dep_step_three">
                    <!--begin::Wrapper-->
                    <div class="w-100">
                        <!--begin::Heading-->
                        <div class="pb-10 pb-lg-12">
                            <!--begin::Title-->
                            <h1 class="fw-bold text-dark">Make Payments</h1>
                            <!--end::Title-->
                            <!--begin::Description-->
                            <div class="text-muted fw-semibold fs-6">
                                If you need more info, please contact us via
                                <a href="../support/" class="link-primary fw-bold" target="_blank">Help Page</a>.
                            </div>
                            <!--end::Description-->
                        </div>
                        <!--end::Heading-->
                        <div class="mt-n1">
                            <!--begin::Wrapper-->
                            <div class="m-0">
                                <!--begin::Label-->
                                <div class="fw-bold fs-3 text-gray-800 mb-8">
                                    Invoice <b id="dep_invoice"></b>
                                </div>
                                <!--end::Label-->
                                <!--begin::Row-->
                                <div class="row g-5 mb-11">
                                    <!--end::Col-->
                                    <div class="col-sm-6">
                                        <!--end::Label-->
                                        <div class="fw-semibold fs-7 text-gray-600 mb-1">Issue Date:</div>
                                        <!--end::Label-->
                                        <!--end::Col-->
                                        <div class="fw-bold fs-6 text-gray-800"><?php echo date('H:ia D d, M Y'); ?></div>
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Col-->
                                    <!--end::Col-->
                                    <div class="col-sm-6">
                                        <!--end::Label-->
                                        <div class="fw-semibold fs-7 text-gray-600 mb-1">Status:</div>
                                        <!--end::Label-->
                                        <!--end::Info-->
                                        <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                                            <span class="pe-2" id="status_display">
                                                Awaiting payment...<i class="fa fa-spinner fa-spin"></i>
                                            </span>
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                                <!--begin::Row-->
                                <div class="row g-5 mb-12">
                                    <!--end::Col-->
                                    <div class="col-sm-6">
                                        <!--end::Label-->
                                        <div class="fw-semibold fs-7 text-gray-600 mb-1">Payment Method:</div>
                                        <!--end::Label-->
                                        <!--end::Text-->
                                        <div class="fw-bold fs-6 text-gray-800">
                                            <b class="coin_name"></b>
                                        </div>
                                        <!--end::Text-->
                                    </div>
                                    <!--end::Col-->
                                    <!--end::Col-->
                                    <div class="col-sm-6">
                                        <!--end::Label-->
                                        <div class="fw-semibold fs-7 text-gray-600 mb-1">Amount:</div>
                                        <!--end::Label-->
                                        <!--end::Text-->
                                        <div class="fw-bold fs-6 text-gray-800">
                                            <b class="amount_holder"></b>
                                        </div>
                                        <!--end::Text-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                                <!--begin::Row-->
                                <div class="row g-5 mb-12">
                                    <!--end::Col-->
                                    <div class="col-sm-10 text-center">
                                        <div id="dep_qr_code" style=""></div>
                                        <!--end::Description-->
                                        <div class="input-group" style=" ">
                                            <input type="text" class="form-control" id="coin_address" value="" readonly="" />
                                            <div class="input-group-append">
                                                <button type="button" onclick="copy_text('coin_address')" data-clipboard-target="#coin_address" class="btn btn-primary clipboard-trigger" id="coin_address_btn" title="Copy wallet address">
                                                    <i class="fa fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                                <!--begin::Row-->
                                <div class="row g-5 mb-12">
                                    <!--end::Col-->
                                    <div class="col-sm-12">
                                        <!--end::Label-->
                                        <!--end::Text-->
                                        <!--end::Description-->
                                        <div class="fw-semibold fs-7 text-danger">
                                            Send only <span class="coin_name"></span> to the address above and after one confirmation your
                                            account will be automatically credited.
                                        </div>
                                        <!--end::Description-->
                                        <!--end::Description-->
                                        <div class="fw-semibold fs-7 text-gray-600" style=" margin-top: 15px">
                                            After making payments, click on the "Confirm Payment" button below to complete this transaction.
                                        </div>
                                        <!--end::Description-->
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class=" fv-row fv-plugins-icon-container text-right" style=" margin-top: 50px;">
                            <button type="button" class="btn btn-lg btn-light-primary me-3" onclick="deposit_step_prev('three','two');">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr063.svg-->
                                <span class="svg-icon svg-icon-3 me-1">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.5" x="6" y="11" width="13" height="2" rx="1" fill="currentColor"></rect>
                                        <path d="M8.56569 11.4343L12.75 7.25C13.1642 6.83579 13.1642 6.16421 12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75L5.70711 11.2929C5.31658 11.6834 5.31658 12.3166 5.70711 12.7071L11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25C13.1642 17.8358 13.1642 17.1642 12.75 16.75L8.56569 12.5657C8.25327 12.2533 8.25327 11.7467 8.56569 11.4343Z" fill="currentColor"></path>
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->Back
                            </button>
                            <button type="button" class="btn btn-lg btn-primary" onclick="deposit_step_one('three','four');" style="float: right">
                                Confirm Payment
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr064.svg-->
                                <span class="svg-icon svg-icon-3 ms-1 me-0">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="currentColor"></rect>
                                        <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="currentColor"></path>
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
                <div data-kt-stepper-element="content" id="dep_step_four">
                    <!--begin::Wrapper-->
                    <div class="w-100">
                        <!--begin::Heading-->
                        <div class="pb-12 text-center">
                            <!--begin::Title-->
                            <h1 class="fw-bold text-dark mb-10">Successful Top Up!</h1>
                            <!--end::Title-->
                            <!--begin::Description-->
                            <div class="fw-semibold text-muted fs-4">
                                You will receive an email with the summary of your latest funding once payment is confirmed!
                            </div>
                            <!--end::Description-->
                        </div>
                        <!--end::Heading-->
                        <!--begin::Actions-->
                        <div class="d-flex flex-center">
                            <a href="../deposit/" class="btn btn-lg btn-light me-3">Add more funds</a>
                            <a href="../dashboard/" class="btn btn-lg btn-primary">View Wallet</a>
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
</div>
<!--end::Referred users-->
<br><br>


<!-- End Main Content-->

<!-- Main Footer-->
<?php include_once '../user_footer.php'; ?>
</body>

</html>