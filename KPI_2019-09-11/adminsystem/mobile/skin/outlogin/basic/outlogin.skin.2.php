<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$outlogin_skin_url.'/style.css">', 0);
?>

<!-- 로그인 후 외부로그인 시작 -->
<aside id="ol_after" class="ol">
    <header id="ol_after_hd">
        <table width="100%">
            <tr>
                <td><strong><?php echo $nick ?>님</strong></td>
                <td rowspan="2"><footer id="ol_after_ft"><a href="<?php echo G5_BBS_URL ?>/logout.php" id="ol_after_logout">로그아웃</a></footer></td>
            </tr>
            <tr>
                <td >요금제 : <strong><?php echo $Crnt_ele_service_type ?></strong></td>
            </tr>               
            <?php if ($is_admin == 'super' || $is_auth) { ?>
            <tr>
                <td colspan="2"><a href="<?php echo G5_ADMIN_URL ?>" class="btn_admin">관리자 모드</a></td>
            </tr>            
            <?php } ?>
        </table>                
    </header>
    
</aside>

<script>
// 탈퇴의 경우 아래 코드를 연동하시면 됩니다.
function member_leave()
{
    if (confirm("정말 회원에서 탈퇴 하시겠습니까?"))
        location.href = "<?php echo G5_BBS_URL ?>/member_confirm.php?url=member_leave.php";
}
</script>
<!-- 로그인 후 외부로그인 끝 -->
