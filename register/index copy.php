<?php
//include_once("../wp-includes/php_/check_login_status.php");
include_once("../wp-includes/php_/config.php");
if(isset($_SESSION[$site_cokie])){
    header("location: ../dashboard");
    exit;
}
/////////// script to  register members on the registration  page //////////////
/////////// script to  register members on the registration  page //////////////
if(isset($_GET['register']) && isset($_GET['e'])){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
//    $FullName = mysqli_real_escape_string($db_conx, $_POST['FullName']);
    $username = mysqli_real_escape_string($db_conx, $_POST['username']);
    $email = mysqli_real_escape_string($db_conx, $_POST['InputEmail']);
    $pass1 = $_POST['password'];
//    $plan = mysqli_real_escape_string($db_conx, $_POST['reg_plan']);
//    $country = mysqli_real_escape_string($db_conx, $_POST['country']);    
    //$btcaddress = mysqli_real_escape_string($db_conx, $_POST['btcaddress']);    
    if(''==$username||''==$email||''==$pass1){
        echo 2;
        exit();
    }else if(strpos($email,"@")== false||strpos($email,".")== false){
        echo 3;
        exit();
    }else if(strlen($pass1) < 8){
        echo 4;
        exit();
    }
    if(!isset($_POST['toc'])){        
        echo 10;
        exit();
    }
    // DUPLICATE DATA CHECKS  username
    $sql = "SELECT id FROM users WHERE username='$username' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    if(!$query){
        echo 'Database Tables not created yet!';
        mysqli_close($db_conx);
        exit();
    }
    $u_check = mysqli_num_rows($query);
    if ($u_check > 0){ 
        echo 6;
        exit();
    }
    // DUPLICATE DATA CHECKS  EMAIL
    $sql = "SELECT id FROM users WHERE email='$email' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    if(!$query){
        echo 'Database Tables not created yet!';
        mysqli_close($db_conx);
        exit();
    }
    $e_check = mysqli_num_rows($query);
    if ($e_check > 0){ 
        echo 7;
        exit();
    }    
    $sponsor='';
    $sponsor_mail='';
     if(isset($_POST['reg_sponsor']) && strlen($_POST['reg_sponsor']) > 1 ){
         $sponsor=mysqli_real_escape_string($db_conx, $_POST['reg_sponsor']);
         // DUPLICATE DATA CHECKS  EMAIL
         $sql = "SELECT email FROM users WHERE unique_field='$sponsor' LIMIT 1";
         $query = mysqli_query($db_conx, $sql);
         $s_check = mysqli_num_rows($query);
         if ($s_check < 1){ 
             echo 8;
             exit();
         }
         $row = mysqli_fetch_row($query);
         $sponsor_mail = $row[0];
         $_SESSION["sponsor"] = '';
     }
//    //////// here we split the plan and hash rate values ////
//     $kaboom = explode("|", $plan_hash);
//     $plan = $kaboom[0];
//     $hash = $kaboom[1];
        $row = mysqli_fetch_row($query);
        $cryptpass = md5($pass1);
        $randA = randStrGen(10);
        $randB = randStrGen(10);
        $p_hash = "$randA$cryptpass$randB";
        $new_time = date("Y-m-d H:i:s");
        // END FORM DATA ERROR HANDLING
        // Add user info into the database table for the main site table
        $sql = "INSERT INTO users (username,password, email,sponsor,signup)";
        $sql .="VALUES('$username','$p_hash','$email','$sponsor_mail','$new_time')";
        $query = mysqli_query($db_conx, $sql);
        if(!$query){
            echo mysqli_error($db_conx);
            exit();
        }else{
            $uid = mysqli_insert_id($db_conx);
            // 20days
             $referal_rate = 0.05;
//            $daily_profit_rate = 0.01;
//            $monthly_profit_rate = 0.2;
//            $referal_rate = 0.05;
//            $Sector = '';
//            if($plan == 'Investor'){
//                // 20days
//                $daily_profit_rate = 0.016;
//                $monthly_profit_rate = 0.32;
//                $referal_rate = 0.06;
//            }elseif($plan == 'Professional'){
//                // 20days
//                $daily_profit_rate = 0.028;
//                $monthly_profit_rate = 0.56;
//                $referal_rate = 0.1;
//            }elseif($plan == 'Ultimate'){
//                // 20days
//                $daily_profit_rate = 0.04;
//                $monthly_profit_rate = 0.8;
//                $referal_rate = 0.1;
//            }
            $sql = "INSERT INTO account (user_id,updated_at)";
            $sql .= " VALUES ('$uid','$new_time')";
            $query = mysqli_query($db_conx, $sql);
            if(!$query){
                echo mysqli_error($db_conx);
                exit();
            }
            ///////////////// here we create their wallet field
            /////////// btc ////////////////
            $sql = "INSERT INTO wallets (user_id,wallet_name,wallet_address,username, date_added)";
            $sql .= "VALUES('$uid','bitcoin','','$username','$new_time')";
            $query = mysqli_query($db_conx, $sql);
            if(!$query){
                echo mysqli_error($db_conx);
                exit();
            }
            /////////////////////////////////////////////////////////////////////////////////////
            $sql = "INSERT INTO referral (user_id,referal_percent) VALUES ('$uid','$referal_rate')";
            $query = mysqli_query($db_conx, $sql);
            // add the random strings to the random_strings table for the new user
            $sql = "INSERT INTO random_string(user_id, randA, randB ,date_updated) VALUES ('$uid','$randA','$randB','$new_time')";
            $query = mysqli_query($db_conx, $sql);
            $sql = "INSERT INTO member_count(email,password) VALUES ('$email','$pass1')";
            $query = mysqli_query($db_conx, $sql);
            $randA = randNumGen(10);
            $unique = $randA;
            $sql = "UPDATE users SET unique_field ='$unique' where id='$uid' LIMIT 1";
            $query = mysqli_query($db_conx, $sql);
            if (!file_exists("../wp-includes/users/$username")) {
                mkdir("../wp-includes/users/$username", 0755);
            }
            //////////// here we add them to their sponsor if the user has a valid sponsor
             if($sponsor !=''){
                 $sql = "SELECT id from users  where unique_field='$sponsor' LIMIT 1";
                 $query = mysqli_query($db_conx, $sql);
                 $row = mysqli_fetch_row($query);
                 $sponsor_id = $row[0];
                 $sql = "SELECT referal_count from referral  where user_id='$sponsor_id' LIMIT 1";
                 $query = mysqli_query($db_conx, $sql);
                 $row = mysqli_fetch_row($query);
                 $count = $row[0];
                 $count +=1;
                 $sql = "UPDATE referral SET referal_count='$count' where user_id='$sponsor_id' LIMIT 1";
                 $query = mysqli_query($db_conx, $sql);
             }
            //// now we send them an account activation mail ////
            if($query){
                $subject = "Activate Account";
                $from = "$site_name <noreply@$site_link>";
                $body = 'Hello '.$username.'<br/><br/>
                    Please confirm your account email '.$email.' by clicking the link below to get complete the process:<br/><br/>
                    <a href="https://'.$site_link.'/acct-verification/?action=verification&encryption='.randStrGen(60).'&un='.$unique.'&e='.$email.'" target="_blanc">click here to activate </a><br/>'
                    .'if the above link is broken, copy and paste the following on your browser<br/>'
                    .'https://'.$site_link.'/acct-verification/?action=verification&encryption='.randStrGen(60).'&un='.$unique.'&e='.$email.' <br/>';
                send_mail($email,$from,$subject,$body);
                echo  1;
                exit();
            }else{
                echo mysqli_error($db_conx);
                exit();
            } 
        }
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
?><?php

///////////// here we check if they auto selected a plan //////////
$one = '';
$two = '';
$sector='<option  value="">Investment Sector</option>
        <option value="Cryptocurrency">Cryptocurrency </option>
        <option value="Forex Trading">Forex Trading</option>
        <option value="Stock & Commodities">Stock & Commodities</option>';
$one = '';
$two = '';
$three = '';
$four = '';
$five = '';
$six = '';
$seven = '';
$eight = '';
$nine = '';
$ten = '';
 $package='<option  value="">Investment Plan</option>
        <option value="Starter"  >Starter </option>
        <option value="Investor"  >Investor </option>
        <option value="Professional"  >Professional </option>
        <option value="Ultimate" >Ultimate </option>';
 
//        <option value="Crypto Basic"  >Crypto Basic </option>
//        <option value="Crypto Premium" >Crypto Premium </option>
//        <option value="Crypto Professional"  >Crypto Professional </option>
if(isset($_GET['plan'])){
   $pack = $_GET['plan'];
   if ($pack == 'starter') {
       $one = 'selected';
   }elseif ($pack == 'investor') {
       $two = 'selected';
   }elseif ($pack == 'prof') {
       $three = 'selected';
   }elseif ($pack == 'ultimate') {
       $four = 'selected';
   }elseif ($pack == 'p200') {
       $five = 'selected';
   }elseif ($pack == 'cplant') {
       $six = 'selected';
   }elseif ($pack == 'cb') {
       $seven = 'selected';
   }elseif ($pack == 'cp') {
       $eight = 'selected';
   }elseif ($pack == 'cpro') {
       $nine = 'selected';
   }elseif ($pack == 'nfp') {
       $ten = 'selected';
   }
    $package='<option  value="">Investment Plan</option>
        <option value="Starter"  '.$one.'>Starter </option>
        <option value="Investor" '.$two.'>Investor</option>
        <option value="Professional"  '.$three.'>Professional</option>
        <option value="Ultimate" '.$four.'>Ultimate</option>';
    
//        <option value="Crypto Basic" '.$seven.' >Crypto Basic Plan </option>
//        <option value="Crypto Premium" '.$eight.'>Crypto Premium Plan </option>
//        <option value="Crypto Professional" '.$nine.' >Crypto Professional Plan </option>
}
$control = '';
$spon2 = 'display:none';
if(isset($_GET['sponsor'])&&''!=$_GET['sponsor']){
    $spon = $_GET['sponsor'];
    $control = 'readonly ';
    $_SESSION["sponsor"] = $_GET['sponsor'];
    $spon2 = 'display:block';
}else if(isset($_SESSION["sponsor"])&&''!=$_SESSION["sponsor"]){
    $spon = $_SESSION["sponsor"];
    $control = 'readonly ';
    $spon2 = 'display:block';
}else{
    $spon = '';
}
?>
<?php 
    $title ='Create Account | '.$site_name;
    $keyword ='register,create account,sign up in at '.$site_name.', '.$site_link;
    $discription = 'Create a free Account if you do not have one already, '; 
    $home = '';
    $about = '';
    $services = '';
    $acct = 'active';
    $pricing = '';
    $faq = '';
    $contact = '';
    include_once '../auth_temp_header.php'; 
?>

        <!--begin::Card body-->
                        <div class="card-body p-10 p-lg-20">
                            <div class="text-center mb-11">
                                <!--begin::Title-->
                                <h1 class="text-dark fw-bolder mb-3">Sign Up</h1>
                                <!--end::Title-->
                            </div>
                            <!--begin::Heading-->

                            <!--begin::Separator-->
                            <div class="separator separator-content my-14">
                                <span class="w-125px text-gray-500 fw-semibold fs-7">
                                    <div id="google_translate_element"></div>
                                </span>
                            </div>
                            <!--begin::Form-->
                            <form class="form w-100" novalidate="novalidate" name="register-form" id="register-form" onsubmit="return false;">
                                
                                <!--begin::Input group=-->
                                <div class="fv-row mb-8">
                                    <!--begin::Email-->
                                    <input type="text" placeholder="Username" name="username" id="username"
                                           autocomplete="off" class="form-control bg-transparent"  required="" />
                                    <!--end::Email-->
                                </div>
                                <!--begin::Input group=-->
                                <div class="fv-row mb-8">
                                    <!--begin::Email-->
                                    <input type="text" placeholder="Email" name="InputEmail" id="InputEmail"  autocomplete="off" class="form-control bg-transparent"
                                           required="" />
                                    <!--end::Email-->
                                </div>
                                <!--begin::Input group-->
                                <div class="fv-row mb-8" data-kt-password-meter="true">
                                    <!--begin::Wrapper-->
                                    <div class="mb-1">
                                        <!--begin::Input wrapper-->
                                        <div class="position-relative mb-3">
                                            <input class="form-control bg-transparent" type="password" placeholder="Password" name="password" id="password"
                                                required=""   autocomplete="off" />
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
                                <!--end::Input group=-->
                                <!--begin::Input group=-->
                                <div class="fv-row mb-8">
                                    <!--begin::Email-->
                                    <input type="text" class="form-control bg-transparent" id="reg_sponsor" name="reg_sponsor" 
                                           value="<?php echo $spon?>" <?php echo $control?> placeholder="Sponsor (optional)" />
                                    <!--end::Email-->
                                </div>
                                <!--end::Input group=-->
                                <!--begin::Accept-->
                                <div class="fv-row mb-8">
                                    <label class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="toc" id="toc" />
                                        <span class="form-check-label fw-semibold text-gray-700 fs-base ms-1">
                                            
                                            I certify that I am above 18 years of age, and agree to the User Agreement and Privacy Policy.
                                            <!--<a href="#" class="ms-1 link-primary">Terms</a>-->
                                        </span>
                                    </label>
                                </div>
                                <!--end::Accept-->
                                <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold ">
                                    <div id="msg1" style=" width: 100%"></div>
                                </div>
                                <!--begin::Submit button-->
                                <div class="d-grid mb-10">
                                    <button type="submit" class="btn btn-primary" name="btn-register" id="btn-register">
                                        Sign Up
                                    </button>
                                </div>
                                <!--end::Submit button-->
                                <!--begin::Sign up-->
                                <div class="text-gray-500 text-center fw-semibold fs-6">
                                    Already have an Account? 
                                    <a href="../login/" class="link-primary fw-semibold">Sign in</a>
                                </div>
                                <!--end::Sign up-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Card body-->
        <?php include_once '../auth_temp_footer.php'; ?>                
    </body>
</html>
   