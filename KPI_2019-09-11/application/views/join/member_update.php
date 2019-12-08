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
			<div class="m_con">
                <?php
                // $this->load->view('index/menu_main', $this->data);
                ?>               
                <div class="content listWrap" style = "float:right; width: 100%;">
                    <div class="contentwrap">
                        <div class="titlem1">
                            <em><?=$this->data['submenu']?></em>
                            <div class="navgroup">
                                <?php
                                $table_name = $this->data['submenu'];
                                ?>
                                <p>Home <span class="rt">&gt;</span><?=$this->data['menu']?><span class="rt">&gt;</span><font color="red"><?=$table_name?></font></p>
                            </div>
						</div>
						<div class="m7con sub_con">					
                            <div class="sub_title"></div>
                            <table class="serv_table_join">
                                <tr>
                                    <td class="tb01">아이디<span style="color: #ff0000;font-size: 20px;position: absolute;padding-left: 15px;">*</span></td>
                                    <td class="tb02">
                                        <input type="text" id="join_uid" class="form-control readonly "value="<?=$join_uid?>" name="join_uid">
                                    </td>

                                </tr>
                                <tr>
                                    <td class="tb01">비밀번호<span style="color: #ff0000;font-size: 20px;position: absolute;padding-left: 10px;">*</span></td>
                                    <td class="tb02">
                                        <input type="password" id="join_password" class="form-control" value="<?=$join_password?>"   name="join_password">
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
                                                } else if($is_mobile_verify !=1) {
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
                                <button class="btn btn-default btn_submit" id="btn_submit" accesskey="s" onclick="onUpdateClick()">수정</button>
                                <button class="btn btn-default" onclick="onCancelClick()">취소</button>
                            </div>
                        </div>
                    </div>
                </div>                            
			</div>
		</div>
	</div>
</div>
