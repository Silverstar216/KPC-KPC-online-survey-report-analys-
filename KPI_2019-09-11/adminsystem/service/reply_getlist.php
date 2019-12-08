<?php
	include_once("./_common.php");
	$data_array = array();
	
	if ($is_guest) {
		$data_array['rtn'] = 'G';
	}  else {
		$data_array['rtn'] = 'S';

		$sql = "select ph_phone phone,ph_bigo bigo,ph_identity uses,".
				"(case when ph_identity = 0 then '인증 전' when ph_identity = 1 then '인증완료' else '' end) proccess ".
  				"from sms5_phone_identity where ph_mbno = '{$member['mb_no']}' and ph_identity !=5 and ph_gubn = 1 ".
  				"order by ph_identity,1";

		$result = sql_query($sql);
		for ($i=0; $row=sql_fetch_array($result); $i++) {
			$listarr[$i] = $row;
		}
		$data_array['cnt'] = $i;
		$data_array['listarry'] = $listarr;
	}
	echo  json_encode($data_array);
?>