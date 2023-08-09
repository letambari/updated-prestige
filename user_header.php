<!DOCTYPE html>
<html lang="en">
<head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
		<meta charset="utf-8">
		<meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
		<title><?php echo $title;?></title>
        <meta name="keywords" content="<?php echo $site_name;?>">
        <meta name="author" content="<?php echo $site_name;?>" />
        <meta name="company" content="<?php echo $site_name;?>" />
        <meta name="copyright" content="&copy; 2022 <?php echo $site_name;?>" />
        <meta name="publisher" content="<?php echo $site_name;?>" />
        <meta property="og:title" content=" <?php echo $site_name;?>" />        
        <meta property="og:locale" content="en_US" />
        <meta property="og:url" content="https://<?php echo $site_link;?>" />
        <meta property="og:site_name" content="<?php echo $site_name;?>" />
        <!-- Favicon -->
		<link rel="icon" type="image/x-icon" href="../assets/img/logo/fav.png?v=<?php echo $ver;?>">

		<!-- Bootstrap css-->
		<link href="../assets_inside/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>

		<!-- Icons css-->
		<link href="../assets_inside/plugins/web-fonts/icons.css" rel="stylesheet"/>
		<link href="../assets_inside/plugins/web-fonts/font-awesome/font-awesome.min.css" rel="stylesheet">
		<link href="../assets_inside/plugins/web-fonts/plugin.css" rel="stylesheet"/>

		<!-- Style css-->
		<link href="../assets_inside/css/style/style.css" rel="stylesheet">
		<link href="../assets_inside/css/skins.css" rel="stylesheet">
		<link href="../assets_inside/css/dark-style.css" rel="stylesheet">
		<link href="../assets_inside/css/colors/default.css" rel="stylesheet">

		<!-- Color css-->
		<link id="theme" rel="stylesheet" type="text/css" media="all" href="../assets_inside/css/colors/color.css">

		<!-- Select2 css-->
        <link href="../assets_inside/plugins/select2/css/select2.min.css" rel="stylesheet">
		
				
		<!-- Sidemenu css-->
		<link href="../assets_inside/css/sidemenu/sidemenu.css" rel="stylesheet">

		<!-- Switcher css-->
		<link href="../assets_inside/switcher/css/switcher.css" rel="stylesheet">
		<link href="../assets_inside/switcher/demo.css" rel="stylesheet">


		<link href="../assets_inside/plugins/datatable/dataTables.bootstrap4.min.css" rel="stylesheet" />
		<link href="../assets_inside/plugins/datatable/responsivebootstrap4.min.css" rel="stylesheet" />
		<link href="../assets_inside/plugins/datatable/fileexport/buttons.bootstrap4.min.css" rel="stylesheet" />

		<?php echo $class_link; ?>
        <!--end::Global Stylesheets Bundle-->
	</head>		


	<body class="main-body leftmenu">

		<!-- Loader -->
		<div id="global-loader">
			<img src="../assets_inside/img/loader.svg" class="loader-img" alt="Loader">
		</div>
        <!-- End Loader -->

		<!-- Page -->
		<div class="page">

        <!-- Sidemenu -->
			<div class="main-sidebar main-sidebar-sticky side-menu">
				<div class="sidemenu-logo">
					<a class="main-logo" href="../dashboard">
						<img src="../assets_inside/img/brand/logo-light.png?v=<?php echo $ver;?>" class="header-brand-img desktop-logo" alt="logo">
						<img src="../assets_inside/img/brand/icon-light.png?v=<?php echo $ver;?>" class="header-brand-img icon-logo" alt="logo">
						<img src="../assets_inside/img/brand/logo.png?v=<?php echo $ver;?>" class="header-brand-img desktop-logo theme-logo" alt="logo">
						<img src="../assets_inside/img/brand/icon.png?v=<?php echo $ver;?>" class="header-brand-img icon-logo theme-logo" alt="logo">
					</a>
				</div>
				<div class="main-sidebar-body">
					<ul class="nav">
						<li class="nav-header"><span class="nav-label">Dashboard</span></li>
						<li class="nav-item <?php echo $dash; ?>">
							<a class="nav-link" href="../dashboard"><span class="shape1"></span><span class="shape2"></span><i class="ti-home sidemenu-icon"></i><span class="sidemenu-label">Dashboard</span></a>
						</li>
                                                <?php 
                                                $show='';
                                                if($pur_invest=='active'||$my_invest=='active'){
                                                    $show='show';
                                                }
                                                ?>
						<li class="nav-item <?php echo $pur_invest.$my_invest.' '.$show; ?>">
                                                    <a class="nav-link with-sub" href="#"><span class="shape1"></span><span class="shape2"></span><i class="ti-wallet sidemenu-icon"></i><span class="sidemenu-label">Investment Portfolio</span><i class="angle fe fe-chevron-right"></i></a>
                                                    <ul class="nav-sub">
                                                            <li class="nav-sub-item <?php echo $pur_invest; ?>">
                                                                    <a class="nav-sub-link" href="../purchase-investment/">Create New Portfolio</a>
                                                            </li>
                                                            <li class="nav-sub-item <?php echo $my_invest; ?>">
                                                                    <a class="nav-sub-link" href="../my-investments/">My Investments</a>
                                                            </li>
                                                    </ul>
						</li>
						
						
						<li class="nav-item <?php echo $dep; ?>">
							<a class="nav-link" href="../deposit"><span class="shape1"></span><span class="shape2"></span><i class="ti-money sidemenu-icon"></i><span class="sidemenu-label ">Fund</span></a>
						</li>

						<li class="nav-item <?php echo $acct_pro; ?>">
							<a class="nav-link with-sub" href="#"><span class="shape1"></span><span class="shape2"></span><i class="ti-user sidemenu-icon"></i><span class="sidemenu-label">Account</span><i class="angle fe fe-chevron-right"></i></a>
							<ul class="nav-sub">
								<li class="nav-sub-item">
									<a class="nav-sub-link" href="../account-profile/">Profile</a>
								</li>
								<li class="nav-sub-item">
									<a class="nav-sub-link" href="../account-settings/">Settings</a>
								</li>
								<li class="nav-sub-item">
									<a class="nav-sub-link" href="../account-change-password/">Security</a>
								</li>
							</ul>
						</li>

						
						<!-- <li class="nav-item">
							<a class="nav-link with-sub" href="#"><span class="shape1"></span><span class="shape2"></span><i class="ti-hand-open sidemenu-icon"></i><span class="sidemenu-label ">Referrals</span></a>
						</li> -->

						<li class="nav-item <?php echo $pricing; ?>">
							<a class="nav-link" href="../pricing"><span class="shape1"></span><span class="shape2"></span><i class="ti-money sidemenu-icon"></i><span class="sidemenu-label ">Pricing</span></a>
						</li>

						<li class="nav-item <?php echo $with; ?>">
							<a class="nav-link" href="../withdrawal"><span class="shape1"></span><span class="shape2"></span><i class="ti-credit-card sidemenu-icon"></i><span class="sidemenu-label ">Withdrawal</span></a>
						</li>

						
						
						<li class="nav-item <?php echo $act_his; ?>">
							<a class="nav-link with-sub" href="#"><span class="shape1"></span><span class="shape2"></span><i class="ti-agenda sidemenu-icon"></i><span class="sidemenu-label">Transactions</span><i class="angle fe fe-chevron-right"></i></a>
							<ul class="nav-sub">
								<li class="nav-sub-item">
									<a class="nav-sub-link" href="../account-history/?transactions=earnings">Earnings</a>
								</li>
								<li class="nav-sub-item">
									<a class="nav-sub-link" href="../account-history/?transactions=funding">Funding</a>
								</li>
								<li class="nav-sub-item">
									<a class="nav-sub-link" href="../account-history/?transactions=porfolio">Portfolio Purchase</a>
								</li>
								<li class="nav-sub-item">
									<a class="nav-sub-link" href="../account-history/?transactions=withdrawal">Withdrawal</a>
								</li>
							</ul>
						</li>
						
						<li class="nav-header"><span class="nav-label"></span></li>
						<li class="nav-item <?php echo $ref; ?>">
							<a class="nav-link" href="../referral"><span class="shape1"></span><span class="shape2"></span><i class="ti-hand-open sidemenu-icon"></i><span class="sidemenu-label ">Referrals</span></a>
						</li>
						<li class="nav-item <?php echo $suppt; ?>">
							<a class="nav-link" href="../support"><span class="shape1"></span><span class="shape2"></span><i class="ti-support sidemenu-icon"></i><span class="sidemenu-label">Support</span></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="../logout"><span class="shape1"></span><span class="shape2"></span><i class="ti-power-off sidemenu-icon"></i><span class="sidemenu-label">Logout</span></a>
						</li>
					</ul>
				</div>
			</div>
			<!-- End Sidemenu -->        <!-- Main Header-->
			<div class="main-header side-header sticky">
				<div class="container-fluid">
					<div class="main-header-left">
						<a class="main-header-menu-icon" href="#" id="mainSidebarToggle"><span></span></a>
					</div>
					<div class="main-header-center">
						<div class="responsive-logo">
							<a href="../dashboard"><img src="../assets_inside/img/brand/logo.png" class="mobile-logo" alt="logo"></a>
							<a href="../dashboard"><img src="../assets_inside/img/brand/logo-light.png" class="mobile-logo-dark" alt="logo"></a>
						</div>
						
					</div>
					<div class="main-header-right">
						
						<div class="dropdown main-profile-menu">
							<a class="d-flex" href="#">
								<span class="main-img-user" ><img alt="avatar" src="../assets_inside/img/users/1.jpg"></span>
							</a>
							<div class="dropdown-menu">
								<div class="header-navheading">
									<h6 class="main-notification-title"><?php echo $full_n; ?></h6>
									<p class="main-notification-text"><?php echo $user_type ; ?></p>
								</div>
								<a class="dropdown-item border-top" href="../account-profile">
									<i class="fe fe-user"></i> My Profile
								</a>
								<a class="dropdown-item" href="../account-settings/">
									<i class="fe fe-settings"></i> Account Settings
								</a>
								<a class="dropdown-item" href="../support">
									<i class="fe fe-settings"></i> Support
								</a>
								<a class="dropdown-item" href="../logout">
									<i class="fe fe-power"></i> Sign Out
								</a>
							</div>
						</div>
					
					</div>
				</div>
			</div>
			<!-- End Main Header-->	
                        <div class="main-content side-content pt-0">
                <div class="container-fluid">
                    <div class="inner-body">