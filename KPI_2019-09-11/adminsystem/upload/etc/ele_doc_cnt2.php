<?php 
	 if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
           $epls_rmip = $_SERVER['REMOTE_ADDR'];
           $epls_xhost = $_SERVER['HTTP_X_FORWARDED_FOR'];
           $epls_agent = $_SERVER['HTTP_USER_AGENT'];
           $epls_host = $_SERVER['HTTP_HOST'];

	if (!isset($ep)) {
		alert('존재하지 않는 문서입니다.', G5_URL);
	}  else {
		if ($ep == '')	{
			alert('존재하지 않는 문서입니다.', G5_URL);
		}
	}
	if (isset($sk)) { 
		// 건별 로그 인써트 
		if ($sk == "")	{
			$sk = '';
		}
	} else {
			$sk = '';		
	}
	$sms_txt = "insert into edoc_answer (ed_type,ed_udoc,ed_usms,addr,hostip,forwdip,browser,crtime) values ";
	$sms_txt = $sms_txt."('{$ed_type}','{$ep}','{$sk}','{$epls_rmip}','{$epls_host}','{$epls_xhost}','{$epls_agent}','".G5_TIME_YMDHIS."') ";
	sql_query($sms_txt);
	$po = sql_fetch(" select * from edoc_master where edoc_ukey = '{$ep}' ");
	if (!$po['edoc_ukey']) {
		alert('존재하지 않는 문서입니다.', G5_URL);
	}
	$filename = $po['edoc_wurl'];
	$edoc_attach_poll_id = $po['edoc_attach_poll_id'];
	$poll_type = '';
	$hs_hp = '';	
	$hs_name = '';		
	if ($edoc_attach_poll_id) {
		if ($edoc_attach_poll_id > 0) {
			// 설문문서인지 아닌지 체크한다. 
			$ppo = sql_fetch("select eplm_gubn from epoll_master where eplm_ukey = '{$edoc_attach_poll_id}' ");
			if ($ppo) {
				$poll_type = $ppo['eplm_gubn'];	
			} else {
				$poll_type = '';
			}
			if (($sk != '') &&($poll_type != '')){
				$smsHIs = sql_fetch("select hs_hp, hs_name from sms5_history where hs_no = '{$sk}' ");
				if ($smsHIs) {
					$hs_hp = $smsHIs['hs_hp'];	
					$hs_name = $smsHIs['hs_name'];	
				} else {
					$hs_hp = '';	
					$hs_name = '';	
				}				
			}
		}
	}
?>