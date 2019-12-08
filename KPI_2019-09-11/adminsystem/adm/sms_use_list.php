<?php
$sub_menu = "700500";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$token = get_token();

if (!$end_date) {
   $gijun_date = date("Y-m-d");
   $end_date = $gijun_date;
   $start_date = date("Y-m-01");
} else {
    $gijun_date = $end_date;
}

$before_start_day = date("Y-m-d",strtotime($gijun_date.' -1 month'));
$before_month= substr($before_start_day,0,7);
$curr_month= substr($gijun_date,0,7);

if ($mb_nickname) {
    $sql_search .= " and ( ";
    $sql_search .= " (g5_member.mb_nick like '%{$mb_nickname}%') ";
    $sql_search .= " ) ";
}
//전체 문자전송현황얻기

if($page > 1) {
    $page = ($page-1)*$per_page;
} else {
    $page = 0;
}
$total_result_lst = [];
//==============이번달 대한 전송통계===============
//message_kind  0 : sms , 1 : lms
//msg_type  1 : sms , 3 : lms
$total_between ="DATE_FORMAT(start_time,'%Y-%m') = '".$curr_month."'";
$msg_result_between ="DATE_FORMAT(request_time,'%Y-%m') = '".$curr_month."'";
$sql="";
$sql.=" select ";
$sql.="(select count(dstaddr) from msg_result where msg_type = 1 and ".$msg_result_between.") as s_totalCount, ";
$sql.="(select count(dstaddr) from msg_result where msg_type = 3 and ".$msg_result_between.") as l_totalCount, ";
$sql.="(select count(dstaddr) from msg_result where msg_type = 1 and stat=3 and result='100' and ".$msg_result_between.") as s_successCount, ";
$sql.="(select count(dstaddr) from msg_result where msg_type = 3 and stat=3 and result='100' and ".$msg_result_between.") as l_successCount, ";
$sql.="(select count(dstaddr) from msg_result where msg_type = 1 and stat=3 and result<>'100' and ".$msg_result_between.") as s_failCount, ";
$sql.="(select count(dstaddr) from msg_result where msg_type = 3 and stat=3 and result<>'100' and ".$msg_result_between.") as l_failCount ";

$curr_month_result = sql_query($sql);

//==============전달 전송통계===============
$total_between ="DATE_FORMAT(start_time,'%Y-%m') = '".$before_month."'";
$msg_result_between ="DATE_FORMAT(request_time,'%Y-%m') = '".$before_month."'";
$sql="";
$sql.=" select ";
$sql.="(select count(dstaddr) from msg_result where msg_type = 1 and ".$msg_result_between.") as s_totalCount, ";
$sql.="(select count(dstaddr) from msg_result where msg_type = 3 and ".$msg_result_between.") as l_totalCount, ";
$sql.="(select count(dstaddr) from msg_result where msg_type = 1 and stat=3 and result='100' and ".$msg_result_between.") as s_successCount, ";
$sql.="(select count(dstaddr) from msg_result where msg_type = 3 and stat=3 and result='100' and ".$msg_result_between.") as l_successCount, ";
$sql.="(select count(dstaddr) from msg_result where msg_type = 1 and stat=3 and result<>'100' and ".$msg_result_between.") as s_failCount, ";
$sql.="(select count(dstaddr) from msg_result where msg_type = 3 and stat=3 and result<>'100' and ".$msg_result_between.") as l_failCount ";

$prev_month_result = sql_query($sql);

$g5['title'] = '문자 전송 현황';
include_once ('./admin.head.php');

?>
<div class="tbl_head01 tbl_wrap">
    <table>
    <caption>전월,현월 현황</caption>
    <thead>
    <tr>
        <th rowspan="2" scope="col">구분</th>
        <th colspan="2" scope="col">총건</th>
        <th colspan="2" scope="col">성공</th>
        <th colspan="2" scope="col">실패</th>
        <th colspan="2" scope="col">대기</th>
    </tr>
    <tr>
        <th scope="col">SMS</th>
        <th scope="col">LMS</th>
        <th scope="col">SMS</th>
        <th scope="col">LMS</th>
        <th scope="col">SMS</th>
        <th scope="col">LMS</th>
        <th scope="col">SMS</th>
        <th scope="col">LMS</th>
    </tr>    
    </thead>
    <tbody>
<!--전월전송통계-->
    <?php
        $row=sql_fetch_array($prev_month_result);
        if(count($row) > 0){
            $smscnt     = $row['s_totalCount'];
            $s_success   = $row['s_successCount'];
            $s_failcnt       =  $row['s_failCount'];
            if ($smscnt >= $s_success + $s_failcnt) {
                $s_gita =  $smscnt  - $s_success - $s_failcnt;   // 대기: 일반문자건수
            }
            else {
                $s_gita = 0;
                if ($smscnt < $s_success) {
                    $s_success = $smscnt;
                    $s_failcnt = 0;
                }
                else {
                    $s_failcnt = $smscnt - $s_success;
                }
            }
            $lmscnt      = $row['l_totalCount'];
            $l_success   = $row['l_successCount'];
            $l_failcnt       =  $row['l_failCount'];
            if ($lmscnt >= $l_success + $l_failcnt) {
                $l_gita =  $lmscnt  - $l_success - $l_failcnt;   // 대기: 일반문자건수
            }
            else {
			$l_gita = 0;
			if ($lmscnt < $l_success) {
				$l_success = $lmscnt;
				$l_failcnt = 0;
			}
			else {
				$l_failcnt = $lmscnt - $l_success;
			}
		}					    	
    ?>
    <tr>
        <td class="td_id"><?= $before_month ?></td>
        <td class="td_right"><?php echo number_format($smscnt,0); ?></td>
        <td class="td_right"><?php echo number_format($lmscnt,0); ?></td>
        <td class="td_right"><?php echo number_format($s_success,0); ?></td>
        <td class="td_right"><?php echo number_format($l_success,0); ?></td>
        <td class="td_right"><?php echo number_format($s_failcnt,0); ?></td>
        <td class="td_right"><?php echo number_format($l_failcnt,0); ?></td>
        <td class="td_right"><?php echo number_format($s_gita,0); ?></td>
        <td class="td_right"><?php echo number_format($l_gita,0); ?></td>
    </tr>

    <?php
        }else
            echo '<tr><td colspan="6" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
<!--현월전송통계-->
    <?php
    $row=sql_fetch_array($curr_month_result);
    if(count($row) > 0){
        $smscnt     = $row['s_totalCount'];
        $s_success   = $row['s_successCount'];
        $s_failcnt       =  $row['s_failCount'];
        if ($smscnt >= $s_success + $s_failcnt) {
            $s_gita =  $smscnt  - $s_success - $s_failcnt;   // 대기: 일반문자건수
        }
        else {
            $s_gita = 0;
            if ($smscnt < $s_success) {
                $s_success = $smscnt;
                $s_failcnt = 0;
            }
            else {
                $s_failcnt = $smscnt - $s_success;
            }
        }
        $lmscnt      = $row['l_totalCount'];
        $l_success   = $row['l_successCount'];
        $l_failcnt       =  $row['l_failCount'];
        if ($lmscnt >= $l_success + $l_failcnt) {
            $l_gita =  $lmscnt  - $l_success - $l_failcnt;   // 대기: 일반문자건수
        }
        else {
            $l_gita = 0;
            if ($lmscnt < $l_success) {
                $l_success = $lmscnt;
                $l_failcnt = 0;
            }
            else {
                $l_failcnt = $lmscnt - $l_success;
            }
        }
        ?>
        <tr>
            <td class="td_id"><?= $curr_month ?></td>
            <td class="td_right"><?php echo number_format($smscnt,0); ?></td>
            <td class="td_right"><?php echo number_format($lmscnt,0); ?></td>
            <td class="td_right"><?php echo number_format($s_success,0); ?></td>
            <td class="td_right"><?php echo number_format($l_success,0); ?></td>
            <td class="td_right"><?php echo number_format($s_failcnt,0); ?></td>
            <td class="td_right"><?php echo number_format($l_failcnt,0); ?></td>
            <td class="td_right"><?php echo number_format($s_gita,0); ?></td>
            <td class="td_right"><?php echo number_format($l_gita,0); ?></td>
        </tr>

        <?php
    }else
        echo '<tr><td colspan="6" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
<label for="stx" >검색기간<strong class="sound_only"> 필수</strong></label>
<input type="text" name="start_date" value="<?php echo $start_date ?>" id="start_date" required class="required frm_input"> ~
<input type="text" name="end_date" value="<?php echo $end_date ?>" id="end_date" required class="required frm_input"> 
<label for="stx" >기관명</label>
<input type="text" name="mb_nickname" value="<?php echo $mb_nickname ?>" id="mb_nickname" class="frm_input">
<input type="submit" class="btn_submit" value="검색">
</form>
<br>
<h1>
<!--기관별(회원별) 문자전송현황얻기-->
<?php echo '대상기간 : '.$start_date.' ~ '.$end_date;?>
</h1>
<div class="tbl_head01 tbl_wrap">
    <table>
    <caption>문자 전송 목록</caption>
    <thead>
    <tr>
        <th rowspan="2" scope="col">기관명</th>
        <th colspan="2" scope="col">총건</th>
        <th colspan="2" scope="col">성공</th>
        <th colspan="2" scope="col">실패</th>
        <th colspan="2" scope="col">대기</th>        
    </tr>
    <tr>
        <th scope="col">SMS</th>
        <th scope="col">LMS</th>
        <th scope="col">SMS</th>
        <th scope="col">LMS</th>
        <th scope="col">SMS</th>
        <th scope="col">LMS</th>
        <th scope="col">SMS</th>
        <th scope="col">LMS</th>
    </tr>      
    </thead>
    <tbody>
    <?php
    //기관별(회원별)정보 불러오기
    $sql = "select * from g5_member where (mb_leave_date IS NULL or mb_leave_date = '') and  (mb_intercept_date IS NULL or mb_intercept_date = '')";
    $sql .= $sql_search; //기관이름검색
    $member_result = sql_query($sql);
    $total_between = "DATE_FORMAT(start_time,'%Y-%m-%d') between '" .$start_date. "' and '".$end_date."'";
    $msg_result_between = "DATE_FORMAT(request_time,'%Y-%m-%d') between '" .$start_date. "' and '".$end_date."'";
    for ($j=0; $member=sql_fetch_array($member_result); $j++) {
        $sql = "";
        $sql .= " select ";
        $sql .= "(select count(dstaddr) from msg_result where msg_type = 1 and " . $msg_result_between . " and user_id = ".$member['mb_no'].") as s_totalCount, ";
        $sql .= "(select count(dstaddr) from msg_result where msg_type = 3 and " . $msg_result_between . " and user_id = ".$member['mb_no'].") as l_totalCount, ";
        $sql .= "(select count(dstaddr) from msg_result where msg_type = 1 and stat=3 and result='100' and " . $msg_result_between . " and user_id = ".$member['mb_no'].") as s_successCount, ";
        $sql .= "(select count(dstaddr) from msg_result where msg_type = 3 and stat=3 and result='100' and " . $msg_result_between . " and user_id = ".$member['mb_no'].") as l_successCount, ";
        $sql .= "(select count(dstaddr) from msg_result where msg_type = 1 and stat=3 and result<>'100' and " . $msg_result_between . " and user_id = ".$member['mb_no'].") as s_failCount, ";
        $sql .= "(select count(dstaddr) from msg_result where msg_type = 3 and stat=3 and result<>'100' and " . $msg_result_between . " and user_id = ".$member['mb_no'].") as l_failCount ";

        $d_result = sql_query($sql);

        for ($i = 0; $row2 = sql_fetch_array($d_result); $i++) {

            $bg = 'bg' . ($i % 2);
            $mb_nick = $member['mb_nick'];
            $smscnt      = $row2['s_totalCount'];
            $s_success   = $row2['s_successCount'];
            $s_failcnt   =  $row2['s_failCount'];
            if ($smscnt >= $s_success + $s_failcnt) {
                $s_gita = $smscnt - $s_success - $s_failcnt;   // 대기: 일반문자건수
            }
            $lmscnt      = $row2['l_totalCount'];
            $l_success   = $row2['l_successCount'];
            $l_failcnt       =  $row2['l_failCount'];
            if ($lmscnt >= $l_success + $l_failcnt) {
                $l_gita = $lmscnt - $l_success - $l_failcnt;   // 대기: 일반문자건수
            }

            ?>
            <tr class="<?php echo $bg; ?>">
                <td class="td_id"><?php echo $mb_nick; ?></td>
                <td class="td_right"><?php echo number_format($smscnt, 0); ?></td>
                <td class="td_right"><?php echo number_format($lmscnt, 0); ?></td>
                <td class="td_right"><?php echo number_format($s_success, 0); ?></td>
                <td class="td_right"><?php echo number_format($l_success, 0); ?></td>
                <td class="td_right"><?php echo number_format($s_failcnt, 0); ?></td>
                <td class="td_right"><?php echo number_format($l_failcnt, 0); ?></td>
                <td class="td_right"><?php echo number_format($s_gita, 0); ?></td>
                <td class="td_right"><?php echo number_format($l_gita, 0); ?></td>
            </tr>
            <?php
        }
    }
    if ($j == 0) {
        echo '<tr><td colspan="6" class="empty_table">자료가 없습니다.</td></tr>';
    }
    ?>

    </tbody>
    </table>
</div>
<?php
include_once ('./admin.tail.php');
?>