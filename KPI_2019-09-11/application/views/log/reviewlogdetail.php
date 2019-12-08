<?php
/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 2/29/2016
 * Time: 9:47 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
$site_url = site_url();
$resultExcel = [];
$resultQuestionExcel = [];
$resultExcelItem = [];
$total_count=sizeof($questions);

$begin_date = $education['begin_date'];
$end_date = $education['end_date'];

if (strstr($begin_date, "-") == FALSE) {
    $begin_date = substr($begin_date, 0, 4)."-".substr($begin_date, 4, 2)."-".substr($begin_date, 6, 2);
}
if (strstr($end_date, "-") == FALSE) {
    $end_date = substr($end_date, 0, 4)."-".substr($end_date, 4, 2)."-".substr($end_date, 6, 2);
}

?>

<div class="container container-bg">
    <div id="content">
        <div id="contents">
            <div class="sub_con">
                <div class="sub_title1"><img src="<?= $site_url ?>images/icon_title.png">설문조사결과</div>
<!--                미응답자들에 대한 <다시보내기>기능을 위해 자료건사하기-->
                <input id="message_content" type="hidden" value="<?=$survey['content']?>">                
                <input id="message_noticeId" type="hidden" value="<?=$survey['noticeId']?>">
                <input id="message_calling_number" type="hidden" value="<?=$survey['calling_number']?>">
                <div class="review_detailheader" style="text-align: center;">

                    <ul>
                        <li>
                            <button class="btn btn-default" onclick="onToReviewListClick(<?=$survey_flag?>, '<?=$parent?>')">목 록</button>
                        </li>
                        <!--                        <li>-->
                        <!--                            <button class="btn btn-default" onclick="onShowNoResponseClick()" id="btn_noResponse">-->
                        <!--                                미응답자전화번호-->
                        <!--                            </button>-->
                        <!--                        </li>-->
                        <!-- <li>
                            <button class="btn btn-default" onclick="onShowMainDetailClick()" id="btn_mainDetail"
                                    style="display: none;">기본상세통계
                            </button>
                        </li> -->
                        <li>
                            <button class="btn btn-default" onclick="onExcelClick()">Excel로 내려받기</button>
                        </li>
                    </ul>
                </div>
<!--                설문조사통계테블의 Header부-->
                <div style="    padding: 20px;">
                    <table class="review_title_scope">
                        <tr>
                            <th colspan="8"><?= $education['subject_name'] ?></th>
                        </tr>
                        <tr>
                            <td style="width:10%;background: #bbb8b8;">부서명</td>
                            <td style="width:20%"><?=$user_group?></td>
                            <td style="width:10%;background: #bbb8b8;">담당자</td>
                            <td style="width:10%"><?=$user_name?></td>
                            <td style="width:10%;background: #bbb8b8">차수</td>
                            <td style="width:7%"><?=$education['count_name']?></td>
                            <td style="width:10%;background: #bbb8b8">교육일자</td>
                            <td style="width:25%"><?=$begin_date?> ~ <?=$end_date?></td>
                        </tr>
                        <tr>
                            <td style="width:10%;background: #bbb8b8;">발송수</td>
                            <td style="width:20%"><?=$survey['mobile_count']?></td>
                            <td style="width:10%;background: #bbb8b8">응답수</td>
                            <td style="width:10%"><?=$total_response_count?> (<?php $rate = $total_response_count*100/$survey['mobile_count']; echo floor($rate*100)/100;?>%)</td>
                            <td style="width:10%;background: #bbb8b8">인원</td>
                            <td style="width:7%"><?=$education['student_count']?></td>
                            <td style="width:10%;background: #bbb8b8;">설문일자</td>
                            <td style="width:25%"><?=substr($survey['start_time'],0,10)?> ~ <?=substr($survey['end_time'],0,10)?></td>
                        </tr>
                    </table>
                </div>

                <!-- <div class = "whiteBand"></div>-->
                <div id="review_mainDetail" style="    padding: 0px 20px;" >
                    <?php
                    $n = 0;
                    $CountSum = "";
                    $resultQuestionExcel['title'] = $education['subject_name'];
                    $resultQuestionExcel['group'] = $user_group;
                    $resultQuestionExcel['man'] = $user_name;
                    $resultQuestionExcel['count'] = $education['count_name'];
                    $resultQuestionExcel['edu_date'] = $begin_date . '~' . $end_date;
                    $resultQuestionExcel['mobile_count'] = $survey['mobile_count'];
                    $resultQuestionExcel['response_count'] = $total_response_count . '(' . floor($rate*100)/100 . '%)';
                    $resultQuestionExcel['student_count'] = $education['student_count'];
                    $resultQuestionExcel['survey_date'] = substr($survey['start_time'],0,10) . '~' . substr($survey['end_time'],0,10);
                    
                    $resultExcel['title']=$resultQuestionExcel;

                    foreach ($questions as $item):
                        $n++;
                        $resultQuestion = $allReview['q' . $n];
                        $resultQuestionExcel_item = [];                        

                        ?>
                        <!--질문제목-->
                        <div class="review_question_scope">
                            <div class="review_detailQuestion">
                                <div class="element1">
                                    <span><?= $n."/".$total_count . " " . $item['question'] ?></span>
                                        <!--**********표만들기-->
                             
                                    
                                </div>

                                <div class="element2" >
                                    <!-- <ul>
                                        <li>       <?php
                                    $resultQuestionExcel = [];array_push($resultQuestionExcel,$n."/".$total_count . " " . $item['question']);
                                    ?>
                                        </li>
                                    </ul> -->
                                </div>
                            </div>
                                                        
                            <div class="review_questionHeader">
                                <div class="element1" style="text-align: center;width:80%">
                                    <span>보
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    기</span>
                                </div>
                                <!-- <div class="element2" >
                                    <ul>
                                        <li>
                                            <span class="responseResult"></span>
                                        </li>
                                    </ul>
                                </div> -->
                                <!-- <div class="element2" >
                                    <ul>
                                        <li>
                                            <span class="responseResult">응답자</span>
                                        </li>
                                    </ul>
                                </div> -->
                                <div class="element2" >
                                    <ul>
                                        <li>
                                            <span class="responseResult">응답자수</span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="element2" >
                                    <ul>
                                        <li>
                                           <?php if($item['type'] != 1) {
                                           ?>
                                                <span class="responseResult"><?= $item['type'] > 1 ? '배점':'비율'?></span>
                                           <?php } ?>
                                        </li>
                                    </ul>
                                </div>

                            </div>
                            <!--질문에 대한 해답항목-->
                            <?php
                                $tcp_example_count = 0;
                            ?>

                            <?php
                            //옵션형식의 중복응답을 위한 변수
                                $total_response_count_including_duplicate = 0;
                            //옵션형식인 경우
                            if ($item['type'] == 0) {
                                array_push($resultQuestionExcel,"0");
                                $tcp_example_count = $tcp_example_count+sizeof($item['examples']);
                                $m = 0;
                                //옵션형식에 응답한 전체 응답수 얻기
                                foreach ($item['examples'] as $example){
                                    $m++;
                                    $resultExample = $resultQuestion['e' . $m];   
                                    $total_response_count_including_duplicate += sizeof($resultExample);    
                                    
                                }
                                if($item['use_other_input']==="1") {//  기타입력사용인경우 전체응답수에 합하기
                                    $total_response_count_including_duplicate += sizeof($resultQuestion['e기타']);
                                }                              

                                $m = 0;
                                foreach ($item['examples'] as $example):
                                    $m++;
                                    $resultExample = $resultQuestion['e' . $m];
                                    $response_name = "";
                                    foreach ($resultExample as $val){
                                        if ($val['response_man'] != "") {
                                            $response_name .= $val['response_man']."<br>";
                                        }                                        
                                    }
                                    //**********표만들기항목초기화
                                    $resultExcelItem = [];


                                    ?>
                                    <div class="review_detailExample">
                                        <div class="element1">
                                            <span><?= $m . ")&nbsp;&nbsp;&nbsp;" . $example['title'] ?></span>
                                            <!--**********표만들기 <제목>-->
                                            <?php array_push($resultExcelItem,$m . ")" . $example['title']); ?>
                                        </div>
                                        <div class="element2">
                                            <?php array_push($resultExcelItem,""); ?>
                                        </div>
                                        <div class="element2">
                                            <ul>
                                                <li>
                                                    <span class="responseResult"><?= $response_name ?></span>
                                                    <!--**********표만들기 <명>-->
                                                    <?php array_push($resultExcelItem,$response_name); ?>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="element2">
                                            <ul>
                                                <li>
                                                    <span class="responseResult"><?= sizeof($resultExample) ?>명</span>
                                                    <!--**********표만들기 <명>-->
                                                    <?php array_push($resultExcelItem,sizeof($resultExample)); ?>
                                                </li>
                                            </ul>

                                        </div>
                                        <div class="element2">
                                            <ul>
                                                <li>
                                                    <span class="responseResult"><?= $total_response_count_including_duplicate > 0 ? intval(sizeof($resultExample) * 100 / $total_response_count_including_duplicate) : 0 ?>
                                                        %</span>
                                                    <!--**********표만들기 <%>-->
                                                    <?php
                                                    $percent = $total_response_count_including_duplicate > 0 ? intval(sizeof($resultExample) * 100 / $total_response_count_including_duplicate) : 0 ;
                                                    array_push($resultExcelItem,$percent.'%'); ?>
                                                </li>
                                            </ul>
                                        </div>
                                        <input id="tq<?= $n ?>e<?= $m ?>" type="hidden" value="<?=$example['title']?>">
                                        <input id="cq<?= $n ?>e<?= $m ?>" type="hidden" value="<?=sizeof($resultExample)?>">
                                    </div>
                                    <!-- 기본내용과 구별구간-->
                                    <div class="exampleSeparate">

                                    </div>
                                        <!--표만들기 <행삽입>-->
                                        <?php array_push($resultQuestionExcel_item,$resultExcelItem);
                                        /*foreach ($resultExample as $resultItem):
                                            //표만들기 <이름>
    //                                        array_push($resultExcel,[$resultItem['name']]);
                                        endforeach;*/

                                        ?>
                                <?php endforeach;
                                if($item['use_other_input']==="1") {//  기타입력사용인경우
                                    $tcp_example_count = $tcp_example_count+1;
                                    $other_count = 0;
                                    $temp_title = "";
                                    $response_name = "";
                                    $resultOther = $resultQuestion['e기타'];
                                    if(!empty($resultOther) && sizeof($resultOther) > 0 ) {
                                        foreach ($resultOther as $otherItem) {
                                            $anser_text = $otherItem['answer'];
                                            $response_name .=$otherItem['response_man']."<br>";
                                            // $data = json_decode($anser_text, TRUE);

                                            $segment_pos = strpos($anser_text,'"'.$n.'":');
                                            $begin_pos = strpos($anser_text,'기타', $segment_pos);
                                            $end_pos = strpos($anser_text,'",', $begin_pos);
                                            if ($begin_pos > 0 && $end_pos > 0) {
                                                $answer_other_text = substr($anser_text, $begin_pos + 6, $end_pos - $begin_pos - 6);
                                                $temp_title .= $answer_other_text."<br>";
                                                $other_count++;
                                            }
                                        }
                                    }
                                    $m++;

                                    //**********표만들기항목초기화
                                    $resultExcelItem = [];

                                ?>
                                    <div class="review_detailExample">
                                        <div class="element1" style="width:20%">
                                            <span><?= $m . ") 기타" ?></span>
                                            <!--**********표만들기 <제목>-->
                                            <?php array_push($resultExcelItem,$m . ") 기타"); ?>
                                        </div>
                                        <div class="element2" style = "width:46%">
                                            <ul>
                                                <li>
                                                    <span class="responseResult"><?= $temp_title ?></span>
                                                    <!--**********표만들기 <명>-->
                                                    <?php array_push($resultExcelItem,$temp_title); ?>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="element2">
                                            <ul>
                                                <li>
                                                    <span class="responseResult"><?= $response_name ?></span>
                                                    <!--**********표만들기 <명>-->
                                                    <?php array_push($resultExcelItem,$response_name); ?>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="element2">
                                            <ul>
                                                <li>
                                                    <span class="responseResult"><?= $other_count ?>명</span>
                                                    <!--**********표만들기 <명>-->
                                                    <?php array_push($resultExcelItem,$other_count); ?>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="element2">
                                            <ul>
                                                <li>
                                                                <span class="responseResult"><?= $total_response_count > 0 ? intval($other_count * 100 / $total_response_count) : 0 ?>
                                                                    %</span>
                                                    <!--**********표만들기 <%>-->
                                                    <?php
                                                    $percent = $total_response_count > 0 ? intval($other_count * 100 / $total_response_count) : 0 ;
                                                    array_push($resultExcelItem,$percent.'%'); ?>
                                                </li>
                                            </ul>
                                        </div>
                                        <input id="tq<?= $n ?>e<?= $m ?>" type="hidden" value="기타">
                                        <input id="cq<?= $n ?>e<?= $m ?>" type="hidden" value="<?=$other_count?>">
                                    </div>
                                    <!-- 기본내용과 구별구간-->
                                    <div class="exampleSeparate">

                                    </div>
                                    <!--표만들기 <행삽입>-->
                                    <?php array_push($resultQuestionExcel_item,$resultExcelItem);
                                    foreach ($resultExample as $resultItem):
                                        //표만들기 <이름>
                //                                        array_push($resultExcel,[$resultItem['name']]);
                                    endforeach;
                                }                            
                            } else if ($item['type'] == 1) {
                                // 주관식인 경우(textbox창)
                                array_push($resultQuestionExcel,"1");
                                $m = 0;
                                $temp_array = array();
                                $i = 0;
                                $key_array = array();
                                $response_man = "";
                                $temp_1 = array();
                                $question_count = sizeof($resultQuestion);
                                                                
                                   /* foreach ($resultQuestion as $val) {
                                        $temp_name = $val['name'];
                                        $temp_text = $val['text'];
                                        $temp_count = 1;
                                        $temp_array = $resultQuestion;
                                        array_shift($temp_array);
                                        foreach ($temp_array as $val_temp) {
                                            if($val_temp['text']==$temp_text) {
                                                $temp_name .="<br>".$resultQuestion[$j]['name'];

                                                unset($resultQuestion[$j]);
                                                $question_count--;
                                                $temp_count ++;
                                            }
                                        }
                                    }*/
                                    
                                    $index = 0;
                                    $temp_count = 0;
                                    $resultQuestion = array_values($resultQuestion);
                                    $noselect_name = '';
                                    $noselect_response  = '';
                                    for ($i = 0; $i < $question_count; $i++) {
                                        $temp_text = $resultQuestion[$i]['text'];
                                        $temp_name = $resultQuestion[$i]['name'];                        
                                        $response_man = $resultQuestion[$i]['response_man'];
                                        if ($temp_text == "미선택") {
                                            $temp_count ++;
                                            if ($temp_name != "")
                                                $noselect_name .= $temp_name."<br>"; 
                                            if ($response_man != "")                                                
                                            $noselect_response .= $response_man."<br>";                                             
                                        }
                                    }
                                    if ($temp_count > 0) {
                                        $temp_1[$index] = array('name' => $noselect_name, 
                                        'text' => "미선택",
                                        'count'=>$temp_count,
                                        'response_man'=>$noselect_response);
                                        $index++;
                                    }

                                    for ($i = 0; $i < $question_count; $i++) {
                                        $temp_text = $resultQuestion[$i]['text'];
                                        $temp_name = $resultQuestion[$i]['name'];                        
                                        $response_man = $resultQuestion[$i]['response_man'];
                                        if ($temp_text != "미선택") {
                                            $temp_1[$index] = array('name' => $temp_name, 
                                            'text' =>$temp_text,
                                            'count'=>"1",
                                            'response_man'=>$response_man);
                                            $index++;    
                                        }
                                    }

                                    foreach ($temp_1 as $resultItem):
                                        $m++;
                                        $resultExcelItem = [];
                                    ?>
                                        <div class="review_detailExample">
                                        <div class="element1">
                                            <span><?= $m . ")" . $resultItem['text'] ?></span>
                        <!--**********표만들기 <제목>-->
                                            <?php array_push($resultExcelItem,$m . ")" . $resultItem['text']); ?>
                                        </div>
                                        <div class="element2">
                                            <?php array_push($resultExcelItem,""); ?>
                                        </div>
                                        <div class="element2">
                                            <ul>
                                                <li>
                                                    <span class="responseResult"><?= $resultItem['response_man'] ?></span>
                                                    <!--**********표만들기 <명>-->
                                                    <?php array_push($resultExcelItem,$resultItem['response_man']); ?>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="element2">
                                            <ul>
                                                <li>
                                                    <span class="responseResult"><?= $resultItem['count'] ?>명</span>
                                                    <!--**********표만들기 <명>-->
                                                    <?php array_push($resultExcelItem,$resultItem['count']); ?>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="element2">
                                            <ul>
                                                <li>
                                                   
                                                </li>
                                            </ul>
                                        </div>
                                        <input id="tq<?= $n ?>e<?= $m ?>" type="hidden" value="<?=$resultItem['text']?>">
                                        <input id="cq<?= $n ?>e<?= $m ?>" type="hidden" value="<?=$resultItem['count']?>">
                                    </div>
                                    <!-- 기본내용과 구별구간-->
                                    <div class="exampleSeparate">

                                    </div>
                                    <!--표만들기 <행삽입>-->
                                    <?php array_push($resultQuestionExcel_item,$resultExcelItem);
                                    endforeach;
                            } else if ($item['type'] == 3) {
                                //강사 만족도인 경우
                                $teachers = $item['teachers'];
                                array_push($resultQuestionExcel,"3");

                                $exam_count = $item['example_count'];
                                $elements = array();
                                if($item['rating_names'] != null){
                                    $elements = explode(',',$item['rating_names']);
                                }else{
                                    if($exam_count == 5)
                                        $elements=['매우불만족','불만족','보통','만족','매우만족'];
                                    else
                                        $elements=['불만족','보통','만족'];
                                }

                                if($item['example_count'] == 5) {
                                    $elements_mark = ['1', '2', '3', '4', '5'];
                                } else {
                                    $elements_mark = ['1', '3', '5'];
                                }
                                //강사별순환
                                $t = 0;
                                foreach ($teachers as $teacher) :
                                    $t ++;
                                    ?>
                                    <div class="review_questionHeader" style="font-size: 18px;">
                                        <img src="<?=$teacher['profile']?>" style="width: 50px;">
                                        <?=$teacher['title']?>
                                        <?php
                                            $resultExcelItem = [];
                                            array_push($resultExcelItem,"");
                                            array_push($resultExcelItem,$teacher['title']);
                                            array_push($resultExcelItem,"");
                                            array_push($resultExcelItem,"");
                                            array_push($resultExcelItem,"");
                                            array_push($resultQuestionExcel_item,$resultExcelItem);
                                        ?>
                                    </div>
                                    <?php
                                        //지표별순환
                                        $teacher_marks = $item['teacher_marks'];
                                        $resultTeacher = $resultQuestion['t' . $t];
                                        $total_response_count = 0;
                                        $total_response_mark = 0;

                                        $m = 0;
                                        foreach($teacher_marks as $teacher_mark):
                                            $m ++;
                                            $resultMark = $resultTeacher['m'.$m];
                                        ?>
                                        <div style = "display: inline-block;font-size: 16px;padding: 8px;text-align: center;font-weight: bold;color: #424141;width: 100%;border-top: 1px solid #d8d8d8;">

                                             <?php
                                                $resultExcelItem = [];
                                                array_push($resultExcelItem,$teacher_mark['title']);
                                                array_push($resultExcelItem,"");
                                                array_push($resultExcelItem,"");
                                                array_push($resultExcelItem,"");
                                                array_push($resultExcelItem,"");
                                                array_push($resultQuestionExcel_item,$resultExcelItem);
                                              ?>
                                            <span><?= $teacher_mark['title'] ?></span>
                                        </div>
                                        <?php
                                            $tcp_example_count=0;
                                            // $tcp_example_count = $tcp_example_count+$item['example_count'];
                                            $total_type3_marks = 0;

                                            $e = 0;
                                            $response_count = 0;
                                            $response_mark = 0;
                                            foreach ($elements as $element):
                                                $e++;
                                                $resultExample = $resultMark['e' . $e];
                                                //**********표만들기항목초기화
                                                $resultExcelItem = [];
                                                $response_name = "";
                                                foreach ($resultExample as $val){
                                                    $response_name .=$val['response_man']."<br>";
                                                }

                                                ?>
                                                <div class="review_detailExample">
                                                    <div class="element1">
                                                        <!--**********표만들기 <제목>-->
                                                        <?php array_push($resultExcelItem,$e . ")" . $element); ?>
                                                        <span><?= $e . ")&nbsp;&nbsp;&nbsp;" . $element ?></span>
                                                    </div>
                                                    <div class="element2">
                                                        <?php array_push($resultExcelItem,""); ?>
                                                    </div>
                                                    <div class="element2">
                                                        <ul>
                                                            <li>
                                                                <span class="responseResult"><?= $response_name ?></span>
                                                                <!--**********표만들기 <명>-->
                                                                <?php array_push($resultExcelItem,$response_name); ?>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="element2">
                                                        <ul>
                                                            <li>
                                                                <span class="responseResult"><?= sizeof($resultExample) ?>명</span>
                                                                <!--**********표만들기 <명>-->
                                                                <?php
                                                                array_push($resultExcelItem, sizeof($resultExample));
                                                                $response_count += sizeof($resultExample);
                                                                $total_response_count += sizeof($resultExample);
                                                                ?>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="element2">
                                                        <ul>
                                                            <li>
                                                                <span class="responseResult"><?= $elements_mark[$e-1];?>점</span>
                                                                <!--**********표만들기 <%>-->
                                                                <?php
                                                                $percent = sizeof($resultExample) * $elements_mark[$e-1];
                                                                $total_type3_marks +=$percent;
                                                                array_push($resultExcelItem, $elements_mark[$e-1].'점');
                                                                array_push($resultQuestionExcel_item,$resultExcelItem);
                                                                $response_mark += $percent;
                                                                $total_response_mark += $percent;
                                                                ?>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <input id="tq<?= $n ?>e<?= $e ?>" type="hidden" value="<?=$element?>">
                                                    <input id="cq<?= $n ?>e<?= $e ?>" type="hidden" value="<?=sizeof($resultExample)?>">
                                                </div>
                                                <div class="exampleSeparate">

                                                </div>
                                            <?php
                                            endforeach;
                                            $resultExcelItem = [];
                                            if ($response_count > 0)
                                                $aver_mark = number_format($response_mark / $response_count, 2 ,'.','');
                                            else
                                                $aver_mark = '0';
                                            ?>

                                            <div class="review_detailExample">
                                                <div class="element1" style="    text-align: center;">
                                                    <!--**********표만들기 <제목>-->
                                                    <?php  array_push($resultExcelItem, '평균'); ?>
                                                    <span style="    font-weight: 700;">평균</span>
                                                </div>
                                                <div class="element2">
                                                    <?php array_push($resultExcelItem,""); ?>
                                                </div>
                                                <div class="element2">
                                                    <?php array_push($resultExcelItem,""); ?>
                                                </div>
                                                <div class="element2">
                                                    <ul>
                                                        <li>
                                                            <span class="responseResult"><?= $response_count ?>명</span>
                                                            <!--**********표만들기 <명>-->
                                                            <?php
                                                            array_push($resultExcelItem, $response_count);
                                                            ?>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="element2">
                                                    <ul>
                                                        <li>
                                                            <span class="responseResult"><?= $aver_mark ?>점</span>
                                                            <!--**********표만들기 <명>-->
                                                            <?php
                                                            array_push($resultExcelItem, $aver_mark);
                                                            array_push($resultQuestionExcel_item, $resultExcelItem);
                                                            ?>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <?php
                                        endforeach;
                                    ?>
                                        <div class="exampleSeparate">
                                        </div>
                                        <?php
                                            $resultExcelItem = [];
                                            if ($total_response_count > 0)
                                                $total_aver_mark = number_format($total_response_mark / $total_response_count, 2 ,'.','');
                                            else
                                                $total_aver_mark = '0';
                                        ?>
                                        <!-- 강사의 총평균점수 -->
                                        <div class="review_detailExample">
                                                <div class="element1" style="    text-align: center;">
                                                    <!--**********표만들기 <제목>-->
                                                    <?php  array_push($resultExcelItem, '총평'); ?>
                                                    <span style="    font-weight: 700;">총&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;평</span>
                                                </div>
                                                <div class="element2">
                                                    <?php array_push($resultExcelItem,""); ?>
                                                </div>
                                                <div class="element2">
                                                    <?php array_push($resultExcelItem,""); ?>
                                                </div>
                                                <div class="element2">
                                                    <ul>
                                                        <li>
                                                            <!-- <span class="responseResult"><?= $total_response_count ?>명</span> -->
                                                            <!--**********표만들기 <명>-->
                                                            <?php
                                                            array_push($resultExcelItem, "");
                                                            ?>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="element2">
                                                    <ul>
                                                        <li>
                                                            <span class="responseResult"><?= $total_aver_mark ?>점</span>
                                                            <!--**********표만들기 <명>-->
                                                            <?php
                                                            array_push($resultExcelItem, $total_aver_mark);
                                                            array_push($resultQuestionExcel_item, $resultExcelItem);
                                                            ?>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        <div class="review_detailExample">
                                            <div class="element1"   >
                                                <!--**********강사 만족도의 주관식답변-->
                                                <?php 
                                                     $resultExcelItem = [];
                                                     array_push($resultExcelItem, '[강사에게 하고싶은 말]'); 
                                                ?>
                                                <span >[강사에게 하고싶은 말]</span>
                                            </div>
                                            <div class="element2">
                                                <?php array_push($resultExcelItem,""); ?>
                                            </div>
                                            <div class="element2">
                                                <?php array_push($resultExcelItem,""); ?>
                                            </div>
                                            <div class="element2">
                                                <?php array_push($resultExcelItem,""); ?>
                                            </div>
                                            <div class="element2">
                                                <?php
                                                array_push($resultExcelItem, "");
                                                array_push($resultQuestionExcel_item, $resultExcelItem);
                                                $resultExcelItem = [];
                                                ?>
                                                <span ></span>
                                            </div>
                                        </div>

                                    <?php
                                        
                                        $text_array = $resultQuestion['t'.$t.'text'];
                                        foreach ($text_array as $text_item):
                                            // $resultExcelItem = [];
                                            // array_push($resultExcelItem, $text_item['text']);
                                            // array_push($resultExcelItem,"");
                                            // array_push($resultExcelItem,"");
                                            // array_push($resultExcelItem,"");
                                            // array_push($resultExcelItem,"");
                                    ?>
                                            <div style = "display: inline-block;font-size: 15px;padding: 4px;text-align: left;color: #424141;width: 100%;">
                                                        <div style="width:30%;display:inline-block"></div><div style="width:70%;display:inline-block"><?= $text_item['text'] ?></div>
                                            </div>
                                    <?php
                                            // array_push($resultQuestionExcel_item, $resultExcelItem);
                                        endforeach;
                                    ?>
                                    <?php
                                    endforeach;                                                               
                            } else {
                                //만족도인 경우(textbox창)
                                array_push($resultQuestionExcel,"2");
                                $tcp_example_count = $tcp_example_count+$item['example_count'];
                                $total_type3_marks = 0;

                                $exam_count = $item['example_count'];
                                $elements = array();
                                if($item['rating_names'] != null){
                                    $elements = explode(',',$item['rating_names']);
                                }else{
                                    if($exam_count == 5)
                                        $elements=['매우불만족','불만족','보통','만족','매우만족'];
                                    else
                                        $elements=['불만족','보통','만족'];
                                }

                                if($item['reverse_question'] == 0){
                                    if($item['example_count'] == 5) {
                                        $elements_mark = ['1', '2', '3', '4', '5'];
                                    } else {
                                        $elements_mark = ['1', '3', '5'];
                                    }
                                }else{   //역문항인경우
                                    if($item['example_count'] == 5) {
                                        $elements_mark = ['5', '4', '3', '2', '1'];
                                    } else {
                                        $elements_mark = ['5', '3', '1'];
                                    }
                                }
                                $m = 0;
                                $response1_count = 0;
                                $response1_mark = 0;   
                                foreach ($elements as $element):
                                    $m++;
                                    $resultExample = $resultQuestion['e' . $m];
                                    //**********표만들기항목초기화
                                    $resultExcelItem = [];
                                    $response_name = "";
                                    foreach ($resultExample as $val){
                                        if ($val['response_man'] != "") {
                                            $response_name .=$val['response_man']."<br>";
                                        }
                                    }
                                    ?>
                                    <div class="review_detailExample">
                                        <div class="element1">
                                            <!--**********표만들기 <제목>-->
                                            <?php array_push($resultExcelItem,$m . ")" . $element); ?>
                                            <span><?= $m . ")&nbsp;&nbsp;&nbsp;" . $element ?></span>
                                        </div>
                                        <div class="element2">
                                            <?php array_push($resultExcelItem,""); ?>
                                        </div>
                                        <div class="element2">
                                            <ul>
                                                <li>
                                                    <span class="responseResult"><?= $response_name ?></span>
                                                    <!--**********표만들기 <명>-->
                                                    <?php array_push($resultExcelItem,$response_name); ?>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="element2">
                                            <ul>
                                                <li>
                                                    <span class="responseResult"><?= sizeof($resultExample) ?>명</span>
                                                    <!--**********표만들기 <명>-->
                                                    <?php 
                                                        $response1_count += sizeof($resultExample);
                                                        array_push($resultExcelItem,sizeof($resultExample)); 
                                                    ?>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="element2">
                                            <ul>
                                                <li>
                                                    <span class="responseResult"><?= $elements_mark[$m-1];?>점</span>
                                                    <!--**********표만들기 <%>-->
                                                    <?php

                                                    $percent = sizeof($resultExample) * $elements_mark[$m-1];
                                                    $total_type3_marks += $percent;
                                                    $response1_mark += $percent;
                                                    array_push($resultExcelItem, $elements_mark[$m-1].'점');
                                                    array_push($resultQuestionExcel_item,$resultExcelItem);
                                                    ?>
                                                </li>
                                            </ul>
                                        </div>
                                        <input id="tq<?= $n ?>e<?= $m ?>" type="hidden" value="<?=$element?>">
                                        <input id="cq<?= $n ?>e<?= $m ?>" type="hidden" value="<?=sizeof($resultExample)?>">
                                    </div>
                                    <div class="exampleSeparate">

                                    </div>
                                <?php 
                                endforeach;

                                $resultExcelItem = [];
                                if ($response1_count > 0)
                                    $aver1_mark = number_format($response1_mark / $response1_count, 2 ,'.','');
                                else
                                    $aver1_mark = '0';
                                ?>

                                <div class="review_detailExample">
                                    <div class="element1" style="    text-align: center;">
                                        <!--**********표만들기 <제목>-->
                                        <?php  array_push($resultExcelItem, '평균'); ?>
                                        <span style="    font-weight: 700;">평균</span>
                                    </div>
                                    <div class="element2">
                                        <?php array_push($resultExcelItem,""); ?>
                                    </div>
                                    <div class="element2">
                                        <?php array_push($resultExcelItem,""); ?>
                                    </div>
                                    <div class="element2">
                                        <ul>
                                            <li>
                                                <span class="responseResult"><?= $response1_count ?>명</span>
                                                <!--**********표만들기 <명>-->
                                                <?php 
                                                    array_push($resultExcelItem, $response1_count);                                                             
                                                ?>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="element2">
                                        <ul>
                                            <li>
                                                <span class="responseResult"><?= $aver1_mark ?>점</span>
                                                <!--**********표만들기 <명>-->
                                                <?php                                                             
                                                    array_push($resultExcelItem, $aver1_mark);  
                                                    array_push($resultQuestionExcel_item, $resultExcelItem);                                                           
                                                ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <?php
                            }

                            if($item['allow_unselect']==="1") {  // 미선택인경우
                                if ($item['type'] !== "1") {  // 주관식이 아니면
                                    $resultQuestion = $allReview['q' . $n . "unselect"];
                                    $resultUnselect = $resultQuestion['e0'];
                                    $other_count = 0;
                                    $temp_title = "";
                                    $m++;
                                    //**********표만들기항목초기화
                                    $resultExcelItem = [];
                                    $response_name = "";
                                    foreach ($resultUnselect as $val){
                                        if ($val['response_man'] != "") {
                                            $response_name .=$val['response_man']."<br>";
                                        }
                                    }
                                    ?>
                                    <div class="review_detailExample">
                                        <div class="element1">
                                            <span><?= $m . ") 미선택" ?></span>
                                            <!--**********표만들기 <제목>-->
                                            <?php array_push($resultExcelItem, $m . ") 미선택"); ?>
                                        </div>
                                        <div class="element2">
                                            <?php array_push($resultExcelItem,""); ?>
                                        </div>
                                        <div class="element2">
                                            <ul>
                                                <li>
                                                    <span class="responseResult"><?= $response_name ?></span>
                                                    <!--**********표만들기 <명>-->
                                                    <?php array_push($resultExcelItem,$response_name); ?>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="element2">
                                            <ul>
                                                <li>
                                                    <span class="responseResult"><?= sizeof($resultUnselect) ?>명</span>
                                                    <!--**********표만들기 <명>-->
                                                    <?php array_push($resultExcelItem, sizeof($resultUnselect) ); ?>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="element2">
                                            <ul>
                                                <li>
                                                                <span class="responseResult"><?= $total_response_count > 0 ? intval(sizeof($resultUnselect) * 100 / $allReview['q' . $n . 'Count']) : 0 ?>
                                                                    %</span>
                                                    <!--**********표만들기 <%>-->
                                                    <?php
                                                    $percent = $total_response_count > 0 ? intval(sizeof($resultUnselect) * 100 / $total_response_count) : 0;
                                                    array_push($resultExcelItem, $percent . '%'); ?>
                                                </li>
                                            </ul>
                                        </div>
                                        <input id="tq<?= $n ?>e<?= $m ?>" type="hidden" value="미선택">
                                        <input id="cq<?= $n ?>e<?= $m ?>" type="hidden"
                                               value="<?= sizeof($resultUnselect) ?>">
                                    </div>
                                    <!-- 기본내용과 구별구간-->
                                    <div class="exampleSeparate">

                                    </div>
                                    <!--표만들기 <행삽입>-->
                                    <?php array_push($resultQuestionExcel_item, $resultExcelItem);                                    
                                    $tcp_example_count = $tcp_example_count+1;
                                }                                
                            }
                            $resultExcelItem = [];
                            ?>

                            <?php
                            if ($item['type'] == "0") {
                            ?>
                                <div class="review_detailExample">
                                <div class="element1" style="text-align: center">
                                    <span style="    font-weight: 700;">소 계</span>
                                    <?php array_push($resultExcelItem, "소 계"); ?>
                                <!--**********표만들기 <제목>-->   
                                </div>
                                <div class="element2">
                                    <?php array_push($resultExcelItem, ""); ?>
                                </div>
                                <div class="element2">
                                    <?php array_push($resultExcelItem, ""); ?>
                                </div>
                                <div class="element2">
                                    <ul>
                                        <li>
                                            <span class="responseResult"><?= $allReview['q' . $n . 'Count'] ?>명</span>
                                            <!--**********표만들기 <명>-->
                                            <?php array_push($resultExcelItem, $allReview['q' . $n . 'Count'] ); ?>
                                        </li>
                                    </ul>
                                </div>
                                <div class="element2">
                                    <ul>
                                        <li>
                                            <?php
                                            if($item['type']=="2") {
                                                ?>
                                                <span class="responseResult">
                                                <?php 
                                                    $total_type3_marks_divide = 0;
                                                    if ($allReview['q' . $n . 'Count'] > 0)
                                                        $total_type3_marks_divide = $total_type3_marks / $allReview['q' . $n . 'Count'];
                                                    
                                                    echo ($total_type3_marks_divide);
                                                ?>점</span>
                                                <?php

                                                array_push($resultExcelItem, $total_type3_marks_divide . '점');
                                                // $tcp_example_count=0;
                                            } else {
                                                if($total_response_count > 0){
                                                    if($item['allow_reply_response'] == "0")
                                                        $total_response_percent = $allReview['q' . $n . 'Count'] > 0 ? intval($allReview['q' . $n . 'Count'] * 100 / $total_response_count) : 0;
                                                    else if($total_response_count_including_duplicate > 0)
                                                        $total_response_percent = 100;


                                                }else
                                                    $total_response_percent = 0;
                                            ?>
                                                <span class="responseResult"><?= $total_response_percent ?>
                                                    %</span>
                                            <?php
                                            if($total_response_count > 0)
                                                $percent = $allReview['q' . $n . 'Count'] > 0 ? intval($allReview['q' . $n . 'Count'] * 100 / $total_response_count) : 0;
                                            else
                                                $percent = 0;
                                            array_push($resultExcelItem, $percent . '%');
                                            }
                                            ?>
                                            <!--**********표만들기 <%>-->

                                        </li>
                                    </ul>
                                <input id="tq<?= $n ?>e<?= $m ?>" type="hidden" value="소계">
                                <input id="cq<?= $n ?>e<?= $m ?>" type="hidden"
                                    value="<?= $allReview['q' . $n . 'Count'] ?>">
                                </div>
                                                        
                            <?php                                
                            }
                            ?>
                            
                            <!-- 기본내용과 구별구간-->
                            <div class="exampleSeparate">

                            </div>
                            <!--표만들기 <행삽입>-->
                            <?php array_push($resultQuestionExcel_item, $resultExcelItem);

                            ?>

                            <div id="chart_container">
                                <div id="circle_chart_<?= $n ?>" class="circle_chart">

                                </div>
                                <div id="line_chart_<?= $n ?>" class="line_chart">

                                </div>

                            </div>

                        </div>
                        <input id="tcq<?= $n ?>" type="hidden" value="<?=$tcp_example_count?>">
                        <input id="tnq<?= $n ?>" type="hidden" value="<?=$item['question']?>">

                    <?php
                        array_push($resultQuestionExcel,$resultQuestionExcel_item);
                        array_push($resultExcel,$resultQuestionExcel);

                    endforeach; ?>
                </div>
                <!-- 미응답자보기구역-->
                <div id="review_noResponse" hidden>
                    <table class="noResponseArea" style = "margin-top:0">
                        <thead>
                            <tr>
                                <th>번호</th>
                                <th>이름</th>
                                <th>전화번호</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $n = 0;
                        foreach ($noResponseMobiles as $items):
                            foreach ($items as $item):
                                $n++;
                                ?>
                                <tr>
                                    <td><?= $n ?></td>
                                    <td><?= $item['name'] ?></td>
                                    <td class = "noResponseNumber"><?= $item['mobile'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button class="btn btn-default" onclick="onResend()" style="float:right;margin-right:30px;margin-bottom:15px;color: #fff; background-color: #5268f7; border-color: #ccc;">다시 보내기
                    </button>
                </div>
                <!-- 결과전송구역-->
                <div id="review_send" style="text-align: center;    padding: 20px;">                    
                    <button class="btn btn-default" onclick="onSendToTeacher()" style="margin-right:30px;margin-bottom:15px;color: #fff; background-color: #5268f7; border-color: #ccc;">
                                결과 보내기
                    </button>
                </div>
                <!-- <div class="sub_img">-->
                <!-- <img src="--><? //=$site_url?><!--images/bg/block.png">-->
                <!-- </div>-->
                <!--표작성-->
                <?php $this->session->set_userdata('noResponseMobiles', $noResponseMobiles); ?>
            </div>
        </div>
    </div>
<!--    표자료를 세션에 넣기-->
    <?php
    $this->session->set_userdata('reviewExcel', $resultExcel);
    ?>
    <!-- displayKind   0 : 종합통계보기-->
    <!-- displayKind   1 : 미응답자보기-->
    <input id="displayKind" type="hidden" value="0">
</div>
