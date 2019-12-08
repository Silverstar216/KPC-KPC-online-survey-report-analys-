<?php	
	include_once('../../common.php');
	$ed_type = 'D';

	$po = sql_fetch(" select edoc_master.*,(SELECT mb_level FROM g5_member where mb_id = edoc_mbid) as mb_level  from edoc_master where edoc_ukey = '{$ep}' ");
	if (!$po['edoc_ukey']) {
		alert('존재하지 않는 문서입니다.', G5_URL);
	}		
	
	$var_count = intval($po['edoc_var']);//chkd	//1
	if ($var_count > 0) {// 개별 고지 문서입니다. 
		// 전화번호 ? 비밀번호 인증 받기 
		$vv = sql_fetch("select *  from edoc_variable where edcv_ukey = '{$uk}' ");		
		if ($vv['edcv_var']) //수학
		{					
			if ($vv['edcv_check'] == 'Y') 
			{		
				$compchk_val = '';
				$compchk_val = $ep.$var_count;
				if ($uk == '')	
				{
					$compchk_val .= '0000';			
				} else {
					$compchk_val .= $uk;
				}
				if ($compchk_val == get_session('whos_call')){

				} else {
					include_once('./prv_doc_chk.php');
					exit;
				}//세션이 존재하면... 통과
			}
		}
		
		//$eleVar = array($var_count);// 이전부분
		$eleText = array($var_count);
		
		if ($vv['edcv_var']) {
			$eleName   = $vv['edcv_name'];
			$elePhone  = $vv['edcv_hp'];			
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
/*
 이전부분
			for ($idx=1;$idx<=$var_count;$idx++)
			{
				if ($idx < 10){				
					$eleVar[$idx-1] = 'eleText0'.$idx;
			    } else {
					$eleVar[$idx-1] = 'eleText'.$idx;
			    }
				$eleVar[$idx-1] = $varList[$idx-1];
			}
*/

	      }
	} else if ($var_count == -1){
		$vv = sql_fetch("select *  from edoc_variable where edcv_ukey = '{$uk}' ");		
		if ($vv['edcv_var']) {		
			if ($vv['edcv_check'] == 'Y') {		
				$compchk_val = '';
				$compchk_val = $ep.$var_count;
				if ($uk == '')	{
					$compchk_val .= '0000';			
				} else {
					$compchk_val .= $uk;
				}
				if ($compchk_val == get_session('whos_call')){

				} else {
					include_once('./prv_doc_chk.php');
					exit;
				}//세션이 존재하면... 통과
			}
		}		
		if ($vv['edcv_var']) {
			$eleName   = $vv['edcv_name'];
			$elePhone  = $vv['edcv_hp'];
			$edu_row   = $vv['edcv_var'];
			if ($nn == ''){
				$edu_msg   = '문자메세지 내용이 나타납니다!';
			} else {
				$edu_msg   = $nn;
			}
			$edufineBill = 'make_edufine01_html.php';
	           }				
	}
	//$compchk_val set_session('whos_call', 'call_number12345');
	//unset($_SESSION['whos_call']);
	//echo 'whos_call : '.$_SESSION['whos_call'].'<br>';
	//echo 'whos_call2 : '..'<br>';      
	//$Text01 = '테스트양';	
	$filename = $po['edoc_wurl'];
	include_once($filename);	
?>