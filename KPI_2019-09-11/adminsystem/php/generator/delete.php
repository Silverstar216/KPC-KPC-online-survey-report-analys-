<?php
/*
 * @param : filename
 */
include("config.php");
 
//$upload_folder = "c:/inetpub/wwwroot/u/o/";	// ���ε�� ���ϰ� ��ȯ�� ������ ����Ǿ� �ִ� ����
	
$filename = filter_input(INPUT_GET, 'filename');
$filename = trim($filename);	
	
unlink($upload_folder . $filename);
	
echo($upload_folder . $filename);
?>