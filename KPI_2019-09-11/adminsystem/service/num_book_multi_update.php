<?php
include_once("../common.php");
if($is_guest)  
alert('회원이시라면 로그인 후 이용해 보십시오.', 
    G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/serv.php?m1='.$pgMNo.'&m2='.$pgMNo1));
$g5['title'] = "전화번호부";

for ($i=0; $i<count($_POST['bk_no']); $i++) 
{
    $bk_no = $_POST['bk_no'][$i];
    if (!trim($bk_no)) continue;

    $res = sql_fetch("select * from {$g5['sms5_book_table']} where bk_no='$bk_no'");
    if (!$res) continue;

    if ($atype == 'del') // 삭제
    {
        sql_query("delete from {$g5['sms5_book_table']} where bk_no='$bk_no'");
    }
}
if( $str_query ){
    $str_query = '?'.$str_query;
}
goto_url('/serv.php?m1=4&m2=3'.$str_query);
?>