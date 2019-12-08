<?php
$sub_menu = "700100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$token = get_token();

$sql_common = " from ele_money_hst, {$g5['member_table']} ";

$sql_search = " where mb_no = eleh_id ";

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

if (!$sst) {
    $sst  = "eleh_id";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

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

$g5['title'] = '요금 처리 내역';
include_once ('./admin.head.php');

$colspan = 9;

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

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
    <option value="mb_nick"<?php echo get_selected($_GET['sfl'], "mb_nick"); ?>>기관명</option>
    <option value="mb_name"<?php echo get_selected($_GET['sfl'], "mb_name"); ?>>담당자명</option>
    <option value="eleh_date"<?php echo get_selected($_GET['sfl'], "eleh_date"); ?>>처리일자</option>    
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
        <th scope="col"><?php echo subject_sort_link('mb_id') ?>아이디</a></th>
        <th scope="col"><?php echo subject_sort_link('mb_name') ?>이름</th>
        <th scope="col"><?php echo subject_sort_link('mb_nick') ?>기관명</th>
        <th scope="col"><?php echo subject_sort_link('eleh_inout') ?>처리구분</a></th>
        <th scope="col"><?php echo subject_sort_link('eleh_real_money') ?>실금액</a></th>        
        <th scope="col"><?php echo subject_sort_link('eleh_sms_cnt') ?>sms처리건</a></th>
        <th scope="col"><?php echo subject_sort_link('eleh_cv_cnt') ?>변환 건수</a></th>        
        <th scope="col"><?php echo subject_sort_link('eleh_sms_user_price') ?>사용단가</a></th>                        
        <th scope="col"><?php echo subject_sort_link('eleh_date') ?>처리일자</a></th>
    </tr>
  
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $bg = 'bg'.($i%2);
        
        $eleh_inout = $row['eleh_inout'];
        switch ($eleh_inout) {
            case '1':
                $elem_type_s = '계약입금';
                break;
            case '2':
                $elem_type_s = '사용';
                break;
            case '3':
                $elem_type_s = '조정';
                break;
            case '4':
                $elem_type_s = '환불 ';
                break;
            case '5':
                $elem_type_s = '추가입금';
                break;
            
            default:
               $elem_type_s = '';
                break;
        }
    ?>
    <tr class="<?php echo $bg; ?>">
        <td class="td_mbid"><?php echo $row['mb_id'] ?></td>
        <td class="td_mbname"><?php echo $row['mb_name'] ?></td>
        <td class="td_name sv_use"><?php echo $row['mb_nick'] ?></td>
        <td class="td_mbname"><?php echo $elem_type_s ?></td>
        <td class="td_num"><?php echo number_format($row['eleh_real_money']) ?></td>
        <td class="td_num"><?php echo number_format($row['eleh_sms_cnt']) ?></td>
        <td class="td_num"><?php echo number_format($row['eleh_cv_cnt']) ?></td>
        <td class="td_num"><?php echo number_format($row['eleh_sms_user_price'],1) ?></td>
        <td class="td_datetime"><?php echo $row['eleh_date'] ?></td>        
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
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['PHP_SELF']}?$qstr&amp;page="); ?>
<?php
include_once ('./admin.tail.php');
?>