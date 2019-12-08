<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
?>
<!-- 로그인 전 아웃로그인 시작 { -->
<form name="foutlogin" action="<?php echo $outlogin_action_url ?>" onsubmit="return fhead_submit(this);" method="post" autocomplete="off">
    <input type="hidden" name="url" value="<?php echo $outlogin_url ?>">
    <ul class="m1">
      <li class="id">아이디 <input type="text" id="ol_id" name="mb_id" required class="required" maxlength="20" tabindex=1></li>
      <li>비밀번호 <input type="password" name="mb_password" id="ol_pw" required class="required" maxlength="20" tabindex=2></li>
    </ul>
    <p class="m2"><input type="submit" id="ol_submit" value="로그인"  class="loginbtn"></p> 
    <ul class="m3">
    <li><a href="<?php echo G5_BBS_URL ?>/register.php" id = "ol_regist" class="gayipbtn">회원가입</a></li>
    <li><a href="<?php echo G5_BBS_URL ?>/password_lost.php" id="ol_password_lost" class="findbtn">ID/PW찾기</a></li>
    </ul>
</form>
<script>
function fhead_submit(f)
{
    return true;
}
</script>
<!-- } 로그인 전 아웃로그인 끝 -->