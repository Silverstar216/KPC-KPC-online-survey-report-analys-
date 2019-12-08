<?php
	function get($key) {
		return empty($_GET[$key]) ? 0 : $_GET[$key];
	}
	
	class Delete {
		var $config;
		
		function Delete() {
			$this->config = parse_ini_file('lit_config.ini');
		}
		
		function del_id($id, $conv=0, $src=0) {
			
			if ($conv == 1) {
				$this->_del_file($id, $conv, $src);
				$this->_del_dir($this->config['UPLOAD_DIR'].$id);
			} else if ($src == 1) {
				$this->_del_file($id, $conv, $src);
			} else {
				
				$this->_del_file($id, $conv, $src);
				$this->_del_dir($this->config['UPLOAD_DIR'].$id);
			}
				
		}
		
		//설정된 날짜보다 오래된 파일을 삭제
		function del_mindate($date, $conv=0, $src=0) {
			$time = strtotime($date);
			
			if (!$time) return;
			
			$files = $this->_get_file("*");
			
			foreach ($files as $file) {
				$info = pathinfo($file);
				//if (strtolower($info['extension']) == 'epub' || strtolower($info['extension']) == 'htm') continue;
				if ($time > @filemtime ($this->config['UPLOAD_DIR'].$file  )) {
					//echo $file;
					$this->del_id($info['filename'], $conv, $src);
				
				}
				
			}

		}
			
		function _del_file($id, $conv, $src) {
			$files = $this->_get_file($id);
			$id = strtolower($id);
			
			foreach ($files as $item) {
				
				if ($conv == 1 && (strtolower($item) == $id.".htm" || strtolower($item) == $id.".epub")) 
					unlink ($this->config['UPLOAD_DIR'].$item);
				else if ($src == 1 && (strtolower($item) != $id.".htm" && strtolower($item) != $id.".epub")) { 
					unlink ($this->config['UPLOAD_DIR'].$item);
				}
				else if($conv ==0 && $src ==0)
					unlink ($this->config['UPLOAD_DIR'].$item);
					
			}
		}
		
		function _del_dir($dir) {
			
			if (!is_dir($dir)) return false;
    		
			$files = $this->_get_file("*",'', $dir);
			
			foreach ($files as $file)
				if (is_dir($dir."/".$file)) $this->_del_dir($dir."/".$file);
				else unlink($dir."/".$file);
			
			//print_r($files);
			
        	return rmdir($dir); 
		}
			
		
		function _get_file($id, $mask='.*', $path = '') {
			$dir = $path == '' ? @dir($this->config["UPLOAD_DIR"]): @dir($path);
			
			$files = array();
			while (false !== ($file = $dir->read())) {
				
				if (fnmatch($id.$mask, $file) && ($file != "." && $file != ".."))
					$files[] = $file;
			}
			
			$dir->close();
			
			return $files;
		}
			
		
			
	} 
	
	$id = get("job_id");
	$converted = get("conv");
	$source= get("src");
	$min = get("min");
	
	$del = new Delete();
	
	
	if ($id > 0)
		$del->del_id($id, $converted, $source);
	else if ($min > 0)
		$del->del_mindate($min,$converted, $source);
		
?>