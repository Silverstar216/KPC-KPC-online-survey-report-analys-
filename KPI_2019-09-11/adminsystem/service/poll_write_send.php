<?php
	define('G5_IS_SERVICE', true);
	include_once('../common.php');
	if(!count($quest_title))
    		alert('잘못된 접근입니다!!!.', $url);	 	

include_once('./eleMoney.php');

if (isset($udoc)) {
    $letter_attache_flag = true;
    $cntCnt = 0;
} else {
    $letter_attache_flag = false;
    $udoc = '';
    $cntCnt = 1;    
}	

$moneyCheck = new eleMoney;
if(!$moneyCheck->is_possible_use('SMS', '', 0, $cntCnt, $member['mb_no'])){
    $rtnErrTxt = $moneyCheck->Get_error_msg();
    $moneyCheck->Init();
    alert($rtnErrTxt, $url);
}

 	$eplm_qcnt = count($quest_title);
 	sql_query("insert into epoll_master SET eplm_mbid = '{$member['mb_no']}',eplm_gubn='{$polltype}', eplm_time = '".G5_TIME_YMDHIS."', eplm_title = '{$m_title}',eplm_qcnt = '{$eplm_qcnt}', eplm_type='{$as_type}' ");
 	$res = sql_fetch("select max(eplm_ukey) as eplm_ukey from epoll_master where eplm_mbid='{$member['mb_no']}' and eplm_title='{$m_title}' and eplm_qcnt   = '{$eplm_qcnt}' ");
	if (!$res)   {
	    alert('등록 하지 못 했습니다.',$url);
	}    
	$eplm_ukey = $res["eplm_ukey"];
	$category = $polltype+1;
if (!$moneyCheck->check_and_use_money(0, 1, $member['mb_no'],'','SMS', $udoc, $category, $eplm_ukey)){
    $rtnErrTxt = $moneyCheck->Get_error_msg();
    $moneyCheck->Init();
    alert_after($rtnErrTxt);
}	

for ($idx=0;$idx<$eplm_qcnt;$idx++){
	$eplh_title = $quest_title[$idx]; 		
	$answer_array_var = 'answer_title'.$idx; 
	$eplh_acnt = 0;				
	if (is_array(${$answer_array_var})) {
		$eplh_acnt = count(${$answer_array_var});	
	} 		
	$eplh_chk = $extra_txt[$idx];	
	$dup_poss = $dup_arr[$idx];	
	if ($dup_poss == 'Y'){
		$dup_cnt = $eplh_acnt;				
	} else {
		$dup_cnt = 1;
	}
	$qnum = $idx+1;
	if ($eplh_acnt == 0) {
		$eplh_chk = 'Y';	
	}
	
	sql_query("insert into epoll_question SET eplh_ukey = '{$eplm_ukey}',eplh_ilbh = '{$qnum}',eplh_title = '{$eplh_title}',eplh_acnt = '{$eplh_acnt}',eplh_chk = '{$eplh_chk}', eplh_dup = '{$dup_cnt }' ");
	for($jdx=0;$jdx<$eplh_acnt;$jdx++){
		$epla_asbh = $jdx+1;
		$epla_title = ${$answer_array_var}[$jdx];
		sql_query("insert into epoll_qahist SET epla_ukey='{$eplm_ukey}',epla_ilbh='{$qnum}',epla_asbh='{$epla_asbh}',epla_title='{$epla_title}' "); 			
	} 		
} 	

if ($polltype == 1) {
	$pudcn= '회신문서';
} else {
	$pudcn= '설문조사';
}

if ($letter_attache_flag == true) {
	$target_para = '/serv.php?m1=4&m2=1&polltype=0&udoc='.$udoc.'&udcn='.$udcn.'&stitle='.urlencode($stitle);
	sql_query("update edoc_master SET edoc_attach_poll_id = '{$eplm_ukey}' where edoc_ukey = '{$udoc}' ");
} else {
	$target_para = '/serv.php?m1=4&m2=1&polltype='.$polltype.'&udoc='.$eplm_ukey.'&udcn='.$pudcn.'&stitle='.urlencode($m_title);
}
	goto_url($target_para);
?>