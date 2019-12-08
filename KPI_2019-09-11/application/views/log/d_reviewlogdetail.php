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
?>

<div class="container container-bg">
    <div id="content">
        <div id="contents">
            <div class="sub_con">
                <div class="sub_title1"><img src="<?= $site_url ?>images/icon_title.png">진단결과</div>
<!--                미응답자들에 대한 <다시보내기>기능을 위해 자료건사하기-->
                <input id="message_content" type="hidden" value="<?=$survey['content']?>">
                <input id="message_noticeId" type="hidden" value="<?=$survey['noticeId']?>">
                <input id="message_calling_number" type="hidden" value="<?=$survey['calling_number']?>">
                <div class="review_detailheader" style="text-align: center;">

                    <ul>
                        <li>
                            <button class="btn btn-default" onclick="onToReviewListClick('<?=$parent?>')">목 록</button>
                        </li>
                        <!--                        <li>-->
                        <!--                            <button class="btn btn-default" onclick="onShowNoResponseClick()" id="btn_noResponse">-->
                        <!--                                미응답자전화번호-->
                        <!--                            </button>-->
                        <!--                        </li>-->
                        <li>
                            <button class="btn btn-default" onclick="onShowMainDetailClick()" id="btn_mainDetail"
                                    style="display: none;">기본상세통계
                            </button>
                        </li>
                        <li>
                            <button class="btn btn-default" onclick="onExcelClick()">Excel로 내려받기</button>
                        </li>
                    </ul>
                </div>
<!--                설문조사통계테블의 Header부-->
                <div style="    padding: 20px;">
                    <table class="review_title_scope">
                        <tr>
                            <th colspan="8"><?= $survey['title'] ?>(<?=$total_count ?>문항)</th>


                        </tr>
                        <tr>
                            <td style="width:10%;background: #ffe6b8;">진단일자</td>
                            <td style="width:30%"><?=substr($survey['start_time'],0,10)?> ~ <?=substr($survey['end_time'],0,10)?></td>
                            <td style="width:10%;    background: #ffe6b8;">발송수</td>
                            <td style="width:10%"><?=$survey['mobile_count']?></td>
                            <td style="width:10%;background: #ffe6b8;">응답수</td>
                            <td style="width:10%"><?=$total_response_count?> (<?php $rate = $total_response_count*100/$survey['mobile_count']; echo floor($rate*100)/100;?>%)</td>
                            <td style="width:10%;background: #ffe6b8;">담당자</td>
                            <td style="width:10%"></td>
                        </tr>
                    </table>
                </div>

                <!-- <div class = "whiteBand"></div>-->
                <div id="review_mainDetail" style="    padding: 0px 20px;" >
                    <?php
                    $n = 0;
                    $CountSum = "";
                    $resultQuestionExcel['title'] = $survey['title'];
                    $resultQuestionExcel['date'] = substr($survey['start_time'],0,10).' ~ '.substr($survey['end_time'],0,10);
                    $resultQuestionExcel['mobile_count'] = $survey['mobile_count'];
                    $resultQuestionExcel['response_count'] = $total_response_count;
                    $resultQuestionExcel['man'] = "";
                    $resultExcel['title']=$resultQuestionExcel;

                    foreach ($questions as $item):
                        $n++;
                        $resultQuestion = $allReview['q' . $n];
                        $resultQuestionExcel_item = []
                        ?>
                        <!--질문제목-->
                        <div class="review_question_scope">
                            <div class="review_detailQuestion">
                                <div class="element1">
                                    <span><?= $n."/".$total_count . " " . $item['question'] ?></span>
                                        <!--**********표만들기-->
                                    <?php
                                    $resultQuestionExcel = [];array_push($resultQuestionExcel,$n."/".$total_count . " " . $item['question']);
                                    ?>
                                </div>


                            </div>

                            <div class="review_questionHeader">
                                <div class="element1" style="text-align: center">
                                    <span>보기</span>
                                </div>
                                <div class="element2" >
                                    <ul>
                                        <li>
                                            <span class="responseResult"></span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="element2" >
                                    <ul>
                                        <li>
                                            <span class="responseResult">응답자</span>
                                        </li>
                                    </ul>
                                </div>

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
                                           <span class="responseResult">비율</span>

                                        </li>
                                    </ul>
                                </div>

                            </div>
                        <!--질문에 대한 해답항목-->
                            <?php
                            $tcp_example_count = 0;

                            ?>


                            <?php
                            //옵션형식인 경우
                            if ($item['type'] == 0) {
                                array_push($resultQuestionExcel,"0");
                                $tcp_example_count = $tcp_example_count+sizeof($item['examples']);
                                $m = 0;
                                foreach ($item['examples'] as $example):
                                    $m++;
                                    $resultExample = $resultQuestion['e' . $m];
                                    $response_name = "";
                                    foreach ($resultExample as $val){
                                        $response_name .=$val['response_man']."<br>";
                                    }
                                    //**********표만들기항목초기화
                                    $resultExcelItem = [];


                                    ?>
                                    <div class="review_detailExample">
                                        <div class="element1">
                                            <span><?= $m . ")" . $example['title'] ?></span>
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
                                                    <span class="responseResult"><?= $total_response_count > 0 ? intval(sizeof($resultExample) * 100 / $total_response_count) : 0 ?>
                                                        %</span>
                                                    <!--**********표만들기 <%>-->
                                                    <?php
                                                    $percent = $total_response_count > 0 ? intval(sizeof($resultExample) * 100 / $total_response_count) : 0 ;
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
                                            $data = json_decode($anser_text, TRUE);
                                            $pos = strpos($data[$n],$n."기타");
                                            if($pos > -1) {
                                                $answer_other_text = substr($data[$n], strlen($n . "기타"), strlen($data[$n]));
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
                                        <div class="element1">
                                            <span><?= $m . ") 기타" ?></span>
                                            <!--**********표만들기 <제목>-->
                                            <?php array_push($resultExcelItem,$m . ") 기타"); ?>
                                        </div>
                                        <div class="element2">
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



                            //주관식인식인 경우(textbox창)
                            } else if ($item['type'] == 1) {
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
                                    $resultQuestion = array_values($resultQuestion);
                                    for($i=0; $i<$question_count; $i++) {
                                        $temp_name = $resultQuestion[$i]['name'];
                                        $temp_text = $resultQuestion[$i]['text'];
                                        $response_man = $resultQuestion[$i]['response_man'];
                                        $temp_count = 1;

                                        for($j=$i+1; $j<$question_count; $j++) {
                                            if($resultQuestion[$i]['text']==$resultQuestion[$j]['text']) {
                                                $temp_name .="<br>".$resultQuestion[$j]['name'];
                                                $response_man .="<br>".$resultQuestion[$j]['response_man'];
                                                unset($resultQuestion[$j]);
                                                $question_count--;
                                                $temp_count ++;
                                            }
                                        }
                                        $temp_1[$i] = array('name' => $temp_name, 'text' => $temp_text,'count'=>$temp_count,'response_man'=>$response_man);
                                        $resultQuestion = array_values($resultQuestion);
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
                                                    <span class="responseResult"><?= $total_response_count > 0 ? intval($resultItem['count'] * 100 / $total_response_count) : 0 ?>
                                                                            %</span>
                                                    <!--**********표만들기 <%>-->
                                                    <?php
                                                    $percent = $total_response_count > 0 ? intval($resultItem['count'] * 100 / $total_response_count) : 0 ;
                                                    array_push($resultExcelItem,$percent.'%'); ?>
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

                            //만족도인 경우(textbox창)
                            } else {
                                array_push($resultQuestionExcel,"2");
                                $tcp_example_count = $tcp_example_count+$item['example_count'];
                                $total_type3_marks = 0;
                                if($item['example_count'] == 5) {
                                    $elements = ['매우불만족', '불만족', '보통', '만족', '매우만족'];
                                    $elements_mark = ['1', '2', '3', '4', '5'];
                                } else {

                                    $elements = ['불만족', '보통', '만족'];
                                    $elements_mark = ['1', '3', '5'];
                                }
                                $m = 0;
                                foreach ($elements as $element):
                                    $m++;
                                    $resultExample = $resultQuestion['e' . $m];
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
                                            <?php array_push($resultExcelItem,$m . ")" . $element); ?>
                                            <span><?= $m . ")" . $element ?></span>
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
                                                    <span class="responseResult"><?= sizeof($resultExample) * $elements_mark[$m-1];?>점</span>
                                                    <!--**********표만들기 <%>-->
                                                    <?php

                                                    $percent = sizeof($resultExample) * $elements_mark[$m-1];
                                                    $total_type3_marks +=$percent;
                                                    array_push($resultExcelItem, $percent.'점');
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
                                <?php endforeach; ?>

                            <?php }
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
                                        $response_name .=$val['response_man']."<br>";
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
                            <div class="review_detailExample">
                                        <div class="element1" style="text-align: center">
                                            <span style="    font-weight: 700;">소 계</span>
                        <!--**********표만들기 <제목>-->
                            <?php array_push($resultExcelItem, "소계"); ?>
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
                                            <span class="responseResult"><?= $total_type3_marks / $allReview['q' . $n . 'Count']?>점</span>
                                            <?php

                                            array_push($resultExcelItem, $total_type3_marks / $allReview['q' . $n . 'Count'] . '점');
                                            $tcp_example_count=0;
                                        } else {
                                        ?>
                                            <span class="responseResult"><?= $allReview['q' . $n . 'Count'] > 0 ? intval($allReview['q' . $n . 'Count'] * 100 / $total_response_count) : 0 ?>
                                                %</span>
                                        <?php
                                        $percent = $allReview['q' . $n . 'Count'] > 0 ? intval($allReview['q' . $n . 'Count'] * 100 / $total_response_count) : 0;
                                        array_push($resultExcelItem, $percent . '%');
                                        }
                                        ?>
                                        <!--**********표만들기 <%>-->

                                    </li>
                                </ul>
                            </div>
                            <input id="tq<?= $n ?>e<?= $m ?>" type="hidden" value="소계">
                            <input id="cq<?= $n ?>e<?= $m ?>" type="hidden"
                                   value="<?= $allReview['q' . $n . 'Count'] ?>">
                            </div>
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
                    <button class="btn btn-default" onclick="onResend()" style="float:right;margin-right:30px;margin-bottom:15px;color: #fff;
    background-color: #5268f7;
    border-color: #ccc;">다시 보내기
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
