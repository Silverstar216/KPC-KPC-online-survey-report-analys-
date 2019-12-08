<? include "../../inc/header.php" ?>

<?
$seq = $_POST['seq'];

if ($seq == "") 
	header("location:main.php")
?>

<!-- datepicker -->
<link type="text/css" href="../../js/calendar/themes/base/ui.all.css" rel="stylesheet" />
<script type="text/javascript" src="../../js/jquery-1.6.min.js"></script>
<script type="text/javascript" src="../../js/calendar/ui/ui.core.js"></script>
<script type="text/javascript" src="../../js/calendar/ui/ui.datepicker.js"></script>
<script type="text/javascript" src="../../js/calendar/ui/ui.datepicker-ko.js"></script>
<!-- datepicker -->

	<?
		include "../../inc/XmlClass.php"; 

		$xml = new XmlClass; 
		$prt = $xml->xmlOpen('../../../litservice/client.xml','list'); 
		$counts = count($prt['list']);

		for($x=0; $x<$counts; $x++) {
			if($prt['client'][$x]['value'] == $seq){
				break;
			}
		}
	?>

<script type="text/javascript">
$(function() { 
	$("#calendar").datepicker({ 
			changeMonth: true,
			changeYear: true,
			yearRange: '2012:2020'
		});		
	});
function goList(){
	var frm = document.frm;
	frm.action = "main.php";
	frm.submit();
}
function goDelete(){
	var frm = document.frm;
	if(confirm("삭제하시겠습니까?")){
		frm.cmd.value = "D";
		frm.submit();
	}
}
function goModify(){
	var frm = document.frm;
	if(frm.ip.value == ""){
		alert("필수입력(아이피)");
		frm.ip.focus();
	}else if(frm.limitday.value == "" && document.getElementById("unlimit").checked == false){
		alert("필수입력(사용기간설정)");
		frm.limitday.focus();
	}else if(frm.validate2.value == "N"){
		alert("아이피의 유효성을 체크해주세요.");
		frm.ip.focus();
	}else{
		if(document.getElementById("unlimit").checked == true){
			frm.unlimit.value = "Y";
		}
		frm.cmd.value = "U";
		frm.submit();
	}
}
function checkValue(gubun,value){
	$.post("check.php" , {'gubun':gubun, 'value':value, 'wonbon':'<?=$prt['ip'][$x]['value'] ?>'}
	, function (data){   
		if(data.result == "N"){
			document.getElementById('alert2').innerHTML = "<font class='green'>사용가능한 IP입니다.</span>";
			document.getElementById('validate2').value = "Y";
		}else{
			document.getElementById('alert2').innerHTML = "<font class='red'>이미 등록된 IP입니다.</span>";
			document.getElementById('validate2').value = "N";
		}
	 }, "json");  
}
</script>

<div id="centerBox">
	<form name="frm" method="post" action="process.php">
	<input type="hidden" name="cmd" value=""/>
	<input type="hidden" name="validate2" id="validate2" value="Y"/>

	<div style="width:900px;">
		<table cellpadding="0" cellspacing="0" class="EditTable">
			<caption class="pb5"><b>업체정보</b></caption>
			<col width="95"/><col width="*"/>
			<tr>
				<td class="titleTD"><span class="red">＊</span>업체명(ID)</td>
				<td class="inputTD">
					<?=$prt['client'][$x]['value'] ?>
					<input type="hidden" name="client" value="<?=$prt['client'][$x]['value'] ?>"/>	
				</td>
			</tr>
			<tr>
				<td class="titleTD"><span class="red">＊</span>아이피</td>
				<td class="inputTD">
					<input type="text" name="ip" class="inputText" value="<?=$prt['ip'][$x]['value'] ?>" onkeyup="checkValue('ip',this.value)"/>
					<span id='alert2'></span>
				</td>
			</tr>
			<tr>
				<td class="titleTD"><span class="red">＊</span>사용기간설정</td>
				<td class="inputTD">
					<?
						if($prt['limitday'][$x]['value'] != ""){
							$m_day = substr($prt['limitday'][$x]['value'], 0, 4) . "-" . substr($prt['limitday'][$x]['value'], 4, 2) . "-" . substr($prt['limitday'][$x]['value'], 6, 2);
						}
					?>
					<input type="text" name="limitday" id="calendar" class="inputText" value="<?=$m_day ?>"  readonly/>&nbsp;&nbsp;&nbsp;
					[ <input type="checkbox" name="unlimit" id="unlimit" value="" <?if($prt['limitday'][$x]['value'] == ""){echo "checked";} ?>/> 무기한 사용시 체크 ]
					<!--
					<select name="limitday">
						<option value="30" <?if($data[limitday] == "30"){echo "selected";} ?>>1달</option>
						<option value="90" <?if($data[limitday] == "90"){echo "selected";} ?>>3달</option>
						<option value="180" <?if($data[limitday] == "180"){echo "selected";} ?>>6달</option>
						<option value="365" <?if($data[limitday] == "365"){echo "selected";} ?>>12달</option>
					</select>
					-->
				</td>
			</tr>
			<tr>
				<td class="titleTD">승인여부</td>
				<td class="inputTD">
					<select name="use_yn">
						<option value="Y" <?if($prt['use'][$x]['value'] == "Y"){echo "selected";} ?>>승인</option>
						<option value="N" <?if($prt['use'][$x]['value'] == "N"){echo "selected";} ?>>대기</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="titleTD">비고</td>
				<td class="inputTD">
					<input type="text" name="des" class="inputText" value="<?=$prt['desc'][$x]['value'] ?>" style="width:99%;"/>
				</td>
			</tr>
		</table>

		<table cellpadding="0" cellspacing="0" border="0" class="mt10" width="100%">
			<tr>
				<td align="right">
					<input type="button" onclick="goDelete()" value="삭제" class="btn"/>
					<input type="button" onclick="goModify()" value="수정" class="btn"/>
					<input type="button" onclick="goList()" value="리스트" class="btn"/>
				</td>
			</tr>
		</table>
	</div>

	</form>
</div>

<? include "../../inc/footer.php" ?>