<?php
define('G5_IS_SERVICE', true);
include_once('../common.php');

$pgMNo = 8;
$pgMNo1 = 1;
if($is_guest)  
alert('회원이시라면 로그인 후 이용해 보십시오.', 
    G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/serv.php?m1='.$pgMNo.'&m2='.$pgMNo1));

if (!is_numeric($wr_no))
    alert('전송 고유 번호가 없습니다.');

$query = "select * from {$g5['sms5_write_table']} where wr_no='$wr_no'";
$res = sql_fetch($query);
$replynumber = $res['wr_reply'];
$udoc = $res['wr_udoc'];
$booking = $res['wr_booking'];
$message = $res['wr_message'];

$query = "delete from {$g5['sms5_write_table']} where wr_no='$wr_no'";
sql_query($query);

$query = "delete from {$g5['sms5_history_table']} where wr_no='$wr_no'";
sql_query($query);

$replynumber = str_replace('-', '', $replynumber);
if ($udoc) {	
	$query = "delete from em_mmt_tran where date_client_req='{$booking}' and content='{$message}' and callback='$replynumber'";	
	sql_query($query);
}
else {
	$query = "delete from em_smt_tran where date_client_req='{$booking}' and content='{$message}' and callback='$replynumber'";	
	sql_query($query);
}

win_close_alert('예약취소 완료');
?>
<div id="sub_content">
</div>

<?php
function win_close_alert($msg) {
    $html = "<script>
    act = window.open('/service/sms_ing.php', 'act', 'width=300, height=200');
    act.close();
    alert('$msg');
    history.back();</script>";
    echo $html;
    die();
    exit;
}

goto_url(G5_URL.'/serv.php?m1='.$pgMNo.'&m2='.$pgMNo1);
?>
