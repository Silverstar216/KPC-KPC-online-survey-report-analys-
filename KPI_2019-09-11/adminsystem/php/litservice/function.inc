<?php

/*
 * global.inc : define constants
 * date : 2012. 02. 13
 *
 * user : LIT generator & LIT publisher
 */

require_once('global.inc');
require_once('XmlClass.php');

class GlobalUtil {
	
	function GlobalUtil( $config_filename ) {
		$this->config_arr = parse_ini_file($config_filename);

		if($this->config_arr['DEBUG'] == OFF) {
			$this->debug = false;
		}else {
			$this->debug = true;
		}
	}

	function checkClient() {
		$xmlFile = "client.xml";

		if(file_exists($xmlFile)){
			
		}else{
			$contenta = "<?xml version='1.0' encoding='utf-8'?>  \r\n";
			$contenta = $contenta . "<Code>  \r\n";
			$contenta = $contenta . "</Code>  \r\n";

			$filea = fopen("client.xml", "w");
			fwrite($filea, $contenta); 
			fclose($filea);
		}

		$xml = new XmlClass; 
		$prt = $xml->xmlOpen('client.xml','list'); 

		$clientFolder = "";
		$today = date("Ymd");
		$counts = count($prt['list']); 

		for($x=0; $x<$counts; $x++) {
			if($prt['ip'][$x]['value'] == IN_ADDRS && ($prt['limitday'][$x]['value'] >= $today || $prt['limitday'][$x]['value'] == "") && ($prt['use'][$x]['value'] == "Y")){
				$clientFolder = $prt['client'][$x]['value'] . "\\";
			}
		}

		if($clientFolder == "" && PERMISSION == "N"){
			$clientFolder = "etc\\";
		}

		$path = $this->config_arr['UPLOAD_DIR'] . $clientFolder; 
		if ( !is_dir($path) ) { 
			mkdir($path); 
		} 

		return(trim($clientFolder));
	} 
	
	function stateClient() {
		$xmlFile = "client.xml";

		if(file_exists($xmlFile)){
			
		}else{
			$contenta = "<?xml version='1.0' encoding='utf-8'?>  \r\n";
			$contenta = $contenta . "<Code>  \r\n";
			$contenta = $contenta . "</Code>  \r\n";

			$filea = fopen("client.xml", "w");
			fwrite($filea, $contenta); 
			fclose($filea);
		}

		$xml = new XmlClass; 
		$prt = $xml->xmlOpen('client.xml','list'); 

		$returnState = "";
		$today = date("Ymd");
		$counts = count($prt['list']); 

		for($x=0; $x<$counts; $x++) {
			if($prt['ip'][$x]['value'] == IN_ADDRS){
				if($prt['limitday'][$x]['value'] < $today && $prt['limitday'][$x]['value'] != ""){
					$returnState = "101";
				}else if($prt['use'][$x]['value'] == "N"){
					$returnState = "102";
				}
			}
		}

		return($returnState);
	}

	function updateCount() {
		
		if(PERMISSION == "Y"){
			$xmlFile = "client.xml";

			if(file_exists($xmlFile)){
				
			}else{
				$contenta = "<?xml version='1.0' encoding='utf-8'?>  \r\n";
				$contenta = $contenta . "<Code>  \r\n";
				$contenta = $contenta . "</Code>  \r\n";

				$filea = fopen("client.xml", "w");
				fwrite($filea, $contenta); 
				fclose($filea);
			}

			$xml = new XmlClass; 
			$prt = $xml->xmlOpen('client.xml','list'); 

			$counts = count($prt['list']); 
			
			$content = "<?xml version='1.0' encoding='utf-8'?>  \r\n";
			$content = $content . "<Code>  \r\n";

			for($x=0; $x<$counts; $x++) {
				$content = $content . "	<list>  \r\n";
				$content = $content . "		<client><![CDATA[" . $prt['client'][$x]['value'] ."]]></client>  \r\n";
				$content = $content . "		<ip><![CDATA[" . $prt['ip'][$x]['value'] ."]]></ip>  \r\n";
				$content = $content . "		<use><![CDATA[" . $prt['use'][$x]['value'] ."]]></use>  \r\n";
				if($prt['ip'][$x]['value'] == IN_ADDRS){
					$content = $content . "		<count><![CDATA[" . ((int)$prt['count'][$x]['value'] + 1) ."]]></count>  \r\n";
				}else{
					$content = $content . "		<count><![CDATA[" . $prt['count'][$x]['value'] ."]]></count>  \r\n";
				}
				$content = $content . "		<limitday><![CDATA[" . $prt['limitday'][$x]['value'] ."]]></limitday>  \r\n";
				$content = $content . "		<regday><![CDATA[" . $prt['regday'][$x]['value'] ."]]></regday>  \r\n";
				$content = $content . "		<desc><![CDATA[" . $prt['desc'][$x]['value'] ."]]></desc>  \r\n";
				$content = $content . "	</list>  \r\n";
			}

			$content = $content . "</Code>  \r\n";

			$file = fopen("client.xml", "w");
			fwrite($file, $content); 
			fclose($file);
		}

	}

	function xmlCheck(){
		

	}

}
?>
