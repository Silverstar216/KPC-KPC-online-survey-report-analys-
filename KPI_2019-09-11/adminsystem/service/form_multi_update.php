<?php
include_once("../common.php");

check_token();
if($is_guest)  
alert('회원이시라면 로그인 후 이용해 보십시오.', 
    G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/serv.php?m1='.$pgMNo.'&m2='.$pgMNo1));
if($atype == "del"){
    $count = count($_POST['fo_no']);
    if(!$count)
        alert('선택삭제 하실 항목을 하나이상 선택해 주세요.');

    for ($i=0; $i<$count; $i++)
    {
        // 실제 번호를 넘김
        $fo_no = $_POST['fo_no'][$i];
        if (!trim($fo_no)) continue;

        $res = sql_fetch("select * from {$g5['sms5_form_table']} where fo_no='$fo_no'");
        if (!$res) continue;

        sql_query("delete from {$g5['sms5_form_table']} where fo_no='$fo_no'");
    }
}    
goto_url('/serv.php?m1=4&m2=5');