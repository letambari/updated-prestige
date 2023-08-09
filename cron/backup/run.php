<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set('Africa/Lagos');
//if (isset($_SERVER['REMOTE_ADDR'])) die('Permission denied.');
include_once("../../wp-includes/php_/functions.php");
include_once("../../wp-includes/php_/db_conx.php");
/////////////////// here we check to delete accounts that have been suspended for seven days //////////////
//recipient
$to = 'techbackup1290@gmail.com';

//sender
$from = 'support@innovestltd.com';
$fromName = 'Innovest Limited';

//email subject
$subject = 'Files Backup'; 

//attachment file path
$file = "files/files.tar.gz";

//email body content
$htmlContent = '<h1>PHP Email with Attachment by iPartenrs</h1>
    <p>This email has sent from PHP script with attachment.</p>';

//header for sender info
$headers = "From: $fromName"." <".$from.">";

//boundary 
$semi_rand = md5(time()); 
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 

//headers for attachment 
$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 

//multipart boundary 
$message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" .
"Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n"; 

//preparing attachment
if(!empty($file) > 0){
    if(is_file($file)){
        $message .= "--{$mime_boundary}\n";
        $fp =    @fopen($file,"rb");
        $data =  @fread($fp,filesize($file));

        @fclose($fp);
        $data = chunk_split(base64_encode($data));
        $message .= "Content-Type: application/octet-stream; name=\"".basename($file)."\"\n" . 
        "Content-Description: ".basename($file)."\n" .
        "Content-Disposition: attachment;\n" . " filename=\"".basename($file)."\"; size=".filesize($file).";\n" . 
        "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
    }
}
$message .= "--{$mime_boundary}--";
$returnpath = "-f" . $from;

//send email
$mail = @mail($to, $subject, $message, $headers, $returnpath); 
//email sending status
echo $mail?"<h1>Mail sent.</h1>":"<h1>Mail sending failed.</h1>";

