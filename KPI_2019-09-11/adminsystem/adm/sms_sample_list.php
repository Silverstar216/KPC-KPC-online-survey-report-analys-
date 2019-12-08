<?php
$sub_menu = '300800';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = 'InChon-SchoolNews SMS 예문 관리';
include_once (G5_ADMIN_PATH.'/admin.head.php');

$sql_common = " from sample_sms_info ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count_q = $row['cnt'];

$sql = "select * $sql_common order by si_sygb desc, si_time desc";
$result = sql_query($sql);


$sql_common_his = " from sample_reader ss";

// 테이블의 전체 레코드수만 얻음
$sql_his = " select count(*) as cnt " . $sql_common_his;
$row_his = sql_fetch($sql_his);
$total_count = $row_his['cnt'];

$rows = 5;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql_his = "select ss.* ".$sql_common_his." order by request_time desc limit ".$from_record.",".$rows;
$result_his = sql_query($sql_his);
?>

<div class="local_ov01 local_ov">
    <?php if ($page > 1) {?><a href="<?php echo $_SERVER['PHP_SELF']; ?>">처음으로</a><?php } ?>
    <span>전체 <?php echo $total_count_q; ?>건</span>
</div>

<div class="local_desc01 local_desc">
    <ol>
        <li>실제로 전송되는 것은 사용중(Y)인 1건만 입니다</li>
        <li>최종 사용중인 것 외에는 <strong>미사용으로 변경</strong>됩니다.</li>
    </ol>
</div>

<div class="btn_add01 btn_add">
    <a href="./sms_sample_form.php">추가 등록</a>
</div>

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">회신번호</th>
        <th scope="col">메세지</th>
        <th scope="col">등록시간</th>
        <th scope="col">사용여부</th>
        <th scope="col">관리</th>
    </tr>
    </thead>
    <tbody>
    <?php for ($i=0; $row=mysqli_fetch_array($result); $i++) {        
        $bg = 'bg'.($i%2);
    ?>
    <tr class="<?php echo $bg; ?>">
        <td class="td_odrnum2"><?php echo $row['si_reply']; ?></td>
        <td><?php echo $row['si_msg']; ?></td>
        <td class="td_odrnum2"><?php echo $row['si_time']; ?></td>
        <td class="td_num"><?php echo $row['si_sygb']; ?></td>
        <td class="td_mng">
            <a href="./sms_sample_form.php?w=u&amp;fm_id=<?php echo $row['si_ukey']; ?>"><span class="sound_only"><?php echo stripslashes($row['si_msg']); ?> </span>수정</a>            
            <?php if ($row['si_sygb'] <> 'Y') { ?>
            <a href="./sms_sample_update.php?w=d&amp;fm_id=<?php echo $row['si_ukey']; ?>" onclick="return delete_confirm();"><span class="sound_only"><?php echo stripslashes($row['si_msg']); ?> </span>삭제</a>
            <?php } ?>            
        </td>
    </tr>
    <?php
    }

    if ($i == 0){
        echo '<tr><td colspan="5" class="empty_table"><span>자료가 한건도 없습니다.</span></td></tr>';
    }
    ?>
    </tbody>
    </table>
</div>
<br><br>
<div class="local_ov01 local_ov">
    <strong>예문 요청 현황</strong>&nbsp;&nbsp;&nbsp;
    <?php if ($page_his > 1) {?><a href="<?php echo $_SERVER['PHP_SELF']; ?>">처음으로</a><?php } ?>
    <span>전체 <?php echo $total_count; ?>건</span>
</div>

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption>요청 목록</caption>
    <thead>
    <tr>
        <th scope="col">회신번호</th>
        <th scope="col">요청시간</th>        
        <th scope="col">관련사항</th>        
        <th scope="col">메세지</th>
    </tr>
    </thead>
    <tbody>
    <?php for ($i=0; $row_his=mysqli_fetch_array($result_his); $i++) {        
        $bg = 'bg'.($i%2);
        if ($row_his['relation'] <> '0') {
            $whoareu = '주소록에 있음';
        } else {
            $whoareu =  '';   
        }
    ?>
    <tr class="<?php echo $bg; ?>">
        <td class="td_odrnum2"><?php echo $row_his['mobile']; ?></td>
        <td class="td_odrnum2"><?php echo $row_his['request_time']; ?></td>
        <td class="td_odrnum2"><?php echo $whoareu; ?></td>
        <td><?php echo $row_his['content']; ?></td>
    </tr>
    <?php
    }

    if ($i == 0){
        echo '<tr><td colspan="3" class="empty_table"><span>자료가 한건도 없습니다.</span></td></tr>';
    }
    ?>
    </tbody>
    </table>
</div>
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['PHP_SELF']}?$qstr&amp;page="); ?>
<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
