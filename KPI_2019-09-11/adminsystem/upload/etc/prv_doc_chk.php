<?php 
	if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="X-UA-Compatible" content="IE=10,chrome=1">
<title>스쿨뉴스</title>
<!--[if lte IE 8]>
<script src="http://www.schoolnews.or.kr/js/html5.js"></script>
<![endif]-->
<script src="http://www.schoolnews.or.kr/js/jquery-1.8.3.min.js"></script>
<style type="text/css">
	body {background:url(<?=G5_CSS_URL?>/images/bg.png) repeat-x; text-align: center;}
	#chkdocform_logo{position:relative;top:-15px;margin: 0 auto;}
	.sound_only {display:inline-block !important;position:absolute;top:0;left:0;margin:0 !important;padding:0 !important;font-size:0;line-height:0;border:0 !important;overflow:hidden !important}
	.chkdocform_input{margin-bottom: 15px;}
	.btn_confirm {text-align:center} /* 서식단계 진행 */
	.btn_submit {padding:8px;border:0;background:#ff3061;color:#fff;letter-spacing:-0.1em;cursor:pointer}	
	a.btn_cancel {display:inline-block;padding:7px;border:1px solid #ccc;background:#fafafa;color:#000;text-decoration:none;vertical-align:middle}
	#emsg{color: red;font-weight: bold;}
</style>
</head>
<body >
	<img id="chkdocform_logo" src="<?=G5_URL?>/img/logo_simple.png">	
	<form id="chkdocform" name="chkdocform" action="./prv_chk.php" onsubmit="return chk_doc_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
	            <input type="hidden" name="ep" value="<?php echo $ep ?>">
	            <input type="hidden" name="uk" value="<?php echo $uk ?>">
    		<input type="hidden" name="hk" value="<?php echo $hk ?>">
    		<input type="hidden" name="cp" value="<?php echo $compchk_val ?>">
    		<div class="chkdocform_input">		
	     		<label for="mnumber">정보 보호를 위해 수신번호 뒤4자리를 입력하세요!!<strong class="sound_only">필수</strong></label>
                        	<input type="password" name="mnumber" id="mnumber" class="frm_input required" minlength="4" maxlength="4" value="<?php echo $hk?>">
                        	<div id="emsg"><?=$emsg?></div>
		</div>		                 		       
		<div class="btn_confirm">
		    <input type="submit" value="확인" id="btn_submit" class="btn_submit" accesskey="s">
		    <a href="<?php echo G5_URL ?>" class="btn_cancel">취소</a>
		</div>		
	</form>
</body>
<SCRIPT TYPE="text/javascript">
function chk_doc_submit(f)
{
        if (f.mnumber.value.length == 4) {
        	return true;
        } else {
            alert("수신 전화번호 뒷 4자리를 입력하십시오.");
            f.mnumber.focus();
            return false;
        }
}
</SCRIPT>
</html>
