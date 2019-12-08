<?php
  if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 
  set_session('ele_conv_s', true);  
  $MaxLimit_len = 60;
  $ele_today = date("Y-m-d");// 처리일  
 if ($elpr_ukey == 'n'){
    $startdate = $ele_today;
    $enddate = date("Y-m-d",strtotime($ele_today.' +1 month -1 day'));
    $start_type = 'doc';
    $proc_type = 'new';
    $proc_Text = '등록';
    $linktitle = '';   
    $elpr_wurl = '';
 } else {
    $start_type = 'addr';
    $proc_type = 'mod';
    $proc_Text = '수정';
    $prsql = "select * from ele_pr_master 
                        where elpr_mbid = '{$member['mb_no']}' and elpr_ukey = '{$elpr_ukey}' ";                        
    $prsql_row = sql_fetch($prsql);    
    if ( !$prsql_row) {
        alert('정상적인 접근이 아닙니다.',G5_URL.'/government/gov.php');      
    }   
   $linktitle = $prsql_row['elpr_title'];   
   $elpr_wurl = $prsql_row['elpr_wurl'];
   $startdate =  date("Y-m-d",strtotime($prsql_row['elpr_stdt']));
   $enddate  =  date("Y-m-d",strtotime($prsql_row['elpr_eddt']));
 }  
?>
<div id="sub_content">  
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td><img src="/service/images/title10_01_B.png" ></td>
            <td align="right"><img src="/service/images/home.png" width="2" height="2"> <strong>홈</strong> &lt; 홍보 문서</td>        
        </tr>
        </table>
    </td>
</tr>
<tr><td height="20"></td></tr>
</table> 
    <!-- 게시물 상단 버튼 시작 { -->
    <div id="bo_v_top">
        <ul class="bo_v_com">
            <li><a href="/government/gov.php" class="btn_b01" id='prlistbtn'>목록으로 돌아가기</a></li>
        </ul>
    </div>
    <!-- } 게시물 상단 버튼 끝 -->    
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="board_type01">
<tr>
<td class="board_type01_td2">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
          <td><img src="/service/images/txt_gv01.png" ></td>
          </tr>
          <tr>
          <td>
            <div class="inputDiv">
                <label for="smsTitle" id="ol_smsTitle">예) 송파구민에게 알립니다.<strong class="sound_only">필수</strong></label>    
                <input type="text" id="smsTitle" name="smsTitle" required class="required" value="<?=$linktitle?>">              
            </div>          
          </td>
          </tr>
      </table>   
</td>
<tr>
<td class="board_type01_td2">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
          <td><img src="/service/images/txt_gv02.png" ></td>
          </tr>  
          <tr>
      <td>
      	<input type="radio" id='convert_doc_radio' name="convert_radio" value="doc" checked><label for = "convert_doc_radio">문서변환</label>
      	<input type="radio" id='convert_addr_radio' name="convert_radio" value="addr"><label for = "convert_addr_radio">주소 입력</label>
      </td>	
          </tr>
          </table>   
</td>
</tr>
<tr id = "doc_pan">
<td class="board_type01_td">
    <form name="upload_conv_form" method="post" enctype="multipart/form-data" id="svc_fileup_frm">                          
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
    <td><img src="/service/images/sub02_01_txt01.png" ></td>
    </tr>
    <tr>
    <td>
    <input type="file" name="conv_up_file" id="conv_up_file" style="width:480px;" onchange="sel_cFile();">
    <span id="upload_button"><input type="button" value="올리기" onclick="upload();" class="btn_submit"></span></td>
    </tr>
    </table>   
    </form>                
      <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:30px;" >
      <tr>
      <td width="40" height="32"><img src="/service/images/sub02_01_txt02.png"></td>
      <td bgcolor="#FFFFFF" ><div id="file_url_s">변환할 문서를 먼저 선택하십시오.</div>
      <div id='btn_cpyview'><img src="/service/images/btn_copy.png" onclick="Copy_to_clipboard();"></div>                        
      <div id='btn_preview'><img src="/service/images/btn_preview.png"  onclick="opennewwindow();"></div>
      </td>
      </tr>
      </table>
</td>
</tr>
<tr id = "addr_pan">
  <td class="board_type01_td2">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
        <td><img src="/service/images/txt_gv03.png" ></td>
        </tr>
        <tr>
        <td>
          <div class="inputDiv">
              <label for="linkURL" id="ol_linkURL">http://www.schoolnews.or.kr<strong class="sound_only">필수</strong></label>    
              <input type="text" id="linkURL" name="linkURL" required class="required" value="<?=$elpr_wurl?>">              
          </div>          
        </td>
        </tr>
        </table>     
  </td>
</tr>
<tr >
  <td class="board_type01_td2">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
        <td><img src="/service/images/txt_gv04.png" ></td>
        </tr>
        <tr>
        <td>
          <div class="inputDiv">
              <input type="text" id="fromdate" size="12" readonly="readonly" value="<?=$startdate?>"> ~ <input type="text" id="todate" size="12" readonly="readonly"  value="<?=$enddate?>">
          </div>          
        </td>
        </tr>
        </table>     
  </td>
</tr>
<tr><td height="40"></td></tr>  
</table>
<div id="file_up_loading" class="file_up_sloading3">
<img src="/img/ajax_loader.gif"><p>파일 업로드 및 변환중... 잠시만 기다려주십시오...</p>
</div>        
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:40px;background:url(/service/images/line.png) repeat-x top">
<tr>
<td align="center" style="padding:30px 0 0 0;">
  <form name="pr_send_fromDoc" method="post" action="/government/pr_write_update.php" onsubmit="return Send_pr_win(this);">
            <input type="hidden" name="m1" id="m1" value="<?=$member['mb_no']?>">          
            <input type="hidden" name="elpr_ukey" value="<?=$elpr_ukey?>">
            <input type="hidden" name="udoc" id="udoc" value="">          
            <input type="hidden" name="udcn" id="udcn" value="">          
            <input type="hidden" name="stitle" id="stitle" value="">          
            <input type="hidden" name="linktitle" id="linktitle" value="<?=$linktitle?>">
            <input type="hidden" name="prlink" id="prlink" value="<?=$elpr_wurl?>">
            <input type="hidden" name="statdate" id="statdate" value="">
            <input type="hidden" name="enddate" id="enddate" value="">
            <input type="hidden" name="polltype" id="polltype" value="0">                      
            <div id = "btn_smsSend2" >
                        <input type="submit" value="<?=$proc_Text?>" class="btn_submit" onclick="document.pressed=this.value">                        
                        <input type="submit" value="미리보기" class="btn_submit" onclick="document.pressed=this.value">       
            </div>                
   </form>
</tr>
</table>                                    
</div> <!--// sub_content -->
<script type="text/javascript"> 
   function pr_preView(f){
          window.open("","pr_preview");
          f.target = 'pr_preview';
          f.action = "./gov_preview.php";
          f.submit();    
   }
$(document).ready(function() {
  $("input[name=convert_radio]").change(function() {
      var radioValue = $(this).val();
      if (radioValue == 'addr') {
                      $('#doc_pan').hide();
                      $('#addr_pan').show();                                            
      } else {
                      $('#addr_pan').hide();
                      $('#doc_pan').show();                      
      }
  });    

    $.datepicker.regional['ko'] = {
       closeText: '닫기',
       prevText: '이전',
       nextText: '다음',
       currentText: '오늘',
       monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
       monthNamesShort: ['1','2','3','4','5','6','7','8','9','10','11','12'],
       dayNames: ['일','월','화','수','목','금','토'],
       dayNamesShort: ['일','월','화','수','목','금','토'],
       dayNamesMin: ['일','월','화','수','목','금','토'],
       weekHeader: 'Wk',
       dateFormat: 'yy-mm-dd',
       firstDay: 0,
       isRTL: false,
       showMonthAfterYear: true,
       yearSuffix: ''};
  
  $.datepicker.setDefaults($.datepicker.regional['ko']);

  $( "#fromdate" ).datepicker();
  $( "#todate" ).datepicker();

});
<?php if ($proc_type == 'new') { ?>
    $('#addr_pan').hide();
    $('#doc_pan').show(); 
    $('input:radio[name="convert_radio"]:input[value="doc"]').attr("checked", true);  
<?php } else  { ?>  
    $('#doc_pan').hide();
    $('#addr_pan').show();   
    $('#ol_smsTitle').css('visibility','hidden');
    $('#ol_linkURL').css('visibility','hidden');          
    $('input:radio[name="convert_radio"]:input[value="addr"]').attr("checked", true);  
<?php } ?>

$omi = $('#smsTitle');
$omi_label = $('#ol_smsTitle');
$omi_label.addClass('ol_smsTitle');
$olinkurl = $('#linkURL');
$olinkurl_label = $('#ol_linkURL');
$olinkurl_label.addClass('ol_linkURL');

$(function() {
    $omi.focus(function() {
        $omi_label.css('visibility','hidden');
    });
    $omi.blur(function() {
        $this = $(this);
        if($this.attr('id') == "smsTitle" && $this.attr('value') == "") $omi_label.css('visibility','visible');
    });
    $olinkurl.focus(function() {
        $olinkurl_label.css('visibility','hidden');
    });
    $olinkurl.blur(function() {
        $this = $(this);
        if($this.attr('id') == "linkURL" && $this.attr('value') == "") $olinkurl_label.css('visibility','visible');
    });    
});

function Send_pr_win(f){
    document.getElementById('stitle').value = document.getElementById('smsTitle').value;  
    fval = document.getElementById('stitle').value;    
    if (fval == ''){
         alert('연결 제목을 입력 하십시오!!!');
         return false;       
    }    
    $('#linktitle').attr('value',fval);
      var witchProc    = $(':radio[name="convert_radio"]:checked').val();
      if (witchProc == 'addr') {
              fval = document.getElementById('linkURL').value;
              if (fval == ''){
                   alert('연결 주소를 입력하십시오!!!');
                   return false;       
              }    
      } else {
              fval = document.getElementById('udcn').value;
              if (fval == ''){
                   alert('첨부문서를 먼저 변환 하십시오!!!');
                   return false;       
              }                                
    }
    $('#prlink').attr('value',fval);
    fdate = document.getElementById('fromdate').value;
    edate = document.getElementById('todate').value;
    if (fdate >edate ) {
      tmpdate = fdate;
      fdate      = edate;
      edate    = tmpdate;
    }
    $('#statdate').attr('value',fdate);    
    $('#enddate').attr('value',edate);
    // 날짜 체크 로직 필요...
    if(document.pressed == "미리보기") {
        pr_preView(f);
        return;
    }    
<?php if ($is_guest) {?>
      alert("로그인 후 사용가능합니다.");
      document.location.replace("<?php echo G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/gorvernment/gov.php') ?>");
      return false;
<?php      }  ?>   
        f.target = "";
        f.action = "/government/pr_write_update.php";
  return true; 
}
function checkFileExt(ext){ 
  pathpoint = ext.lastIndexOf('.'); 
  filepoint = ext.substring(pathpoint+1,ext.length); 
  filetype = filepoint.toLowerCase(); 
  if (filetype == 'jpg'||filetype == 'jpeg'|| filetype == 'hwp'|| filetype == 'xls'|| filetype == 'xlsx'|| filetype == 'doc'|| filetype == 'docx'|| filetype == 'pdf'){
         return true; 
  }else{
         alert('변환 가능한 문서 형식이 아닙니다!!!');
         return false; 
  } 
} 
function sel_cFile(){
    fval = document.getElementById('conv_up_file').value;
    if (fval == ''){
        document.getElementById('file_url_s').innerHTML = '변환할 문서를 먼저 선택하십시오.';    
        document.getElementById('upload_button').style.display='none';  
        document.getElementById('btn_preview').style.display = 'none';
        document.getElementById('btn_cpyview').style.display = 'none';
    } else {
        if (checkFileExt(fval)) {
            document.getElementById('file_url_s').innerHTML = '선택된 문서를 파일전송 하십시오.';
            document.getElementById('upload_button').style.display='inline';  
        } else {
          document.getElementById('file_url_s').innerHTML = '변환할 문서를 먼저 선택하십시오.';    
          document.getElementById('upload_button').style.display='none';            
        }
        document.getElementById('btn_preview').style.display = 'none';
        document.getElementById('btn_cpyview').style.display = 'none';
    }    
    document.getElementById('file_up_loading').style.display='none';  
}

function opennewwindow(){   
      var url = document.getElementById('file_url_s').innerHTML;
      window.open(url);
}

function Copy_to_clipboard()
{
    var url =  document.getElementById('file_url_s').innerHTML;
    var IE=(document.all)?true:false;
    if (IE) {
      window.clipboardData.setData("Text", url);
      alert('변환문서 주소가 복사되었습니다.\n\nCtrl+V (붙여넣기) 단축키를 이용하시면,\n주소를 붙여 넣으실 수 있습니다.');
    } else {
      temp = prompt("Ctrl+C를 눌러 복사 후 사용[Ctrl+V (붙여넣기) 단축키]하세요", url);
    }
}

function upload(w)
{    
<?php if ($is_guest) {?>
      alert("로그인 후 사용가능합니다.");
      document.location.replace("<?php echo G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/gorvernment/gov.php') ?>");
<?php } else {?>    
    var f = document.upload_conv_form;
    if (typeof w == 'undefined') {
        document.getElementById('upload_button').style.display = 'none';
        document.getElementById('file_up_loading').style.display = 'block';
        document.getElementById('file_url_s').innerHTML = '변환중...';
        f.action = '/service/ele_file_upload.php';
    } else {
        document.getElementById('upload_button').style.display = 'none';      
        document.getElementById('file_up_loading').style.display = 'block';
        document.getElementById('file_url_s').innerHTML = '변환중...';
        f.action = '/service/ele_file_upload.php';
    }
    (function($){
        if(!document.getElementById("fileupload_fr")){
            var i = document.createElement('iframe');
            i.setAttribute('id', 'fileupload_fr');
            i.setAttribute('name', 'fileupload_fr');
            i.style.display = 'none';
            document.body.appendChild(i);
        }
        f.target = 'fileupload_fr';
        f.submit();
    })(jQuery);
<?php }?>      
}
</script>