<?php
$config_arr = parse_ini_file('lit_config.ini', true);

function putPostMultipart( $host, $port, $path, $filepath, $filename , $job_id) {
	// from http://cafe.naver.com/q69/8997

	$content_type = "application/octet-stream";

	// make boundary
	srand((double)microtime()*1000000);
	$boundary = "---------------------------".substr(md5(rand(0,32000)),0,10);

	$data = "\r\n--$boundary";
	$data.="
Content-Disposition: form-data; name=userfile; name=\"userid\";

$job_id
";	
	
	$data.= "--$boundary";

	// make multi-part body
	$content_file = join("", file($filepath));
	$data.="
Content-Disposition: form-data; name=userfile; filename=\"$filename\"
Content-Type: $content_type

$content_file
--$boundary";
	$data.="--\r\n\r\n";

	// make HTTP post request header
	$msg =
"POST $path HTTP/1.0
Content-Type: multipart/form-data; boundary=$boundary
Content-Length: ".strlen($data)."\r\n\r\n";

	$result="";

	// send request
	$f = fsockopen($host, $port);
	fputs($f, $msg.$data);

	// get the response
	while(!feof($f)) $result .= fread($f,32000);

	fclose($f);

	return(strstr($result, "{"));
}

function getHttp( $host, $port, $path ) {
	$fp = fsockopen($host, $port);
	fwrite($fp,'GET '.$path.' HTTP/1.0'."\r\n\r\n");
	$result = ''; 
	while ( !feof($fp) ) $result.= fread($fp,512); 
	fclose($fp); 

	return(strstr($result, "{"));
}

function delete($job_id) {
	global $config_arr;
	//echo ($config_arr['WEB']['BASE_URL']. $config_arr['DIRECTORY']['API_DIR'].'delete.php?job_id='.$job_id);
	getHttp($config_arr['WEB']['BASE_URL'], 80, $config_arr['DIRECTORY']['API_DIR'].'delete.php?job_id='.$job_id);
}

function convert($job_id, $filename, $ishtml, $isLocal) {
	global $config_arr;

	if($isLocal) {
		$job_id = getExtEnum($filename).$job_id;
	}
		
	if($ishtml) {
		$option = 'mode=html&job_id='.$job_id.'&action=convert&sync=on';
	}else {
		$option = 'mode=epub&job_id='.$job_id.'&action=convert&sync=on';
	}
	
	//Params = job_id, sync(on/off), mode(html/epub), action(convert/status)
//	$jsonObj = json_decode(file_get_contents('http://web.lemontimeit.com/php/hjhwang/convert.php?'.$option));
	
	//echo ($config_arr['WEB']['BASE_URL']. $config_arr['DIRECTORY']['API_DIR'].'convert.php?'.$option);
	//$jsonObj = json_decode(getHttp($config_arr['WEB']['BASE_URL'], 80, $config_arr['DIRECTORY']['API_DIR'].'convert.php?'.$option));
	$jsonObj = lit_decode(getHttp($config_arr['WEB']['BASE_URL'], 80, $config_arr['DIRECTORY']['API_DIR'].'convert.php?'.$option));
	if(is_object($jsonObj)) {
		foreach($jsonObj as $key => $value) {
			$result_array[$key] = $value;
		}
	}else {
		$result_array = $jsonObj;
	}

	if(!isset($result_array['url'])) {
		// error
		print("<script language=javascript>alert('Warning : Convert fail.');history.go(-1);</script>");
		return(false);
	}else {
		return($result_array['url']);
	}
}

// by DNK! 2012.06.04
function convertEx($job_id, $filename, $ishtml, $isLocal) {
	global $config_arr;

	if($isLocal) {
		$job_id = getExtEnum($filename).$job_id;
	}
		
	if($ishtml) {
		$option = 'mode=html&job_id='.$job_id.'&action=convert&sync=on&tagstrip=on';
	}else {
		$option = 'mode=epub&job_id='.$job_id.'&action=convert&sync=on&tagstrip=on';
	}
	
	//Params = job_id, sync(on/off), mode(html/epub), action(convert/status)
//	$jsonObj = json_decode(file_get_contents('http://web.lemontimeit.com/php/hjhwang/convert.php?'.$option));
	
	//echo ($config_arr['WEB']['BASE_URL']. $config_arr['DIRECTORY']['API_DIR'].'convert.php?'.$option);
	//$jsonObj = json_decode(getHttp($config_arr['WEB']['BASE_URL'], 80, $config_arr['DIRECTORY']['API_DIR'].'convert.php?'.$option));
	$jsonObj = lit_decode(getHttp($config_arr['WEB']['BASE_URL'], 80, $config_arr['DIRECTORY']['API_DIR'].'convert.php?'.$option));
	if(is_object($jsonObj)) {
		foreach($jsonObj as $key => $value) {
			$result_array[$key] = $value;
		}
	}else {
		$result_array = $jsonObj;
	}

	if(!isset($result_array['url'])) {
		// error
		print("<script language=javascript>alert('Warning : Convert fail.');history.go(-1);</script>");
		return(false);
	}else {
		return($result_array['url']);
	}
}

function upload($job_id, $filepath, $filename, $isLocal) {
	global $config_arr;

	if($isLocal) {
		$job_id = getExtEnum($filename).$job_id;
	}
	
	//$jsonObj = json_decode(putPostMultipart($config_arr['WEB']['BASE_URL'], 80, $config_arr['DIRECTORY']['API_DIR'].'upload.php', $filepath, $filename));
	$jsonObj = lit_decode(putPostMultipart($config_arr['WEB']['BASE_URL'], 80, $config_arr['DIRECTORY']['API_DIR'].'upload.php', $filepath, $filename, $job_id));
	if(is_object($jsonObj)) {
		foreach($jsonObj as $key => $value) {
			$result_array[$key] = $value;
		}
	}else {
		$result_array = $jsonObj;
	}


	if(!isset($result_array['job_id'])) {
		// error
		print("<script language=javascript>alert('Warning : Upload fail.');history.go(-1);</script>");
		return(false);
	}else {
		$job_id = $result_array['job_id'];
		if($isLocal) {
			$job_id = substr($job_id,2);
		}
		return $job_id;
	}
}

function exist($job_id, $filename, $ishtml, $isLocal) {
	global $config_arr;

	if($isLocal) {
		$job_id = getExtEnum($filename).$job_id;
	}
	
//	if(strlen($job_id) != 16 || !preg_match("/[0-9]/", $job_id)){
//		return (false);
//	}

	//job_id, mode(html/epub)
	if($ishtml) {
		$option = 'mode=html&job_id='.$job_id;
	}else {
		$option = 'mode=epub&job_id='.$job_id;
	}

	//$jsonObj = json_decode(getHttp($config_arr['WEB']['BASE_URL'], 80, $config_arr['DIRECTORY']['API_DIR'].'exist.php?'.$option));
	$jsonObj = lit_decode(getHttp($config_arr['WEB']['BASE_URL'], 80, $config_arr['DIRECTORY']['API_DIR'].'exist.php?'.$option));

	if(is_object($jsonObj)) {
		foreach($jsonObj as $key => $value) {
			$result_array[$key] = $value;
		}
	}else {
		$result_array = $jsonObj;
	}

	//Fail/Success = { "exist" : code }, errcode = "yes", "no"
	if($result_array['exist'] == "no") {
		// error
	//	print("<script language=javascript>alert('Warning : Convert fail.');history.go(-1);</script>");
		return(false);
	}else {
		return(true);
	}
}

function lit_decode($pstr) {
	$pstr = substr($pstr, 1, strlen($pstr)-2);
	$pstr = str_replace("\"", "", $pstr);
	$aval1 = explode(',', $pstr);
	$skey = '';
	$sval = '';
	for($i=0; $i<count($aval1); $i++) {
		$skey = substr($aval1[$i], 0, strpos($aval1[$i], ':'));
		$sval = substr($aval1[$i], strpos($aval1[$i], ':')+1);
		$sval = str_replace('\\', '', $sval);
		$aret[$skey] = $sval;
	}
	return $aret;
}


function getExtEnum($pFileName)
{
	$allowed_extensions = array( ".hwp", ".doc", ".docx", ".ppt", ".pptx", ".xls", ".xlsx", ".pdf", ".png", ".jpg", ".gif", ".bmp", ".tif", ".tiff" );
	$sRet = "";
	for ($i = 0; $i < count($allowed_extensions); $i++)
	{
		//if (strstr($pFileName, $allowed_extensions[$i])!=false)
		if (stristr($pFileName, $allowed_extensions[$i])!=false)	// by DNK! 2012.08.24 
		{
			$sRet = sprintf("%02d", ($i + 1));
			break;
		}
	}
	return $sRet;
}
?>
