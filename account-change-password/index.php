<?php
include_once("../controller/dependent.php");
///////////////////////////////////////////////////////////////
//SCript to uplload profile image /////////////////////////////
////////////////////////////////////////////////////////////////

// variables for those who are to pay 
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
//////////////////////////
?> <?php
	$title = 'Change Password | ' . $site_name;
	$title2 = 'Change Password';
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
	$acct_settings = '';
	$acct_security = 'active';
	$suppt = '';
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
								<h3 class="fw-bold m-0">Change Password</h3>
							</div>
							<!--end::Card title-->
						</div>
					</div>


					<div class="main-content-body tab-pane p-4 border-top-0" id="settings">
						<div class="card-body border" data-select2-id="12">
							<form class="form-horizontal" data-select2-id="11" onsubmit="return false" id="accountForm">



								<!--end::Hint-->
								<div class="form-group ">
									<div class="row row-sm">
										<div class="col-md-3">
											<label class="form-label">Email Address</label>
										</div>
										<div class="col-md-9">
											<input type="text" class="form-control" placeholder="User Name" type="email" value="<?php echo $email; ?>" id="email" name="email" required="" readonly="">
										</div>
									</div>
								</div>
								<div class="form-group ">
									<div class="row row-sm">
										<div class="col-md-3">
											<label class="form-label">Current Password</label>
										</div>
										<div class="col-md-9">
											<input class="form-control" id="current_password" name="current_password" placeholder="Current Password" type="password">
										</div>
									</div>
								</div>
								<div class="form-group " data-select2-id="108">
									<div class="row" data-select2-id="107">
										<div class="col-md-3">
											<label class="form-label">New Password</label>
										</div>
										<div class="col-md-9" data-select2-id="106">
											<input class="form-control" id="password2" name="password2" placeholder="New Password" type="password" autocomplete="off">

										</div>
									</div>
								</div>
								<div class="form-group " data-select2-id="10">
									<div class="row" data-select2-id="9">
										<div class="col-md-3">
											<label class="form-label">Retype New Password</label>
										</div>
										<div class="col-md-9" data-select2-id="8">
											<input class="form-control" name="password" id="password" placeholder="Retype New Password" type="password" autocomplete="off">

										</div>
									</div>
								</div>


								<div class="form-group">
									<div class="row row-sm">

										<div class="col-md-9">
											<label class="ckbox mg-b-10-f">
												<div class="" id="status1"></div>
												<input type="hidden" id="__x" name="__x" value="<?php echo $identifier; ?>" />
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
											<button onclick="change_pass()" id="pass_btn" type="submit" class="btn btn-primary mr-1">Save</button>
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