<?php
$config_arr = parse_ini_file('lit_config.ini', true);

function build_data_files($boundary, $fields, $files, $name){
    $data = '';
    $eol = "\r\n";
    $delimiter = '-------------' . $boundary;

    foreach ($fields as $file => $content) {
        $data .= "--" . $delimiter . $eol
            . 'Content-Disposition: form-data; name="' . $name . "\"".$eol.$eol
            . $content . $eol;
    }

    foreach ($files as $file => $content) {
        $data .= "--" . $delimiter . $eol
            . 'Content-Disposition: form-data; name="' . $name . '"; filename="' . basename($file) . '"' . $eol
            . 'Content-Transfer-Encoding: binary'.$eol
            ;

        $data .= $eol;
        $data .= $content . $eol;
    }
    $data .= "--" . $delimiter . "--".$eol;


    return $data;
}

/*
	Reference: https://gist.github.com/maxivak/18fcac476a2f4ea02e5f80b303811d5f
*/
function putPostMultipart( $host, $port, $path, $filepath, $filename , $job_id) {
	$url = 'http://'.$host.':'.$port.$path;			
	$filesize = filesize($filepath);
	
	if ($filesize > 0) {		
		// data fields for POST request
		$fields = array("userid"=>$job_id);

		// files to upload
		$filenames = array($filepath);;

		$files = array();
		foreach ($filenames as $f){
		   $files[$f] = file_get_contents($f);
		}

		// curl
		$curl = curl_init();
		$url_data = http_build_query($fields);

		$boundary = uniqid();
		$delimiter = '-------------' . $boundary;

		$post_data = build_data_files($boundary, $fields, $files, 'userfile');

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => 1,
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POST => 1,
		  CURLOPT_POSTFIELDS => $post_data,
		  CURLOPT_HTTPHEADER => array(		
			"Content-Type: multipart/form-data; boundary=" . $delimiter,
			"Content-Length: " . strlen($post_data)
		  ),	  
		));
		$result = curl_exec($curl);	
		if(!curl_errno($curl))
		{
			$info = curl_getinfo($curl);
			if ($info['http_code'] == 200)
				$errmsg = "File uploaded successfully";
		}
		else
		{
			$errmsg = curl_error($curl);
		}
		curl_close($curl);
	}
	else
	{
		$errmsg = "Please select the file";
	}
	return(strstr($result, "{"));
}

/*
	Reference: http://cafe.naver.com/q69/8997
*/
function putPostMultipart1( $host, $port, $path, $filepath, $filename , $job_id) 
{	
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

	echo $msg.$data;
	exit;
	
	// send request	
	$f = fsockopen($host, $port);
	fputs($f, $msg.$data);

	// get the response
	while(!feof($f)) $result .= fread($f,32000);

	fclose($f);
	
	return(strstr($result, "{"));
}

function getHttp( $host, $port, $path ) {		
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36");
	$url = 'http://'.$host.':'.$port.$path;			
	curl_setopt($ch, CURLOPT_URL, $url);
	$result = curl_exec($ch);	
	curl_close($ch);	
	return(strstr($result, "{"));
}

function delete($job_id) {
	global $config_arr;	
	getHttp($config_arr['WEB']['BASE_URL'], intval($config_arr['WEB']['BASE_PORT']),  $config_arr['DIRECTORY']['API_DIR'].'delete.php?job_id='.$job_id);
}

function convert($infile, $outdir) {
	global $config_arr;
	$indirfile = G5_DATA_PATH.'/file/service/'.$infile;
	$pos = strrpos($infile, '.');
	$file_ext = strtolower(substr($infile, $pos, strlen($infile)));
	$file_name = substr($infile, 0, $pos);
	$converted_url = $outdir.$file_name.'.html';
	$count = 5;
	
	switch($file_ext) {
		case '.hwp' :
			$shellcmd = '/opt/wwwroot/service/conv -s "'.$indirfile.'" -o "'.$converted_url.'" -m convert';						
			if (file_exists($indirfile)) {
				shell_exec($shellcmd);
				unlink($indirfile);
				
				if (file_exists($converted_url)) {
					$handle = fopen($converted_url, 'r') or die('Cannot open file:  '.$converted_url); 
					$data = fread($handle, filesize($converted_url));
					$search = '<!DOCTYPE';							
					$start = strpos($data, $search);
					$data = substr($data, $start, strlen($data) - $start);					
					fclose($handle);					
					
					$handle = fopen($converted_url, 'w') or die('Cannot open file:  '.$converted_url); 
					fwrite($handle, $data);					
					fclose($handle);
				}
			}
			break;
		case '.jpg' :
		case '.png' :
		case '.docx' :
		case '.doc' :
		case '.xls' :
		case '.xlsx' :				
			$indirfile = G5_DATA_PATH.'/file/service/'.$file_name.'.pdf';
			while ($count > 0) {				
				if (file_exists($indirfile))
					break;
				$count--;
				sleep(1);
			}			
			if (file_exists($indirfile)) {
				$shellcmd = 'pdf2htmlEX --fallback 1 --process-outline 0 --dest-dir "'.$outdir.'" "'.$indirfile.'"';
				shell_exec($shellcmd);
				unlink($indirfile);
			}												
			break;					
		case '.pdf' :
			$shellcmd = 'pdf2htmlEX --fallback 1 --process-outline 0 --dest-dir "'.$outdir.'" "'.$indirfile.'"';
			shell_exec($shellcmd);
			unlink($indirfile);
			break;		
		default :
			$converted_url = '';
	}	
	
	if (file_exists($converted_url))
		return $converted_url;
	else
		return 'error';
}

// by DNK! 2012.06.04
function convertEx($infile, $outdir, $infile_ext) {
	global $config_arr;

	$converted_url = $outdir.'index.html';
	
	return $converted_url;
}

function upload($job_id, $filepath, $filename, $isLocal) {
	global $config_arr;

	if($isLocal) {
		$job_id = getExtEnum($filename).$job_id;
	}
		
	$filepath = $filepath.$filename;
	//$jsonObj = json_decode(putPostMultipart($config_arr['WEB']['BASE_URL'], intval($config_arr['WEB']['BASE_PORT']),  $config_arr['DIRECTORY']['API_DIR'].'upload.php', $filepath, $filename));
	$jsonObj = lit_decode(putPostMultipart($config_arr['WEB']['BASE_URL'], intval($config_arr['WEB']['BASE_PORT']),  $config_arr['DIRECTORY']['API_DIR'].'upload.php', $filepath, $filename, $job_id));
	if(is_object($jsonObj)) {
		foreach($jsonObj as $key => $value) {
			$result_array[$key] = $value;
		}
	}else {
		$result_array = $jsonObj;
	}
	
	if(!isset($result_array['job_id'])) {
		// error
		print("<script language=javascript>alert('변환중 문제가 발생하였습니다.');history.go(-1);</script>");
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

	//$jsonObj = json_decode(getHttp($config_arr['WEB']['BASE_URL'], intval($config_arr['WEB']['BASE_PORT']),  $config_arr['DIRECTORY']['API_DIR'].'exist.php?'.$option));
	$jsonObj = lit_decode(getHttp($config_arr['WEB']['BASE_URL'], intval($config_arr['WEB']['BASE_PORT']),  $config_arr['DIRECTORY']['API_DIR'].'exist.php?'.$option));
	
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
	$allowed_extensions = array( ".hwp", ".doc", ".docx", ".ppt", ".pptx", ".xls", ".xlsx", ".pdf", ".png", ".jpg",".jpeg", ".gif", ".bmp", ".tif", ".tiff");
	$sRet = "";
	for ($i = 0; $i < count($allowed_extensions); $i++)
	{
		if (strstr($pFileName, $allowed_extensions[$i])!=false)
		{
			$sRet = sprintf("%02d", ($i + 1));
			break;
		}
	}
	return $sRet;
}
?>
