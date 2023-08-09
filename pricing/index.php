<?php
include_once("../controller/dependent.php");
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
if (isset($_GET['select_plan']) && isset($_POST['plan'])) {
	// GATHER THE POSTED DATA INTO LOCAL VARIABLES
	$plan_code = mysqli_real_escape_string($db_conx, $_POST['plan']);
	$unique = $_SESSION[$site_cokie];
	if ($plan_code == "") {
		echo 2;
		exit();
	} else {
		// DUPLICATE DATA CHECKS 
		$sql = "SELECT id,email,full_name,username FROM users WHERE unique_field='$unique' LIMIT 1";
		$query = mysqli_query($db_conx, $sql);
		$unique_check = mysqli_num_rows($query);
		if ($unique_check < 0) {
			echo 3;
			exit();
		} else {
			$row = mysqli_fetch_row($query);
			$id = $row[0];
			$e = $row[1];
			$f_n = ucwords($row[2]);
			$uname = $row[3];
			if ('' == $f_n) {
				$f_n = ucwords($uname);
			}
			$sql = "SELECT active,plan,currency FROM account WHERE user_id='$id' LIMIT 1";
			$query = mysqli_query($db_conx, $sql);
			$row = mysqli_fetch_row($query);
			$active = $row[0];
			$db_plan = $row[1];
			$currency_main = explode("|", $row[2]);
			$currency = $currency_main[0];
			$symbol = end($currency_main);
			if ($db_plan != '' || $active == 1) {
				echo 4;
				exit();
			}
			$plan = 'Pawn';
			$daily_profit_rate = 0.008;
			$monthly_profit_rate = 0.16;
			$referal_rate = 0.05;
			if ($plan_code == '78476358736238') {
				$plan = 'Bishop';
				$daily_profit_rate = 0.016;
				$monthly_profit_rate = 0.32;
				$referal_rate = 0.06;
			} elseif ($plan_code == '83436275876387') {
				// 20days
				$plan = 'Knight';
				$daily_profit_rate = 0.024;
				$monthly_profit_rate = 0.48;
				$referal_rate = 0.08;
			} elseif ($plan_code == '63588377348762') {
				// 20days
				$plan = 'Rook';
				$daily_profit_rate = 0.03;
				$monthly_profit_rate = 0.6;
				$referal_rate = 0.1;
			} elseif ($plan_code == '48765883773632') {
				// 20days
				$plan = 'Crypto IRA';
				$daily_profit_rate = 0.013;
				$monthly_profit_rate = 0.26;
				$referal_rate = 0.1;
			}
			// END FORM DATA ERROR HANDLING
			// Begin Insertion of data into the database
			$new_time = date("Y-m-d H:i:s");
			$sql = "update account set plan='$plan',daily_profit_rate='$daily_profit_rate',monthly_profit_rate='$monthly_profit_rate',updated_at='$new_time' where user_id='$id' limit 1";
			$query = mysqli_query($db_conx, $sql);
			if (!$query) {
				echo mysqli_error($db_conx);
				exit();
			}
			$sql = "update referral set referal_percent='$referal_rate' where user_id='$id' limit 1";
			$query = mysqli_query($db_conx, $sql);
			if (!$query) {
				echo mysqli_error($db_conx);
				exit();
			}
			echo 1;
			exit();
		}
	}
}
//$btn_disable = 'disabled';
//if($plan ==''){
//    $btn_disable = '';
//}
?>
<?php
$title = 'Investment Pricing | ' . $site_name;
$title2 = 'Pricing';
$keyword = 'Investment Pricing,Pricing at ' . $site_name . ', what we offer, ' . $site_link;
$discription = 'Our Investment Packages: Starter, Investor, and Ultimate.';
$dash = '';
$dep = '';
$class_link = "";
$pricing = 'active';
$pur_invest = '';
$my_invest = '';
$with = '';
$act_his = '';
$ref = '';
$acct_pro = '';
$acct_settings = '';
$acct_security = '';
$suppt = '';
include_once '../user_header.php';
?>
<!-- Mobile-header closed -->
<!-- Main Content-->



<!-- Page Header -->
<div class="page-header">
	<div>
		<h2 class="main-content-title tx-24 mg-b-5">Pricing</h2>

	</div>

</div>

<div class="row row-sm">
	<div class="col-xl-12 col-lg-12">
		<div class="card custom-card">
			<div class="card-body">
				<div>

				</div>
				<div class="pricing-tabs">
					<div class=" text-center">
						<div class="pri-tabs-heading">
							<h2>PREMIUM CRYPTO ASSET PICKS</h2>
							<p class="text-muted">If you need more info about our pricing, please check Pricing Guidelines</p>
						</div>
						<div class="col-xl-12">
							<div class=" ">
								<b>Terms & Conditions:</b> <br />
								0% Withdrawal Fee, <br />
								8% Management Fee (paid quarterly) <br />
								8% Upgrade Bonus, <br />
								10% Referral Bonus <br />
								30 Days Renewable Trading Contract.
							</div>
						</div>

						<div class="tab-content">
							<div class="tab-pane active" id="year">
								<div class="row row-sm">
									<div class="col-sm-6 col-lg-3">
										<div class="card overflow-hidden">
											<div class="text-center card-pricing pricing1">
												<div class="p-2 text-white bg-primary fs-20">PCAP (Tier 1)</div>
												<div class="p-3 font-weight-normal mb-0"><span class="price">$1K</span></div>
												<div class="card-body text-center pt-0">
													<ul class="list-unstyled mb-0">
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Min: </strong>$1000</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Max: </strong>$50,999</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Monthly APY: </strong>20%</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Money</strong> BackGuarntee</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>24/7</strong> Support</li>
													</ul>
												</div>
												<div class="card-footer text-center">
													<button class="btn ripple btn-primary btn-block" onclick="select_portfolio('PCAP (Tier 1)','Tier 1','subscription_modal')">Select</button>
												</div>
											</div>
										</div>
									</div>

									<div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
										<div class="card overflow-hidden">
											<div class="text-center card-pricing pricing1">
												<div class="p-2 text-white bg-secondary fs-20">PCAP (Tier 2)</div>
												<div class="p-3 font-weight-normal mb-0"><span class="price">$51K</span></div>
												<div class="card-body text-center pt-0">
													<ul class="list-unstyled mb-0">
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Min: </strong>$51,000</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Max: </strong>$210,999</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Monthly APY:</strong> 25%s</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Money</strong> BackGuarntee</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>24/7</strong> Support</li>
													</ul>
												</div>
												<div class="card-footer text-center">
													<button class="btn ripple btn-secondary btn-block" onclick="select_portfolio('PCAP (Tier 2)','Tier 2','subscription_modal')">Select</button>

												</div>
											</div>
										</div>
									</div>

									<div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
										<div class="card overflow-hidden">
											<div class="text-center card-pricing pricing1">
												<div class="p-2 text-white bg-info fs-20">PCAP (Tier 3)</div>
												<div class="p-3 font-weight-normal mb-0"><span class="price">$211K</span></div>
												<div class="card-body text-center pt-0">
													<ul class="list-unstyled mb-0">
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Min: </strong>$211,000</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Max: </strong>$500,999</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Monthly APY:</strong> 30%</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Money</strong> BackGuarntee</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>24/7</strong> Support</li>
													</ul>
												</div>
												<div class="card-footer text-center">

													<button class="btn ripple btn-info btn-block" onclick="select_portfolio('PCAP (Tier 3)','Tier 3','subscription_modal')">Select</button>
													<!--end::Select-->
												</div>
											</div>
										</div>
									</div>

									<div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
										<div class="card overflow-hidden">
											<div class="text-center card-pricing pricing1">
												<div class="p-2 text-white bg-success fs-20">PCAP (Tier 4)</div>
												<div class="p-3 font-weight-normal mb-0"><span class="price">$501K</span></div>
												<div class="card-body text-center pt-0">
													<ul class="list-unstyled mb-0">
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Min: </strong>$501,000</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Max: </strong>none</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Monthly APY: </strong>35%+</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Money</strong> BackGuarntee</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>24/7</strong> Support</li>
													</ul>
												</div>
												<div class="card-footer text-center">
													<button class="btn ripple btn-success btn-block" onclick="select_portfolio('PCAP (Tier 4)','Tier 4','subscription_modal')">Select</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<div class="row row-sm">
	<div class="col-xl-12 col-lg-12">
		<div class="card custom-card">
			<div class="card-body">
				<div>

				</div>
				<div class="pricing-tabs">
					<div class=" text-center">
						<div class="pri-tabs-heading">
							<h2>PREMIUM STOCK PICKS</h2>
							<p class="text-muted">If you need more info about our pricing, please check Pricing Guidelines</p>
						</div>
						<div class="col-xl-12">
							<div class=" ">
								<b>Terms & Conditions:</b> <br />
								0% Withdrawal Fee, <br />
								8% Management Fee (paid quarterly) <br />
								8% Upgrade Bonus, <br />
								10% Referral Bonus <br />
								30 Days Renewable Trading Contract.
							</div>
						</div>

						<div class="tab-content">
							<div class="tab-pane active" id="year">
								<div class="row row-sm">
									<div class="col-sm-6 col-lg-3">
										<div class="card overflow-hidden">
											<div class="text-center card-pricing pricing1">
												<div class="p-2 text-white bg-primary fs-20">PSP (Tier 1)</div>
												<div class="p-3 font-weight-normal mb-0"><span class="price">$1K</span></div>
												<div class="card-body text-center pt-0">
													<ul class="list-unstyled mb-0">
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Min: </strong>$1000</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Max: </strong>$50,999</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Monthly APY: </strong>20%</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Money</strong> BackGuarntee</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>24/7</strong> Support</li>
													</ul>
												</div>
												<div class="card-footer text-center">
													<button class="btn ripple btn-primary btn-block" onclick="select_portfolio('PSP (Tier 1)','Tier 1','subscription_modal')">Select</button>
												</div>
											</div>
										</div>
									</div>

									<div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
										<div class="card overflow-hidden">
											<div class="text-center card-pricing pricing1">
												<div class="p-2 text-white bg-secondary fs-20">PSP (Tier 2)</div>
												<div class="p-3 font-weight-normal mb-0"><span class="price">$51K</span></div>
												<div class="card-body text-center pt-0">
													<ul class="list-unstyled mb-0">
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Min: </strong>$51,000</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Max: </strong>$210,999</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Monthly APY:</strong> 25%s</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Money</strong> BackGuarntee</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>24/7</strong> Support</li>
													</ul>
												</div>
												<div class="card-footer text-center">
													<button class="btn ripple btn-secondary btn-block" onclick="select_portfolio('PSP (Tier 2)','Tier 2','subscription_modal')">Select</button>

												</div>
											</div>
										</div>
									</div>

									<div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
										<div class="card overflow-hidden">
											<div class="text-center card-pricing pricing1">
												<div class="p-2 text-white bg-info fs-20">PSP (Tier 3)</div>
												<div class="p-3 font-weight-normal mb-0"><span class="price">$211K</span></div>
												<div class="card-body text-center pt-0">
													<ul class="list-unstyled mb-0">
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Min: </strong>$211,000</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Max: </strong>$500,999</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Monthly APY:</strong> 30%</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Money</strong> BackGuarntee</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>24/7</strong> Support</li>
													</ul>
												</div>
												<div class="card-footer text-center">

													<button class="btn ripple btn-info btn-block" onclick="select_portfolio('PSP (Tier 3)','Tier 3','subscription_modal')">Select</button>
													<!--end::Select-->
												</div>
											</div>
										</div>
									</div>

									<div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
										<div class="card overflow-hidden">
											<div class="text-center card-pricing pricing1">
												<div class="p-2 text-white bg-success fs-20">PSP (Tier 4)</div>
												<div class="p-3 font-weight-normal mb-0"><span class="price">$501K</span></div>
												<div class="card-body text-center pt-0">
													<ul class="list-unstyled mb-0">
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Min: </strong>$501,000</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Max: </strong>none</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Monthly APY: </strong>35%+</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Money</strong> BackGuarntee</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>24/7</strong> Support</li>
													</ul>
												</div>
												<div class="card-footer text-center">
													<button class="btn ripple btn-success btn-block" onclick="select_portfolio('PSP (Tier 4)','Tier 4','subscription_modal')">Select</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>




<div class="row row-sm">
	<div class="col-xl-12 col-lg-12">
		<div class="card custom-card">
			<div class="card-body">
				<div>

				</div>
				<div class="pricing-tabs">
					<div class=" text-center">
						<div class="pri-tabs-heading">
							<h2>PREMIUM CURRENCY PAIR PICKS</h2>
							<p class="text-muted">If you need more info about our pricing, please check Pricing Guidelines</p>
						</div>
						<div class="col-xl-12">
							<div class=" ">
								<b>Terms & Conditions:</b> <br />
								0% Withdrawal Fee, <br />
								8% Management Fee (paid quarterly) <br />
								8% Upgrade Bonus, <br />
								10% Referral Bonus <br />
								30 Days Renewable Trading Contract.
							</div>
						</div>

						<div class="tab-content">
							<div class="tab-pane active" id="year">
								<div class="row row-sm">
									<div class="col-sm-6 col-lg-3">
										<div class="card overflow-hidden">
											<div class="text-center card-pricing pricing1">
												<div class="p-2 text-white bg-primary fs-20">PCPP (Tier 1)</div>
												<div class="p-3 font-weight-normal mb-0"><span class="price">$1K</span></div>
												<div class="card-body text-center pt-0">
													<ul class="list-unstyled mb-0">
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Min: </strong>$1000</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Max: </strong>$50,999</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Monthly APY: </strong>20%</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Money</strong> BackGuarntee</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>24/7</strong> Support</li>
													</ul>
												</div>
												<div class="card-footer text-center">
													<button class="btn ripple btn-primary btn-block" onclick="select_portfolio('PCPP (Tier 1)','Tier 1','subscription_modal')">Select</button>
												</div>
											</div>
										</div>
									</div>

									<div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
										<div class="card overflow-hidden">
											<div class="text-center card-pricing pricing1">
												<div class="p-2 text-white bg-secondary fs-20">PCPP (Tier 2)</div>
												<div class="p-3 font-weight-normal mb-0"><span class="price">$51K</span></div>
												<div class="card-body text-center pt-0">
													<ul class="list-unstyled mb-0">
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Min: </strong>$51,000</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Max: </strong>$210,999</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Monthly APY:</strong> 25%s</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Money</strong> BackGuarntee</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>24/7</strong> Support</li>
													</ul>
												</div>
												<div class="card-footer text-center">
													<button class="btn ripple btn-secondary btn-block" onclick="select_portfolio('PCPP (Tier 2)','Tier 2','subscription_modal')">Select</button>

												</div>
											</div>
										</div>
									</div>

									<div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
										<div class="card overflow-hidden">
											<div class="text-center card-pricing pricing1">
												<div class="p-2 text-white bg-info fs-20">PCPP (Tier 3)</div>
												<div class="p-3 font-weight-normal mb-0"><span class="price">$211K</span></div>
												<div class="card-body text-center pt-0">
													<ul class="list-unstyled mb-0">
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Min: </strong>$211,000</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Max: </strong>$500,999</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Monthly APY:</strong> 30%</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Money</strong> BackGuarntee</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>24/7</strong> Support</li>
													</ul>
												</div>
												<div class="card-footer text-center">

													<button class="btn ripple btn-info btn-block" onclick="select_portfolio('PCPP (Tier 3)','Tier 3','subscription_modal')">Select</button>
													<!--end::Select-->
												</div>
											</div>
										</div>
									</div>

									<div class="col-sm-6 col-lg-3 mg-t-10 mg-lg-t-0">
										<div class="card overflow-hidden">
											<div class="text-center card-pricing pricing1">
												<div class="p-2 text-white bg-success fs-20">PCPP (Tier 4)</div>
												<div class="p-3 font-weight-normal mb-0"><span class="price">$501K</span></div>
												<div class="card-body text-center pt-0">
													<ul class="list-unstyled mb-0">
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Min: </strong>$501,000</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Max: </strong>none</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Monthly APY: </strong>35%+</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Money</strong> BackGuarntee</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>24/7</strong> Support</li>
													</ul>
												</div>
												<div class="card-footer text-center">
													<button class="btn ripple btn-success btn-block" onclick="select_portfolio('PCPP (Tier 4)','Tier 4','subscription_modal')">Select</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<div class="row row-sm">
	<div class="col-xl-12 col-lg-12">
		<div class="card custom-card">
			<div class="card-body">
				<div>

				</div>
				<div class="pricing-tabs">
					<div class=" text-center">
						<div class="pri-tabs-heading">
							<h2>IRA PICKS</h2>
							<p class="text-muted">If you need more info about our pricing, please check Pricing Guidelines</p>
						</div>
						<div class="col-xl-12">
							<div class=" ">
								<b>Terms & Conditions:</b> <br />
								0% Withdrawal Fee, <br />
								8% Management Fee (paid quarterly) <br />
								8% Upgrade Bonus, <br />
								10% Referral Bonus <br />
								30 Days Renewable Trading Contract.
							</div>
						</div>

						<div class="tab-content">
							<div class="tab-pane active" id="year">
								<div class="row row-sm">
									<div class="col-sm-6 col-lg-3">
										<div class="card overflow-hidden">
											<div class="text-center card-pricing pricing1">
												<div class="p-2 text-white bg-primary fs-20">Crypto IRA</div>
												<div class="p-3 font-weight-normal mb-0"><span class="price">$50K</span></div>
												<div class="card-body text-center pt-0">
													<ul class="list-unstyled mb-0">
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Min: </strong>$50,000</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Max: </strong>Unlimited</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Daily ROI: </strong>1.3%</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>Monthly ROI: </strong>26%</li>
														<li><i class="fe fe-check mr-2 text-success"></i><strong>APY:</strong> 312%</li>
													</ul>
												</div>
												<div class="card-footer text-center">
													<button class="btn ripple btn-primary btn-block" onclick="select_portfolio('Crypto IRA','Crypto IRA','subscription_modal')">Select</button>
												</div>
											</div>
										</div>
									</div>


								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- End Main Content-->

<!-- Main Footer-->
<?php include_once '../user_footer.php'; ?>

</body>

</html>