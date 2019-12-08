<?php
$sub_menu = "700100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$token = get_token();

$sql_common = " from user_money, {$g5['member_table']} ";

$sql_search = " where user_id = mb_no ";

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_id' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

$sql_order = " order by mb_no desc ";

$sql = " select count(*) as cnt
            {$sql_common}
            {$sql_search}
            {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select *
            {$sql_common}
            {$sql_search}
            {$sql_order}
            limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['PHP_SELF'].'" class="ov_listall">전체목록</a>';

$mb = array();
if ($sfl == 'mb_id' && $stx)
    $mb = get_member($stx);

$g5['title'] = '요금관리';
include_once ('./admin.head.php');

$colspan = 10;

$po_expire_term = '';
if($config['cf_point_term'] > 0) {
    $po_expire_term = $config['cf_point_term'];
}

if (strstr($sfl, "mb_id"))
    $mb_id = $stx;
else
    $mb_id = "";
$processFlag = false;
?>

<!--<form name="updateprice" id="updateprice" class="local_sch01 local_sch" method="post" onsubmit="return fconfigform_submit(this);">
	<label for="elpe_sms_money" >건당가격 (SMS)</label>
	<input type="text" name="elpe_sms_money" value="<?php echo $price_user['elpe_sms_money'] ?>" id="elpe_sms_money" required class="required frm_input">
	<label for="elpe_lms_money" >건당가격 (LMS)</label>
	<input type="text" name="elpe_lms_money" value="<?php echo $price_user['elpe_lms_money'] ?>" id="elpe_lms_money" required class="required frm_input">
	<label for="elpe_mms_money" >건당가격 (MMS)</label>
	<input type="text" name="elpe_mms_money" value="<?php echo $price_user['elpe_mms_money'] ?>" id="elpe_mms_money" required class="required frm_input">
	<label for="elpe_cv_money" >건당가격 (Convert)</label>
	<input type="text" name="elpe_cv_money" value="<?php echo $price_user['elpe_cv_money'] ?>" id="elpe_cv_money" required class="required frm_input">
	<input type="submit" class="btn_submit" value="갱신">
</form>-->

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
    <option value="mb_nick"<?php echo get_selected($_GET['sfl'], "mb_nick"); ?>>기관명</option>    
    <option value="elem_start_date"<?php echo get_selected($_GET['sfl'], "elem_start_date"); ?>>시작일자</option>
    <option value="mb_name"<?php echo get_selected($_GET['sfl'], "mb_name"); ?>>담당자명</option>
    <option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id"); ?>>회원아이디</option>    
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
<input type="submit" class="btn_submit" value="검색">
</form>


<form name="fpointlist" id="fpointlist">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="<?php echo $token ?>">

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col"><?php echo subject_sort_link('mb_id') ?>아이디</th>
        <th scope="col"><?php echo subject_sort_link('mb_name') ?>이름</th>
        <th scope="col"><?php echo subject_sort_link('mb_nick') ?>기관명</th>
        <th scope="col" width="7%"><?php echo subject_sort_link('elem_type') ?>요금제</th>
        <th scope="col"><?php echo subject_sort_link('elem_money') ?>입금액</th>
		<th scope="col" width="5%"><?php echo subject_sort_link('elem_crnt_money') ?>잔여액</a></th>
        <th scope="col"><?php echo subject_sort_link('elem_crnt_cnt') ?>sms잔여건</th>
        <!--<th scope="col"><?php echo subject_sort_link('elem_crnt_cv_bonus_cnt') ?>무료변환잔여건</th>-->        		
		<th scope="col" width="7%"><?php echo subject_sort_link('elem_charge_first_count') ?>선불충전건수</a></th>                
		<th scope="col" width="7%"><?php echo subject_sort_link('elem_charge_last_count') ?>후불유지건수</a></th>                
        <th scope="col" width="8%"><?php echo subject_sort_link('elem_start_date') ?>요금처리일</a></th>
<!--        <th scope="col" width="8%">--><?php //echo subject_sort_link('elem_expire_date') ?><!--상 세</th>         -->
    </tr>
    </thead>
    <tbody>
    <?php
    $eletoday = date("Y-m-d");
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $expr = '';
        $elem_stat = $row['elem_stat'];
        $charge_type = $row['charge_type'];
        $elem_type_s = $row['elpu_type_name'];
        $alink = "<a href='./money_update.php?rq_mb_no=".$row['elem_id']."'>";
        if ($row['elem_start_date'] == '0000-00-00 00:00:00'){
            $elem_start_date = '';
        } else {
            $elem_start_date = date("Y-m-d",strtotime($row['elem_start_date']));    
        }
        
        if ($row['elem_expire_date'] == '9999-12-31 00:00:00'){
                $elem_expire_date = '';
        } else {
               $elem_expire_date =  date("Y-m-d",strtotime($row['elem_expire_date']));
                if ($eletoday > $elem_expire_date){
                    $elem_expire_date = '만료 '.$elem_expire_date;
                    $expr = ' txt_expired';
                }
        }
        $bg = 'bg'.($i%2);
        if ($p_name == $row['mb_id']) {
            $processFlag = true;
            $prow = $row;
            $prow_type_s = $elem_type_s;
        }       

		$s_mod = '<a href="./money_form.php?'.$qstr.'&amp;w=u&amp;mb_id='.$row['mb_id'].'">';		
    ?>
    <tr class="<?php echo $bg; ?>">
        <td class="td_mbid"><?php echo $s_mod.$row['mb_id'] ?></a></td>
        <td class="td_mbname"><?php echo $row['mb_name'] ?></td>
        <td class="td_name sv_use"><?php echo $row['mb_nick'] ?></td>
        <td class="td_mbname"><?= $charge_type=='0'? '선불충전식':'후불정산제' ?></td>
        <td class="td_num"><?php if ($charge_type=='0') echo number_format($row['last_deposit'])?></td>
		<td class="td_mbname"><?php if ($charge_type=='0') echo number_format($row['current_amount']) ?></td>
        <td class="td_num"><?php if ($charge_type=='002') echo number_format($row['elem_crnt_cnt']) ?></td>
		<td class="td_num"><?php if ($charge_type=='0') echo number_format($row['charge_count']) ?></td>
		<td class="td_num"><?php if ($charge_type=='1') echo number_format($row['month_count']) ?></td>
        <td class="td_datetime"><?php echo $row['last_charge_date'] ?></td>
<!--        <td class="td_date--><?php //echo $expr; ?><!--">--><?php //echo $row['expire_date'] ?><!--</td>-->
    </tr>
    <?php
    }

    if ($i == 0)
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>
</form>

<script>
function fconfigform_submit(f)
{
    f.action = "./ele_price_update.php";
    return true;
}
</script>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['PHP_SELF']}?$qstr&amp;page="); ?>
<?php
include_once ('./admin.tail.php');
?>