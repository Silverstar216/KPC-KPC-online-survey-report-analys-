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
                            <em><?=$this->data['submenu']?></em>
                            <div class="navgroup">
                                <?php
                                $table_name = $this->data['submenu'];
                                ?>
                                <p>Home <span class="rt">&gt;</span><?=$this->data['menu']?><span class="rt">&gt;</span><font color="red"><?=$table_name?></font></p>
                            </div>
                        </div>
                        <div class="m7con">                            
                            <div class="search" style="text-align: center;">                                
                                <label style="width: 100%;">신규로 설문을 작성하시는 분께서는 좌측 <b>신규작성</b>을, 기존에 작성하신 설문이 있으신분께서는</label>
                                <label>우측 <b>불러오기</b>를 클릭해주세요.</label>
                            </div>
                            <div class="search search-items">                        
                                <table align="center" style="width:50%; text-align: center; border-top: 2px solid #3d4144;">                              
                                    <tr>
                                        <td style="width:25%; padding: 6px 10px; border: 1px solid #cccdce;">
                                            <img style="padding: 15px;" src="<?=$site_url?>images/img/create_new_survey.png">
                                            <button class="btn btn-default btn-sm" style="width: 120px; " onclick="create_new_survey(<?=$survey_flag?>);">신규작성</button>
                                        </td>
                                        <td style="width:25%; padding: 6px 10px; border: 1px solid #cccdce;">
                                            <img style="padding: 15px;" src="<?=$site_url?>images/img/load_draft_survey.png">
                                            <button class="btn btn-default btn-sm" style="width: 120px; " onclick="browse_draft_survey(<?=$survey_flag?>)">불러오기</button>
                                        </td>                                        
                                    </tr>
                                </table>                                
                            </div>                            
                        </div>    
                    </div>                    
                </div>               
            </div>
        </div>
    </div>
</div>
