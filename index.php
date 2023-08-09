<?php
include_once("wp-includes/php_/config.php");
if(isset($_SESSION[$site_cokie])){
    header("location: dashboard");
    exit;
}
///////////// script to  dynamically create tables //////////////
$sql = "SELECT 1 FROM users  LIMIT 1";
$query = mysqli_query($db_conx, $sql);
if($query == FALSE){
    include_once("wp-includes/php_/make_my_tables.php");
    $new_time = date("Y-m-d H:i:s");
    /////////// btc ////////////////
    $sql = "INSERT INTO wallets (user_id,wallet_symbol,wallet_name,wallet_address,wallet_type, date_added)";
    $sql .= "VALUES('1','btc','bitcoin','1JjvmESE44AAk2Zjcjmss9eCXbr54bb59r','Admin','$new_time')";
    $query = mysqli_query($db_conx, $sql);
    /////////// usdt(trc20) ////////////////
    $sql = "INSERT INTO wallets (user_id,wallet_symbol,wallet_name,wallet_address,wallet_type, date_added)";
    $sql .= "VALUES('1','usdt','usdt(trc20)','1JjvmESE44AAk2Zjcjmss9eCXbr54bb59r','Admin','$new_time')";
    $query = mysqli_query($db_conx, $sql);
    /////////// bch ////////////////
    $sql = "INSERT INTO wallets (user_id,wallet_symbol,wallet_name,wallet_address,wallet_type, date_added)";
    $sql .= "VALUES('1','bch','bitcoin cash','qzyh64k7q0rzyxlru8d9mry609ulrjt7855v44q203','Admin','$new_time')";
    $query = mysqli_query($db_conx, $sql);
    /////////// ethe ////////////////
    $sql = "INSERT INTO wallets (user_id,wallet_symbol,wallet_name,wallet_address,wallet_type, date_added)";
    $sql .= "VALUES('1','eth','ethereum','0xcb226c3ed0307812D657210c1332e04a9Db3555d','Admin','$new_time')";
    $query = mysqli_query($db_conx, $sql);
    /////////// usdt(erc20) ////////////////
    $sql = "INSERT INTO wallets (user_id,wallet_symbol,wallet_name,wallet_address,wallet_type, date_added)";
    $sql .= "VALUES('1','usdt','usdt(erc20)','1JjvmESE44AAk2Zjcjmss9eCXbr54bb59r','Admin','$new_time')";
    $query = mysqli_query($db_conx, $sql);
    /////////// ltc ////////////////
    $sql = "INSERT INTO wallets (user_id,wallet_symbol,wallet_name,wallet_address,wallet_type, date_added)";
    $sql .= "VALUES('1','ltc','litecoin','MAQxZsrdGSYTq6zVVP2zy7of1z1yj9CohA','Admin','$new_time')";
    $query = mysqli_query($db_conx, $sql);
    /////////// doge ////////////////
    $sql = "INSERT INTO wallets (user_id,wallet_symbol,wallet_name,wallet_address,wallet_type, date_added)";
    $sql .= "VALUES('1','doge','doge','qzyh64k7q0rzyxlru8d9mry609ulrjt7855v44q203','Admin','$new_time')";
    $query = mysqli_query($db_conx, $sql);
    /////////// bnb ////////////////
    $sql = "INSERT INTO wallets (user_id,wallet_symbol,wallet_name,wallet_address,wallet_type, date_added)";
    $sql .= "VALUES('1','bnb','binance coin','0xcb226c3ed0307812D657210c1332e04a9Db3555d','Admin','$new_time')";
    $query = mysqli_query($db_conx, $sql);
    /////////// xrp ////////////////
    $sql = "INSERT INTO wallets (user_id,wallet_symbol,wallet_name,wallet_address,wallet_type, date_added)";
    $sql .= "VALUES('1','xrp','ripple','MAQxZsrdGSYTq6zVVP2zy7of1z1yj9CohA','Admin','$new_time')";
    $query = mysqli_query($db_conx, $sql);
    /////////// ada ////////////////
    $sql = "INSERT INTO wallets (user_id,wallet_symbol,wallet_name,wallet_address,wallet_type, date_added)";
    $sql .= "VALUES('1','ada','ada','MAQxZsrdGSYTq6zVVP2zy7of1z1yj9CohA','Admin','$new_time')";
    $query = mysqli_query($db_conx, $sql);
    ///////// admin update///////
    $sql = "INSERT INTO update_control (auto)VALUES('1')";
    $query = mysqli_query($db_conx, $sql);
    ////////////////////
    $sql = "SELECT auto FROM update_control where id='1' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    if(mysqli_num_rows($query) < 1){
        /////////// add the control ////////////////
        $sql = "INSERT INTO update_control (auto)";
        $sql .= "VALUES('1')";
        $query = mysqli_query($db_conx, $sql);
        echo 'success';
    }
}
////////////////////
$sql = "SELECT auto FROM update_control where id='1' LIMIT 1";
$query = mysqli_query($db_conx, $sql);
if(mysqli_num_rows($query) < 1){
    /////////// add the control ////////////////
    $sql = "INSERT INTO update_control (auto)";
    $sql .= "VALUES('1')";
    $query = mysqli_query($db_conx, $sql);
    echo 'success';
}
?>
<?php
include_once("wp-includes/php_/config.php");
if(isset($_SESSION[$site_cokie])){
    header("location: dashboard");
    exit;
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $site_name;?></title>
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Cache-Control" content="no-cache" />
        <meta name="keywords" content="<?php echo $site_name;?>">
        <meta name="author" content="<?php echo $site_name;?>" />
        <meta name="company" content="<?php echo $site_name;?>" />
        <meta name="copyright" content="&copy; 2022 <?php echo $site_name;?>" />
        <meta name="publisher" content="<?php echo $site_name;?>" />
        <meta property="og:title" content=" <?php echo $site_name;?>" />
        <meta property="og:description" content="<?php echo $site_name;?>. Unleash your Financial Potentials: Invest with a Vision" />
        <meta name="Description" content="<?php echo $site_name;?>. Unleash your Financial Potentials: Invest with a Vision" />
        <meta name="keywords" content="<?php echo $site_name;?> " />
        <link rel="icon" type="image/x-icon" href="assets/img/logo/fav.png?v=<?php echo $ver;?>">
        <!-- Bootstrap CSS -->
        <link href="assets/vendors/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/vendors/slick/slick-theme.css" rel="stylesheet">
        <link href="assets/vendors/slick/slick.css" rel="stylesheet">
        <link href="assets/vendors/animation/animate.css" rel="stylesheet">
        <link href="assets/vendors/phosphor/css/icons.css" rel="stylesheet">
        <link href="assets/css/style.css?v=<?php echo $ver;?>" rel="stylesheet" type="text/css"/>        
        <link rel="stylesheet" href="assets/css/responsive.css?v=<?php echo $ver;?>">
        <link href="assets/css/custom.css?v=<?php echo $ver;?>" rel="stylesheet" type="text/css"/>
        
    </head>

    <body data-scroll-animation="true">
        <!-- <div id="preloader">
            <div id="loader"></div>
            <h2>Terra</h2>
        </div> -->
        <!-- start header  -->
        <header class="header" id="header">
            <nav class="navbar navbar-expand-lg menu_four">
                <div class="container-fluid">
                    <a class="navbar-brand sticky_logo" href="">
                        <img src="assets/img/logo/logo_dark.png?v=<?php echo $ver;?>" alt="logo">
                        <img src="assets/img/logo/logo_dark.png?v=<?php echo $ver;?>" alt="logo">
                    </a>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav menu ms-lg-auto me-lg-auto">
                            <li class="nav-item dropdown submenu active">
                                <a class="nav-link dropdown-toggle" href="" role="button" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    Home
                                </a>
                                <!-- <i class="ph-caret-down mobile_dropdown_icon"></i> -->
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="about/">
                                    About
                                </a>
                                <!-- <i class="ph-caret-down mobile_dropdown_icon"></i> -->
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="pricing/">
                                    Plans
                                </a>
                                <!-- <i class="ph-caret-down mobile_dropdown_icon"></i> -->
                            </li>

                            <li class="nav-item"><a href="contact/" class="nav-link">Contact</a></li>
                        </ul>
                    </div>
                    <ul class="list-unstyled alter_nav alter_nav_color_btn">
                        <li><a href="login/">Login</a></li>
                        <li><a href="register/" class="nav_btn btn_hover">Create Account<span></span></a></li>
                    </ul>
                    <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span></span><span></span><span></span><span></span><span></span><span></span>
                    </button>
                </div>
            </nav>
        </header>
        <!-- start header  -->
        <!-- Hero -->
        <section class="hero_financial_transaction apps_craft_animation"
            data-bg-img="assets/img/financial_transactions_bg.png">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-7 col-md-12">
                        <div class="hero_contents wow fadeInLeft">
                            <div class="sub_title">
                                <span>Next generation Payment Ways <img src="assets/img/sub_title.png" alt=""></span>
                            </div>
                            <div class="section_title">
                                <h1 style="font-size: 65px;">Unleash your Financial Potentials: <br> Invest with a Vision</h1>
                                <p>Embrace the power of trading bots, and embark on a new era of <br/>intelligent and efficient 
                                    crypto investing.
                                </p>
                            </div>
                            <div class="buttons">
                                <a href="register/" class="financial_transaction_btn">Get Started</a>
                                <a href="how-it-works/" class="text_button hover_style_btn">How it Works?</a>
                            </div>
                            <div class="details">
                                <div class="item">
                                    <h3>2M</h3>
                                    <span>Registered <br>
                                        users</span>
                                </div>
                                <div class="item">
                                    <h3>100%</h3>
                                    <span>Successful <br>
                                        transactions </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-5 col-md-12 banner-left">
                        <div class="section_img">
                            <img class="min wow fadeInRight" data-wow-delay="0.1s"
                                 src="assets/img/crypto-1.png" alt="">
                            <!-- <img data-parallax='{"x": 0, "y": 50, "rotateZ":0}' class="one wow fadeInDown"
                                data-wow-delay="0.4s" src="assets/img/financial_transactions2.png" alt="">
                            <img data-parallax='{"x": 0, "y": -50, "rotateZ":0}' class="two wow fadeInLeft"
                                data-wow-delay="0.7s" src="assets/img/3d-cryptocurrency-rendering-design-removebg-preview.png" alt=""> -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Hero -->

        <!-- Info Box -->
        <section class="info_box h_4">
            <div class="container">
                <div class="section_title h_4 text-center wow fadeInUp">
                    <h2>The financial management <br>
                        tool you’ll ever need</h2>
                    <p>Our Team of Experts uses a methodology to carefully monitor, evaluate and adjust to market conditions<br> and trends as a way to guide the bots to do its job in the most efficient way.</p>
                </div>
                <div class="min_area">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="single_info_box wow fadeInUp" data-bg-color="#FFFAF1">
                                <div class="icon">
                                    <img src="assets/img/info_box/4_1.svg" alt="">
                                </div>
                                <div class="content">
                                    <h5>Super easy to use</h5>
                                    <p>By leveraging advanced technology, data-driven decision-making, and relentless execution,<br>  trading bots empower you to make the most of <br>
                                    the dynamic crypto landscape and unlock the potential for greater returns.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="single_info_box wow fadeInUp" data-wow-delay="0.2s" data-bg-color="#F8FFF0">
                                <div class="icon">
                                    <img src="assets/img/info_box/4_2.svg" alt="">
                                </div>
                                <div class="content">
                                    <h5>Safe and protected</h5>
                                    <p>Securely trade crypto with confidence using our advanced trading bot. <br> Your assets are protected with robust security measures,
                                    providing you with a worry-free investment experience.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="single_info_box wow fadeInUp" data-wow-delay="0.3s" data-bg-color="#F5FDFF">
                                <div class="icon">
                                    <img src="assets/img/info_box/4_3.svg" alt="">
                                </div>
                                <div class="content">
                                    <h5>Very Fast Payouts Of Profit</h5>
                                    <p>Experience lightning-fast ROI payouts with our investment platform. <br> Get rewarded promptly for your successful investments, ensuring you can enjoy the fruits of your crypto trades without delay.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Info Box -->

        <!-- Integration -->
        <section class="integration_two h_4">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-6">
                        <div class="section_img">
                            <div class="shape"></div>
                            <img class="wow fadeInLeft" src="assets/img/crypto-2.png" alt="">
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="content">
                            <div class="section_title h_4 wow fadeInRight">
                                <h2>Fast Transactions From<br> Different Parts of the <br> World</h2>
                                <p>We embrace the future of finance by accepting cryptocurrency <br>  as a seamless and innovative means of investment.
                                </p>
                            </div>
                             <ul class="data_list list-unstyled">
                                <li class="d-flex align-items-center wow fadeInLeft" data-wow-delay="0.1s"><i
                                        class="ph-check"></i> Transaction in multiple currencies
                                </li>
                                <li class="d-flex align-items-center wow fadeInLeft" data-wow-delay="0.4s"><i
                                        class="ph-check"></i> Customer service round the clock
                                </li>
                                <li class="d-flex align-items-center wow fadeInLeft" data-wow-delay="0.7s"><i
                                        class="ph-check"></i> Two factor authentication supported
                                </li>
                            </ul> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="shape_items">
                <img src="assets/img/inte_shape.png" alt="" class="one">
            </div>
        </section>
        <!-- Integration -->

        <!-- Integration Two -->
        <section class="integration_two h_4 s_2">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="content">
                            <div class="section_title h_4">
                                <h2 class="wow fadeInLeft" data-wow-delay="0.1s">Smart workflow with <br>
                                    powerful integrations <br> easy to use</h2>
                                <p class="wow fadeInLeft" data-wow-delay="0.4s">Navigating our company website is a breeze, 
                                providing a user-friendly experience <br> for seamless exploration and access to valuable investment resources.
                                </p>
                            </div>
                            <!-- <a href="contact.html" class="text_button wow fadeInLeft" data-wow-delay="0.7s"><span>Learn
                                    More</span></a> -->
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="section_img">
                            <div class="shape"></div>
                            <img class="wow fadeInRight" src="assets/img/inte_shape_2.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="shape_items">
                <img src="assets/img/inte_shape_3.png" alt="" class="one_sp">
            </div>
        </section>
        <br><br><br><br>
        <!-- Integration Two -->

        <!-- Payments Flow -->
        <!-- <section class="payments_flow apps_craft_animation">
            <div class="container">
                <div class="section_title h_4 text-center wow fadeInUp">
                    <h2>Manage and control the <br> flow of payments</h2>
                    <p>Our team of experts uses a methodology to identify the credit cards most <br> likely fit
                        your needs percentage rates ullamcorper</p>
                </div>
                <div class="image_box">
                    <div class="shape"></div>
                    <img src="assets/img/p_flow.png" alt="" class="main wow fadeInUp" data-wow-delay="0.1s">
                    <img data-parallax='{"x": 0, "y": 50, "rotateZ":0}' src="assets/img/p_flow_2.png" alt=""
                        class="one wow fadeInLeft" data-wow-delay="0.4s">
                    <img data-parallax='{"x": 0, "y": -50, "rotateZ":0}' src="assets/img/p_flow_3.png" alt=""
                        class="two wow fadeInRight" data-wow-delay="0.7s">
                    <div class="shape_items">
                        <img src="assets/img/p_flow_shape.png" alt="" class="one">
                        <img src="assets/img/p_flow_shape_2.png" alt="" class="two spin">
                        <img src="assets/img/p_flow_shape_3.png" alt="" class="three spin">
                    </div>
                </div>
            </div>
        </section> -->
        <!-- Payments Flow -->

        <!-- Testimonial -->
        <!-- <section class="testimonial text-center" data-bg-img="assets/img/testi_bg.png">
            <div class="container">
                <h2>Our happy customer stories</h2>
                <div class="testimonial_items">
                    <div class="single_testimonial_item">
                        <p>
                            <span>“</span> Fondy has been a great partner for us when it <br> comes to expanding our
                            acquiring capabilities
                            in <br> Eastern Europe, leveling up the region’s acquiring <br> capabilities. Their experience
                            in the market has <br> been invaluable, helping us scale <span>”</span>
                        </p>
                        <div class="autrhor_info">
                            <img src="assets/img/testimonial.png" alt="">
                            <div class="content">
                                <h5>John Alexander</h5>
                                <p>CEO & Founder</p>
                            </div>
                        </div>
                    </div>
                    <div class="single_testimonial_item">
                        <p>
                            <span>“</span> Fondy has been a great partner for us when it <br> comes to expanding our
                            acquiring capabilities
                            in <br> Eastern Europe, leveling up the region’s acquiring <br> capabilities. Their experience
                            in the market has <br> been invaluable, helping us scale <span>”</span>
                        </p>
                        <div class="autrhor_info">
                            <img src="assets/img/testimonial.png" alt="">
                            <div class="content">
                                <h5>John Alexander</h5>
                                <p>CEO & Founder</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section> -->
        <!-- Testimonial -->

        <!-- Faq -->
        <section class="faq" id="faq">
            <div class="container">
                <div class="section_title h_4 text-center wow fadeInUp">
                    <h2>Frequently asked questions</h2>
                    <p>If the FAQ doesn’t answer your question, our live chat is happy to assist</p>
                </div>
                <div class="min_faq_area wow fadeInUp">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="single_faq_item">
                                <img src="assets/img/question.png" alt="">
                                <div class="content">
                                    <h3>How much is the minimum investment required to start with <?php echo $site_name;?>?</h3>
                                    <p>The minimum amount you can start with is $100 and no amount is too big to start with. <br>
                                        However we advice to start with an amount you are comfortable with for a start. 
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="single_faq_item">
                                <img src="assets/img/question.png" alt="">
                                <div class="content">
                                    <h3>Can i withdraw my capital after making my investment?</h3>
                                    <p>You can withdraw your capital at the end of your investment period only. </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="single_faq_item">
                                <img src="assets/img/question.png" alt="">
                                <div class="content">
                                    <h3>Can i withdraw my profit anytime i want? </h3>
                                    <p>Yes you can withdraw your profit however we process all withdrawals on Wednesday. <br>That is to say that if you make a withdrawal on a Friday, it will be paid out on Wednesday, etc. </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="single_faq_item">
                                <img src="assets/img/question.png" alt="">
                                <div class="content">
                                    <h3>Do i get some bonus if i bring someone else onboard? </h3>
                                    <p>Yes we offer different bonuses based on your achievements in regards to the referrals you have. </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="single_faq_item">
                                <img src="assets/img/question.png" alt="">
                                <div class="content">
                                    <h3>What currencies do you accept for investment? </h3>
                                    <p>We accept major cryptocurrencies such as bitcoin, ethereum, usdt, usdc, avax, solana. <br>You can always contact support to assist if you need help on making a deposit. </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="single_faq_item">
                                <img src="assets/img/question.png" alt="">
                                <div class="content">
                                    <h3>How many days does the company trade in a week. ?</h3>
                                    <p>We trade 5 working days (monday - Friday) and run maintenance on Saturday then rest and prepare for the new week on Sunday. </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="single_faq_item">
                                <img src="assets/img/question.png" alt="">
                                <div class="content">
                                    <h3>Can i open an account for my children that are below 18 years? </h3>
                                    <p>Yes you can, its an investment so we advise you open accounts for them for future purposes. However we will not accept dubious attitudes by people trying to make multiple referral bonuses by opening different accounts and exploiting from the system. You can be fined the company notices any form of dubious activities linked to your account. </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="single_faq_item">
                                <img src="assets/img/question.png" alt="">
                                <div class="content">
                                    <h3>Is the company registered? </h3>
                                    <p>Yes we are registered in UK and have a valid certificate of incorporation</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Faq -->

        <!-- Cta -->
        <section class="cta">
            <div class="container">
                <div class="min_cta_area" data-bg-img="assets/img/cta_bg.png">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <div class="content">
                                <h3 class="wow fadeInLeft" data-wow-delay="0.1s">Let’s try our <br> service now!</h3>
                                <p class="wow fadeInLeft" data-wow-delay="0.5s">After much said and done, only actions can yield results. Let’s take a step further to get you onboard</p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="cta_btn wow fadeInRight">
                                <a href="login/" class="btn_hover">Get Started <span></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Cta -->


        <!-- Footer -->
        <section class="footer h_4" data-bg-color="#111828">
            <div class="container">
                <div class="main_footer">
                    <div class="row">
                        <div class="col-xl-4 col-md-6">
                            <div class="site_info">
                                <img src="assets/img/logo/logo_3.png" alt="">
                                <div class="content">
                                    <ul class="info_list list-unstyled">
                                        <li>
                                            <p><?php echo $site_address;?></p>
                                        </li>
                                        <li><a href="mailto:<?php echo $site_support;?>"><?php echo $site_support;?></a></li>
                                        <li><a class="number" href="tel:<?php echo $site_phone;?>"><?php echo $site_phone;?></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6">
                            <div class="site_info posi-f1">
                                <div class="content">
                                    <h5>About Us</h5>
                                    <ul class="info_list list-unstyled">
                                        <li><a href="about">About Us</a></li>
                                        <li><a href="mission">Our Mission</a></li>
                                        <li><a href="history">Our History</a></li>
                                        <li><a href="pricing/">Plan</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6">
                            <div class="site_info posi-f2">
                                <div class="content">
                                    <h5>Workflow</h5>
                                    <ul class="info_list list-unstyled">
                                        <li><a href="contact/">Contact Us</a></li>
                                        <li><a href="#faq">FAQ</a></li>
                                        <li><a href="login/">Login</a></li>
                                        <li><a href="register/">Sign Up</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="footer_bottom">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-6">
                            <p>© <?php echo date('Y');?> <?php echo $site_name;?>. All Rights Reserved</p>
                        </div>
                        <div class="col-lg-6 col-md-6">
<!--                            <div class="social_icons">
                                <a href="https://www.instagram.com/"><i class="ph-instagram-logo"></i></a>
                                <a href="https://dribbble.com/"><i class="ph-dribbble-logo"></i></a>
                                <a href="https://www.behance.net/"><i class="ph-behance-logo"></i></a>
                                <a href="https://www.last.fm/"><i class="ph-spotify-logo"></i></a>
                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Footer -->

        <!-- Optional JavaScript; choose one of the two! -->
        <script src="assets/js/jquery-3.6.0.min.js"></script>
        <!-- Option 1: Bootstrap Bundle with Popper -->
        <script src="assets/js/popper.min.js?v=<?php echo $ver;?>"></script>
        <script src="assets/js/bootstrap.min.js?v=<?php echo $ver;?>"></script>
        <script src="assets/vendors/isotope/imagesloaded.pkgd.min.js"></script>
        <script src="assets/vendors/isotope/isotope.pkgd.min.js"></script>
        <script src="assets/vendors/parallax/jquery.parallax-scroll.js"></script>
        <script src="assets/vendors/parallax/parallax.min.js"></script>
        <script src="assets/vendors/slick/slick.min.js"></script>
        <script src="assets/vendors/wow/wow.min.js"></script>
        <script src="assets/js/custom.js?v=<?php echo $ver;?>"></script>
        <?php echo $live_chat;?>
    </body>


</html>      