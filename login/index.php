<?php
//include_once("../wp-includes/php_/check_login_status.php");
//include_once("../wp-includes/php_/template_country_list.php");
//include_once("../wp-includes/php_/functions.php");
//include_once("../wp-includes/php_/randStrGen.php");
include_once("../wp-includes/php_/config.php");
if(isset($_SESSION[$site_cokie])){
    header("location: ../dashboard");
    exit;
}
/////////// /////////////////////////////////////
/////////// script to  login members on the registration  page //////////////
if(isset($_GET['login']) && isset($_POST['Uemail'])){   
    $uname = mysqli_real_escape_string($db_conx, $_POST['Uemail']);
    $p = md5($_POST['password']);
    $rememberMe = FALSE;
    if(isset($_POST['rememberL'])){        
        $rememberMe = TRUE;
    }
    $_SESSION["rememberL"] = $rememberMe;
    // FORM DATA ERROR HANDLING
    if($uname == "" || $p == ""){
        echo 0; //echo 'Log In failed, form field(s) are empty';
        exit();
    } else {
    // END FORM DATA ERROR HANDLING
        $sql = "SELECT id, password,unique_field,email,verified FROM users WHERE username='$uname' or  email='$uname' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        if(!$query){
            echo 'Database Tables not created yet!';
            mysqli_close($db_conx);
            exit();
        }
        $row = mysqli_fetch_row($query);
        if(mysqli_num_rows($query) < 1){
            echo 2;//echo "Log In failed. Invalid Username / Password";
            exit();
        }else{      
            $db_id = $row[0];
            $db_pass_str = $row[1];
            $unique =$row[2];
            $email =$row[3];
            $verified =$row[4];
            $sql = "SELECT randA,randB,pin,security_level FROM random_string WHERE user_id='$db_id' LIMIT 1";
            $query = mysqli_query($db_conx, $sql);
            $row = mysqli_fetch_row($query);
            $randA = $row[0];
            $randB = $row[1];
            $pin = $row[2];
            $security_level = $row[3];
            $p = "$randA$p$randB";
            if($p != $db_pass_str){
                echo 2; //echo "Log In failed. Invalid Username / Password.";
                exit();
            } else {
                if($verified == 0){
                    $subject = "Activate Account";
                    $from = "$site_name <noreply@$site_link>";
                    $body = 'Hello <br/>
                    Confirm your email '.$email.' by clicking the link below<br/><br/>
                    Please <a href="https://'.$site_link.'/acct-verification/?action=verification&encryption='.randStrGen(60).'&un='.$unique.'&e='.$email.'" target="_blanc">click here to activate </a><br/>'
                        . 'if the above link is broken, copy and paste the following on your browser<br/>'
                        . 'https://'.$site_link.'/acct-verification/?action=verification&encryption='.randStrGen(60).'&un='.$unique.'&e='.$email.' <br/>';
                    send_mail($email,$from,$subject,$body);
                    echo 3; 
                    exit();
                }elseif($verified == 2){
                    echo 4; 
                    exit();
                }
                if($security_level == 2){ 
                    $pin = randNumGen(8);
                    $sql = "UPDATE random_string SET pin ='$pin' where user_id='$db_id' LIMIT 1";
                    $query = mysqli_query($db_conx, $sql);
                    $subject = "2FA Authentication Code";
                    $from = "$site_name <auth@$site_link>";
                    $body = 'Hello <br/>
                    Use the one-time pin below to login to your account<br/><br/>
                    PIN: '.$pin.'<br/><br/>Do not disclose this to a third party.';
                    send_mail($email,$from,$subject,$body);
                    $_SESSION["security_level"] = 2;
                    echo 11; //echo "Log In failed. Invalid Username / Password.";
                    exit();
                }
                $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
                // CREATE THEIR SESSIONS AND COOKIES            
                $_SESSION[$site_cokie] = $unique;
                setcookie($site_cokie, $unique, time()+15000, "/", "", "", TRUE);
                if($_SESSION["rememberL"] == TRUE){
                   setcookie($site_cokie, $unique, time()+57600, "/", "", "", TRUE); 
                }
                $new_time = date("Y-m-d H:i:s");
                $sql = "UPDATE users SET ip='$ip', lastlogin='$new_time' WHERE unique_field='$unique' LIMIT 1";
                $query = mysqli_query($db_conx, $sql);
                echo 1;
                mysqli_close($db_conx);                
                exit();
                header("location: ../dashboard");
            }
        }
    }
}
/////////// script to  login members afer verifying their pin //////////////
if(isset($_GET['login_pin']) && isset($_POST['code']) && isset($_POST['username_email'])){   
    $uname = mysqli_real_escape_string($db_conx, $_POST['username_email']);
    $p = $_POST['code'];
    // FORM DATA ERROR HANDLING
    if($uname == "" || $p == ""){
        echo '0'; //echo 'Log In failed, form field(s) are empty';
        exit();
    } else {
    // END FORM DATA ERROR HANDLING
        $sql = "SELECT id, password,unique_field,email,verified FROM users WHERE username='$uname' or  email='$uname' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        $row = mysqli_fetch_row($query);
        if(mysqli_num_rows($query) < 1){
            echo '2';//echo "Log In failed. Invalid Username / Password";
            exit();
        }else{      
            $db_id = $row[0];
            $db_pass_str = $row[1];
            $unique =$row[2];
            $email =$row[3];
            $verified =$row[4];
            $sql = "SELECT pin FROM random_string WHERE user_id='$db_id' LIMIT 1";
            $query = mysqli_query($db_conx, $sql);
            $row = mysqli_fetch_row($query);
            $db_pin = $row[0];
            if($p != $db_pin){
                echo '2'; //echo "Log In failed. Invalid Username / Password.";
                exit();
            }else{
                $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
                // CREATE THEIR SESSIONS AND COOKIES   
                $_SESSION["security_level"] = '';
                $_SESSION[$site_cokie] = $unique;
                setcookie($site_cokie, $unique, time()+15000, "/", "", "", TRUE);
                if($_SESSION["rememberL"] == TRUE){
                    setcookie($site_cokie, $unique, time()+57600, "/", "", "", TRUE); 
                }
                $new_time = date("Y-m-d H:i:s");
                $sql = "UPDATE users SET ip='$ip', lastlogin='$new_time' WHERE unique_field='$unique' LIMIT 1";
                $query = mysqli_query($db_conx, $sql);
                mysqli_close($db_conx);
                echo '1';
                exit();
                header("location: ../dashboard");
            }
        }
    }
}
// If user is logged in, header them away
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
$login = '<!--begin::Form-->
            <form class="form w-100" novalidate="novalidate"  name="loginform" id="loginform" onsubmit="return false;">

                <!--end::Separator-->
                <!--begin::Input group=-->
                <div class="form-group text-left">
                    <!--begin::Email-->
                    <label>Email</label>
                    <input type="text" name="Uemail" id="Uemail" class="form-control bg-transparent" 
                           placeholder="Username or Email" required="">
                    <!--end::Email-->
                </div>
                <!--end::Input group=-->
                <div class="form-group text-left">
                    <!--begin::Password-->
                    <label>Password</label>
                    <input type="password" placeholder="Password" name="password" id="password" autocomplete="off" 
                           class="form-control bg-transparent" required="" />
                    <!--end::Password-->
                </div>
                <!--end::Input group=-->
                <!--end::Input group=-->
                <div class="form-group text-left">
                    <!--begin::Password-->
                    <input id="rememberL" name="rememberL" style="" type="checkbox" value="forever" /> 
                    <label for="rememberL">Remember me</label>
                    <a href="../reset-password/" class="link-primary pull-right">Forgot Password ?</a>
                    <!--end::Password-->
                </div>
                <!--begin::Wrapper-->
                <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold ">
                    <div id="msg" style=" width: 100%"></div>
                </div>
                <!--end::Wrapper-->
                <!--begin::Submit button-->
                <div class="form-group text-left">
                    <button type="submit" name="btn_login"  id="btn-login" onclick="//Login()"  class="btn ripple btn-main-primary btn-block">
                        Sign In 
                    </button>
                </div>
                <!--end::Submit button-->
                <!--begin::Sign up-->
                <div class="text-gray-500 text-center fw-semibold fs-6">
                    Not a Member yet? <a href="../register/" class="link-primary">Sign up</a>
                </div>
                <!--end::Sign up-->
            </form>
            <!--end::Form-->';
if(isset($_GET['security-clearance']) && ''!=$_GET['security-clearance'] && isset($_SESSION["security_level"])
        && $_SESSION["security_level"] == 2){
    $e =  mysqli_real_escape_string($db_conx, $_GET['security-clearance']);
    $sql = "SELECT email FROM users WHERE username='$e' or  email='$e' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    if(mysqli_num_rows($query) < 1){
        header("location: ../login");
        exit();	
    }
    $row = mysqli_fetch_row($query);        
    $e = $row[0];
    $login = '<!--begin::Form-->
                <form class="form w-100" novalidate="novalidate"  name="loginform" id="loginform" onsubmit="return false;">
                    <p class="text-info text-center"> 
                        Authentication code has been sent to your email address on file. 
                        Delivery may take a maximum of 15 minutes, please do check 
                        your inbox and spam folders.
                    </p>
                    <!--end::Separator-->
                    <!--begin::Input group=-->
                    <div class="form-group text-left">
                        <!--begin::Email-->
                        <label>Email</label>
                        <input name="username_email" id="username_email" readonly value="'.$e.'" type="email"
                        class="form-control bg-transparent" placeholder="Email"  required="" />
                        <!--end::Email-->
                    </div>
                    <!--end::Input group=-->
                    <div  class="form-group text-left">
                        <!--begin::Password-->
                        <label>Code</label>
                        <input placeholder="2FA code" name="code" id="code" type="text" autocomplete="off" 
                               class="form-control bg-transparent" required="" />
                        <!--end::Password-->
                    </div>
                    <!--end::Input group=-->
                    <!--begin::Wrapper-->
                    <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold ">
                        <div id="msg" style=" width: 100%"></div>
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Submit button-->
                    <div class="form-group text-left">
                        <button type="submit" name="btn_login2"  id="btn-login2" onclick="//Login2()" class="btn ripple btn-main-primary btn-block">
                            Approve Login 
                        </button>
                    </div>
                    <!--end::Submit button-->
                </form>
                <!--end::Form-->';
}
?>
<?php 
    $title ='Login to your Account | '.$site_name;
    $keyword ='Login, sign in,logon,sign up in at '.$site_name.', '.$site_link.',';
    $discription = 'Login here to access your account, '; 
    $home = '';
    $about = '';
    $services = '';
    $acct = '';
    $pricing = '';
    $faq = '';
    $contact = '';
    include_once '../auth_temp_header.php'; 
?>
        
		<!-- Page -->
		<div class="page main-signin-wrapper">

			<!-- Row -->
			<div class="row signpages text-center">
				<div class="col-md-12">
					<div class="card">
						<div class="row row-sm">
							<div class="col-lg-6 col-xl-5 d-none d-lg-block text-center bg-primary details">
								<div class="mt-5 pt-4 p-2 pos-absolute">
                                                                    <a href="../">
                                                                        <img src="../assets/img/logo/logo_2.png?v=<?php echo $ver;?>" class="header-brand-img mb-4" alt="<?php echo $site_name;?>" 
                                                                             style="padding-left: 40px">
                                                                    </a>
									
									<div class="clearfix"></div>
									<img src="../assets_inside/img/svgs/user.svg" class="ht-100 mb-0" alt="user"  style="padding-left: 40px">
									<h5 class="mt-4 text-white" style="padding-left: 40px">Signin To Your Account</h5>
									<!-- <span class="tx-white-6 tx-13 mb-5 mt-xl-0">Welcome Back, login to your account and enjoy </span> -->
								</div>
							</div>
							<div class="col-lg-6 col-xl-7 col-xs-12 col-sm-12 login_form ">
								<div class="container-fluid">
									<div class="row row-sm">
										<div class="card-body mt-2 mb-2">
											<img src="../assets_inside/img/brand/logo.png" class=" d-lg-none header-brand-img text-left float-left mb-4" alt="<?php echo $site_name;?>">
											<div class="clearfix"></div>
                                             <!--begin::Separator-->
                                            <div class="separator separator-content my-14">
                                                <span class="w-125px text-gray-500 fw-semibold fs-7">
                                                    <div id="google_translate_element"></div>
                                                </span>
                                            </div>
                                            
                                            <?php echo $login; ?>

										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End Row -->

		</div>
		<!-- End Page -->

        <?php include_once '../auth_temp_footer.php'; ?>  
        </body>
</html>