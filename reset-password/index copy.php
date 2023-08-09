<?php
//include_once("../wp-includes/php_/check_login_status.php");
//include_once("../wp-includes/php_/template_country_list.php");
include_once("../wp-includes/php_/config.php");
if(isset($_SESSION[$site_cokie])){
    header("location: ../dashboard");
    exit;
}
// If user is logged in, header them away
if(isset($_SESSION[$site_cokie])){
    $identifier = preg_replace('#[^a-z$0-9]#i', '', $_SESSION[$site_cokie]);
    // CHECK TO KNOW IF THE PERSON HAS PAID.... IF NOT REDIRECT THEM TO THE DEFAULT PAYMENT PAGE
    $sql = "SELECT id FROM users WHERE unique_field='$identifier' LIMIT 1";
    $user_query = mysqli_query($db_conx, $sql);
    // Now make sure that user exists in the table
    $numrows = mysqli_num_rows($user_query);
    if($numrows > 0){
        header("location: ../dashboard");
        exit();	
    }else{
        $_SESSION = array();
	// Expire their cookie files
	if( isset($_COOKIE[$site_cokie])) {
	   setcookie($site_cokie, '', strtotime( '-5 days' ),'/');
	}
	session_unset(); 
	// Destroy the session variables
	session_destroy();
    }
}
$display ='<form class="form w-100" novalidate="novalidate" name="pwd_link_form" id="pwd_link_form" onsubmit="return false;">
                <div class="text-center mb-11">
                    <!--begin::Title-->
                    <!--end::Title-->
                    <div class="text-center mb-10">
                        <!--begin::Title-->
                        <h1 class="text-dark fw-bolder mb-3">Forgot Password ?</h1>
                        <!--end::Title-->
                        <!--begin::Separator-->
                        <div class="separator separator-content my-14">
                            <span class="w-125px text-gray-500 fw-semibold fs-7">
                                <div id="google_translate_element"></div>
                            </span>
                        </div>
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-6">Enter your email to reset your password.</div>
                        <!--end::Link-->
                    </div>
                </div>
                <!--begin::Heading-->
                <!--begin::Input group=-->
                <div class="fv-row mb-8">
                    <!--begin::Email-->
                    <input type="text" placeholder="Username or Email" name="unameemail" id="unameemail"
                        autocomplete="off" class="form-control bg-transparent" required="" />
                    <!--end::Email-->
                </div>
                <!--begin::Input group=-->
                <!--end::Accept-->
                <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold ">
                    <div id="pass_msg" style=" width: 100%"></div>
                </div>
                <!--begin::Submit button-->
                <div class="d-grid mb-10">
                    <button type="submit" class="btn btn-primary" onclick="send_reset_link()" id="pass_btn">
                        Submit
                    </button>
                </div>
                <!--end::Submit button-->
                <!--begin::Sign up-->
                <div class="text-gray-500 text-center fw-semibold fs-6">
                    Already have an Account? 
                    <a href="../login/" class="link-primary fw-semibold">Sign in</a>
                </div>
                <!--end::Sign up-->
            </form>';
/////////////// to now reset the password /////////////////////
if(isset($_GET['q']) && isset($_GET['reset-password']) && isset($_GET['u']) && strlen($_GET['u']) > 8 && isset($_GET['e'])){
    $u = mysqli_real_escape_string($db_conx, $_GET['u']);
    $e = mysqli_real_escape_string($db_conx, $_GET['e']);
    $sql = "SELECT id FROM users WHERE unique_field='$u' and email='$e' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $numrows = mysqli_num_rows($query);
    if($numrows > 0){
        $display = '<!--begin::Form-->
                    <form class="form w-100" novalidate="novalidate" name="pwd_link_form" id="pwd_link_form" onsubmit="return false;">
                        <div class="text-center mb-11">
                            <!--begin::Title-->
                            <!--end::Title-->
                            <div class="text-center mb-10">
                                <!--begin::Title-->
                                <h1 class="text-dark fw-bolder mb-3">Reset Password</h1>
                                <!--end::Title-->
                                <!--begin::Separator-->
                                <div class="separator separator-content my-14">
                                    <span class="w-125px text-gray-500 fw-semibold fs-7">
                                        <div id="google_translate_element"></div>
                                    </span>
                                </div>
                                <!--begin::Link-->
                                <div class="text-gray-500 fw-semibold fs-6">
                                    Fill and submit the form to complete the process.
                                </div>
                                <!--end::Link-->
                            </div>
                        </div>
                        <!--begin::Heading-->
                        <!--begin::Input group=-->
                        <div class="fv-row mb-8">
                            <!--begin::Email-->
                            <input name="emailF" id="emailF"  value="'.$e.'" type="email" disabled="" 
                                   class="form-control bg-transparent" placeholder="Email" />
                            <input type="hidden" id="__x" name="__x" value="'.$u.'" />
                            <!--end::Email-->
                        </div>
                        <div class="fv-row mb-8" data-kt-password-meter="true">
                            <!--begin::Wrapper-->
                            <div class="mb-1">
                                <!--begin::Input wrapper-->
                                <div class="position-relative mb-3">
                                    <input class="form-control bg-transparent" type="password" placeholder="Password" id="password" name="password" autocomplete="off" />
                                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                                        <i class="bi bi-eye-slash fs-2"></i>
                                        <i class="bi bi-eye fs-2 d-none"></i>
                                    </span>
                                </div>
                                <!--end::Input wrapper-->
                                <!--begin::Meter-->
                                <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                                </div>
                                <!--end::Meter-->
                            </div>
                            <!--end::Wrapper-->
                            <!--begin::Hint-->
                            <div class="text-muted">Use 8 or more characters with a mix of letters, numbers & symbols.</div>
                            <!--end::Hint-->
                        </div>
                        <!--begin::Input group=-->
                        <div class="fv-row mb-8">
                            <!--begin::Repeat Password-->
                            <input type="password" placeholder="Repeat Password" name="password2" id="password2" 
                                   autocomplete="off" class="form-control bg-transparent" />
                            <!--end::Repeat Password-->
                        </div>
                        <!--end::Accept-->
                        <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold ">
                            <div id="status1" style=" width: 100%"></div>
                        </div>
                        <!--begin::Submit button-->
                        <div class="d-grid mb-10">
                            <button type="submit" class="btn btn-primary" onclick="reset_password()" id="pass_btn">
                                Submit
                            </button>
                        </div>
                        <!--end::Submit button-->
                        <!--end::Sign up-->
                    </form>
                    <!--end::Form-->';
    }
}
?><?php 
    $title ='Forget Password | '.$site_name;
    $keyword ='Reset password, forget password,logon,sign up in at '.$site_name.', '.$site_link;
    $discription = 'Regain access to your account, '; 
    $home = '';
    $about = '';
    $services = '';
    $acct = '';
    $pricing = '';
    $faq = '';
    $contact = '';
    include_once '../auth_temp_header.php'; 
?>

        <!--begin::Card body-->
                        <div class="card-body p-10 p-lg-20">
                            <?php echo $display;?>
                        </div>
                        <!--end::Card body-->
        <?php include_once '../auth_temp_footer.php'; ?>                
    </body>
</html>