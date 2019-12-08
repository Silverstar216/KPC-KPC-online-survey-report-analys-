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
                            <div class="search search-items">
                                <ul>
                                    <li style="width: 50%">조회기간
                                    <input type="text" style="height: 28px; width: 35%;" id="diagnosis_begindate" value="<?=$start_date?>"> &nbsp;&nbsp; ~ &nbsp;&nbsp; 
                                    <input type="text" style="height: 28px; width: 35%;" id="diagnosis_enddate" value="<?=$end_date?>"></li>
                                    <li style="width: 25%">담당자
                                    <input type="text" id="diagnosis_admin"></li>                                    
                                    <li style="width: 25%">부서명
                                    <input type="text" id="diagnosis_groupname" style="float:right"></li>
                                </ul>
                                <ul>
                                    <li style="width: 25%">사업팀
                                    <input type="text" id="diagnosis_team" style="width: 70%"></li>
                                    <li style="width: 25%">고객사명
                                    <input type="text" id="diagnosis_customer" style="width: 70%"></li>
                                    <li style="width: 25%">과정명
                                    <input type="text" id="diagnosis_education" style="width: 70%"></li>
                                    <li style="width: 25%">교육차수
                                    <input type="text" id="diagnosis_count" style="width: 70%; float:right"></li>
                                </ul>
                                <ul>
                                    <li style="width: 25%">진단명
                                    <input type="text" id="diagnosis_name" style="width: 70%"></li>
                                    <li style="width: 25%">시행명
                                    <input type="text" id="diagnosis_executename" style="width: 70%"></li>
                                    <form id="upload_erp" method="post" enctype="multipart/form-data" action="">                                        
                                        <li style="width: 40%">진단과정엑셀
                                            <input type="file" id="excel_diagnosis_file" accept="Excel/*.xlsx" name="excel_diagnosis_file" style="display: inline-block;">
                                        </li>
                                        <li style="width: 10%; float:right">
                                            <input class="btn btn-delete btn-sm" style="width: 100%" type="button" value="올리기" onclick="onUploadDiagnosisExcel()">
                                        </li>
                                    </form>
                                </ul>
                            </div>
                            <div class="search search-btn" style="text-align: center;">
                                <button class="btn btn-default btn-sm" style="width: 120px; " onclick="getDiagnosisList()">조회하기</button>
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
