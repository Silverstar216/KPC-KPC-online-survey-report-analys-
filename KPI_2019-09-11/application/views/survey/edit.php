<?php
/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 2/29/2016
 * Time: 9:47 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
$site_url = site_url();

$now_date = date('Y-m-d');
$str_date=strtotime($now_date.'+7 days');
$end_date=date('Y-m-d',$str_date);
$start_date = date('Y-m-d');
$education_id = '';
$count_name = '';
$student_count = '';
$customer_name = '';
$education_name = '';
$subject_name = '';
$teachers_count = 0;
$teacher1 = '';
$teacher2 = '';
$teacher3 = '';
$teacher4 = '';
$teacher5 = '';

$subject_name = "";
if ($education != null) {
    $education_id = $education[0]['id'];
    $education_name = $education[0]['subject_name'];     
    $count_name = $education[0]['count_name'];
    $student_count = $education[0]['student_count'];
    $customer_name = $education[0]['customer_name'];
    // if ($survey_flag == 1) {
        $teachers = explode(" ", $education[0]['teachers_name']);    
        if (count($teachers) > 0) {$teacher1 = $teachers[0]; $teachers_count++;}
        if (count($teachers) > 1) {$teacher2 = $teachers[1]; $teachers_count++;}
        if (count($teachers) > 2) {$teacher3 = $teachers[2]; $teachers_count++;}
        if (count($teachers) > 3) {$teacher4 = $teachers[3]; $teachers_count++;}
        if (count($teachers) > 4) {$teacher5 = $teachers[4]; $teachers_count++;}
        if($teachers_count > 0)
            $teachers_count--;    
    // }
}else{
    if($sms_available == 1){
        $education_name = $education_course;      
        $customer_name = $education_customer;
        $teachers = explode(",", $education_teacher);    
        if (count($teachers) > 0) {$teacher1 = $teachers[0]; $teachers_count++;}
        if (count($teachers) > 1) {$teacher2 = $teachers[1]; $teachers_count++;}
        if (count($teachers) > 2) {$teacher3 = $teachers[2]; $teachers_count++;}
        if (count($teachers) > 3) {$teacher4 = $teachers[3]; $teachers_count++;}
        if (count($teachers) > 4) {$teacher5 = $teachers[4]; $teachers_count++;}
        if($teachers_count > 0)
            $teachers_count--;   
    }
}

if ($education_id == '')
    $education_id = '0';

$survey_edit_type = '';
if (($education_id == '' || $education_id == '0') && $this->data['sms_available'] != 1)
    $survey_edit_type = 'hidden';

$loaded_page = 0;

if ($education_title != '') {
    $subject_name = $education_title;
    $loaded_page = 1;
}
if ($survey_start_date != '') {
    $start_date = $survey_start_date;
    $loaded_page = 1;
}
if ($survey_end_date != '') {
    $end_date = $survey_end_date;
    $loaded_page = 1;
}

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
                        <div class="m7con">
                            <input id="survey_attached" type="hidden" value="<?=$attached?>">
                            <input id="survey_attachedHTML" type="hidden" value="">
                            <input id="survey_id" type="hidden" value="<?=$survey_id?>">
                            <input id="survey_flag" type="hidden" value="<?=$survey_flag?>">
                            <input id="is_public_enable" type="hidden" value="<?=$is_public_enable?>">
                            <input id="newflag" type="hidden" value="<?=$newflag?>">
                            <input id="education_count" type="hidden" value="<?=$count_name?>">
                            <input id="education_id" type="hidden" value="<?=$education_id?>">
                            <input id="loaded_page" type="hidden" value="<?=$loaded_page?>">
                            <!-- <div class="search">
                                <img src="<?=$site_url?>images/img/information.png">
                                <?php
                                    if ($this->data['survey_id'] == 0)
                                        echo ('신규작성 페이지입니다.');
                                    else
                                        echo ('불러오기 페이지입니다.');
                                ?>                                
                            </div>                             -->
                            <div class="search">
                                <img src="<?=$site_url?>images/img/icon_item_red.png">
                                <b>1단계 기초정보</b>
                            </div>
                            <div class="search search-items">
                                <table class="import_survey_table"> 
                                    <tr>
                                        <td class="label_td">설문양식</td>
                                        <td style="text-align: left;">
                                            <input type="text" style="width: 95%; height: 30px;" id="education_title" value="<?=$subject_name?>">
                                            <!-- <button onclick="edit_education()" class="btn btn-default btn-question-type" style="width: 15%;">교육과정설정</button> -->
                                        </td>
                                    </tr>
                                    <tr <?=$survey_edit_type?>>
                                        <td class="label_td">교육과정명</td>
                                        <td style="text-align: left;">
                                            <input type="text" style="width: 95%; height: 30px;" id="education_caption" value="<?=$education_name?>" >
                                        </td>
                                    </tr>
                                    <?php
                                    if($survey_flag == 0){ ?>
                                        <tr <?=$survey_edit_type?>>
                                            <td class="label_td">고객사</td>
                                            <td style="text-align: left;">
                                                <input type="text" style="width: 95%; height: 30px;" id="education_customer" value="<?=$customer_name?>" >
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <tr <?=$survey_edit_type?>>
                                        <td class="label_td">강사</td>
                                        <td style="text-align: left;">
                                            <input type="text" style="width: 18%; height: 30px; margin-right: 5px; text-align: center;" id="education_teacher_1" value="<?=$teacher1?>" >
                                            <input type="text" style="width: 18%; height: 30px; margin-right: 5px; text-align: center;" id="education_teacher_2" value="<?=$teacher2?>" >
                                            <input type="text" style="width: 18%; height: 30px; margin-right: 5px; text-align: center;" id="education_teacher_3" value="<?=$teacher3?>" >
                                            <input type="text" style="width: 18%; height: 30px; margin-right: 5px; text-align: center;" id="education_teacher_4" value="<?=$teacher4?>" >
                                            <input type="text" style="width: 18%; height: 30px; margin-right: 5px; text-align: center;" id="education_teacher_5" value="<?=$teacher5?>" >
                                        </td>
                                    </tr>
                                    <tr <?=$survey_edit_type?>>
                                        <td class="label_td">설문기간</td>
                                        <td style="text-align: left;">
                                            <input type="text" style="height: 28px;" id="survey_start_date" value="<?=$start_date?>">    ~    
                                            <input type="text" style="height: 28px;" id="survey_end_date" value="<?=$end_date?>">      
                                            &nbsp;&nbsp;&nbsp;차수&nbsp;&nbsp;<input type="text" style="width: 50px;" id="education_count_name" value="<?=$count_name?>" readonly></li>  
                                            &nbsp;&nbsp;&nbsp;인원수&nbsp;&nbsp;<input type="text" style="width: 50px;" id="education_student_count" value="<?=$student_count?>" readonly></li>  
                                        </td>
                                    </tr>                                   
                                    <tr hidden>
                                        <td class="label_td">종료조건</td>
                                        <td style="text-align: left;">
                                            <li style="width: 23%; text-align: left;">
                                            <input type="radio" name="survey_end_condition" value="0" checked>설문기간 만료 시 종료</li>
                                            <li style="width: 40%; text-align: left;">
                                            <input type="radio" name="survey_end_condition" value="1">응답자   
                                            <input type="text" style="width: 30px;" id="survey_end_count" value="0">명 도달시 종료</li>                                          
                                        </td>
                                    </tr>
                                    <tr hidden>
                                        <td class="label_td">응답자정보</td>
                                        <td style="text-align: left;">
                                            <li style="width: 20%; text-align: left;">
                                            <input type="radio" name="review_infor" value="0" checked>익명 (묻지않음)</li>                                            
                                            <input type="radio" name="review_infor" value="1">학년 + 반 + 이름</li>                                                
                                            <li style="width: 15%; text-align: left;">
                                            <input type="radio" name="review_infor" value="2">소속 + 이름</li>   
                                            <li style="width: 15%; text-align: left;">
                                            <input type="radio" name="review_infor" value="3">이름</li>                                               
                                        </td>
                                    </tr>
                                    <tr hidden>
                                        <td class="label_td">본인인증</td>
                                        <td style="text-align: left;">
                                            <li style="width: 20%; text-align: left;">
                                            <input type="radio" name="survey_auth" value="0" checked>사용안함</li>
                                            <li style="width: 25%; text-align: left;">
                                            <input type="radio" name="survey_auth" value="1">사용함 <font color="red"><b>(전화번호 뒤4자리)</b></font></li>   
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="search search-items">
                                <table class="import_survey_table"> 
                                    <tr>
                                        <td class="label_td">설문그룹수</td>
                                        <td style="text-align: left;">
                                            <select id="survey_group_count" class="form-control" style="width: 50%; display: inline;" onchange="on_change_group_count()">
                                            <?php
                                                for ($i = 1; $i <= 20; $i++) {
                                                    ?>
                                                    <option value="<?=$i?>"><?=$i?></option>
                                                    <?php
                                                }
                                            ?>
                                            </select> 개
                                        </td>
                                        <td class="label_td">페이지별 문항수</td>
                                        <td style="text-align: left;">
                                            <select id="survey_question_count_page" class="form-control"  style="width: 50%; display: inline;">
                                                <?php
                                                for ($i = 1; $i <= 20; $i++) {
                                                    ?>
                                                    <option value="<?=$i?>"><?=$i?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select> 개
                                        </td>
                                    </tr>                                   
                                </table>
                            </div>
                            
                            <div class="search">
                                <img src="<?=$site_url?>images/img/icon_item_red.png">
                                <b>2단계 설문작성</b>
                                <button onclick="browse_survey()" class="btn btn-default btn-question-type" style="float: right;">불러오기</button>
                            </div>
                            
                            <!------------------ 3. 진단작성구간 -------------------->                                
                            <div id = "survey_groups">
                                <!--****** 2). 진단문항쌤플구간 *******-->
                                <div class="survey-question-container-sample">
                                    <div class="survey-question">
                                        <div class="row">
                                            <div style = "width:16%;float:left;margin-left:5px">
                                                <div class="survey-question-header">
                                                    <span style="color:#ff007a;font-size: 20px;">Q</span> 
                                                    <span class="survey-question-number">쌤플문항</span>
                                                </div>
                                            </div>
                                            <div style = "width:60%;float:left;">
                                                <button class="btn btn-warning btn-question-type" style="width: 20%" question-type="0">객관식</button>
                                                <button class="btn btn-default btn-question-type" style="width: 20%" question-type="1">주관식</button>
                                                <button class="btn btn-default btn-question-type" style="width: 20%" question-type="2">만족도</button>
                                                <button class="btn btn-default btn-question-type" style="width: 20%" question-type="3" style = "padding-left:35px;padding-right:35px">강사만족도</button>
                                            </div>
                                            <div class="survey-question-io" style="float: right">
                                                <button disabled class="btn-question-up btn btn-primary btn-sm"  ><i class="fas fa-arrow-up"></i></button>
                                                <button disabled style="padding: 5px 10px;" class="btn-question-down btn btn-sm btn-warning"><i class="fas fa-arrow-down"></i></button>
                                                <button class="btn-question-remove btn btn-danger btn-sm"><i class="fas fa-times"></i></button>
                                            </div>
                                        </div>
                                    </div>

                                    <!--****** (1). <객관/주관/만족도/강사만족도> 설정구간 *******-->
                                    <div style = "font-size: 14px;font-weight: bold; padding-left: 5px">
                                        <table class="import_survey_table" >
                                            <tr>
                                                <td class="label_td">옵션</td>
                                                <td class="survey-question-option" style="text-align: left;">
                                                    <div class="row question-option-0">
                                                        <div class="col-md-12 form-inline">
                                                            <label style="font-size: 13px;">
                                                                <input class="form-check-input reply_response" name="reply_response" value="0" type="checkbox" >
                                                                복수선택가능
                                                            </label>
                                                            <label id="sub_input_use_scope" style="font-size: 13px;margin-left: 10px;">
                                                                <input class="form-check-input use_other_input" name="use_other_input" value="0" type="checkbox" >
                                                                마지막 보기(주관식)
                                                            </label>
                                                            <label id="no_select" style="font-size: 13px;margin-left: 10px;">
                                                                <input class="form-check-input allow_unselect" name="allow_unselect" value="0" type="checkbox" >
                                                                미선택 가능
                                                            </label>
                                                            <label style="font-size: 13px;margin-left: 10px;">
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
                                                                <option value="1">원형</option>
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
                                                            <label id="reverse_question" style="font-size: 13px;margin-left: 50px;">
                                                                <input class="form-check-input reverse_question_1" name="reverse_question_1" value="0" type="checkbox" >
                                                                역문항
                                                            </label>
                                                        </div>

                                                    </div>
                                                    <div class="row question-option-3" style="margin-top: 5px;margin-bottom: 5px;">
                                                        <div class="col-md-3">
                                                            <span>모양</span>
                                                            <select class="question-type-grade">
                                                                <option value="0" selected>별점형</option>
                                                                <option value="1">원형</option>
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
                                                <td class="label_td">질문</td>
                                                <td class="tb04">
                                                    <div style="position: relative">
                                                        <div class="survey-query-text" style="float: left; width: 84%;">
                                                            <table style="width: 100%; border: 0;">
                                                                <tr><td style="border: 0px">
                                                                <textarea class="survey-question-title form-control"  style="resize: none; width: 100%;"></textarea>
                                                                </td></tr>
                                                                <tr><td style="text-align: left; border: 0px">
                                                                * 이미지의 가로 해상도는 540픽셀이 적당합니다.(540*480 or 540*400 or 540*360, 2Mb이하 jpg, png 형식 지원)                                                
                                                                </td></tr>
                                                            </table>                                                                                                                    
                                                        </div>                                            
                                                        <div class="survey-query-img" style="float: right;">
                                                            <span class="btn btn-default fileinput-button">
                                                                <i class="far fa-image"></i>
                                                                <input class="question-thumb" type="file" name="files[]">
                                                                <input type="hidden" class="question-thumbnail-file-name" value="">
                                                            </span>
                                                            <img class="hidden question-thumb-uploaded" src="<?=$site_url?>images/img/upload.png">
                                                            <div class="btn-del-img-container"><button class="btn btn-danger btn-del-img"><i class="fas fa-times"></i></button></div>
                                                        </div>                                            
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="question-type-0">
                                                <td class="label_td">보기<br>
                                                    <label><input class="form-check-input example-image-check"  type="checkbox"> 이미지삽입</label>
                                                </td>
                                                <td class="tb04 survey-example">
                                                    <ul class="examples">
                                                        <li class="example-sample" style="text-align: left;width:100%">
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
                                                            <input type="text" class="form-control input-inline input-sm example-title" style="width: 40%"/>
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
                                                        $item_count = 0;
                                                        foreach($GLOBALS['survey']['fav_grades_3'] as $grade) {
                                                            ?>
                                                            <div class="col-md-2 example-fav-block">
                                                                <div class="example-fav-star">
                                                                    <?php 
                                                                        $item_count++;
                                                                        if ($item_count < 3)
                                                                            echo '<i class="fa fa-star example-fav"></i>';
                                                                        else
                                                                            echo '<i class="fa fa-star-o example-fav"></i>';
                                                                    ?>                                                                    
                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="row example-stars example-stars-5">
                                                        <?php
                                                        $item_count = 0;
                                                        foreach($GLOBALS['survey']['fav_grades'] as $grade) {
                                                            ?>
                                                            <div class="col-md-2 example-fav-block">
                                                                <div class="example-fav-star">
                                                                    <?php 
                                                                        $item_count++;
                                                                        if ($item_count < 4)
                                                                            echo '<i class="fa fa-star example-fav"></i>';
                                                                        else
                                                                            echo '<i class="fa fa-star-o example-fav"></i>';
                                                                    ?>   
                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="row example-squares example-squares-3">
                                                        <?php
                                                        $item_count = 0;
                                                        foreach($GLOBALS['survey']['fav_grades_3'] as $grade) {
                                                            ?>
                                                            <div class="col-md-2 example-fav-block">
                                                                <div class="example-fav-square">
                                                                <?php
                                                                    $item_count++;
                                                                    if ($item_count < 3)
                                                                        echo '<i class="fas fa-circle example-fav"></i>';
                                                                    else
                                                                        echo '<i class="far fa-circle example-fav"></i>';
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="row example-squares example-squares-5">
                                                        <?php
                                                         $item_count = 0;
                                                        foreach($GLOBALS['survey']['fav_grades'] as $grade) {
                                                            ?>
                                                            <div class="col-md-2 example-fav-block">
                                                                <div class="example-fav-square">
                                                                <?php
                                                                    $item_count++;
                                                                    if ($item_count < 4)
                                                                        echo '<i class="fas fa-circle example-fav"></i>';
                                                                    else
                                                                        echo '<i class="far fa-circle example-fav"></i>';
                                                                    ?>
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
                                                                    <input class="form-control rating-name" value="<?=$grade?>" style = "padding:0">
                                                                    <label class="question-move-index" style="padding-top: 5px;">
                                                                        <select class="form-control question-move " style="width:100%;display:inline-block">
                                                                            <option value="0"> </option>
                                                                            <option value="1"> 1 </option>
                                                                        </select>
                                                                         <div style="width:120px;margin-left:-10px">번 문항으로 이동</div>
                                                                    </label>
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
                                                                    <input class="form-control rating-name" value="<?=$grade?>"  style = "padding:0">
                                                                    <label class="question-move-index" style="padding-top: 5px;">
                                                                        <select class="form-control question-move " style="width:100%;display:inline-block">
                                                                            <option value="0"> </option>
                                                                            <option value="1"> 1 </option>
                                                                        </select>
                                                                         <div style="width:120px;margin-left:-10px">번 문항으로 이동</div>
                                                                    </label>
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
                                                        $item_count = 0;
                                                        foreach($GLOBALS['survey']['fav_grades_3'] as $grade) {
                                                            ?>
                                                            <div class="col-md-2 example-fav-block">
                                                                <div class="example-fav-star">
                                                                    <?php 
                                                                        $item_count++;
                                                                        if ($item_count < 3)
                                                                            echo '<i class="fa fa-star example-fav"></i>';
                                                                        else
                                                                            echo '<i class="fa fa-star-o example-fav"></i>';
                                                                    ?>   
                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="row example-stars example-stars-5">
                                                        <?php
                                                        $item_count = 0;
                                                        foreach($GLOBALS['survey']['fav_grades'] as $grade) {
                                                            ?>
                                                            <div class="col-md-2 example-fav-block">
                                                                <div class="example-fav-star">
                                                                    <?php 
                                                                        $item_count++;
                                                                        if ($item_count < 4)
                                                                            echo '<i class="fa fa-star example-fav"></i>';
                                                                        else
                                                                            echo '<i class="fa fa-star-o example-fav"></i>';
                                                                    ?>    
                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="row example-squares example-squares-3">
                                                        <?php
                                                         $item_count = 0;
                                                        foreach($GLOBALS['survey']['fav_grades_3'] as $grade) {
                                                            ?>
                                                            <div class="col-md-2 example-fav-block">
                                                                <div class="example-fav-square">
                                                                <?php
                                                                    $item_count++;
                                                                    if ($item_count < 3)
                                                                        echo '<i class="fas fa-circle example-fav"></i>';
                                                                    else
                                                                        echo '<i class="far fa-circle example-fav"></i>';
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="row example-squares example-squares-5">
                                                        <?php
                                                         $item_count = 0;
                                                        foreach($GLOBALS['survey']['fav_grades'] as $grade) {
                                                            ?>
                                                            <div class="col-md-2 example-fav-block">
                                                                <div class="example-fav-square">
                                                                <?php
                                                                    $item_count++;
                                                                    if ($item_count < 4)
                                                                        echo '<i class="fas fa-circle example-fav"></i>';
                                                                    else
                                                                        echo '<i class="far fa-circle example-fav"></i>';
                                                                    ?>
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
                                                                    <input class="form-control" placeholder="<?=$grade?>" readonly>
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
                                                                    <input class="form-control" placeholder="<?=$grade?>" readonly>
                                                                </div>
                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>

                                                </td>
                                            </tr>
                                        </table>
                                    </div>

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
                                                            for ($i = 1; $i <= 30; $i++) {
                                                                ?>
                                                                <option value="<?=$i?>"<?php if ($teachers_count == $i) echo (" selected");?>><?=$i?></option>
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
                                                <td class="tb03" style="width: 10%;">평가지표1</td>
                                                <td class="tb04" style="width: 90%;">
                                                    <div style="position: relative;">
                                                        <div style="display: inline-block; width: 100%; text-align: center; padding-top: 10px;">제목</div>
                                                        <div style="display: inline-block; width: 100%; ">
                                                            <div class="form-inline">
                                                                <input type="text" class="form-control input-inline input-sm exam-kind-title" style="width:90%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div style="position: relative;margin-top:10px">
                                                        <div style="width: 100%;display: inline-block; text-align: center; vertical-align: top; margin-top: 20px;">내용</div>
                                                        <div style="display: inline-block; width: 100%;">
                                                            <textarea class="exam-kind-content form-control"  style="resize: none;height:80px;width:640px; margin-left: 33px;  margin-right: 33px;"></textarea>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                        <table class="servey-table exam-object-table" style = "margin-top: 10px; border-top: 2px solid #929191;">
                                            <tr  class="exam-object-tr-sample" style = "display: none">
                                                <td class="tb03" style="width: 10%;">평가대상1</td>
                                                <td class="tb04">
                                                    <div style="position: relative; padding-top: 10px; text-align:center;">
                                                        <input type="text" class="form-control input-inline input-sm exam-object-title" style="width:550px; margin-left: 15px;">
                                                    </div>
                                                    <div style="position: relative; padding-top: 10px; text-align:left;    padding-left: 15px;">
                                                        <span style="width: 90%; font-weight: bold;">* 이미지의 가로 해상도는 540픽셀이 적당합니다.<br>(540*480 or 540*400 or 540*360, 2Mb이하 jpg, png 형식 지원)</span>                                                
                                                    </div>
                                                </td>
                                                <td>
                                                    <div style="position: relative;">
                                                        <div class="survey-teacher-img">
                                                            <span class="btn btn-default fileinput-button">
                                                                <i class="far fa-image"></i>
                                                                <input class="question-teacher-file" type="file" name="files[]">
                                                                <input type="hidden" class="teacher-profile-file-name" value="">
                                                            </span>
                                                            <img class="hidden question-teacher-profile" src="">
                                                            <div class="btn-del-img-container"><button class="btn btn-danger btn-del-img"><i class="fas fa-times"></i></button></div>
                                                        </div>
                                                    </div>
                                                </td>                                                
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class = "survey-group-container-sample" style = "margin-top:30px">
                                    <!--****** 3). 질문개수선택구간 *******-->
                                    <div style = "font-size: 14px;font-weight: bold; padding-left: 5px">
                                        <table class="servey-table import_survey_table">
                                            <tr>
                                                <td class="label_td"  style = "background: #c7c5c5">
                                                    <span class="survey-group-number">쌤플그룹</span>
                                                </td>
                                                <td style="text-align: left;">제 목
                                                    <input type="text" class="form-control input-inline input-sm group-title" style="width: 80%;">
                                                </td>
                                                <td class="label_td"  style = "background: #c7c5c5">설문 문항수</td>
                                                <td class="tb06" style="text-align: left;">
                                                    <div class="form-inline">
                                                        <select class = "survey_question_count form-control" style="height: 34px;padding: 6px 12px; font-weight: lighter; width: 50%;">
                                                            <?php
                                                            for ($i = 1; $i <= 30; $i++) {
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

                            <!------------------ 4. 진단종료구간 -------------------->
                            <div class="search">
                                <img src="<?=$site_url?>images/img/icon_item_red.png">
                                <b>3단계 설문종료</b>
                            </div>

                            <div class="search search-items">
                                <table class="import_survey_table"> 
                                    <tr>
                                        <td class="label_td">종료1<input type="checkbox" name="end_check_1" class="form-check-input" style="margin-left: 5px;"></td>
                                        <td style="text-align: left;">
                                            <input type="text" style="width: 95%;" name="end_comment_1" id="end_comment_1">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label_td">종료2<input type="checkbox" name="end_check_2" class="form-check-input" style="margin-left: 5px;"></td>
                                        <td style="text-align: left;">
                                            <input type="text" style="width: 95%;" name="end_comment_2" id="end_comment_2" readonly>                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label_td">종료3<input type="checkbox" name="end_check_3" class="form-check-input" style="margin-left: 5px;"></td>
                                        <td style="text-align: left;">
                                            <input type="text" style="width: 95%;" name="end_comment_3" id="end_comment_3" readonly>                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label_td">종료4<input type="checkbox" name="end_check_4" class="form-check-input" style="margin-left: 5px;"></td>
                                        <td style="text-align: left;">
                                            <input type="text" style="width: 95%;" name="end_comment_4" id="end_comment_4" readonly>                                            
                                        </td>
                                    </tr>                                   
                                </table>
                            </div>

                            <!------------------ 5. 단추조작구간 -------------------->
                            <div style="text-align: center; margin-top: 19px;">
                                <input hidden type="checkbox" style="" name="survey_show_type" id="survey_show_type">
                                <button onclick="preview();" class="btn btn-preview" style="font-size: 15px; letter-spacing: 8px;width: 100px;">미리보기</button>                                
                                <?php 
                                if ($survey_edit_type == 'hidden' && $this->data['sms_available'] == 0) {
                                    if ($this->data['survey_id'] == 0) {
                                ?>
                                <button onclick="save();" class="btn btn-warning" style="font-size: 15px; letter-spacing: 8px;width: 100px;" id="btn_new_save">저장하기</button>
                                <button onclick="save(1);" class="btn btn-warning" style="font-size: 15px; display: none; letter-spacing: 8px;width: 100px;" id="btn_resave">다시저장</button>
                                <button onclick="save();" class="btn btn-warning" style="font-size: 15px; display: none; letter-spacing: 8px;width: 100px;" id="btn_new_clone">새로저장</button>
                                <?php
                                    }
                                    else {                                        
                                ?>
                                <button onclick="save();" class="btn btn-warning" style="font-size: 15px; display: none;letter-spacing: 8px;width: 100px;" id="btn_new_save">저장하기</button>
                                <button onclick="save(1);" class="btn btn-warning" style="font-size: 15px; letter-spacing: 8px;width: 100px;" id="btn_resave">다시저장</button>
                                <button onclick="save();" class="btn btn-warning" style="font-size: 15px; letter-spacing: 8px;width: 100px;" id="btn_new_clone">새로저장</button>
                                <?php
                                    }                  
                                } else {
                                ?>
                                    <button onclick="post();" class="btn btn-save" style="font-size: 15px; letter-spacing: 4px;width: 100px;">SMS발송</button>
                                    <button onclick="" class="btn btn-save" style="font-size: 15px; letter-spacing: 8px;width: 100px;">이메일</button>
                                    <button onclick="" class="btn btn-save" style="font-size: 15px; letter-spacing: 8px;width: 100px;">QR코드</button>                                
                                <?php
                                }
                                ?>                                
                            </div>

                            <!------------------ 6. 기 타 -------------------->
                            <div style="display: inline-block;width: 80%; border: 5px solid #a1a1a1; padding: 0px 20px; margin: 20px 100px;">
                                <?php 
                                if ($survey_edit_type != 'hidden') {
                                    if ($this->data['survey_id'] == 0) {
                                ?>
                                <p style="line-height: 30px;    margin-top: 5px; margin-bottom: 5px;">
                                    <strong>SMS발송</strong>을 선택하면 전송화면으로 넘어가며, 전송이후에는 <lable style="color: #4b8df8"><?=$this->data['menu']?> -> 설문내역</lable>에 자동저장됩니다.<br>
                                    <strong>저장하기</strong>를 선택하면 <lable style="color: #4b8df8"><?=$this->data['menu']?> -> 작성중 설문</lable>에 저장됩니다.
                                </p>
                                <?php
                                    }
                                    else {                                        
                                ?>
                                <p style="line-height: 30px;    margin-top: 5px; margin-bottom: 5px;">
                                    <strong>SMS발송</strong>을 선택하면 전송화면으로 넘어가며, 전송이후에는 <lable style="color: #4b8df8"><?=$this->data['menu']?> -> 설문내역</lable>에 자동저장됩니다.<br>
                                    <strong>다시저장</strong>을 선택하면 같은 이름으로 <lable style="color: #4b8df8"><?=$this->data['menu']?> -> 작성중 설문</lable>에 저장됩니다.<br>
                                    <strong>새로저장</strong>을 선택하면 새로운 이름으로 <lable style="color: #4b8df8"><?=$this->data['menu']?> -> 작성중 설문</lable>에 저장됩니다.
                                </p>
                                <?php
                                    }                               
                                }   
                                ?>                                       
                            </div>
                        </div>
                        <div class="container container-bg" id = "previewArea" style="margin-top: 50px;background: #fff;margin-bottom: 50px; width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

