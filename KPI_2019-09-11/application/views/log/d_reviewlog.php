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
                            <div class="search">
                                <img src="<?=$site_url?>images/img/icon_item_red.png">
                                <b>조회조건</b>
                            </div>
                            <div class="search search-items" style="text-align: center;">
                                <ul>
                                    <li style="width: 8%; text-align: left">조회기간</li>
                                    <li style="width: 26%; text-align: left">
                                    <input type="text" style="height: 28px; width: 40%;" id="diagnosis_begindate" value="<?=$start_date?>"> &nbsp; ~ &nbsp; 
                                    <input type="text" style="height: 28px; width: 40%;" id="diagnosis_enddate" value="<?=$end_date?>"></li>
                                    <li style="width: 8%">작성자</li>
                                    <li style="width: 10%">
                                    <input type="text" id="diagnosis_admin" style="width: 95%;"></li>                                    
                                    <li style="width: 8%">부서명</li>
                                    <li style="width: 10%">
                                    <input type="text" id="diagnosis_groupname" style="width: 95%;"></li>
                                    <li style="width: 8%; text-align: left">진단명</li>
                                    <li style="width: 12%; text-align: left">
                                        <input type="text" id="diagnosis_name" style="width: 100%;">                                        
                                    </li>
                                </ul>
                                <ul>
                                    <li style="width: 8%; text-align: left">고객사명</li>
                                    <li style="width: 20%; text-align: left">
                                        <input type="text" id="diagnosis_customer" style="width: 95%;">                                        
                                    </li>                                    
                                    <li style="width: 10%; text-align: left">교육과정명</li>
                                    <li style="width: 12%; text-align: left">
                                        <input type="text" id="diagnosis_education" style="width: 95%;">                                        
                                    </li>
                                    <li style="width: 8%; text-align: left">교육차수</li>
                                    <li style="width: 12%; text-align: left">
                                        <input type="text" id="diagnosis_countname" style="width: 95%;">                                        
                                    </li>
                                    <li style="width: 8%; text-align: left">시행명</li>
                                    <li style="width: 12%; text-align: left">
                                        <input type="text" id="diagnosis_executename" style="width: 100%; float:right">                                        
                                    </li>                                   
                                </ul>
                            </div>
                            <div class="search search-btn" style="text-align: center;">
                                <button class="btn btn-default btn-sm" style="width: 120px; " onclick="search()">조회하기</button>
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
