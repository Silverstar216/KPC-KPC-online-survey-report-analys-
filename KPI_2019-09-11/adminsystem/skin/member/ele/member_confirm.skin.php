<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/style.css">', 0);
?>

   <div class="subTopTab">
   	<ul class="item">
       <li><a href="#" title="페이지 이동" class="active"><span>회원정보수정</span></a></li>
    </ul>
   </div>

   <div class="titlegroup">
       <em>회원정보수정</em>      
		 <div class="navgroup">		
				 <p>Home <span class="rt">&gt;</span> 마이페이지 <span class="rt">&gt;</span> 회원정보수정</p>
		 </div>     
   </div>

<!-- 회원수정

-->
<div class="phonegroup">
<div class="phonegroupin">
<div class="phonegroupwrap">
    
<!--
    <h1>회원 비밀번호 확인</h1>
    <p><strong>비밀번호를 한번 더 입력해주세요.</strong></p>
    <p>회원님의 정보를 안전하게 보호하기 위해 비밀번호를 한번 더 확인합니다.</p>
-->
<!--
    <div id="mb_confirm" class="mbskin">
-->
    <div id="mb_confirm" class="mbskin" style=padding:0px >
    <a href="<?php echo G5_URL ?>/"><img src="<?php echo G5_IMG_URL ?>/bookimg.png" alt="<?php echo $config['cf_title']; ?>"></a>    
    <h1><?php echo $g5['title'] ?></h1>
    <p>
        <strong>비밀번호를 한번 더 입력해주세요.</strong>
        <?php if ($url == 'member_leave.php') { ?>
        비밀번호를 입력하시면 회원탈퇴가 완료됩니다.
        <?php }else{ ?>
        회원님의 정보를 안전하게 보호하기 위해 비밀번호를 한번 더 확인합니다.
        <?php }  ?>
    </p>

    <form name="fmemberconfirm" action="<?php echo $url ?>" onsubmit="return fmemberconfirm_submit(this);" method="post">
    <input type="hidden" name="mb_id" value="<?php echo $member['mb_id'] ?>">
    <input type="hidden" name="w" value="u">
     <fieldset>
        회원아이디
        <span id="mb_confirm_id">&nbsp;<?php echo $member['mb_id'] ?></span>
        <label for="confirm_mb_password">비밀번호<strong class="sound_only">필수</strong></label>
        <input type="password" name="mb_password" id="confirm_mb_password" required class="required frm_input" size="15" maxLength="20">
        <input type="submit" value="확인" id="btn_submit" class="btnW1">
     </fieldset>
    </form>

    <div class="btn_confirm">
        <a href="<?php echo G5_URL ?>">메인으로</a>
    </div>

<!--
    <div class="btn_confirm">
       <input type="button" value="메인으로"  class="btnT1">
    </div>
-->
  </div>
</div>
</div>
</div>

<!-- //회원수정 -->


<!-- 회원 비밀번호 확인 시작  
-->

<script>
function fmemberconfirm_submit(f)
{
    document.getElementById("btn_submit").disabled = true;

    return true;
}
</script>

<!-- } 회원 비밀번호 확인 끝 -->

