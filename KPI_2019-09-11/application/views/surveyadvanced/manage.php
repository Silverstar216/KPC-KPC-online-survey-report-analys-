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
            <div class="sub-content">
                <?php if ($view_flag==1) {?>
                <div class="sub-title"><img src="<?=$site_url?>images/icon_title.png">전송한 설문목록</div>
                <?php }else{?>
                    <div class="sub-title"><img src="<?=$site_url?>images/icon_title.png">작성중 설문목록</div>
                <?php }?>
                <input type="hidden" id='view_flag' value="<?php echo $view_flag?>" >
                        <input type="hidden" id="hstval" value="<?php echo $stval?>" >

                    <div class="serv_t" style="    margin-left: 0;">
                        총 갯수 <?=$survey_total_count;?> 개
                    </div>
                    <div style="display: inline-block; width: 100%;    margin-top: 20px;">
                       <div style="    display: inline-block;">
                           <label style="margin-left:20px;">제목 : <input type="text" id="st_val" name="st_val" value="<?=$stval?>" style="width: 300px;height: 27px;"></label>

                           <a style="cursor:pointer" onclick="getSurveyList(0);"><img style="    margin-top: -2px;" src="<?=$site_url;?>images/btn/btn_search.png"></a>
                       </div>
                       <div style="    display: inline-block; float: right;">
                           <?php if ($view_flag==1) {?>
                           <button onclick="survey_public();" class="btn_modal_public btn " style="float: none" >선택 공개</button>
                           <?php }?>
                           <button onclick="survey_delete();" class="btn_modal_public btn " style="float: none">선택 삭제</button>
                       </div>
                    </div>
                <div id="grouplistDiv" style=" over-flow:scroll;">

                </div>

                <div class="blog-pagination">

                </div>

                    <div class="sub_img">
                        <img src="<?=$site_url?>images/bg/block.png">
                    </div>

                </div>
            </div>

            <!--   -->

        </div>
    </div>
</div>
