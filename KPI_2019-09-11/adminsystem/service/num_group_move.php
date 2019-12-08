<?php
// 휴대폰 그룹 이동
include_once ('../common.php');
if($is_guest)  
alert('회원이시라면 로그인 후 이용해 보십시오.', 
    G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/serv.php?m1='.$pgMNo.'&m2='.$pgMNo1));
$res = sql_fetch("select * from {$g5['sms5_book_group_table']} where bg_no='$bg_no'");
sql_query("update {$g5['sms5_book_table']} set bg_no='$move_no' where bg_no='$bg_no'");

goto_url('/serv.php?m1=4&m2=2');
?>