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
                <div class="sub_title"><img src="<?=$site_url?>images/icon_title.png">비밀번호변경</div>
				<table class="serv_table_join">
                    <tr>
                        <td class="tb01">아이디<span style="color: #ff0000;font-size: 20px;position: absolute;padding-left: 15px;">*</span></td>
						<td class="tb02">
                            <input type="text" id="join_uid" class="form-control readonly "value="<?=$join_uid?>" name="join_uid">
                        </td>

					</tr>
                    <tr>
                        <td class="tb01">현재 비밀번호<span style="color: #ff0000;font-size: 20px;position: absolute;padding-left: 10px;">*</span></td>
                        <td class="tb02">
                            <input type="password" id="join_password" class="form-control" value="<?=$join_password?>"   name="join_password"  >
                            <span id="msg_mb_nick"></span>
                        </td>

                    </tr>
					<tr>
                        <td class="tb01">새 비밀번호<span style="color: #ff0000;font-size: 20px;position: absolute;padding-left: 10px;">*</span></td>
						<td class="tb02">
                            <!--<input type="password" tabindex="2" name="join_password" id="join_password" class="form-control join_put_password"
                                   onfocus="this.className='join_put_password'; checkEmail(); no_css();"
                                   onblur="this.className='join_put_password'; no_css2();" onkeyup="check_pwd()"
                                   required name="join_password" minlength="6" maxlength="20"  placeholder="비밀번호 6자리이상 (영문,숫자 조합)" style="width:420px;">-->
                            <input type="password" id="new_join_password" class="form-control" value="<?=$new_join_password?>"   name="join_password" placeholder="영문과 숫자, 특수문자를 포함하여 8자 이상으로 등록하십시요" >
                            <span id="msg_mb_nick"></span>
                        </td>
					</tr>
					<tr>
                        <td class="tb01">새 비밀번호 확인</td>
						<td class="tb02">
                            <input type="password" id="new_join_password_confirm" class="form-control" value="<?=$new_join_password_confirm?>"  name="join_password_confirm" >

                        </td>
					</tr>
                    <tr id="captcha_area">
                    </tr>
				</table>
				<br />


				<div class="join_btn">
					<button class="btn btn-default btn_submit" id="btn_submit" accesskey="s" onclick="onPassChangeClick()">수정</button>
					<button class="btn btn-default" onclick="onCancelClick()">취소</button>
				</div>
                </form>
			</div>
		</div>
	</div>
</div>
