<?php
function randStrGen($len){
	$result = "";
    $chars = "012CDab34GH56ijJK78RSpq9ABc012deEFI56fgh34LMN3827klmnWXo54OPQ827rst193TYZu276vwUVxy654z";
    $charArray = str_split($chars);
    for($i = 0; $i < $len; $i++){
	    $randItem = array_rand($charArray);
	    $result .=$charArray[$randItem];
    }
    return $result;
}
function randNumGen($len){
	$result = "";
    $chars = "193827654";
    $charArray = str_split($chars);
    for($i = 0; $i < $len; $i++){
	    $randItem = array_rand($charArray);
	    $result .=$charArray[$randItem];
    }
    return $result;
}
function randNumGen2($len){
	$result = "";
    $chars = "132504";
    $charArray = str_split($chars);
    for($i = 0; $i < $len; $i++){
	    $randItem = array_rand($charArray);
	    $result .=$charArray[$randItem];
    }
    return $result;
}