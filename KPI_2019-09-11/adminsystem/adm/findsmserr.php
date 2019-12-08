<?php
$sub_menu = "700901";
include_once('./_common.php');
auth_check($auth[$sub_menu], 'r');
$token = get_token();

$errmsg = '';
if (!$gijun_date) {
   $gijun_date = date("Y-m-d");
   $gijuntabe = date("Ym");
} else {
    $mm = substr($gijun_date,5,2);
    $dd   = substr($gijun_date,8,2);
    $yy   = substr($gijun_date,0,4);
    if (checkdate($mm, $dd, $yy)){
        $wondate = $gijun_date;
        $gijun_date = date("Y-m-d",strtotime($gijun_date));
        if ($gijun_date < '2015-03-01'){
            $errmsg = '[입력하신 일자('.$wondate.') 확인 필요.yyyy-mm-dd 형식, 2015년 3월 이후.금일로 변경조회함.]';
            $gijun_date = date("Y-m-d");
            $gijuntabe = date("Ym");
        } else {
            $gijun_date = date("Y-m-d",strtotime($gijun_date));
            $gijuntabe = date("Ym",strtotime($gijun_date));
        }
    } else {
        $errmsg = '[입력하신 일자('.$gijun_date.') 확인 필요.yyyy-mm-dd 형식, 2015년 3월 이후.금일로 변경조회함.]';
        $gijun_date = date("Y-m-d");
        $gijuntabe = date("Ym");
    }
}
$name_search_qry = '';
if($co_name){
    if ($co_name == ''){

    } else {
        $name_search_qry = " and g5.mb_nick like '%".$co_name."%' ";
    }
}

$logtable = "em_smt_log_".$gijuntabe;
$lms_logtable = "em_mmt_log_".$gijuntabe;
$sql_a = "select mb_nick,content,mt_report_code_ib,date_client_req,recipient_num,callback, ".
          "(select srt_name from sms_resultcode where srt_code = mt_report_code_ib) code_ny ".
          "from eletter.sms5_history s5,eletter.g5_member g5,imds.em_smt_tran ".
          "where mt_pr = hs_mt_pr and hs_flag = 0 and mt_report_code_ib != '1000' and g5.mb_no = s5.mb_no".$name_search_qry." and DATE_FORMAT(hs_datetime,'%Y-%m-%d') = '{$gijun_date}' ";

$sql_b = "select mb_nick,content,mt_report_code_ib,date_client_req,recipient_num,callback, ".
          "(select srt_name from sms_resultcode where srt_code = mt_report_code_ib) code_ny ".
          "from eletter.sms5_history s5,eletter.g5_member g5,imds.".$logtable.
          " where mt_pr = hs_mt_pr and hs_flag = 0 and mt_report_code_ib != '1000' and g5.mb_no = s5.mb_no".$name_search_qry." and DATE_FORMAT(hs_datetime,'%Y-%m-%d') = '{$gijun_date}' ";

$sql_la = "select mb_nick,content,mt_report_code_ib,date_client_req,recipient_num,callback, ".
          "(select srt_name from sms_resultcode where srt_code = mt_report_code_ib) code_ny ".
          "from eletter.sms5_history s5,eletter.g5_member g5,imds.em_mmt_tran ".
          "where mt_pr = hs_mt_pr and hs_flag = 0 and mt_report_code_ib != '1000' and g5.mb_no = s5.mb_no".$name_search_qry." and DATE_FORMAT(hs_datetime,'%Y-%m-%d') = '{$gijun_date}' ";

$sql_lb = "select mb_nick,content,mt_report_code_ib,date_client_req,recipient_num,callback, ".
          "(select srt_name from sms_resultcode where srt_code = mt_report_code_ib) code_ny ".
          "from eletter.sms5_history s5,eletter.g5_member g5,imds.".$lms_logtable.
          " where mt_pr = hs_mt_pr and hs_flag = 0 and mt_report_code_ib != '1000' and g5.mb_no = s5.mb_no".$name_search_qry." and DATE_FORMAT(hs_datetime,'%Y-%m-%d') = '{$gijun_date}' ";

$result_a = sql_query($sql_a);
$result_b = sql_query($sql_b);
$result_la = sql_query($sql_la);
$result_lb = sql_query($sql_lb);
$g5['title'] = '오류 내역';
include_once ('./admin.head.php');
$spam_cnt = 0;
?>
<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
<label for="stx" >검색일자</label>
<input type="text" name="gijun_date" value="<?php echo $gijun_date ?>" id="gijun_date" required class="frm_input">
<label for="stx" >기관명</label>
<input type="text" name="co_name" value="<?php echo $co_name ?>" id="co_name" class="frm_input">
<input type="submit" class="btn_submit" value="검색">
</form>
<br>
<h1>
<?php echo '대상일자 : '.$gijun_date.$errmsg;?>
</h1>
<div class="tbl_head01 tbl_wrap">
    <table>
    <caption>문자 전송 목록</caption>
    <thead>
    <tr>
        <th scope="col">기관명</th>
        <th scope="col" class="td_tel">수신번호</th>
        <th scope="col" class="td_tel">발신번호</th>
        <th scope="col">메세지내용</th>
        <th scope="col" class="td_cntsmall">오류코드</th>
        <th scope="col" class="td_datetime">전송일시</th>        
        <th scope="col" class="td_odrnum">코드내역</th>        
    </tr>
    </thead>
    <tbody>
    <?php
        $i=0;
    while($row=sql_fetch_array($result_a)) {
        $bg = 'bg'.($i%2);        
        $e_code   = $row['mt_report_code_ib']; 
        if (($e_code == '1004')||($e_code == '3006')||($e_code == '3012')){
            $spam_cnt++;
        }
    ?>
    <tr class="<?php echo $bg; ?>">
        <td class="td_id"><?php echo $row['mb_nick']; ?></td>
        <td><?php echo $row['recipient_num']; ?></td>
        <td><?php echo $row['callback']; ?></td>
        <td><?php echo $row['content']; ?></td>
        <td><?php echo $e_code; ?></td>
        <td><?php echo $row['date_client_req']; ?></td>
        <td><?php echo $row['code_ny']; ?></td>        
    </tr>
    <?php
        $i++;
    }
    while($row=sql_fetch_array($result_b)) {
        $bg = 'bg'.($i%2);        
        $e_code   = $row['mt_report_code_ib']; 
        if ($e_code == '40'){
            $spam_cnt++;
        }
    ?>
    <tr class="<?php echo $bg;?>">
        <td class="td_id"><?php echo $row['mb_nick']; ?></td>
        <td><?php echo $row['recipient_num']; ?></td>
        <td><?php echo $row['callback']; ?></td>
        <td><?php echo $row['content']; ?></td>
        <td><?php echo $e_code; ?></td>
        <td><?php echo $row['date_client_req']; ?></td>
        <td><?php echo $row['code_ny']; ?></td>        
    </tr>
    <?php
        $i++;
    }    
    while($row=sql_fetch_array($result_la)) {
        $bg = 'bg'.($i%2);        
        $e_code   = $row['RSLT']; 
    ?>
    <tr class="<?php echo $bg; ?>">
        <td class="td_id"><?php echo $row['mb_nick']; ?></td>
        <td><?php echo $row['recipient_num']; ?></td>
        <td><?php echo $row['callback']; ?></td>
        <td><?php echo $row['content']; ?></td>
        <td><?php echo $e_code; ?></td>
        <td><?php echo $row['date_client_req']; ?></td>
        <td><?php echo $row['code_ny']; ?></td>        
    </tr>
    <?php
        $i++;
    }
    while($row=sql_fetch_array($result_lb)) {
        $bg = 'bg'.($i%2);        
        $e_code   = $row['RSLT']; 
    ?>
    <tr class="<?php echo $bg; ?>">
        <td class="td_id"><?php echo $row['mb_nick']; ?></td>
        <td><?php echo $row['recipient_num']; ?></td>
        <td><?php echo $row['callback']; ?></td>
        <td><?php echo $row['content']; ?></td>
        <td><?php echo $e_code; ?></td>
        <td><?php echo $row['date_client_req']; ?></td>
        <td><?php echo $row['code_ny']; ?></td>        
    </tr>
    <?php
        $i++;
    }
    if ($i == 0) {
        echo '<tr><td colspan="7" class="empty_table">자료가 없습니다.</td></tr>';
    } else {?>
    <tr style="background: rgb(255, 52, 69);">
        <td colspan = "4">전체 건수 : <?php echo number_format($i); ?></td>
        <td colspan="4">스팸 건수 : <?php echo number_format($spam_cnt); ?></td>
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