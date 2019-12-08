<?php 

	if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
           $epls_rmip = $_SERVER['REMOTE_ADDR'];
           $epls_xhost = $_SERVER['HTTP_X_FORWARDED_FOR'];
           $epls_agent = $_SERVER['HTTP_USER_AGENT'];
           $epls_host = $_SERVER['HTTP_HOST'];
		   $actual_link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		   
	if (!isset($ep)) {
		alert('존재하지 않는 문서입니다.', G5_URL);
	}  else {
		if ($ep == '')	{
			alert('존재하지 않는 문서입니다.', G5_URL);
		}
	}
	if (isset($sk)) { 
	
		$keyword = hash('ripemd160', $actual_link);
		$sql = "select clicks from forel_url where keyword='{$keyword}'";
		$result = sql_fetch($sql); 
		if (!$result) {
			$sql = "insert into forel_url (keyword, url, ip, clicks) values ('{$keyword}', '{$actual_link}', '{$epls_rmip}', 1)";
			sql_query($sql); 
		}
		else {
			$sql = "update forel_url set url='{$actual_link}', ip='{$epls_rmip}' where keyword='{$keyword}'";
			sql_query($sql); 
		}
	
		// 건별 로그 인써트 
		if ($sk == "")	{
			$sk = '';
		} else {
			if ($ed_type == 'P') { 			
				$rchkSendSql = "SELECT sms5_write.wr_no, wr_udoc,wr_poll, ".
				"(case when (wr_poll = '{$ep}') then 'OK' else (select (case when (count(edoc_ukey) > 0) then 'OK' else 'NK' end) from edoc_master where edoc_ukey = wr_udoc and edoc_attach_poll_id = '{$ep}') end ) ok_field ".
                                           "FROM sms5_write,sms5_history where sms5_write.wr_no = sms5_history.wr_no and hs_no = '{$sk}'";
          				$rchkSendRow = sql_fetch($rchkSendSql);                                 
          				if(!$rchkSendRow['ok_field']){
          					alert('존재하지 않는 문서입니다.', G5_URL);			
          				}
          				if($rchkSendRow['ok_field']<>'OK'){
          					alert('존재하지 않는 문서이거나 전송되지 않는 내역입니다.', G5_URL);		
          				}
			} else if ($ed_type == 'D') {
				$rchkSendSql = "SELECT sms5_write.wr_no, wr_udoc, ".
				"(case when (wr_udoc = '{$ep}') then 'OK' else 'NK' end) ok_field ".
                                           "FROM sms5_write,sms5_history where sms5_write.wr_no = sms5_history.wr_no and hs_no = '{$sk}'";
          				$rchkSendRow = sql_fetch($rchkSendSql);                                 
          				if(!$rchkSendRow['ok_field']){
          					alert('존재하지 않는 문서입니다.', G5_URL);			
          				}
          				if($rchkSendRow['ok_field']<>'OK'){
          					alert('존재하지 않는 문서이거나 전송되지 않는 내역입니다.2', G5_URL);		
          				}
			}

		}
	} else {
			$sk = '';		
	}
	
	if ($ed_type == 'P') { 
		
	} else {
		$po = sql_fetch(" select edoc_master.*,(SELECT mb_level FROM g5_member where mb_id = edoc_mbid) as mb_level  from edoc_master where edoc_ukey = '{$ep}' ");
		if (!$po['edoc_ukey']) {
			alert('존재하지 않는 문서입니다.', G5_URL);
		}		
		$var_count = $po['edoc_var'];
				
		if ($var_count > 0) {// 개별 고지 문서입니다. 						
			// 전화번호 ? 비밀번호 인증 받기 
			$vv = sql_fetch(" select *  from edoc_variable,sms5_history where hs_no = '{$sk}' and edcv_ukey = bk_no ");	
			if ($vv['edcv_var']) {
				if ($vv['edcv_check'] == 'Y') {
					$compchk_val = '';
					$compchk_val = $ep.$var_count;
					if ($sk == '')	{
						$compchk_val .= '0000';			
					} else {
						$compchk_val .= $sk;
					}		
					$comkey = explode(':', get_session('whos_call'));
					if ($compchk_val == $comkey[0]){
						$vk = $comkey[1];
					} else {
						include_once('./ele_doc_check.php');
						exit;
					}//세션이 존재하면... 통과                     			
				}
			}
			
			$eleVar = array($var_count);
			if ($vv['edcv_var']) {
				$eleName = $vv['edcv_name'];
				$elePhone = $vv['edcv_hp'];
				
				$varList = explode('|', $vv['edcv_var']);
				for ($idx=1;$idx<=$var_count;$idx++)
				{
					$v="";
					if ($idx < 10){	
						$v.='0'.$idx;
					} else {
						$v.= $idx;
					}
					$eleText[$v]=$varList[$idx-1];
				}

				//exit;

               /*
				for ($idx=1;$idx<=$var_count;$idx++)
				{
					if ($idx < 10){
						$eleVar[$idx-1] = 'eleText0'.$idx;	
					} else {
						$eleVar[$idx-1] = 'eleText'.$idx;	
					}				
					$$eleVar[$idx-1] = $varList[$idx-1];
				}
				*/
			}
		} else if ($var_count == -1){
			// edufine 문서다.. 표를 바꿔치기한다. 
			// 전화번호 ? 비밀번호 인증 받기 
			$vv = sql_fetch(" select edcv_name,edcv_hp,edcv_var,hs_message,edcv_check  from edoc_variable,sms5_history where hs_no = '{$sk}' and edcv_ukey = bk_no ");	
			if ($vv['edcv_var']) {		
				if ($vv['edcv_check'] == 'Y') {
					$compchk_val = '';
					$compchk_val = $ep.$var_count;
					if ($sk == '')	{
						$compchk_val .= '0000';			
					} else {
						$compchk_val .= $sk;
					}		
					$comkey = explode(':', get_session('whos_call'));
					if ($compchk_val == $comkey[0]){
						$vk = $comkey[1];
					} else {
						include_once('./ele_doc_check.php');
						exit;
					}//세션이 존재하면... 통과		
					//$eleVar = array($var_count);
				}
			}
			
			if ($vv['edcv_var']) {
				$eleName   = $vv['edcv_name'];
				$elePhone  = $vv['edcv_hp'];
				$edu_row   = $vv['edcv_var'];
				$tmp_msg   = explode('http://mms.ac', $vv['hs_message']);
				$edu_msg   = $tmp_msg[0];
				$edufineBill = 'make_edufine01_html.php';
				   } else {
				$eleName   = '이름';
				$elePhone  = '전화번호';
				$edu_row = '{"g":"1","c":"1","i":"14","n":"%EA%B9%80%ED%95%99%EC%83%9D","l":[{"t":"%EC%88%98%EC%97%85%EB%A3%8C","s":"3%2F4%EB%B6%84%EA%B8%B0","m":"342,900"},{"t":"%EC%88%98%EC%97%85%EB%A3%8C","s":"4%2F4%EB%B6%84%EA%B8%B0","m":"342,900"},{"t":"%ED%95%99%EA%B5%90%EC%9A%B4%EC%98%81%EC%A7%80%EC%9B%90%EB%B9%84","s":"3%2F4%EB%B6%84%EA%B8%B0","m":"73,860"},{"t":"%ED%95%99%EA%B5%90%EC%9A%B4%EC%98%81%EC%A7%80%EC%9B%90%EB%B9%84","s":"4%2F4%EB%B6%84%EA%B8%B0","m":"73,860"},{"t":"%EC%A4%91%EC%8B%9D%EB%B9%84","s":"9%EC%9B%94","m":"68,400"},{"t":"%EC%A4%91%EC%8B%9D%EB%B9%84","s":"11%EC%9B%94","m":"64,800"},{"t":"%EC%A4%91%EC%8B%9D%EB%B9%84","s":"12%EC%9B%94","m":"79,200"},{"t":"%EC%84%9D%EC%8B%9D%EB%B9%84","s":"5%EC%9B%94","m":"58,500"},{"t":"%EC%84%9D%EC%8B%9D%EB%B9%84","s":"6%EC%9B%94","m":"70,200"},{"t":"%EB%B0%A9%EA%B3%BC%ED%9B%84%ED%95%99%EA%B5%90","s":"5%EC%9B%94","m":"47,500"},{"t":"%EA%B5%90%EA%B3%BC%EC%84%9C%EB%8C%80%EA%B8%88","s":"2%EC%9B%94","m":"91,880"},{"t":"s","s":"","m":"1,314,000"}]}';
				$edu_msg   = '샘플 양식입니다.(메세지 내용)';
				$edufineBill = 'make_edufine01_html.php';	           	
				   }		
		}
		//$compchk_val set_session('whos_call', 'call_number12345');
		//unset($_SESSION['whos_call']);
		//echo 'whos_call : '.$_SESSION['whos_call'].'<br>';
		//echo 'whos_call2 : '..'<br>';      
		//$Text01 = '테스트양';	
		$sms_txt = "insert into edoc_answer (ed_type,ed_udoc,ed_usms,addr,hostip,forwdip,browser,crtime) values ";
		$sms_txt = $sms_txt."('{$ed_type}','{$ep}','{$sk}','{$epls_rmip}','{$epls_host}','{$epls_xhost}','{$epls_agent}','".G5_TIME_YMDHIS."') ";
		sql_query($sms_txt);

		$filename = $po['edoc_wurl'];
		$edoc_attach_poll_id = $po['edoc_attach_poll_id'];
		$poll_type = '';
		if ($edoc_attach_poll_id) {
			if ($edoc_attach_poll_id > 0) {
				// 설문문서인지 아닌지 체크한다. 
				$ppo = sql_fetch("select eplm_gubn from epoll_master where eplm_ukey = '{$edoc_attach_poll_id}' ");
				if ($ppo) {
					$poll_type = $ppo['eplm_gubn'];	
				} else {
					$poll_type = '';
				}
			}
		}
				
		$pr_attach_flag = false;	
		if ($po['mb_level']==4){// 유료고객 덕현 고 제외 이레터 신문 기사 링크 삽입 

		} else if ($poll_type == ''){
			$ele_today = date("Y-m-d");
			if ($po['mb_level']==2) {
				$prsql = "select * from ele_pr_master 
									  where elpr_ukey = 19 and '{$ele_today}' between elpr_stdt and elpr_eddt
									  order by elpr_eddt desc,elpr_stdt desc, elpr_ukey desc";									
			} else {
				$prsql = "select * from ele_pr_master 
									  where elpr_mbid 
									  in (select elgm_mbid from g5_member,ele_pr_group_member 
										where mb_id = '{$po['edoc_mbid']}' and mb_no = elgm_sbid and elgm_sygb = 'Y') 
									  and '{$ele_today}' between elpr_stdt and elpr_eddt
									  order by elpr_eddt desc,elpr_stdt desc, elpr_ukey desc";							                  
			}
			$prresult = sql_query($prsql);	
			$pr_list = array();
			$idx = 0;
			while ($prrow = sql_fetch_array($prresult)) {
				$pr_list[$idx]['elpr_ukey'] = $prrow['elpr_ukey'];
				$pr_list[$idx]['elpr_mbid'] = $prrow['elpr_mbid'];
				$pr_list[$idx]['elpr_title'] = $prrow['elpr_title'];
				$pr_list[$idx]['elpr_wurl'] = $prrow['elpr_wurl'];
				$pr_list[$idx][''] = $prrow[''];
				$idx++;
			}	
			$proc_count = $idx; 	
			if ($proc_count > 0)  {
				if ($po['mb_level']==2) {
					$pr_up_txt = "update ele_pr_master set elpr_read =  elpr_read+1	
							where elpr_ukey = 19 and '{$ele_today}' between elpr_stdt and elpr_eddt";                                                                           				
				} else {
					$pr_up_txt = "update ele_pr_master set elpr_read =  elpr_read+1	
							where elpr_mbid 
											in (select elgm_mbid from g5_member,ele_pr_group_member 
												where mb_id = '{$po['edoc_mbid']}' and mb_no = elgm_sbid and elgm_sygb = 'Y') 
																				   and '{$ele_today}' between elpr_stdt and elpr_eddt";				
				}
				sql_query($pr_up_txt);
				$pr_attach_flag = true;			
			}
		}	
	}
?>