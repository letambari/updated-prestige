<?php
session_start();
// Set Session data to an empty array
$_SESSION = array();
// Expire their cookie files
if( isset($_COOKIE['__prest__'])) {
   setcookie('__prest__', '', strtotime( '-5 days' ),'/');
}
session_unset(); 
// Destroy the session variables
session_destroy();
// Double check to see if their sessions exists
if(isset($_SESSION['__prest__'])){
   echo 'Error: Logout Failed';
   exit();
} else {
  header("location: ../login");
   exit();
}