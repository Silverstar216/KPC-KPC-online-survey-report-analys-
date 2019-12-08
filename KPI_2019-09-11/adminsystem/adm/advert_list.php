<?php
$sub_menu = "200830";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['member_table']} ";

$sql_search = " where (1) and (mb_level=5 or mb_level=7) and  mb_leave_date='' and mb_intercept_date='' ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_point' :
            $sql_search .= " ({$sfl} >= '{$stx}') ";
            break;
        case 'mb_level' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        case 'mb_nick' :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
        case 'mb_tel' :
        case 'mb_hp' :
            $sql_search .= " ({$sfl} like '%{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if ($is_admin != 'super') {
    $sql_search .= " and mb_level <= '{$member['mb_level']}' ";
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
function get_secret_link($level,$nick,$s_id){

    return '<a class="sbtn" href="./advert_link.php?mb_id=">연결</a>';
}
if (!$sst) {
    $sst = "mb_datetime";
    $sod = "desc";
}

$sql_order = " order by mb_level desc, mb_datetime desc";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
/*
// 탈퇴회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_leave_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$leave_count = $row['cnt'];

// 차단회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_intercept_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$intercept_count = $row['cnt'];*/

$listall = '<a href="'.$_SERVER['PHP_SELF'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '광고주관리';
include_once('./admin.head.php');

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$colspan = 16;
?>

<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    총 광고주 회원수 <?php echo number_format($total_count) ?>명
</div>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">

    <label for="sfl" class="sound_only">검색대상</label>
    <select name="sfl" id="sfl">
        <option value="mb_nick"<?php echo get_selected($_GET['sfl'], "mb_nick"); ?>>기관명</option>
        <option value="mb_name"<?php echo get_selected($_GET['sfl'], "mb_name"); ?>>이름</option>
        <option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id"); ?>>회원아이디</option>
        <option value="mb_level"<?php echo get_selected($_GET['sfl'], "mb_level"); ?>>권한</option>
    </select>
    <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
    <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
    <input type="submit" class="btn_submit" value="검색">

</form>

<div class="local_desc01 local_desc">
    <p>
        권한설정에서 5: 구, 7: 시, 10: 관리자 입니다.
        <!--(구청)등 홍보기관은 권한을 5로 부여하고 홍보삽입 대상은 그룹으로 결정합니다.<br>
        하위기관 권한 설정 (2 : eletter 홍보 첨부, 3 : 상위 홍보첨부, 4 : 첨부 불가)-->
    </p>
</div>
<!--
<?php /*if ($is_admin == 'super') { */?>
<div class="btn_add01 btn_add">
    <a href="./member_form.php" id="member_add">회원추가</a>
</div>
--><?php /*} */?>

<form name="fmemberlist" id="fmemberlist" action="./member_list_update.php" onsubmit="return fmemberlist_submit(this);" method="post">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">

    <div class="tbl_head02 tbl_wrap">
        <table>
            <caption><?php echo $g5['title']; ?> 목록</caption>
            <thead>
            <tr>
                <th scope="col" id="mb_list_chk" style="width:5%">

                </th>
                <th style="width:10%" scope="col" id="mb_list_id"><?php echo subject_sort_link('mb_id') ?>아이디</a></th>
                <th style="width:10%; text-align: center;" scope="col" id="mb_list_name"><?php echo subject_sort_link('mb_name') ?>이름</a></th>
                <th style="width:10%" scope="col" id="mb_list_auth">상태/<?php echo subject_sort_link('mb_level', '', 'desc') ?>권한</a></th>
                <th style="width:20%" scope="col" id="mb_list_nick"><?php echo subject_sort_link('mb_nick') ?>기관명</a></th>
                <th style="width:15%" scope="col" id="mb_list_mobile">휴대폰</th>
                <th style="width:30%" scope="col" id="mb_list_link">연결기관</th>

            </tr>
            </thead>
            <tbody>
            <?php
            for ($i=0; $row=sql_fetch_array($result); $i++) {

                $mb_nick = get_sideview($row['mb_id'], $row['mb_nick'], $row['mb_email'], $row['mb_homepage']);

                $mb_id = $row['mb_id'];
                $leave_msg = '';
                $intercept_msg = '';
                $intercept_title = '';

                $address = $row['mb_zip1'] ? print_address($row['mb_addr1'], $row['mb_addr2'], $row['mb_addr3'], $row['mb_addr_jibeon']) : '';
                $s_mod = '<a class="sbtn" href="./advert_link.php?'.$qstr.'&amp;w=u&amp;mb_id='.$row['mb_id'].'&amp;mb_level='.$row['mb_level'].'&amp;mb_nick='.$row['mb_nick'].'">연결</a>';
                $bg = 'bg'.($i%2);

                ?>
                <tr class="<?php echo $bg; ?>">
                    <td headers="mb_list_chk" class="td_chk" >

                    </td>
                    <td headers="mb_list_id"  class="td_name sv_use"><?php echo $mb_id ?> <?php echo  $s_mod?></td>
                    <td style="text-align: center;" headers="mb_list_name" class="td_mbname"><?php echo $row['mb_name']; ?></td>
                    <td headers="mb_list_auth" class="td_mbstat">
                        <?php
                        if ($leave_msg || $intercept_msg) echo $leave_msg.' '.$intercept_msg;
                        else echo "정상";
                        ?>
                        <?php echo get_member_level_select("mb_level[$i]", 1, $member['mb_level'], $row['mb_level']) ?>
                    </td>
                    <td style="text-align: center;" headers="mb_list_nick" class="td_name sv_use"><div><?php echo $mb_nick ?></div></td>

                    <td headers="mb_list_mobile" class="td_tel"><?php echo $row['mb_hp']; ?></td>
                    <td headers="mb_list_mobile" class="td_tel"><?php echo $row['mb_link_list']; ?></td>

                </tr>

                <?php
            }
            if ($i == 0)
                echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
            ?>
            </tbody>
        </table>
    </div>



</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>


<?php
include_once ('./admin.tail.php');
?>
