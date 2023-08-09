<?php
include_once("../wp-includes/php_/config.php");
if(isset($_SESSION[$site_cokie])){
    // Select the member from the users table
    $sql = "SELECT * FROM users WHERE id='$profile_id' LIMIT 1";
    $user_query = mysqli_query($db_conx, $sql);
    while ($row = mysqli_fetch_array($user_query, MYSQLI_ASSOC)) {
        $f_n = $row['full_name'];
        $username = $row['username'];
        $email = $row['email'];
        $email_main = $email;
        $tel = $row['phone'];
        $country = $row['country'];
        $avatar = $row['avatar'];
        $active = $row['active'];
        $signup = $row['signup'];
        $notescheck = $row['notescheck'];
        ///////////////////////
        $timeAgoObject = new convertToAgo; // Create an object for the time conversion functions
        // Query your database here and get timestamp
        $convertedTime = ($timeAgoObject -> convert_datetime($signup)); // Convert Date Time
        $signup = ($timeAgoObject -> makeAgo($convertedTime)); // Then convert to ago time
        /////////////////////////////////
        $active_dis = '<span class="badge badge-success">active</span>';
        if($active == '0'){
            $active_dis = '<span class="badge badge-danger">in-active</span>';
        }
        $img_link = '../wp-includes/users/'.$email.'/'.$avatar;
        $img = '<img  src="../wp-includes/users/'.$email.'/'.$avatar.'" alt="'.$f_n.'">';
        $img2 = '<img alt="'.$f_n.'"  class="bg-light w-100 h-100 rounded-circle avatar-lg img-thumbnail"  src="../wp-includes/users/'.$email.'/'.$avatar.'" />';
        $img3 = '<img alt="'.$f_n.'" src="../wp-includes/users/'.$email.'/'.$avatar.'" />';
        if($avatar == ''){
            $img_link = '../assets/avatar.jpg';
            $img = '<img src="../assets/avatar.jpg"  alt="'.$f_n.'" />';
            $img2 = '<img alt="'.$f_n.'"  class="bg-light w-100 h-100 rounded-circle avatar-lg img-thumbnail" src="../assets/avatar.jpg" />';
            $img3 = '<img alt="'.$f_n.'" src="../assets/avatar.jpg" />';
        }
    }
    $Note = '';
    if($user_type == 'admin'){
        $Note = '<div class="menu-item ">
                <!--begin:Menu link-->
                <a class="menu-link active" href="../secure/">
                    <span class="menu-icon">
                        <i class="fa fa-gears"></i>
                        <!--end::Svg Icon-->
                    </span>
                    <span class="menu-title">Admin</span>
                </a>       
                <!--end:Menu link-->
            </div>';
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    /////// here we get total deposited funds and their profit so far//////////
    $sql = "SELECT * FROM account WHERE user_id='$profile_id'";
    $query = mysqli_query($db_conx, $sql);
    $row = mysqli_fetch_array($query, MYSQLI_ASSOC);
    $active = $row['active'];
    $reinvest = $row['reinvest'];
    $currency_main = explode("|", $row['currency']);
    $currency = $currency_main[0];
    $symbol = end($currency_main);
    $total_withdraw = $row['total_withdrawal'];
    $deposited = $row['total_deposite'];
    $l_withdraw = $row['last_withdrawal'];
    $l_deposited = $row['last_deposite'];
    $profit = $row['profit'];
    $bonus = $row['bonus'];
    $current_week_profit = $row['current_week_profit'];
    $total_bal = $row['balance'];
    $inactive_withdraw = 'none';
    $active_withdraw = '';
    $acct_status = '<span class="badge badge-success">active</span>';
    if($active == '0'){
        $acct_status = '<span class="badge badge-danger">in-active</span>';
        $inactive_withdraw = '';
        $active_withdraw = 'none';
    }
//    $avg_profit = $daily_rate * $deposited;
//    if($compound ==1 ){
//        $avg_profit = $daily_rate * $total_bal;
//    }
    //////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////
    //////////////////////////////////////////Referal Bonus/////////////////////////////////////////////////////
    $gen_revenue = $total_withdraw + $profit;
    ///////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    /////// here we get total deposited funds and their profit so far//////////
    $invested = 0;$t_balance = 0;$t_profit = 0;$t_withdraw = 0;$investment_count = 0;
    $cap = 0; $psp = 0; $pcpp =0;
    $sql = "SELECT * FROM plan_account WHERE user_id='$profile_id'";
    $query = mysqli_query($db_conx, $sql);
    if(mysqli_num_rows($query) > 0){
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            $investment_count += 1;
            $p_profit = $row['profit'];
            $p_total_withdrawal = $row['total_withdrawal'];
            $p_total_deposit = $row['total_deposite'];
            $p_balance = $row['balance'];
            ////////
            $invested += $p_total_deposit;
            $t_profit += $p_profit;
            $t_withdraw += $p_total_withdrawal;
            $t_balance += $p_balance;
            /////////////
            $plan_main = explode(" ", $row['plan']);
            $p_plan = $plan_main[0];
            if($p_plan == 'PCAP'){
                $cap += 1;
            }else if($p_plan == 'PSP'){
                $psp += 1;
            }else if($p_plan == 'PCPP'){
                $pcpp += 1;
            }
        }
    }
    //////////////////////////////////////////Referal Bonus/////////////////////////////////////////////////////
    $sql = "SELECT count(id),referal_amount,total_referal_amount,referal_percent FROM referral WHERE user_id='$profile_id' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $row = mysqli_fetch_row($query);
    $referal_hits = $row[0];
    $referal_amount = $row[1];
    $referal_T_amount = $row[2];
    $referal_rate = $row[3] * 100;
    //////////////////////////////////////////Total transactions/////////////////////////////////////////////////////
    $trans_count = 0;
    $sql = "SELECT count(id) FROM transactions WHERE user_id='$profile_id' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    if(mysqli_num_rows($query) > 0){
        $row = mysqli_fetch_row($query);
        $trans_count = $row[0];
    }
    /////////////////////////////
    //////////////////////////////////////////Wallet address/////////////////////////////////////////////////////
    $sql = "SELECT wallet_address FROM wallets WHERE user_id='$profile_id' and wallet_name='bitcoin' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $wallet_address ='';
    if (mysqli_num_rows($query) > 0){ 
        $row = mysqli_fetch_row($query);
        $wallet_address = $row[0];
    }
    /////////////////////////////////
    // two factor authentication check
    //////////////////////////////////////////Referal Bonus/////////////////////////////////////////////////////
    $sql = "SELECT two_fa FROM update_control WHERE id='1' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $row = mysqli_fetch_row($query);
    $two_fa_control = $row[0];
    $two_fa_display="style='display:none'";
    if($two_fa_control ==1){
        $two_fa_display="";
    }
    //////////////////////////////////////////////////////////////////////////////
            ///////////////////////////////////////////////////////////////////////////////////
            ////////////////////////////////////////////////////
    ////////////// here we get all active investment history
    include_once("portfolio_script.php");
            ////////////////////////////////////////////////////
    ////////////// here we get all their Earning transactions history
        $sql = "SELECT * FROM transactions WHERE user_id='$profile_id' AND transaction_type='Profit' order by id desc";
        $query = mysqli_query($db_conx, $sql);
        // Now make sure that user exists in the table
        $numrows = mysqli_num_rows($query);
        if($numrows < 1){
            $pro_transaction ='<tr>
                            <td colspan="5"> <strong >No record(s)..</strong></td>
                          </tr>';
        }else {
        $pro_transaction='';
        $count=0;
        // Fetch the user row from the query above
        while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            $count +=1;
            $transaction_id = $row['unique_field'];
            $transaction_type = preg_replace('#[^a-zA-Z]#i', ' ', $row['transaction_type']);
            $payment_details = $row['payment_details'];
            $payment_method = $row['payment_method'];
            $amount = $row['amount'];
            $status = $row['status'];
            $created_at = $row['created_at'];
            $status_dis = '<span class="badge badge-success">Completed</span>';
            if($status == '0'){
                $status_dis = '<span class="badge badge-danger">Cancelled</span>';
            }elseif($status =='1'){
                $status_dis = '<span class="badge badge-light-warning">Processing</span>';
            }
            $pro_transaction .= '<tr>
                        <td>'.$count.'</td>
                        <td>'.$transaction_id.'</td>
                        <td>'.$transaction_type.'</td>
                        <td>'.$symbol.number_format($amount,2).' - '.ucwords($payment_method).'</td>                        
                        <td>'.$status_dis.'</td>
                        <td> '.date('D d, M Y', strtotime($created_at)).'</td>         
                      </tr>';
        }
    }
    ////////////// here we get all their DEPOSIT transactions history
        $sql = "SELECT * FROM transactions WHERE user_id='$profile_id' AND transaction_type='Deposit' order by id desc";
            $query = mysqli_query($db_conx, $sql);
        // Now make sure that user exists in the table
        $numrows = mysqli_num_rows($query);
        if($numrows < 1){
            $dep_transaction ='<tr>
                            <td colspan="5"> <strong >No deposit record(s)..</strong></td>
                          </tr>';
        }  else {
        $dep_transaction='';
        $count=0;
        // Fetch the user row from the query above
        while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            $count +=1;
            $transaction_id = $row['unique_field'];
            $transaction_type = preg_replace('#[^a-zA-Z]#i', ' ', $row['transaction_type']);
            $payment_details = $row['payment_details'];
            $payment_method = $row['payment_method'];
            $amount = $row['amount'];
            $status = $row['status'];
            $created_at = $row['created_at'];
            $status_dis = '<span class="badge badge-success">Completed</span>';
            if($status == '0'){
                $status_dis = '<span class="badge badge-danger">Cancelled</span>';
            }elseif($status =='1'){
                $status_dis = '<span class="badge badge-light-warning">Processing</span>';
            }
            $dep_transaction .= '<tr>
                        <td>'.$count.'</td>
                        <td>'.$transaction_id.'</td>
                        <td>'.$transaction_type.'</td>
                        <td>'.$symbol.number_format($amount,2).' - '.ucwords($payment_method).'</td>                        
                        <td>'.$status_dis.'</td>
                        <td> '.date('D d, M Y', strtotime($created_at)).'</td>         
                      </tr>';
        }
    }
    ////////////////////////////////////////////////////////
    ////////////// here we get all their WITHDRAWED transactions history
        $sql = "SELECT * FROM transactions WHERE user_id='$profile_id' AND transaction_type='Withdrawal' order by id desc";
        $query = mysqli_query($db_conx, $sql);
        // Now make sure that user exists in the table
        $numrows = mysqli_num_rows($query);
        if($numrows < 1){
            $pay_transaction ='<tr>
                            <td colspan="6"> <strong >No pay-out record(s)..</strong></td>
                          </tr>';
        }  else {
        $pay_transaction='';
        $count=0;
        // Fetch the user row from the query above
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            $count +=1;
            $transaction_id = $row['unique_field'];
            $transaction_type = preg_replace('#[^a-zA-Z]#i', ' ', $row['transaction_type']);
            $payment_details = $row['payment_details'];
            $payment_method = $row['payment_method'];
            $amount = $row['amount'];
            $status = $row['status'];
            $created_at = $row['created_at'];
            $status_dis = '<span class="badge badge-success">Completed</span>';
            if($status == '0'){
                $status_dis = '<span class="badge badge-danger">Cancelled</span>';
            }elseif($status =='1'){
                $status_dis = '<span class="badge badge-light-warning">Processing</span>';
            }
            $pay_transaction .= '<tr>
                        <td>'.$count.'</td>
                        <td>'.$transaction_id.'</td>
                        <td>'.$transaction_type.'</td>
                        <td>'.$symbol.number_format($amount,2).'</td>  
                        <td>'.ucwords($payment_method).'</td>  
                        <td>'.$status_dis.'</td>
                        <td> '.date('D d, M Y', strtotime($created_at)).'</td>         
                      </tr>';
        }
    }////////////////////////////////////////////////////////
    ////////////// here we get all their Portfolio Purchase transactions history
        $sql = "SELECT * FROM transactions WHERE user_id='$profile_id' AND transaction_type='Portfolio Purchase' order by id desc";
        $query = mysqli_query($db_conx, $sql);
        // Now make sure that user exists in the table
        $numrows = mysqli_num_rows($query);
        $purchase_transaction ='<tr>
                            <td colspan="6"> <strong >No record(s)..</strong></td>
                          </tr>';
        if($numrows > 0) {
        $purchase_transaction='';
        $count=0;
        // Fetch the user row from the query above
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            $count +=1;
            $transaction_id = $row['unique_field'];
            $plan_unique_id = $row['plan_unique'];
            $transaction_type = preg_replace('#[^a-zA-Z]#i', ' ', $row['transaction_type']);
            $payment_details = $row['payment_details'];
            $payment_method = $row['payment_method'];
            $amount = $row['amount'];
            $status = $row['status'];
            $created_at = $row['created_at'];
            $status_dis = '<span class="badge badge-success">Completed</span>';
            if($status == '0'){
                $status_dis = '<span class="badge badge-danger">Cancelled</span>';
            }elseif($status =='1'){
                $status_dis = '<span class="badge badge-light-warning">Processing</span>';
            }
            $purchase_transaction .= '<tr>
                        <td>'.$count.'</td>
                        <td>'.$transaction_id.'</td>
                        <td>'.$plan_unique_id.'</td>
                        <td>'.$transaction_type.'</td>
                        <td>'.$symbol.number_format($amount,2).' </td>
                        <td>'.ucwords($payment_method).'</td>
                        <td>'.$status_dis.'</td>
                        <td> '.date('D d, M Y', strtotime($created_at)).'</td>         
                      </tr>';
        }
    }
    ////////////// here we get all their transactions history
        ////////////// here we get all their transactions history
        $sql = "SELECT * FROM transactions WHERE user_id='$profile_id'  order by id desc";
            $query = mysqli_query($db_conx, $sql);
        // Now make sure that user exists in the table
        $numrows = mysqli_num_rows($query);
        if($numrows < 1){
            $transaction ='<tr>
                            <td colspan="6"> <strong >No record(s)..</strong></td>
                          </tr>';
            $dash_transaction ='<tr>
                            <td colspan="6"> <strong >No record(s)..</strong></td>
                          </tr>';
        }  else {
        $transaction='';
        $count=0;
        // Fetch the user row from the query above
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            $count +=1;
            $transaction_id = $row['unique_field'];
            $transaction_type = preg_replace('#[^a-zA-Z]#i', ' ', $row['transaction_type']);
            $payment_details = $row['payment_details'];
            $payment_method = $row['payment_method'];
            $amount = $row['amount'];
            $status = $row['status'];
            $created_at = $row['created_at'];
            $status_dis = '<span class="badge badge-success">Completed</span>';
            if($status == '0'){
                $status_dis = '<span class="badge badge-danger">Cancelled</span>';
            }elseif($status =='1'){
                $status_dis = '<span class="badge badge-light-warning">Processing</span>';
            }
            $transaction .= '<tr>
                        <td>'.$count.'</td>
                        <td>'.$transaction_id.'</td>
                        <td>'.$transaction_type.'</td>
                        <td>'.$symbol.number_format($amount,2).'</td> 
                        <td>'.ucwords($payment_method).'</td> 
                        <td>'.$status_dis.'</td>
                        <td> '.date('D d, M Y', strtotime($created_at)).'</td>         
                      </tr>';
            if($count < 6){
                $dash_transaction = $transaction;
            }
        }
    }
    ///////////////////////////////////
    ////Notification
    /// to get count of new notifications
    $n_count = 0;
    $sql = "SELECT count(id) FROM notifications WHERE user_id='$profile_id' and did_read='0' and date_time>'$notescheck'";
    $query = mysqli_query($db_conx, $sql);
    if(mysqli_num_rows($query) > 0){
        $row = mysqli_fetch_row($query);
        $n_count = $row[0];
    }
    /////////////////////////
    $notification_list = "";
    $sql = "SELECT * FROM notifications WHERE user_id LIKE BINARY '$profile_id' and type LIKE BINARY 'b' ORDER BY date_time desc limit 4";
    $query = mysqli_query($db_conx, $sql);
    $numrows = mysqli_num_rows($query);
    if($numrows < 1){
        $notification_list ='<a class="d-flex" href="#">
                                    <div class="list-item d-flex align-items-start">                                        
                                        <div class="list-item-body flex-grow-1">
                                            <small class="notification-text"> You do not have any notifications</small>
                                        </div>
                                    </div>
                                </a>' ;
    } else {
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
            $noteid = $row["id"];
            $initiator = $row["initiator"];
            $app_title = $row["title"];
            $note = $row["note"];
            $did_read = $row["did_read"];
            $date_time = $row["date_time"];
            $date_time = date("h:ia M d, Y", strtotime($date_time));
            $color = '';
            if($did_read == 0){
                $color = 'color: #6D62E4;';
            }
            $notification_list .= '<a class="d-flex" href="../notification#'.$noteid.'" >
                                    <div class="list-item d-flex align-items-start">                                        
                                        <div class="list-item-body flex-grow-1">
                                            <p class="media-heading" style="'.$color.'"><span class="fw-bolder">'.$app_title.' 🎉</span> '.$date_time.'</p>
                                            <small class="notification-text"> 
                                                '.$note.'
                                            </small><br/>
                                            <small class=" text-danger mt-2" style="float: left;">
                                                <i>
                                                    Note: Please be aware of phishing sites and always make sure you 
                                                    are visiting the official <b>'.$site_link.'</b> website when entering sensitive data. 
                                                </i>
                                            </small> 
                                        </div>
                                    </div>
                                </a>';
        }
    }
    ///////////////////////////
    include_once("graph_values.php");
//    include_once("portfolio_script.php");
}
