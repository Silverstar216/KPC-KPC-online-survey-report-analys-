<?php
	include_once('../../common.php');
	$nextUrl = G5_URL.'/upload/etc/prv.php?ep='.$ep.'&uk='.$uk.'&hk='.$hk;	
	if ($ep =='') {
		$nextUrl = G5_URL;
	} else if ($uk == '') {
		$nextUrl = G5_URL;
	} else {  
		if ($hk == $mnumber){
			set_session('whos_call',$cp);	
			$nextUrl = G5_URL.'/upload/etc/prv.php?ep='.$ep.'&uk='.$uk.'&hk='.$hk.'&vk='.$uk;
		} else {
			$nextUrl = G5_URL.'/upload/etc/prv.php?ep='.$ep.'&uk='.$uk.'&emsg='.urlencode('끝자리를 다시 확인하세요.');				
		}			
	}
	//echo $nextUrl;
	goto_url($nextUrl);
?>