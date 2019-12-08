<?php
if (!defined('_GNUBOARD_')) exit;

// 외부로그인
function outlogin($skin_dir='basic')
{
    global $config, $member, $g5, $urlencode, $is_admin, $is_member;

    if (array_key_exists('mb_nick', $member)) {		
        $nick  = cut_str($member['mb_nick'], $config['cf_cut_name']);		
    }
    if (array_key_exists('mb_point', $member)) {
        $point = number_format($member['mb_point']);
    }

    if (G5_IS_MOBILE) {
        $outlogin_skin_path = G5_MOBILE_PATH.'/'.G5_SKIN_DIR.'/outlogin/'.$skin_dir;
        $outlogin_skin_url = G5_MOBILE_URL.'/'.G5_SKIN_DIR.'/outlogin/'.$skin_dir;
    } else {
        $outlogin_skin_path = G5_SKIN_PATH.'/outlogin/'.$skin_dir;
        $outlogin_skin_url = G5_SKIN_URL.'/outlogin/'.$skin_dir;
    }

    // 읽지 않은 쪽지가 있다면
    if ($is_member) {
        $sql = " select count(*) as cnt from {$g5['memo_table']} where me_recv_mb_id = '{$member['mb_id']}' and me_read_datetime = '0000-00-00 00:00:00' ";
        $row = sql_fetch($sql);
        $memo_not_read = $row['cnt'];

        $is_auth = false;
        $sql = " select count(*) as cnt from {$g5['auth_table']} where mb_id = '{$member['mb_id']}' ";
        $row = sql_fetch($sql);
        if ($row['cnt'])
            $is_auth = true;

        $Crnt_ele_service_type =  '기본무료서비스';
        $sql = " select elpu_type_name as tname from ele_money_mst,ele_price_user where elem_id = '{$member['mb_no']}' and elem_stat = 'Y' and elem_type = elpu_type and elpu_stat = 'm' ";
        $row = sql_fetch($sql);
        if ($row['tname']) {
            $Crnt_ele_service_type = $row['tname'];        
        }
    }
    
    $outlogin_url        = login_url($urlencode);
    $outlogin_action_url = G5_HTTPS_BBS_URL.'/login_check.php';

    ob_start();		
    if ($is_member)
        include_once ($outlogin_skin_path.'/outlogin.skin.2.php');
    else // 로그인 전이라면
        include_once ($outlogin_skin_path.'/outlogin.skin.1.php');
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}

function account_info() 
{
	$accountinfo = "스쿨뉴스는 학교와 학부모들사이의 원활한 소통을 도와주며 교원업무를 대폭 경감하는 친환경 서비스입니다.";
	global $config, $member, $g5, $urlencode, $is_admin, $is_member;
	
	if ($is_member) 
	{
		$sql = "select * 
				from 
					{$g5['member_table']}, ele_money_mst, ele_price_user 
				where 
					mb_id = '{$member['mb_id']}' and mb_no = elem_id and elem_type = elpu_type";
        $row = sql_fetch($sql);
		$accountinfo = '요금제: '.$row['elpu_type_name'];
		
		switch ($row['elem_type']) {
			case '001':
			{
				$rest_money = $row['elem_money'] - $row['elem_crnt_money'];
				$accountinfo .= ',&#9;충전금액 '.$row['elem_money'].'원 중 '.$rest_money.'원을 이용하셨습니다.';
			}
			break;
			case '002':
			{				
				$accountinfo .= ',&#9;당월 이용건수 '.$row['elem_charge_first_count'].'건중 '.$row['elem_crnt_cnt'].'건이 남았습니다.';
			}
			break;
		}        
		$accountinfo .= '&nbsp;&nbsp;&nbsp;<a href="'.$G5_URL.'/serv.php?m1=8&m2=6" style="color: white">확인하기</a>';
	}  	
	
	return $accountinfo;
}

?>