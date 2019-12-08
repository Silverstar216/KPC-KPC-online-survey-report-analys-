<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$outlogin_skin_url.'/style.css">', 0);
?>
<!-- 로그인 후 아웃로그인 시작 { -->
<table width="220" border="0" cellspacing="0" cellpadding="0">
<tr>
    <td style="letter-spacing:-1;text-align:center;vertical-align:middle;"><strong><?=$nick?></strong></td>
    <td rowspan="2" style="padding:0 10px;width:60px;text-align:right;">   
        <a href="<?php echo G5_BBS_URL ?>/logout.php"><img src="<?=$outlogin_skin_url?>/img/logout_button.gif"></a>
    </td>                
</tr>                                                                                                           
<tr>
<?php if ($is_admin == 'super' || $is_auth) {  ?>
    <td style="text-align:center;">&nbsp;<a href="<?php echo G5_ADMIN_URL ?>" class="btn_ol_admin">&nbsp;&nbsp;관리자 모드&nbsp;&nbsp;</a>&nbsp;</td>

<?php } else if ($member['mb_level'] == 5){
?>

    <td style="text-align:center;">&nbsp;<a href="<?php echo G5_URL ?>/government/gov.php" class="btn_ol_admin">&nbsp;&nbsp;홍 보 관 리&nbsp;&nbsp;</a>&nbsp;</td>
<?php
} else {
?>         
    <td></td>      
<?php
} 
?>                 
</tr>
</table>
<script>
// 탈퇴의 경우 아래 코드를 연동하시면 됩니다.
function member_leave()
{
    if (confirm("정말 회원에서 탈퇴 하시겠습니까?"))
        location.href = "<?php echo G5_BBS_URL ?>/member_confirm.php?url=member_leave.php";
}
</script>
<!-- } 로그인 후 아웃로그인 끝 -->