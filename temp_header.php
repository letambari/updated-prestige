<?php
$nav = '<ul class="navbar-nav">
            <li class="nav-item">
                <a href="../" class="nav-link dropdown-toggle '.$home.'">Home </a>

            </li>
            <li class="nav-item">
                <a href="../about/" class="nav-link dropdown-toggle '.$about.'"> About</a>
            </li>

            <li class="nav-item">
                <a href="../services/" class="nav-link dropdown-toggle  '.$services.'">Services <i class="bx bx-chevron-down"></i></a>
                <ul class="dropdown-menu">
                    <li class="nav-item">
                        <a href="../services-forex/" class="nav-link">Forex Trading</a>
                    </li>
                    <li class="nav-item">
                        <a href="../services-stocks/" class="nav-link">Stocks & Commodities</a>
                    </li>
                    <li class="nav-item">
                        <a href="../services-crypto/" class="nav-link">Cryptocurrency Investment</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="../pricing/" class="nav-link '.$pricing.'">pricing</a>
            </li>
            <li class="nav-item">
                <a href="../javascript:void" class="nav-link dropdown-toggle '.$faq.'">Strategy <i class="bx bx-chevron-down"></i></a>
                <ul class="dropdown-menu">
                    <li class="nav-item">
                        <a href="../how-it-works/" class="nav-link">How It Works</a>
                    </li>
                    <li class="nav-item">
                        <a href="../non-farm-payroll-nfp/" class="nav-link">NON-FARM PAYROLL (NFP)</a>
                    </li>
                    <li class="nav-item">
                        <a href="../nfp-trading-strategy/" class="nav-link">NFP Trading Strategy</a>
                    </li>
                    <li class="nav-item">
                        <a href="../risks/" class="nav-link">Risks</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="../contact/" class="nav-link  '.$contact.'">Contact</a>
            </li>
            <li class="nav-item">
                <a href="../javascript:void" class="nav-link dropdown-toggle  '.$acct.'">Accounts <i class="bx bx-chevron-down"></i></a>
                <ul class="dropdown-menu">
                    <li class="nav-item">
                        <a href="../login/" class="nav-link">Sign In</a>
                    </li>
                    <li class="nav-item">
                        <a href="../register/" class="nav-link">Register</a>
                    </li>
                </ul>
            </li>
        </ul>';
$nav2 ='<li>
            <a href="../login/" target="" style=" font-size: 14px">
                Login <i class=" fa fa-sign-in"></i>
            </a>
        </li>
        <li>
            <a href="../javascript:void" target="_blank" style=" font-size: 14px">/
            </a>
        </li>
        <li>
            <a href="../register/" target="" style=" font-size: 14px">
                Sign Up <i class=" fa fa-user-plus"></i>
            </a>
        </li>';
$nav3 ='<a class="consultant-btn" href="../register">
            Register
        </a>';
if(isset($_SESSION[$site_cokie])){
    $nav = '<ul class="navbar-nav">
            <li class="nav-item">
                <a href="../dashboard" class="nav-link dropdown-toggle '.$home.'">Home </a>

            </li>
            <li class="nav-item">
                <a href="../about/" class="nav-link dropdown-toggle '.$about.'"> About</a>
            </li>

            <li class="nav-item">
                <a href="../services/" class="nav-link dropdown-toggle  '.$services.'">Services <i class="bx bx-chevron-down"></i></a>
                <ul class="dropdown-menu">
                    <li class="nav-item">
                        <a href="../services-forex/" class="nav-link">Forex Trading</a>
                    </li>
                    <li class="nav-item">
                        <a href="../services-stocks/" class="nav-link">Stocks & Commodities</a>
                    </li>
                    <li class="nav-item">
                        <a href="../services-crypto/" class="nav-link">Cryptocurrency Investment</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="../pricing/" class="nav-link '.$pricing.'">pricing</a>
            </li>
            <li class="nav-item">
                <a href="../javascript:void" class="nav-link dropdown-toggle '.$faq.'">Strategy <i class="bx bx-chevron-down"></i></a>
                <ul class="dropdown-menu">
                    <li class="nav-item">
                        <a href="../how-it-works/" class="nav-link">How It Works</a>
                    </li>
                    <li class="nav-item">
                        <a href="../non-farm-payroll-nfp/" class="nav-link">NON-FARM PAYROLL (NFP)</a>
                    </li>
                    <li class="nav-item">
                        <a href="../nfp-trading-strategy/" class="nav-link">NFP Trading Strategy</a>
                    </li>
                    <li class="nav-item">
                        <a href="../risks/" class="nav-link">Risks</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="../contact/" class="nav-link  '.$contact.'">Contact</a>
            </li>
            <li class="nav-item">
                <a href="../javascript:void" class="nav-link dropdown-toggle  '.$acct.'">Accounts <i class="bx bx-chevron-down"></i></a>
                <ul class="dropdown-menu">
                    <li class="nav-item">
                        <a href="../account-profile/" class="nav-link"><i class="fa fa-user"></i>&nbsp; '.$f_n.'</a>
                    </li>
                    <li class="nav-item">
                        <a href="../logout/" class="nav-link">Log Out</a>
                    </li>
                </ul>
            </li>
        </ul>';
    $nav3 ='<a href="../dashboard" class="btn-style2 small theme">
            <!-- <i class="fa fa-user-circle">Account</i> -->
            <span>Dashboard</span>
        </a>';
    $nav2 ='<li>
            <a href="../account-profile/" target="" style=" font-size: 14px">
                '.$f_n.' <i class=" fa fa-user"></i>
            </a>
        </li>
        <li>
            <a href="../javascript:void" target="_blank" style=" font-size: 14px">/
            </a>
        </li>
        <li>
            <a href="../logout/" target="" style=" font-size: 14px">
                Sign Out <i class=" fa fa-sign-out"></i>
            </a>
        </li>';
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $title;?></title>
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Cache-Control" content="no-cache" />
        <meta name="keywords" content="<?php echo $site_name;?>">
        <meta name="author" content="<?php echo $site_name;?>" />
        <meta name="company" content="<?php echo $site_name;?>" />
        <meta name="copyright" content="&copy; 2022 <?php echo $site_name;?>" />
        <meta name="publisher" content="<?php echo $site_name;?>" />
        <meta property="og:title" content=" <?php echo $site_name;?>" />
        <meta property="og:description" content="<?php echo $site_name;?>. <?php echo $discription;?>" />
        <meta name="Description" content="<?php echo $site_name;?>. <?php echo $discription;?>" />
        <meta name="keywords" content="<?php echo $site_name;?>,<?php echo $keyword;?>" />
        <link rel="icon" type="image/x-icon" href="../assets/img/logo/fav.png?v=<?php echo $ver;?>">
        <!-- Bootstrap CSS -->
        <link href="../assets/vendors/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="../assets/vendors/slick/slick-theme.css" rel="stylesheet">
        <link href="../assets/vendors/slick/slick.css" rel="stylesheet">
        <link href="../assets/vendors/animation/animate.css" rel="stylesheet">
        <link href="../assets/vendors/phosphor/css/icons.css" rel="stylesheet">
        <link href="../assets/css/style.css?v=<?php echo $ver;?>" rel="stylesheet" type="text/css"/>        
        <link rel="stylesheet" href="../assets/css/responsive.css?v=<?php echo $ver;?>">
        <link href="../assets/css/custom.css?v=<?php echo $ver;?>" rel="stylesheet" type="text/css"/>
        
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
                    <a class="navbar-brand sticky_logo" href="../">
                        <img src="../assets/img/logo/logo_dark.png?v=<?php echo $ver;?>" alt="logo">
                        <img src="../assets/img/logo/logo_dark.png?v=<?php echo $ver;?>" alt="logo">
                    </a>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav menu ms-lg-auto me-lg-auto">
                            <li class="nav-item dropdown submenu active">
                                <a class="nav-link dropdown-toggle" href="../" role="button" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    Home
                                </a>
                                <!-- <i class="ph-caret-down mobile_dropdown_icon"></i> -->
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../about/">
                                    About
                                </a>
                                <!-- <i class="ph-caret-down mobile_dropdown_icon"></i> -->
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../pricing/">
                                    Plans
                                </a>
                                <!-- <i class="ph-caret-down mobile_dropdown_icon"></i> -->
                            </li>

                            <li class="nav-item"><a href="../contact/" class="nav-link">Contact</a></li>
                        </ul>
                    </div>
                    <ul class="list-unstyled alter_nav alter_nav_color_btn">
                        <li><a href="../login/">Login</a></li>
                        <li><a href="../register/" class="nav_btn btn_hover">Create Account<span></span></a></li>
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