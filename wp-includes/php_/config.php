<?php
//////////// variable initialization
$site_cokie = '__prest__';
global $site_name,$site_link;
$site_name = 'Prestige Capital';
$site_name_abbr = 'Prestige Capital';
$site_name_abbr2 = 'Prestige Capital';
$site_link = 'prestigecapital.io';
$site_address_full = "4 St. Paul's Churchyard, London, England, EC4M 8AY";
$site_address = "4 St. Paul's Churchyard, London";
$site_address2 = '1500 Franklin Street, San Francisco, CA 94109';
$site_address3 = '';
$site_address4 = '';
$site_phone = '';
$site_reg_num = '';
$site_year = 2018;
$site_whatsapp = '';
$site_support = 'support@prestigecapital.io';
$ver = 1.3211;
$live_chat = '';
//$live_chat = '';
include_once("check_login_status.php");
include_once("functions.php");
include_once("randStrGen.php");
include_once("template_country_list.php");
$g_investors = 500;
$sql = "SELECT count(id) FROM users ";
$query = mysqli_query($db_conx, $sql);
if($query){
    if (mysqli_num_rows($query) > 0){ 
        $row = mysqli_fetch_row($query);
        $u_count = $row[0];
        $g_investors += ($u_count * 9);
    }
}