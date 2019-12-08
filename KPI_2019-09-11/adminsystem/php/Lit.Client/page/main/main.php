<? include "../../inc/header.php" ?>

<?
$SearchValue = isset($_POST['keyword']) ? ''.$_POST['keyword'] : ''; 
$SearchField = isset($_POST['keyfield']) ? ''.$_POST['keyfield'] : ''; 
?>

<script type="text/javascript">
function goList(){
	var frm = document.frm;
	frm.action = "main.php";
	frm.submit();
}
function goView(seq){
	var frm = document.frm;
	frm.seq.value = seq;
	frm.action = "view.php";
	frm.submit();
}
</script>

<div id="centerBox">
	<form name="frm" method="post" action="main.php">
	<input type="hidden" name="seq" value=""/>

	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td>
				<select name="keyfield" class="selectBox">
					<option value="client" <?if($SearchField == "client"){echo "selected";} ?>>업체명</option>
					<option value="ip" <?if($SearchField == "ip"){echo "selected";} ?>>아이피</option>
				</select>
				<input type="text" name="keyword" class="inputText" value="<?=$SearchValue ?>"/>
				<input type="button" onclick="goList()" value="검색" class="btn"/>
			</td>
		</tr>
	</table>

	<div style="width:900px;">
		<table cellpadding="0" cellspacing="0" class="AdTable">
			<col width="60"/><col width="*"/><col width="180"/><col width="70"/>
			<col width="70"/><col width="90"/><col width="90"/>
			<tr>
				<th>No</th>
				<th>업체ID [IP]</th>
				<th>사용가능기간(남은날짜)</th>
				<th>사용횟수</th>
				<th>승인여부</th>
				<th>등록일</th>
				<th>상세보기</th>
			</tr>
	<?
		include "../../inc/XmlClass.php"; 
		
		
		$xmlFile = "../../../litservice/client.xml";

		if(file_exists($xmlFile)){
			
		}else{
			$contenta = "<?xml version='1.0' encoding='utf-8'?>  \r\n";
			$contenta = $contenta . "<Code>  \r\n";
			$contenta = $contenta . "</Code>  \r\n";

			$filea = fopen("../../../litservice/client.xml", "w");
			fwrite($filea, $contenta); 
			fclose($filea);
		}

		$xml = new XmlClass; 
		$prt = $xml->xmlOpen('../../../litservice/client.xml','list'); 
		$counts = count($prt['list']);
		$total = count($prt['list']);

		for($x=0; $x<$counts; $x++) {	
			$result = "";

			if($SearchValue != ""){
				if ($SearchField == "client"){
					$result = strstr($prt['client'][$x]['value'], $SearchValue);
				}else if($SearchField == "ip"){
					$result = strstr($prt['ip'][$x]['value'], $SearchValue);
				}
			}

			if( ($SearchValue == "") || ($SearchValue != "" && $result != "") ){
	?>

			<tr>
				<td><?=$total ?></td>
				<td align="left" style="padding-left:10px;">
					<?=$prt['client'][$x]['value'] ?>  [<?=$prt['ip'][$x]['value'] ?>]<!-- => 참고 : <?=$prt['desc'][$x]['value'] ?>-->
				</td>
				<td>
					<?
						$limit = $prt['limitday'][$x]['value'];
						$today = date("Ymd");

						 $s_timestamp = strtotime("$limit"); 
						 $e_timestamp = strtotime("$today");  
						 $range_day =  abs(($e_timestamp - $s_timestamp) /(24*60*60));

						 if($prt['limitday'][$x]['value'] == ""){
								echo "무제한";	
						 }else{
							$m_day = substr($prt['limitday'][$x]['value'], 0, 4) . "-" . substr($prt['limitday'][$x]['value'], 4, 2) . "-" . substr($prt['limitday'][$x]['value'], 6, 2);

							if($limit < $today){  //만료
								echo "<span class='red'>". $m_day ."까지(만료)</span>";
							 }else{
								echo $m_day . "까지(".$range_day."일)";	
							 }
						 }
					?>
				</td>
				<td><?=$prt['count'][$x]['value'] ?></td>
				<td>
					<? 
					if($prt['use'][$x]['value'] == "Y"){
						echo "승인";
					}else{
						echo "<span class='red'>대기</span>";
					}
					?>
				</td>
				<td>
					<?
						$r_day = substr($prt['regday'][$x]['value'], 0, 4) . "-" . substr($prt['regday'][$x]['value'], 4, 2) . "-" . substr($prt['regday'][$x]['value'], 6, 2);	
						echo $r_day;
					?>
				</td>
				<td><input type="button" value="상세보기" onclick="goView('<?=$prt['client'][$x]['value'] ?>')" class="btn"/></td>
			</tr>
		<?	
				}
				$total -= 1;
			}
		?>
		<? if ($x == 0){ ?>
			<tr>
				<td colspan="8" align="center"><br/>No Data<br/><br/></td>
			</tr>
		<? } ?>
		</table>

		
	</div>

	</form>
</div>

<? include "../../inc/footer.php" ?>