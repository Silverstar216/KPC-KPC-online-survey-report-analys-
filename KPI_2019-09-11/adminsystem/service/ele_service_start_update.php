<?php
define('G5_IS_SERVICE', true);
include_once('../common.php');

include_once('./eleMoney.php');

$pgMNo = 8;
$pgMNo1 = 1;
if($is_guest)  
alert('회원이시라면 로그인 후 이용해 보십시오.', 
    G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/serv.php?m1='.$pgMNo.'&m2='.$pgMNo1));

$g5['title'] = "입금내역 확인 요청";

if (!trim($elem_type))
    alert('요금제도를 확인하십시오!');

if (!trim($eler_name))
    alert('입금인명를 확인하십시오!');

if (!trim($eler_money))
    alert('입금금액을 확인하십시오!!!');
        $sql_txt =  "insert into ele_money_request set ";
        $sql_txt .= "eler_id =  '{$member['mb_no']}', eler_type='{$elem_type}',";
        $sql_txt .= "eler_stat ='1', eler_money='{$eler_money}', eler_request_date='".G5_TIME_YMDHIS."', ";
        $sql_txt .= "eler_money_category='1', eler_proc_time='".G5_TIME_YMDHIS."', eler_name='{$eler_name}' ";
        sql_query($sql_txt);


$wr_reply = ($member['mb_tel'] == '') ? $member['mb_hp'] : $member['mb_tel'];
if (!trim($wr_reply)) $wr_reply = '025852359';
    
$wr_message = '이스쿨레터ID['.$member['mb_id'].']입금명['.$eler_name.']['.$eler_money.']원 요청' ;

$row = sql_fetch("select max(wr_no) as wr_no from {$g5['sms5_write_table']}");
if ($row)
    $wr_no = $row['wr_no'] + 1;
else
    $wr_no = 1;

$moneyCheck = new eleMoney;
if(!$moneyCheck->check_and_use_money(1, 0, '1', $wr_no, 'SMS', '','0','')){
    $moneyCheck->Init();
}    

$sql_txt =  "insert into {$g5['sms5_write_table']} set wr_no='$wr_no', wr_success='1',".
                    "wr_failure='0', wr_id=  '1', wr_type='-1', wr_renum=0, wr_reply='{$wr_reply}',".
                    "wr_message='{$wr_message}', wr_total='1', wr_datetime='".G5_TIME_YMDHIS."'";
sql_query($sql_txt);

sql_query("insert into {$g5['sms5_history_table']} set wr_no='$wr_no', wr_renum=0, mb_no= '1',hs_flag='1', hs_name='요금담당', hs_hp='010-3723-5981', hs_datetime='".G5_TIME_YMDHIS."'");

$command = 'start /B php C:/inetpub/wwwroot/service/sms_send_serv.php '.$wr_no.'  > NUL';
pclose( popen( $command, 'r' ) );

goto_url(G5_URL);
?>