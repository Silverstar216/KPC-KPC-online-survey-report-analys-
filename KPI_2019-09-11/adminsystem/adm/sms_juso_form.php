<?php
$sub_menu = "300100";
include_once('./_common.php');
auth_check($auth[$sub_menu], 'w');
$g5['title'] = '주소록 올리기';
include_once ('./admin.head.php');
?>
<style type="text/css">
    #sms5_fileup_frm .sms_fileup_hide {display:none;border:0}
    #upload_button {display:none;border:0}
    #sms5_fileup iframe {border : 0px;}
    #sms5_fileup .btn_submit {padding : 5px;}
    #upload_info {margin-top: 15px;}

</style>
<div id = 'findgk' class="tbl_frm01 tbl_wrap">
<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get" onsubmit="return false;">
<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
    <option value="mb_nick"<?php echo get_selected($_GET['sfl'], "mb_nick"); ?>>기관명</option>        
    <option value="mb_name"<?php echo get_selected($_GET['sfl'], "mb_name"); ?>>이름</option>
    <option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id"); ?>>회원아이디</option>    
    <option value="mb_level"<?php echo get_selected($_GET['sfl'], "mb_level"); ?>>권한</option>
    <option value="mb_email"<?php echo get_selected($_GET['sfl'], "mb_email"); ?>>E-MAIL</option>
    <option value="mb_tel"<?php echo get_selected($_GET['sfl'], "mb_tel"); ?>>전화번호</option>
    <option value="mb_hp"<?php echo get_selected($_GET['sfl'], "mb_hp"); ?>>휴대폰번호</option>
    <option value="mb_point"<?php echo get_selected($_GET['sfl'], "mb_point"); ?>>포인트</option>
    <option value="mb_datetime"<?php echo get_selected($_GET['sfl'], "mb_datetime"); ?>>가입일시</option>
    <option value="mb_ip"<?php echo get_selected($_GET['sfl'], "mb_ip"); ?>>IP</option>
    <option value="mb_recommend"<?php echo get_selected($_GET['sfl'], "mb_recommend"); ?>>추천인</option>
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
<input type="button" class="btn_submit" value="검색" onclick="find_id(this);">
</form>
<div id="gkpan"></div>
</div>
<div class="tbl_frm01 tbl_wrap">
<form name="upload_form" method="post" enctype="multipart/form-data" id="sms5_fileup_frm">
<div id="sms5_fileup">
    <label for="csv">파일선택</label>    
    <input type="file" name="csv" id="csv" onchange="sel_upbtn()">
    <input type="hidden" name="gk_no" id="gk_no" value=''>
    <input type="hidden" name="gk_nm" id="gk_nm" value=''>
    <span id="upload_button">
        <input type="button" value="파일전송" onclick="upload();" class="btn_submit">
    </span>
    <span id="uploading" class="sms_fileup_hide">
        파일을 업로드 중입니다. 잠시만 기다려주세요.
    </span>
     
    <div id="upload_info" class="sms_fileup_hide"></div>
    <div id="register" class="sch_last sms_fileup_hide">
        휴대폰번호를 저장중 입니다. 잠시만 기다려주세요.
    </div>
</div>
</form>
</div>
<script>
function sel_upbtn(){
    fval = document.getElementById('csv').value;
    if (fval == ''){
        document.getElementById('upload_button').style.display='none';  
        document.getElementById('upload_info').style.display='none';
    } else {
            document.getElementById('upload_button').style.display='inline';  
            document.getElementById('upload_info').style.display='block';
    }    
    var ifrmae = window.frames['fileupload_fr'];
    if(ifrmae){
        ifrmae.document.body.innerHTML = '';             
    }
}
function upload(w)
{
    if (!test_check()) { return false;}
<?php if ($is_guest) {?>
      alert("로그인 후 사용가능합니다.");
      document.location.replace("<?php echo G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/serv.php?m1='.$pgMNo.'&m2='.$pgMNo1) ?>");
      return false;
<?php } else {?>                 
    var f = document.upload_form;        
    if (typeof w == 'undefined') { 
        document.getElementById('upload_button').style.display = 'none';
        document.getElementById('uploading').style.display = 'inline';
        document.getElementById('upload_info').style.display = 'none';
        f.action = './jusofileup.php?confirm=1';
    } else {
        document.getElementById('upload_button').style.display = 'none';
        document.getElementById('upload_info').style.display = 'none';
        document.getElementById('register').style.display = 'block';
        f.action = './jusofileup.php';
    }
    (function($){
        if(!document.getElementById("fileupload_fr")){

            var i = document.createElement('iframe');            
            i.setAttribute('id', 'fileupload_fr');
            i.setAttribute('name', 'fileupload_fr');            
            i.setAttribute('width', '100%');            
            i.setAttribute('border', '0');
            i.setAttribute('onload',"autoResize(this);"); 
            //i.style.display = 'none';
            document.getElementById("sms5_fileup").appendChild(i);
        }
        $('#fileupload_fr').html('');
        f.target = 'fileupload_fr';
        f.submit();
    })(jQuery);
<?php } ?>    
}
function autoResize(i)
{
    var iframeHeight=
    (i).contentWindow.document.body.scrollHeight;
    (i).height=iframeHeight+20;
}

$('#gkpan').hide();

var dup_send_flag = false;
function find_id(){
  if (dup_send_flag == true) {
    alert('전송중입니다!!!');
    return false;
  } 
  sfl = $('#sfl').attr('value');
  stx = $('#stx').attr('value');

  var params = { sfl : sfl, stx : stx };        
  $.ajax({
  url: "<?=G5_URL?>/adm/member_find_ajax.php",
  cache:false,
  timeout : 30000,
  data : params,
  dataType:'html',
  type:'post',
  success: function(data) {  
        dup_send_flag = false;
        $('#gkpan').html(data);
        $('#gkpan').show();
  },
  error: function (xhr, ajaxOptions, thrownError) {
        dup_send_flag = false;    
      }
  });  
}

function test_check(){
    var ss_cnt = $('.id_chk').size();
    if (ss_cnt == 0) { alert('사용자를 먼저 선택해 주세요.'); return false;}
    var selcnt = 0;
    for (idx=0;idx<ss_cnt;idx++){
        var ii = $('.id_chk').eq(idx).attr('value');
        if ($('.id_chk').eq(idx).is(':checked') == true){
            selcnt++;
            $('#gk_no').attr('value',$('.id_chk').eq(idx).attr('value'));
            $('#gk_nm').attr('value',$('.id_chk').eq(idx).attr('nm'));            
        }        

    }
    if (selcnt == 0) { alert('사용자를 먼저 선택해 주세요.'); return false;}
    else if (selcnt > 1) { alert('사용자는 한명만 선택해 주세요.'); return false;}
    return true;
}
</script>
<?php
include_once ('./admin.tail.php');
?>
