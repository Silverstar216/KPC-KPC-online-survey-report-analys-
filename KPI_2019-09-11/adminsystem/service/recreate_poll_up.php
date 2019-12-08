<?php
	include_once('../common.php');
 	if(!isset($uk)){
 		return;
 	}
 	if(!isset($ep)){
 		return;
 	}
 	if($member['mb_no'] == ''){
 		return;
 	}
 	if ($uk == 'd') {
		$sql_text = "delete from epoll_tmp_qahist where epla_ukey = (select eplm_ukey from epoll_tmp_master where eplm_mbid = '{$member['mb_no']}' and eplm_ukey = {$ep})";
		sql_query($sql_text);
		$sql_text = "delete from epoll_tmp_question where eplh_ukey = (select eplm_ukey from epoll_tmp_master where eplm_mbid = '{$member['mb_no']}' and eplm_ukey = {$ep})";
		sql_query($sql_text);
		$sql_text = "delete from epoll_tmp_master where eplm_mbid = '{$member['mb_no']}' and eplm_ukey = {$ep}";
		sql_query($sql_text);		
	           return;
	}  if ($uk == 'i') {
	 	$eplm_qcnt = count($quest_title);
	 	sql_query("insert into epoll_tmp_master SET eplm_mbid = '{$member['mb_no']}',eplm_gubn='{$polltype}', eplm_time = '".G5_TIME_YMDHIS."', eplm_title = '{$m_title}',eplm_qcnt = '{$eplm_qcnt}', eplm_type='{$as_type}' ");
	 	$res = sql_fetch("select max(eplm_ukey) as eplm_ukey from epoll_tmp_master where eplm_mbid='{$member['mb_no']}' and eplm_title='{$m_title}' and eplm_qcnt   = '{$eplm_qcnt}' ");
		if (!$res)   {
		    alert('저장 하지 못 했습니다.',$url);
		}    
		$eplm_ukey = $res["eplm_ukey"];
		$category = $polltype+1;

	 	for ($idx=0;$idx<$eplm_qcnt;$idx++){
	 		$eplh_title = $quest_title[$idx]; 		
	 		$answer_array_var = 'answer_title'.$idx;
	 		$eplh_acnt = count(${$answer_array_var});
			$eplh_chk = $extra_txt[$idx];	
			$dup_poss = $dup_arr[$idx];
			if ($dup_poss == 'Y'){
				$dup_cnt = $eplh_acnt;				
			} else {
				$dup_cnt = 1;
			}				
	 		$qnum = $idx+1;
	 		sql_query("insert into epoll_tmp_question SET eplh_ukey = '{$eplm_ukey}',eplh_ilbh = '{$qnum}',eplh_title = '{$eplh_title}',eplh_acnt = '{$eplh_acnt}',eplh_chk = '{$eplh_chk}', eplh_dup = '{$dup_cnt }' ");
	 		for($jdx=0;$jdx<$eplh_acnt;$jdx++){
				$epla_asbh = $jdx+1;
				$epla_title = ${$answer_array_var}[$jdx];
				sql_query("insert into epoll_tmp_qahist SET epla_ukey='{$eplm_ukey}',epla_ilbh='{$qnum}',epla_asbh='{$epla_asbh}',epla_title='{$epla_title}' "); 			
	 		} 		
	 	} 	
	 	echo $eplm_ukey;
	}  if ($uk == 'u') {
		$res = sql_fetch("select eplm_mbid from epoll_tmp_master where eplm_ukey={$ep}");
		if (!$res)   {
		    alert('저장 하지 못 했습니다.',$url);
		}    	
		$eplm_qcnt = count($quest_title);
		if ($res['eplm_mbid'] == $member['mb_no']) {
			$sql_text = "delete from epoll_tmp_qahist where epla_ukey = (select eplm_ukey from epoll_tmp_master where eplm_mbid = '{$member['mb_no']}' and eplm_ukey = {$ep})";
			sql_query($sql_text);
			$sql_text = "delete from epoll_tmp_question where eplh_ukey = (select eplm_ukey from epoll_tmp_master where eplm_mbid = '{$member['mb_no']}' and eplm_ukey = {$ep})";
			sql_query($sql_text);
		 	sql_query("update epoll_tmp_master SET eplm_gubn='{$polltype}', eplm_time = '".G5_TIME_YMDHIS."', eplm_title = '{$m_title}',eplm_qcnt = '{$eplm_qcnt}', eplm_type='{$as_type}',eplm_public = '{$save_tmp}' where eplm_mbid = '{$member['mb_no']}' and eplm_ukey = {$ep} ");
		 	$eplm_ukey = $ep;
	           } else {
		 	sql_query("insert into epoll_tmp_master SET eplm_mbid = '{$member['mb_no']}',eplm_gubn='{$polltype}', eplm_time = '".G5_TIME_YMDHIS."', eplm_title = '{$m_title}',eplm_qcnt = '{$eplm_qcnt}', eplm_type='{$as_type}', eplm_public = '{$save_tmp}' ");
		 	$res = sql_fetch("select max(eplm_ukey) as eplm_ukey from epoll_tmp_master where eplm_mbid='{$member['mb_no']}' and eplm_title='{$m_title}' and eplm_qcnt   = '{$eplm_qcnt}' ");
			if (!$res)   {
			    alert('저장 하지 못 했습니다.',$url);
			}    
			$eplm_ukey = $res["eplm_ukey"];
	           }
		$category = $polltype+1;

	 	for ($idx=0;$idx<$eplm_qcnt;$idx++){
	 		$eplh_title = $quest_title[$idx]; 		
	 		$answer_array_var = 'answer_title'.$idx;
	 		$eplh_acnt = count(${$answer_array_var});
			$eplh_chk = $extra_txt[$idx];
			$dup_poss = $dup_arr[$idx];
			if ($dup_poss == 'Y'){
				$dup_cnt = $eplh_acnt;				
			} else {
				$dup_cnt = 1;
			}					
	 		$qnum = $idx+1;
	 		sql_query("insert into epoll_tmp_question SET eplh_ukey = '{$eplm_ukey}',eplh_ilbh = '{$qnum}',eplh_title = '{$eplh_title}',eplh_acnt = '{$eplh_acnt}',eplh_chk = '{$eplh_chk}', eplh_dup = '{$dup_cnt }' ");
	 		for($jdx=0;$jdx<$eplh_acnt;$jdx++){
				$epla_asbh = $jdx+1;
				$epla_title = ${$answer_array_var}[$jdx];
				sql_query("insert into epoll_tmp_qahist SET epla_ukey='{$eplm_ukey}',epla_ilbh='{$qnum}',epla_asbh='{$epla_asbh}',epla_title='{$epla_title}' "); 			
	 		} 		
	 	} 	
	 	echo $eplm_ukey;
	}
?>