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

    ?>
        <input id="file_url" type="hidden" value="<?= $surveys['file_url'] ?>">
        <input id="survey_id" type="hidden" value="<?= $survey_id ?>">
        <input id="error" type="hidden" value="<?= $error ?>">
        <?php
        $index=0;

        foreach($end_comments as $comment) {
            $index +=1;
            ?>
            <input id="comment_<?= $index ?>" type="hidden" value="<?= $comment['content'] ?>">
            <?php

        }
            ?>

        <input id="question_count_page" type="hidden" value="<?= $surveys['question_count_page'] ?>">
        <input id="question_count" type="hidden" value="<?= $surveys['question_count'] ?>">
<!--    문서가 첨부되었으면 / 첨부문서구역에 대한 보기속성설정-->
        <?php
        if($surveys['attached']==1) {
            ?>

            <div class="attached_area">
                <div id="attached_content" style="height:100%;display:none">
                    <?php
                    $htmlPage = file_get_contents($surveys['file_url']);
                    $startBodyPos = strpos($htmlPage, '<body>');
                    $endBodyPos = strpos($htmlPage, '</body>');
                    $startHeadPos = strpos($htmlPage, '<head>');
                    $endHeadPos = strpos($htmlPage, '</head>');
                    $content = substr($htmlPage,$startBodyPos + 6,$endBodyPos - $startBodyPos - 6);
                    $headContent = substr($htmlPage,$startHeadPos + 6,$endHeadPos - $startHeadPos - 6);
                    echo $headContent.$content;
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

                        }else if($('.htOffice').length){
                            $("#docKind").val("1"); //hwp변환파일이면
                            //setting viewport
                            $("meta[name='viewport']").attr("content", "width=360, initial-scale=0.45");
                            // $('head').append('<meta name="viewport" content="width=360, initial-scale=0.45" />');
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
                            <p>본 설문은 익명이 보장됩니다.</p>
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
                            if($question['allow_unselect']==="1") {
                                $condition_text .= "(미선택가능)";
                            }
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
                        <?php
                            if(!empty($question['question_img_url'])) {
                        ?>

                                    <img src="<?=$site_url?>survey/thumb/<?=$question['question_img_url']?>" alt=" " class="img-responsive zoom-img" style="    width: 540px;margin-left: 20px;">


                            <?php
                            }
                            $example_index=0;
                            if ( $question['type'] == 0) { //객관식

                                shuffle($question['examples']);
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
                                                                             value='<?= $example['title'] ?>' role='<?=$example['question_move']?>' isChecked="false"><?= $example['title']  ?>
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
                                        <label><?=$example_index+1?>)<input type='radio' id='other_input_<?=$index?>' name='question_<?=$index?>' value='' role='1' isChecked="false">기타 <input type="text" name='question_other_<?=$index?>' onkeyup="set_radio_value(<?=$index?>)" onclick="other_input_check(<?=$index?>)" value="" class="example-input-text-1"></label>
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
                            }
                            else { //만족도
                                $fav_grades=['매우 만족','만족','보통','불만족','매우 불만족'];
                                $star_section = "";
//                                $thead_html="";
//                                $tbody_html="";
                                $star_header="<table class='table-star'><thead class='table-head'><tr>";


                                if($question['example_count']==3) {
                                    $width_rate=33;
                                }else {
                                    $width_rate=20;
                                }
                                ?>
                                <form>
                                 <fieldset class="starRating_<?=$index?>" style = "heigth:30px;width:100%;overflow: hidden">

                                    <?php
                                        if($question['type_grade']==0) { //별형
                                            if($question['example_count'] ==="5") {
                                                $itemWidth = 100 / $question['example_count'];
                                                $j = $question['example_count'];
                                                for ($i = $question['example_count']; $i > 0; $i--) {
                                                    $star_header .= "<td class='matrix-col-label' style='width:" . $width_rate . "%'>" . $fav_grades[$i - 1] . "</td>";

                                                    $star_section .= "<input class='star-input' id='".$index."_rating". $i . "' type='radio' name='rating' value='". $fav_grades[$j-$i] ."'>";
                                                    $star_section .= "<label class='star-label' for='".$index."_rating". $i . "' style = 'width:" . $itemWidth . "%'><i class='fas fa-star example-fav'></i></label>";
                                                }
                                            } else if($question['example_count'] ==="3") {
                                                $itemWidth = 100 / $question['example_count'];
                                                $j = $question['example_count'];
                                                for ($i = $question['example_count']; $i > 0; $i--) {
                                                    $star_header .= "<td class='matrix-col-label' style='width:" . $width_rate . "%'>" . $fav_grades[$i] . "</td>";

                                                    $star_section .= "<input class='star-input' id='".$index."_rating". $i . "' type='radio' name='rating' value='". $fav_grades[4-$i] ."'>";
                                                    $star_section .= "<label class='star-label' for='".$index."_rating". $i . "' style = 'width:" . $itemWidth . "%'><i class='fas fa-star example-fav'></i></label>";
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
                                                for ($i = $question['example_count']; $i > 0; $i--) {
                                                    $star_header .= "<td class='matrix-col-label' style='width:" . $width_rate . "%'>" . $fav_grades[$i - 1] . "</td>";

                                                    $star_section .= "<input class='star-input' id='".$index."_rating". $i . "' type='radio' name='rating' value='". $fav_grades[$j-$i] ."'>";
                                                    $star_section .= "<label class='star-label' for='".$index."_rating". $i . "' style = 'width:" . $itemWidth . "%'><i class='fas fa-window-minimize example-fav'></i></label>";
                                                }
                                            }else if($question['example_count'] ==="3") {
                                                $itemWidth = 100 / $question['example_count'];
                                                $j = $question['example_count'];
                                                for ($i = $question['example_count']; $i > 0; $i--) {
                                                    $star_header .= "<td class='matrix-col-label' style='width:" . $width_rate . "%'>" . $fav_grades[$i] . "</td>";

                                                    $star_section .= "<input class='star-input' id='".$index."_rating". $i . "' type='radio' name='rating' value='". $fav_grades[4-$i] ."'>";
                                                    $star_section .= "<label class='star-label' for='".$index."_rating". $i . "' style = 'width:" . $itemWidth . "%'><i class='fas fa-window-minimize example-fav'></i></label>";
                                                }
                                            }
                                            $star_header.="</tr></thead></table>";
                                            ?>
                                            <?=$star_header?>
                                            <?=$star_section?>
                                           <!-- <?php
/*                                        }else { //슬라이더형
                                            $fav_grades=['매우 만족','만족','보통','불만족','매우 불만족','선택하지않음'];
                                            */?>
                                             <input class="slider_count" type="hidden" value="<?/*=$question['example_count']*/?>">

                                            <?php
/*                                            if($question['example_count'] ==="5") {
                                                for ($i = $question['example_count']; $i > 0; $i--) {
                                                    $star_header .= "<td class='matrix-col-label' style='width:" . $width_rate . "%'>" . $fav_grades[$i - 1] . "</td>";

                                                }
                                            } else {
                                                for ($i = $question['example_count']; $i > 0; $i--) {
                                                    $star_header .= "<td class='matrix-col-label' style='width:" . $width_rate . "%'>" . $fav_grades[$i] . "</td>";

                                                }
                                            }
                                            $star_header.="</tr></thead></table>";
                                            */?>
                                            <?/*=$star_header*/?>
                                            <div class="example-slider">
                                                <div class="pre-slider-container">
                                                    <input id = "slider_<?/*=($index-1)*/?>" class="slider_<?/*=($index-1)*/?>" type="text" value="">
                                                    <div class="example-fav-slider_<?/*=($index-1)*/?> m-nouislider m-nouislider--handle-danger"></div>
                                                </div>
                                            </div>-->
                                        <?php
                                        }

                                     ?>



                                 </fieldset>
                                </form>
                            <?php
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
            <div class="preview-footer">
                <div style="text-align: center; margin-top: 19px;" id ="button_save">
                    <button  class=" btn_auth_ok btn" onclick="previewClose_check()" style=" letter-spacing: 8px;margin-right: 10px;margin-bottom:20px">설문완료</button>


                </div>

            </div>



                <div style="text-align: center; margin-top: 19px; display: none" id="button_next">
                    <button onclick="next();" class="btn_auth_ok btn " style=" letter-spacing: 8px;margin-right: 10px;margin-bottom:20px">다음</button>
                </div>


        </div>


