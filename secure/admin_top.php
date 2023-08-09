                        <div class="card mb-6 mb-xl-9">
                            <div class="card-body pt-9 pb-0">
                                <!--begin::Details-->
                                <div class="d-flex flex-wrap flex-sm-nowrap mb-6">
                                    <!--begin::Wrapper-->
                                    <div class="flex-grow-1">
                                        <!--begin::Head-->
                                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                            <!--begin::Details-->
                                            <div class="d-flex flex-column">
                                                <!--begin::Status-->
                                                <div class="d-flex align-items-center mb-1">
                                                    <a href="#" class="text-gray-800 text-hover-primary fs-2 fw-bold me-3">Admin Navigation</a>
                                                </div>
                                                <!--end::Status-->
                                            </div>
                                            <!--end::Details-->
                                        </div>
                                        <!--end::Head-->
                                        <!--begin::Info-->
                                        <div class="d-flex flex-wrap justify-content-start">
                                            <div class="row" id="admin">
                                                <?php 
                                                /////////////////////////////////
                                                    $sql='select count(id) from users';
                                                    $query = mysqli_query($db_conx, $sql);
                                                    $row = mysqli_fetch_row($query);
                                                    $tm = $row[0];
                                                    ////////////////////////
                                                    $sql="select count(id) from account where active='1' and balance>'0'";
                                                    $query = mysqli_query($db_conx, $sql);
                                                    $row = mysqli_fetch_row($query);
                                                    $fm = $row[0];
                                                    ////////////////////////
                                                    $sql="select count(id) from plan_account where active='1'";
                                                    $query = mysqli_query($db_conx, $sql);
                                                    $row = mysqli_fetch_row($query);
                                                    $ai = $row[0];
                                                    ////////////////////////
                                                    $sql="select count(id) from users where active='1'";
                                                    $query = mysqli_query($db_conx, $sql);
                                                    $row = mysqli_fetch_row($query);
                                                    $am = $row[0];
                                                    ////////////////////////
                                                    $sql="select count(id) from users where active='0'";
                                                    $query = mysqli_query($db_conx, $sql);
                                                    $row = mysqli_fetch_row($query);
                                                    $dm = $row[0];
                                                    //////////////// here we get the count of pending deposit and withdrawals
                                                    ////////// deposit
                                                    $dr = 0;
                                                    $sql="select count(id) from transactions where transaction_type='Deposit' and status='1'";
                                                    $query = mysqli_query($db_conx, $sql);
                                                    $row = mysqli_fetch_row($query);
                                                    $dr = $row[0];
                                                    ////////// withdrawal
                                                    $wr = 0;
                                                    $sql="select count(id) from transactions where transaction_type='Withdrawal' and status='1'";
                                                    $query = mysqli_query($db_conx, $sql);
                                                    $row = mysqli_fetch_row($query);
                                                    $wr = $row[0];
                                                    ///////////////////////////////////////////
                                                    $sql="select auto from update_control where id='1'";
                                                      $query = mysqli_query($db_conx, $sql);
                                                      $row = mysqli_fetch_row($query);
                                                      $match_control = $row[0];
                                                      $match_btn ='<form action="" method="post" class="form">
                                                        <input type="hidden" value="1" name="control" />
                                                        <button class="btn btn-primary btn-sm" type="submit" name="update_btn">Switch to AUTOmatic Update</button>
                                                      </form>';
                                                      if($match_control > 0){
                                                        $match_btn ='<form action="" method="post"  class="form">
                                                        <input type="hidden" value="0" name="control" />
                                                        <button class="btn btn-warning btn-sm" type="submit" name="update_btn">Switch to MANUAL Update</button>
                                                        </form>';
                                                      }
                                                      $two_fa_btn ='';
                                                      if($profile_id ==1){
                                                          /////////////////// 2fa ////////////////
                                                        ///////////////////////////////////////////
                                                        $sql="select two_fa from update_control where id='1'";
                                                          $query = mysqli_query($db_conx, $sql);
                                                          $row = mysqli_fetch_row($query);
                                                          $two_fa_control = $row[0];
                                                          $two_fa_btn ='<form action="../secure/index.php" method="post" class="form">
                                                            <input type="hidden" value="1" name="control_2fa" />
                                                            <button class="btn btn-primary btn-sm" type="submit" name="2fa_update_btn">Switch On 2fa</button>
                                                          </form>';
                                                          if($two_fa_control > 0){
                                                            $two_fa_btn ='<form action="../secure/index.php" method="post" class="form">
                                                            <input type="hidden" value="0" name="control_2fa" />
                                                            <button class="btn btn-danger btn-sm" type="submit" name="2fa_update_btn">Switch Off 2fa</button>
                                                            </form>';
                                                          }
                                                      }

                                                ?>
                                                <div class="col-lg-12 col-12 mb-1">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-12">
                                                            <?php echo $match_btn;?>
                                                        </div>
                                                        <div class="col-lg-3 col-12">
                                                            <?php echo $two_fa_btn;?>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-lg-12 col-12 mb-1">
                                                    <a href="../secure/all_members.php" class="btn btn-primary waves-effect waves-float waves-light btn-sm">All Members : <b><?php echo $tm;?></b></a>                        
                                                    <a href="../secure/funded_accounts.php" class="btn btn-success waves-effect waves-float waves-light btn-sm">Funded Accounts : <b><?php echo $fm;?></b></a>
                                                    <a href="../secure/active_members.php" class="btn btn-info waves-effect waves-float waves-light btn-sm">Active Members : <b><?php echo $am;?></b></a>
                                                    <a href="../secure/dormant_members.php" class="btn btn-danger waves-effect waves-float waves-light btn-sm">Dormant Members : <b><?php echo $dm;?></b></a>
                                                </div>
                                                <div class="col-lg-12 col-12">
                                                    <a href="../secure/" class="btn btn-info waves-effect waves-float waves-light btn-sm">
                                                        Home/Active Investments <b>(<?php echo $ai;?>)</b>
                                                    </a> 
                                                    <a href="../secure/deposit.php"  class="btn btn-success waves-effect waves-float waves-light btn-sm"> 
                                                        Deposit Request  <b>(<?php echo $dr;?>)</b>
                                                    </a> 
                                                    <a href="../secure/withdrawal.php"  class="btn btn-danger waves-effect waves-float waves-light btn-sm">
                                                        Withdrawal Request <b> (<?php echo $wr;?>)</b>
                                                    </a> 
                                                    <a href="../secure/?action=sendMail" class="btn btn-primary waves-effect waves-float waves-light btn-sm">
                                                        <b>E-mail sending portal</b>
                                                    </a> 
                                                    <a href="../secure/?action=wallet" class="btn btn-danger waves-effect waves-float waves-light btn-sm">
                                                        <b>Manage wallets</b>
                                                    </a>
                                                </div>
                                                <div id="side_nav" class="text-info">
                                                    <ul style="list-style-type:none;padding: 0; margin: 0">
                                                        <li>
                                                            <button class="btn btn-primary btn-sm" onclick="window.location='../secure/deposit.php#request_tab'" >Deposit Request Tab</button>
                                                        </li>
                                                        <li>
                                                            <button class="btn btn-secondary btn-sm" onclick="window.location='../secure/withdrawal.php#withdrawal_tab'" >Withdrawal Request Tab</button>
                                                        </li>
                                                        <li>
                                                            <button class="btn btn-primary btn-sm" onclick="window.location='../secure/#deposit_tab'" >Active Deposit Tab</button>
                                                        </li>
                                                        <li>
                                                            <button class="btn btn-secondary btn-sm" onclick="window.location='../secure/all_members.php#members_tab';" >All Members Tab</button>
                                                        </li>
                                                    </ul>

                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <!--end::Details-->
                                <div class="separator"></div>
                            </div>
                        </div>