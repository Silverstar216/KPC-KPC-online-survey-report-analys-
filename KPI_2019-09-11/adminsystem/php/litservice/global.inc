<?php

/*
 * global.inc : define constants
 * date : 2010. 08. 02. (09. 28. updated)
 * author : december(h@i.co.kr)
 *
 * user : LIT generator & LIT publisher
 */

define("CONFIG_FILENAME",				"lit_config.ini");

define("FILENAME",						"filename");
define("INPUT_FILENAME",				"input_filename");
define("OUTPUT_FILENAME",				"output_filename");

define("ERRNO",							"errno");

define("ERRCODE_NOERROR",				"0");		// success
define("ERRCODE_WAIT",					"1");		// wait : not error

define("ERRCODE_INVALID_FILETYPE",		"10");
define("ERRCODE_UPLOAD_FAIL",			"11");
define("ERRCODE_INVALID_JOB_ID",		"12");
define("ERRCODE_FAIL_TO_MOVE",			"13");
define("ERRCODE_FAIL_TO_READ_FILE",		"14");
define("ERRCODE_FAIL_TO_WRITE_FILE",	"15");
define("ERRCODE_WAIT_TIMEOUT",			"16");

define("SYNC",		"sync");		// on(default) | off
define("MODE",		"mode");		// html(default) | epub
define("ACTION",	"action");		// do(default) | status
define("TAGSTRIP",	"tagstrip");		// by DNK! 2012.06.04

define("JOB_ID",	"job_id");
define("URL",		"url");

define("OFF",		"off");
define("ON",		"on");

define("HTML",		"html");
define("EPUB",		"epub");

define("CONVERT",	"convert");
define("STATUS",	"status");

define("EXIST",		"exist");
define("YES",		"yes");
define("NO",		"no");

//2012-02-13Ãß°¡
define("PERMISSION",	"N");
define("IN_ADDRS",	$_SERVER['REMOTE_ADDR']);
define("GUIDE_PAGE",	"http://demo.lemontimeit.com/Php/guide.php");

$allowed_extensions = array( ".hwp", ".doc", ".docx", ".ppt", ".pptx", ".xls", ".xlsx", ".pdf", ".png", ".jpg", ".gif", ".bmp", ".tif", ".tiff" );		/* TO DO : sustain order */		// by DNK! 2012.08.02
$allowed_types = array( "hwp", "word", "word", "powerpoint", "powerpoint", "excel", "excel", "pdf", "png", "jpg", "gif", "bmp", "tif", "tiff" );		/* TO DO : sustain order */

//$allowed_extensions = array( ".hwp", ".doc", ".docx", ".ppt", ".pptx", ".xls", ".xlsx", ".pdf" );		/* TO DO : sustain order */			// by DNK! 2011.05.30
//$allowed_types = array( "hwp", "word", "word", "powerpoint", "powerpoint", "excel", "excel", "pdf" );		/* TO DO : sustain order */




?>
