<?php
	include_once('../common.php');
	$ppoa = sql_fetch(" select count(*) as cnt, max(epls_time) as ptime from epoll_answer where epls_usms = '{$eplm_sk}'  ");
	if ($ppoa) {
	    $epls_cnt = $ppoa['cnt'];
	    if ($epls_cnt > 0) {
	            $epls_ptime = $ppoa['ptime'];
	            alert(date("Y-m-d",strtotime($epls_ptime)).'에 이미 참여 하셨습니다.','/service/thank_you.php?ep=asdkfljasdfasdflkasdjflqowdij');                       
	    }
	} 		
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
<title>e-letter poll</title>
<!--[if lte IE 8]>
<script src="<?php echo G5_JS_URL ?>/html5.js"></script>
<![endif]-->
<script src="<?php echo G5_JS_URL ?>/jquery-1.8.3.min.js"></script>
<script src="<?php echo G5_JS_URL ?>/common.js"></script>
<script src="<?php echo G5_JS_URL ?>/wrest.js"></script>
</head>
<body>
<?php
if (!$eplm_ukey) die();
       
$po = sql_fetch(" select * from epoll_master where eplm_ukey = '{$eplm_ukey}' ");
if (!$po['eplm_ukey']) die();
// 기간 경과 체크 ??
$eplm_qcnt = count($eplh_rsp);
$eplm_tcnt = count($eplh_chk);

if ( ($i0) || ($i1) || ($i2)|| ($i3) ){
	$epls_info = str_replace('|', '{g}',$i0).'|'.str_replace('|', '{g}',$i1).'|'.str_replace('|', '{g}',$i2).'|'.str_replace('|', '{g}',$i3);
} else {
	$epls_info = '';
}

for ($idx = 0;$idx<$eplm_qcnt;$idx++){
	$q_ilbh = $idx+1;
           $epls_id = '';
           $quest_row = sql_fetch("SELECT eplh_dup,eplh_acnt FROM epoll_question where eplh_ukey = '{$eplm_ukey}' and eplh_ilbh = '{$q_ilbh}' ");
	if ($quest_row['eplh_dup']) {
		$dup_possible_cnt = $quest_row['eplh_dup'];
		$eplh_acnt            = $quest_row['eplh_acnt'];
	}	
	$q_txt  = $eplh_chk[$idx];	
	$extra_sql = "epls_etxt='{$q_txt}',"; 				
	if ($dup_possible_cnt == 1){

		if ($eplm_sk != '') {
			$poa = sql_fetch(" select * from epoll_answer where epls_usms = '{$eplm_sk}' and epls_ilbh ='{$q_ilbh}' ");
			if ($poa['epls_ukey']) {
			    $epls_id = $poa['epls_id'];
			} 
		}		
		$q_abh = $eplh_rsp[$idx];
	           if ($epls_id =='') {
	           	if ($eplm_sk == '') {
	           		sql_query("insert into epoll_answer SET epls_ukey='{$eplm_ukey }',epls_ilbh='{$q_ilbh}',epls_asbh='{$q_abh}',epls_etxt='{$q_txt}', epls_info= '{$epls_info}',  epls_usms = '{$eplm_sk}',epls_rmip = '{$epls_rmip}', epls_xhost = '{$epls_xhost}', epls_agent = '{$epls_agent}', epls_host = '{$epls_host}',epls_time = '".G5_TIME_YMDHIS."' "); 

		           } else {
				sql_query("insert into epoll_answer SET epls_ukey='{$eplm_ukey }',epls_ilbh='{$q_ilbh}',epls_asbh='{$q_abh}',epls_etxt='{$q_txt}', epls_info= '{$epls_info}',  epls_usms = '{$eplm_sk}',epls_rmip = '{$epls_rmip}', epls_xhost = '{$epls_xhost}', epls_agent = '{$epls_agent}', epls_host = '{$epls_host}',epls_time = '".G5_TIME_YMDHIS."' "); 	           	
	           	}
	           } else {
	           	sql_query("update epoll_answer SET epls_asbh='{$q_abh}',epls_etxt='{$q_txt}',epls_rmip = '{$epls_rmip}', epls_xhost = '{$epls_xhost}', epls_agent = '{$epls_agent}', epls_host = '{$epls_host}', epls_time = '".G5_TIME_YMDHIS."' where epls_ukey='{$eplm_ukey}' and epls_ilbh='{$q_ilbh}' and epls_id = '{$epls_id}' "); 
	           }
	   } else {
	   	// 일단 두번 응답은 못한다고 생각하자. 업데이트하려면 지워야 하니까...
	   	$lastflag = true;
		$extra_rsql = '';
	   	for($kdx=$eplh_acnt;$kdx>0;$kdx--){
	   		$select_chk = 'chkq_'.$q_ilbh.'_'.$kdx;	
	   		if ($$select_chk == 'on'){
	   			if ($lastflag == true) {// 제일 나중에만 기타 입력을 해놓는다. 
					$lastflag = false;
					$extra_rsql = $extra_sql;
	   			} else {
					$extra_rsql = '';
	   			}
	   			sql_query("insert into epoll_answer SET epls_ukey='{$eplm_ukey }',epls_ilbh='{$q_ilbh}',epls_asbh='{$kdx}', epls_info= '{$epls_info}',".$extra_rsql ."  epls_usms = '{$eplm_sk}',epls_rmip = '{$epls_rmip}', epls_xhost = '{$epls_xhost}', epls_agent = '{$epls_agent}', epls_host = '{$epls_host}',epls_time = '".G5_TIME_YMDHIS."' "); 	   		
	   		}
			
	   	}	
	   	if ($lastflag == true){// 선택된것이 없으면 0으로  기타 내용만 넣는다. 
                     // 기존것은 삭제 한다. 
			sql_query("insert into epoll_answer SET epls_ukey='{$eplm_ukey }',epls_ilbh='{$q_ilbh}',epls_asbh='0', epls_info= '{$epls_info}',".$extra_sql ."  epls_usms = '{$eplm_sk}',epls_rmip = '{$epls_rmip}', epls_xhost = '{$epls_xhost}', epls_agent = '{$epls_agent}', epls_host = '{$epls_host}',epls_time = '".G5_TIME_YMDHIS."' "); 	   			   		
	   	}   		   	   	
	   }
}
goto_url('/service/thank_you.php?ep=asdkfljasdfasdflkasdjflqowdij');
?>
</body>
</html>
<?php echo html_end(); // HTML 마지막 처리 함수 : 반드시 넣어주시기 바랍니다. ?>