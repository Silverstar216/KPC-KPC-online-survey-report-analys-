<?php
include_once ('../common.php');
include_once ('./ele_file_convert.php');//chkd
?>
<script src="<?php echo G5_JS_URL ?>/jquery-1.8.3.min.js"></script>
 <script type="text/javascript"> 
parent.document.getElementById("upload_button").style.display = "inline"; 
parent.document.getElementById("file_up_loading").style.display = "none";
parent.document.getElementById('upload_button').style.display='none';
parent.document.getElementById('upload_conv_form').style.display = 'none';        
parent.document.getElementById('udoc').value = '<?=$curr_ukey?>';
parent.document.getElementById('udcn').value = '<?php echo $S_url_nm ?>'; 
parent.document.getElementById("file_url_s").innerHTML = "<?php echo $S_url_nm ?>"; 

<?php
    if ($varCnt == 0) {
?>
    parent.document.getElementById('form_r').value = '';
    $('#edufiine_select', parent.document).css("display","none");
<?php
        alert_after('고지 변수가 없습니다.');
    } else if ($varCnt == -1) {
?>
    parent.document.getElementById('form_r').value = 'D';
    $('#edufiine_select', parent.document).css("display","inline-block");
<?php
    } else {
?>
    parent.document.getElementById('form_r').value = 'E';    
    $('#edufiine_select', parent.document).css("display","none");
    
<?php
    }
?>
parent.document.getElementById("data_file_up_pan").innerHTML =  
"<form id= 'goji_conv_form' name='goji_conv_form' method='post' enctype='multipart/form-data' class='table_fileup_frm'> "+
"<input type='hidden' name='varCount' value = '<?=$varCnt?>'>"+
"<input type='hidden' name='doc_ukey' value = '<?=$curr_ukey?>'>"+
"<input type='hidden' name='goji_type' id='goji_type' value = ''>"+
"<input type='hidden' name='edu_year' id='edu_year' value = ''>"+
"<input type='hidden' name='edu_kind' id='edu_kind' value = ''>"+
"<table width='100%' border='0' cellspacing='0' cellpadding='0'> "+
"<tr><td>개별자료문서 (엑셀파일)</td></tr> "+
"<tr><td><input type='radio' name='edcv_check' id='edcv_Y' value='Y' checked='checked'><label for='edcv_Y'>번호확인</label><input type='radio' name='edcv_check' id='edcv_N' value='N'><label for='edcv_N'>확인안함</label>&nbsp;&nbsp;(개인을 특정할 수 있는 정보가 포함된 경우 수신자만 조회할 수 있도록 번호확인을 체크해주십시오!!)</td></tr> "+
"<tr><td><input type='file' name='goji_up_file' id='goji_up_file' style='width:580px;' onchange='sel_gFile();'>"+
"<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span id='goji_button'><input type='button' value='올리기' onclick='dataup();' class='btnT1'></span></td></tr>"+
"</table></form> "+
"<table width='100%' border='0' cellspacing='0' cellpadding='0' style='margin-top:30px;' > "+
"<tr><td bgcolor='#FFFFFF' ><div id='goji_help_s'>데이터 엑셀 파일을 선택하십시오!</div></td></tr>"+
"</table> ";
</script>
<?php
function alert_after($str) {
    echo "<script>
    parent.document.getElementById('upload_conv_form').style.display = 'inline';        
    parent.document.getElementById('upload_button').style.display = 'inline';    
    parent.document.getElementById('file_up_loading').style.display = 'none';
    parent.document.getElementById('udoc').value = '';    
    parent.document.getElementById('file_url_s').innerHTML = '변환전';
    </script>";
    alert_just($str);
}
?>
