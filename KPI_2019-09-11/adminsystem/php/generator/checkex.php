<?php
/*
 * @param : serial, ip, mac
 * @return : "invalid_serial", "expired_serial", "exceeded_serial" or ""
 */

$serial = $_GET['serial'];
$ip = $_GET['ip'];
$mac = $_GET['mac'];

// 0. Add to Log
include("config.php");

$today = date("Ymd");

$str = "ip=".$_SERVER['REMOTE_ADDR'].";";
$str .= "serial=".$serial.";";
$str .= "user=".$ip.";";
$str .= "mac=".$mac.";";
$str .= date("H:i:s").";"; 

$fp = fopen($log_filepath.$today."_".$serial.".txt", "a");
fputs($fp, $str."\r\n");
fclose($fp);


// 1. Check Serial NO
$serial_List = "serials.lst";

$fp = fopen($serial_List , "r");
$has_serial = false;
while (!feof ($fp)) {
   $buffer = trim(fgets($fp, 4096));
   //if($buffer && substr($buffer, 0, 17) == $serial) {
   if($buffer && substr($buffer, 0, 17) == substr($serial, 0, 17)) {
	   $has_serial = true;
	   break;
   }
}
fclose($fp);

if($has_serial == false) {
	echo("invalid_serial");
}

// 2. Check Validation Period
//if($valid_serial == false) {
//	echo("expired_serial");
//}

// 3. Check User Counts
//if($valid_usercount == false) {
//	echo("exceeded_serial");
//}
?>