<?php

/*
 * litupload.inc : store uploaded file to temp directory class.
 * date : 2010. 08. 05. (08. 16. updated)
 * author : december(h@i.co.kr)
 *
 * user : LIT generator & LIT publisher
 *
 * API
 *		constructor : LitUpload( $config_filename )
 *		file controler : prepare_convert_file( $in_filename, $in_mime, $in_temp_filename )
 *		$job_id generator : filename_to_job_id( $unique_filename )
 */

require_once('global.inc');


class LitUpload {
	var $config_arr;
	var $debug;

	function LitUpload( $config_filename ) {
		$this->config_arr = parse_ini_file($config_filename);

		if($this->config_arr['DEBUG'] == OFF) {
			$this->debug = false;
		}else {
			$this->debug = true;
		}
	}

	function _isvalid_file( $in_filename, $in_mime ) {
		global $allowed_extensions, $allowed_types;

		for($i = 0; $i < count($allowed_extensions); $i++) {
			//if(strstr($in_filename, $allowed_extensions[$i]) != false) {
			if(stristr($in_filename, $allowed_extensions[$i]) != false) {	// by DNK! 2012.08.24
			//if(strstr($in_filename, $allowed_extensions[$i]) != false && strstr($in_mime, $allowed_types[$i]) != false) {
				return($i + 1);
			}
		}

		return(false);
	}

	function _make_unique_filename( $file_type ) {
		// 16 dibits : type(2) + date
		return(sprintf("%02d", $file_type) . date("YmdHis"));
	}

	function filename_to_job_id( $unique_filename ) {
		// 2 + rev(12) + 2
		$id = substr($unique_filename, 0, 2) . strrev(substr($unique_filename, 2, 12)) . substr($unique_filename, 14, 2);

		return($id);
	}

	function prepare_convert_file( $in_filename, $in_mime, $in_temp_filename, $in_fileid, $clientLocation)
	{
		global $allowed_extensions;

		// 1. check : extension & mimetype
		$file_type = $this->_isvalid_file($in_filename, $in_mime);
		if($file_type == false) {
			$result[ERRNO] = ERRCODE_INVALID_FILETYPE;
			return($result);
		}

		// 2. store
		if(strlen($in_fileid)>0) {
			$unique_filename = $in_fileid . $allowed_extensions[$file_type-1];
		} else {
			$unique_filename = $this->_make_unique_filename($file_type) . $allowed_extensions[$file_type-1];
		}

		$upload_filename = $this->config_arr['UPLOAD_DIR'] . $clientLocation . $unique_filename;

		if($unique_filename == false) {
			$result[ERRNO] = ERRCODE_INVALID_FILETYPE;
			return($result);
		}

		if(move_uploaded_file($in_temp_filename, $upload_filename) == false) {
			$result[ERRNO] = ERRCODE_FAIL_TO_MOVE;
			return($result);
		}

		$result[FILENAME] = $unique_filename;
		return($result);
	}
}

?>