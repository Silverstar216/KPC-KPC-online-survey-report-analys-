<?php
include_once('./_common.php');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

check_token();

$sql = " update ele_price
            set elpe_sms_money = '{$_POST['elpe_sms_money']}',
                elpe_lms_money = '{$_POST['elpe_lms_money']}',
                elpe_mms_money = '{$_POST['elpe_mms_money']}',
                elpe_cv_money = '{$_POST['elpe_cv_money']}'";
sql_query($sql);
goto_url('./money_list.php', false);
?>