<?php

/*
 * exist.inc : check if uploaded file is exist.
 * date : 2010. 09. 28. (09. 28. updated)
 * author : december(h@i.co.kr)
 *
 * user : LIT generator & LIT publisher
 *
 * input
 *		Method = GET
 *		Params = job_id, mode(html/epub)
 *
 * return
 *		Fail/Success = { "exist" : code }, errcode = "yes", "no"
 */

require_once('global.inc');
require_once('litconvert.inc');

require_once('function.inc');
$GlobalUtil = new GlobalUtil(CONFIG_FILENAME);
$clientLocation = $GlobalUtil->checkClient();

function _return_result( $debug, $name, $value ) {
	$result[$name] = $value;
	print(json_encode($result));

	if($debug == true) {
		print("</PRE></HTML>");
	}
	exit();
}

// 1. load config
$ConvertObj = new LitConvert(CONFIG_FILENAME);

if($ConvertObj->debug == true) {
	print("<HTML><PRE>");
	print(CONFIG_FILENAME . "\n");
	print_r($ConvertObj->config_arr);
}

// 2. check parameters
if(!isset($_GET[JOB_ID]) && !isset($_POST[JOB_ID])) {
	_return_result($ConvertObj->debug, EXIST, NO);
}else {
	if(isset($_GET[JOB_ID]) && !isset($_POST[JOB_ID])){
		$job_id = $_GET[JOB_ID];
	}else{
		$job_id = $_POST[JOB_ID];
	}
	
}
if(isset($_GET[JOB_ID]) && !isset($_POST[JOB_ID])){
	if(!isset($_GET[MODE]) || $_GET[MODE] != EPUB) {
		$html = true;		// default
	}else {
		$html = false;
	}
}else{
	if(!isset($_POST[MODE]) || $_POST[MODE] != EPUB) {
		$html = true;		// default
	}else {
		$html = false;
	}
}


// 3. make system parameters
$result = $ConvertObj->make_call_parameters($job_id, $html, $clientLocation);
if(isset($result[ERRNO])) {
	// error
	_return_result($ConvertObj->debug, EXIST, NO);
}else {
	$in_fullfilename = $result[INPUT_FILENAME];
	$out_fullfilename = $result[OUTPUT_FILENAME];
}

// 4. check cache
$download_url = $ConvertObj->make_download_url($job_id, $html, $clientLocation);
if($ConvertObj->is_in_cache($out_fullfilename, $convert) == true) {
	if($ConvertObj->debug == true) {
		print("Cached : $out_fullfilename\n");
		print("</PRE><A HREF='$download_url'>Download</A><PRE>");
	}
	_return_result($ConvertObj->debug, EXIST, YES);
}else {
	_return_result($ConvertObj->debug, EXIST, NO);
}


if($ConvertObj->debug == true) {
	print("</PRE></HTML>");
}

?>