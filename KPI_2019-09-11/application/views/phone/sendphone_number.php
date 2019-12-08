<?php
/**
 * Created by PhpStorm.
 * User: CHKD
 * Date: 10/9/2018
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
						<div class="m7con sub_con" style="padding-right: 0px; padding-left: 0px;">			
						<!--    발신전화번호관리-->
							<div class="sec04" style="width: 100%; margin-left: 0px;">								
						<!--        설명문부분-->
								<div class = "comment_area">
									<a>
										발신번호 등록후 번호이동 등으로 등록된 번호를 사용하지 않는 경우에는 반드시 삭제해주시기 바랍니다.<br>
										"번호도용문자차단서비스"에 가입된 번호는 발신번호를 등록했더라도 발송이 되지 않습니다.<br>
									</a>
								</div>
								<div class = "phone_register_title">발신번호(보내는번호)등록
								</div>
								<div class="div-phone-verify" style="display: block">
									<div class = "phone_item1" style = "margin-top:5px;">
										<a >발신번호(보내는 번호): </a>
										<input type = "text" style = "width:20%" class="phoneNumberPart1">
										<lable style="    margin-left: 20px;">설명: </lable>
										<input type = "text" style = "width:20%" class="phonetextComment">
									<!-- <input type = "text" style = "width:20%" id="txtComment">-->
										<button class="btn-example-remove btn btn-danger btn-sm"><i class="fas fa-times"></i></button>
										<button class="btn-example-plus btn btn-success btn-sm"><i class="fas fa-plus"></i></button>
									</div>

								</div>
								<div class="div-phone-verify_1" style="display: block">
									<div class = "item2">
										<a style="color:#FF0000">인증받을 이동전화: </a>
										<input type = "text" style = "width:20%" id="txtSendingMobile">
										<button type="button" onclick="onRegisterSenderPhone()" id = "btnSendingVerifyCode" style="margin-left: 20px;">코드받기</button>
										<input type = "text" style = "width:18%" id="txtVerifyCode">
										<button type="button" onclick="onSaveSenderPhone()" id = "btnAddSenderPhone" style="margin-left: 20px;">등록</button>
									</div>
								</div>
								<table class="sendphone-header-table">
									<thead>
									<th style="width: 8%">대 표</th>
									<th colspan = "2" style="width: 25%">설 명</th>
									<th colspan = "2" style="width: 27%">발신번호(보내는 번호)</th>
									<th style="width: 20%">인증일시</th>
									<th style="width: 20%">인증번호</th>
									</thead>
								</table>
								<div class="div-phone-body">
									<table class="sendphone-body-table">
										<tbody>
											<?php
											foreach($senderPhoneList as $senderPhone):
												?>
												<tr>
													<td style="width: 8%"><img src="<?=$site_url?>include/img/star-off-big.png" alt=" " class="img-responsive zoom-img" style = "margin-left:12px;"></td>
													<td style="width: 15%"><input type = "text" value = "<?=$senderPhone['memo'] == null ?'(이름없음)':$senderPhone['memo'] ?>" style = "width:100%;border:none;text-align: center" id="senderPhoneMemo<?=$senderPhone['id']?>"></td>
													<td style="width: 10%"><button onclick="onSaveSenderPhoneMemo(<?=$senderPhone['id']?>)">저장</button></td>
													<td class="phoneNumber" style="width: 17%"><?=$senderPhone['phone']?></td>
													<td style="width: 10%"><button onclick="onRemoveSenderPhone(<?=$senderPhone['id']?>)">삭제</button></td>

													<td style="width: 20%"><?=$senderPhone['request_date']?></td>
													<td style="width: 20%"><?=$senderPhone['request_phone']?></td>
												</tr>
											<?php endforeach;?>
										</tbody>
									</table>
								</div>
								<input type = "hidden" id="auKind" value="0">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

