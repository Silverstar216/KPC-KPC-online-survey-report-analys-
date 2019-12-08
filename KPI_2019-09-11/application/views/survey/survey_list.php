<?php
/**
 * Created by PhpStorm.
 * User: CHKD
 * Date: 10/9/2018
 */
defined('BASEPATH') OR exit('No direct script access allowed');
$site_url = site_url();

$now_date = date('Y-m-d');
$str_begin_date = strtotime($now_date.'-1 months');
$str_end_date = strtotime($now_date.'+1 months');
// $start_date = date('Y-m-01',$str_begin_date); 
$start_date = date('Y-m-d');
$end_date = date('Y-m-d',$str_end_date); 
// $str_end_date = strtotime($end_date.'-1 days');
// $end_date = date('Y-m-d',$str_end_date); 
?>


<div class="container container-bg">
    <div id="content">
        <div id="contents">
            <div class="m_con">
                <?php
                // $this->load->view('index/menu_main', $this->data);   // 좌측메뉴 
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
                            <input type="hidden" id='view_flag' value="<?php echo $view_flag?>" >
                            <input type="hidden" id="hstval" value="<?php echo $stval?>" >
                            <input type="hidden" id='survey_flag' value="<?php echo $survey_flag?>" >
                            <input type="hidden" id="is_landing" value="<?php echo isset($this->data['is_landing']) ? 1 : 0 ?>" >
                            <div class="search">
                                <img src="<?=$site_url?>images/img/icon_item_red.png">
                                <b>조회조건</b>
                            </div>
                            <div class="search search-items" style="margin-left:25px">
                            <ul>
                                    <li style="width: 7%;     padding-top: 5px;">조회기간</li>
                                    <li style="width: 23%">
                                        <input type="text" style="height: 28px; width: 40%;padding-left: 5px;" id="survey_begindate" value="<?=$start_date?>"> &nbsp;&nbsp; ~ &nbsp;&nbsp;
                                        <input type="text" style="height: 28px; width: 42%;padding-left: 5px;" id="survey_enddate" value="<?=$end_date?>">
                                    </li>
                                    <li style="width: 5%; padding-top: 5px;margin-left: 20px">부서명</li>
                                    <li style="width: 30%">
                                        <input type="text" id="survey_groupname" style="width: 100%; height: 28px;padding-left: 5px;">
                                    </li>
                                    <li style="width: 7%; padding-top: 5px;margin-left: 20px">담당자</li>
                                    <li style="width: 22%">
                                        <input type="text" id="survey_admin" value="<?=$username?>" style="width: 78%; height: 28px;padding-left: 5px;">
                                    </li>
                                    <li style="width: 5%; padding-top: 5px;margin-left: 20px" hidden>직무명</li>
                                    <li style="width: 30%" hidden>
                                        <select id="survey_job" style="width: 70%; height: 28px">
                                        <option value="">[이름만 검색]</option>    
                                        <option value="회장">회장</option>
                                        <option value="부회장">부회장</option>
                                        <option value="원장">원장</option>
                                        <option value="소장">소장</option>
                                        <option value="상무">상무</option>
                                        <option value="본부장">본부장</option>
                                        <option value="본부장직무대리">본부장직무대리</option>                                        
                                        <option value="지역본부장">지역본부장</option>
                                        <option value="센터장">센터장</option>
                                        <option value="책임전문위원">책임전문위원</option>
                                        <option value="선임전문위원">선임전문위원</option>                                                                             
                                        <option value="수석전문위원">수석전문위원</option>
                                        <option value="전문위원">전문위원</option>                                                                                
                                        <option value="팀장">팀장</option>                                        
                                        <option value="실장">실장</option>
                                        <option value="연구원">연구원</option>
                                        <option value="연구원보">연구원보</option>                                        
                                    </select>
                                    </li>                                    
                                </ul>
                                <ul>
                                    <li style="width: 7%; padding-top: 5px;">대분류</li>
                                    <li style="width: 23%"> <input type="text" id="education_type" style="width: 100%; height: 28px;padding-left: 5px;"></li>
                                    <li style="width: 5%;padding-top: 5px;margin-left: 20px">과정명</li>
                                    <li style="width: 30%">
                                    <input type="text" id="survey_course" style="width: 100%; height: 28px;padding-left: 5px;"></li>
                                    <li hidden style="width: 25%">설문명
                                    <input type="text" id="survey_name" style="width: 70%; height: 28px"></li>
                                    <?php
                                        if ($survey_flag == 0) {
                                    ?>
                                    <li style="width: 7%; padding-top: 5px;margin-left: 20px">고객사명</li>
                                    <li style="width: 22%">
                                        <input type="text" id="survey_customer" style="width: 78%; height: 28px;padding-left: 5px;">
                                    </li>
                                    <?php
                                        }
                                        else {
                                    ?>
                                    <li style="width: 7%; padding-top: 5px;margin-left: 20px">차수</li>
                                    <li style="width: 22%">
                                        <input type="text" id="survey_count" style="width: 78%; height: 28px;padding-left: 5px;">
                                    </li>
                                    <?php
                                        }
                                    ?>
                                </ul>
                                <!-- <?php
                                        if ($survey_flag == 0) 
                                        {
                                    ?>   
                                <ul>            
                                <form id="upload_erp" method="post" enctype="multipart/form-data" action="">
                                    <li style="width: 10%">교육과정목록</li>
                                    <li style="width: 50%">
                                    <input type="file" id="excel_education_file" accept="Excel/*.xlsx" name="excel_education_file"></li>
                                    <li style="width: 10%">
                                    <input class="btn btn-delete btn-sm" style="width: 80px; " type="button" value="올리기" onclick="onUploadEducationsExcel()">
                                    </li>
                                    <li id="excel_file_path" style="width: 30%;padding-top: 8px;text-align: right;"></li>
                                </form>
                                </ul>
                                <?php
                                    }                                                                         
                                ?> -->

                            </div>
                            <div class="search search-btn" style="text-align: center;">
                                <button class="btn btn-default btn-sm" style="width: 100px; " onclick="onSearchEducationSecheduleList()">조회하기</button>
                            </div>
                            <div style="display: inline-block; width: 100%;    margin-top: 20px;">
                            </div>
                            <?php
                                if ($survey_id != '0') {
                                    echo ("<div class='search'>
                                    <img src='");
                                    echo ($site_url);
                                    echo ("images/img/icon_item_red.png'>
                                        <b>편집할 설문</b>&nbsp;&nbsp;(설문명: ");
                                    echo ($selected_survey_name);
                                    echo (")</div>");
                                }                               
                            ?>
                        </div>    
                        <div id="grouplistDiv" style=" over-flow:scroll;">
                        </div>           
                        <div class="blog-pagination">
                        </div>         
                        <div style="display: inline-block; width: 100%; text-align: center; margin-bottom: 20px;">                                
                            <button class="btn btn-default btn-sm" style="width: 100px;" onclick="onSendSurvey(<?=$survey_id?>, <?=$newflag?>, <?=$attached?>)">설문하기</button>
                            <?php
                                if ($survey_flag == 0) {
                            ?>      
                            <button class="btn btn-default btn-sm" style="width: 100px;" onclick="onCreateNewSurvey()">신규설문</button>
                            <?php 
                                }
                            ?>
                        </div>
                    </div>                    
                </div>               
            </div>
        </div>
    </div>
</div>
