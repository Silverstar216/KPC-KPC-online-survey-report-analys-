<?php
$sub_menu = "200100";
include_once("./_common.php");
include_once(G5_LIB_PATH."/register.lib.php");
include_once('./ele_scheduler.php');

auth_check($auth[$sub_menu], 'w');

check_token();

$mb_id = trim($_POST['mb_id']);
$user_id = $_POST['user_id'];
$new_elem_money = intval($_POST['total_amount']);
$rest_elem_money = intval($_POST['elem_money_add']) + intval($_POST['elem_crnt_money']);

//$smscnt_used_prev = intval($_POST['elem_charge_first_prev']) - intval($_POST['elem_crnt_cnt_prev']);
//$sms_curcnt_new = intval($_POST['elem_crnt_cnt_prev']);
////
//if ($_POST['elem_chargetype'] == '002') {
//	// 선불정액제인 경우 갱신되는 최대건수와 이미 리용된 건수에 따라서 현재 건수를 갱신해준다.
//	if ($smscnt_used_prev >= 0) {
//		$sms_curcnt_new = intval($_POST['elem_charge_first_count']) - $smscnt_used_prev;
//		if ($sms_curcnt_new < 0)
//			$sms_curcnt_new = 0;
//	}
//}
$current_date = date('Y-m-d H:i:s');

$sql_common = "  charge_type = {$_POST['elem_chargetype']},
                 sms_g_simple = {$_POST['sms_g_simple']}, 
				 sms_g_attach = {$_POST['sms_g_attach']}, 
				 sms_sur_simple = {$_POST['sms_sur_simple']}, 
				 sms_sur_attach = {$_POST['sms_sur_attach']},
				 lms_g_simple = {$_POST['lms_g_simple']},
				 lms_g_attach = {$_POST['lms_g_attach']},
				 lms_sur_simple = {$_POST['lms_sur_simple']}, 
				 lms_sur_attach = {$_POST['lms_sur_attach']}";
//선불충전식인 경우
if($_POST['elem_chargetype'] == "0" && $_POST['add_amount'] != ''){
    $sql_common .= " , current_amount = '{$new_elem_money}',
                     total_deposit = '{$_POST['total_deposit']}', 
	    			 last_deposit = {$_POST['add_amount']},
		    		 charge_count = charge_count + 1,
		    		 last_charge_date='{$current_date}'";
//후불정산제인 경우
}else if($_POST['elem_chargetype'] == "1"){
    $sql_common .= ", month_count = '{$_POST['month_count']}',
                     current_count = '{$_POST['month_count']}'";
}

$mb = get_member($mb_id);
if (!$mb['mb_id'])
	alert('존재하지 않는 회원자료입니다.');

if ($is_admin != 'super' && $mb['mb_level'] >= $member['mb_level'])
	alert('자신보다 권한이 높거나 같은 회원은 수정할 수 없습니다.');
//입금액만큼 잔고액을 갱신
$sql = " update user_money
			set {$sql_common} 
			where user_id = '{$user_id}' ";
sql_query($sql);

//입금추가액을 이력에  추가
if($_POST['elem_chargetype'] == "0" && $_POST['add_amount'] != '') {
    sql_query("insert into user_money_history set user_id='{$user_id}', deposit='{$_POST['add_amount']}', date='{$current_date}',current_amount='{$new_elem_money}'");
}
goto_url('./money_list.php?'.$_POST['money_form_param']);
?>