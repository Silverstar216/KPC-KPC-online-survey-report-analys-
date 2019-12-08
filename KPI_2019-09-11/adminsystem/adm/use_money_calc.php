<?php
$sub_menu = "700902";
include_once('./_common.php');
auth_check($auth[$sub_menu], 'w');
$g5['title'] = '요금정산';
$token = get_token();
if ((!$start_date)||(!$end_date)) {
   $gijun_date = date("Y-m-d");
   $gijun_start= substr($gijun_date,0,7).'-01';//이번달 1일    
   $start_date = $gijun_start;     
   $end_date = $gijun_date;
}

if ($stx) {
    $sql_search .= " and  ";
    switch ($sfl) {
        case 'mb_id' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
}

$g5['title'] = '요금청구 | 충전정산 현황';
include_once ('./admin.head.php');
?>

<div id = 'findgk' class="tbl_frm01 tbl_wrap">
<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
<br>    
<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
    <option value="mb_nick"<?php echo get_selected($_GET['sfl'], "mb_nick"); ?>>기관명</option>        
</select>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
<br><br>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>    
<label for="start_date" >검색기간<strong class="sound_only"> 필수</strong></label>
<input type="text" name="start_date" value="<?php echo $start_date ?>" id="start_date" required class="required frm_input">~
<input type="text" name="end_date" value="<?php echo $end_date ?>" id="end_date" required class="required frm_input">
<input type="submit" class="btn_submit" value="검 색" style="width:150px;font-size:13px;font-weight: bold;margin-left:10px">
<a href="<?= Main_DOMAIN ?>uselog/download_excel?stx=<?php $stx ?>&start_date=<?php echo $start_date ?>&end_date=<?php echo $end_date ?>" class = "download_button">Excel로 받기</a>
</form>
</div>
<br>
<h1>
<?php echo '대상기간 : '.$start_date.' ~ '.$end_date.' (건당 가격은 요금관리에서 설정한 유저별 가격을 이용합니다!!!)'; ?>
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspVAT별도입니다
</h1>
<div class="tbl_head01 tbl_wrap">
    <table>
    <caption>문자 전송 목록</caption>
    <thead>
    <tr>
        <th rowspan="3" scope="col">기관명</th>
        <th colspan="8" scope="col">S M S</th>
        <th colspan="8" scope="col">L M S</th>
        <th rowspan="3" scope="col" style = "width:180px">합계금액</th>
    </tr>
    <tr>
        <th colspan="2" scope="col">일반문자</th>
        <th colspan="2" scope="col">문서포함문자</th>
        <th colspan="2" scope="col">단순설문</th>
        <th colspan="2" scope="col">문서포함설문</th>
        <th colspan="2" scope="col">일반문자</th>
        <th colspan="2" scope="col">문서포함문자</th>
        <th colspan="2" scope="col">단순설문</th>
        <th colspan="2" scope="col">문서포함설문</th>
    </tr>
    <tr>
        <?php
            for($i = 0; $i < 8; $i ++){
                echo '<th scope="col" style = "width:80px;">단가</th>';
                echo '<th scope="col" style = "width:80px;">전송성공</th>';
            }
        ?>
    </tr>
    </thead>
    <tbody>
    <?php
//------------주어진 기간내의 요금상황얻기---------------
    //기관별(회원별)정보 불러오기
    $sql = "select * from g5_member where (mb_leave_date IS NULL or mb_leave_date = '') and  (mb_intercept_date IS NULL or mb_intercept_date = '') ";
    $sql .= $sql_search; //기관이름검색
    $member_result = sql_query($sql);
    $total_between = "DATE_FORMAT(start_time,'%Y-%m-%d') between '" .$start_date. "' and '".$end_date."'";
    $msg_result_between = "DATE_FORMAT(request_time,'%Y-%m-%d') between '" .$start_date. "' and '".$end_date."'";
    $total_sms_g_simple   = 0;
    $total_sms_g_attach   = 0;
    $total_sms_sur_simple = 0;
    $total_sms_sur_attach = 0;
    $total_lms_g_simple   = 0;
    $total_lms_g_attach   = 0;
    $total_lms_sur_simple = 0;
    $total_lms_sur_attach = 0;
    $total_price = 0;
    for ($j=0; $member=sql_fetch_array($member_result); $j++) {
        $sql = "";
        $sql .= " select ";
        $sql .= "(select count(dstaddr) from msg_result where user_msg_type = 1 and stat=3 and result='100' and " . $msg_result_between . " and user_id = ".$member['mb_no'].") as sms_g_simple, ";
        $sql .= "(select count(dstaddr) from msg_result where user_msg_type = 2 and stat=3 and result='100' and " . $msg_result_between . " and user_id = ".$member['mb_no'].") as sms_g_attach, ";
        $sql .= "(select count(dstaddr) from msg_result where user_msg_type = 3 and stat=3 and result='100' and " . $msg_result_between . " and user_id = ".$member['mb_no'].") as sms_sur_simple, ";
        $sql .= "(select count(dstaddr) from msg_result where user_msg_type = 4 and stat=3 and result='100' and " . $msg_result_between . " and user_id = ".$member['mb_no'].") as sms_sur_attach, ";
        $sql .= "(select count(dstaddr) from msg_result where user_msg_type = 5 and stat=3 and result='100' and " . $msg_result_between . " and user_id = ".$member['mb_no'].") as lms_g_simple, ";
        $sql .= "(select count(dstaddr) from msg_result where user_msg_type = 6 and stat=3 and result='100' and " . $msg_result_between . " and user_id = ".$member['mb_no'].") as lms_g_attach, ";
        $sql .= "(select count(dstaddr) from msg_result where user_msg_type = 7 and stat=3 and result='100' and " . $msg_result_between . " and user_id = ".$member['mb_no'].") as lms_sur_simple, ";
        $sql .= "(select count(dstaddr) from msg_result where user_msg_type = 8 and stat=3 and result='100' and " . $msg_result_between . " and user_id = ".$member['mb_no'].") as lms_sur_attach ";

        $d_result = sql_query($sql);
        //회원의 메세지종류별가격얻기
        $sql = "select * from user_money where user_id = ".$member['mb_no'];
        $member_price_sqlResult = sql_query($sql);
        $member_price = sql_fetch_array($member_price_sqlResult);
        $memberTotalPrice = 0;
        //회원의 요금상황을 테블에 반영
        for ($i = 0; $row2 = sql_fetch_array($d_result); $i++) {
            $mb_nick = $member['mb_nick'];
            $sms_g_simple   = $member_price['sms_g_simple'];
            $sms_g_attach   = $member_price['sms_g_attach'] ;
            $sms_sur_simple = $member_price['sms_sur_simple'];
            $sms_sur_attach = $member_price['sms_sur_attach'];
            $lms_g_simple   = $member_price['lms_g_simple'] ;
            $lms_g_attach   = $member_price['lms_g_attach'];
            $lms_sur_simple = $member_price['lms_sur_simple'];
            $lms_sur_attach = $member_price['lms_sur_attach'];

            ?>
            <tr>
                <td class="td_id"><?php echo $mb_nick; ?></td>

                <td class="td_right"><?php echo $sms_g_simple ?></td>
                <td class="td_right"><?php echo $row2['sms_g_simple'] ?></td>
                <?php
                $price1= $row2['sms_g_simple'] * $sms_g_simple;
                $memberTotalPrice += $price1;
                ?>

                <td class="td_right"><?php echo $sms_g_attach ?></td>
                <td class="td_right"><?php echo $row2['sms_g_attach'] ?></td>
                <?php
                $price2= $row2['sms_g_attach'] * $sms_g_attach;
                $memberTotalPrice += $price2;
                ?>

                <td class="td_right"><?php echo $sms_sur_simple ?></td>
                <td class="td_right"><?php echo $row2['sms_sur_simple'] ?></td>
                <?php
                $price3= $row2['sms_sur_simple'] * $sms_sur_simple;
                $memberTotalPrice += $price3;
                ?>

                <td class="td_right"><?php echo $sms_sur_attach ?></td>
                <td class="td_right"><?php echo $row2['sms_sur_attach'] ?></td>
                <?php
                $price4= $row2['sms_sur_attach'] * $sms_sur_attach;
                $memberTotalPrice += $price4;
                ?>

                <td class="td_right"><?php echo $lms_g_simple ?></td>
                <td class="td_right"><?php echo $row2['lms_g_simple'] ?></td>
                <?php
                $price5= $row2['lms_g_simple'] * $lms_g_simple;
                $memberTotalPrice += $price5;
                ?>

                <td class="td_right"><?php echo $lms_g_attach ?></td>
                <td class="td_right"><?php echo $row2['lms_g_attach'] ?></td>
                <?php
                $price6= $row2['lms_g_attach'] * $lms_g_attach;
                $memberTotalPrice += $price6;
                ?>

                <td class="td_right"><?php echo $lms_sur_simple ?></td>
                <td class="td_right"><?php echo $row2['lms_sur_simple'] ?></td>
                <?php
                $price7= $row2['lms_sur_simple'] * $lms_sur_simple;
                $memberTotalPrice += $price7;
                ?>

                <td class="td_right"><?php echo $lms_sur_attach ?></td>
                <td class="td_right"><?php echo $row2['lms_sur_attach'] ?></td>
                <?php
                $price8= $row2['lms_sur_attach'] * $lms_sur_attach;
                $memberTotalPrice += $price8
                ?>
                <td class="td_right"><?php echo $memberTotalPrice ?></td>
            </tr>

            <?php
            //합계를 위해 종류별 <건수>를 증가
            $total_sms_g_simple   += $row2['sms_g_simple'];
            $total_sms_g_attach   += $row2['sms_g_attach'];
            $total_sms_sur_simple += $row2['sms_sur_simple'];
            $total_sms_sur_attach += $row2['sms_sur_attach'];
            $total_lms_g_simple   += $row2['lms_g_simple'];
            $total_lms_g_attach   += $row2['lms_g_attach'];
            $total_lms_sur_simple += $row2['lms_sur_simple'];
            $total_lms_sur_attach += $row2['lms_sur_attach'];
            //합계를 위해 종류별 <가격>을 증가
            $total_price += $memberTotalPrice;
        }
    }

    if ($i == 0) {
        echo '<tr><td colspan="11" class="empty_table">자료가 없습니다.</td></tr>';
    }else{
    ?>
        <tr>
            <td class="td_id">합계</td>

            <td class="td_right"></td>
            <td class="td_right"><?php echo $total_sms_g_simple; ?></td>

            <td class="td_right"></td>
            <td class="td_right"><?php echo $total_sms_g_attach; ?></td>

            <td class="td_right"></td>
            <td class="td_right"><?php echo $total_sms_sur_simple; ?></td>

            <td class="td_right"></td>
            <td class="td_right"><?php echo $total_sms_sur_attach; ?></td>

            <td class="td_right"></td>
            <td class="td_right"><?php echo $total_lms_g_simple; ?></td>

            <td class="td_right"></td>
            <td class="td_right"><?php echo $total_lms_g_attach; ?></td>

            <td class="td_right"></td>
            <td class="td_right"><?php echo $total_lms_sur_simple; ?></td>

            <td class="td_right"></td>
            <td class="td_right"><?php echo $total_lms_sur_attach; ?></td>

            <td class="td_right"><?php echo $total_price ?></td>
        </tr>

        <?php
    }
    ?>
    </tbody>
    </table>
</div>
<?php
include_once ('./admin.tail.php');
?>