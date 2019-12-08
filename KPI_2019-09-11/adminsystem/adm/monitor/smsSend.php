<?php
	$sub_menu = "700900";
	define('G5_IS_ADMIN', true);
	include_once ('../../common.php');
	include_once(G5_ADMIN_PATH.'/admin.lib.php');
	auth_check($auth[$sub_menu], 'r');
    $qryTime  = date("H:i:s",time());
?>
<section>
    <span class="m_title">금일 문자 전송  목록 : 조회시간(<?=$qryTime?>)</span>
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
    $today            = date("Y-m-d");
    $diffTime   = strtotime('-10 minutes');
    //================sql=================
    $qryDate_notice = "DATE_FORMAT(start_time,'%Y-%m-%d') =  '{$today }'";
    $qryDate_msg_result = "DATE_FORMAT(request_time,'%Y-%m-%d') =  '{$today }'";
       //금일메세지전송한 회원목록
    $sql = "select *,max(start_time) as last_time from notices";
    $sql .= " inner join g5_member on(notices.user_id = g5_member.mb_no)";
    $sql .=" where ".$qryDate_notice;
    $sql .= " group by user_id";
    $todayMemberList = sql_query($sql);
      //회원별로 전송상황얻기
    $total_smscnt = 0;
    $total_lmscnt = 0;
    $total_success = 0;
    $total_failcnt =  0;
    $total_gita =  0;

    for($i = 0; $row=sql_fetch_array($todayMemberList); $i ++){
      //회원이 금일 전송한 총 sms/lms전송갯수얻기
        $sql = "";
        $sql .= " select ";
        $sql .= "(select count(dstaddr) from msg_result where msg_type = 1 and " . $qryDate_msg_result . " and user_id = ".$row['user_id'].") as s_totalCount, ";
        $sql .= "(select count(dstaddr) from msg_result where msg_type = 3 and " . $qryDate_msg_result . " and user_id = ".$row['user_id'].") as l_totalCount, ";
        $sql .= "(select count(dstaddr) from msg_result where msg_type = 1 and stat=3 and result='100' and " . $qryDate_msg_result . " and user_id = ".$row['user_id'].") as s_successCount, ";
        $sql .= "(select count(dstaddr) from msg_result where msg_type = 3 and stat=3 and result='100' and " . $qryDate_msg_result . " and user_id = ".$row['user_id'].") as l_successCount, ";
        $sql .= "(select count(dstaddr) from msg_result where msg_type = 1 and stat=3 and result<>'100' and " . $qryDate_msg_result . " and user_id = ".$row['user_id'].") as s_failCount, ";
        $sql .= "(select count(dstaddr) from msg_result where msg_type = 3 and stat=3 and result<>'100' and " . $qryDate_msg_result . " and user_id = ".$row['user_id'].") as l_failCount ";

       $sendCountList = sql_query($sql);
        $row2=sql_fetch_array($sendCountList);
        if(count($row2) > 0){
            $bg = 'bg'.($i%2);
            $mb_nick   = $row['mb_nick'];
            $smscnt     = $row2['s_totalCount'];
            $lmscnt      = $row2['l_totalCount'];
            $s_success   = $row2['s_successCount'];
            $s_failcnt       =  $row2['s_failCount'];
            $l_success   = $row2['l_successCount'];
            $l_failcnt       =  $row2['l_failCount'];

            $s_gita =  $smscnt  - $s_success  -$s_failcnt ;
            $l_gita =  $lmscnt  - $l_success  -$l_failcnt ;
            $procTime = strtotime( $row['last_time'] );
            if ($procTime > $diffTime) { $bg = 'bgR'; }
            $ctime  = date("H:i:s",$procTime);
            //총계값을 가산하기
            $total_smscnt += floatval($smscnt);
            $total_lmscnt += floatval($lmscnt);
            $total_s_success += floatval($s_success);
            $total_s_failcnt += floatval($s_failcnt);
            $total_l_success += floatval($l_success);
            $total_l_failcnt += floatval($l_failcnt);
            $total_s_gita += floatval($s_gita);
            $total_l_gita += floatval($l_gita);

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
    ?>

    <?php
    if ($i > 0) {
        $total_gita = $total_smscnt + $total_lmscnt - $total_success - $total_failcnt;
    ?>
        <tr>
            <td class="td_id"><?php echo  '합('.$today.')' ?></td>
            <td class="td_right"><?php echo number_format($total_smscnt, 0); ?></td>
            <td class="td_right"><?php echo number_format($total_lmscnt, 0); ?></td>
            <td class="td_right"><?php echo number_format($total_s_success, 0); ?></td>
            <td class="td_right"><?php echo number_format($total_l_success, 0); ?></td>
            <td class="td_right"><?php echo number_format($total_s_failcnt, 0); ?></td>
            <td class="td_right"><?php echo number_format($total_l_failcnt, 0); ?></td>
            <td class="td_right"><?php echo number_format($total_s_gita, 0); ?></td>
            <td class="td_right"><?php echo number_format($total_l_gita, 0); ?></td>

        </tr>
    <?php
    }
    ?>

    <?php
    if ($i == 0) {
        echo '<tr><td colspan="7" class="empty_table">자료가 없습니다.</td></tr>';
    }
    ?>
    </tbody>
    </table>
</div>
</section>