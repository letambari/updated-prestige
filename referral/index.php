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
$display = '<tr>
                <td colspan="4"> <strong >No record(s)..</strong></td>
              </tr>';
$ref_count = 0;
$sql = "SELECT id,username,full_name,email,avatar,signup FROM users WHERE sponsor='$email' ";
$query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($query);
if ($numrows > 0) {
    $display = '';
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        $ref_count += 1;
        $signup = $row['signup'];
        $id = $row['id'];
        $email1 = $row['email'];
        $username1 = $row['username'];
        $full_name1 = $row['full_name'];
        $avatar1 = $row['avatar'];
        if ($full_name1 == '') {
            $full_name1 = $username1;
        }
        $ref_img = '<img  src="../wp-includes/users/' . $email1 . '/' . $avatar1 . '" style="width:50%" class="rounded-circle  " alt="' . $full_name1 . '">';
        if ($avatar1 == '') {
            $ref_img = '<img alt="' . $f_n . '" class="rounded-circle " style="width:50%" src="../assets/avatar.jpg" />';
        }
        $sql1 = "SELECT active FROM account WHERE user_id='$id' ";
        $query1 = mysqli_query($db_conx, $sql1);
        $row1 = mysqli_fetch_array($query1, MYSQLI_ASSOC);
        $active1 = $row1['active'];
        $status_dis1 = '<span class="badge py-3 px-4 fs-7 badge-light-danger">dormant</span>';
        if ($active1 == 1) $status_dis1 = '<span class="badge py-3 px-4 fs-7 badge-light-success">active</span>';
        $display .= '<tr>
                        <td class="ps-9">' . $ref_count . '</td>
                        <td class="ps-0">
                            <a href="javascript:void(0)" class="text-gray-600 text-hover-primary">
                                ' . $full_name1 . '
                            </a>
                        </td>
                        <td>' . date('D d, M Y', strtotime($signup)) . '</td>
                        <td>' . $status_dis1 . '</td>
                        <td class="text-success">5%</td>
                    </tr>';
    }
}
?> <?php
    $title = 'Referrals | ' . $site_name;
    $title2 = 'Referrals';
    $keyword = '';
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
    $class_link = '';
    include_once '../user_header.php';
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

<!-- Row -->
<div class="row square">
    <div class="col-lg-12 col-md-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="panel profile-cover">
                    <div class="profile-cover__img">
                        <?php echo $img3; ?>
                        <h3 class="h3"><?php echo $f_n; ?></h3>
                    </div>

                    <div class="profile-cover__action bg-img"></div>
                    <div class="profile-cover__info">
                        <ul class="nav">
                            <li><strong><?php echo $investment_count; ?></strong>Portfolio</li>
                            <li><strong> <?php echo $symbol . number_format($invested, 2); ?></strong>Invested</li>
                            <li><strong> <?php echo $symbol . number_format($t_profit, 2); ?></strong>Earnings</li>
                        </ul>
                    </div>
                </div>
                <div class="profile-tab tab-menu-heading">
                    <nav class="nav main-nav-line p-3 tabs-menu profile-nav-line bg-gray-100">
                        <a class="nav-link  active" href="../account-profile/">Overview</a>
                        <a class="nav-link" href="../account-settings/">Account Settings</a>
                        <a class="nav-link" href="../account-change-password/">Security</a>
                        <a class="nav-link" href="../referral/">Referrals</a>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Row -->

<!--begin::Referral program-->
<div class="card mb-5 mb-xl-10">
    <!--begin::Body-->
    <div class="card-body py-10">
        <h2 class="mb-9" style="text-align: center;">Referral Program</h2>
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
                    <input id="ref_link" type="text" class="form-control form-control-solid me-3 flex-grow-1" readonly="" name="ref_link" value="https://www.<?php echo $site_link; ?>/register/?sponsor=<?php echo $identifier; ?>" />
                    <button id="kt_referral_program_link_copy_btn" class="btn btn-light btn-active-light-primary fw-bold flex-shrink-0" onclick="copy_text('ref_link')" data-clipboard-target="#ref_link">Copy Link</button>
                </div>
            </div>
            <!--end::Col-->
        </div>
        <!--end::Overview-->
        <br><br>
        <!--begin::Stats-->
        <div class="row">

            <!--begin::Col-->
            <div class="col">

                <div class="card custom-card card-body bg-gray-800 tx-white">
                    <span class="fs-4 fw-semibold text-danger pb-1 px-2" style="text-align: center;">Referral Bonus</span>
                    <span class="fs-lg-2tx fw-bold d-flex justify-content-center">
                        <span data-kt-countup="true" data-kt-countup-value="<?php echo $referal_rate; ?>" class="counted" data-kt-initialized="1"><?php echo $referal_rate; ?>%</span>
                    </span>
                </div>
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col">
                <div class="card custom-card card-body bg-gray-800 tx-white">
                    <span class="fs-4 fw-semibold text-info pb-1 px-2" style="text-align: center;">Available Bonus</span>
                    <span class="fs-lg-2tx fw-bold d-flex justify-content-center">
                        <?php echo $symbol; ?>
                        <span data-kt-countup="true" data-kt-countup-value="<?php echo number_format($referal_amount, 2); ?>" class="counted" data-kt-initialized="1"><?php echo number_format($referal_amount, 2); ?>
                        </span>
                    </span>
                </div>
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col">
                <div class="card custom-card card-body bg-gray-800 tx-white">
                    <span class="fs-4 fw-semibold text-success pb-1 px-2" style="text-align: center;">Total Earned</span>
                    <span class="fs-lg-2tx fw-bold d-flex justify-content-center">
                        <?php echo $symbol; ?>
                        <span data-kt-countup="true" data-kt-countup-value="<?php echo number_format($referal_T_amount, 2); ?>" class="counted" data-kt-initialized="1">
                            <?php echo number_format($referal_T_amount, 2); ?>
                        </span>
                    </span>
                </div>
            </div>
            <!--end::Col-->

            <!--begin::Col-->
            <div class="col">
                <div class="card custom-card card-body bg-gray-800 tx-white">
                    <span class="fs-4 fw-semibold text-primary pb-1 px-2" style="text-align: center;" style="text-align: center;">Referral Sign-ups</span>
                    <span class="fs-lg-2tx fw-bold d-flex justify-content-center">
                        <span data-kt-countup="true" data-kt-countup-value='<?php echo $ref_count; ?>"' class="counted" data-kt-initialized="1">
                            <?php echo $ref_count; ?>
                        </span>
                    </span>
                </div>
            </div>
            <!--end::Col-->
        </div>
        <!--end::Stats-->
        <!--end::Info-->
        <!--begin::Notice-->
        <div class="card custom-card card-body bg-primary tx-white">
            <!--begin::Icon-->
            <!--begin::Svg Icon | path: icons/duotune/finance/fin001.svg-->
            <span class="svg-icon svg-icon-2tx svg-icon-primary me-4">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 19.725V18.725C20 18.125 19.6 17.725 19 17.725H5C4.4 17.725 4 18.125 4 18.725V19.725H3C2.4 19.725 2 20.125 2 20.725V21.725H22V20.725C22 20.125 21.6 19.725 21 19.725H20Z" fill="currentColor"></path>
                    <path opacity="0.3" d="M22 6.725V7.725C22 8.325 21.6 8.725 21 8.725H18C18.6 8.725 19 9.125 19 9.725C19 10.325 18.6 10.725 18 10.725V15.725C18.6 15.725 19 16.125 19 16.725V17.725H15V16.725C15 16.125 15.4 15.725 16 15.725V10.725C15.4 10.725 15 10.325 15 9.725C15 9.125 15.4 8.725 16 8.725H13C13.6 8.725 14 9.125 14 9.725C14 10.325 13.6 10.725 13 10.725V15.725C13.6 15.725 14 16.125 14 16.725V17.725H10V16.725C10 16.125 10.4 15.725 11 15.725V10.725C10.4 10.725 10 10.325 10 9.725C10 9.125 10.4 8.725 11 8.725H8C8.6 8.725 9 9.125 9 9.725C9 10.325 8.6 10.725 8 10.725V15.725C8.6 15.725 9 16.125 9 16.725V17.725H5V16.725C5 16.125 5.4 15.725 6 15.725V10.725C5.4 10.725 5 10.325 5 9.725C5 9.125 5.4 8.725 6 8.725H3C2.4 8.725 2 8.325 2 7.725V6.725L11 2.225C11.6 1.925 12.4 1.925 13.1 2.225L22 6.725ZM12 3.725C11.2 3.725 10.5 4.425 10.5 5.225C10.5 6.025 11.2 6.725 12 6.725C12.8 6.725 13.5 6.025 13.5 5.225C13.5 4.425 12.8 3.725 12 3.725Z" fill="currentColor"></path>
                </svg>
            </span>
            <!--end::Svg Icon-->
            <!--end::Icon-->
            <!--begin::Wrapper-->

            <h4 class="text-gray-900 fw-bold">Referral bonuses adds to wallet balance.</h4>
            <div class="fs-6 text-gray-700 pe-7">Referral bonus earnings are automatically added to your wallet balance at the end of every trading week.</div>
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
                        <?php echo $display; ?>
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

<br><br>

<!-- End Main Content-->

<!-- Main Footer-->
<?php include_once '../user_footer.php'; ?>

</body>

</html>