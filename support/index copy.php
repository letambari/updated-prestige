<?php
include_once("../controller/dependent.php");
//////////////////////////////////////////////////////////////////
//////// Script to post Contact us requests/questions////////////
/////////////////////////////////////////////////////////////////
if(isset($_POST['subject']) && isset($_POST['message'])){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $n = $f_n;
    $e = mysqli_real_escape_string($db_conx, $_POST['con_email']);
    $s = preg_replace('#[^a-z 0-9]#i', '', ucwords($_POST['subject']));
    $m = mysqli_real_escape_string($db_conx, $_POST['message']);
    if($e == "" || $m == ""|| $s == ""){
        echo 2;
        exit();
    }else {  
        $sql = "INSERT INTO message (f_name,email,subject, message, created_at)VALUES('$n','$e','$s','$m',now())";
        $query = mysqli_query($db_conx, $sql);
        if($query){
            $subject = $s;
            $from = $e;
            $to = "support@$site_link";
            $body = 'E-mail : '.$e.',<br/>
                Message: '.$m;
            send_mail($to,$from,$subject,$body);
            echo 1;
            mysqli_close($db_conx);
            exit();
        }  else {
            echo 3;
            mysqli_close($db_conx);
            exit();
        }
    }
}
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
    $title ='Support | '.$site_name;
    $title2 ='Support';
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
    $acct_settings = ''; 
    $acct_security = '';
    $suppt = 'active';    
    include_once '../user_header.php'; 
?>
<!--begin::Content-->
<div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
        <!--begin::Contact-->
        <div class="card">
            <!--begin::Body-->
            <div class="card-body p-lg-17">
                <!--begin::Row-->
                <div class="row mb-3">
                    <!--begin::Col-->
                    <div class="col-md-6 pe-lg-10">
                        <!--begin::Form-->
                        <form action="#" class="form mb-15" method="post" onsubmit="return false"  id="support_form">
                            <h1 class="fw-bold text-dark mb-9">Send Us Email</h1>
                            <!--begin::Input group-->
                            <div class="row mb-5">
                                <!--begin::Col-->
                                <div class="col-md-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="fs-5 fw-semibold mb-2">Name</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input name="name" id="name" placeholder="Your name" value="<?php echo $f_n;?>" type="text" 
                                        class="form-control form-control-solid" readonly=""    required  />
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-md-6 fv-row">
                                    <!--end::Label-->
                                    <label class="fs-5 fw-semibold mb-2">Email</label>
                                    <!--end::Label-->
                                    <!--end::Input-->
                                    <input name="con_email" id="con_email" placeholder="Your email" value="<?php echo $email;?>" 
                                        type="email" class="form-control form-control-solid"  readonly=""  required  />
                                    <!--end::Input-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="d-flex flex-column mb-5 fv-row">
                                <!--begin::Label-->
                                <label class="fs-5 fw-semibold mb-2">Subject</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input class="form-control form-control-solid" placeholder="" name="subject" id="subject" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="d-flex flex-column mb-10 fv-row">
                                <label class="fs-6 fw-semibold mb-2">Message</label>
                                <textarea class="form-control form-control-solid" rows="6" id="message" name="message" placeholder=""></textarea>
                            </div>
                            <div class="d-flex flex-column mb-10 fv-row">
                                <div class="" id="support_status"></div>
                            </div>
                            <!--end::Input group-->
                            <button type="submit" class="btn btn-primary" onclick="send_message(0)" id="support_btn">
                                &nbsp; Submit &nbsp;
                            </button>
                            <button type="reset" class="btn btn-light-primary mr-1">
                                 Cancel
                            </button>
                            <!--end::Submit-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-md-6 ps-lg-10">
                        <!--begin::Map-->
                        <div class="w-100 rounded mb-2 mb-lg-0 mt-2" style="height: 486px;">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2482.988028964498!2d-0.10249488543046174!3d51.5134356180847!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x487604ac99427907%3A0x6079a4f5bf124867!2s4%20St.%20Paul&#39;s%20Churchyard%2C%20London%20EC4M%208AY%2C%20UK!5e0!3m2!1sen!2sca!4v1662101642451!5m2!1sen!2sca"  style="border:0; height: 100%; width: 100%" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                        <!--end::Map-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
                <!--begin::Row-->
                <div class="row g-5 mb-5 mb-lg-15">
                    <!--begin::Col-->
                    <div class="col-sm-6 pe-lg-10">
                        <!--begin::Phone-->
                        <div class="text-center bg-light card-rounded d-flex flex-column justify-content-center p-10 h-100">
                            <!--begin::Icon-->
                            <!--SVG file not found: icons/duotune/finance/fin006.svgPhone.svg-->
                            <!--end::Icon-->
                            <!--begin::Subtitle-->
                            <h1 class="text-dark fw-bold my-5">Let’s Speak</h1>
                            <!--end::Subtitle-->
                            <!--begin::Number-->
                            <div class="text-gray-700 fw-semibold fs-2">
                                <a href="tel:<?php echo $site_phone;?>"><?php echo $site_phone;?></a>
                            </div>
                            <!--end::Number-->
                        </div>
                        <!--end::Phone-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-sm-6 ps-lg-10">
                        <!--begin::Address-->
                        <div class="text-center bg-light card-rounded d-flex flex-column justify-content-center p-10 h-100">
                            <!--begin::Icon-->
                            <!--begin::Svg Icon | path: icons/duotune/general/gen018.svg-->
                            <span class="svg-icon svg-icon-3tx svg-icon-primary">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        opacity="0.3"
                                        d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z"
                                        fill="currentColor"
                                    />
                                    <path
                                        d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z"
                                        fill="currentColor"
                                    />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <!--end::Icon-->
                            <!--begin::Subtitle-->
                            <h1 class="text-dark fw-bold my-5">Our Head Office</h1>
                            <!--end::Subtitle-->
                            <!--begin::Description-->
                            <div class="text-gray-700 fs-3 fw-semibold">
                                <?php echo $site_address;?>
                            </div>
                            <!--end::Description-->
                        </div>
                        <!--end::Address-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
                <!--begin::Card-->
                <div class="card mb-4 bg-light text-center">
                    <!--begin::Body-->
                    <div class="card-body py-12" style=" font-size: 16px">
                        <!--begin::Icon-->
                        <!--begin::Icon-->
                        <a href="mailto:<?php echo $site_support;?>">
                            <i class=" fa fa-envelope"></i> <?php echo $site_support;?>
                        </a>
                        <!--end::Icon-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Contact-->
    </div>
    <!--end::Content container-->
</div>
<!--end::Content-->


<?php include_once '../user_footer.php'; ?>
</body>

</html>