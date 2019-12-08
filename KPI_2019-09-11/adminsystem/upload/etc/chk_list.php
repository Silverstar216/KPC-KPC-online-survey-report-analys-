<?php
	include_once('../../common.php');
	$nextUrl = G5_URL.'/upload/etc/susin_menu.php?ep='.$ep.'&sk='.$sk;	
	if ($ep =='') {
		$nextUrl = G5_URL;
	} else if ($sk == '') {
		$qsql = "select (case mb_tel when '' then '0000' when null then '0000' else mb_tel end) as hs_hp ".
		             "from g5_member,edoc_master where edoc_ukey = '{$ep}'  and mb_id = edoc_mbid ";
		$hs = sql_fetch($qsql);
		if ($hs['hs_hp']) {
			$end4n = substr($hs['hs_hp'],-4,4);
			if ($end4n == $mnumber){
				$inkey = $cp.':0';
				set_session('whos_call',$inkey);	
			} else {
				$nextUrl = G5_URL.'/upload/etc/susin_menu.php?ep='.$ep.'&emsg='.urlencode('회신 전화번호 끝자리 4자리(미등록시 0000)를 입력하세요!');				
			}			
		} else {
			$nextUrl = G5_URL.'/upload/etc/susin_menu.php?ep='.$ep.'&emsg='.urlencode('회신 전화번호 끝자리 4자리(미등록시 0000)를 입력하세요!');							
		}
	} else {
		$qsql = "SELECT hs_hp,bk_no FROM sms5_history where hs_no = '{$sk}' ";
		$hs = sql_fetch($qsql);
		if ($hs['hs_hp']) {
			$end4n = substr($hs['hs_hp'],-4,4);
			if ($end4n == $mnumber){
				$inkey = $cp.':'.$hs['bk_no'];
				set_session('whos_call',$inkey);	
				$nextUrl = G5_URL.'/upload/etc/susin_menu.php?ep='.$ep.'&sk='.$sk;
			} else {
				$nextUrl = G5_URL.'/upload/etc/susin_menu.php?ep='.$ep.'&sk='.$sk.'&emsg='.urlencode('끝자리를 다시 확인하세요.');				
			}			
		} else {
			$nextUrl = G5_URL.'/upload/etc/susin_menu.php?ep='.$ep.'&sk='.$sk.'&emsg='.urlencode('끝자리를 다시 확인하세요.');							
		}
	}
	//echo $nextUrl;
	goto_url($nextUrl);
?>