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
						<div class="m7con sub_con">			
							<div class="file">
								<div class="file_left" style="text-align: left;">
									<span class="file_title">파일 업로드</span><br>
									※ 휴대폰번호에 하이픈(-)은 포함되어도 되고 포함되지 않아도 됩니다.<br>
									같은 데이터가 두개 이상인 경우 마지막 데이터를 사용합니다.<br>
									<span class="file_red">엑셀파일은 xls 확장자로 저장하여 업로드 하십시오.<br>
									※ 주의) 암호가 걸려있는 파일은 처리되지 않습니다!!!(암호 해제후 업로드)</span><br>
									<div class="file_gr">
										<li>그룹선택 : 
											<select id="groups" name="groups" style="    font-size: 13px;padding: 2px 0px;">
												<option value="all" <?php echo get_selected('all', $ngst); ?>>전체</option>
												<?php foreach ($groups as $item):?>
													<option value="<?=$item['id'];?>" <?php echo get_selected($item['id'], $ngst); ?>><?=$item['name']?></option>
												<?php endforeach;?>
											</select>
											<a href="<?=$site_url?>phone"><img src="<?=$site_url?>images/btn/btn_group001.png"></a>
										</li>										
									</div>
									<div class="attached-<?=$attached?>-container" style="margin-top: 20px; margin-right: 10px">
											<div id="file_container"  style="border: solid 1px #ccc;padding:5px 0;background-color: rgb(239, 239, 239);">
												<div style="width:70px;display: inline-block">
												<!-- <span class="sms_span">포함할 문서<span> -->
												</div>
												<div id="pick_file_area_phone" style="width:300px; display: inline-block; ">
													<a id="pick_file" class="btn green" style="display: inline-block; font-size: 12px; padding: 5px;">파일선택</a>
													<div id="uploader_filelist" class="dropzone-file-area" style="width:220px">선택된 파일이 없음</div>
												</div>
												<div style="display: inline-block;  padding-top: 3px;">
													<button id="save" disabled class="btn blue" onclick="post()"><i class="fas fa-arrow-up"></i> 업로드</button>
												</div>
											</div>
										<input id="attached_file_name" type="hidden" value="">
										<input id="attached_origin_file_name" type="hidden" value="">
									</div>
								</div>
								<div class="file_right"><img src="<?=$site_url?>images/img/img_ex.png"></div>
								</div>
								<div class="sub_img">
									<img src="<?=$site_url?>images/bg/block.png">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

