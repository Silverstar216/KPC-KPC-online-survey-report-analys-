<?php
/*
 * @param : foldername
 */

$foldername = filter_input(INPUT_GET, 'foldername');
$foldername = trim($foldername);

$is_folder_exist = is_dir($foldername);

if ($is_folder_exist) {
	echo 'TRUE';
}
else {
	echo 'FALSE';
}
?>