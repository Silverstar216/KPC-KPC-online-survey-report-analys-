<?php
$sub_menu = "300200";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

$token = get_token();

$mb = get_member($mb_id);
if (!$mb['mb_id'])
    alert('존재하지 않는 회원입니다.');

$g5['title'] = '접근가능그룹';
include_once('./admin.head.php');

$colspan = 4;
?>

<form name="fboardgroupmember_form" id="fboardgroupmember_form" action="./prgroupmember_update.php" onsubmit="return boardgroupmember_form_check(this)" method="post">
<input type="hidden" name="mb_no" value="<?php echo $mb['mb_no'] ?>" id="mb_no">
<input type="hidden" name="mb_id" value="<?php echo $mb['mb_id'] ?>" id="mb_id">
<input type="hidden" name="mb_nick" value="<?php echo $mb['mb_nick'] ?>" id="mb_nick">
<input type="hidden" name="token" value="<?php echo $token ?>" id="token">
<div class="local_cmd01 local_cmd">
    <p>아이디 <b><?php echo $mb['mb_id'] ?></b>, 이름 <b><?php echo $mb['mb_name'] ?></b>, 기관명 <b><?php echo $mb['mb_nick'] ?></b></p>
    <label for="gr_id">기관지정</label>
    <select name="gr_id" id="gr_id">
        <option value="">지역 기관을 선택하세요.</option>
        <?php
        $sql = " select *
                    from {$g5['member_table']}
                    where mb_level = 5 and mb_no not in (select elgm_mbid from ele_pr_group_member where elgm_sbid = '{$mb['mb_no']}') ";
        //if ($is_admin == 'group') {
        $sql .= " order by mb_nick ";
        $result = sql_query($sql);
        for ($i=0; $row=sql_fetch_array($result); $i++) {
            echo "<option value=\"".$row['mb_no']."\">".$row['mb_nick']."</option>";
        }
        ?>
    </select>
    <input type="submit" value="선택" class="btn_submit" accesskey="s">
</div>
</form>

<form name="fboardgroupmember" id="fboardgroupmember" action="./prgroupmember_update.php" onsubmit="return fboardgroupmember_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>" id="sst">
<input type="hidden" name="sod" value="<?php echo $sod ?>" id="sod">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>" id="sfl">
<input type="hidden" name="stx" value="<?php echo $stx ?>" id="stx">
<input type="hidden" name="page" value="<?php echo $page ?>" id="page">
<input type="hidden" name="token" value="<?php echo $token ?>" id="token">
<input type="hidden" name="mb_id" value="<?php echo $mb['mb_id'] ?>" id="mb_id">
<input type="hidden" name="w" value="d" id="w">

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">
            <label for="chkall" class="sound_only">지역기관 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th scope="col">관리기관</th>
        <th scope="col">비고</th>
        <th scope="col">사용여부</th>
        <th scope="col">처리일시</th>
    </tr>
    </thead>
    <tbody>
    <?php                 
    $sql = " select * from ele_pr_group_member a, {$g5['member_table']} b
                where a.elgm_sbid = '{$mb['mb_no']}'
                and a.elgm_mbid = b.mb_no ";
    $sql .= " order by b.mb_nick ";
    $result = sql_query($sql);
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $s_del = '<a href="javascript:post_delete(\'prgroupmember_update.php\', \''.$row['elgm_ukey'].'\');">삭제</a>';
    ?>
    <tr>
        <td class="td_chk">
            <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['mb_nick'] ?> 그룹</label>
            <input type="checkbox" name="chk[]" value="<?php echo $row['elgm_ukey'] ?>" id="chk_<?php echo $i ?>">
        </td>
        <td class="td_grid"><?php echo $row['mb_nick'] ?></td>
        <td class="td_category"><?php echo $row['elgm_bigo'] ?></td>
        <td class="td_category"><?php echo $row['elgm_sygb'] ?></td>
        <td class="td_datetime"><?php echo $row['elgm_time'] ?></td>
    </tr>
    <?php
    }
    if ($i == 0) {
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">접근가능한 그룹이 없습니다.</td></tr>';
    }
    ?>
    </tbody>
    </table>
</div>

<div class="btn_list01 btn_list">
    <input type="submit" name="" value="선택삭제">
</div>
</form>

<script>
function fboardgroupmember_submit(f)
{
    if (!is_checked("chk[]")) {
        alert("선택삭제 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    return true;
}

function boardgroupmember_form_check(f)
{
    if (f.gr_id.value == '') {
        alert('접근가능 그룹을 선택하세요.');
        return false;
    }

    return true;
}
</script>

<?php
include_once('./admin.tail.php');
?>
