<?php
  if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가       
?>
<div id="replywrapPan">
	<div id="replyPan">
		<div id="replyheader">
				<div class="btn_reply btn_adds"><a onclick="close_reply();" href="javascript:;">닫기</a></div>			
				<div class="btn_reply btn_adds"><a onclick="reply_request();" href="javascript:;">발신번호 추가</a></div>				
				<div id="replytitle">발신번호 관리</div>
	    </div>
	    <div id="reply_table">
			<table id="reply_htbl">
				<tr>
					<th class="r_ph">발신번호</th>
					<th class="r_bg">비고</th>
					<th class="r_st">상태</th>					
					<th class="r_us">사용</th>
				</tr>
			</table>
			<div id="replayResult">			
				<table>
					<tr>
						<td>조회중입니다.</td>
					</tr>			
				</table>
			</div>
		</div>
		<div id="reply_input_pan">
				<label for="replyhp">발신번호</label>
				<input type="text" name="replyhp" id="replyhp" class="frm_input" size="20" maxlength="13">
				<label for="reply_bigo">비고</label>
				<input type="text" name="reply_bigo" id="reply_bigo" class="frm_input" size="40" maxlength="40">
				<button type="button" class="reply_floater reply_floater_btn" onclick="reply_save()">저장</button>
				<button type="button" class="reply_floater reply_floater_btn" onclick="reply_del()">삭제</button>                                                
		</div>
	</div>
</div>	
<script type="text/javascript">	
    var reply_type = 'n';
	function usereply(cr){
		var ph = $(cr).parent().children('.r_ph').html(); 
		$('#wr_reply').attr('value',ph);
		close_reply();
	}
	function clickRow(cr){
		var ph = $(cr).children('.r_ph').html(); 
		var st = $(cr).children('.r_st').html();
		var bg = $(cr).children('.r_bg').html();
		if (st == '인증완료') {
		} else {
		}
		reply_type = ph;
		$('#replyhp').attr('readonly',true);
		$('#replyhp').attr('value',ph);
		$('#reply_bigo').attr('value',bg);		
		$('#reply_bigo').focus();
	}
	function reply_mng(){
		<?php if ($is_guest) { ?>
			alert('로그인 후 이용가능합니다.');
		<?php } else { ?>
			Get_reply_list();
	    	$('#replywrapPan').fadeIn(200);			
		<?php }  ?>					
	}
	function close_reply(){
		$('#replywrapPan').hide();
	}
	function reply_request(){
		$('#replyhp').attr('value','');
		$('#reply_bigo').attr('value','');
		reply_type = 'n';
		$('#replyhp').attr('readonly',false);
		$('#replyhp').focus();
	}	
	function reply_save(){
		if (!check_replay('replyhp')){
            var p_reply2 = document.getElementById('replyhp');
            alert('발신번호 형식이 올바르지 않습니다.');
            p_reply2.focus();
            return false;            
        }
        ph = document.getElementById('replyhp').value;
        bg = document.getElementById('reply_bigo').value;
        if (reply_type == 'n') {
			if(!confirm("발신번호를 인증요청 등록하시겠습니까?")) {
				return false;
			}
		} else if (reply_type == 'd') {
			if(!confirm("발신번호("+ph+")를 삭제 하시겠습니까?")) {
				return false;
			}
        } else {
			if(!confirm(reply_type+" 수정하시겠습니까?")) {
				return false;
			}
        }
        $.ajax({
            url: "/service/reply_save.php",
            type: "POST",
            async: false,
            cache: false,
            timeout : 30000,
			dataType: "json",
			data: {
                "ph": ph ,
                "bg": bg,
                "pr": reply_type
            },
            success: function(data) {
            	if (data.rtn == 'S'){
            		alert('처리되었습니다.');
            		Get_reply_list();
            	} else if (data.rtn == 'E'){
            		alert(data.err);
            	} else {
            		$('#replayResult').html('<div class="replyText">로그인후 이용가능합니다.</div>');
            	}
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });	
	}	
	function reply_del(){
		var chkde = $('#replyhp').attr('value');
		if (chkde == ''){
			alert('먼저 삭제할 번호를 선택하여주십시오!');
			return false;
		}
		reply_type = 'd';
		reply_save();
	}	


	function Get_reply_list(){
		$('#replayResult').html('');
		$.ajax({
            url: "/service/reply_getlist.php",
            async: false,
            cache: false,
            timeout : 30000,
			dataType: "json",
            success: function(data) {
            	if (data.rtn == 'S'){
            		if (data.cnt > 0){
            			var rtable = '<table>';
            			for (i=0;i<data.cnt;i++) {
            				rtable += '<tr onclick="clickRow(this)">';
            				rtable += '<td class="r_ph">';
            				rtable += data.listarry[i].phone;
            				rtable += '</td>';
            				rtable += '<td class="r_bg">';
            				rtable += data.listarry[i].bigo;
            				rtable += '</td>';
            				rtable += '<td class="r_st">';
            				rtable += data.listarry[i].proccess;
            				rtable += '</td>';
            				if (data.listarry[i].uses == 1) {
								usestr = '<button type="button">사용하기</button>';
								useclick = ' onclick="usereply(this);"';
            				} else {
            					usestr = '';
            					useclick = '';
            				}            				
							rtable += '<td class="r_us"'+useclick+'>';
            				rtable += usestr;
            				rtable += '</td>';
            				rtable += '</tr>';
            			}
            			rtable += '</table>';
            			$('#replayResult').html(rtable);
            		} else {
            			$('#replayResult').html('<div class="replyText">발신번호를 먼저 등록하십시오!</div>');
            		}
            		reply_request();
            	} else {
            		$('#replayResult').html('<div class="replyText">로그인후 이용가능합니다.</div>');
            	}
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });		
	}
</script>