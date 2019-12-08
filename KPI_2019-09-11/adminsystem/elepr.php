<?php 
           include_once('./_common.php');
	if (!isset($n)) {
		alert('존재하지 않는 문서입니다.', G5_URL);
	}
	if($n == ''){
		alert('존재하지 않는 문서입니다.', G5_URL);
	}
	if (!isset($s)) {
		$s = '';
	}
           $epls_rmip = $_SERVER['REMOTE_ADDR'];
           $epls_xhost = $_SERVER['HTTP_X_FORWARDED_FOR'];
           $epls_agent = $_SERVER['HTTP_USER_AGENT'];
           $epls_host = $_SERVER['HTTP_HOST'];
	$pr_hist_txt = "insert into ele_pr_history (elph_ukey,elph_usms,addr,hostip,forwdip,browser,crtime) values ";
	$pr_hist_txt = $pr_hist_txt."('{$n}','{$s}','{$epls_rmip}','{$epls_host}','{$epls_xhost}','{$epls_agent}','".G5_TIME_YMDHIS."') ";
	sql_query($pr_hist_txt);
	$pr = sql_fetch("select * from ele_pr_master where elpr_ukey = '{$n}' ");
	if (!$pr['elpr_ukey']) {
		alert('존재하지 않는 문서입니다.', G5_URL);
	}
	$Linkname = $pr['elpr_wurl'];
	header('Location:'.$Linkname); 
?>