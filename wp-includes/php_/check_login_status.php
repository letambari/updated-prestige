<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
//date_default_timezone_set('Europe/London');
session_start();
include_once("db_conx.php");
// Files that inculde this file at the very top would NOT require 
// connection to database or session_start(), be careful.
// Initialize some vars
// User Verify function
$profile_id = '';
$Note ='';
$user_type = "";
$full_n="";
$log_email ='';
function evalLoggedUser($conx,$unique){
	global $profile_id,$user_type,$full_n,$verify,$log_email;
    $sql = "SELECT id,full_name,user_type,verified,email FROM users WHERE unique_field='$unique'  LIMIT 1";
    $query = mysqli_query($conx, $sql);
    $numrows = mysqli_num_rows($query);
    if($numrows > 0){
        $row = mysqli_fetch_row($query);
        $profile_id =$row[0];
        $full_n = $row[1];
        $user_type = $row[2];$verify = $row[3];$log_email = $row[4];
  //      if($full_n == '') $full_n = 'Anonymous';
        
        //$verify_text = '<div class=" bg-danger" id="veri_info" >your account has not been verified, <a href="http://localhost/nextfund/account/verify">click here to verify</a></div>';
        return true;
    }        
}
if(isset($_SESSION[$site_cokie])){
    $log_unique = mysqli_real_escape_string($db_conx,$_SESSION[$site_cokie]);
    // Verify the user
    $user_ok = evalLoggedUser($db_conx,$log_unique);
} else if(isset($_COOKIE[$site_cokie])){
    $_SESSION[$site_cokie] = mysqli_real_escape_string($db_conx,$_COOKIE[$site_cokie]);
    $log_unique = mysqli_real_escape_string($db_conx,$_SESSION[$site_cokie]);
    // Verify the user
    $user_ok = evalLoggedUser($db_conx,$log_unique);
	
}