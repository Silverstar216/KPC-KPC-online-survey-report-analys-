<?php

/*
 * convert.inc : convert uploaded file to download directory(HTML or ePub).
 * date : 2010. 08. 02. (08. 05. updated)
 * author : december(h@i.co.kr)
 *
 * user : LIT generator & LIT publisher
 *
 * input
 *		Method = GET
 *		Params = job_id, sync(on/off), mode(html/epub), action(convert/status)
 *				 combination-1 = action(convert) + job_id + sync(on/off) + mode(html/epub)
 *					=> { "error", errcode}, errcode = ERRCODE_INVALID_JOB_ID, ERRCODE_FAIL_TO_READ_FILE, ERRCODE_WAIT_TIMEOUT
 *					   { "url", url }, url = download url
 *					   { "job_id", job_id }, when "sync=off"
 *				 combination-2 = action(status) + job_id + mode(html/epub)
 *					=> { "error", errcode}, errcode = ERRCODE_INVALID_JOB_ID, ERRCODE_FAIL_TO_READ_FILE, ERRCODE_WAIT
 *					   { "url", url }, url = download url
 *
 * return
 *		Fail = { "errno" : errcode }, errcode = ERRCODE_INVALID_JOB_ID, ERRCODE_FAIL_TO_READ_FILE, ERRCODE_WAIT_TIMEOUT
 *		Success = { "errno" : errcode }, errcode = ERRCODE_WAIT
 *				  { "job_id" : job_id }, job_id = 16 dibit string
 *				  { "url" : url }, url = download url
 */

require_once('global.inc');
require_once('litconvert.inc');

//require_once('litlog.inc');
//$logger = new litlog();
//$logger->makelog("convert start");

require_once('function.inc');
$GlobalUtil = new GlobalUtil(CONFIG_FILENAME);
$clientLocation = $GlobalUtil->checkClient();

if($clientLocation == ""){
	$clientState = $GlobalUtil->stateClient();
	$result[URL] = GUIDE_PAGE . "?state=$clientState";
	print(json_encode($result));
	exit();
}

function _return_result( $debug, $name, $value ) {
	global $GlobalUtil;
	$result[$name] = $value;
	print(json_encode($result));
	if($debug == true) {
		print("</PRE></HTML>"); 
	}
	$GlobalUtil->updateCount();
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
if(isset($_GET[JOB_ID]) && !isset($_POST[JOB_ID])){
	if(!isset($_GET[JOB_ID])) {
		_return_result($ConvertObj->debug, ERRNO, ERRCODE_INVALID_JOB_ID);
	}else {
		$job_id = $_GET[JOB_ID];
	}
	if(!isset($_GET[SYNC]) || $_GET[SYNC] != OFF) {
		$sync = true;		// default
	}else {
		$sync = false;
	}
	if(!isset($_GET[MODE]) || $_GET[MODE] != EPUB) {
		$html = true;		// default
	}else {
		$html = false;
	}
	if(!isset($_GET[ACTION]) || $_GET[ACTION] != STATUS) {
		$convert = true;	// default
	}else {
		$convert = false;
	}
}else{
	if(!isset($_POST[JOB_ID])) {
		_return_result($ConvertObj->debug, ERRNO, ERRCODE_INVALID_JOB_ID);
	}else {
		$job_id = $_POST[JOB_ID];
	}
	if(!isset($_POST[SYNC]) || $_POST[SYNC] != OFF) {
		$sync = true;		// default
	}else {
		$sync = false;
	}
	if(!isset($_POST[MODE]) || $_POST[MODE] != EPUB) {
		$html = true;		// default
	}else {
		$html = false;
	}
	if(!isset($_POST[ACTION]) || $_POST[ACTION] != STATUS) {
		$convert = true;	// default
	}else {
		$convert = false;
	}
}
// by DNK! 2012.06.04
if(!isset($_GET[TAGSTRIP]) || $_GET[TAGSTRIP] != ON) {
	$tagstrip = false;	// default
}else {
	$tagstrip = true;
}

// 3. make system parameters
$result = $ConvertObj->make_call_parameters($job_id, $html, $clientLocation);
if(isset($result[ERRNO])) {
	// error
	_return_result($ConvertObj->debug, ERRNO, $result[ERRNO]);
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
	_return_result($ConvertObj->debug, URL, $download_url);
}

// 5. return status
if($convert == false) {
	// send status
	_return_result($ConvertObj->debug, ERRNO, ERRCODE_WAIT);		
}

// 6. convert it
//$ConvertObj->call_server($in_fullfilename, $out_fullfilename, $html);
if($tagstrip == true) {
	$ConvertObj->call_server_ex($in_fullfilename, $out_fullfilename, $html);
}else {
	$ConvertObj->call_server($in_fullfilename, $out_fullfilename, $html);
}

if($ConvertObj->debug == true) {
	print("</PRE><A HREF='$download_url'>Download</A><PRE>");
}

if($sync == true) {
	if($ConvertObj->wait_for_job_done($out_fullfilename) == false) {
		_return_result($ConvertObj->debug, ERRNO, ERRCODE_WAIT_TIMEOUT);		
	}
	_return_result($ConvertObj->debug, URL, $download_url);
}else {
	_return_result($ConvertObj->debug, JOB_ID, $job_id);
}

if($ConvertObj->debug == true) {
	print("</PRE></HTML>");
}

?>