<?php
include_once("../controller/dependent.php");
if(isset($_SESSION[$site_cokie])){
    header("location: ../support");
    exit;
}
//////////////////////////////////////////////////////////////////
//////// Script to post Contact us requests/questions////////////
/////////////////////////////////////////////////////////////////
if(isset($_POST['form_email']) && isset($_GET['contact_page_form'])){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $e = mysqli_real_escape_string($db_conx, $_POST['form_email']);
    $n = mysqli_real_escape_string($db_conx, $_POST['form_name']);
    $m = mysqli_real_escape_string($db_conx, $_POST['form_message']);
    $s = mysqli_real_escape_string($db_conx, $_POST['form_subject']);
//    $p = mysqli_real_escape_string($db_conx, $_POST['form_phone']);
    $p = 'nil';
    if($e == "" || $m == ""|| $n == ""|| $p == ""|| $s == ""){
        echo 2;
        exit();
    }else {  
        $new_time = date("Y-m-d H:i:s");
        $sql = "INSERT INTO message (email,f_name, subject,phone, message, created_at)VALUES('$e','$n','$s','$p','$m','$new_time')";
        $query = mysqli_query($db_conx, $sql);
        if($query){
            $subject = $s;
            $from = $e;
            $to = "support@$site_link";
            $body = 'E-mail : '.$e.',<br/>
                Name : '.$n.',<br/>Phone : '.$p.',<br/>
                Subject : '.$s.',<br/>
                Message: '.$m;
            send_mail($to,$from,$subject,$body);
            echo 1;
            mysqli_close($db_conx);
            exit();
        }  else {
            echo mysqli_error($db_conx);
            mysqli_close($db_conx);
            exit();
        }
    }
}
?>
<?php 
    $title ='Contact Us | '.$site_name;
    $keyword ='Contact us,Contact '.$site_name.', Contact '.$site_link;
    $discription = 'Our Customer Care representatives are available 24/7. Send us email on contact@'.$site_link; 
    $home = '';
    $about = '';
    $services = '';
    $acct = '';
    $pricing = '';
    $faq = '';
    $contact = 'active';
    include_once '../temp_header.php'; 
?>
    <!-- start header  -->
    <!-- Breadcrumb -->
    <section class="breadcrumb_main">
        <div class="container">
            <div class="content text-center">
                <span class="sub_title">Contact Us</span>
                <h1 class="title">Get In <span>Touch</span></h1>
            </div>
        </div>
    </section>
    <!-- Breadcrumb -->

    <!-- Contact Box -->
    <section class="contact_box">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="single_item wow fadeInLeft" data-wow-delay="1s">
                        <div class="icon"><img src="assets/img/contact1.svg" alt=""></div>
                        <h5>Location</h5>
                        <p><?php echo $site_address; ?></p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single_item wow fadeInLeft" data-wow-delay="1.2s">
                        <div class="icon"><img src="assets/img/contact2.svg" alt=""></div>
                        <h5>Phone Contact</h5>
                        <a href="tel:<?php echo $site_phone; ?>"><?php echo $site_phone; ?></a>
                        
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single_item wow fadeInLeft" data-wow-delay="1.4s">
                        <div class="icon"><img src="assets/img/contact3.svg" alt=""></div>
                        <h5>Email Contact</h5>
                        <a href="mailto:<?php echo $site_support; ?>"><?php echo $site_support; ?> </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="shape_items">
            <img src="assets/img/cons1.png" alt="" class="one">
            <img src="assets/img/cons2.png" alt="" class="two">
            <img src="assets/img/cons3.png" alt="" class="three">
        </div>
    </section>
    <!-- Contact Box -->

    <!-- Contact Form -->
    <section class="contact_form" data-bg-color="#FCFCFE">
        <div class="container">
            <div class="section_title innar_page text-center wow fadeInLeft">
                <h2>Let's discuss your projects</h2>
            </div>
            <form  name="contact_page_form" id="contact_page_form" onsubmit="return false;" class="main_form">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form_box">
                            <h6>Full Name</h6>
                            <input class="form-control" id="form_name" name="form_name" type="text" placeholder="First Name">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form_box">
                            <h6>E-mail</h6>
                            <input id="form_email" name="form_email" class="form-control" type="email" placeholder="Mail">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form_box">
                            <h6>Subject</h6>
                            <input id="form_subject" name="form_subject" class="form-control" type="text" placeholder="Title">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form_box">
                            <h6>Message</h6>
                            <textarea id="form_message"  name="form_message" class="form-control"  placeholder="Type your message"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-12 mt-4">
                        <div class="form_box">
                            <div id="contact_status" style=" width: 100%"></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="submit_note">
                            <span>*We won’t spam or publish your email</span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="submit_area">
                            <button class="submit_btn" name="contact_btn"  id="contact_btn" onclick="send_contact()">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="shape_items">
            <img src="../assets/img/cfs1.png" alt="" class="one">
            <img src="../assets/img/cfs2.png" alt="" class="two">
        </div>
    </section>
    <!-- Contact Form -->


    <!-- Footer -->
<?php include_once '../temp_footer.php'; ?>
</body>

</html>