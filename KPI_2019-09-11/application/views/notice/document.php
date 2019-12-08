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
                            <div class="sms02">
                            </div>
                            <div class="attached-<?=$attached?>-container" style="padding: 10px; width: 100%;">
                                <div id="file_container" class="row" style="border: solid 1px #ccc;padding:5px 0;background-color: rgb(239, 239, 239);">
                                    <div style="float: left; width: 15%; ">
                                        <span class="sms_span">포함문서<span>
                                    </div>
                                    <div id="pick_file_area" style="float: left; width: 60%; margin-left: 5px;">
                                        <a id="pick_file" class="btn green" style="display: inline-block; width: 20%;">파일선택</a>
                                        <div id="uploader_filelist" class="dropzone-file-area">선택된 파일이 없음</div>
                                    </div>
                                    <div style="float: right; width: 20%; margin-right: 5px;">
                                        <button id="import" src="<?=$site_url?>images/btn/btn_att01.png" disabled class="btn btn-default " onclick="onImportClick()">포함하기</button>
                                        <button id="preview" src="<?=$site_url?>images/btn/btn_att02.png" disabled class="btn btn-default " onclick="onShowClick()">미리보기</button>
                                    </div>
                                </div>
                                <input id="attached_file_name" type="hidden" value="">
                                <input id="attached_origin_file_name" type="hidden" value="">
                                <input id="attached_check" type="hidden" value="0">
                                <input id="attached_file_url" type="hidden" value="">
                            </div>
                            <div style="background: #eeeeee; display: inline-block; border: 1px solid #a1a1a1; margin-top: 10px; padding: 0px 60px;">
                                <p style="margin: 10px 0;">이미 작성된 문서에서  복사하기 ->붙히기로 가져오면,
                                    문서내부의 숨은코드로 인해 발송이 안되는 경우가 있습니다.
                                    이럴때는 입력창에 직접 입력하십시요.  </p>
                        	</div>             
                            <?php
                            $this->load->view('notice/sms', $this->data);
                            ?>
                            <div style="    display: inline-block;    width: 100%;">
                                <button style="width: 100px;" class="btn btn-warning" onclick="onSendNoticeClick()">보내기</button>                                
                            </div>
                        </div>
                    </div>
               </div>                        
            </div>
		</div>
	</div>
</div>
