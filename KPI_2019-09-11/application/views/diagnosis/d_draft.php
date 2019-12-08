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
$str_end_date = strtotime($now_date.'+2 months');
$start_date = date('Y-m-01',$str_begin_date); 
$end_date = date('Y-m-01',$str_end_date); 
$str_end_date = strtotime($end_date.'-1 days');
$end_date = date('Y-m-d',$str_end_date); 
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
                            <div class="search">
                                <img src="<?=$site_url?>images/img/icon_item_red.png">
                                <b>조회조건</b>
                            </div>
                            <div class="search search-items" style="text-align: center;">
                                <!-- <input type="text" style="width: 40%" placeholder="설문제목을 입력해주세요." id="survey_searchname"></li>
                                <button class="btn btn-default btn-sm" style="width: 80px; " onclick="search()">검 색</button> -->
                                <ul>
                                    <li style="width: 30%; text-align: left">조회기간
                                    <input type="text" style="height: 28px; width: 30%;" id="diagnosis_begindate" value="<?=$start_date?>"> &nbsp;&nbsp; ~ &nbsp;&nbsp; 
                                    <input type="text" style="height: 28px; width: 30%;" id="diagnosis_enddate" value="<?=$end_date?>"></li>
                                    <li style="width: 22%">작성자
                                    <input type="text" id="diagnosis_admin"></li>                                    
                                    <li style="width: 22%">부서명
                                    <input type="text" id="diagnosis_groupname"></li>
                                    <li style="width: 25%">진단도구명
                                        <input type="text" id="diagnosis_tool" style="float:right">                                        
                                    </li>
                                </ul>
                            </div>
                            <div class="search search-btn" style="text-align: center;">
                                <button class="btn btn-default btn-sm" style="width: 120px; " onclick="getDiagnosisList()">조회하기</button>
                            </div>
                            <div style="display: inline-block; width: 100%;    margin-top: 20px;">
                            </div>
                            <div style="display: inline-block; width: 100%;    margin-top: 20px;">
                                <label style="float: left; padding-top: 10px;">총 <font color="red"><label id="my_item_total_count_1">0</label>개</font>의 게시물이 있습니다.</label>
                                <button class="btn btn-default btn-sm btn-delete" style="width: 100px; float: right;" onclick="search()">선택삭제</button>
                            </div>
                            <div id="grouplistDiv" style=" over-flow:scroll;">
                            </div>         
                        </div>                                      
                    </div>                    
                </div>               
            </div>
        </div>
    </div>
</div>
