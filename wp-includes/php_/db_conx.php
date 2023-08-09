<?php 
/* 1 die() will exit the script and show an error if something goes wrong
 * with the "connect" or "select" functions.
 */
//db host name
    $db_host = "127.0.0.1";
//db user name
    $db_username = "root";
//db password
    $db_password = "";
//db name
    $db_name = "prestige";
    
//this is the main connection
    global $db_conx;
    $db_conx = mysqli_connect($db_host, $db_username, $db_password);
    mysqli_select_db($db_conx,$db_name) or die("no database");