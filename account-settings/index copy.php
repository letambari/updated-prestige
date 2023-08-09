<?php
include_once("../controller/dependent.php");
///////////////////////////////////////////////////////////////
if( isset($_FILES["img"]["name"]) && $_FILES["img"]["tmp_name"] != ""){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $fileName = $_FILES["img"]["name"];
    $fileTmpLoc = $_FILES["img"]["tmp_name"];
    $fileType = $_FILES["img"]["type"];
    $fileSize = $_FILES["img"]["size"];
    $fileErrorMsg = $_FILES["img"]["error"];
    $kaboom = explode(".", $fileName);
    $fileExt = end($kaboom);
    list($width, $height) = getimagesize($fileTmpLoc);
    if($width < 10 || $height < 10){
        $error = "Image too small, select another.";
    }else if($fileSize > 4048576) {
        $error =  "<span style='color:#a94442'>Image Size too big, select another and try again.</span>";
    } else if (!preg_match("/\.(gif|jpg|png)$/i", $fileName) ) {
        $error =  "<span style='color:#a94442'> Your image file was not recognized, select another and try again.</span>";
    } else if ($fileErrorMsg == 1) {
        $error =  "<span style='color:#a94442'> An unknown error occurred.. Pls try again</span>";
    }else{
        //////CHECKING IF THE USER ALREADY HAS AN IMAGE UPLOADED////////
        $uniq = $_SESSION[$site_cokie];
        $sql = "SELECT email,avatar FROM users WHERE unique_field='$uniq' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $row = mysqli_fetch_row($query);
        $un = $row[0];
        $img_name = $row[1];
        $randNum = randNumGen(12);
        $avatar_name = $randNum.'.'.$fileExt;
        $path = "$un";
        if($img_name != ""){
            $picurl = "../wp-includes/users/$path/$img_name";
            if (file_exists($picurl)) { unlink($picurl); }
        }
        $moveResult = move_uploaded_file($fileTmpLoc, "../wp-includes/users/$path/$avatar_name");
        if ($moveResult != true) {
            $error = "<span style='color:#a94442'> Image upload failed... Try again.</span>";
        }else{
            include_once("../wp-includes/php_/image_resize.php");
            $target_file = "../wp-includes/users/$path/$avatar_name";
            $resized_file = "../wp-includes/users/$path/$avatar_name";
            $wmax = 500;
            $hmax = 500;
            img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
            $sql = "UPDATE users set avatar='$avatar_name' WHERE unique_field='$uniq' LIMIT 1";
            $query = mysqli_query($db_conx, $sql);
        }
        header("location: ");
    }
        
}
//echo $error;
//////// Script to update profile ////////////
/////////////////////////////////////////////////////////////////
if( isset($_GET['update_account']) && isset($_POST['name']) and isset($_POST['UserMobile'])){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $name = mysqli_real_escape_string($db_conx, ucwords($_POST['name']));
    $tel = preg_replace('#[^+0-9]#i', '', $_POST['UserMobile']);
    $country = $_POST['country'];
    $currency = mysqli_real_escape_string($db_conx, $_POST['currency']);
    $two_fa = 1;
    if($two_fa_control ==1 && isset($_POST['two_fa'])){
        $two_fa = 2;
    }
    //$two_fa = $_POST['two_fa'];
    // DUPLICATE DATA CHECKS  EMAIL
    $sql = "SELECT id,email FROM users WHERE id='$profile_id' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $user_check = mysqli_num_rows($query);
    if($name === "" || $tel === ""|| $country === ""||$two_fa === ""){
        echo 2;
        mysqli_close($db_conx);
        exit();
    }else if($name == "none" || $tel == "none"|| $country == "none"){
        echo 2;
        mysqli_close($db_conx);
        exit();
    }else if ($user_check < 0){ 
        echo 3;
        mysqli_close($db_conx);
        exit();
    }else{   
//     if(substr($wallet,0,1) != 1 && substr($wallet,0,1) != 3){
//        echo 4;
//        exit();
//    }else if(strlen($wallet) < 26 || strlen($wallet) > 35){
//        echo 4;
//        exit();
//    }
        $new_time = date("Y-m-d H:i:s");
        $sql = "UPDATE users SET full_name='$name',phone='$tel',country='$country' where  id='$profile_id' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        if(!$query){
            echo mysqli_error($db_conx);
            exit();
        }
        $sql = "UPDATE random_string SET security_level='$two_fa' where  user_id='$profile_id' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        if(!$query){
            echo mysqli_error($db_conx);
            exit();
        }
        ///////////////////////////////////////////////////////
        if($currency != ''){
            $sql = "UPDATE account SET wallets currency='$currency' where  user_id='$profile_id' LIMIT 1";
            $query = mysqli_query($db_conx, $sql);
            if(!$query){
                echo mysqli_error($db_conx);
                exit();
            }
        }
            
        /////////////////////////////////////////////////////////////////////////////
//        $sql = "INSERT INTO wallets (user_id,wallet_name,wallet_address, date_added)";
//        $sql .= "VALUES('$profile_id','bitcoin','$wallet','$new_time')";            
//        if (mysqli_num_rows($query) > 0){ 
//            $sql = "UPDATE wallets SET wallet_address='$wallet' where  user_id='$profile_id' and wallet_name='bitcoin' LIMIT 1";
//        }
//        $query = mysqli_query($db_conx, $sql);
        if(!$query){
            echo mysqli_error($db_conx);
            exit();
        }else{
            echo 1;
            echo mysqli_error($db_conx);
            exit();
        } 
    }
}

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
while ($row = mysqli_fetch_array($user_query, MYSQLI_ASSOC)) {
    $country = $row['country'];
    $sponsor = $row['sponsor'];
    if($sponsor == ''){
        $sponsor = 'none';
    }
}
//////////////
//////////////////////////
// Check their security level
$sql = "SELECT security_level FROM random_string WHERE user_id='$profile_id' LIMIT 1";
$query = mysqli_query($db_conx, $sql);
$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
$security_level = $row['security_level'];
$two_fa = '<input class="form-check-input w-45px h-30px" type="checkbox" name="two_fa"  />' ;
if($security_level ==2){
    $two_fa = '<input class="form-check-input w-45px h-30px" type="checkbox" name="two_fa" checked="checked"  />' ;
}
?> <?php 
    $title ='Settings | '.$site_name;
    $title2 ='Settings';
    $keyword ='';
    $discription = ''; 
    $dash = '';
    $dep = '';
    $pricing = '';
    $pur_invest = '';
    $my_invest = '';
    $with = '';
    $act_his = '';
    $ref = '';
    $acct_pro = ''; 
    $acct_settings = 'active'; 
    $acct_security = '';
    $suppt = '';    
    include_once("../wp-includes/php_/template_country_list.php");
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
                                            <div class="fs-2 fw-bold counted" data-kt-countup="true" 
                                                 data-kt-countup-value="<?php echo $investment_count;?>" data-kt-countup-prefix="" data-kt-initialized="1">
                                                <?php echo $investment_count;?>
                                            </div>
                                        </div>
                                        <!--end::Number-->
                                        <!--begin::Label-->
                                        <div class="fw-semibold fs-6 text-gray-400">Portfolio</div>
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
                                            <div class="fs-2 fw-bold counted" data-kt-countup="true" data-kt-countup-value="<?php echo $invested;?>" data-kt-countup-prefix="$" data-kt-initialized="1">
                                                <?php echo $symbol.number_format($invested,2);?>
                                            </div>
                                        </div>
                                        <!--end::Number-->
                                        <!--begin::Label-->
                                        <div class="fw-semibold fs-6 text-gray-400">Invested</div>
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
                                            <div class="fs-2 fw-bold counted" data-kt-countup="true" data-kt-countup-value="<?php echo $t_profit;?>" data-kt-countup-prefix="$" data-kt-initialized="1">
                                                <?php echo $symbol.number_format($t_profit,2);?>
                                            </div>
                                        </div>
                                        <!--end::Number-->
                                        <!--begin::Label-->
                                        <div class="fw-semibold fs-6 text-gray-400">Earnings</div>
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
                        <a class="nav-link text-active-primary ms-0 me-10 py-5 " href="../account-profile/">Overview</a>
                    </li>
                    <!--end::Nav item-->
                    <!--begin::Nav item-->
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5 active" href="../account-settings/">Settings</a>
                    </li>
                    <!--end::Nav item-->
                    <!--begin::Nav item-->
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5" href="../account-change-password/">Security</a>
                    </li>
                    <!--end::Nav item-->
                    <!--begin::Nav item-->
                    <li class="nav-item mt-2">
                        <a class="nav-link text-active-primary ms-0 me-10 py-5 " href="../referral/">Referrals</a>
                    </li>
                    <!--end::Nav item-->
                </ul>
                <!--begin::Navs-->
            </div>
        </div>
        <!--end::Navbar-->
        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Profile Details</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->
            <!--begin::Content-->
            <div id="kt_account_settings_profile_details" class="collapse show">
                <form action="" method="post" role="form" enctype="multipart/form-data"  class="form fv-plugins-bootstrap5 fv-plugins-framework">
                    <div class="card-body border-top p-9">
                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">Avatar</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Image input-->
                                <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('../assets_inside/img/svg/avatars/blank.svg');">
                                    <!--begin::Preview existing avatar-->
                                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url(<?php echo $img_link;?>);"></div>
                                    <!--end::Preview existing avatar-->
                                    <!--begin::Label-->
                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" aria-label="Change avatar" data-kt-initialized="1">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <!--begin::Inputs-->
                                        <input type="file" name="img" id="img" required="" onchange="this.form.submit()" class="form-control"  accept=".png, .jpg, .jpeg" />
                                        <input type="hidden" name="avatar_remove" value="1" />
                                        <!--end::Inputs-->
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Cancel-->
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel avatar" data-kt-initialized="1">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                    <!--end::Cancel-->
                                    <!--begin::Remove-->
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" aria-label="Remove avatar" data-kt-initialized="1">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                    <!--end::Remove-->
                                </div>
                                <!--end::Image input-->
                                <!--begin::Hint-->
                                <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                                <!--end::Hint-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                    </div>
                        
                </form>
                <!--begin::Form-->
                <form onsubmit="return false" id="accountForm" class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">
                        
                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Full Name</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8">
                                <!--begin::Row-->
                                <div class="row">
                                    <!--begin::Col-->
                                    <div class="col-lg-12 fv-row fv-plugins-icon-container">
                                        <input class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="Full Name" id="name" name="name" 
                                            required=""  value="<?php echo $f_n;?>"   type="text">
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                    </div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Row-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">
                                <span class="required">Contact Phone</span>
                                <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" aria-label="Phone number must be active" data-kt-initialized="1"></i>
                            </label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <input placeholder="Mobile" class="form-control form-control-lg form-control-solid" type="tel" 
                                    value="<?php echo $tel;?>"   id="UserMobile" name="UserMobile" required=""/>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">
                                <span class="required">Country</span>
                                <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" aria-label="Country of origination" data-kt-initialized="1"></i>
                            </label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                                <select name="country" id="country"   required=""
                                    aria-label="Select a Country"
                                    data-placeholder="Select a country..."
                                    class="form-select form-select-solid form-select-lg fw-semibold "
                                    data-select2-id="select2-data-10-k9fj"
                                    tabindex="-1"
                                    aria-hidden="true"
                                    data-kt-initialized="1"
                                        >
                                    <option value="<?php echo $country;?>" ><?php echo $country;?></option>
                                    <?php echo $country_list;?>
                                </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--begin::Input group-->
                        <div class="row mb-6">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">Currency</label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row">
                                <select class="form-select form-select-solid form-select-lg" name="currency" id="currency">
                                        <option value="">Select Currency</option>
                                        <option value="USD|$" >USD&nbsp;-&nbsp;USA dollar</option>
                                        <option value="POUND|£">GBP&nbsp;-&nbsp;British pound</option>
                                </select>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-6" <?php echo $two_fa_display;?> >
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">2FA </label>
                            <!--begin::Label-->
                            <!--begin::Label-->
                            <div class="col-lg-8 d-flex align-items-center">
                                <div class="form-check form-check-solid form-switch fv-row">
                                    <?php echo $two_fa;?>
                                    <label class="form-check-label" for="allowmarketing"></label>
                                </div>
                            </div>
                            <!--begin::Label-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-0">
                            <!--begin::Label-->
                            <label class="col-lg-4 col-form-label fw-semibold fs-6"></label>
                            <!--end::Label-->
                            <!--begin::Col-->
                            <div class="col-lg-8 fv-row">
                                <span id='update_status'></span> 
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Card body-->
                    <!--begin::Actions-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
                        <button id="acct_btn" onclick="update_account()" type="submit" class="btn btn-primary ">
                            Save changes
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Content-->
        </div>


    </div>
    <!--end::Content container-->
</div>

<?php include_once '../user_footer.php'; ?>
</body>

</html>