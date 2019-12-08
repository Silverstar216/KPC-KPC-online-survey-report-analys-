<? include "../../inc/header.php" ?>

<!-- datepicker -->
<link type="text/css" href="../../js/calendar/themes/base/ui.all.css" rel="stylesheet" />
<script type="text/javascript" src="../../js/jquery-1.6.min.js"></script>
<script type="text/javascript" src="../../js/calendar/ui/ui.core.js"></script>
<script type="text/javascript" src="../../js/calendar/ui/ui.datepicker.js"></script>
<script type="text/javascript" src="../../js/calendar/ui/ui.datepicker-ko.js"></script>
<!-- datepicker -->

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
function goCreate(){
	var frm = document.frm;
	if(frm.client.value == ""){
		alert("필수입력(업체명)");
		frm.client.focus();
	}else if(frm.ip.value == ""){
		alert("필수입력(아이피)");
		frm.ip.focus();
	}else if(frm.limitday.value == "" && document.getElementById("unlimit").checked == false){
		alert("필수입력(사용기간설정)");
		frm.limitday.focus();
	}else if(frm.validate1.value == "N"){
		alert("업체명(ID)의 유효성을 체크해주세요.");
		frm.client.focus();
	}else if(frm.validate2.value == "N"){
		alert("아이피의 유효성을 체크해주세요.");
		frm.ip.focus();
	}else{
		if(document.getElementById("unlimit").checked == true){
			frm.unlimit.value = "Y";
		}
		frm.cmd.value = "C";
		frm.submit();
	}
}
function checkValue(gubun,value){
	$.post("check.php" , {'gubun':gubun, 'value':value}
	, function (data){   
		if(gubun == "id"){
			if(data.result == "N"){
				document.getElementById('alert1').innerHTML = "<font class='green'>사용가능한 ID입니다.</span>";
				document.getElementById('validate1').value = "Y";
			}else{
				document.getElementById('alert1').innerHTML = "<font class='red'>이미 등록된 ID입니다.</span>";
				document.getElementById('validate1').value = "N";
			}
		}else{
			if(data.result == "N"){
				document.getElementById('alert2').innerHTML = "<font class='green'>사용가능한 IP입니다.</span>";
				document.getElementById('validate2').value = "Y";
			}else{
				document.getElementById('alert2').innerHTML = "<font class='red'>이미 등록된 IP입니다.</span>";
				document.getElementById('validate2').value = "N";
			}
		}
	 }, "json");  
}
</script>

<div id="centerBox">
	<form name="frm" method="post" action="process.php">
	<input type="hidden" name="cmd" value="C"/>
	<input type="hidden" name="validate1" id="validate1" value="N"/>
	<input type="hidden" name="validate2" id="validate2" value="N"/>

	<div style="width:900px;">
		<table cellpadding="0" cellspacing="0" class="EditTable">
			<caption class="pb5"><b>업체등록</b></caption>
			<col width="95"/><col width="*"/>
			<tr>
				<td class="titleTD"><span class="red">＊</span>업체명(ID)</td>
				<td class="inputTD">
					<p>
						<input type="text" name="client" class="inputText" value="" onkeyup="checkValue('id',this.value)"/>&nbsp;
						<span id='alert1'></span>
					</p>
					<p style="margin-top:3px;">※ '/upload/(ID)' -> 폴더명이 될 이름입니다(영문 또는 숫자로 작성해주세요.한글x)</p>
				</td>
			</tr>
			<tr>
				<td class="titleTD"><span class="red">＊</span>아이피</td>
				<td class="inputTD">
					<input type="text" name="ip" class="inputText" value="" onkeyup="checkValue('ip',this.value)"/>
					<span id='alert2'></span>
				</td>
			</tr>
			<tr>
				<td class="titleTD"><span class="red">＊</span>사용기간설정</td>
				<td class="inputTD">
					<input type="text" name="limitday" id="calendar" class="inputText" value="" readonly/>&nbsp;&nbsp;&nbsp;
					[ <input type="checkbox" name="unlimit" id="unlimit" value=""/> 무기한 사용시 체크 ]
					<!--
					<select name="limitday">
						<option value="30" selected>1달</option>
						<option value="90">3달</option>
						<option value="180">6달</option>
						<option value="365">12달</option>
					</select>
					-->
				</td>
			</tr>
			<tr>
				<td class="titleTD">승인여부</td>
				<td class="inputTD">
					<select name="use_yn">
						<option value="Y" selected>승인</option>
						<option value="N">대기</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="titleTD">비고</td>
				<td class="inputTD">
					<input type="text" name="des" class="inputText" value="" style="width:99%;"/>
				</td>
			</tr>
		</table>

		<table cellpadding="0" cellspacing="0" border="0" class="mt10" width="100%">
			<tr>
				<td align="right">
					<input type="button" onclick="goCreate()" value="등록" class="btn"/>
					<input type="button" onclick="goList()" value="리스트" class="btn"/>
				</td>
			</tr>
		</table>
	</div>

	</form>
</div>

<? include "../../inc/footer.php" ?>