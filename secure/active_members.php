<?php
include_once("../controller/dependent.php");
// Initialize any variables that the page might echo

$js='';
$sql = "SELECT auto FROM update_control where id='1' LIMIT 1";
$query = mysqli_query($db_conx, $sql);
if(mysqli_num_rows($query) < 1){
	/////////// add the control ////////////////
	$sql = "INSERT INTO update_control (auto)";
	$sql .= "VALUES('1')";
	$query = mysqli_query($db_conx, $sql);
        echo 'success';
}
if(isset($_POST['update_btn']) && isset($_POST['control'])){
    $control = $_POST['control'];
    $sql = "UPDATE update_control set auto='$control' WHERE  id='1' ";
    $query = mysqli_query($db_conx, $sql);
} 
///////////// Individual Update /////////
if(isset($_POST['function']) && isset($_POST['id']) && isset($_POST['value'])){
    $control = $_POST['value'];
    $id = $_POST['id'];
    $sql = "UPDATE account set auto_update='$control' WHERE  user_id='$id' limit 1 ";
    $query = mysqli_query($db_conx, $sql);
    if($query){ 
        echo 1;
        exit();
    }else{
        echo mysqli_error($db_conx);
        mysqli_close($db_conx);
        exit();
    }
} 

// variables for those who are to pay 
if(!isset($_SESSION[$site_cokie])){
    header("location: ../login");
    exit;
}else{
    if($user_type == 'member'){
        header("location: ../dashboard");
        exit;
    }
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
// Fetch the user row from the query above
while ($row = mysqli_fetch_array($user_query, MYSQLI_ASSOC)) {
    $f_n = $row['full_name'];
    $email = $row['email'];
    $tel = $row['phone'];
}
if($user_type == 'member'){
       header("location: ../dashboard");
    exit();
}
if($user_type !== 'member'){
//            include_once("functions.php");            
	$display = '';
                /// to get members on the site ////
                $display .= '<div class="col-xs-12 col-sm-12" id="members_tab">
                                <div class="card">
                                        <div class="card-header card-header-stretch">
                                            <div class="card-title">
                                                <h3>Active Site Members</h3>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-head-bg-info  table-striped table-bordered" cellspacing="0">
                                                    <thead >
                                                        <tr >
                                                            <th >S/n</th>
                                                            <th>Details</th>   
                                                            <th>More Details</th>
                                                            <th>Joined Date</th>
                                                            <th>Account Status </th>
                                                            <th>Action </th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot >
                                                        <tr >
                                                            <th >S/n</th>
                                                            <th>Details</th>  
                                                            <th>More Details</th>
                                                            <th>Joined Date</th>
                                                            <th>Account Status </th>
                                                            <th>Action </th>
                                                        </tr>
                                                    </tfoot>
                                                    <tbody>';
                $sql = "SELECT * FROM users where active='1'";
                $query = mysqli_query($db_conx, $sql);
                $numrows = mysqli_num_rows($query);
                if($numrows > 0){                    
                    $count = 0;
                    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
                        $count += 1; 
                        $id = $row['id'];
                        $unique1 = $row['unique_field'];
                        $uname = $row['username'];
                        $fname = $row['full_name'];
                        $email = $row['email'];
                        $user_type_two = $row['user_type'];
                        $phone = $row['phone'];
                        $sponsor = $row['sponsor'];
                        $country = $row['country'];
                        $active_account = $row['active'];
                        $date = $row['signup'];
                        $account_status = '<span class="badge badge-glow bg-success">active</span>';
                        $btn='<button class="btn mb-1  btn-sm btn-danger" id="'."$uname$count".'" '
                            . 'onclick="swap_admin_account(\''.$id.'\',\''.$uname.$count.'\',\'0\')" >De-activate Account</button>';
                        if($active_account ==='0'){
                            $account_status = '<span class="badge badge-glow bg-danger">in-active</span>';
                            $btn='<button class="btn mb-1 btn-sm btn-primary" id="'."$uname$count".'" '
                                . 'onclick="swap_admin_account(\''.$id.'\',\''.$uname.$count.'\',\'1\')" >Activate Account</button>';
                        }
                        $user_stats='';
                        if($user_type_two ==='admin'){
                            $user_stats='<span class="badge badge-glow bg-success">Admin</span>';
                        }
                        $user_admin_btn='';
                        if($user_type ==='admin'){
                            $user_admin_btn='<button class="btn btn-primary mb-1 btn-sm" id="'."$uname$count".'_swap" '
                            . 'onclick="admin_swap(\''.$id.'\',\''.$uname.$count.'\',\'1\')" >Make admin</button>';
                            if($user_type_two ==='admin'){
                                $user_admin_btn='<button class="btn btn-danger btn-sm mb-1" id="'."$uname$count".'_swap" '
                                . 'onclick="admin_swap(\''.$id.'\',\''.$uname.$count.'\',\'0\')" >Remove admin</button>';
                            }
                        }
                        $display .='<tr>
                            <td>'.$count.'</td>
                            <td> Name: <br/>'.$fname.' '.$user_stats.'<br/><br/>
                                E-mail: <br/>'.$email.'<br/><br/>
                                Phone: <br/>'.$phone.'<br/><br/></td>   
                            <td> Country: <br/>'.$country.'<br/><br/>
                                Sponsor: <br/>
                                <input type="text" id="'.$unique1.'_sponsor" value="'.$sponsor.'" style="" placeholder="Sponsor email " /> <br/>
                                    <button class="btn btn-success  mt-1 btn-sm"  id="'.$unique1.'_spo_btn"
                                onclick="update_sponsor(\''.$unique1.'\')" >
                                    Update</button>
                            </td>
                            <td>'.$date.'</td>
                            <td>'.$account_status.'</td>
                            <td>
                                '.$btn.'
                                '.$user_admin_btn.'<br/>
                                <a href="../secure/?action=sendMail&email='.$email.'&name='.$fname.'" class="btn mb-1 btn-sm btn-info">Send Message</a> 
                                <button class="btn btn-danger mb-1 btn-sm donate_btn"  id="'.$email.'_remove"
                                onclick="remove_member(\''.$email.'\')" >
                                    Delete Member</button><br/>
                                <a href="member_dashboard.php?user='.$unique1.'" target="_blanc" class="btn btn-primary btn-sm">View Dashboard</a>
                            </td>
                        </tr>';
//                        <a href="member_dashboard.php?user='.$unique.'" target="_blanc" class="btn btn-primary">View Dashboard</a>
                    }
                }else{
                $display .='<tr>
                                <td></td> <td colspan="6"> This list is empty for now. .</td>
                        </tr>';
                }
                $display .= '</tbody>
                    </table></div></div>
                    <div class="card-footer small text-muted"></div></div></div>';
}
?>
<?php 
/////////////////////////////////////////////
    $title ='Active Members | '.$site_name;
    $title2 ='Active Members';
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
    $suppt = ''; 
    include_once '../user_header.php';
/////////////////////////////////////////////
?>

<?php include_once 'admin_body.php';?>