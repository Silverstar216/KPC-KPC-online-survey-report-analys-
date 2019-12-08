<?php
$sub_menu = '300800';
include_once('./_common.php');

if ($w == "u" || $w == "d")
    check_demo();

if ($W == 'd')
    auth_check($auth[$sub_menu], "d");
else
    auth_check($auth[$sub_menu], "w");


$sql_common = " set si_msg = '$si_msg',
                    si_reply = '$si_reply',
                    si_time = now(),
                    si_sygb = 'Y' ";

if ($w == "")
{
    $sql = " update sample_sms_info set si_sygb = '' where si_ukey is not null";
    sql_query($sql);    
    $sql = " insert sample_sms_info $sql_common ";
    sql_query($sql);
    $fm_id = mysqli_insert_id($g5['connect_db']);
}
else if ($w == "u")
{
    $sql = " update sample_sms_info set si_sygb = '' where si_ukey !=  '$fm_id' ";
    sql_query($sql);        
    $sql = " update sample_sms_info $sql_common where si_ukey = '$fm_id' ";
    sql_query($sql);
}
else if ($w == "d")
{
    // FAQ상세삭제
	$sql = " delete from sample_sms_info where si_ukey = '$fm_id' and si_sygb ='' ";
    sql_query($sql);
}

if ($w == "" || $w == "u")
{
    goto_url("./sms_sample_form.php?w=u&amp;fm_id=$fm_id");
}
else
    goto_url("./sms_sample_list.php");
?>
