<?php
include_once("../controller/dependent.php");
/////////////////////////////////////////////////////////////////
$error = '';
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
		$sql = "SELECT username,avatar FROM users WHERE unique_field='$uniq' LIMIT 1";
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
		header("location: ../account-profile");
	}
}
//////// Script to update profile ////////////
/////////////////////////////////////////////////////////////////
if (isset($_GET['update_account']) && isset($_POST['UserFirstName']) and isset($_POST['UserMobile'])) {
	// GATHER THE POSTED DATA INTO LOCAL VARIABLES
	$name = mysqli_real_escape_string($db_conx, ucwords($_POST['UserFirstName']));
	$tel = preg_replace('#[^+0-9]#i', '', $_POST['UserMobile']);
	$country = $_POST['country'];
	$wallet = mysqli_real_escape_string($db_conx, $_POST['wallet']);
	$two_fa = 1;
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
	} else if (substr($wallet, 0, 1) != 1 && substr($wallet, 0, 1) != 3) {
		echo 4;
		exit();
	} else if (strlen($wallet) < 26 || strlen($wallet) > 35) {
		echo 4;
		exit();
	} else if ($user_check < 0) {
		echo 3;
		mysqli_close($db_conx);
		exit();
	} else {
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
		$sql = "SELECT wallet_address FROM wallets WHERE user_id='$profile_id' and wallet_name='bitcoin' LIMIT 1";
		$query = mysqli_query($db_conx, $sql);
		if (!$query) {
			echo mysqli_error($db_conx);
			exit();
		}
		/////////////////////////////////////////////////////////////////////////////
		$sql = "INSERT INTO wallets (user_id,wallet_name,wallet_address, date_added)";
		$sql .= "VALUES('$profile_id','bitcoin','$wallet','$new_time')";
		if (mysqli_num_rows($query) > 0) {
			$sql = "UPDATE wallets SET wallet_address='$wallet' where  user_id='$profile_id' and wallet_name='bitcoin' LIMIT 1";
		}
		$query = mysqli_query($db_conx, $sql);
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
// Check their security level
$sql = "SELECT security_level FROM random_string WHERE user_id='$profile_id' LIMIT 1";
$query = mysqli_query($db_conx, $sql);
$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
$security_level = $row['security_level'];
$two_fa = '<fieldset class="radio">
                <label>
                    <input type="radio"  value="2" name="two_fa" />
                    On
                </label>
            </fieldset>
            <fieldset class="radio">
                <label>
                    <input type="radio" checked="" value="1" name="two_fa" />
                    Off
                </label>
            </fieldset>';
if ($security_level == 2) {
	$two_fa = '<fieldset class="radio">
                <label>
                    <input type="radio"  checked="" value="2" name="two_fa" />
                    On
                </label>
            </fieldset>
            <fieldset class="radio">
                <label>
                    <input type="radio" value="1" name="two_fa" />
                    Off
                </label>
            </fieldset>';
}
?> <?php
	$title = 'Account Profile | ' . $site_name;
	$title2 = 'Profile';
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
	$acct_pro = 'active';
	$acct_settings = '';
	$acct_security = '';
	$suppt = '';
	$class_link = '';
	include_once '../user_header.php';
	?>
<!-- Mobile-header closed -->
<!-- Main Content-->


<!-- Page Header -->
<div class="page-header">
	<div>
		<h2 class="main-content-title tx-24 mg-b-5">Profile</h2>

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
						<a class="nav-link  active" href="../account-profile/">Overview</a>
						<a class="nav-link" href="../account-settings/">Account Settings</a>
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
				<div class="main-content-body tab-pane p-4 border-top-0 active" id="about">
					<div class="card-body p-0 border p-0 rounded-10">
						<div class="p-4">
							<div class="m-t-30">
								<h4 class="tx-15 text-uppercase mt-3">UID: <span class="mt-3 text-primary m-b-5 tx-14"><?php echo $identifier; ?></span></h4>
								<hr>

								<div class="">
									<h5 class="tx-15 text-uppercase">Username: <span class="mt-3 text-primary m-b-5 tx-14"><?php echo $username; ?></span></h5>
									<hr>
									<p class=""></p>
								</div>

								<div class="">
									<h5 class="tx-15 text-uppercase">Fullname: <span class="mt-3 text-primary m-b-5 tx-14"><?php echo $f_n; ?></span></h5>
									<p class=""></p>
								</div>
							</div>
						</div>
						<div class="border-top"></div>
						<div class="p-4">
							<label class="main-content-label tx-13 mg-b-20">Contact</label>
							<div class="d-sm-flex">
								<div class="mg-sm-r-20 mg-b-10">
									<div class="main-profile-contact-list">
										<div class="media">
											<div class="media-icon bg-primary-transparent text-primary"> <i class="icon ion-md-phone-portrait"></i> </div>
											<div class="media-body"> <span>Mobile</span>
												<div><?php echo $tel; ?></div>
											</div>
										</div>
									</div>
								</div>
								<div class="mg-sm-r-20 mg-b-10">
									<div class="main-profile-contact-list">
										<div class="media">
											<div class="media-icon bg-success-transparent text-success"> <i class="icon ion-md-link"></i> </div>
											<div class="media-body"> <span>Email</span>
												<div><?php echo $email_main; ?></div>
											</div>
										</div>
									</div>
								</div>
								<div class="">
									<div class="main-profile-contact-list">
										<div class="media">
											<div class="media-icon bg-info-transparent text-info"> <i class="icon ion-md-locate"></i> </div>
											<div class="media-body"> <span>Current Address</span>
												<div> <?php echo $country; ?> </div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="border-top"></div>

					</div>
				</div>
				<div class="main-content-body tab-pane p-4 border-top-0" id="edit">
					<div class="card-body border">
						<div class="mb-4 main-content-label">Personal Information</div>
						<form class="form-horizontal">
							<div class="mb-4 main-content-label">Name</div>
							<div class="form-group ">
								<div class="row row-sm">
									<div class="col-md-3">
										<label class="form-label">User Name</label>
									</div>
									<div class="col-md-9">
										<input type="text" class="form-control" placeholder="User Name" value="Mack Adamia">
									</div>
								</div>
							</div>
							<div class="form-group ">
								<div class="row row-sm">
									<div class="col-md-3">
										<label class="form-label">First Name</label>
									</div>
									<div class="col-md-9">
										<input type="text" class="form-control" placeholder="First Name" value="Mack Adamia">
									</div>
								</div>
							</div>
							<div class="form-group ">
								<div class="row row-sm">
									<div class="col-md-3">
										<label class="form-label">last Name</label>
									</div>
									<div class="col-md-9">
										<input type="text" class="form-control" placeholder="Last Name" value="Mack Adamia">
									</div>
								</div>
							</div>
							<div class="form-group ">
								<div class="row row-sm">
									<div class="col-md-3">
										<label class="form-label">Nick Name</label>
									</div>
									<div class="col-md-9">
										<input type="text" class="form-control" placeholder="Nick Name" value="Spruha">
									</div>
								</div>
							</div>
							<div class="form-group ">
								<div class="row row-sm">
									<div class="col-md-3">
										<label class="form-label">Designation</label>
									</div>
									<div class="col-md-9">
										<input type="text" class="form-control" placeholder="Designation" value="Web Designer">
									</div>
								</div>
							</div>
							<div class="mb-4 main-content-label">Contact Info</div>
							<div class="form-group ">
								<div class="row row-sm">
									<div class="col-md-3">
										<label class="form-label">Email<i>(required)</i></label>
									</div>
									<div class="col-md-9">
										<input type="text" class="form-control" placeholder="Email" value="info@Spruha.in">
									</div>
								</div>
							</div>
							<div class="form-group ">
								<div class="row row-sm">
									<div class="col-md-3">
										<label class="form-label">Website</label>
									</div>
									<div class="col-md-9">
										<input type="text" class="form-control" placeholder="Website" value="@spruko.w">
									</div>
								</div>
							</div>
							<div class="form-group ">
								<div class="row row-sm">
									<div class="col-md-3">
										<label class="form-label">Phone</label>
									</div>
									<div class="col-md-9">
										<input type="text" class="form-control" placeholder="phone number" value="+245 354 654">
									</div>
								</div>
							</div>
							<div class="form-group ">
								<div class="row row-sm">
									<div class="col-md-3">
										<label class="form-label">Address</label>
									</div>
									<div class="col-md-9">
										<textarea class="form-control" name="example-textarea-input" rows="2" placeholder="Address">San Francisco, CA</textarea>
									</div>
								</div>
							</div>
							<div class="mb-4 main-content-label">Social Info</div>
							<div class="form-group ">
								<div class="row row-sm">
									<div class="col-md-3">
										<label class="form-label">Twitter</label>
									</div>
									<div class="col-md-9">
										<input type="text" class="form-control" placeholder="twitter" value="twitter.com/spruko.html">
									</div>
								</div>
							</div>
							<div class="form-group ">
								<div class="row row-sm">
									<div class="col-md-3">
										<label class="form-label">Facebook</label>
									</div>
									<div class="col-md-9">
										<input type="text" class="form-control" placeholder="facebook" value="https://www.facebook.com/Spruha">
									</div>
								</div>
							</div>
							<div class="form-group ">
								<div class="row row-sm">
									<div class="col-md-3">
										<label class="form-label">Google+</label>
									</div>
									<div class="col-md-9">
										<input type="text" class="form-control" placeholder="google" value="spruko.com">
									</div>
								</div>
							</div>
							<div class="form-group ">
								<div class="row row-sm">
									<div class="col-md-3">
										<label class="form-label">Linked in</label>
									</div>
									<div class="col-md-9">
										<input type="text" class="form-control" placeholder="linkedin" value="linkedin.com/in/spruko">
									</div>
								</div>
							</div>
							<div class="form-group ">
								<div class="row row-sm">
									<div class="col-md-3">
										<label class="form-label">Github</label>
									</div>
									<div class="col-md-9">
										<input type="text" class="form-control" placeholder="github" value="github.com/sprukos">
									</div>
								</div>
							</div>
							<div class="mb-4 main-content-label">About Yourself</div>
							<div class="form-group ">
								<div class="row row-sm">
									<div class="col-md-3">
										<label class="form-label">Biographical Info</label>
									</div>
									<div class="col-md-9">
										<textarea class="form-control" name="example-textarea-input" rows="4" placeholder="">pleasure rationally encounter but because pursue consequences that are extremely painful.occur in which toil and pain can procure him some great pleasure..</textarea>
									</div>
								</div>
							</div>
							<div class="mb-4 main-content-label">Email Preferences</div>
							<div class="form-group mb-0">
								<div class="row row-sm">
									<div class="col-md-3">
										<label class="form-label">Verified User</label>
									</div>
									<div class="col-md-9">
										<div class="custom-controls-stacked">
											<label class="ckbox mg-b-10-f"><input checked="" type="checkbox"><span> Accept to receive post or page notification emails</span></label>
											<label class="ckbox"><input checked="" type="checkbox"><span> Accept to receive email sent to multiple recipients</span></label>
										</div>
										<div class="mt-3">
											<button type="button" class="btn btn-primary mr-1">Save</button>
											<button type="button" class="btn btn-danger">Reset</button>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>






				<div class="main-content-body tab-pane p-4 border-top-0" id="settings">
					<div class="card-body border" data-select2-id="12">
						<form class="form-horizontal" data-select2-id="11">
							<div class="mb-4 main-content-label">Account</div>
							<div class="form-group ">
								<div class="row row-sm">
									<div class="col-md-3">
										<label class="form-label">User Name</label>
									</div>
									<div class="col-md-9">
										<input type="text" class="form-control" placeholder="User Name" value="Sonia Taylor">
									</div>
								</div>
							</div>
							<div class="form-group ">
								<div class="row row-sm">
									<div class="col-md-3">
										<label class="form-label">Email</label>
									</div>
									<div class="col-md-9">
										<input type="text" class="form-control" placeholder="Email" value="info@SoniaTaylor.in">
									</div>
								</div>
							</div>
							<div class="form-group " data-select2-id="108">
								<div class="row" data-select2-id="107">
									<div class="col-md-3">
										<label class="form-label">Language</label>
									</div>
									<div class="col-md-9" data-select2-id="106">
										<select class="form-control select2" data-select2-id="1" tabindex="-1" aria-hidden="true">
											<option data-select2-id="3">Us English</option>
											<option data-select2-id="109">Arabic</option>
											<option data-select2-id="110">Korean</option>
										</select>
									</div>
								</div>
							</div>
							<div class="form-group " data-select2-id="10">
								<div class="row" data-select2-id="9">
									<div class="col-md-3">
										<label class="form-label">Timezone</label>
									</div>
									<div class="col-md-9" data-select2-id="8">
										<select class="form-control select2" data-select2-id="4" tabindex="-1" aria-hidden="true">
											<option value="Pacific/Midway" data-select2-id="6">(GMT-11:00) Midway Island, Samoa</option>
											<option value="America/Adak" data-select2-id="16">(GMT-10:00) Hawaii-Aleutian</option>
											<option value="Etc/GMT+10" data-select2-id="17">(GMT-10:00) Hawaii</option>
											<option value="Pacific/Marquesas" data-select2-id="18">(GMT-09:30) Marquesas Islands</option>
											<option value="Pacific/Gambier" data-select2-id="19">(GMT-09:00) Gambier Islands</option>
											<option value="America/Anchorage" data-select2-id="20">(GMT-09:00) Alaska</option>
											<option value="America/Ensenada" data-select2-id="21">(GMT-08:00) Tijuana, Baja California</option>
											<option value="Etc/GMT+8" data-select2-id="22">(GMT-08:00) Pitcairn Islands</option>
											<option value="America/Los_Angeles" data-select2-id="23">(GMT-08:00) Pacific Time (US &amp; Canada)</option>
											<option value="America/Denver" data-select2-id="24">(GMT-07:00) Mountain Time (US &amp; Canada)</option>
											<option value="America/Chihuahua" data-select2-id="25">(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
											<option value="America/Dawson_Creek" data-select2-id="26">(GMT-07:00) Arizona</option>
											<option value="America/Belize" data-select2-id="27">(GMT-06:00) Saskatchewan, Central America</option>
											<option value="America/Cancun" data-select2-id="28">(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
											<option value="Chile/EasterIsland" data-select2-id="29">(GMT-06:00) Easter Island</option>
											<option value="America/Chicago" data-select2-id="30">(GMT-06:00) Central Time (US &amp; Canada)</option>
											<option value="America/New_York" data-select2-id="31">(GMT-05:00) Eastern Time (US &amp; Canada)</option>
											<option value="America/Havana" data-select2-id="32">(GMT-05:00) Cuba</option>
											<option value="America/Bogota" data-select2-id="33">(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
											<option value="America/Caracas" data-select2-id="34">(GMT-04:30) Caracas</option>
											<option value="America/Santiago" data-select2-id="35">(GMT-04:00) Santiago</option>
											<option value="America/La_Paz" data-select2-id="36">(GMT-04:00) La Paz</option>
											<option value="Atlantic/Stanley" data-select2-id="37">(GMT-04:00) Faukland Islands</option>
											<option value="America/Campo_Grande" data-select2-id="38">(GMT-04:00) Brazil</option>
											<option value="America/Goose_Bay" data-select2-id="39">(GMT-04:00) Atlantic Time (Goose Bay)</option>
											<option value="America/Glace_Bay" data-select2-id="40">(GMT-04:00) Atlantic Time (Canada)</option>
											<option value="America/St_Johns" data-select2-id="41">(GMT-03:30) Newfoundland</option>
											<option value="America/Araguaina" data-select2-id="42">(GMT-03:00) UTC-3</option>
											<option value="America/Montevideo" data-select2-id="43">(GMT-03:00) Montevideo</option>
											<option value="America/Miquelon" data-select2-id="44">(GMT-03:00) Miquelon, St. Pierre</option>
											<option value="America/Godthab" data-select2-id="45">(GMT-03:00) Greenland</option>
											<option value="America/Argentina/Buenos_Aires" data-select2-id="46">(GMT-03:00) Buenos Aires</option>
											<option value="America/Sao_Paulo" data-select2-id="47">(GMT-03:00) Brasilia</option>
											<option value="America/Noronha" data-select2-id="48">(GMT-02:00) Mid-Atlantic</option>
											<option value="Atlantic/Cape_Verde" data-select2-id="49">(GMT-01:00) Cape Verde Is.</option>
											<option value="Atlantic/Azores" data-select2-id="50">(GMT-01:00) Azores</option>
											<option value="Europe/Belfast" data-select2-id="51">(GMT) Greenwich Mean Time : Belfast</option>
											<option value="Europe/Dublin" data-select2-id="52">(GMT) Greenwich Mean Time : Dublin</option>
											<option value="Europe/Lisbon" data-select2-id="53">(GMT) Greenwich Mean Time : Lisbon</option>
											<option value="Europe/London" data-select2-id="54">(GMT) Greenwich Mean Time : London</option>
											<option value="Africa/Abidjan" data-select2-id="55">(GMT) Monrovia, Reykjavik</option>
											<option value="Europe/Amsterdam" data-select2-id="56">(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
											<option value="Europe/Belgrade" data-select2-id="57">(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
											<option value="Europe/Brussels" data-select2-id="58">(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
											<option value="Africa/Algiers" data-select2-id="59">(GMT+01:00) West Central Africa</option>
											<option value="Africa/Windhoek" data-select2-id="60">(GMT+01:00) Windhoek</option>
											<option value="Asia/Beirut" data-select2-id="61">(GMT+02:00) Beirut</option>
											<option value="Africa/Cairo" data-select2-id="62">(GMT+02:00) Cairo</option>
											<option value="Asia/Gaza" data-select2-id="63">(GMT+02:00) Gaza</option>
											<option value="Africa/Blantyre" data-select2-id="64">(GMT+02:00) Harare, Pretoria</option>
											<option value="Asia/Jerusalem" data-select2-id="65">(GMT+02:00) Jerusalem</option>
											<option value="Europe/Minsk" data-select2-id="66">(GMT+02:00) Minsk</option>
											<option value="Asia/Damascus" data-select2-id="67">(GMT+02:00) Syria</option>
											<option value="Europe/Moscow" data-select2-id="68">(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
											<option value="Africa/Addis_Ababa" data-select2-id="69">(GMT+03:00) Nairobi</option>
											<option value="Asia/Tehran" data-select2-id="70">(GMT+03:30) Tehran</option>
											<option value="Asia/Dubai" data-select2-id="71">(GMT+04:00) Abu Dhabi, Muscat</option>
											<option value="Asia/Yerevan" data-select2-id="72">(GMT+04:00) Yerevan</option>
											<option value="Asia/Kabul" data-select2-id="73">(GMT+04:30) Kabul</option>
											<option value="Asia/Yekaterinburg" data-select2-id="74">(GMT+05:00) Ekaterinburg</option>
											<option value="Asia/Tashkent" data-select2-id="75">(GMT+05:00) Tashkent</option>
											<option value="Asia/Kolkata" data-select2-id="76">(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
											<option value="Asia/Katmandu" data-select2-id="77">(GMT+05:45) Kathmandu</option>
											<option value="Asia/Dhaka" data-select2-id="78">(GMT+06:00) Astana, Dhaka</option>
											<option value="Asia/Novosibirsk" data-select2-id="79">(GMT+06:00) Novosibirsk</option>
											<option value="Asia/Rangoon" data-select2-id="80">(GMT+06:30) Yangon (Rangoon)</option>
											<option value="Asia/Bangkok" data-select2-id="81">(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
											<option value="Asia/Krasnoyarsk" data-select2-id="82">(GMT+07:00) Krasnoyarsk</option>
											<option value="Asia/Hong_Kong" data-select2-id="83">(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
											<option value="Asia/Irkutsk" data-select2-id="84">(GMT+08:00) Irkutsk, Ulaan Bataar</option>
											<option value="Australia/Perth" data-select2-id="85">(GMT+08:00) Perth</option>
											<option value="Australia/Eucla" data-select2-id="86">(GMT+08:45) Eucla</option>
											<option value="Asia/Tokyo" data-select2-id="87">(GMT+09:00) Osaka, Sapporo, Tokyo</option>
											<option value="Asia/Seoul" data-select2-id="88">(GMT+09:00) Seoul</option>
											<option value="Asia/Yakutsk" data-select2-id="89">(GMT+09:00) Yakutsk</option>
											<option value="Australia/Adelaide" data-select2-id="90">(GMT+09:30) Adelaide</option>
											<option value="Australia/Darwin" data-select2-id="91">(GMT+09:30) Darwin</option>
											<option value="Australia/Brisbane" data-select2-id="92">(GMT+10:00) Brisbane</option>
											<option value="Australia/Hobart" data-select2-id="93">(GMT+10:00) Hobart</option>
											<option value="Asia/Vladivostok" data-select2-id="94">(GMT+10:00) Vladivostok</option>
											<option value="Australia/Lord_Howe" data-select2-id="95">(GMT+10:30) Lord Howe Island</option>
											<option value="Etc/GMT-11" data-select2-id="96">(GMT+11:00) Solomon Is., New Caledonia</option>
											<option value="Asia/Magadan" data-select2-id="97">(GMT+11:00) Magadan</option>
											<option value="Pacific/Norfolk" data-select2-id="98">(GMT+11:30) Norfolk Island</option>
											<option value="Asia/Anadyr" data-select2-id="99">(GMT+12:00) Anadyr, Kamchatka</option>
											<option value="Pacific/Auckland" data-select2-id="100">(GMT+12:00) Auckland, Wellington</option>
											<option value="Etc/GMT-12" data-select2-id="101">(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
											<option value="Pacific/Chatham" data-select2-id="102">(GMT+12:45) Chatham Islands</option>
											<option value="Pacific/Tongatapu" data-select2-id="103">(GMT+13:00) Nuku'alofa</option>
											<option value="Pacific/Kiritimati" data-select2-id="104">(GMT+14:00) Kiritimati</option>
										</select>
									</div>
								</div>
							</div>
							<div class="form-group ">
								<div class="row row-sm">
									<div class="col-md-3 col">
										<label class="form-label">Verification</label>
									</div>
									<div class="col-md-9 col">
										<label class="ckbox mg-b-10-f">
											<input type="checkbox"><span>Email</span></label>
										<label class="ckbox mg-b-10-f">
											<input checked="" type="checkbox"><span>SMS</span></label>
										<label class="ckbox mg-b-10-f">
											<input type="checkbox"><span>Phone</span></label>
									</div>
								</div>
							</div>
							<div class="mb-4 main-content-label">Security Settings</div>
							<div class="form-group ">
								<div class="row row-sm">
									<div class="col-md-3">
										<label class="form-label">Login Verification</label>
									</div>
									<div class="col-md-9"> <a class="" href="#">Setup Verification</a> </div>
								</div>
							</div>
							<div class="form-group ">
								<div class="row row-sm">
									<div class="col-md-3">
										<label class="form-label">Password Verification</label>
									</div>
									<div class="col-md-9">
										<label class="ckbox mg-b-10-f">
											<input type="checkbox"><span>Require Personal Details</span></label>
									</div>
								</div>
							</div>
							<div class="form-group ">
								<div class="row row-sm">
									<div class="col-md-3"> </div>
									<div class="col-md-9"> <a class="mg-r-20" href="#">Deactivate Account</a> <a class="" href="#">Change Password</a>
										<div class="mt-3">
											<button type="button" class="btn btn-primary mr-1">Save</button>
											<button type="button" class="btn btn-danger">Reset</button>
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