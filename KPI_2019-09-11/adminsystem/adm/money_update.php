<?php
$sub_menu = "700700";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

if ($rq_mb_no == '') {
    alert('처리대상이 선택되지 않았습니다.');
}
$sql_common = " from {$g5['member_table']},ele_price_user,ele_money_mst ";
$sql_search = " where mb_no = '{$rq_mb_no}' and ".
                                 "elpu_type = elem_type and elpu_stat = 'm' and elpu_end_date = '9999-12-31 00:00:00' ".
                                  "and elem_id = mb_no";
$sql = " select *
            {$sql_common}
            {$sql_search}";           
$result = sql_query($sql);
$row=sql_fetch_array($result);
$eletoday = date("Y-m-d");
$expr = '';
$elem_stat = $row['elem_stat'];
$elem_type = $row['elem_type'];
$elpu_gubn = $row['elpu_gubn'];
$elem_type_s = $row['elpu_type_name'];
if ($row['elem_start_date'] == '0000-00-00 00:00:00'){
    $elem_start_date = '';
} else {
    $elem_start_date = date("Y-m-d",strtotime($row['elem_start_date']));    
}
if ($row['elem_expire_date'] == '9999-12-31 00:00:00'){
        $elem_expire_date = '';
} else {
       $elem_expire_date =  date("Y-m-d",strtotime($row['elem_expire_date']));
       if ($elpu_gubn == 'A') {
        if ($eletoday > $elem_expire_date){
            $elem_expire_date = '만료 '.$elem_expire_date;
            $expr = ' txt_expired';
        }
        } else {
            $elem_expire_date = '';
        }

$elem_crnt_cnt = $row['elem_crnt_cnt'];
$elem_crnt_cv_bonus_cnt = $row['elem_crnt_cv_bonus_cnt'];
}
$g5['title'] = '요금 처리';
include_once ('./admin.head.php');
?>
<div class="tbl_head01 tbl_wrap">
    <h2 class="h2_frm">처리전 내역</h2>    
    <table>
    <thead>
    <tr>
        <th scope="col">아이디</th>
        <th scope="col">이름</th>
        <th scope="col">기관명</th>
        <th scope="col">요금제</a></th>
        <th scope="col">입금액</a></th>        
        <th scope="col">sms잔여건</a></th>
        <th scope="col">무료변환잔여건</a></th>
        <th scope="col">적용단가</a></th>                
        <th scope="col">시작일</a></th>
        <th scope="col">만료일</th>        
    </tr>
    </thead>
    <tbody>
    <tr >
        <td class="td_mbid"><?php echo $row['mb_id'] ?></td>
        <td class="td_mbname"><?php echo $row['mb_name'] ?></td>
        <td class="td_name sv_use"><?php echo $row['mb_nick'] ?></td>
        <td class="td_mbname"><?php echo $elem_type_s ?></td>
        <td class="td_num"><?php echo number_format($row['elem_money']) ?></td>
        <td class="td_num"><?php echo number_format($row['elem_crnt_cnt']) ?></td>
        <td class="td_num"><?php echo number_format($row['elem_crnt_cv_bonus_cnt']) ?></td>
        <td class="td_num"><?php echo number_format($row['elem_sms_user_price'],1) ?></td>
        <td class="td_datetime"><?php echo $elem_start_date ?></td>        
        <td class="td_date<?php echo $expr; ?>"><?php echo $elem_expire_date ?></td>
    </tr>
    </tbody>
    </table>
</div>
<div class="tbl_head01 tbl_wrap">
    <h2 class="h2_frm">처리전 요금제 기준 표</h2>    
    <table>
    <thead>
    <tr>
           <th scope="col">요금제명</th>                
        <?php if ($elem_type == '001') { ?>
            <th scope="col">무료변환건수</th>        
        <?php } else { ?>
            <th scope="col">요금</th>
            <th scope="col">월제한건수</th>        
            <th scope="col">SMS적용가격</th>                
        <?php  }?>        
    </tr>
    </thead>
    <tbody>
    <tr >
               <td class="td_mbname"><?php echo $elem_type_s ?></td>        
        <?php if ($elem_type = '001') { ?>
                <td class="td_num"><?php echo number_format($row['elpu_cv_limit_count']) ?></td>       
        <?php } else { ?>
                <td class="td_num"><?php echo number_format($row['elpu_money']) ?></td>       
                <td class="td_num"><?php echo number_format($row['elpu_sms_limit_count']) ?></td>
                <td class="td_num"><?php echo number_format($row['elpu_sms_user_money'],1) ?></td>
        <?php } ?>        
    </tr>
    </tbody>
    </table>
</div>
<?php
$remain_cnt = $elem_crnt_cnt;
$remain_bonus = $elem_crnt_cv_bonus_cnt; 
include_once('money_update_form.php');
include_once ('./admin.tail.php');
?>
