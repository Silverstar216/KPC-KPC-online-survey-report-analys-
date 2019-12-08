<?
include "../../inc/XmlClass.php";

$gubun = $_POST['gubun'];
$value = $_POST['value'];

//수정페이지
$wonbon = $_POST['wonbon'];

$xml = new XmlClass; 
$prt = $xml->xmlOpen('../../../litservice/client.xml','list'); 
$counts = count($prt['list']);

$reCount = "N";

if($gubun == "id"){
	for($x=0; $x<$counts; $x++) {
		if($prt['client'][$x]['value'] == $value){
			$reCount = "Y";
		}
	}
}else{
	for($x=0; $x<$counts; $x++) {
		if($prt['ip'][$x]['value'] == $value){
			$reCount = "Y";
		}
	}
}	

if($wonbon != "" && $wonbon == $value){
	$reCount = "N";
}

$resultArray = array();  
$resultArray["result"] = $reCount;

header("Content-Type: application/json");
echo json_encode($resultArray);
?>
