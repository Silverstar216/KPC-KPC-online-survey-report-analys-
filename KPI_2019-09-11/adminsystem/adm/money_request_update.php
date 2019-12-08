<?php
$sub_menu = "700100";
include_once('./_common.php');
auth_check($auth[$sub_menu], 'w');
check_token();
$mb_id = $_POST['eler_id'];
$mb = get_member($mb_id);
if (!$mb['mb_id'])
    alert('존재하는 회원아이디가 아닙니다.', $url);

// 상태 확인
$Sql_text = "select * from ele_money_request   where eler_ukey = $eler_ukey and eler_stat = '1' ";
$srow = sql_fetch($Sql_text);
if ($srow) {
    // 요청 테이블 업데이트 
    $Sql_text = "update ele_money_request set eler_stat = '2' where eler_ukey = $eler_ukey ";
    sql_query($Sql_text);   
    $eleh_inout = 1;
} else {
    $eleh_inout = 5;
}
// 요금 히스토리 생성
/*
  `eleh_ukey` int(11) NOT NULL AUTO_INCREMENT,
  `eleh_id` int(11) NOT NULL,
  `eleh_date` datetime DEFAULT NULL COMMENT '처리일자',
  `eleh_inout` char(1) DEFAULT NULL COMMENT '1계약입금, 2사용, 3정리, 4 환불 5: 추가입금',
  `eleh_category` char(1) DEFAULT NULL COMMENT '0 : SMS, 1: 가정통신문, 2: 회신문서, 3: 설문조사, 7 : 서비스',
  `eleh_real_money` decimal(11,0) DEFAULT NULL COMMENT '실입금액',
  `eleh_curr_money` decimal(11,1) DEFAULT NULL COMMENT '사용액',
  `eleh_sms_cnt` int(11) DEFAULT NULL COMMENT 'SMS건수',
  `eleh_cv_cnt` int(11) DEFAULT NULL COMMENT '변환건수',
  `eleh_cv_user_price` decimal(11,1) DEFAULT NULL COMMENT '적용 사용자 변환 단가',
  `eleh_sms_user_price` decimal(11,1) DEFAULT NULL COMMENT '적용 사용자 SMS 단가',
  `eleh_cv_price` decimal(11,1) DEFAULT NULL COMMENT '적용 변환 단가',
  `eleh_sms_price` decimal(11,1) DEFAULT NULL COMMENT '적용 SMS 단가',
  `eleh_sms_wr_no` int(11) DEFAULT NULL COMMENT '사용SMS key',
  `eleh_edoc_ukey` int(11) DEFAULT NULL COMMENT '사용 변환 key',
  `eleh_eplm_ukey` int(11) DEFAULT NULL COMMENT '사용 설문 key',
  `eleh_bigo` varchar(255) DEFAULT NULL COMMENT '비고',
*/
$Sql_text = "insert into ele_money_hst (eleh_id,eleh_date,eleh_category,eleh_real_money,";
$Sql_text = $Sql_text."eleh_sms_cnt,eleh_cv_cnt,";
$Sql_text = $Sql_text."eleh_inout) VALUES (";
$Sql_text = $Sql_text."'{$mb['mb_no']}',sysdate(),'7','{$elem_real_money}',";
$Sql_text = $Sql_text."'{$elem_crnt_cnt}','{$elem_crnt_bonus}',";
$Sql_text = $Sql_text." '1')";
sql_query($Sql_text);

$Sql_text = "update ele_money_mst  set ";
$Sql_text = $Sql_text."elem_stat = 'Y', ";
$Sql_text = $Sql_text."elem_money = '{$eler_money}',";
//$Sql_text = $Sql_text."elem_type = '{$eler_type}',"; 
$Sql_text = $Sql_text."elem_crnt_cnt = '{$elem_crnt_cnt}',";
$Sql_text = $Sql_text."elem_crnt_cv_bonus_cnt = '{$elem_crnt_cnt}', ";
if (($money_or_cnt  == 2) || ($money_or_cnt  == 1))     { 
    $start_date1  = date("Y-m-d",strtotime($wr_by.'-'.$wr_bm.'-'.$wr_bd));
    $end_date1   = date("Y-m-d",strtotime($wr_ey.'-'.$wr_em.'-'.$wr_ed));
    $Sql_text = $Sql_text."elem_start_date = '{$start_date1}',";
    $Sql_text = $Sql_text."elem_expire_date = '{$end_date1}',";
}
$Sql_text = $Sql_text."elem_proc_time = sysdate() ";
$Sql_text = $Sql_text." where elem_id = '{$mb['mb_no']}' ";
sql_query($Sql_text);
    goto_url('./money_list.php');
?>