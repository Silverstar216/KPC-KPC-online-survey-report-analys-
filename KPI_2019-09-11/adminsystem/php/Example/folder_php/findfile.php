<?
	$config_arr = parse_ini_file("lit_config.ini");
	$debug = false;

	if($config_arr[DEBUG] == "off") {
		$debug = false;
	}else {
		$debug = true;
	}
	

	$input_file = "zzz.hwp";
	$dot_pos = strpos($input_file, '.');

	$cmp_word = substr($input_file, 0, $dot_pos);
	
	if ($handle = opendir($config_arr[UPLOAD_DIR])) 
	{ 
		while (false !== ($file = readdir($handle))) 
		{ 
			
			if(is_file($config_arr[UPLOAD_DIR].$file) == true)
			{
				$temp = $file;
				$find_word = substr($temp, 0, $dot_pos);

				if(strcasecmp($find_word, $cmp_word) == 0 && strcasecmp(substr($temp, $dot_pos+1), "htm") == 0)
				{
					echo "found";
					break;

				}else
				{
					$fp = fopen($config_arr[UPLOAD_DIR].$cmp_word.".end", "w");
					fwrite($fp, $cmp_word.substr($input_file, $dot_pos));
					fclose($fp);
					
					if(wait_for_job_done($config_arr[UPLOAD_DIR].$cmp_word.".htm") == false)
					{
						echo "ERRCODE_WAIT_TIMEOUT";
					}else
					{
						echo $config_arr[DOWNLOAD_BASE_URL].$cmp_word.".htm";
					}
					break;
				}
			}
		}
	}

	function wait_for_job_done( $filename ) {
		global $debug;

		$wait_sec = $config_arr[WAIT];

		while(1) 
		{
			if(file_exists($filename)) 
			{
				if($debug == true) 
				{
					print("Exist : $filename\n");
					fflush();
				}
				sleep(1);
				break;
			}
			else 
			{
				if($debug == true) 
				{
					print("Wait : $wait_sec\n");
					fflush();
				}
				sleep(1);
				$wait_sec = $wait_sec - 1;
				if($wait_sec == 0) 
				{
					return(false);
				}
			}
		}
		return(true);
	}

?>