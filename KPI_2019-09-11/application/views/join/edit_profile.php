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
                <div class="sub_title"><img src="<?=$site_url?>images/icon_title.png">회원가입<span style="color:#ff0000;   margin-left: 100px;  font-size: 14px;"><label style="    font-size: 24px; padding: 0;
    margin: 0;
    display: inline-block;
    position: absolute;
    margin-left: -10px;
    margin-top: 10px;">*</label> 는 필수입니다.</span></div>
				<table class="serv_table_join">
                    <tr>
                        <td class="tb01">아이디<span style="color: #ff0000;font-size: 20px;position: absolute;padding-left: 15px;">*</span></td>
						<td class="tb02">
                            <input type="text" id="join_uid" class="form-control "value="<?=$join_uid?>" name="join_uid" placeholder="영문 또는 영문,숫자 조합으로 6자 이상으로 등록하십시요">
                        </td>
                        <td class="tb02_1"><button class="btn check_botton"  onclick="javascript:check_uid()">증복확인</button></td>

					</tr>

					<tr>
                        <td class="tb01">비밀번호<span style="color: #ff0000;font-size: 20px;position: absolute;padding-left: 10px;">*</span></td>
						<td class="tb02">
                            <!--<input type="password" tabindex="2" name="join_password" id="join_password" class="form-control join_put_password"
                                   onfocus="this.className='join_put_password'; checkEmail(); no_css();"
                                   onblur="this.className='join_put_password'; no_css2();" onkeyup="check_pwd()"
                                   required name="join_password" minlength="6" maxlength="20"  placeholder="비밀번호 6자리이상 (영문,숫자 조합)" style="width:420px;">-->
                            <input type="password" id="join_password" class="form-control" value="<?=$join_password?>"   name="join_password" placeholder="영문과 숫자, 특수문자를 포함하여 8자 이상으로 등록하십시요" >
                            <span id="msg_mb_nick"></span>
                        </td>
					</tr>
					<tr>
                        <td class="tb01">비밀번호 확인</td>
						<td class="tb02">
                            <input type="password" id="join_password_confirm" class="form-control" value="<?=$join_password_confirm?>"  name="join_password_confirm" >

                        </td>
					</tr>

				</table>
				<br />
				<table class="serv_table_join">
                    <tr>

                        <td class="tb01">기관명<span style="color: #ff0000;font-size: 20px;position: absolute;padding-left: 15px;">*</span></td>
                        <td class="tb02">
                            <input type="text"  id="join_company" class="form-control"value="<?=$join_company?>"  name="join_company"  >

                        </td>
                        <td class="tb02_1"></td>
                    </tr>
                    <tr>
                        <td class="tb01">담당자명<span style="color: #ff0000;font-size: 20px;position: absolute;padding-left: 10px;">*</span></td>
						<td class="tb02">
                            <input type="text"  id="join_name" class="form-control" value="<?=$join_name?>"  name="join_name" >
                            <span id="msg_mb_nick"></span>
                        </td>

					</tr>
					<tr>
                        <td class="tb01">전화번호</td>
						<td class="tb02"><input type="number"  id="join_phone" class="form-control" value="<?=$join_phone?>" e="join_phone" ></td>
					</tr>
					<tr>
                        <td class="tb01">휴대폰번호<span style="color: #ff0000;font-size: 20px;position: absolute;padding-left: 8px;">*</span></td>
						<td class="tb02"><input type="number"  id="join_mobile" class="form-control" value="<?=$join_mobile?>" name="join_mobile" style="    width: 35%;display: inline-block;">
                            <button class="btn btn-default" onclick="send_verify_code()" style="display: inline-block; margin-left: 10px;margin-top: -5px;margin-right:10px">인증코드 보내기</button>
                            <input type="number"  id="join_mobile_verify" class="form-control" value="<?=$mobile_verify?>" name="join_mobile_verify" style="    width: 35%;display: inline-block;" onkeyup="mobile_verify()">
                            <input id="is_mobile_verify" type="hidden" value="<?=$is_mobile_verify?>">
                            <span style="display: inline-block;height: 18px;">
                                <?php
                                    if($is_mobile_verify ==1) {
                                        ?>
                                        <span class="ok" style="display: block;"></span>
                                     <span class="no" style="display: none;"></span></span>
                                        <?php
                                    } else if($is_mobile_verify ==1) {
                                ?>
                                 <span class="ok" style="display: none;"></span>
                                <span class="no" style="display: block;"></span></span>
                            <?php
                            }else {
                            ?>
                                <span class="ok" style="display: none;"></span>
                                <span class="no" style="display: none;"></span></span>
                                <?php
                            }
                            ?>
                        </td>
					</tr>

					<tr>
                        <td class="tb01">팩스번호</td>
						<td class="tb02"><input type="text"   id="join_fax" class="form-control" value="<?=$join_fax?>" name="join_fax"></td>
					</tr>
					<tr>
                        <td class="tb01">e-mail<span style="color: #ff0000;font-size: 20px;position: absolute;padding-left: 15px;">*</span></td>
						<td class="tb02">

                            <input type="email" id="join_email" class="form-control" required name="join_email" value="<?=$join_email?>"></td>
					</tr>
					<tr id="captcha_area">
                       </tr>
				</table>
				<div class="join_btn">
					<button class="btn btn-default btn_submit" id="btn_submit" accesskey="s" onclick="onConfirmClick()">회원가입</button>
					<button class="btn btn-default" onclick="onCancelClick()">취소</button>
				</div>
                </form>
			</div>
		</div>
	</div>
</div>
