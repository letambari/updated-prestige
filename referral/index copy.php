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
$display ='<tr>
                <td colspan="4"> <strong >No record(s)..</strong></td>
              </tr>';
$ref_count = 0;
$sql = "SELECT id,username,full_name,email,avatar,signup FROM users WHERE sponsor='$email' ";
$query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($query);
if($numrows > 0){    
    $display ='';
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) { 
        $ref_count +=1;
        $signup = $row['signup'];
        $id = $row['id'];
        $email1 = $row['email'];
        $username1 = $row['username'];
        $full_name1 = $row['full_name'];
        $avatar1 = $row['avatar'];
        if($full_name1 ==''){
            $full_name1 = $username1;
        }
        $ref_img = '<img  src="../wp-includes/users/'.$email1.'/'.$avatar1.'" style="width:50%" class="rounded-circle  " alt="'.$full_name1.'">';
        if($avatar1 == ''){
            $ref_img = '<img alt="'.$f_n.'" class="rounded-circle " style="width:50%" src="../assets/avatar.jpg" />';
        }
        $sql1 = "SELECT active FROM account WHERE user_id='$id' ";
        $query1 = mysqli_query($db_conx, $sql1);
        $row1 = mysqli_fetch_array($query1, MYSQLI_ASSOC);
        $active1 = $row1['active'];
        $status_dis1 = '<span class="badge py-3 px-4 fs-7 badge-light-danger">dormant</span>';
        if($active1 == 1)$status_dis1 = '<span class="badge py-3 px-4 fs-7 badge-light-success">active</span>';
        $display .='<tr>
                        <td class="ps-9">'.$ref_count.'</td>
                        <td class="ps-0">
                            <a href="javascript:void(0)" class="text-gray-600 text-hover-primary">
                                '.$full_name1.'
                            </a>
                        </td>
                        <td>'.date('D d, M Y', strtotime($signup)).'</td>
                        <td>'.$status_dis1.'</td>
                        <td class="text-success">5%</td>
                    </tr>';
    }                     
}
?> <?php 
    $title ='Referrals | '.$site_name;
    $title2 ='Referrals';
    $keyword ='';
    $discription = '';  
    $dash = '';
    $dep = '';
    $pricing = '';
    $pur_invest = '';
    $my_invest = '';
    $with = '';
    $act_his = '';
    $ref = 'active';
    $acct_pro = ''; 
    $acct_settings = ''; 
    $acct_security = '';
    $suppt = '';    
    include_once '../user_header copy.php'; 
?>
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
        <!--begin::Navbar-->
        <div class="card mb-5 mb-xl-10">
            <div class="card-body pt-9 pb-0">
                <!--begin::Details-->
                <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                    <!--begin: Pic-->
                    <div class="me-7 mb-4">
                        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                            <?php echo $img3;?>
                            <div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px"></div>
                        </div>
                    </div>
                    <!--end::Pic-->
                    <!--begin::Info-->
                    <div class="flex-grow-1">
                        <!--begin::Title-->
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <!--begin::User-->
                            <div class="d-flex flex-column">
                                <!--begin::Name-->
                                <div class="d-flex align-items-center mb-2">
                                    <a href="javascript:void(0)" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">
                                        <?php echo $f_n;?>
                                    </a>
                                    <a href="javascript:void(0)">
                                        <!--begin::Svg Icon | path: icons/duotune/general/gen026.svg-->
                                        <span class="svg-icon svg-icon-1 svg-icon-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
                                                <path
                                                    d="M10.0813 3.7242C10.8849 2.16438 13.1151 2.16438 13.9187 3.7242V3.7242C14.4016 4.66147 15.4909 5.1127 16.4951 4.79139V4.79139C18.1663 4.25668 19.7433 5.83365 19.2086 7.50485V7.50485C18.8873 8.50905 19.3385 9.59842 20.2758 10.0813V10.0813C21.8356 10.8849 21.8356 13.1151 20.2758 13.9187V13.9187C19.3385 14.4016 18.8873 15.491 19.2086 16.4951V16.4951C19.7433 18.1663 18.1663 19.7433 16.4951 19.2086V19.2086C15.491 18.8873 14.4016 19.3385 13.9187 20.2758V20.2758C13.1151 21.8356 10.8849 21.8356 10.0813 20.2758V20.2758C9.59842 19.3385 8.50905 18.8873 7.50485 19.2086V19.2086C5.83365 19.7433 4.25668 18.1663 4.79139 16.4951V16.4951C5.1127 15.491 4.66147 14.4016 3.7242 13.9187V13.9187C2.16438 13.1151 2.16438 10.8849 3.7242 10.0813V10.0813C4.66147 9.59842 5.1127 8.50905 4.79139 7.50485V7.50485C4.25668 5.83365 5.83365 4.25668 7.50485 4.79139V4.79139C8.50905 5.1127 9.59842 4.66147 10.0813 3.7242V3.7242Z"
                                                    fill="currentColor"
                                                ></path>
                                                <path
                                                    d="M14.8563 9.1903C15.0606 8.94984 15.3771 8.9385 15.6175 9.14289C15.858 9.34728 15.8229 9.66433 15.6185 9.9048L11.863 14.6558C11.6554 14.9001 11.2876 14.9258 11.048 14.7128L8.47656 12.4271C8.24068 12.2174 8.21944 11.8563 8.42911 11.6204C8.63877 11.3845 8.99996 11.3633 9.23583 11.5729L11.3706 13.4705L14.8563 9.1903Z"
                                                    fill="white"
                                                ></path>
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->
                                    </a>
                                </div>
                                <!--end::Name-->
                                <!--begin::Info-->
                                <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                    
                                    <a href="javascript:void(0)" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                        <!--begin::Svg Icon | path: icons/duotune/general/gen018.svg-->
                                        <span class="svg-icon svg-icon-4 me-1">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    opacity="0.3"
                                                    d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z"
                                                    fill="currentColor"
                                                ></path>
                                                <path
                                                    d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z"
                                                    fill="currentColor"
                                                ></path>
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon--><?php echo $country;?>
                                    </a>
                                    <a href="javascript:void(0)" class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                        <!--begin::Svg Icon | path: icons/duotune/communication/com011.svg-->
                                        <span class="svg-icon svg-icon-4 me-1">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path opacity="0.3" d="M21 19H3C2.4 19 2 18.6 2 18V6C2 5.4 2.4 5 3 5H21C21.6 5 22 5.4 22 6V18C22 18.6 21.6 19 21 19Z" fill="currentColor"></path>
                                                <path d="M21 5H2.99999C2.69999 5 2.49999 5.10005 2.29999 5.30005L11.2 13.3C11.7 13.7 12.4 13.7 12.8 13.3L21.7 5.30005C21.5 5.10005 21.3 5 21 5Z" fill="currentColor"></path>
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon--><?php echo $email_main;?>
                                    </a>
                                </div>
                                <!--end::Info-->
                            </div>
                            <!--end::User-->
                        </div>
                        <!--end::Title-->
                        <!--begin::Stats-->
                        <div class="d-flex flex-wrap flex-stack">
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-column flex-grow-1 pe-8">
                                <!--begin::Stats-->
                                <div class="d-flex flex-wrap">
                                    <!--begin::Stat-->
                                    <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <!--begin::Number-->
                                        <div class="d-flex align-items-center">
                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
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
                                            <div class="fs-2 fw-bold counted" data-kt-countup="true" data-kt-countup-value="<?php echo $referal_T_amount;?>" data-kt-countup-prefix="$" data-kt-initialized="1">
                                                <?php echo $symbol.number_format($referal_T_amount,2);?>
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
                                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
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
                                            <div class="fs-2 fw-bold counted" data-kt-countup="true" data-kt-countup-value="<?php echo $ref_count;?>"
                                                 data-kt-countup-prefix="" data-kt-initialized="1"><?php echo $ref_count;?></div>
                                        </div>
                                        <!--end::Number-->
                                        <!--begin::Label-->
                                        <div class="fw-semibold fs-6 text-gray-400">Referrals</div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Stat-->
                                </div>
                                <!--end::Stats-->
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Stats-->
                    </div>
                    <!--end::Info-->
                </div>
                <!--end::Details-->
                <!--begin::Navs-->
                <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                    <!--begin::Nav item-->
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5" href="../account-profile/">Overview</a>
                    </li>
                    <!--end::Nav item-->
                    <!--begin::Nav item-->
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5" href="../account-settings/">Settings</a>
                    </li>
                    <!--end::Nav item-->
                    <!--begin::Nav item-->
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5" href="../account-change-password/">Security</a>
                    </li>
                    <!--end::Nav item-->
                    <!--begin::Nav item-->
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5 active" href="../referral/">Referrals</a>
                    </li>
                    <!--end::Nav item-->
                </ul>
                <!--begin::Navs-->
            </div>
        </div>
        <!--end::Navbar-->
        <!--begin::Referral program-->
        <div class="card mb-5 mb-xl-10">
            <!--begin::Body-->
            <div class="card-body py-10">
                <h2 class="mb-9">Referral Program</h2>
                <!--begin::Overview-->
                <div class="row mb-10">
                    <!--begin::Col-->
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-xl-8">
                        <h4 class="text-gray-800 mb-0">Your Referral Link</h4>
                        <p class="fs-6 fw-semibold text-gray-600 py-4 m-0">
                            Use your referral link to invite friends
                            and family.
                        </p>
                        <div class="d-flex">
                            <input id="ref_link" type="text" class="form-control form-control-solid me-3 flex-grow-1" 
                                   readonly=""   name="ref_link" value="https://www.<?php echo $site_link;?>/register/?sponsor=<?php echo $identifier;?>" />
                            <button id="kt_referral_program_link_copy_btn" class="btn btn-light btn-active-light-primary fw-bold flex-shrink-0"
                                onclick="copy_text('ref_link')"    data-clipboard-target="#ref_link">Copy Link</button>
                        </div>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Overview-->
                <!--begin::Stats-->
                <div class="row">
                    <!--begin::Col-->
                    <div class="col">
                        <div class="card card-dashed flex-center min-w-175px my-3 p-6">
                            <span class="fs-4 fw-semibold text-danger pb-1 px-2">Referral Bonus</span>
                            <span class="fs-lg-2tx fw-bold d-flex justify-content-center">
                                <span data-kt-countup="true" data-kt-countup-value="<?php echo $referal_rate;?>" 
                                      class="counted" data-kt-initialized="1"><?php echo $referal_rate;?>%</span>
                            </span>
                        </div>
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col">
                        <div class="card card-dashed flex-center min-w-175px my-3 p-6">
                            <span class="fs-4 fw-semibold text-info pb-1 px-2">Available Bonus</span>
                            <span class="fs-lg-2tx fw-bold d-flex justify-content-center">
                                <?php echo $symbol;?> 
                                <span data-kt-countup="true" data-kt-countup-value="<?php echo number_format($referal_amount,2);?>" class="counted" 
                                        data-kt-initialized="1"><?php echo number_format($referal_amount,2);?>
                                </span>
                            </span>
                        </div>
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col">
                        <div class="card card-dashed flex-center min-w-175px my-3 p-6">
                            <span class="fs-4 fw-semibold text-success pb-1 px-2">Total Earned</span>
                            <span class="fs-lg-2tx fw-bold d-flex justify-content-center">
                                <?php echo $symbol;?> 
                                <span data-kt-countup="true" data-kt-countup-value="<?php echo number_format($referal_T_amount,2);?>" class="counted" data-kt-initialized="1">
                                    <?php echo number_format($referal_T_amount,2);?>
                                </span>
                            </span>
                        </div>
                    </div>
                    <!--end::Col-->
                    
                    <!--begin::Col-->
                    <div class="col">
                        <div class="card card-dashed flex-center min-w-175px my-3 p-6">
                            <span class="fs-4 fw-semibold text-primary pb-1 px-2">Referral Sign-ups</span>
                            <span class="fs-lg-2tx fw-bold d-flex justify-content-center">
                                <span data-kt-countup="true" data-kt-countup-value='<?php echo $ref_count;?>"' class="counted" data-kt-initialized="1">
                                    <?php echo $ref_count;?>
                                </span>
                            </span>
                        </div>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Stats-->
                <!--end::Info-->
                <!--begin::Notice-->
                <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-6">
                    <!--begin::Icon-->
                    <!--begin::Svg Icon | path: icons/duotune/finance/fin001.svg-->
                    <span class="svg-icon svg-icon-2tx svg-icon-primary me-4">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 19.725V18.725C20 18.125 19.6 17.725 19 17.725H5C4.4 17.725 4 18.125 4 18.725V19.725H3C2.4 19.725 2 20.125 2 20.725V21.725H22V20.725C22 20.125 21.6 19.725 21 19.725H20Z" fill="currentColor"></path>
                            <path
                                opacity="0.3"
                                d="M22 6.725V7.725C22 8.325 21.6 8.725 21 8.725H18C18.6 8.725 19 9.125 19 9.725C19 10.325 18.6 10.725 18 10.725V15.725C18.6 15.725 19 16.125 19 16.725V17.725H15V16.725C15 16.125 15.4 15.725 16 15.725V10.725C15.4 10.725 15 10.325 15 9.725C15 9.125 15.4 8.725 16 8.725H13C13.6 8.725 14 9.125 14 9.725C14 10.325 13.6 10.725 13 10.725V15.725C13.6 15.725 14 16.125 14 16.725V17.725H10V16.725C10 16.125 10.4 15.725 11 15.725V10.725C10.4 10.725 10 10.325 10 9.725C10 9.125 10.4 8.725 11 8.725H8C8.6 8.725 9 9.125 9 9.725C9 10.325 8.6 10.725 8 10.725V15.725C8.6 15.725 9 16.125 9 16.725V17.725H5V16.725C5 16.125 5.4 15.725 6 15.725V10.725C5.4 10.725 5 10.325 5 9.725C5 9.125 5.4 8.725 6 8.725H3C2.4 8.725 2 8.325 2 7.725V6.725L11 2.225C11.6 1.925 12.4 1.925 13.1 2.225L22 6.725ZM12 3.725C11.2 3.725 10.5 4.425 10.5 5.225C10.5 6.025 11.2 6.725 12 6.725C12.8 6.725 13.5 6.025 13.5 5.225C13.5 4.425 12.8 3.725 12 3.725Z"
                                fill="currentColor"
                            ></path>
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                    <!--end::Icon-->
                    <!--begin::Wrapper-->
                    <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                        <!--begin::Content-->
                        <div class="mb-3 mb-md-0 fw-semibold">
                            <h4 class="text-gray-900 fw-bold">Referral bonuses adds to wallet balance.</h4>
                            <div class="fs-6 text-gray-700 pe-7">Referral bonus earnings are automatically added to your wallet balance at the end of every trading week.</div>
                        </div>
                        <!--end::Content-->
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Notice-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Referral program-->
        <!--begin::Referred users-->
        <div class="card">
            <!--begin::Header-->
            <div class="card-header card-header-stretch">
                <!--begin::Title-->
                <div class="card-title">
                    <h3>Referred Users</h3>
                </div>
                <!--end::Title-->
            </div>
            <!--end::Header-->
            <!--begin::Tab content-->
            <div id="kt_referred_users_tab_content" class="tab-content">
                <!--begin::Tab panel-->
                <div id="kt_referrals_1" class="card-body p-0 tab-pane fade show active" role="tabpanel" aria-labelledby="#kt_referrals_tab_1">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-row-bordered table-flush align-middle gy-6">
                            <!--begin::Thead-->
                            <thead class="border-bottom border-gray-200 fs-6 fw-bold bg-lighten">
                                <tr>
                                    <th class="ps-9">#</th>
                                    <th class="min-w-125px px-0">User</th>
                                    <th class="min-w-125px">Date</th>
                                    <th class="min-w-125px">Status</th>
                                    <th class="min-w-125px ps-0">Bonus</th>
                                </tr>
                            </thead>
                            <!--end::Thead-->
                            <!--begin::Tbody-->
                            <tbody class="fs-6 fw-semibold text-gray-600">
                                <?php echo $display;?>
                            </tbody>
                            <!--end::Tbody-->
                        </table>
                        <!--end::Table-->
                    </div>
                </div>
                <!--end::Tab panel-->
            </div>
            <!--end::Tab content-->
        </div>
        <!--end::Referred users-->
    </div>
    <!--end::Content container-->
</div>

<?php include_once '../user_footer copy.php'; ?>
</body>

</html>