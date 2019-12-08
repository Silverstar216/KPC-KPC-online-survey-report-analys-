<? 
	session_start();

	if ($_SESSION['valid_user']!="lemontimeit") {
		header( 'Location: /Php/Lit.Client/' ) ;
	}

	$cmd = $_POST['cmd'];

	//변수
	$client = $_POST['client'];
	$ip = $_POST['ip'];
	$limitday = str_replace("-","",$_POST['limitday']);
	$use_yn = $_POST['use_yn'];
	$des = $_POST['des'];

	if($_POST['unlimit'] == "Y"){
		$limitday = "";
	}

	include "../../inc/XmlClass.php"; 
	
	$xml = new XmlClass; 
	$prt = $xml->xmlOpen('../../../litservice/client.xml','list'); 
	$counts = count($prt['list']);

	$content = "<?xml version='1.0' encoding='utf-8'?>  \r\n";
	$content = $content . "<Code>  \r\n";

	if($cmd == "C"){

		for($x=0; $x<$counts; $x++) {
			$content = $content . "	<list>  \r\n";
			$content = $content . "		<client><![CDATA[" . $prt['client'][$x]['value'] ."]]></client>  \r\n";
			$content = $content . "		<ip><![CDATA[" . $prt['ip'][$x]['value'] ."]]></ip>  \r\n";
			$content = $content . "		<use><![CDATA[" . $prt['use'][$x]['value'] ."]]></use>  \r\n";
			$content = $content . "		<count><![CDATA[" . $prt['count'][$x]['value'] ."]]></count>  \r\n";
			$content = $content . "		<limitday><![CDATA[" . $prt['limitday'][$x]['value'] ."]]></limitday>  \r\n";
			$content = $content . "		<regday><![CDATA[" . $prt['regday'][$x]['value'] ."]]></regday>  \r\n";
			$content = $content . "		<desc><![CDATA[" . $prt['desc'][$x]['value'] ."]]></desc>  \r\n";
			$content = $content . "	</list>  \r\n";
		}
			$now_day = date("Ymd",time());
			$content = $content . "	<list>  \r\n";
			$content = $content . "		<client><![CDATA[" . $client ."]]></client>  \r\n";
			$content = $content . "		<ip><![CDATA[" . $ip ."]]></ip>  \r\n";
			$content = $content . "		<use><![CDATA[" . $use_yn ."]]></use>  \r\n";
			$content = $content . "		<count><![CDATA[0]]></count>  \r\n";
			$content = $content . "		<limitday><![CDATA[" . $limitday ."]]></limitday>  \r\n";
			$content = $content . "		<regday><![CDATA[" . $now_day ."]]></regday>  \r\n";
			$content = $content . "		<desc><![CDATA[" . $des ."]]></desc>  \r\n";
			$content = $content . "	</list>  \r\n";

	}else if($cmd == "U"){
		
		for($x=0; $x<$counts; $x++) {
			if($prt['client'][$x]['value'] == $client){
				$content = $content . "	<list>  \r\n";
				$content = $content . "		<client><![CDATA[" . $client ."]]></client>  \r\n";
				$content = $content . "		<ip><![CDATA[" . $ip ."]]></ip>  \r\n";
				$content = $content . "		<use><![CDATA[" . $use_yn ."]]></use>  \r\n";
				$content = $content . "		<count><![CDATA[" . $prt['count'][$x]['value'] . "]]></count>  \r\n";
				$content = $content . "		<limitday><![CDATA[" . $limitday ."]]></limitday>  \r\n";
				$content = $content . "		<regday><![CDATA[" . $prt['regday'][$x]['value'] ."]]></regday>  \r\n";
				$content = $content . "		<desc><![CDATA[" . $des ."]]></desc>  \r\n";
				$content = $content . "	</list>  \r\n";
			}else{
				$content = $content . "	<list>  \r\n";
				$content = $content . "		<client><![CDATA[" . $prt['client'][$x]['value'] ."]]></client>  \r\n";
				$content = $content . "		<ip><![CDATA[" . $prt['ip'][$x]['value'] ."]]></ip>  \r\n";
				$content = $content . "		<use><![CDATA[" . $prt['use'][$x]['value'] ."]]></use>  \r\n";
				$content = $content . "		<count><![CDATA[" . $prt['count'][$x]['value'] ."]]></count>  \r\n";
				$content = $content . "		<limitday><![CDATA[" . $prt['limitday'][$x]['value'] ."]]></limitday>  \r\n";
				$content = $content . "		<regday><![CDATA[" . $prt['regday'][$x]['value'] ."]]></regday>  \r\n";
				$content = $content . "		<desc><![CDATA[" . $prt['desc'][$x]['value'] ."]]></desc>  \r\n";
				$content = $content . "	</list>  \r\n";
			}
		}

	}else if($cmd == "D"){
		
		for($x=0; $x<$counts; $x++) {
			if($prt['client'][$x]['value'] !== $client){
				$content = $content . "	<list>  \r\n";
				$content = $content . "		<client><![CDATA[" . $prt['client'][$x]['value'] ."]]></client>  \r\n";
				$content = $content . "		<ip><![CDATA[" . $prt['ip'][$x]['value'] ."]]></ip>  \r\n";
				$content = $content . "		<use><![CDATA[" . $prt['use'][$x]['value'] ."]]></use>  \r\n";
				$content = $content . "		<count><![CDATA[" . $prt['count'][$x]['value'] ."]]></count>  \r\n";
				$content = $content . "		<limitday><![CDATA[" . $prt['limitday'][$x]['value'] ."]]></limitday>  \r\n";
				$content = $content . "		<regday><![CDATA[" . $prt['regday'][$x]['value'] ."]]></regday>  \r\n";
				$content = $content . "		<desc><![CDATA[" . $prt['desc'][$x]['value'] ."]]></desc>  \r\n";
				$content = $content . "	</list>  \r\n";
			}
		}

	}
	
	$content = $content . "</Code>  \r\n";

	//xml파일생성
	$file = fopen("../../../litservice/client.xml", "w");
	fwrite($file, $content); 
	fclose($file);

	header("location:main.php")
?>