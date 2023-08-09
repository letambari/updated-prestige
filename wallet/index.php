<?php
include_once("../controller/dependent.php");
// variables for those who are to pay 
//////// Script to update profile ////////////
/////////////////////////////////////////////////////////////////
if( isset($_GET['update_wallet']) && isset($_POST['wallet'])){
    // GATHER THE POSTED DATA INTO LOCAL VARIABLES
    $wallet = mysqli_real_escape_string($db_conx, lcfirst($_POST['wallet']));
//    $code = mysqli_real_escape_string($db_conx, ucwords($_POST['code']));
    // DUPLICATE DATA CHECKS  EMAIL
    $sql = "SELECT id,email FROM users WHERE id='$profile_id' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $user_check = mysqli_num_rows($query);
    if($wallet === "" ){
        echo 2;
        mysqli_close($db_conx);
        exit();
    }else if(strlen($wallet) < 26 || strlen($wallet) > 45){
        echo 3;
        exit();
    }else if($wallet[0] != 1 && $wallet[0] != 3 && substr($wallet, 0, 2) != "bc"){
        echo 3;
        mysqli_close($db_conx);
        exit();
    }else if ($user_check < 0){ 
        echo 4;
        mysqli_close($db_conx);
        exit();
    }else{ 
//        $sql = "SELECT pin FROM random_string WHERE user_id='$profile_id' LIMIT 1";
//        $query = mysqli_query($db_conx, $sql);
//        $row = mysqli_fetch_row($query);
//        $db_pin = $row[0];
//        if($code != $db_pin){
//            echo 5; //echo "Log In failed. Invalid Username / Password.";
//            mysqli_close($db_conx);
//            exit();
//        }
        $new_time = date("Y-m-d H:i:s");
        $query = mysqli_query($db_conx, $sql);
        $sql = "SELECT wallet_address FROM wallets WHERE user_id='$profile_id' and wallet_name='bitcoin' LIMIT 1";
        $query = mysqli_query($db_conx, $sql);
        /////////////////////////////////////////////////////////////////////////////
        $sql = "INSERT INTO wallets (user_id,wallet_name,wallet_address, date_added)";
        $sql .= "VALUES('$profile_id','bitcoin','$wallet','$new_time')";            
        if (mysqli_num_rows($query) > 0){ 
            $sql = "UPDATE wallets SET wallet_address='$wallet' where  user_id='$profile_id' and wallet_name='bitcoin' LIMIT 1";
        }
        $query = mysqli_query($db_conx, $sql);
        echo 1;
        mysqli_close($db_conx);
        exit(); 
    }
}
if(!isset($_SESSION[$site_cokie])){
    header("location: ../login");
    exit;
}else{
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
?><?php 
    $title ='Account Wallet | '.$site_name;
    $title2 ='Account Wallet';
    $keyword ='';
    $discription = '';   
    $dash = '';
    $profil = '';
    $dep = '';
    $invest = '';
    $with = '';
    $histroy = '';
    $price = '';
    $wallet_p = 'active';
    $referal = '';
    $support = ''; 
    include_once '../user_header.php'; 
?>
<?php if($wallet_address==''){
    ?>
<script>
    alert('Enter a valid bitcoin wallet address to continue');
</script>
<?php
} ?>
                <div class="content-body">
                    <!-- Dashboard Ecommerce Starts -->
                    <section id="dashboard-ecommerce">
                        
                        <div class="row match-height">
                            <div class="col-md-8 col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Account Wallet</h4>
                                    </div>
                                    <div class="card-body">
                                        
                                        <form class="form form-vertical" onsubmit="return false;" method="POST" id="wallet_form">
                                            <p class="br-section-text">
                                                Enter your Bitcoin wallet address to enable you receive payments.
                                            </p>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="mb-1">
                                                        <label class="form-label" for="email">Email:</label>
                                                        <input type="email" id="email" name="email" class="form-control"
                                                        placeholder="Email" value="<?php  echo $email;?>"  readonly="" />
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="mb-1">
                                                        <label class="form-label" for="amount">Wallet Address :</label>
                                                        <input type="text" class="form-control" placeholder="wallet address"  name="wallet"
                                                        value="<?php echo $wallet_address;?>"   id="wallet" required="" />
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="mb-1">
                                                        <div class="col-sm-12 text-center" id="wallet_status"></div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-primary me-1" id="wallet_btn" name="wallet_btn" 
                                                        onclick="add_wallet_acct()">Submit</button>
                                                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </section>
                    <!-- Dashboard Ecommerce ends -->
                </div>
    <?php include_once '../user_footer.php'; ?>
</body>

</html>