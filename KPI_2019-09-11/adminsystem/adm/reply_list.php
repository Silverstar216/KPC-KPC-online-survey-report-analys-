<?php
$sub_menu = "200200";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');
/*
$sql_common = " from sender_phone a";
$sql_search = " where status = 0 ";
$sql_order = " order by request_date desc ";

$sql = " select count(*) as cnt {$sql_common} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];
$sql = " select count(*) as cnt {$sql_common} {$sql_search} ";
$row = sql_fetch($sql);

$new_idenity = $row['cnt'];
$sql = " select ".
        " (select uid from users where id = a.user_id) uid,".
        " (select name from users where id = a.user_id) name,".
        " (select company from users where id = a.user_id) company,".
       "a.* {$sql_common} {$sql_search} {$sql_order} ";
$result = sql_query($sql);*/



$token = get_token();

$sql_common = " from sender_phone ,g5_member ";

$sql_search = " where mb_no = user_id";
$sql_search1 = " where mb_no = user_id and status = 0 ";

if ($stx) {
    $sql_search .= " and ( ";
    $sql_search1 .= " and ( ";
    switch ($sfl) {
        case 'mb_id' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            $sql_search1 .= " ({$sfl} = '{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            $sql_search1 .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
    $sql_search1 .= " ) ";
}
if($sfl ==="status") {
    $sql_search .= " and status = '0' ";
}
$sst1  = "status";
$sod1 = "asc";
if (!$sst) {
    $sst  = "request_date";
    $sod = "desc";

}
$sql_order = " order by mb_id asc ,{$sst1} {$sod1} , {$sst} {$sod} ";

$sql = " select count(*) as cnt
            {$sql_common}
            {$sql_search}
            {$sql_order} ";
$row = sql_fetch($sql);

$total_count = $row['cnt'];
$sql = " select count(*) as cnt
            {$sql_common}
            {$sql_search1}
            {$sql_order} ";
$row = sql_fetch($sql);
$new_idenity = $row['cnt'];

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
$listall1 = '<a href="'.$_SERVER['PHP_SELF'].'?stx=0&sfl=status" class="ov_listall">미 인증 '.number_format($new_idenity).'건</a>';

$mb = array();
if ($sfl == 'mb_id' && $stx)
    $mb = get_member($stx);

$g5['title'] = '발신번호 인증 관리';
include_once ('./admin.head.php');

$colspan = 7;

$po_expire_term = '';
if($config['cf_point_term'] > 0) {
    $po_expire_term = $config['cf_point_term'];
}

if (strstr($sfl, "mb_id"))
    $mb_id = $stx;
else
    $mb_id = "";
?>


<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    전체 <?php echo number_format($total_count) ?>  건 중
    <?php echo $listall1 ?>
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
    <option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id"); ?>>아이디</option>
    <option value="mb_nick"<?php echo get_selected($_GET['sfl'], "mb_nick"); ?>>기관명</option>
    <option value="mb_name"<?php echo get_selected($_GET['sfl'], "mb_name"); ?>>이름</option>
    <option value="mb_hp"<?php echo get_selected($_GET['sfl'], "mb_hp"); ?>>전화번호</option>
</select>
<label for="stx" class="sound_only">검색어</label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
<input type="submit" class="btn_submit" value="검색">
</form>

<form name="fpointlist" id="fpointlist" method="post" action="./reply_list_identify.php" onsubmit="return freplylist_submit(this);">
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
        <th style="width:5%" scope="col">
            <label for="chkall" class="sound_only">미 인증 내역 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th style="width:10%" scope="col"><?php echo subject_sort_link('mb_id') ?>회원아이디</a></th>
        <th style="width:10%" scope="col"><?php echo subject_sort_link('mb_name') ?>이름</a></th>
        <th style="width:15%" scope="col"><?php echo subject_sort_link('mb_nick') ?>기관명</a></th>
        <th style="width:15%" scope="col"><?php echo subject_sort_link('phone') ?>발신번호</a></th>
        <th style="width:15%" scope="col"><?php echo subject_sort_link('request_date') ?>요청일시</a></th>
        <th style="width:5%" scope="col"><?php echo subject_sort_link('status') ?>인증여부</a></th>
        <th style="width:15%" scope="col"><?php echo subject_sort_link('verify_date') ?>인증일시</a></th>
        <th style="width:10%" scope="col"><?php echo subject_sort_link('memo') ?>비고</a></th>
        
        
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $bg = 'bg'.($i%2);
    ?>

    <tr class="<?php echo $bg; ?>">
        <td class="td_chk">
            <input type="hidden" name="mb_no[<?php echo $i ?>]" value="<?php echo $row['mb_no'] ?>" id="mb_no_<?php echo $i ?>">
            <input type="hidden" name="ph_id[<?php echo $i ?>]" value="<?php echo $row['id'] ?>" id="ph_id_<?php echo $i ?>">
            <label for="chk_<?php echo $i; ?>" class="sound_only">미인증 내역</label>
            <input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
        </td>
        <td class="td_mbid"><a href="?sfl=mb_id&amp;stx=<?php echo $row['mb_id'] ?>"><?php echo $row['mb_id'] ?></a></td>
        <td class="td_mbname"><?php echo $row['mb_name'] ?></td>
        <td class="td_name sv_use"><div><?php echo $row['mb_nick'] ?></div></td>
        <td class="td_name"><?php echo $row['phone'] ?></td>
        <td class="td_datetime"><?php echo $row['request_date'] ?></td>
        <td class="td_name">

            <?php
                        if ($row['status']==="0") echo "";
                        else echo "인증됨";
                        ?>

        </td>
        <td class="td_datetime"><?php echo $row['verify_date'] ?></td>
        <td ><?php echo $row['memo'] ?></td>
    </tr>

    <?php
    }

    if ($i == 0)
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>

<div class="btn_list01 btn_list">
    <input type="submit" name="act_button" value="선택인증" onclick="document.pressed=this.value">
</div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['PHP_SELF']}?$qstr&amp;page="); ?>


<script>
function freplylist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택인증") {
        if(!confirm("선택한 번호를 인증 처리하시겠습니까?")) {
            return false;
        }
    }

    return true;
}
</script>

<?php
include_once ('./admin.tail.php');
?>
