<?php
$sub_menu = '200200';
include_once('./_common.php');

check_demo();

auth_check($auth[$sub_menu], 'd');

check_token();

$count = count($_POST['chk']);
if(!$count)
    alert($_POST['act_button'].' 하실 항목을 하나 이상 체크하세요.');

for ($i=0; $i<$count; $i++)
{
   
    $k = $_POST['chk'][$i];

    
    $sql = " select * from sender_phone where id = '{$_POST['ph_id'][$k]}' ";
    $row = sql_fetch($sql);

    if(!$row['id'])
        continue;

    // po_mb_point에 반영
    $sql = " update sender_phone
                set status = 1, verify_date=sysdate() 
                where user_id = '{$_POST['mb_no'][$k]}'
                  and id = '{$_POST['ph_id'][$k]}' ";
    sql_query($sql);
}

goto_url('./reply_list.php?'.$qstr);
?>