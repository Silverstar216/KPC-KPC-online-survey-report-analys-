<?php
	include_once('../common.php');
           include_once('./epoll_func.php');    
	if (!$ep) {
		alert('존재하지 않는 문서입니다.', G5_URL);
	}
           $ed_type = 'P';
          $res = sql_fetch("select * from epoll_tmp_master where eplm_ukey='{$ep}' ");

	if (!$res)   {
	    alert('존재 하지 않는 문서입니다.','http://www.schoolnews.or.kr');
	}    
	$eplm_ukey = $res['eplm_ukey'];
	$eplm_mbid = $res['eplm_mbid'];
	$m_title       = $res['eplm_title'];
	$eplm_qcnt  = $res['eplm_qcnt'];
	$polltype      = $res['eplm_gubn'];	
           $as_type     = $res['eplm_type'];   
            

            $arow = get_poll_person_info($as_type);                
	//echo $eplm_ukey.'/'.$eplm_mbid.'/'.$m_title.'/질문 수 : '.$eplm_qcnt.'/'.$polltype.'<br>';
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<?php
	echo '<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10">'.PHP_EOL;
	echo '<meta name="HandheldFriendly" content="true">'.PHP_EOL;
	echo '<meta name="format-detection" content="telephone=no">'.PHP_EOL;
	echo '<link rel="stylesheet" href="'.G5_CSS_URL.'/eleview.css">'.PHP_EOL;
?>
<title><?php echo $m_title; ?></title>
<!--[if lte IE 8]>
<script src="<?php echo G5_JS_URL ?>/html5.js"></script>
<![endif]-->
<script src="<?php echo G5_JS_URL ?>/jquery-1.8.3.min.js"></script>
<script src="<?php echo G5_JS_URL ?>/common.js"></script>
<script src="<?php echo G5_JS_URL ?>/wrest.js"></script>
</head>
<body>
<div id="wrapper">
    <div id="container">
	<div id="container_head">    	
		<div id="container_title"><?=$m_title?></div>	
	</div> 
<?php if ($polltype != "1") { ?>
<p>"본 설문조사는 익명이 보장되며 1회에 한해 응답하실 수 있습니다"</p>
<? } ?>         
	<form name="felpoll" onsubmit="return fpoll_submit(this);" method="post">	 
	<input type="hidden" name="eplm_ukey" id="eplm_ukey" value='<?=$eplm_ukey?>'>			
	<input type="hidden" name="eplm_sk" id="eplm_sk" value='<?=$sk?>'>			
          <input type="hidden" name="epls_rmip" value='<?=$epls_rmip?>'>          
          <input type="hidden" name="epls_xhost" value='<?=$epls_xhost?>'>          
          <input type="hidden" name="epls_agent" value='<?=$epls_agent?>'>          
          <input type="hidden" name="epls_host" value='<?=$epls_host?>'>          
<?php if ($polltype == "1")	{ 
?>
<div class="tbl_head01 tbl_wrap">   
<table>
          <tr>
<?php               
$epli_icnt = $arow['epli_icnt'];

for($kdx=0;$kdx<$epli_icnt;$kdx++){ 
    echo '<td ><label for="i'.$kdx.'">'.$arow['epli_title'][$kdx].'</label><input class="'.$arow['epli_size'][$kdx].' required"  required type="text" name="i'.$kdx.'" id="i'.$kdx.'"></td>';
}
?>            
          </tr>
 </table>               
</div>
<?php	
}	// poo type if end 
 	for ($idx=0;$idx<$eplm_qcnt;$idx++){
 		$eplh_ilbh = $idx+1; 		
		$resq = sql_fetch("select *  from epoll_tmp_question where eplh_ukey='{$eplm_ukey}' and eplh_ilbh = '{$eplh_ilbh}' ");
		//echo "select *  from epoll_question where eplh_ukey='{$eplm_ukey}' and eplh_ilbh = '{$eplh_ilbh}' <br>";
                     if (!$resq['eplh_ilbh']) continue;
 		$eplh_title = $resq['eplh_title'];
 		$eplh_title = ($idx+1).'. '.$eplh_title;
 		$eplh_acnt    = $resq['eplh_acnt'];
 		$eplh_chk     = $resq['eplh_chk'];
                      $eplh_dup    = $resq['eplh_dup'];                     
		//echo $eplh_title.'/'.$eplh_acnt.'/'.$eplh_chk.'<br>';		 		
?> 		
	<div class="tbl_head01 tbl_wrap">
		<table >
			<thead>
			<tr>
			<th scope="col" colspan = "3"><?=$eplh_title?>
	<input type="hidden" name="eplh_rsp[]" type="text"  id="<?='rsp_'.$eplh_ilbh?>" value=''>
	<input type="hidden" name="eplh_chk[]" type="text" id="<?='chk_'.$eplh_ilbh?>" value='' gubn='<?=$eplh_chk?>'>
			</th>
			</tr>
			</thead>
			<tbody class="question_ele">							
<?php
 		for($jdx=0;$jdx<$eplh_acnt;$jdx++){
 			$epla_asbh = $jdx+1;
			$resa = sql_fetch("select * from epoll_tmp_qahist where epla_ukey='{$eplm_ukey}' and epla_ilbh = '{$eplh_ilbh}' and epla_asbh = '{$epla_asbh}' ");
                                if (!$resa['epla_asbh']) continue;
 			$epla_asbh = $epla_asbh.' ) ';
 			$epla_title = $resa['epla_title'];
?>
			<tr class='answerp'>
				<td class="td_num"><?=$epla_asbh?></td>
				<td class="td_chk"><input type="checkbox" qnum="<?=$eplh_ilbh?>" gubn="<?=$epla_asbh?>" class = "answerchk" dp='<?=$eplh_dup?>' ></td>
				<td class="td_subject"><?=$epla_title?></td>
			</tr>
<?php 		} 
	if ($eplh_chk=='Y') {	
?> 
			<tr class="answert">   
				<td class="td_subject" colspan = "3"><input id='ext_<?=$eplh_ilbh?>' type="text" value=""></td>				
			</tr>
<?php 		}   ?>
			</tbody>			
		</table>
		<div class="line_dir"></div>
	</div>
<?php } ?>	
<div class="btn_confirm01 btn_confirm">
<?php if ($polltype == "1") { ?>    
	        <input type="submit" value="회신 완료" class="btn_submit">
<?php } else { ?>    
          <input type="submit" value="설문 완료" class="btn_submit">
<?php } ?>                
	        <button class="btnT1" type="button" onclick="window.close();">닫기</button>
</div>        			
</form>	 	
<!-- } 콘텐츠 끝 -->
<hr>
<!-- 하단 시작 { -->
        </div>        
</div>
<div id="ft">
<p>감사합니다. </p>
[미리보기] 이므로 설문 완료 버튼은 작동 하지 않습니다.
</div>
<!-- } 하단 끝 -->
<script>
$(function(){ 
     $("body").on("click",".answerchk", function(e) {
        thischk = $(this).attr('gubn');
        thisdp = $(this).attr('dp');        
        var pidObj = $(this).parent().parent().parent().children('.answerp');
        
        pcnt = pidObj.size();
        for (idx=0;idx<pcnt;idx++){
            ppObj = pidObj.eq(idx).children().children('.answerchk');
            targetchk = ppObj.eq(0).attr('gubn');
            qnum = ppObj.eq(0).attr('qnum');
            if (thisdp ==1) {
                    if (thischk != targetchk) {
                        	ppObj.eq(0).attr('checked',false);	
                    } 
            }
            if (ppObj.eq(0).is(':checked') == true) {
           	$('#rsp_'+qnum).attr('value',idx+1);
            }
        }        
    });     
 });
function fpoll_submit(f)
{
<?php if ($polltype  == 1) { 
        if ($as_type == '1') {?>
        if ($('#sc_grade').attr('value') == '') {
            alert('학년을 반드시 입력하세요!');
            $('#sc_grade').focus();
            return false;
        }
        <?php }?>
        <?php if ($as_type <> '3') {?>
            if ($('#sc_class').attr('value') == '') {
                <?php if ($as_type == '2') {?>
                    alert('소속을 반드시 입력하세요!');                
                <?php } else { ?>
                    alert('반을 반드시 입력하세요!');
                <?php } ?>
                $('#sc_class').focus();
                return false;
            }
        <?php } ?>
        if ($('#sc_name').attr('value') == '') {
            alert('이름을 반드시 입력하세요!');
            $('#sc_name').focus();
            return false;
        }            
<?php
}    
?>    
    var qtable = $('.question_ele');
    for (idx=0;idx<qtable.size();idx++){
    	 pidObj = qtable.eq(idx).children('.answerp');
    	 var pcnt = pidObj.size();
    	 var must_chk = false;    
        	 var cnum = idx+1;    	 
    	 if (pcnt == 0) {
    	     var ctxt = $('#ext_'+cnum).attr('value'); 	
    	     if (ctxt == '') {
    	     	alter('답변을 입력하세요!');
    	     	$('#ext_'+cnum).focus;
    	     	return false;
    	     }
    	 } 
        	 for (jdx=0;jdx<pcnt;jdx++){
            	ppObj = pidObj.eq(jdx).children().children('.answerchk');
            	//targetchk = ppObj.eq(0).attr('gubn');
            	qnum = ppObj.eq(0).attr('qnum');
            	extTxtgubn = '';
            	
	           if (ppObj.eq(0).is(':checked') == true) {
	           	$rtnval = jdx+1;
	          		$('#rsp_'+qnum).attr('value',$rtnval);
	          		must_chk = true;
	           }
        	 }

        	 cgb = $('#chk_'+cnum).attr('gubn');
            var ctxt = '';
        	 if (cgb == "Y") {
        	  	ctxt = $('#ext_'+cnum).attr('value'); 	
        	 } else {
        	 	ctxt = '';
        	 }        	         	 
        	 if (must_chk ==  false) {
        	 	if (cgb == 'N') {
        	 		alert(cnum+'번 질문의 답변 하나는 선택해야 합니다.');
        	 		return false;
        	           } else {
        	           	$('#rsp_'+cnum).attr('value','');	
        	           }
        	 }
        	 $('#chk_'+cnum).attr('value',ctxt);
    }
    return true;
}
</script>
</body>

</html>
<?php echo html_end(); // HTML 마지막 처리 함수 : 반드시 넣어주시기 바랍니다. ?>