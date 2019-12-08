<?php
$sub_menu = '300800';
include_once('./_common.php');
auth_check($auth[$sub_menu], "w");

$html_title = 'SMS 예문';
$g5['title'] = 'SMS 예문 관리';

if ($w == "u")
{
    $html_title .= ' 수정';
    $readonly = ' readonly';

    $sql = " select * from sample_sms_info where si_ukey = '$fm_id' ";
    $fm = sql_fetch($sql);
    if (!$fm['si_msg']) alert('등록된 자료가 없습니다.');
}
else
{
    $html_title .= ' 입력';
}

include_once (G5_ADMIN_PATH.'/admin.head.php');
?>

<form name="frmsmssampleform" action="./sms_sample_update.php" onsubmit="return frmsmssampleform_check(this);" method="post" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="fm_id" value="<?php echo $fm_id; ?>">

<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?></caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="si_reply">회신번호</label></th>
        <td>
            <input type="text" name="si_reply" value="<?php echo $fm['si_reply']; ?>" id="si_reply" required class="frm_input required" maxlength="15" size="15">
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="si_msg">메세지 내용</label></th>
        <td>
            <input type="text" value="<?php echo get_text($fm['si_msg']); ?>" name="si_msg" id="si_msg" required class="frm_input required"  size="90">
        </td>
    </tr>   
    </tbody>
    </table>
</div>

<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="확인" class="btn_submit" accesskey="s">
    <a href="./sms_sample_list.php">목록</a>
</div>

</form>
<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
