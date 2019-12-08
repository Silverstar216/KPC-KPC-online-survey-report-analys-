<?php
include_once("../common.php");
if($is_guest)  
alert('회원이시라면 로그인 후 이용해 보십시오.', 
    G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/serv.php?m1='.$pgMNo.'&m2='.$pgMNo1));
if ($w == 'u') // 업데이트
{
    for ($i=0; $i<count($_POST['chk']); $i++)
    {
        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];
        $fg_no = $_POST['fg_no'][$k];
        $fg_name = $_POST['fg_name'][$k];
        $fg_member = $_POST['fg_member'][$k];

        if (!is_numeric($fg_no))
            alert('그룹 고유번호가 없습니다.');

        $res = sql_fetch("select * from {$g5['sms5_form_group_table']} where fg_no='$fg_no' and fg_member = '{$member['mb_no']}' ");
        if (!$res)
            alert('존재하지 않는 그룹입니다.');

        if (!strlen(trim($fg_name)))
            alert('그룹명을 입력해주세요');

        $res = sql_fetch("select fg_name from {$g5['sms5_form_group_table']} where fg_no<>'$fg_no' and fg_name='$fg_name'");
        if ($res)
            alert('같은 그룹명이 존재합니다.');
        sql_query("update {$g5['sms5_form_group_table']} set fg_name='$fg_name' where fg_no='$fg_no' and fg_member = '{$member['mb_no']}' ");        
    }
}
else if ($w == 'de') // 그룹삭제
{
    for ($i=0; $i<count($_POST['chk']); $i++)
    {
        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];
        $fg_no = $_POST['fg_no'][$k];

        if (!is_numeric($fg_no))
            alert('그룹 고유번호가 없습니다.');

        $res = sql_fetch("select * from {$g5['sms5_form_group_table']} where fg_no='$fg_no' and fg_member = '{$member['mb_no']}' ");
        if (!$res)
            alert('존재하지 않는 그룹입니다.');

        sql_query("delete from {$g5['sms5_form_group_table']} where fg_no='$fg_no' and fg_member = '{$member['mb_no']}' ");
        sql_query("update {$g5['sms5_form_table']} set fg_no = 0 where fg_no='$fg_no' and fg_member = '{$member['mb_no']}' ");
    }
}
else if ($w == 'em') 
{
    for ($i=0; $i<count($_POST['chk']); $i++)
    {
        // 실제 번호를 넘김
        $k = $_POST['chk'][$i];
        $fg_no = $_POST['fg_no'][$k];

        if ($fg_no == 'no') $fg_no = 0;

        sql_query("delete from {$g5['sms5_form_table']} where fg_no = '$fg_no' and fg_member = '{$member['mb_no']}' ");
    }
}
else if ($w == 'no') 
{
    if ($fg_no == 'no') $fg_no = 0;

    sql_query("delete from {$g5['sms5_form_table']} where fg_no = '$fg_no' and fg_member = '{$member['mb_no']}' ");
}
else // 등록
{
    if (!strlen(trim($fg_name)))
        alert('그룹명을 입력해주세요');

    $res = sql_fetch("select fg_name from {$g5['sms5_form_group_table']} where fg_name = '$fg_name' and fg_member = '{$member['mb_no']}' ");
    if ($res)
        alert('같은 그룹명이 존재합니다.');

    sql_query("insert into {$g5['sms5_form_group_table']} set fg_name = '$fg_name', fg_member = '{$member['mb_no']}' ");
}

goto_url('/serv.php?m1=4&m2=4');
?>