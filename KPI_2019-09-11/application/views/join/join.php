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
				<div class="sub_title"><img src="<?=$site_url?>images/icon_title.png">회원가입</div>
				<div class="join_text">
					<span>개인정보취급방침안내</span> </br>
                    회원가입약관 및 개인정보취급방침안내의 내용에 동의하셔야 회원가입을 하실 수 있습니다.
					<textarea readonly=""><?=$result['cf_stipulation']?></textarea>
					<p>회원가입약관 내용에 동의합니다. <input type="checkbox" class="cb_join1"></p>
				</div>
				<div class="join_text">
					<span>회원가입약관</span> </br>
                    회원가입약관 및 개인정보취급방침안내의 내용에 동의하셔야 회원가입을 하실 수 있습니다.
					<textarea readonly=""><?=$result['cf_privacy']?></textarea>
					<p>개인정보취급방침안내의 내용에 동의합니다. <input type="checkbox" class="cb_join2"></p>
				</div>
				<div class="join_btn linkImg"><img src="<?=$site_url?>images/btn/btn_join.png" onclick="onJoinClick()"></div>
			</div>
		</div>
	</div>
</div>
