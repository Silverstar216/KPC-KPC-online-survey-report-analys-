<?php

/*
 * litconvert.inc : convert uploaded file to download directory(HTML or ePub) class.
 * date : 2010. 08. 05. (09. 17. updated)
 * author : december(h@i.co.kr)
 *
 * user : LIT generator & LIT publisher
 *
 * API
 *		constructor : LitConvert( $config_filename )
 *		system param generator : make_call_parameters( $job_id, $html )
 *		return url generator : make_download_url( $job_id, $html )
 *		cache control : is_in_cache( $out_fullfilename )
 *		system call : call_server( $in_fullfilename, $out_fullfilename, $html )
 *		synchronous : wait_for_job_done( $filename )
 */

require_once('global.inc');

class LitConvert {
	var $config_arr;
	var $debug;

	function LitConvert( $config_filename ) {
		$this->config_arr = parse_ini_file($config_filename);

		if($this->config_arr['DEBUG'] == OFF) {
			$this->debug = false;
		}else {
			$this->debug = true;
		}
	}

	function _job_id_to_unique_name( $job_id ) {
		// 2 + rev(12) + 2
		return(substr($job_id, 0, 2) . strrev(substr($job_id, 2, 12)) . substr($job_id, 14, 2));
	}

	function _job_id_to_filename( $job_id ) {
		global $allowed_extensions;

//		if(strlen($job_id) != 16) {
//			return(false);
//		}
		// don't reverse id 
		//$unique_name = $this->_job_id_to_unique_name($job_id);
		$unique_name = $job_id;
		$extIdx = (int)substr($unique_name,0,2)-1;
		return($unique_name . $allowed_extensions[$extIdx]);
	}

	function make_call_parameters( $job_id, $html, $clientLocation ) {

		// 1. make input filename
		$in_filename = $this->_job_id_to_filename($job_id);

		if($in_filename == false) {
			$result[ERRNO] = ERRCODE_INVALID_JOB_ID;
			return($result);
		}

		

		$in_fullfilename = $this->config_arr['UPLOAD_DIR'] . $clientLocation . $in_filename;
		if(is_readable($in_fullfilename) == false) {
			if($this->debug == true) {
				print("ERRCODE_FAIL_TO_READ_FILE : $in_fullfilename\n");
				fflush();
			}
			$result[ERRNO] = ERRCODE_FAIL_TO_READ_FILE;
			return($result);
		}
		
		// 2. make output filename
		if($html == true) {
			$ext = $this->config_arr['HTML_EXTENSION'];
			// don't reverse id 
			//$out_fullfilename = $this->config_arr['DOWNLOAD_DIR'] . $this->_job_id_to_unique_name($job_id) . $ext;
			$out_fullfilename = $this->config_arr['DOWNLOAD_DIR'] . $clientLocation . $job_id . $ext;
		}else {
			$ext = $this->config_arr['EPUB_EXTENSION'];
			// don't reverse id 
			//$out_fullfilename = $this->config_arr['DOWNLOAD_DIR'] . $this->_job_id_to_unique_name($job_id) . $ext;
			$out_fullfilename = $this->config_arr['DOWNLOAD_DIR'] . $clientLocation . $job_id . $ext;
		}

		$result[INPUT_FILENAME] = $in_fullfilename;
		$result[OUTPUT_FILENAME] = $out_fullfilename;
		return($result);
	}
	
	//2012-02-13 ผ๖มค
	function make_download_url( $job_id, $html, $clientLocation ) {
		if($html == true) {
			$ext = $this->config_arr['HTML_EXTENSION'];
		}else {
			$ext = $this->config_arr['EPUB_EXTENSION'];
		}
		// don't reverse id 
		//$download_url = $this->config_arr['DOWNLOAD_BASE_URL'] . $this->_job_id_to_unique_name($job_id) . $ext;
		$download_url = $this->config_arr['DOWNLOAD_BASE_URL'] . str_replace("\\","/",$clientLocation) . $job_id . $ext;

		return($download_url);
	}

	function is_in_cache( $out_fullfilename ) {
		return(is_readable($out_fullfilename));
	}

	function call_server( $in_fullfilename, $out_fullfilename, $html ) {
		if($html == true) {
			$cmd_str = sprintf("%s %s %s", $this->config_arr['LIT_GENERATOR'], $in_fullfilename, $out_fullfilename);
		}else {
			$cmd_str = sprintf("%s %s %s", $this->config_arr['LIT_PUBLISHER'], $in_fullfilename, $out_fullfilename);
		}

		if($this->debug == true) {
			print("system($cmd_str)\n");
			fflush();
		}

		//echo $cmd_str;

		$last_line = system($cmd_str, $retval);

		if($this->debug == true) {
			print("return(last_line = $last_line, retval = $retval)\n");
			fflush();
		}
	}

	// by DNK! 2012.06.04
	function call_server_ex( $in_fullfilename, $out_fullfilename, $html ) {
		if($html == true) {
			$cmd_str = sprintf("%s %s %s tagStrip", $this->config_arr['LIT_GENERATOR'], $in_fullfilename, $out_fullfilename);
		}else {
			$cmd_str = sprintf("%s %s %s tagStrip", $this->config_arr['LIT_PUBLISHER'], $in_fullfilename, $out_fullfilename);
		}

		if($this->debug == true) {
			print("system($cmd_str)\n");
			fflush();
		}

		//echo $cmd_str;

		$last_line = system($cmd_str, $retval);

		if($this->debug == true) {
			print("return(last_line = $last_line, retval = $retval)\n");
			fflush();
		}
	}

	function wait_for_job_done( $filename ) {
		$wait_sec = $this->config_arr['WAIT'];

		while(1) {
			if(file_exists($filename)) {
				if($this->debug == true) {
					print("Exist : $filename\n");
					fflush();
				}
				sleep(1);
				break;
			}else {
				if($this->debug == true) {
					print("Wait : $wait_sec\n");
					fflush();
				}
				sleep(1);
				$wait_sec = $wait_sec - 1;
				if($wait_sec == 0) {
					return(false);
				}
			}
		}

		return(true);
	}
}

?>