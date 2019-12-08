<?php
/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 2/29/2016
 * Time: 9:47 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
$site_url = site_url();

?>


<div class="container container-bg">
	<div id="content">
		<div id="contents">
			<div class="sub_con">
                <div class="sub_title"><img src="<?=$site_url?>images/icon_title.png">회원정보 찾기 안내</div>

               <p style="margin:20px 0 0;padding: 30px 70px;border-bottom: 1px solid #eee;line-height: 2em; font-size: 20px;">
                    <span style="color:#ff3061"><strong> <?=$mb_name?></strong></span> 회원님은 <?=$mb_created?> 에 회원정보 찾기 요청을 하셨습니다.<br>
                   저희 사이트는 관리자라도 회원님의 비밀번호를 알 수 없기 때문에, 비밀번호를 알려드리는 대신 새로운 비밀번호를 생성하여 안내 해드리고 있습니다.<br>
                   아래에서 비밀번호변경단추를 누르시고 홈페이지에서 회원아이디와 변경된 비밀번호를 입력하시고 로그인 하십시오.<br>
                   로그인 후에는 회원정보수정 메뉴에서 새로운 비밀번호로 변경해 주십시오.
                   </p>
               <p style="margin:0;    padding: 30px 70px; border-bottom: 1px solid #eee; line-height: 2em; font-size: 24px;
    text-align: center;">
                   <span style="display:inline-block;width:200px">회원아이디</span> <?=$mb_id?><br>
                   <span style="display:inline-block;width:200px">변경될 비밀번호</span> <strong style="color:#ff3061"><?=$mb_pass?></strong>
                   </p>


				<div class="join_btn">
					<button style="font-size: 18px;" class="btn btn-default btn_submit" id="btn_submit" accesskey="s" onclick="onLost_PassChangeClick('<?=$mb_id?>','<?=$mb_pass?>')">비밀번호 변경</button>

				</div>
                </form>
			</div>
		</div>
	</div>
</div>
