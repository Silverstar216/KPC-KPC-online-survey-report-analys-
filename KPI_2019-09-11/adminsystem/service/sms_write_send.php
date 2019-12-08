<?php
define('G5_IS_SERVICE', true);
include_once('../common.php');
$pgMNo = 4;
$pgMNo1 = 1;
if($is_guest)  
alert('회원이시라면 로그인 후 이용해 보십시오.', 
    G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/serv.php?m1='.$pgMNo.'&m2='.$pgMNo1));

include_once('../_head.php');

include_once('./eleMoney.php');
include_once('./ele_func.php');

$g5['title'] = "문자전송중";

if (!trim($wr_reply))
    win_close_alert('발신 번호를 입력해주세요.');

$row2 = sql_fetch("select ph_phone from sms5_phone_identity where ph_mbno = '{$member['mb_no']}' and ph_phone = '{$wr_reply}' and ph_gubn = 1 and ph_identity = 1 ");
if(!$row2) win_close_alert('인증 되지 않은 발신 번호입니다.');

if (!trim($wr_message))
    win_close_alert('메세지를 입력해주세요.');

if (!trim($send_list))
    win_close_alert('문자 메세지를 받을 휴대폰번호를 입력해주세요!!!');

$list = array();
$hps = array();

$send_list = explode('/', $send_list);
$wr_overlap = 0; // 중복번호를 체크함 2015.03.03 체크 안함.  1일때 체크 
$overlap = 0;
$duplicate_data = array();
$duplicate_data['hp'] = array();
$str_serialize = "";

while ($row = array_shift($send_list))
{
    $item = explode(',', $row);

    for ($i=1, $max = count($item); $i<$max; $i++)
    {
        if (!trim($item[$i])) continue;
        switch ($item[0])
        {
            case 'g': // 그룹전송
                $qry = sql_query("select * from {$g5['sms5_book_table']} where bg_no='$item[1]' and mb_no = '{$member['mb_no']}'  and bk_receipt=1");
                while ($row = sql_fetch_array($qry))
                {
                    $row['bk_hp'] = get_hp($row['bk_hp'], 0);
                    if ($wr_overlap && array_overlap($hps, $row['bk_hp'])) {
                        $overlap++;
                        array_push( $duplicate_data['hp'], $row['bk_hp'] );
                        continue;
                    }

                    array_push($list, $row);
                    array_push($hps, $row['bk_hp']);
                }
                break;
            case 'v': // 권한(mb_leve) 선택
                  $qry = sql_query("select * from edoc_variable where edcv_udoc='$udoc' and edcv_mbno = '{$member['mb_no']}' and edcv_grid='{$ed_mnid}' ");
                while ($row = sql_fetch_array($qry))
                {
                    $hp = get_hp($row['edcv_hp'], 0);
                    $name = $row['edcv_name'];      
                    $bk_no = $row['edcv_ukey'];                    
                    if ($wr_overlap && array_overlap($hps, $hp)) {
                        $overlap++;
                        array_push( $duplicate_data['hp'], $row['bk_hp'] );
                        continue;
                    }
                    array_push($list, array('bk_hp' => $hp, 'bk_name' => $name, 'bk_no' => $bk_no));
                    array_push($hps, $hp);
                }
                break;
            case 'h': // 권한(mb_leve) 선택

                $item[$i] = explode(':', $item[$i]);
                $hp = get_hp($item[$i][1], 0);
                $name = $item[$i][0];

                if ($wr_overlap && array_overlap($hps, $hp)) {
                    $overlap++;
                    array_push( $duplicate_data['hp'], $row['bk_hp'] );
                    continue;
                }

                array_push($list, array('bk_hp' => $hp, 'bk_name' => $name));
                array_push($hps, $hp);
                break;                
            case 'p': // 개인 선택

                $row = sql_fetch("select * from {$g5['sms5_book_table']} where bk_no='$item[$i]' and mb_no = '{$member['mb_no']}' ");
                $row['bk_hp'] = get_hp($row['bk_hp'], 0);

                if ($wr_overlap && array_overlap($hps, $row['bk_hp'])) {
                    $overlap++;
                    array_push( $duplicate_data['hp'], $row['bk_hp'] );
                    continue;
                }
                array_push($list, $row);
                array_push($hps, $row['bk_hp']);
                break;
        }
    }
}

if( count($duplicate_data['hp']) ){ //중복된 번호가 있다면
    $duplicate_data['total'] = $overlap;
    $str_serialize = serialize($duplicate_data);
}

// SMS / LMS 검사
$wr_message = conv_unescape_nl($wr_message);
$wr_total = count($list);
$msg_length = strlen(utf2euc($wr_message));
if (($polltype == 0)||($polltype == 1)||($polltype == 2)) {// 첨부 문서 
	$limit_len = 69;
} else {
	$limit_len = 90;
}
if ($msg_length > $limit_len) {
  $lmsvalue = 'LMS';
} else {
  $lmsvalue = 'SMS';
}

$udockey = '';
$pollkey = '';
if ($polltype == 0) {
	$udockey = $udoc;
} else if ($polltype == 1) {
	$pollkey = $udoc;
} else if ($polltype == 2) {
	$pollkey = $udoc;
}			

// 예약전송
$msgcnt = $wr_total;
if (($polltype > 2)||($polltype < 0)) {
    $cvcnt = 0;
    $eleh_category = '1';
} else {
    $cvcnt = $wr_total;    
    $eleh_category = $polltype+1;
}

$moneyCheck = new eleMoney;
if(!$moneyCheck->is_possible_use($lmsvalue, $udoc, $msgcnt, $cvcnt, $member['mb_no'])){
    $rtnErrTxt = $moneyCheck->Get_error_msg();
    $moneyCheck->Init();
    win_close_alert($rtnErrTxt);
}   

if ($wr_by && $wr_bm && $wr_bd && $wr_bh && $wr_bi) {
    $wr_booking = "$wr_by-$wr_bm-$wr_bd $wr_bh:$wr_bi:00";
} else {
    $wr_booking = '';
}
?>

<?php
$SMS = new SMS5;
$SMS->SMS_connect($config['cf_icode_server_ip'], 
			$config['cf_icode_id'], 
			$config['cf_icode_pw'], 
			$config['cf_icode_server_port'],
			"eletter");
			
$reply = str_replace('-', '', trim($wr_reply));
$result = $SMS->AddDests($list, $reply, '', '', $wr_message, $wr_booking, $wr_total);
if ($result)
{    
    if ($SMS->Result) 
    {
        $keySql = "INSERT INTO sms5_fetch set smsf_mbno = '{$member['mb_no']}', smsf_time = '".G5_TIME_YMDHIS."' ;";
        sql_query($keySql);
        $wr_no = mysqli_insert_id($g5['connect_db']);        
		
		// 자금검사
        if(!$moneyCheck->check_and_use_money($msgcnt, $cvcnt, $member['mb_no'], $wr_no, $lmsvalue, $udoc, $eleh_category, $pollkey)){
            $rtnErrTxt = $moneyCheck->Get_error_msg();
            $moneyCheck->Init();
            win_close_alert($rtnErrTxt);
        }    
        
		// 전송요청추가
        if ($polltype == 0) {
            $sql_txt =  "insert into {$g5['sms5_write_table']} set wr_no='$wr_no', wr_id=  '{$member['mb_no']}', wr_type='{$polltype}',  wr_udoc ='{$udoc}', wr_renum=0, wr_reply='{$wr_reply}', wr_message='$wr_message', wr_booking='$wr_booking', wr_total='$wr_total', wr_datetime='".G5_TIME_YMDHIS."'";
        } else if ($polltype == 1) {
            $sql_txt =  "insert into {$g5['sms5_write_table']} set wr_no='$wr_no', wr_id=  '{$member['mb_no']}', wr_type='{$polltype}',  wr_poll ='{$udoc}', wr_renum=0, wr_reply='{$wr_reply}', wr_message='$wr_message', wr_booking='$wr_booking', wr_total='$wr_total', wr_datetime='".G5_TIME_YMDHIS."'" ;           
        } else if ($polltype == 2) {
            $sql_txt =  "insert into {$g5['sms5_write_table']} set wr_no='$wr_no', wr_id=  '{$member['mb_no']}', wr_type='{$polltype}',  wr_poll ='{$udoc}', wr_renum=0, wr_reply='{$wr_reply}', wr_message='$wr_message', wr_booking='$wr_booking', wr_total='$wr_total', wr_datetime='".G5_TIME_YMDHIS."'" ;                       
        } else {
            $sql_txt =  "insert into {$g5['sms5_write_table']} set wr_no='$wr_no', wr_id=  '{$member['mb_no']}', wr_type='-1', wr_renum=0, wr_reply='{$wr_reply}', wr_message='{$wr_message}', wr_booking='$wr_booking', wr_total='$wr_total', wr_datetime='".G5_TIME_YMDHIS."'";			
        }
        sql_query($sql_txt);		
				
		// 전송리력추가
        $wr_success = 0;
        $wr_failure = 0;
        $count      = 0; 			
        foreach ($SMS->Result as $smsresult)
        {							
            list($callback, $phone, $code) = explode(":", $smsresult);						
			$hs_memo = get_hp($phone, 1)."로 전송요청을 했습니다.";
			$hs_flag = 2;	// 전송요청

            $row = array_shift($list);
            $hs_hp = get_hp($row['bk_hp'], 0);
            $hs_code = 'IFB';			
            $log = array_shift($SMS->Log);
			$insertsql = "insert into {$g5['sms5_history_table']} set wr_no='$wr_no', wr_renum=0, bg_no='{$row['bg_no']}', ".
					"mb_no= '{$member['mb_no']}', mb_id = '{$lmsvalue}', bk_no='{$row['bk_no']}', ".
					"hs_name='".addslashes($row['bk_name'])."', hs_hp='{$hs_hp}', hs_datetime='".G5_TIME_YMDHIS.
					"', hs_flag='$hs_flag', hs_code='$hs_code', hs_memo='".addslashes($hs_memo)."', hs_log='".addslashes($log)."'";
            sql_query($insertsql);			
        }
		$updatesql = "update {$g5['sms5_write_table']} set wr_memo='$str_serialize' where wr_no='$wr_no' and wr_renum=0";
		sql_query($updatesql);		

		// 실지 SMS전송
		$sql_squery = "select w.*,(select mb_nick from g5_member where mb_no = wr_id) as wr_nick,(select mb_7 from g5_member where mb_no = wr_id) as lmsflag from sms5_write as w where wr_no = '{$wr_no}' ";
		$row = sql_fetch($sql_squery);
		if ($row) {
			$wr_type  = $row['wr_type'];
			$wr_udoc  = $row['wr_udoc'];                 
			$wr_poll   = $row['wr_poll'];
			$wr_datetime = $row['wr_datetime'];	// 등록시간...
			$wr_booking = $row['wr_booking'];	// 예약시간 
			$wr_nick       = $row['wr_nick'];
			
			if ($wr_booking == '0000-00-00 00:00:00') {
				$date_client_req = $wr_datetime;		
			} else {
				$date_client_req = $wr_booking;		
			}  
			
			$wr_reply = $row['wr_reply'];// 회신번호 
			$callback = str_replace('-', '', trim($wr_reply));
			$callback = str_replace(' ', '', trim($callback));                        
			$callback = str_replace('.', '', trim($callback));        
			$callback = str_replace('(', '', trim($callback));
			$callback = str_replace(')', '', trim($callback));
			$callback = str_replace('[', '', trim($callback));
			$callback = str_replace(']', '', trim($callback));    
		}
		
		// $lms_flag = $row['lmsflag'];        
		// $Mb_7 = json_decode($lms_flag);
		// if ($Mb_7->{'LMS'} == '1'){
			$msg_length = strlen(utf2euc($wr_message));
			if (($wr_type == 0)||($wr_type == 1)||($wr_type == 2)) {// 첨부 문서 
				$limit_len = 69;
			} else {
				$limit_len = 90;
			}
			if ($msg_length > $limit_len) {
			  $lmsvalue = 'LMS';
			  $s_subject = '['.$row['wr_nick'].']';			  
			} else {
			  $lmsvalue = 'SMS';
			  $s_subject = '';
			}
		// }  
	
		$mlong_url = '';
		$edoc_surl  = '';
		if (($wr_type == 0)||($wr_type == 1)||($wr_type == 2)) {// 첨부 문서 			
			if ($wr_type == 0) {
				$qrydoc = sql_fetch("select edoc_surl,edoc_wdoc from edoc_master where edoc_ukey = '{$wr_udoc}' ");
				$edoc_surl = $qrydoc['edoc_surl'];              
				$edoc_file_name = $qrydoc['edoc_wdoc'];              
				$pos = strrpos($edoc_file_name, '.');                         
				$titlename = substr($edoc_file_name, 0, $pos);          
				$mlong_url = G5_URL.'upload/etc/eletter.php?ep='.$wr_udoc;
			} else {                           
				$mlong_url = G5_URL.'felv.php?ep='.$wr_poll;
				$edoc_surl = make_4el_surl($mlong_url,$titlename);
			}
		} else { 	// 그냥 SMS
		// 링크 따지마..
		} 
			
		$result = $SMS->SendSMS($wr_type, $titlename, $s_subject, $lmsvalue, $wr_no, $mlong_url, $callback, $date_client_req, $wr_message);				
        $SMS->Init(); // 보관하고 있던 결과값을 지웁니다.					     
    }
    else win_close_alert("에러: SMS 서버와 통신이 불안정합니다.");
}
else win_close_alert("에러: SMS 데이터 입력도중 에러가 발생하였습니다.");
?>
<div id="sub_content">
</div>
<?php
include_once('../_tail.php');

function win_close_alert($msg) {
    $html = "<script>
    act = window.open('/service/sms_ing.php', 'act', 'width=300, height=200');
    act.close();
    alert('$msg');
    history.back();</script>";
    echo $html;
    die();
    exit;
}
?>

<script type="text/javascript">
act = window.open('/service/sms_ing.php', 'act', 'width=300, height=200');
act.close();
</script>

<?php
goto_url(G5_URL.'/service/history_view.php?wr_no='.$wr_no);
?>

