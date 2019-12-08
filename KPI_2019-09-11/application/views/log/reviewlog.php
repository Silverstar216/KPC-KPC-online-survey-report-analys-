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
                            <input type="hidden" id='survey_flag' value="<?php echo $survey_flag?>" >
                            <div class="search search-items" style="text-align: center;">
                                <label>검색기간 &nbsp;&nbsp;</label>
                                <input type="text" id="reviewlog_start_date" class="form-control input-inline reviewlog_start_datepicker" value="<?=$start_date?>" style="padding:2px 6px; height: 25px;">
						        <label>&nbsp;~&nbsp;</label>
                                <input type="text" id="reviewlog_end_date" class="form-control input-inline reviewlog_end_datepicker" value="<?=$end_date?>" style="padding:2px 6px; height: 25px;">    
                                <button class="btn btn-default btn-sm" style="width: 80px; " onclick="get_reviewlog_list(0)">검 색</button>
                            </div>
                            <div style="display: inline-block; width: 100%;    margin-top: 20px;">
                                <label style="float: left; padding-top: 10px;">총 <font color="red"><label id="totalcnt_1">0</label>개</font>의 게시물이 있습니다.</label>
                                <button class="btn btn-default btn-sm btn-delete" style="width: 100px; float: right;" onclick="delete_reviewlog()">선택삭제</button>
                            </div>
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
