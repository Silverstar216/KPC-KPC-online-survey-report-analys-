<?php
$sub_menu = "700500";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$token = get_token();

if (!$end_date) {
   $gijun_date = date("Y-m-d");
   $end_date = $gijun_date;
   $start_date = $gijun_date;
} else {
    $gijun_date = $end_date;
}

        $gijun_start= substr($gijun_date,0,7).'-01';//이번달 1일 
        $before_start_day = date("Y-m-d",strtotime($gijun_start.' -1 month'));
        $before_end_day  = date("Y-m-d",strtotime($gijun_start.' -1 day'));
        $before_month= substr($before_start_day,0,7);
        $curr_month= substr($gijun_date,0,7);
$sql = " select DATE_FORMAT(hs_datetime,'%Y-%m')  month, ".
"sum(case when (mb_id != 'LMS') then 1 else 0 end) smscnt,".
"sum(case when (mb_id = 'LMS') then 1 else 0 end) lmscnt,".
"sum(case when (hs_flag = '1') then 1 else 0 end) success,".
"sum(case when (hs_flag = '0') then 1 else 0 end) fail ".
" from sms5_history ".
"where DATE_FORMAT(hs_datetime,'%Y-%m') in ('{$before_month}','{$curr_month}') ".
            "group by 1 ";

$result = sql_query($sql);

$sql = " select mb_no,(select mb_nick from g5_member where mb_no = sh.mb_no) mb_nick, ".
"sum(case when (mb_id != 'LMS') then 1 else 0 end) smscnt,".
"sum(case when (mb_id = 'LMS') then 1 else 0 end) lmscnt,".
"sum(case when (hs_flag = '1') then 1 else 0 end) success,".
"sum(case when (hs_flag = '0') then 1 else 0 end) fail ".
" from sms5_history sh ".
"where DATE_FORMAT(hs_datetime,'%Y-%m-%d') between  '{$start_date}' and '{$end_date}' ".
            "group by 1,2 order by 5 desc ";
$d_result = sql_query($sql);

$g5['title'] = '문자 전송 현황';
include_once ('./admin.head.php');

?>
<div class="tbl_head01 tbl_wrap">
    <table>
    <caption>전월,현월 현황</caption>
    <thead>
    <tr>
        <th scope="col">구분 </a></th>
        <th scope="col">SMS</th>
        <th scope="col">LMS</th>
        <th scope="col">성공</th>
        <th scope="col">실패</th>
        <th scope="col">대기</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $smscnt     = $row['smscnt']; 
        $lmscnt      = $row['lmscnt']; 
        $success   = $row['success']; 
        $failcnt       =  $row['fail']; 
        $gita =  $smscnt  + $lmscnt  - $success  -$failcnt ;
    ?>
    <tr>
        <td class="td_id"><?php echo  $row['month'] ?></td>
        <td ><?php echo number_format($smscnt,0); ?></td>
        <td ><?php echo number_format($lmscnt,0); ?></td>
        <td ><?php echo number_format($success,0); ?></td>
        <td ><?php echo number_format($failcnt,0); ?></td>
        <td ><?php echo number_format($gita,0); ?></td>
    </tr>
    <?php
    }

    if ($i == 0)
        echo '<tr><td colspan="6" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
<label for="stx" >검색기간<strong class="sound_only"> 필수</strong></label>
<input type="text" name="start_date" value="<?php echo $start_date ?>" id="start_date" required class="required frm_input">~
<input type="text" name="end_date" value="<?php echo $end_date ?>" id="end_date" required class="required frm_input">
<input type="submit" class="btn_submit" value="검색">
</form>
<br>
<h1>
<?php echo '대상기간 : '.$start_date.' ~ '.$end_date;?>
</h1>
<div class="tbl_head01 tbl_wrap">
    <table>
    <caption>문자 전송 목록</caption>
    <thead>
    <tr>
        <th scope="col">기관명</th>
        <th scope="col">SMS건</th>
        <th scope="col">LMS</th>
        <th scope="col">성공</th>        
        <th scope="col">실패</th>    
        <th scope="col">대기</th>        
    </tr>
  
    </thead>
    <tbody>
    <?php
    for ($i=0; $row2=sql_fetch_array($d_result); $i++) {
        $bg = 'bg'.($i%2);        
        $mb_nick   = $row2['mb_nick'];
        $smscnt     = $row2['smscnt']; 
        $lmscnt      = $row2['lmscnt']; 
        $success   = $row2['success']; 
        $failcnt       =  $row2['fail']; 
        $gita =  $smscnt  + $lmscnt  - $success  -$failcnt ;        
    ?>
    <tr class="<?php echo $bg; ?>">
        <td class="td_id"><?php echo $mb_nick; ?></td>
        <td ><?php echo number_format($smscnt,0); ?></td>
        <td ><?php echo number_format($lmscnt,0); ?></td>
        <td ><?php echo number_format($success,0); ?></td>
        <td ><?php echo number_format($failcnt,0); ?></td>
        <td ><?php echo number_format($gita,0); ?></td>
    </tr>
    <?php
    }

    if ($i == 0) {
        echo '<tr><td colspan="6" class="empty_table">자료가 없습니다.</td></tr>';
    }
    ?>
    </tbody>
    </table>
</div>
<?php
include_once ('./admin.tail.php');
?>