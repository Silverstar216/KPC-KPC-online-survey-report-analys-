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
                $this->load->view('index/menu_main', $this->data);
                ?>               
                <div class="content listWrap" style = "float:right; width: 80%;">
                    <div class="contentwrap">

                        <div class="titlem1">
                            <em><?=$this->data['menu']?></em>
                            <div class="navgroup">
                                <?php
                                $table_name = $this->data['submenu'];
                                ?>
                                <p>Home <span class="rt">&gt;</span><?=$this->data['menu']?><span class="rt">&gt;</span><?=$table_name?></p>
                            </div>
                        </div>
                        <div class="m7con">
                            <div class="search">
                                <img src="<?=$site_url?>images/img/icon_item_red.png">
                                <b>조회조건</b>
                            </div>
                            <div class="search search-items">
                                <input type="hidden" id='view_flag' value="<?php echo $view_flag?>" >
                                <input type="hidden" id="hstval" value="<?php echo $stval?>" >                                
                                <ul>
                                    <li style="width: 50%">조회기간
                                    <input type="date" id="survey_begindate" style="line-height: 12px;"> ~ 
                                    <input type="date" id="survey_enddate" style="line-height: 12px;"></li>
                                    <li style="width: 25%">담당자
                                    <input type="text" id="survey_admin"></li>                                    
                                    <li style="width: 25%">부서명
                                    <input type="text" id="survey_groupname"></li>
                                </ul>
                                <ul>
                                    <li style="width: 25%">사업팀
                                    <input type="text" id="survey_team"></li>
                                    <li style="width: 25%">고객사명
                                    <input type="text" id="survey_customer"></li>
                                    <li style="width: 25%">과정명
                                    <input type="text" id="survey_customer"></li>
                                    <li style="width: 25%">설문명
                                    <input type="text" id="survey_customer"></li>
                                </ul>
                            </div>
                            <div class="search search-btn" style="text-align: center;">
                                <button class="btn btn-default btn-sm" style="width: 120px; " onclick="search()">조회하기</button>
                            </div>
                            <div style="display: inline-block; width: 100%;    margin-top: 20px;">
                            </div>
                        </div>    
                        <div id="grouplistDiv" style=" over-flow:scroll;">
                        </div>                    
                    </div>                    
                </div>               
            </div>
        </div>
    </div>
</div>
