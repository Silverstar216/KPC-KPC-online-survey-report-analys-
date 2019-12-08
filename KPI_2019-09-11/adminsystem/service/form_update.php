<?php
include_once("../common.php");
if($is_guest)  
alert('회원이시라면 로그인 후 이용해 보십시오.', 
    G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/serv.php?m1='.$pgMNo.'&m2='.$pgMNo1));
$g5['title'] = "내 메세지 업데이트";

if ($w == 'u') // 업데이트
{
    if (!$fg_no) $fg_no = 0;

    if (!$fo_receipt) $fo_receipt = 0; else $fo_receipt = 1;

    if (!strlen(trim($fo_name)))
        alert('이름을 입력해주세요');

    if (!strlen(trim($fo_content)))
        alert('메세지을 입력해주세요');
/*
    $res = sql_fetch("select * from {$g5['sms5_form_table']} where fo_no<>'$fo_no' and fo_content='$fo_content'");
    if ($res)
        alert('같은 메세지가 존재합니다.');
*/
    $res = sql_fetch("select * from {$g5['sms5_form_table']} where fo_no='$fo_no'");
    if (!$res)
        alert('존재하지 않는 데이터 입니다.');

    sql_query("update {$g5['sms5_form_table']} set fg_no='$fg_no', fo_name='$fo_name', fo_content='$fo_content', fo_datetime='".G5_TIME_YMDHIS."' where fo_no='$fo_no'");
}
else if ($w == 'd') // 삭제
{
    if (!is_numeric($fo_no))
        alert('고유번호가 없습니다.');

    $res = sql_fetch("select * from {$g5['sms5_form_table']} where fo_no='$fo_no'");
    if (!$res)
        alert('존재하지 않는 데이터 입니다.');

    sql_query("delete from {$g5['sms5_form_table']} where fo_no='$fo_no'");

    $get_fg_no = $fg_no;
}
else // 등록
{
    if (!$fg_no) $fg_no = 0;

    if (!strlen(trim($fo_name)))
        alert('이름을 입력해주세요');

    if (!strlen(trim($fo_content)))
        alert('메세지을 입력해주세요');

    $res = sql_fetch("select * from {$g5['sms5_form_table']} where fo_content='$fo_content' and fg_member='{$member['mb_no']}' ");
    if ($res)
        alert('같은 메세지가 존재합니다.');

    $group = sql_fetch("select * from {$g5['sms5_form_group_table']} where fg_no = '$fg_no'");

    sql_query("insert into {$g5['sms5_form_table']} set fg_no='$fg_no', fg_member='{$member['mb_no']}' , fo_name='$fo_name', fo_content='$fo_content', fo_datetime='".G5_TIME_YMDHIS."'");


    $get_fg_no = $fg_no;
}

$go_url = '/serv.php?m1=4&m2=5&page='.$page.'&amp;fg_no='.$get_fg_no;
goto_url($go_url);
?>