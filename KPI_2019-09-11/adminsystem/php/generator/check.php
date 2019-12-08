<?php
/*
 * @param : serial
 */
$serial = filter_input(INPUT_GET, 'serial');
$serial = trim($serial);

include("config.php");

$fp = fopen($serial_filename , "r");
$has_serial = false;
while (!feof ($fp)) {
   $buffer = trim(fgets($fp, 4096));
   if($buffer && substr($buffer, 0, 29) == $serial) {
	   $has_serial = true;
   }
}
fclose($fp);

if($has_serial == true) {
	echo("TRUE");
}else {
	$str_serial = "SerialNO=" . $serial;
	$fp = fopen($ini_filename , "r");
	$valid_serial = false;
	while (!feof ($fp)) {
	   $buffer = trim(fgets($fp, 4096));
	   if($buffer && substr($buffer, 0, 38) == $str_serial) {
		   $valid_serial = true;
	   }
	}
	fclose($fp);
	
	if($valid_serial == true) {
		echo("FALSE");
	}else {
		echo("TRUE");
	}	
}

?>