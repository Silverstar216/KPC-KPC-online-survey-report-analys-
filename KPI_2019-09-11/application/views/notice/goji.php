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
<!--------------- 기본 구역 -------------------->
<div class="container container-bg" id = "documentArea">
	<div id="content">
		<div id="contents" style = "padding:0">
			<div class="sub_con">
				<div class="sub_title1"><img src="<?=$site_url?>images/icon_title.png">개별고지</div>

				<div class="sub_img">
					<img src="<?=$site_url?>images/img_goji.png">
				</div>
<!--            고지내용입력구간-->
                <div class="attached-content-container" style="padding: 10px 30px 10px 30px ; width: 100%;">
                    <div id="file_container" class="row" style="padding:5px 0;background-color: rgb(239, 239, 239);">
                        <div style="width:100px;display: inline-block;text-align: right">
                            <span class="sms_span">내용입력<span>
                        </div>
                        <a id="show_gojiList" class="btn green" style="display: inline-block; margin-left:20px;href="#">양식목록</a>
                        <input type = "text" style = "width: 630px; height:32px;font-size:15px" class= "goji_content_editor" oninput="onGojiBytesLength()">
                        <a style="font-size:16px;margin-left:20px;" class= "goji_content_length">0</a>
                        <a style="font-size:16px;">/70 byte</a>
                    </div>
                </div>
<!--            고지문서선택구간-->
                <div class="attached-gojiDoc-container" style="padding: 10px 30px 10px 30px ; width: 100%">
                    <div id="gojidoc_file_container" class="row" style="padding:5px 0;background-color: rgb(239, 239, 239);">
                        <div style="width:100px;display: inline-block">
                            <span class="sms_span">개별고지문서<span>
                        </div>
                        <div id="gojidoc_pick_file_area" style="width:640px; display: inline-block; margin-left: 20px;">
                            <a id="gojidoc_pick_file" class="btn green" style="display: inline-block">파일선택</a>

                            <div id="gojiDoc_uploader_filelist" class="dropzone-file-area">선택된 파일이 없음</div>
                        </div>
                        <div style="width: 180px;display: inline-block; margin-left: 20px;float: right;    padding-top: 3px;">
                            <button id="btnGojiDocConvert" src="<?=$site_url?>images/btn/btn_att01.png" disabled class="btn btn-default " onclick="onGojiConvertClick()">변환하기</button>
                            <button id="btnGojiDocPreview" src="<?=$site_url?>images/btn/btn_att02.png" disabled class="btn btn-default " onclick="onGojiShowClick()">미리보기</button>
                        </div>
                    </div>
                    <input id="gojidoc_uploaded_filename" type="hidden" value="">
                    <input id="gojidoc_original_filename" type="hidden" value="">
                    <input id="gojidoc_converted_check" type="hidden" value="0">
                    <input id="gojidoc_converted_file_url" type="hidden" value="">

                </div>
<!--            고지변수 xls문서선택구간-->
                <div class="attached-gojiVar-container" style="padding: 10px 30px 10px 30px ; width: 100%;display: none">
                    <div style = "font-size:15px;margin-bottom:10px">
                        <input type="radio" name ="optCheckPhone" id ="PhoneNumberCheck" checked><label for="PhoneNumberCheck" style = "margin-left:5px;margin-right:10px">번호확인</label>
                        <input type="radio" name ="optCheckPhone" id ="NoPhoneNumberCheck"><label for="NoPhoneNumberCheck" style = "margin-left:5px;margin-right:10px">확인안함</label>
                        <a>(개인을 특정할 수 있는 정보가 포함된 경우 수신자만 조회할 수 있도록 번호확인을 체크해주십시오!)</a>
                    </div>
                    <div id="gojivar_file_container" class="row" style="padding:5px 0;background-color: rgb(239, 239, 239);">
                        <div style="width:100px;display: inline-block">
                            <span class="sms_span">개별자료문서<span>
                        </div>
                        <div id="gojivar_pick_file_area" style="width:640px; display: inline-block; margin-left: 20px;">
                            <a id="gojivar_pick_file" class="btn green" style="display: inline-block">파일선택</a>

                            <div id="gojiVar_uploader_filelist" class="dropzone-file-area">선택된 파일이 없음</div>
                        </div>
                        <div style="width: 180px;display: inline-block; margin-left: 20px;float: right;    padding-top: 3px;">
                            <button id="btnGojiVarUpload" src="<?=$site_url?>images/btn/btn_att01.png" disabled class="btn btn-default " onclick="onUploadGojiVarClick()">올리기</button>
                            <button id="btnGojiVarPreview" src="<?=$site_url?>images/btn/btn_att02.png" disabled class="btn btn-default " onclick="onGojiShowClick()">미리보기</button>
                        </div>
                    </div>

                    <input id="gojivar_uploaded_filename" type="hidden" value="">
                    <input id="gojivar_original_filename" type="hidden" value="">
                    <input id="gojivar_uploaded_check" type="hidden" value="0">
                    <!--                    <input id="gojivar_uploaded_file_url" type="hidden" value="">-->

                </div>
<!--            고지변수 등록구간-->
                <div class="attached-gojiVar-registry-container" style="padding: 10px 30px 10px 30px ; width: 100%;display: none">
                    <div style = "font-size:15px;margin-bottom:10px;text-align: center;" id = "gojivar_check">
                    </div>
                    <div style = "text-align: center;padding:20px 0 30px 0">
                        <button onclick="onRegistryGojiVar();" class="btn btn-primary" style="padding: 10px 20px 10px 28px; letter-spacing: 8px;width: 130px;">등 록</button>
                    </div>
                    <input id="gojivar_registry_check" type="hidden" value="0">
                </div>
<!--            등록된 고지변수 미리보기구간-->

                <div class="attached-gojiVar-preview-container" style="padding: 10px 30px 10px 30px ; width: 100%;display: none;text-align:center">
                    <div style="font-size:15px; display: inline-block" id="registry_result">
                    </div>
                    <button onclick="onPreviewGojiVar();" class="btn btn-primary" style="width: 100px;height: 35px;margin-left: 20px;background:#a932d2">
                        미리보기
                    </button>
                </div>

                <div style = "text-align: center;padding:20px 0 30px 0" id = "btnNextArea">
                    <button onclick="NextStep();" class="btn btn-primary" style="padding: 10px 20px 10px 28px; letter-spacing: 8px;width: 130px;">다 음</button>
                </div>
			</div>
		</div>
	</div>
</div>
<!--------------- 고지양식목록보기 대화창 -------------------->
<div class="modal fade draggable-modal" id="questions_modal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #58b5fb;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title" style="color: #fff;">개별고지양식목록</h4>
            </div>
            <div class="modal-body" style="font-size: 14px;">
                <div class="row">
                    <div>
                        <div class="search">
                            <ul>
                                <li>구분 :
                                    <select id="goji_type" name="groups" style="width:125px;">
                                        <option value="all">전체</option>
                                        <option value="common">개별</option>
                                        <option value="edu">에듀파인</option>
                                    </select>
                                </li>
                                <li><input type="text" style = "width:120px;margin-left:10px" id="docName"></li>
                                <li style="cursor:pointer;margin-left:10px" onclick="javascript:SearchBtnClick();"><img src="<?=$site_url . 'images/btn/btn_search.png'?>"></li>
                            </ul>
                        </div>
                        <div class="serv_t" style="display: inline-block; float: right; margin-right: 30px;  margin-left: 0;">
                            총 갯수 0 개
                        </div>
                    </div>
                </div>
                <div class="row" id="goji_list">

                </div>

                <div class="blog-pagination" style = "margin-top:0">

                </div>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn blue btn-outline" data-dismiss="modal" onclick="close()" >취소</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!--------------- 고지문서 미리보기구역 ------------------->
<div class="container container-bg" id = "preview_area" style="display: none;">
    <div id="content">
        <div style="text-align: center;display:inline-block;width:100%;margin-top:15px;margin-bottom: 10px;">
            <div style = "width:80%;float:left;">
                <h4 style = "margin-left:230px;">개별고지문서 미리보기</h4>
            </div>
            <ul>
                <li style = "float:left;padding-right:10px"><button type="button" class="btn btn-outline" onclick="onShowDocumentArea()" style = "float:right;">돌아가기</button></li>
            </ul>
        </div>
        <div style = "width:100%;height: 1px;background: #b3b2b2;"></div>
        <div id="attachedHTMLDialog">
            <div id="attached_content" style="height:600px;">
            </div>
        </div>
    </div>
</div>

<!-------------- 고지변수 미리보기구역 --------------------->
<div class="container container-bg" id = "gojivar_preview_area" style="display: none;">
    <div id="content">
        <div style="text-align: center;display:inline-block;width:100%;margin-top:15px;margin-bottom: 10px;">
            <div style = "width:80%;float:left;">
                <h4 style = "margin-left:230px;">데이터내역</h4>
            </div>
            <ul>
                <li style = "float:left;padding-right:10px"><button type="button" class="btn btn-outline" onclick="onShowDocumentArea()" style = "float:right;">돌아가기</button></li>
            </ul>
        </div>
        <div style = "width:100%;height: 1px;background: #b3b2b2;"></div>
        <div id="gojivar_content">
        </div>
    </div>
</div>