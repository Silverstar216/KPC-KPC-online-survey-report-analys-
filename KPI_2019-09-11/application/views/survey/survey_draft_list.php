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
                            <input type="hidden" id='view_flag' value="<?php echo $view_flag?>" >
                            <input type="hidden" id="hstval" value="<?php echo $stval?>" >
                            <input type="hidden" id='survey_flag' value="<?php echo $survey_flag?>" >
                            <div class="search">
                                <img src="<?=$site_url?>images/img/icon_item_red.png">
                                    <b>조회조건</b>
                                </div>
                                <div class="search search-items">
                                    <ul>
                                        <li style="width: 9%; padding-top: 5px;">조회기간</li>
                                        <li style="width: 25%">
                                            <input type="text" style="height: 28px; width: 40%;" id="survey_begindate" value="<?=$start_date?>"> &nbsp;&nbsp; ~ &nbsp;&nbsp; 
                                            <input type="text" style="height: 28px; width: 40%;" id="survey_enddate" value="<?=$end_date?>">
                                        </li>
                                        <li style="width: 5%;padding-top: 5px;;margin-left:20px">부서명</li>
                                        <li  style="width: 25%">
                                            <input type="text" id="survey_group" style="width: 90%; height: 28px">
                                        </li>
                                        <li style="width: 5%;padding-top: 5px;margin-left:10px">담당자</li>
                                        <li  style="width: 25%">
                                            <input type="text" id="survey_admin" value="" style="width: 90%; height: 28px">
                                        </li>                                    
                                        </li>
                                        <li style="width: 18%;padding-top: 5px;;margin-left:10px" hidden>사업팀
                                            <input type="text" id="survey_team" style="width: 70%; height: 28px">
                                        </li>
                                    </ul>
                                    <ul>
                                        <li style="width: 9%;     padding-top: 5px;">고객사명</li>
                                        <li style="width: 25%">                                    
                                            <input type="text" id="survey_customer" style="width: 95%; height: 28px">
                                        </li>
                                        <li style="width: 5%;padding-top: 5px;margin-left:20px">과정명</li>
                                        <li  style="width: 25%">
                                            <input type="text" id="survey_course" style="width: 90%; height: 28px">
                                        </li>                                    
                                        <li style="width: 5%;padding-top: 5px;margin-left:10px">설문명</li>
                                        <li  style="width: 25%">
                                            <input type="text" style="width: 90%; height: 28px" id="survey_name">
                                        </li>
                                    </ul>                                    
                            </div>
                            <div class="search search-items" style="text-align: center;">
                                <button class="btn btn-default btn-sm" style="width: 120px;  margin-top: 20px;" onclick="getSurveyList(0)">조회하기</button>
                            </div>
                            <!-- <div style="display: inline-block; width: 100%;    margin-top: 20px;">
                                <label style="float: left; padding-top: 10px;">총 <font color="red"><label id="my_item_total_count_1">0</label>개</font>의 게시물이 있습니다.</label>
                                <button class="btn btn-default btn-sm btn-delete" style="width: 100px; float: right;" onclick="survey_delete()">선택삭제</button>
                            </div> -->
                        </div>    
                        <div id="grouplistDiv" style=" over-flow:scroll;">
                        </div>   
                        <div class="blog-pagination">
                        </div>                                            
                    </div>                    
                </div>               
            </div>
        </div>
    </div>
</div>
