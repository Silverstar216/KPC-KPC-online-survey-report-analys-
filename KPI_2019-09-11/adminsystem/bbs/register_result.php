<?php
include_once('./_common.php');

if (isset($_SESSION['ss_mb_reg']))
    $mb = get_member($_SESSION['ss_mb_reg']);

// 회원정보가 없다면 초기 페이지로 이동
if (!$mb['mb_id'])
    goto_url(G5_URL);

$g5['title'] = '회원가입이 완료되었습니다.';
$pgMNo = 9;
include_once('./_head.php');
?>
	<div class="snb">
      <div class="loginbox"> 
          <em>회원가입 완료</em>
             <div class="bca">
				<div class="breadCrumbs scBasic">
				  <p>홈<span class="rt">&gt;</span> 회원가입 완료</p>
				</div>
		   </div>
<?php
include_once($member_skin_path.'/register_result.skin.php');
?>
</div> <!-- // loginbox -->
</div> <!-- ."snb" --> 
<?php
include_once('./_tail.php');
?>