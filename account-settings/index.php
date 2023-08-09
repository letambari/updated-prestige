<?php
include_once("../controller/dependent.php");
///////////////////////////////////////////////////////////////
if (isset($_FILES["img"]["name"]) && $_FILES["img"]["tmp_name"] != "") {
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $fileName = $_FILES["img"]["name"];
    $fileTmpLoc = $_FILES["img"]["tmp_name"];
    $fileType = $_FILES["img"]["type"];
    $fileSize = $_FILES["img"]["size"];
    $fileErrorMsg = $_FILES["img"]["error"];
    $kaboom = explode(".", $fileName);
    $fileExt = end($kaboom);
    list($width, $height) = getimagesize($fileTmpLoc);
    if ($width < 10 || $height < 10) {
        $error = "Image too small, select another.";
    } else if ($fileSize > 4048576) {
        $error =  "<span style='color:#a94442'>Image Size too big, select another and try again.</span>";
    } else if (!preg_match("/\.(gif|jpg|png)$/i", $fileName)) {
        $error =  "<span style='color:#a94442'> Your image file was not recognized, select another and try again.</span>";
    } else if ($fileErrorMsg == 1) {
        $error =  "<span style='color:#a94442'> An unknown error occurred.. Pls try again</span>";
    } else {
        //////CHECKING IF THE USER ALREADY HAS AN IMAGE UPLOADED////////
        $uniq = $_SESSION[$site_cokie];
        $sql = "SELECT email,avatar FROM users WHERE unique_field='$uniq' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $row = mysqli_fetch_row($query);
        $un = $row[0];
        $img_name = $row[1];
        $randNum = randNumGen(12);
        $avatar_name = $randNum . '.' . $fileExt;
        $path = "$un";
        if ($img_name != "") {
            $picurl = "../wp-includes/users/$path/$img_name";
            if (file_exists($picurl)) {
                unlink($picurl);
            }
        }
        $moveResult = move_uploaded_file($fileTmpLoc, "../wp-includes/users/$path/$avatar_name");
        if ($moveResult != true) {
            $error = "<span style='color:#a94442'> Image upload failed... Try again.</span>";
        } else {
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
if (isset($_GET['update_account']) && isset($_POST['name']) and isset($_POST['UserMobile'])) {
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $name = mysqli_real_escape_string($db_conx, ucwords($_POST['name']));
    $tel = preg_replace('#[^+0-9]#i', '', $_POST['UserMobile']);
    $country = $_POST['country'];
    $currency = mysqli_real_escape_string($db_conx, $_POST['currency']);
    $two_fa = 1;
    if ($two_fa_control == 1 && isset($_POST['two_fa'])) {
        $two_fa = 2;
    }
    //$two_fa = $_POST['two_fa'];
    // DUPLICATE DATA CHECKS  EMAIL
    $sql = "SELECT id,email FROM users WHERE id='$profile_id' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $user_check = mysqli_num_rows($query);
    if ($name === "" || $tel === "" || $country === "" || $two_fa === "") {
        echo 2;
        mysqli_close($db_conx);
        exit();
    } else if ($name == "none" || $tel == "none" || $country == "none") {
        echo 2;
        mysqli_close($db_conx);
        exit();
    } else if ($user_check < 0) {
        echo 3;
        mysqli_close($db_conx);
        exit();
    } else {
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
        if (!$query) {
            echo mysqli_error($db_conx);
            exit();
        }
        $sql = "UPDATE random_string SET security_level='$two_fa' where  user_id='$profile_id' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        if (!$query) {
            echo mysqli_error($db_conx);
            exit();
        }
        ///////////////////////////////////////////////////////
        if ($currency != '') {
            $sql = "UPDATE account SET wallets currency='$currency' where  user_id='$profile_id' LIMIT 1";
            $query = mysqli_query($db_conx, $sql);
            if (!$query) {
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
        if (!$query) {
            echo mysqli_error($db_conx);
            exit();
        } else {
            echo 1;
            echo mysqli_error($db_conx);
            exit();
        }
    }
}

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
while ($row = mysqli_fetch_array($user_query, MYSQLI_ASSOC)) {
    $country = $row['country'];
    $sponsor = $row['sponsor'];
    if ($sponsor == '') {
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
$two_fa = '<input class="form-check-input w-45px h-30px" type="checkbox" name="two_fa"  />';
if ($security_level == 2) {
    $two_fa = '<input class="form-check-input w-45px h-30px" type="checkbox" name="two_fa" checked="checked"  />';
}
?> <?php
    $title = 'Settings | ' . $site_name;
    $title2 = 'Settings';
    $keyword = '';
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
                        <a class="nav-link" href="../account-profile/">Overview</a>
                        <a class="nav-link" active href="../account-settings/">Account Settings</a>
                        <a class="nav-link" href="../account-change-password/">Security</a>
                        <a class="nav-link" href="../referral/">Referrals</a>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Row -->

<!-- Row -->
<div class="row row-sm">
    <div class="col-lg-12 col-md-12">
        <div class="card custom-card main-content-body-profile">
            <div class="tab-content">

                <div class="main-content-body tab-pane p-4 border-top-0 active" id="edit">
                    <div class="card-body border">

                        <!--begin::Card header-->
                        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
                            <!--begin::Card title-->
                            <div class="card-title m-0">
                                <h3 class="fw-bold m-0">Profile Details</h3>
                            </div>
                            <!--end::Card title-->
                        </div>
                    </div>


                    <div class="main-content-body tab-pane p-4 border-top-0" id="settings">
                        <div class="card-body border" data-select2-id="12">
                            <form class="form-horizontal" data-select2-id="11" onsubmit="return false" id="accountForm">

                                <div class="form-group ">
                                    <div class="row row-sm">
                                        <div class="col-md-3">
                                            <?php echo $img3; ?>
                                            <input type="file" name="img" id="img" required="" onchange="this.form.submit()" class="form-control" accept=".png, .jpg, .jpeg" />
                                            <input type="hidden" name="avatar_remove" value="1" />
                                            <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                                            <!-- <div class="image-input-wrapper w-125px h-125px" style="background-image: url(<?php echo $img_link; ?>);"></div> -->
                                        </div>
                                        <br>
                                    </div>
                                </div>

                                <!--begin::Hint-->

                                <!--end::Hint-->
                                <div class="form-group ">
                                    <div class="row row-sm">
                                        <div class="col-md-3">
                                            <label class="form-label">Full Name</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" placeholder="User Name" id="name" name="name" required="" value="<?php echo $f_n; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <div class="row row-sm">
                                        <div class="col-md-3">
                                            <label class="form-label">Contact Phone</label>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" placeholder="Email" value="<?php echo $tel; ?>" id="UserMobile" name="UserMobile" required="">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group " data-select2-id="108">
                                    <div class="row" data-select2-id="107">
                                        <div class="col-md-3">
                                            <label class="form-label">Currency</label>
                                        </div>
                                        <div class="col-md-9" data-select2-id="106">
                                            <select class="form-control select2" name="currency" id="currency">
                                                <option value="">Select Currency</option>
                                                <option value="USD|$">USD&nbsp;-&nbsp;USA dollar</option>
                                                <option value="POUND|£">GBP&nbsp;-&nbsp;British pound</option>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group " data-select2-id="10">
                                    <div class="row" data-select2-id="9">
                                        <div class="col-md-3">
                                            <label class="form-label">Country</label>
                                        </div>
                                        <div class="col-md-9" data-select2-id="8">
                                            <select name="country" id="country" required="" aria-label="Select a Country" data-placeholder="Select a country..." class="form-control" data-select2-id="select2-data-10-k9fj" tabindex="-1" aria-hidden="true" data-kt-initialized="1">
                                                <option value="<?php echo $country; ?>"><?php echo $country; ?></option>
                                                <?php echo $country_list; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group <?php echo $two_fa_display; ?>">
                                    <div class="row row-sm">
                                        <div class="col-md-3">
                                            <label class="form-label">2FA</label>
                                        </div>
                                        <div class="col-md-9">
                                            <label class="ckbox mg-b-10-f">
                                                <?php echo $two_fa; ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!--begin::Col-->
                                <div class="form-group ">
                                    <span id='update_status'></span>
                                </div>
                                <!--end::Col-->
                                <div class="form-group ">
                                    <div class="row row-sm">
                                        <div class="col-md-3"> </div>
                                        <div class="mt-3">
                                            <button id="acct_btn" onclick="update_account()" type="submit" class="btn btn-primary mr-1">Save</button>
                                            <button type="reset" class="btn btn-danger">Discard</button>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Row -->

<!-- End Main Content-->

<!-- Main Footer-->
<?php include_once '../user_footer.php'; ?>
</body>

</html>