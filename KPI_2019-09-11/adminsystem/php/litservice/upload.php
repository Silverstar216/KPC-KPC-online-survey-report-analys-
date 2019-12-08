<?php

/*
 * upload.inc : store uploaded file to temp directory.
 * date : 2010. 08. 02. (08. 05. updated)
 * author : december(h@i.co.kr)
 *
 * user : LIT generator & LIT publisher
 *
 * input
 *		Method = multipart/form-data, POST
 *		Params = userfile
 *
 * return
 *		Fail = { "errno" : errcode }, errcode = ERRCODE_INVALID_FILETYPE, ERRCODE_FAIL_TO_MOVE, ERRCODE_UPLOAD_FAIL
 *		Success = { "job_id" : job_id }, job_id = 16 dibit string
 */

require_once('global.inc');
require_once('litupload.inc');

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
$UploadObj = new LitUpload(CONFIG_FILENAME);

if($UploadObj->debug == true) {
	print("<HTML><PRE>");
	print(CONFIG_FILENAME . "\n");
	print_r($UploadObj->config_arr);
	print("\n_FILES['userfile']\n");
	print_r($_FILES['userfile']);
}


// 2. handling exeption case
if($_FILES['userfile']['error'] != 0) {
	_return_result($UploadObj->debug, ERRNO, ERRCODE_UPLOAD_FAIL);
}


// 3. store uploaded file
if($clientLocation != ""){
	$result = $UploadObj->prepare_convert_file($_FILES['userfile']['name'], $_FILES['userfile']['type'], $_FILES['userfile']['tmp_name'], $_POST["userid"], $clientLocation);
}

if(isset($result[ERRNO])) {
	// error
	_return_result($UploadObj->debug, ERRNO, $result[ERRNO]);
}else {
	$upload_filename = $result[FILENAME];
}


//$job_id = $UploadObj->filename_to_job_id($upload_filename);
//$job_id = substr($upload_filename, 0, 16);
$job_id = substr($upload_filename, 0, strpos($upload_filename, "."));

if($UploadObj->debug == true) {
	print("upload_filename : $upload_filename\n");
	print("job_id : $job_id\n");
	print("</PRE>
			<FORM action=convert.php method=GET>
				<INPUT type=hidden name=job_id value='$job_id' />
				<TABLE><TR align=center><TD>sync</TD><TD>mode</TD><TD>action</TD><TD></TD>
				</TR><TR><TD>
					<SELECT name=sync>
					  <OPTION value=on>ON</OPTION>
					  <OPTION value=off>OFF</OPTION>
					</select>
				</TD><TD>
					<SELECT name=mode>
					  <OPTION value=html>HTML</OPTION>
					  <OPTION value=epub>ePub</OPTION>
					</select>
				</TD><TD>
					<SELECT name=action>
					  <OPTION value=convert>CONVERT</OPTION>
					  <OPTION value=status>STATUS</OPTION>
					</select>
				</TD><TD>
					<INPUT type=submit value=Convert />
				</TD></TR>
				</TABLE>
			</FORM>
			<PRE>");
}

// 4. return result
_return_result($UploadObj->debug, JOB_ID, $job_id);

if($UploadObj->debug == true) {
	print("</PRE></HTML>");
}

?>