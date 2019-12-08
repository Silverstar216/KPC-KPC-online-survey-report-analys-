<?php
/**
 * Created by PhpStorm.
 * User: CHKD
 * Date: 10/9/2018
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
                        <div class="m7con">
                            <input type="hidden" id='view_flag' value="<?php echo $view_flag?>" >                            
                            <input type="hidden" id="hstval" value="<?php echo $stval?>" >
                            <input type="hidden" id='survey_flag' value="<?php echo $survey_flag?>" >
                            <input type="hidden" id='prev_education_id' value="<?php echo $prev_education_id?>" >

                            <input type="hidden" id='sms_available' value="<?php echo $sms_available?>" >
                            <input type="hidden" id='education_course' value="<?php echo $education_course?>" >
                            <input type="hidden" id='education_customer' value="<?php echo $education_customer?>" >
                            <input type="hidden" id='education_teacher' value="<?php echo $education_teacher?>" >

                            <input type="hidden" id='education_title' value="<?php echo $education_title?>" >
                            <input type="hidden" id='survey_start_date' value="<?php echo $survey_start_date?>" >
                            <input type="hidden" id='survey_end_date' value="<?php echo $survey_end_date?>" >                            
                            <div class="search search-items" style="text-align: center;">
                                <input type="text" style="width: 40%; height: 28px;" placeholder="설문제목을 입력해주세요." id="survey_name"></li>
                                <button class="btn btn-default btn-sm" style="width: 80px; " onclick="getSurveyList(0)">검 색</button>
                            </div>       
                            <div style="display: inline-block; width: 100%;    margin-top: 20px;">
                                <label style="float: left; padding-top: 10px;">총 <font color="red"><label id="my_item_total_count_1">0</label>개</font>의 게시물이 있습니다.</label>
                                <div style="display: inline-block; width: 30%;    float: right; margin-top: 10px;">
                                    <button class="btn btn-default btn-sm" style="width: 100px; " onclick="survey_show_public()">선택공개</button>
                                    <button class="btn btn-default btn-sm btn-delete" style="width: 100px; float: right;" onclick="survey_delete()">선택삭제</button>
                                </div>
                            </div>                     
                        </div>    
                        <div id="grouplistDiv" style=" over-flow:scroll;">
                        </div>   
                        <div class="blog-pagination">
                        </div>                            
                    </div>        
                    <div class="search" style="text-align: center; font-size: 13px; font-weight: bold;">
                        <!-- <img src="<?=$site_url?>images/img/information.png"> -->
                        공개된 설문은 관리자만 삭제할 수 있습니다.  
                    </div>            
                </div>               
            </div>
        </div>
    </div>
</div>
