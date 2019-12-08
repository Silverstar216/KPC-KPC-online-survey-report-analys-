<?php
/*
 * @param : filename
 */
include("config.php");
 
//$upload_folder = "c:/inetpub/wwwroot/u/o/";	// 업로드된 파일과 변환된 파일이 저장되어 있는 폴더
	
$filename = filter_input(INPUT_GET, 'filename');
$filename = trim($filename);	
	
unlink($upload_folder . $filename);
	
echo($upload_folder . $filename);
?>