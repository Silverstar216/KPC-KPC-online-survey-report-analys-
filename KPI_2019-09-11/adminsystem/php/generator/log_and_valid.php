<?php
/**
 * @param : key
 */

include("config.php");

$today = date("Ymd");
$ip = $_SERVER['REMOTE_ADDR'];

$fp = fopen($log_filepath.$today."_".$ip.".txt", "a");

function getFormKey() {
	foreach($_GET as $key => $val) {
		$f_data[$key] = $val;
	}
	foreach($_POST as $key => $val) {
		$f_data[$key] = $val;
	}
	return $f_data;
}

function getFormData() {
	$str = "ip=".$_SERVER['REMOTE_ADDR'].";";
	$f_data = getFormKey();
	foreach($f_data as $key => $val) {
		$str .= $key."=".$val.";";
	}
	
	$str .= date("H:i:s").";";
	
	return $str;
}

fputs($fp, getFormData()."\r\n");
fclose($fp);

$fp = fopen($ip_filename , "r");

$has_ip = false;
while (!feof ($fp)) {
   $buffer = trim(fgets($fp, 4096));
   $len = strlen($buffer);

   $pos = strpos( $buffer, "*" );
   if ( $pos != false ) {
		$buffer2 = substr( $buffer, 0, $pos );
		$buffer = $buffer2;
		$len = strlen($buffer);
	}

	if($buffer && strncmp($buffer, $_SERVER['REMOTE_ADDR'], $len) == 0) {
	   $has_ip = true;
   }
}

if($has_ip == true) {
	echo("FALSE");
}else {
	echo("TRUE");
}
?>