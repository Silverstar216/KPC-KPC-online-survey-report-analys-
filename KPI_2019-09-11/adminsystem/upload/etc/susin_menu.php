<?php 
	include_once('../../common.php');
	if (!isset($sk)) { alert('잘못된 접근입니다!!!.', $url);	 }	
	if (!isset($ep)) { $ep = 1; }	
	// 전화번호 물어보고..맞으면 진행하자.. 		
	$compchk_val = 'elist'.$sk;
	$comkey = explode(':', get_session('whos_call'));
	if ($compchk_val == $comkey[0]){
		$vk = $comkey[1];
	} else {
		include_once('./ele_list_check.php');
		exit;
	}//세션이 존재하면... 통과                     					

	$Sql= "select a.* from sms5_history a, (SELECT hs_hp as hshp FROM sms5_history where hs_no = '{$sk}') b where a.hs_hp = hshp and hs_mt_pr is not null order by hs_datetime desc";
    	$ssntResult = sql_query($Sql);
    	while($res = sql_fetch_array($ssntResult)) {
    		echo $res['hs_datetime'].' '.$res['hs_message'].' '.$res['hs_surl'].'<br>';
    	}	
?>

