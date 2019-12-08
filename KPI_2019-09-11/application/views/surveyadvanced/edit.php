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
<input id="survey_attached" type="hidden" value="<?=$attached?>">
<input id="survey_attachedHTML" type="hidden" value="">
<input id="survey_id" type="hidden" value="<?=$survey_id?>">
<input id="newflag" type="hidden" value="<?=$newflag?>">
<div class="container container-bg" id = "documentArea">

    <div id="content">
        <div id="contents">
            <div class="sub-content">
<!--                <div class="sub-title"><img src="--><?//=$site_url?><!--images/icon_title.png">--><?//=$subtitle?><!--</div>-->
                <div class="sub-title"><img src="<?=$site_url?>images/icon_title.png">설문조사</div>
                <div class="sub-img-area">
                    <?php if($attached===0) {
                        ?>
                        <img src="<?=$site_url?>images/survey/img_serv01.png">
                    <?php
                    } else {
                        ?>
                        <img src="<?=$site_url?>images/survey/img_serv02.png" >
                    <?php
                    }
                    ?>
                </div>
                <div class="line">
                </div>
        <!------------------ 1. 포함문서선택구간 -------------------->
                <div class="attached-<?=$attached?>-container" style="padding: 30px; width: 100%;">
                    <div id="file_container" class="row" style="border: solid 1px #ccc;padding:5px 0;background-color: rgb(239, 239, 239);">
                        <div style="width:100px;display: inline-block">
                            <span class="sms_span">포함할 문서</span>
                        </div>
                        <div id="pick_file_area" style="width:540px; display: inline-block; margin-left: 20px;">
                            <a id="pick_file" class="btn green" style="display: inline-block">파일선택</a>

                            <div id="uploader_filelist" class="dropzone-file-area" style="width:450px;">선택된 파일이 없음</div>
                        </div>
                        <div style="width: 180px;display: inline-block; margin-left: 20px;float: right;    padding-top: 3px;">


                            <button id="import" src="<?=$site_url?>images/btn/btn_att01.png" disabled class="btn btn-default " onclick="onImportClick()">포함하기</button>
                            <button id="preview" src="<?=$site_url?>images/btn/btn_att02.png" disabled class="btn btn-default " onclick="onShowClick()">미리보기</button>
                        </div>
                    </div>
                    <input id="attached_file_name" type="hidden" value="">
                    <input id="attached_check" type="hidden" value="0">
                    <input id="attached_origin_file_name" type="hidden" value="">
                </div>

        <!------------------ 2. 기본정보등록구간 -------------------->
                <div class="survey-step-title"><span>Step1</span> 기본정보등록</div>
                <table class="servey-table">
                    <tr>
                        <td class="tb01">제목</td>
                        <td class="tb02"><input type="text" class="form-control" id="survey_title"></td>
                    </tr>
                    <tr>
                        <td class="tb01">설문기간</td>
                        <td class="tb02">
                            <div class="form-inline">
                                <input type="text" class="form-control input-inline input-sm" id="survey_start_date" value="<?=date('Y-m-d H:i')?>">

                                <span style="margin-left:10px;margin-right:10px;">  ~~ </span>
                                <input type="text" class="form-control input-inline input-sm" id="survey_end_date" value="<?=date('Y-m-d H:i')?>">


                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="tb01">종료조건</td>
                        <td class="tb02">
                            <label><input name="survey_end_condition" value="0" type="radio" checked> 설문기간 만료 시 종료</label>
                            <label style="margin-left: 20px"><input name="survey_end_condition" value="1" type="radio"> 응답자 <input id="survey_end_count" type="text" class="form-control input-inline input-sm"> 명 도달시 종료</label>
                        </td>
                    </tr>
                    <tr>
                        <td class="tb01">응답자정보</td>
                        <td class="tb02">
                            <label><input name="review_infor" value="0" type="radio" checked> 익명(묻지않음)</label>
<!--                            <label style="margin-left: 20px"><input name="review_infor" value="1" type="radio"> 학년+반+이름 </label>-->
                            <label style="margin-left: 20px"><input name="review_infor" value="2" type="radio"> 소속+이름 </label>
                            <label style="margin-left: 20px"><input name="review_infor" value="3" type="radio"> 이름 </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="tb01">본인인증</td>
                        <td class="tb02">
                            <label><input name="survey_auth" value="0" type="radio" checked> 사용안함</label>
                            <label style="margin-left: 20px"><input name="survey_auth" value="1" type="radio"> 사용함 </label><label style="color:#ff0000">( * 전화번호뒤 4자리)</label>
                        </td>
                    </tr>
                </table>

        <!------------------ 3. 설문지작성구간 -------------------->
                <div class="survey-step-title"><span>Step2</span> 설문지작성<button onclick="getSurveyList(0);" class="btn btn-primary" style="padding: 10px 20px 10px 28px; letter-spacing: 8px;margin-right: 5px;float: right">공개설문 불러오기</button></div>

                <!--****** 1). 설문그룹개수선택구간 *******-->
                <div style = "margin-bottom: 40px;border: 4px double #adadad;">
                    <table class="servey-table" style = "border:none">
                    <tr>
                        <td class="tb05" style = "width: 160px;">설문그룹수</td>
                        <td class="tb06">
                            <div class="form-inline">
                                <select id="survey_group_count" class="form-control" style="margin-top: 6px; margin-left: 50px" onchange="on_change_group_count()">
                                    <?php
                                    for ($i = 1; $i <= 20; $i++) {
                                        ?>
                                        <option value="<?=$i?>"><?=$i?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <span> 개</span>
                            </div>
                        </td>
                        <td class="tb05" style = "padding-left: 50px;width: 180px;">페이지별 문항수</td>
                        <td class="tb06" style = "width:">
                            <div class="form-inline">
                                <select id="survey_question_count_page" class="form-control"  style="margin-top: 6px;margin-left:10px">
                                    <?php
                                    for ($i = 1; $i <= 20; $i++) {
                                        ?>
                                        <option value="<?=$i?>"><?=$i?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <span> 개</span>
                            </div>
                        </td>
                    </tr>
                </table>
                </div>

                <div id = "survey_groups">
                <!--****** 2). 설문문항쌤플구간 *******-->
                    <div class="survey-question-container-sample">
                        <div class="survey-question">
                            <div class="row">
                                <div style = "width:16%;float:left;margin-left:20px">
                                    <div class="survey-question-header"><span style="color:#ff007a;font-size: 20px;">Q</span> <span class="survey-question-number">쌤플문항</span></div>
                                </div>
                                <div style = "width:60%;float:left;margin-left:20px">
                                    <button class="btn btn-warning btn-question-type" question-type="0">객관식</button>
                                    <button class="btn btn-default btn-question-type" question-type="1">주관식</button>
                                    <button class="btn btn-default btn-question-type" question-type="2">만족도</button>
                                    <button class="btn btn-default btn-question-type" question-type="3" style = "padding-left:35px;padding-right:35px">강사만족도</button>
                                </div>
                                <div class="col-md-2 survey-question-io" style="padding-top:0px;float: right">
                                    <button disabled class="btn-question-up btn btn-primary btn-sm"  ><i class="fas fa-arrow-up"></i></button>
                                    <button disabled style="padding: 5px 10px;" class="btn-question-down btn btn-sm btn-warning"><i class="fas fa-arrow-down"></i></button>
                                    <button class="btn-question-remove btn btn-danger btn-sm"><i class="fas fa-times"></i></button>
                                </div>
                            </div>
                        </div>

                        <!--****** (1). <객관/주관/만족도/강사만족도> 설정구간 *******-->
                        <table class="servey-table" >
                            <tr>
                                <td class="tb01">옵션</td>
                                <td class="survey-question-option">
                                    <div class="row question-option-0" style="margin-top: 5px">
                                        <div class="col-md-12 form-inline">
                                            <label style="font-size: 13px;margin-left: 50px;">
                                                <input class="form-check-input reply_response" name="reply_response" value="0" type="checkbox" >
                                                복수선택가능
                                            </label>
                                            <label id="sub_input_use_scope" style="font-size: 13px;margin-left: 50px;">
                                                <input class="form-check-input use_other_input" name="use_other_input" value="0" type="checkbox" >
                                                마지막 보기(주관식)
                                            </label>
                                            <label id="no_select" style="font-size: 13px;margin-left: 50px;">
                                                <input class="form-check-input allow_unselect" name="allow_unselect" value="0" type="checkbox" >
                                                미선택 가능
                                            </label>
                                            <label style="font-size: 13px;margin-left: 50px;">
                                                <input class="form-check-input allow_random_align" name="allow_random_align" value="0" type="checkbox" >
                                                보기순서 임의로 배열
                                            </label>
                                            <!-- <span>보기 선택수</span>-->
                                            <!--<select class="form-control survey-example-min">
                                                <option value="1">최소 1개</option>
                                                <option value="2">최소 2개</option>
                                            </select>
                                            <select class="form-control survey-example-max">
                                                <option value="1">최대 1개</option>
                                                <option value="2">최대 2개</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-inline">
                                            <span>보기 순서</span>
                                            <select class="form-control survey-example-order">
                                                <option value="0">순서대로 보여주기</option>
                                                <option value="1">무작위로 보여주기</option>
                                            </select>-->
                                        </div>
                                    </div>
                                    <div class="row question-option-1" style="margin-top: 5px">
                                        <div class="col-md-6">
                                            <span>입력창수</span>
                                            <select class="survey-example-count" id="survey-example-count1">
                                                <option value="1">1개</option>
                                                <option value="2">2개</option>
                                                <option value="3">3개</option>
                                            </select>
                                            <label id="no_select" style="font-size: 13px;margin-left: 50px;">
                                                <input class="form-check-input allow_unselect_1" name="allow_unselect_1" value="0" type="checkbox" >
                                                미선택 가능
                                            </label>
                                        </div>


                                    </div>
                                    <div class="row question-option-2" style="margin-top: 5px;margin-bottom: 5px;">
                                        <div class="col-md-3">
                                            <span>모양</span>
                                            <select class="question-type-grade">
                                                <option value="0" selected>별점형</option>
                                                <option value="1">막대형</option>
                                                <!-- <option value="2">슬라이드형</option>-->
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <span>갯수</span>
                                            <select class="survey-example-count" id="survey-example-count2">
                                                <option value="3">3개</option>
                                                <option value="5" selected>5개</option>
                                            </select>
                                            <label id="no_select" style="font-size: 13px;margin-left: 50px;">
                                                <input class="form-check-input allow_unselect_2" name="allow_unselect_2" value="0" type="checkbox" >
                                                미선택 가능
                                            </label>
                                        </div>

                                    </div>
                                    <div class="row question-option-3" style="margin-top: 5px;margin-bottom: 5px;">
                                        <div class="col-md-3">
                                            <span>모양</span>
                                            <select class="question-type-grade">
                                                <option value="0" selected>별점형</option>
                                                <option value="1">막대형</option>
                                                <!-- <option value="2">슬라이드형</option>-->
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <span>갯수</span>
                                            <select class="survey-example-count" id="survey-example-count2">
                                                <option value="3">3개</option>
                                                <option value="5" selected>5개</option>
                                            </select>
                                            <label id="no_select" style="font-size: 13px;margin-left: 50px;">
                                                <input class="form-check-input allow_unselect_3" name="allow_unselect_3" value="0" type="checkbox" >
                                                미선택 가능
                                            </label>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                            <tr class="question_content">
                                <td class="tb03">질문</td>
                                <td class="tb04">
                                    <div style="position: relative">
                                        <textarea class="survey-question-title form-control"  style="resize: none"></textarea>
                                        <div class="survey-query-img">
                                                <span class="btn btn-default fileinput-button">
                                                    <i class="far fa-image"></i>
                                                    <input class="question-thumb" type="file" name="files[]">
                                                    <input type="hidden" class="question-thumbnail-file-name" value="">
                                                </span>
                                            <img class="hidden question-thumb-uploaded" src="">
                                            <div class="btn-del-img-container"><button class="btn btn-danger btn-del-img"><i class="fas fa-times"></i></button></div>
                                        </div>
                                        <span style="color: #ff007a;">* 이미지의 가로 해상도는 540픽셀이 적당합니다.(540*480 or 540*400 or 540*360, 200kb이하 jpg, png 형식 지원)</span>
                                    </div>
                                </td>
                            </tr>
                            <tr class="question-type-0">
                                <td class="tb03">보기<br>
                                    <label><input class="form-check-input example-image-check"  type="checkbox"> 이미지삽입</label>
                                </td>
                                <td class="tb04 survey-example">

                                    <ul class="examples">
                                        <li class="example-sample">
                                            <span class="example-number">보기 쌤플</span>
                                            <div class="example-image-file-container">
                                                    <span class="btn btn-default fileinput-button">
                                                        <i class="far fa-image"></i>
                                                        <input class="example-thumb" type="file" name="files[]">
                                                        <input type="hidden" class="example-thumbnail-file-name" value="">
                                                    </span>
                                                <img class="hidden example-thumb-uploaded" src="">
                                                <div class="btn-exam-del-img-container"><button class="btn btn-danger btn-exam-del-img"><i class="fas fa-times"></i></button></div>
                                            </div>
                                            <input type="text" class="form-control input-inline input-sm example-title" style=""/>
                                            <button class="btn-example-up btn btn-primary btn-sm"><i class="fas fa-arrow-up"></i></button>
                                            <button class="btn-example-down btn btn-warning btn-sm"><i class="fas fa-arrow-down"></i></button>
                                            <button class="btn-example-remove btn btn-danger btn-sm"><i class="fas fa-times"></i></button>
                                            <button class="btn-example-plus btn btn-success btn-sm"><i class="fas fa-plus"></i></button>
                                            <label class="question-move-index" >
                                                <select class="form-control question-move ">
                                                    <option value="0"> </option>
                                                    <option value="1"> 1 </option>
                                                </select>
                                                번 문항으로 이동</label>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            <tr class="question-type-1">
                                <td class="tb03">입력창</td>
                                <td class="tb04 survey-example">
                                    <input class="form-control input-sm input-inline" disabled />
                                    <input class="form-control input-sm input-inline hidden" disabled />
                                    <input class="form-control input-sm input-inline hidden" disabled />
                                    <label class="end-move-index" >
                                        <select class="form-control end-move " style="display: inline-block; width: 100px; height: 31px;">
                                            <option value="0"> </option>

                                        </select>
                                        로 완료</label>
                                </td>

                            </tr>
                            <tr class="question-type-2">
                                <td class="tb03">보기</td>
                                <td class="survey-example">
                                    <div class="row example-stars example-stars-3">
                                        <?php
                                        foreach($GLOBALS['survey']['fav_grades_3'] as $grade) {
                                            ?>
                                            <div class="col-md-2 example-fav-block">
                                                <div class="example-fav-star">
                                                    <i class="fas fa-star example-fav"></i>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="row example-stars example-stars-5">
                                        <?php
                                        foreach($GLOBALS['survey']['fav_grades'] as $grade) {
                                            ?>
                                            <div class="col-md-2 example-fav-block">
                                                <div class="example-fav-star">
                                                    <i class="fas fa-star example-fav"></i>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="row example-squares example-squares-3">
                                        <?php
                                        foreach($GLOBALS['survey']['fav_grades_3'] as $grade) {
                                            ?>
                                            <div class="col-md-2 example-fav-block">
                                                <div class="example-fav-square">
                                                    <i class="fas fa-window-minimize example-fav"></i>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="row example-squares example-squares-5">
                                        <?php
                                        foreach($GLOBALS['survey']['fav_grades'] as $grade) {
                                            ?>
                                            <div class="col-md-2 example-fav-block">
                                                <div class="example-fav-square">
                                                    <i class="fas fa-window-minimize example-fav"></i>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <!-- <div class="example-slider example-slider-3">
                                         <div class="slider-container-3">
                                             <div class="example-fav-slider m-nouislider m-nouislider--handle-danger"></div>
                                         </div>
                                     </div>
                                     <div class="example-slider example-slider-5">
                                         <div class="slider-container-5">
                                             <div class="example-fav-slider m-nouislider m-nouislider--handle-danger"></div>
                                         </div>
                                     </div>-->

                                    <div class="row type-grade-inputs-5">
                                        <?php
                                        foreach($GLOBALS['survey']['fav_grades'] as $grade) {
                                            ?>
                                            <div class="col-md-2 example-fav-block">
                                                <div class="example-fav-input">
                                                    <input class="form-control" placeholder="<?=$grade?>">
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>

                                    <div class="row type-grade-inputs-3">
                                        <?php
                                        foreach($GLOBALS['survey']['fav_grades_3'] as $grade) {
                                            ?>
                                            <div class="col-md-2 example-fav-block">
                                                <div class="example-fav-input">
                                                    <input class="form-control" placeholder="<?=$grade?>">
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>

                                </td>
                            </tr>
                            <tr class="question-type-3">
                                <td class="tb03">보기</td>
                                <td class="survey-example">
                                    <div class="row example-stars example-stars-3">
                                        <?php
                                        foreach($GLOBALS['survey']['fav_grades_3'] as $grade) {
                                            ?>
                                            <div class="col-md-2 example-fav-block">
                                                <div class="example-fav-star">
                                                    <i class="fas fa-star example-fav"></i>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="row example-stars example-stars-5">
                                        <?php
                                        foreach($GLOBALS['survey']['fav_grades'] as $grade) {
                                            ?>
                                            <div class="col-md-2 example-fav-block">
                                                <div class="example-fav-star">
                                                    <i class="fas fa-star example-fav"></i>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="row example-squares example-squares-3">
                                        <?php
                                        foreach($GLOBALS['survey']['fav_grades_3'] as $grade) {
                                            ?>
                                            <div class="col-md-2 example-fav-block">
                                                <div class="example-fav-square">
                                                    <i class="fas fa-window-minimize example-fav"></i>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="row example-squares example-squares-5">
                                        <?php
                                        foreach($GLOBALS['survey']['fav_grades'] as $grade) {
                                            ?>
                                            <div class="col-md-2 example-fav-block">
                                                <div class="example-fav-square">
                                                    <i class="fas fa-window-minimize example-fav"></i>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="row type-grade-inputs-5">
                                        <?php
                                        foreach($GLOBALS['survey']['fav_grades'] as $grade) {
                                            ?>
                                            <div class="col-md-2 example-fav-block">
                                                <div class="example-fav-input">
                                                    <input class="form-control" placeholder="<?=$grade?>">
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>

                                    <div class="row type-grade-inputs-3">
                                        <?php
                                        foreach($GLOBALS['survey']['fav_grades_3'] as $grade) {
                                            ?>
                                            <div class="col-md-2 example-fav-block">
                                                <div class="example-fav-input">
                                                    <input class="form-control" placeholder="<?=$grade?>">
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>

                                </td>
                            </tr>
                        </table>

                        <!--****** (2). 강사만족도구간 *******-->
                        <div class = "section-question-type-3">
                            <table class="servey-table">
                                <tr>
                                    <td class="tb05" style = "font-size:12px">평가지표수</td>
                                    <td class="tb06">
                                        <div class="form-inline">
                                            <select class="exam_kind_count">
                                                <?php
                                                for ($i = 1; $i <= 20; $i++) {
                                                    ?>
                                                    <option value="<?=$i?>"><?=$i?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <span> 개</span>
                                        </div>
                                    </td>
                                    <td class="tb05" style = "font-size:12px"><span style = "margin-left: 40px;">평가대상수</span></td>
                                    <td class="tb06">
                                        <div class="form-inline">
                                            <select class="exam_object_count">
                                                <?php
                                                for ($i = 1; $i <= 20; $i++) {
                                                    ?>
                                                    <option value="<?=$i?>"><?=$i?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <span> 개</span>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <table class="servey-table exam-kind-table" style = "margin-top: 10px; border-top: 2px solid #929191;">
                                <tr class="exam-kind-tr-sample" style = "display: none">
                                    <td class="tb03">평가지표1</td>
                                    <td class="tb04">
                                        <div style="position: relative">
                                            <div style="display: inline-block;width: 70px;text-align: center;">제목</div>
                                            <div style="display: inline-block">
                                                <div class="form-inline">
                                                    <input type="text" class="form-control input-inline input-sm exam-kind-title" style="width:640px">
                                                </div>
                                            </div>
                                        </div>
                                        <div style="position: relative;margin-top:10px">
                                            <div style="width: 70px;display: inline-block; text-align: center; vertical-align: top;margin-top: 30px;">내용</div>
                                            <div style="display: inline-block">
                                                <textarea class="exam-kind-content form-control"  style="resize: none;height:80px;width:640px"></textarea>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <table class="servey-table exam-object-table" style = "margin-top: 10px; border-top: 2px solid #929191;">
                                <tr  class="exam-object-tr-sample" style = "display: none">
                                    <td class="tb03">평가대상1</td>
                                    <td class="tb04">
                                        <div style="position: relative;text-align:center;margin-left: 70px;">
                                            <input type="text" class="form-control input-inline input-sm exam-object-title" style="width:640px">
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </div>
                    <div class = "survey-group-container-sample" style = "margin-top:30px">
                <!--****** 3). 질문개수선택구간 *******-->
                        <div style = "border-top: 4px solid #5cb85c;font-size: 14px;font-weight: bold;">
                            <table class="servey-table">
                                <tr>
                                    <td class="tb05" style = "background: #f7f7f7"><span style="color: #ff007a;font-size: 18px;margin-left: -20px;margin-right: 10px;">G</span><span class="survey-group-number">쌤플그룹</span></td>
                                    <td style="padding-left: 25px;width: 75px;"><span>제 목</span></td>
                                    <td class="tb06" style = "padding-left:0px">
                                        <div class="form-inline">
                                            <input type="text" class="form-control input-inline input-sm group-title" style="width:260px">
                                        </div>
                                    </td>
                                    <td style = "padding-left: 20px;">설문 문항수</td>
                                    <td class="tb06">
                                        <div class="form-inline">
                                            <select class = "survey_question_count form-control" style="margin-top: 6px;margin-left:0;height: 34px;padding: 6px 12px; font-weight: lighter;">
                                                <?php
                                                for ($i = 1; $i <= 20; $i++) {
                                                    ?>
                                                    <option value="<?=$i?>"><?=$i?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <span style="font-size:12px;font-weight: lighter"> 개</span>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                <!--****** 4). 질문작성구간 *******-->
                        <div class = "survey_questions">
                        </div>
                    </div>
                </div>

        <!------------------ 4. 설문종료구간 -------------------->
                <div class="survey-step-title"><span>Step3</span> 설문 종료</div>
                <table class="servey-table">
                    <tr>
                        <td class="tb01"><label style="font-size: 13px;    font-weight: 700;">종료1<input type="checkbox" name="end_check_1" class="form-check-input" style="margin-left: 5px;"> </label></td>
                        <td class="tb02">
                            <input type="text" name="end_comment_1" id="end_comment_1" readonly class="form-control" style="resize: none"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="tb01"><label style="font-size: 13px;    font-weight: 700;">종료2<input type="checkbox" name="end_check_2" class="form-check-input" style="margin-left: 5px;"> </label></td>
                        <td class="tb02">
                            <input type="text" name="end_comment_2" id="end_comment_2" readonly class="form-control" style="resize: none"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="tb01"><label style="font-size: 13px;    font-weight: 700;">종료3<input type="checkbox" name="end_check_3" class="form-check-input" style="margin-left: 5px;"> </label></td>
                        <td class="tb02">
                            <input type="text" name="end_comment_3" id="end_comment_3" readonly class="form-control" style="resize: none"/>
                        </td>
                    </tr>
                    <tr>
                        <td class="tb01"><label style="font-size: 13px;    font-weight: 700;">종료4<input type="checkbox" name="end_check_4" class="form-check-input" style="margin-left: 5px;"> </label></td>
                        <td class="tb02">
                            <input type="text" name="end_comment_4" id="end_comment_4" readonly class="form-control" style="resize: none"/>
                        </td>
                    </tr>
                </table>

        <!------------------ 5. 단추조작구간 -------------------->
                <div style="text-align: center; margin-top: 19px;">
                    <button onclick="post();" class="btn btn-primary" style="padding: 10px 20px 10px 28px; letter-spacing: 8px;width: 130px;margin-right: 50px;">다음</button>
                    <button onclick="save();" class="btn btn-primary" style="padding: 10px 20px 10px 28px; letter-spacing: 8px;width: 130px;margin-right: 50px;">저장</button>
                    <button onclick="preview();" class="btn btn-primary" style="padding: 10px 20px 10px 28px; letter-spacing: 8px;width: 130px;">미리보기</button>
                </div>

        <!------------------ 6. 기 타 -------------------->
                <div style="display: inline-block;width: 80%; border: 5px solid #a1a1a1; padding: 0px 20px; margin: 20px 100px;">
                    <p style="font-size: 16px;    line-height: 30px;    margin-top: 5px; margin-bottom: 5px;">
                        <strong>다음</strong>을 선택하면 전송화면으로 넘어가며, 전송이후에는 <lable style="color: #4b8df8">설문조사 -> 전송한 설문 목록</lable>에 자동저장됩니다.<br>
                        <strong>저장</strong>을 선택하면 <lable style="color: #4b8df8">설문조사 ->작성중 설문 목록</lable>에 저장됩니다.
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="container container-bg" id = "previewArea" style="margin-top: 50px;background: #fff;margin-bottom: 50px;">
</div>
