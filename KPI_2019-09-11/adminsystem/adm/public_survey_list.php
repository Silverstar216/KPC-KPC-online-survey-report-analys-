<?php
$sub_menu = "300900";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from surveys INNER JOIN g5_member ON g5_member.mb_no = surveys.user_id ";

$sql_search = " where (1) and show_user_id = 0";
if ($stx) {
    $sql_search .= " and ( ";

            $sql_search .= " ({$sfl} like '{$stx}%') ";

    $sql_search .= " ) ";
}


/*
if($ss) {
    if ($ss == 7) $secret_link = true;
    else $secret_link = false;
} else {
    $secret_link = false;
}
*/
$secret_link = true;

if (!$sst) {
    $sst = "public_date";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$listall = '<a href="'.$_SERVER['PHP_SELF'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '공개설문 목록관리';
include_once('./admin.head.php');

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$colspan = 16;
?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    총공개목록 <?php echo number_format($total_count) ?>개
</div>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">

<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
    <option value="mb_nick"<?php echo get_selected($_GET['sfl'], "mb_nick"); ?>>기관명</option>        

    <option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id"); ?>>회원아이디</option>    
    <option value="title"<?php echo get_selected($_GET['sfl'], "title"); ?>>설문제목</option>
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
<input type="submit" class="btn_submit" value="검색">

</form>


<form name="surveylist" id="surveylist" action="./public_survey_delete.php" onsubmit="return surveylist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">

<div class="tbl_head02 tbl_wrap">
    <table style="table-layout: fixed;word-break: break-word; -ms-word-wrap: break-word">
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col" id="mb_list_chk" style="width:5%">
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th style="width:60%" scope="col" id="mb_list_id"><?php echo subject_sort_link('mb_id') ?>설문제목</a></th>
        <th style="width:5%; text-align: center;" scope="col" id="mb_list_name"><?php echo subject_sort_link('mb_name') ?>문항수</a></th>
        <th style="width:10%" scope="col" id="mb_list_nick"><?php echo subject_sort_link('mb_nick') ?>공개일시</a></th>

        <th style="width:5%" scope="col" id="mb_list_mobile">첨부문서</th>
        <th style="width:10%" scope="col" id="mb_list_tel">기관명</th>
        <th style="width:5%" scope="col" id="mb_list_auth">회원아이디</th>

    </tr>

    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {

        $mb_nick = get_sideview($row['mb_id'], $row['mb_nick'], $row['mb_email']);

        $mb_id = $row['mb_id'];
        $leave_msg = '';
        $intercept_msg = '';
        $intercept_title = '';

        $bg = 'bg'.($i%2);
    ?>
        <tr class="<?php echo $bg; ?>">
            <td headers="mb_list_chk" class="td_chk" >

                <input type="checkbox" name="chk[]" value="<?php echo $row['id'] ?>" id="chk_<?php echo $i ?>">
            </td>
            <td style="text-align: center;" headers="mb_list_name" class="td_mbname"><a href="<?=Main_DOMAIN?>preview/preview/<?=$row['id']?>" target="_blank"><?php echo $row['title']; ?></a></td>
            <td style="text-align: center;" headers="mb_list_name" class="td_mbname"><?php echo $row['question_count']; ?></td>
            <td headers="mb_list_join" class="td_date"><?php echo $row['public_date']; ?></td>
            <?php if($row['file_url'] ==="" || empty($row['file_url'])) {
                ?>
                <td style="text-align: center;" headers="mb_list_name" class="td_mbname"></td>
            <?php }else { ?>

                <td style='color:#ff0000;text-align: center;'><a href="<?=$row['file_url']?>" target="_blank">첨부문서</a></td>
            <?php } ?>

            <td style="text-align: center;" headers="mb_list_nick" class="td_name sv_use"><div><?php echo $row['mb_nick'] ?></div></td>
            <td style="text-align: center;" headers="mb_list_name" class="td_mbname"><?php echo $row['mb_id']; ?></td>

        </tr>

    <?php
    }
    if ($i == 0)
        echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
    ?>
    </tbody>
    </table>
</div>

<div class="btn_list01 btn_list" style="text-align: center;">

    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value">
</div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

<script>
function surveylist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}
</script>

<?php
include_once ('./admin.tail.php');
?>
