<?php
/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 2/29/2016
 * Time: 9:47 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
$site_url = site_url();
$star_index=1;

    if($is_survey === 0) {
        $base_path = $this->input->server('DOCUMENT_ROOT') . base_url();
        $file_url = $base_path . strstr($file_url, 'uploads/');
        ?>
        <div class="attached_area">
            <div style=" text-align: center;margin-top:30px;display:block;" id = "mobile_view">
                <button onclick="onViewMobile('<?=$file_url?>')" class="btn btn-primary" style="background-color:#be3a94;letter-spacing: 8px;width: 230px;font-size:30px;margin-right: 10px;margin-bottom:20px">모바일보기</button>
            </div>
            <div style=" text-align: center;margin-top:30px;display:none;" id = "common_view">
                <button onclick="onViewCommon('<?=$file_url?>')" class="btn btn-primary" style="background-color:#be3a94;letter-spacing: 8px;width: 230px;font-size:30px;margin-right: 10px;margin-bottom:20px">일반보기</button>
            </div>
            <div id="attached_content" style="height:100%;display:none;">
                <?php
                $htmlPage = file_get_contents($file_url);

                if($htmlPage != false){
                    $htmlPage = str_replace('src="images/', 'src="uploads/html/images/', $htmlPage);

                    $startBodyPos = strpos($htmlPage, '<body>');
                    $endBodyPos = strpos($htmlPage, '</body>');
                    $startHeadPos = strpos($htmlPage, '<head>');
                    $endHeadPos = strpos($htmlPage, '</head>');
                    $content = substr($htmlPage,$startBodyPos + 6,$endBodyPos - $startBodyPos - 6);

                    if(isset($message_type) && $message_type == 4){ //개별고지이면
                        for($nIndex = 0; $nIndex < count($var_list); $nIndex ++){
                            if($nIndex < 9)
                                $content = str_replace("<?=$"."eleText0".($nIndex + 1)."?>", $var_list[$nIndex], $content);
                            else
                                $content = str_replace("<?=$"."eleText".($nIndex + 1)."?>", $var_list[$nIndex], $content);
                        }
                    }

                    $headContent = substr($htmlPage,$startHeadPos + 6,$endHeadPos - $startHeadPos - 6);
                    echo $headContent.$content;
                }else{
                    echo "파일이 존재하지 않습니다.";
                }
                ?>
                <div id="areaForPDF"></div>
            </div>
            <div  id = "enterAdvert" style=" display:none;margin-bottom: 30px;   text-align: center; margin-top: 10px;font-size: 45px;color: #ffffff;">
                <?php
                $i = 1;
                foreach ($advert_result as $item) {
                    ?>
                    <button id ="advert_link_<?=$i?>" style="display: none; background:#<?=$item['background']?>; width: 100%;  padding-top:20px;padding-bottom: 20px;" onclick="advert_link('<?=$item['link_url']?>','<?=$item['id']?>');"><?=$item['advert_title']?></button>
                    <?php
                    $i = $i+1;
                }
                ?>
                <input id="link_count" type="hidden" value="<?=$i-1?>">


            </div>
            <script>
                $(function () {
                    if ($('#page-container').length) {
                        $("#docKind").val("2"); //pdf변환파일이면
                        //settting viewport
                        $("meta[name='viewport']").attr("content", "width=360, initial-scale=0.6");
                        // $('head').append('<meta name="viewport" content="width=360, initial-scale=0.6" />');
                        $("#sidebar").remove();
                        //remove background
                        $('#page-container').css({'background-color' : '', 'background-image' : ''});
                        //replace new main area
                        $("#enterAdvert").appendTo($("#page-container"));
                        //insert enterSurvy button
                        $("#areaForPDF").html($("#page-container").html());
                        //replace remove old main area
                        $("#page-container").remove();

                        $("#mobile_view").hide();
                        $("#common_view").hide();
                    }else if($('.htOffice').length){

                        //setting viewport
                        $('head').append('<meta name="viewport" content="width=360, initial-scale=0.45" />');
                        //setting body css
                        $('body').css({'padding' :'0', 'margin:' : '0'});
                        $('.htOffice').css({'padding' :'0', 'margin:' : '0'});
                        $('.LOFooter').css({'padding' :'0', 'margin:' : '30px auto 30px auto'});
                        $('table').css({'margin-right:' : 'auto','margin-left:':'auto'});
                    }

                    //show main area and button
                    $("#attached_content").show();
                    $("#enterAdvert").show();
                });

            </script>


        </div>
        <?php
    }else {
        ?>

        <input id="file_url" type="hidden" value="<?= $surveys['file_url'] ?>">
        <input id="survey_id" type="hidden" value="<?= $survey_id ?>">
        <input id="error" type="hidden" value="<?= $error ?>">
        <?php
        $index=0;

        foreach($end_comments as $comment) {
            $index = $index + 1;
            ?>
            <input id="comment_<?= $index ?>" type="hidden" value="<?= $comment['content'] ?>">
            <?php
        }
            ?>
        <input id="mobile" type="hidden" value="<?= $mobile ?>">
        <input id="notice_id" type="hidden" value="<?= $notice_id ?>">
        <input id="question_count_page" type="hidden" value="<?= $surveys['question_count_page'] ?>">
        <input id="question_count" type="hidden" value="<?= $surveys['question_count'] ?>">
        <input id="review_infor" type="hidden" value="<?= $surveys['review_infor'] ?>">
<!--    문서가 첨부되었으면 / 첨부문서구역에 대한 보기속성설정-->
        <?php
        if($surveys['attached']==1) {
            ?>

            <div class="attached_area">
                <div style=" text-align: center;margin-top:30px;display:block;" id = "mobile_view">
                    <button onclick="onViewMobile(<?=$file_url?>);" class="btn btn-primary"style="background-color:#be3a94;letter-spacing: 8px;width: 230px;font-size:30px;margin-right: 10px;margin-bottom:20px">모바일보기</button>
                </div>
                <div style=" text-align: center;margin-top:30px;display:none;" id = "common_view">
                    <button onclick="onViewCommon(<?=$file_url?>);" class="btn btn-primary"style="background-color:#be3a94;letter-spacing: 8px;width: 230px;font-size:30px;margin-right: 10px;margin-bottom:20px">일반보기</button>
                </div>
                <div id="attached_content" style="height:100%;display:none">
                    <?php

                    $htmlPage = file_get_contents($surveys['file_url']);

                    if($htmlPage != false){
                        $startBodyPos = strpos($htmlPage, '<body>');
                        $endBodyPos = strpos($htmlPage, '</body>');
                        $startHeadPos = strpos($htmlPage, '<head>');
                        $endHeadPos = strpos($htmlPage, '</head>');
                        $content = substr($htmlPage, $startBodyPos + 6, $endBodyPos - $startBodyPos - 6);
                        $headContent = substr($htmlPage, $startHeadPos + 6, $endHeadPos - $startHeadPos - 6);
                        echo $headContent . $content;
                    }else{
                        echo "파일이 존재하지 않습니다.";
                    }
                    ?>
                    <div id="areaForPDF"></div>

                </div>

                <div style=" text-align: center;margin-top:30px;display:none;" id = "enterSurvey">
                    <button onclick="invite();" class="btn btn-primary"style="background-color:#be3a94;letter-spacing: 8px;width: 130px;margin-right: 10px;margin-bottom:20px">설문참여</button>
                </div>
                <script>
                    $(function () {

                        if ($('#page-container').length) {
                            $("#docKind").val("2"); //pdf변환파일이면
                            //settting viewport
                            $("meta[name='viewport']").attr("content", "width=360, initial-scale=0.6");
                            // $('head').append('<meta name="viewport" content="width=360, initial-scale=0.6" />');
                            $("#sidebar").remove();
                            //remove background
                            $('#page-container').css({'background-color' : '', 'background-image' : ''});
                            //replace new main area
                            $("#enterSurvey").appendTo($("#page-container"));
                            //insert enterSurvy button
                            $("#areaForPDF").html($("#page-container").html());
                            //replace remove old main area
                            $("#page-container").remove();
                            $("#mobile_view").hide();
                            $("#common_view").hide();
                        }else if($('.htOffice').length){
                            $("#docKind").val("1"); //hwp변환파일이면
                            //setting viewport
                            $("meta[name='viewport']").attr("content", "width=360, initial-scale=0.45");
                            $('body').css({'padding' :'0', 'margin:' : '0'});
                            $('.htOffice').css({'padding' :'0', 'margin:' : '0'});
                            $('.LOFooter').css({'padding' :'0', 'margin:' : '30px auto 30px auto'});
                            $('table').css({'margin-right:' : 'auto','margin-left:':'auto'});
                            //setting htOffice css
                            // $('.htOffice').css({'padding' :0, 'margin:' : '0'});
                        }

                        //show main area and button
                        $("#attached_content").show();
                        $("#enterSurvey").show();
                    });
                </script>
            </div>

            <?php
        }
        ?>
<!--        기본설문페지만들기-->
        <div id="preview-content" style = "display: none">
            <div class="preview-header">
                        <div class="survey-title-scope" id="survey-title-scope">
                            <p><?=$surveys['title']?></p>
                        </div>
                <div class="survey-comment-scope" id="survey-comment-scope">
                    <?php
                    if($surveys['review_infor']=="0"){
                        ?>
                        <p class="survey_desc">본 설문은 익명이 보장됩니다.</p>
                        <?php
                    } else if($surveys['review_infor']=="1"){
                        ?>
                        <input type="text" id="review_year" value="" size="5" style="margin-top: 10px;text-align: center;border: 2px solid;"><label style="    font-weight: 900; margin-right: 30px;margin-left: 5px;">학년</label> <input type="text" id="review_half" value="" size="5" style="    text-align: center;border: 2px solid;"><label style="    font-weight: 900;margin-right: 30px;margin-left: 5px;">반</label> <label style="font-weight: 900;margin-right: 5px;">이름</label><input type="text" id="review_name" value="" size="10" style="text-align: center;border: 2px solid;">
                        <?php
                    } else if($surveys['review_infor']=="2"){
                        ?>
                        <input type="text" id="review_half" value="" size="5" style="margin-top: 10px;text-align: center;border: 2px solid;"><label style="    font-weight: 900;margin-right: 30px;margin-left: 5px;">소속</label> <label style="font-weight: 900;margin-right: 5px;">이름</label><input type="text" id="review_name" value="" size="10" style="text-align: center;border: 2px solid;">
                        <?php
                    } else {
                        ?>
                        <label style="font-weight: 900;margin-right: 5px;margin-top: 10px;">이름</label><input type="text" id="review_name" value="" size="10" style="text-align: center;border: 2px solid;">
                        <?php
                    }
                    ?>
                </div>

            </div>

            <div class="question-content">

                <div class="question-scope" id="question-scope">
                    <?php
				
			            // print_r($questions);
                        $index=0;
                        $questions_total_count = sizeof($questions);
                        foreach($questions as $question) {
                        $index=$index+1;
                            $condition_text = "";
                    ?>
                    <div  class="question-index" style="display:none">
                        <?php
                        if($question['allow_reply_response']==="1") {
                            $condition_text .= "(복수선택가능)";
                        }
                        // if($question['allow_unselect']==="1") {
                        //     $condition_text .= "(미선택가능)";
                        // }
                        if($question['allow_random_align']==="1") {
                            $condition_text .= "(보기순서 임의배열)";
                        }
                        if($condition_text =="") {
                            ?>

                            <h4><?= $index ?>/<?= $questions_total_count ?>. <?= $question['question'] ?></h4>
                            <?php
                        } else {
                        ?>
                            <h4><?= $index ?>/<?= $questions_total_count ?>. <?= $question['question'] ?><br>&nbsp;&nbsp;&nbsp;<span
                                        style="font-size: 28px;color:#ff0000;"><?= $condition_text ?></span></h4>
                        <?php
                        }
                        ?>

                        <input id="type" type="hidden" value="<?=$question['type']?>">
                        <input id="type_grade" type="hidden" value="<?=$question['type_grade']?>">
                        <input id="reply_response" type="hidden" value="<?=$question['allow_reply_response']?>">
                        <input id="allow_unselect" type="hidden" value="<?=$question['allow_unselect']?>">
                        <input id="example_count" type="hidden" value="<?=$question['example_count']?>">
                        <input id="use_other_input" type="hidden" value="<?=$question['use_other_input']?>">
                        <?php
                            if(!empty($question['question_img_url'])) {
                        ?>

                                    <img src="<?=$site_url?>survey/thumb/<?=$question['question_img_url']?>" alt=" " class="img-responsive zoom-img" style="    width: 540px; margin-left: 20px;">


                            <?php
                            }
                            $example_index=0;
                            if ( $question['type'] == 0) { //객관식
                                // shuffle($question['examples']);
                                if($question['allow_reply_response']==1) {   //증복응답허용일때
                                    foreach($question['examples'] as $example) {
                                        $example_index=$example_index+1;
                            ?>
                                        <label><?=$example_index?> ) <input type='checkbox' name='question_<?=$index?>' value='<?=$example['title']?>'><?=$example['title']?></label>
                                  <?php
                                        if(!empty($example['img_url'])) {
                                            ?>
                                            <img src="<?= $site_url ?>survey/thumb/<?= $example['img_url'] ?>"
                                                 alt=" " class="img-responsive zoom-img" style="    width: 540px;margin-left: 20px;">
                                            <?php
                                        }
                                    }
                                } else {  //증복응답을 허용하지않을때
                                    foreach ($question['examples'] as $example) {
                                        $example_index = $example_index + 1;
                                        ?>
                                        <label><?=$example_index?>) <input type='radio' name='question_<?=$index?>'
                                                                             value='<?= $example['title'] ?>' role='<?=$example['question_move']?>' isChecked="false" style = "width:25px;height: 25px; margin-top:0"><?= $example['title'] ?>
                                        </label>
                                        <?php
                                        if (!empty($example['img_url'])) {
                                            ?>
                                            <img src="<?= $site_url ?>survey/thumb/<?= $example['img_url'] ?>"
                                                 alt=" " class="img-responsive zoom-img" style="    width: 540px;margin-left: 20px;">
                                            <?php
                                        }
                                    }
                                    if($question['use_other_input'] ==="1") {
                                            ?>
                                         <label><?=$example_index+1?>)<input type='radio' id='other_input_<?=$index?>' name='question_<?=$index?>' value='' role='1' isChecked="false" style = "width:25px;height: 25px; margin-top:0">기타 <input type="text" name='question_other_<?=$index?>' onkeyup="set_radio_value(<?=$index?>)" onclick="other_input_check(<?=$index?>)" value="" class="example-input-text-1"></label>
                                        <?php
                                    }
                                }
                            } else if ( $question['type'] == 1) { //주관식

                                for($i=0; $i < $question['example_count']; $i++) {
                                    $example_index=$i+1;

                        ?>
                                  <label><?=$example_index?>) <input type='text' name='question2_<?=$i?>' value='' class='example-input-text'></label>
                            <?php
                                }
                            ?>
                                <input id="question2_count" type="hidden" value="<?=$question['example_count']?>">
                                <input type="hidden" name="end_comment_index_<?=$index?>" value="<?=$question['end_comment_index']?>">
                        <?php
                            } else if ($question['type'] == 2) { //만족도


                                $exam_count = $question['example_count'];
                                $fav_grades = array();
                                if($question['rating_names'] != null){
                                    $fav_grades = explode(',',$question['rating_names']);
                                }else{
                                    if($exam_count == 5)
                                        $fav_grades=['매우 불만족','불만족','보통','만족','매우 만족'];
                                    else
                                        $fav_grades=['불만족','보통','만족'];
                                }
                                $star_section = "";
                                $star_header="<table class='table-star'><thead class='table-head'><tr>";


                                if($question['example_count']==3) {
                                    $width_rate=33;
                                }else {
                                    $width_rate=20;
                                }
                                ?>
                                <form>
                                    <?php
                                    if ($question['type_grade'] == 0) { //별형
                                        ?>
                                    <fieldset class="starRating" style = "heigth:30px;width:100%;overflow: hidden">
                                    <?php
                                        }else { //원형
                                    ?>
                                    <fieldset class="circleRating" style = "heigth:30px;width:100%;overflow: hidden">
                                    <?php
                                     } 

                                        //문항이동값얻기
                                        $move_value = $question['rating_move_value'];
                                        $lst_move_value = [0,0,0,0,0];
                                        if($move_value != null){
                                            $lst_move_value = explode(",",$move_value);
                                        }
                                        
                                        if($question['type_grade']==0) { //별형
                                            if($exam_count ==="5") {
                                                $itemWidth = 100 / $exam_count;
                                                $j = $exam_count;
                                                $checked_prop = "checked";
                                                for ($i = $exam_count; $i > 0; $i--) {
                                                    $star_header .= "<td class='matrix-col-label' style='width:" . $width_rate . "%'>" . $fav_grades[$j - $i] . "</td>";

                                                    $star_section .= "<input class='star-input' id='".$index."_rating". $i . "' type='radio' name='rating' role = '".$lst_move_value[$i - 1]."' value='". $fav_grades[$i - 1] ."' ".$checked_prop.">";
                                                    $star_section .= "<label class='star-label' for='".$index."_rating". $i . "' style = 'width:" . $itemWidth . "%'></label>";

                                                    $checked_prop = "";
                                                }
                                            } else if($exam_count ==="3") {
                                                $itemWidth = 100 / $exam_count;
                                                $j = $exam_count;
                                                $checked_prop = "checked";
                                                for ($i = $exam_count; $i > 0; $i--) {
                                                    $star_header .= "<td class='matrix-col-label' style='width:" . $width_rate . "%'>" . $fav_grades[$j - $i] . "</td>";

                                                    $star_section .= "<input class='star-input' id='".$index."_rating". $i . "' type='radio' name='rating' role = '".$lst_move_value[$i - 1]."' value='". $fav_grades[$i - 1] ."' ".$checked_prop.">";
                                                    $star_section .= "<label class='star-label' for='".$index."_rating". $i . "' style = 'width:" . $itemWidth . "%'></label>";

                                                    $checked_prop = "";
                                                }
                                            }
                                            $star_header.="</tr></thead></table>";
                                            ?>
                                            <?=$star_header?>
                                            <?=$star_section?>
                                            <?php
                                        }else  if($question['type_grade']==1) {  //원형
                                            if($exam_count ==="5") {
                                                $itemWidth = 100 / $exam_count;
                                                $j = $exam_count;
                                                $checked_prop = "checked";
                                                for ($i = $exam_count; $i > 0; $i--) {
                                                    $star_header .= "<td class='matrix-col-label' style='width:" . $width_rate . "%'>" . $fav_grades[$j - $i] . "</td>";

                                                    $star_section .= "<input class='circle-label' id='".$index."_rating". $i . "' type='radio' name='rating' role = '".$lst_move_value[$i - 1]."' value='". $fav_grades[$i - 1] ."' ".$checked_prop.">";
                                                    $star_section .= "<label class='circle-label' for='".$index."_rating". $i . "' style = 'width:" . $itemWidth . "%'></label>";

                                                    $checked_prop = "";
                                                }
                                            }else if($exam_count==="3") {
                                                $itemWidth = 100 / $exam_count;
                                                $j = $exam_count;
                                                $checked_prop = "checked";
                                                for ($i = $exam_count; $i > 0; $i--) {
                                                    $star_header .= "<td class='matrix-col-label' style='width:" . $width_rate . "%'>" . $fav_grades[$j - $i] . "</td>";

                                                    $star_section .= "<input class='circle-label' id='".$index."_rating". $i . "' type='radio' name='rating' role = '".$lst_move_value[$i - 1]."' value='". $fav_grades[$i - 1] ."' ".$checked_prop.">";
                                                    $star_section .= "<label class='circle-label' for='".$index."_rating". $i . "' style = 'width:" . $itemWidth . "%'></label>";

                                                    $checked_prop = "";
                                                }
                                            }
                                            $star_header.="</tr></thead></table>";
                                            ?>
                                            <?=$star_header?>
                                            <?=$star_section?>
                                            <?php
                                        }else { //슬라이더형
                                            ?>
                                             <input class="slider_count" type="hidden" value="<?=$exam_count?>">

                                            <?php
                                            if($exam_count ==="5") {
                                                for ($i = $exam_count; $i > 0; $i--) {
                                                    $star_header .= "<td class='matrix-col-label' style='width:" . $width_rate . "%'>" . $fav_grades[$i - 1] . "</td>";

                                                }
                                            } else {
                                                for ($i = $exam_count; $i > 0; $i--) {
                                                    $star_header .= "<td class='matrix-col-label' style='width:" . $width_rate . "%'>" . $fav_grades[$i] . "</td>";

                                                }
                                            }
                                            $star_header.="</tr></thead></table>";
                                            ?>
                                            <?=$star_header?>
                                            <div class="example-slider">
                                                <div class="pre-slider-container">
                                                    <input id = "slider_<?=($index-1)?>" class="slider_<?=($index-1)?>" type="text" value="">
                                                    <div class="example-fav-slider_<?=($index-1)?> m-nouislider m-nouislider--handle-danger"></div>
                                                </div>
                                            </div>
                                        <?php
                                        }
                                        $star_index++;

                                     ?>
                                 </fieldset>
                                 <!-- <div style = "font-size: 20px; margin-left: 25px;">
                                    1,2번으로 답하실 경우 불만족한 이유가 무엇입니까?
                                    <textarea class="teacher_satisfy_reason form-control"  style="resize: none"></textarea>
                                 </div> -->
                                </form>
                            <?php
                            }
                            else { // 강사만족도
                                $exam_count = $question['example_count'];
                                $fav_grades = array();
                                if($question['rating_names'] != null){
                                    $fav_grades = explode(',',$question['rating_names']);
                                }else{
                                    if($exam_count == 5)
                                        $fav_grades=['매우 불만족','불만족','보통','만족','매우 만족'];
                                    else
                                        $fav_grades=['불만족','보통','만족'];
                                }

                                ?>

                                <table style="border-collapse: collapse;border: 1px solid rgb(167, 167, 167);width: 100%;table-layout: fixed; ">
                                    <tbody>

                                <?php
                                foreach($question['teacher_marks'] as $teacher_marks_point) {
                                ?>
                                    <tr style="font-size: 22px;text-align: center;border: 1px solid #b3b2b2">
                                        <td style="width: 22%;background: #eaeaea;font-weight: bold;padding:8px;word-wrap:break-word;"><?=$teacher_marks_point['title']?></td>
                                        <td style="padding:8px;width:78%;word-wrap:break-word;text-align:left"><?=$teacher_marks_point['content']?></td>
                                    </tr>                                
                                <?php    
                                }
                                ?>
                                    </tbody>
                                </table>

                                <?php
                                if ($question['example_count']==3) {
                                    $width_rate=33;
                                }else {
                                    $width_rate=20;
                                }

                                $teacher_index = 0;
                                foreach($question['teachers'] as $teacher) {
                                ?>
                                    <div class="teacher_id" teacher_id="<?=$teacher['id']?>" style="font-size: 24px;margin: 10px 0;padding: 7px;background: #eaeaea;">
                                <?php
                                    if ($teacher['profile'] != '') {
                                        ?>
                                        <img src="<?=$teacher['profile']?>" style="width: 150px;">
                                <?php
                                    }
                                ?>
                                        &lt; <?=$teacher['title']?> &gt;
                                 
                                <?php
                                    $teacher_index = $teacher_index + 1;
                                    $teacher_check_index = 0;                                    
                                    foreach($question['teacher_marks'] as $teacher_marks_point) {
                                        $teacher_check_index = $teacher_check_index + 1;

                                        $star_section = "";
                                        $star_header = "<table class='table-star'><thead class='table-head'><tr>";
                                ?>                                
                                                             
                                <div class="question_exam_kinds_id" question_exam_kinds_id="<?=$teacher_marks_point['id']?>" style="font-size: 20px;margin-bottom: 5px;margin-left: 10px;">- <?=$teacher_marks_point['title']?>
                                    <form style="background: white;">
                                    <?php
                                        if ($question['type_grade'] == 0) { //별형
                                            ?>
                                        <fieldset class="starRating" style = "heigth:30px;width:100%;overflow: hidden">
                                        <?php
                                            }else { //원형
                                        ?>
                                        <fieldset class="circleRating" style = "heigth:30px;width:100%;overflow: hidden">
                                        <?php 
                                         }
                                            if($question['type_grade']==0) { //별형
                                                if($question['example_count'] ==="5") {
                                                    $itemWidth = 100 / $question['example_count'];
                                                    $j = $question['example_count'];
                                                    $checked_prop = "checked";
                                                    for ($i = $question['example_count']; $i > 0; $i--) {
                                                        $star_header .= "<td class='matrix-col-label' style='width:" . $width_rate . "%'>" . $fav_grades[$j-$i] . "</td>";

                                                        $star_section .= "<input class='star-input' id='".$index."_".$teacher_index."_".$teacher_check_index."_rating". $i . "' type='radio' name='rating' value='". $fav_grades[$i - 1] ."' ".$checked_prop.">";
                                                        $star_section .= "<label class='star-label' for='".$index."_".$teacher_index."_".$teacher_check_index."_rating". $i . "' style = 'width:" . $itemWidth . "%'></label>";

                                                        $checked_prop = "";
                                                    }
                                                } else if($question['example_count'] ==="3") {
                                                    $itemWidth = 100 / $question['example_count'];
                                                    $j = $question['example_count'];
                                                    $checked_prop = "checked";
                                                    for ($i = $question['example_count']; $i > 0; $i--) {
                                                        $star_header .= "<td class='matrix-col-label' style='width:" . $width_rate . "%'>" . $fav_grades[$j-$i] . "</td>";

                                                        $star_section .= "<input class='star-input' id='".$index."_".$teacher_index."_".$teacher_check_index."_rating". $i . "' type='radio' name='rating' value='". $fav_grades[$i - 1] ."' ".$checked_prop.">";
                                                        $star_section .= "<label class='star-label' for='".$index."_".$teacher_index."_".$teacher_check_index."_rating". $i . "' style = 'width:" . $itemWidth . "%'></label>";

                                                        $checked_prop = "";
                                                    }
                                                }
                                                $star_header.="</tr></thead></table>";
                                                ?>
                                                <?=$star_header?>
                                                <?=$star_section?>
                                                <?php
                                            }else  if($question['type_grade']==1) {  //막대기형
                                                if($question['example_count'] ==="5") {
                                                    $itemWidth = 100 / $question['example_count'];
                                                    $j = $question['example_count'];
                                                    $checked_prop = "checked";
                                                    for ($i = $question['example_count']; $i > 0; $i--) {
                                                        $star_header .= "<td class='matrix-col-label' style='width:" . $width_rate . "%'>" . $fav_grades[$j-$i] . "</td>";

                                                        $star_section .= "<input class='circle-label' id='".$index."_".$teacher_index."_".$teacher_check_index."_rating". $i . "' type='radio' name='rating' value='". $fav_grades[$i - 1] ."' ".$checked_prop.">";
                                                        $star_section .= "<label class='circle-label' for='".$index."_".$teacher_index."_".$teacher_check_index."_rating". $i . "' style = 'width:" . $itemWidth . "%'></label>";

                                                        $checked_prop = "";
                                                    }
                                                }else if($question['example_count'] ==="3") {
                                                    $itemWidth = 100 / $question['example_count'];
                                                    $j = $question['example_count'];
                                                    $checked_prop = "checked";
                                                    for ($i = $question['example_count']; $i > 0; $i--) {
                                                        $star_header .= "<td class='matrix-col-label' style='width:" . $width_rate . "%'>" . $fav_grades[$j-$i] . "</td>";

                                                        $star_section .= "<input class='circle-label' id='".$index."_".$teacher_index."_".$teacher_check_index."_rating". $i . "' type='radio' name='rating' value='". $fav_grades[$i - 1] ."' ".$checked_prop.">";
                                                        $star_section .= "<label class='circle-label' for='".$index."_".$teacher_index."_".$teacher_check_index."_rating". $i . "' style = 'width:" . $itemWidth . "%'></label>";

                                                        $checked_prop = "";
                                                    }
                                                }
                                                $star_header.="</tr></thead></table>";
                                                ?>
                                                <?=$star_header?>
                                                <?=$star_section?>
                                                <?php
                                            }else { //슬라이더형
                                                ?>
                                                <input class="slider_count" type="hidden" value="<?=$question['example_count']?>">

                                                <?php
                                                if($question['example_count'] ==="5") {
                                                    for ($i = $question['example_count']; $i > 0; $i--) {
                                                        $star_header .= "<td class='matrix-col-label' style='width:" . $width_rate . "%'>" . $fav_grades[$i - 1] . "</td>";

                                                    }
                                                } else {
                                                    for ($i = $question['example_count']; $i > 0; $i--) {
                                                        $star_header .= "<td class='matrix-col-label' style='width:" . $width_rate . "%'>" . $fav_grades[$i] . "</td>";

                                                    }
                                                }
                                                $star_header.="</tr></thead></table>";
                                                ?>
                                                <?=$star_header?>
                                                <div class="example-slider">
                                                    <div class="pre-slider-container">
                                                        <input id = "slider_<?=($index-1)?>" class="slider_<?=($index-1)?>" type="text" value="">
                                                        <div class="example-fav-slider_<?=($index-1)?> m-nouislider m-nouislider--handle-danger"></div>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                            $star_index++;

                                        ?>
                                    </fieldset>                                
                                    </form>                                    
                                </div>
                            <?php                                    
                                    }
                                    echo ('<label style="font-size:20px;margin:5px 0 5px 10px">&nbsp;&nbsp;강사에게 하고싶은 말<br>');
                                    echo ('<textarea rows = "4" name="teacher_desc" value="" style="width: 100%;font-size:24px;margin:15px 5px 0 10px;border:1px solid"></textarea></label>');
                                echo ('</div>');                                    
                                }
                            }
                            ?>    

                        <div style = "margin:20px 0">
                            <div class="separate"></div>
                            <div class="separate"></div>
                        </div>
                    </div>
                        <?php
                        }

                        ?>

                    </div>

                </div>
            <div class="button_save" id ="button_save" style="text-align: center; margin-top: 19px;">
                <button onclick="previewClose_check();" class=" btn_auth_ok btn" style=" letter-spacing: 8px;margin-right: 10px;margin-bottom:20px">설문완료</button>
            </div>
            <div class="preview-footer">
                <div class="survey-title-scope" id="survey-title-scope">
                    <p>감사합니다.</p>
                </div>
            </div>
            <div style = "width:100%;text-align:center">
                <button onclick="window.close();" class="btn " id = "btn_exitBrower" style="display:none;font-size: 32px; font-weight: bold;height: 70px; width: 30%;letter-spacing: 8px;margin-top: 70px;margin-bottom: 70px;background-color: #be3a94;">돌아가기</button>
            </div>
            
            <div style="text-align: center; margin-top: 19px; display: none" id="button_next">
                <button onclick="before();" class="btn " style="font-size: 26px; font-weight: bold;height: 70px; width: 30%;letter-spacing: 8px;margin-right: 10px;margin-bottom:20px;background-color: #be3a94;">< 이전</button>
                <button onclick="next();" class="btn " style="font-size: 26px; font-weight: bold; height: 70px; width: 30%;letter-spacing: 8px;margin-right: 10px;margin-bottom:20px;background-color: #be3a94;">다음 ></button>
            </div>
            <div  style="    text-align: center; margin-top: 50px;margin-bottom:50px; font-size: 45px;color: #ffffff;">
                <?php
                $i = 1;
                foreach ($advert_result as $item) {
                    ?>
                    <button id ="advert_link_<?=$i?>" style="display: none; background:#<?=$item['background']?>; width: 100%; padding-top:20px;padding-bottom: 20px;" onclick="advert_link('<?=$item['link_url']?>','<?=$item['id']?>');"><?=$item['advert_title']?></button>
                    <?php
                    $i = $i+1;
                }
                ?>
                <input id="link_count" type="hidden" value="<?=$i-1?>">
            </div>
            <?php
            }
            ?>
            </div>



