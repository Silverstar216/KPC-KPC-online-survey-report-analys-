<?php
	define('G5_IS_SERVICE', true);
	include_once("../common.php");
	if ($pt){		
		// 로그 쌓기?
		exit();
	}
	if (!is_hp($tp)) {
		echo '잘못된 전화번호입니다. 입력 예) 01X-XXXX-XXXX 또는 01XXXXXXXXX';
		exit();
	}
	$rc_tp = get_hp($tp, 1);

	// 5분내 재전송 한 경우 막기...
	$wr_message = '[OO고]가정통신문입니다.' ;	
	$wr_reply = '025852359';
	$dup_qry = "select count(*) as dupcnt from sample_sms_history where ss_tele = '{$rc_tp}' and ss_time >= date_add(now(),interval -5 minute) ";
	
	$duprow = sql_fetch($dup_qry);
	if ($duprow){
		if ($duprow['dupcnt'] > 0) { echo '동일번호로는 5분 간격으로 예제전송이 가능합니다!'; exit(); }
	}
	$msg_qry = "select * from sample_sms_info where si_sygb = 'Y' ";
	$msgrow = sql_fetch($msg_qry);

	if ($msgrow){
		$wr_message = $msgrow['si_msg'];
		$wr_reply      =  $msgrow['si_reply'];
	} else {
		echo "현재는 예제 변경 중입니다.";
		exit();
	}

include_once('./eleMoney.php');

        $keySql = "INSERT INTO sms5_fetch set smsf_mbno = '1', smsf_time = '".G5_TIME_YMDHIS."' ;";
        sql_query($keySql);
        $wr_no = mysqli_insert_id($g5['connect_db']);



$moneyCheck = new eleMoney;
if(!$moneyCheck->check_and_use_money(1, 0, '1', $wr_no, 'SMS', '','0','')){
    $moneyCheck->Init();
	echo '예제문자전송을 위한 관리자금액이 부족합니다.!'; exit();
}    

$sql_txt =  "insert into {$g5['sms5_write_table']} set wr_no='$wr_no', wr_success='1',".
                    "wr_failure='0', wr_id=  '1', wr_type='-1', wr_renum=0, wr_reply='{$wr_reply}',".
                    "wr_message='{$wr_message}', wr_total='1', wr_datetime='".G5_TIME_YMDHIS."'";
sql_query($sql_txt);

sql_query("insert into {$g5['sms5_history_table']} set wr_no='$wr_no', wr_renum=0, mb_no= '1', mb_id='SMS', hs_flag='1', hs_code='IFB', hs_name='예제전송', hs_hp='{$rc_tp}', hs_memo='{$rc_tp}로 전송했습니다.', hs_datetime='".G5_TIME_YMDHIS."'");

$sql_txt = "insert into sample_sms_history set ss_tele = '{$rc_tp}', ss_msg= '{$wr_message}', ss_time = '".G5_TIME_YMDHIS."' ";
sql_query($sql_txt);

$SMS = new SMS5;
$SMS->SMS_connect($config['cf_icode_server_ip'], 
			$config['cf_icode_id'], 
			$config['cf_icode_pw'], 
			$config['cf_icode_server_port'],
			"eletter");

$list = array();
$recv = array();
$reply = str_replace('-', '', trim($wr_reply));
$wr_message = conv_unescape_nl($wr_message);
$recv['bk_hp'] = get_hp($tp, 0);
array_push($list, $recv);
$result = $SMS->AddDests($list, $reply, '', '', $wr_message, "", 1);
if ($result) {
	$result = $SMS->SendMessage('', 'SMS', true);	
}

echo $tp."로 사용 예제가 전송되었습니다.";
?>