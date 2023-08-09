<?php 
include_once("../wp-includes/php_/config.php");
if(isset($_GET['action']) && $_GET['action'] =='verification' && isset($_GET['encryption']) && isset($_GET['un'])){        
        $unique = mysqli_real_escape_string($db_conx,$_GET['un']);
        if(strlen($unique) < 9){
            echo '<div class="row text-center">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="request-form text-warning" style="padding:20px 0px 20px 10px; font-size:22px">
                                <p>Activation Error!!!<br/> Click on the link in the email sent to you.</p>
                        </div>	
                    </div>
            </div> ';
            exit();
        }else{
            $sql = "SELECT id,verified,email FROM users WHERE unique_field='$unique' LIMIT 1";
            $query = mysqli_query($db_conx, $sql);
            $numrows = mysqli_num_rows($query);
            if($numrows < 1){
                echo '<div class="row text-center">
                        <div class="col-md-6 col-md-offset-3">
                                <div class="request-form text-warning" style="padding:40px 0px 50px 10px; font-size:24px">
                                        <p>Activation Error!!!<br/> Account does not exist, please click on the link sent to your e-mail.</p>
                                </div>	
                        </div>
                </div>';
                exit();
            }else {
                $row = mysqli_fetch_row($query);
                $id = $row[0];
                $verified = $row[1];
                 $email = $row[2];
                if($verified > 0 ){
                    echo "This link has expired. Account already verified.";
                    echo '<a href="../login" class="btn btn-primary"  >
                                Login Here
                            </a>';
                    exit();
                }
                $sql = "UPDATE users SET verified='1' WHERE id='$id' LIMIT 1";
                $query = mysqli_query($db_conx, $sql);
                $_SESSION[$site_cokie] = $unique;
                setcookie($site_cokie, $unique, strtotime( '+2 days' ), "/", "", "", TRUE);
                header("location: ../dashboard");
            }
        }
}
echo '<div class="row text-center">
            <div class="col-md-6 col-md-offset-3">
                    <div style="padding:40px 0px 50px 10px; font-size:24px; color:red">
                            <p>Access Denied</p>
                    </div>	
            </div>
    </div>';