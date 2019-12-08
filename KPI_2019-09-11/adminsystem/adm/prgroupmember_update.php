<?php
$sub_menu = "300200";
include_once('./_common.php');

if ($w == '')
{
    auth_check($auth[$sub_menu], 'w');

    $mb = get_member($mb_id);
    if (!$mb['mb_id']) {
        alert('존재하지 않는 회원입니다.');
    }

    $sql = " select count(*) as cnt
                from ele_pr_group_member
                where elgm_mbid = '{$gr_id}'
                and elgm_sbid = '{$mb_no}' ";
    $row = sql_fetch($sql);
    if ($row['cnt']) {
        alert('이미 등록되어 있는 자료입니다.');
    }
    else
    {
        check_token();

        $sql = " insert into ele_pr_group_member
                    set elgm_mbid = '{$_POST['gr_id']}',
                         elgm_sbid = '{$_POST['mb_no']}',
                         elgm_sygb = 'Y',
                         elgm_bigo = (select mb_nick from {$g5['member_table']} where mb_no = '{$_POST['gr_id']}'),
                         elgm_time = '".G5_TIME_YMDHIS."' ";
        sql_query($sql);
    }
}
else if ($w == 'd' || $w == 'ld')
{
    auth_check($auth[$sub_menu], 'd');

    $count = count($_POST['chk']);
    if(!$count)
        alert('삭제할 목록을 하나이상 선택해 주세요.');

    check_token();

    for($i=0; $i<$count; $i++) {
        $gm_id = $_POST['chk'][$i];
        $sql = " select * from ele_pr_group_member where elgm_ukey = '$gm_id' ";
        $gm = sql_fetch($sql);
        if (!$gm['elgm_ukey']) {
            if($count == 1)
                alert('존재하지 않는 자료입니다.');
            else
                continue;
        }
        $sql = " delete from ele_pr_group_member where elgm_ukey = '$gm_id' ";
        sql_query($sql);
    }
}
if ($w == 'd' || $w == 'ld')
    goto_url('./member_list.php');
else
    goto_url('./pr_groupmember_form.php?mb_id='.$mb_id);
?>
