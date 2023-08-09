<?php
function send_mail($to,$from,$subject,$body){
    global $site_link,$site_name;
    $message ='<img src="https://'.$site_link.'/assets/images/logo.png" alt="'.$site_name.'" style="width:200px" class="pull-left img-responsive"/><br/>'.$body.'<br/><br/>                
    Kind Regards,<br/> <a href="https://'.$site_link.'" target="_blanc">'.$site_name.'</a><br/>&nbsp;&nbsp;';
    $headers = "From: $from\r\n";    
    $headers .= "Reply-To: $from \r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\n";
    $headers .= "X-Mailer: PHP \r\n";
    mail($to, $subject, $message, $headers);
}
function round_up ( $value, $precision ){ 
    $pow = pow ( 10, $precision ); 
    return ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow; 
} 

////////////////// time ago function /////////////////
$timestamp_convert_original='';
class convertToAgo{
    function convert_datetime($str){
        list($date2,$time2) = explode(' ',$str);
        list($year2,$month2,$day2) = explode('-',$date2);
        list($hour2,$minute2,$second2) = explode(':',$time2);
        $timestamp = mktime($hour2,$minute2,$second2,$month2,$day2,$year2);
        return "$timestamp|$str";
    }    
    function makeAgo($timestamp){
        $kaboom = explode("|", $timestamp);
        $timestamp_ago =$kaboom[0];
        $original_timestamp = end($kaboom);
        global $timestamp_convert_original;
        $timestamp_convert_original ='';
        check_time($timestamp,$original_timestamp);
        if($timestamp_convert_original !=''){
            return $timestamp_convert_original;
        }  else {
            $diff = time() - $timestamp_ago;
            $periods = array('sec','min','hr','day','week','month','year','decade');
            $lengths = array('60','60','24','7','4.35','12','10');
            for($j = 0;$diff >= $lengths[$j];$j++){
                $diff /= $lengths[$j];
            }
            $diff = round($diff);
            if($diff != 1) {$periods[$j].='s';}
            $text = "$diff $periods[$j] ago";
            return $text;
        }        
    }
}
function check_time($timestamp,$original_timestamp){
    global $timestamp_convert_original,$gmt,$yr,$hour;
    $new_time = date("Y-m-d H:i:s", strtotime('-24 hours'));
    list($date1,$time1) = explode(' ',$new_time);
    list($year1,$month1,$day1) = explode('-',$date1);
    list($hour1,$minute1,$second1) = explode(':',$time1);
    $new_time = mktime($hour1,$minute1,$second1,$month1,$day1,$year1);
    if($timestamp < $new_time){
        list($date,$time) = explode(' ',$original_timestamp);
        list($year,$month,$day) = explode('-',$date);
        list($hour,$minute,$second) = explode(':',$time);
        $months_alphbet = array('January','February','March','April','May','June','July','August','September','October','November','December');
        $month -=1;
        for($j = 0;$j <= 11;$j++){
            
            if($j == $month) {
                $month=$months_alphbet[$j];
                $gmt='am';
                $yr='';
                convert_gmt($hour,$gmt,$year,$year1,$yr);                
                $timestamp_convert_original = "$month $day at $hour:$minute $gmt  $yr";
            }
        }
    }
}
//function convert_month($month,$hour,$minute,$day,$months_alphbet){
//    for($j = 0;$j <= 12;$j++){
//        if($j == $month) {
//            $month=$months_alphbet[$j];
//            $gmt='am';
//            convert_gmt($hour,$gmt);
//            $timestamp= "$month $day at $hour:$minute$gmt";
//            return $timestamp;
//        }
//    }
//}
function convert_gmt($hour,$gmt,$year,$year1,$yr){    
    global $gmt,$yr,$hour;
    if($hour > 12){
        $hour-=12;
        $gmt='pm';
    }
    if($year < $year1){
        $yr = $year;            
    }
}
////////////// Time ago function //////////////////
////////////// Add notification //////////////////
function add_notification($db_conx,$note_title,$note,$id,$unique,$type){   
    
    $new_time = date("Y-m-d H:i:s");
    $sql = "INSERT INTO notifications (unique_code,user_id,initiator,title,note,type,date_time)";
    $sql .= "VALUES('$unique','$id','Admin','$note_title','$note','$type','$new_time')";
    $query = mysqli_query($db_conx, $sql);
    return $query;
}
