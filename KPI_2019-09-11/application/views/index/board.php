<?php
/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 2/29/2016
 * Time: 9:47 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
$site_url = site_url();
?>
<!--<input id="survey_attached" type="hidden" value="--><?//=$key?><!--">-->

<div class="container container-bg" id = "documentArea">
    <div id="content" style ="background: none;min-height: 620px;">
        <div id="contents">
            <div class="m_con">
<!--                문자받기관련css정의-->
                <style>
                    #contents .m_con .m_left .left_sample p {
                        font-size: 11px;
                        line-height: 16px;
                        padding: 20px 0 20px 15px;
                    }
                </style>
                <?php
                $this->load->view('index/menu', $this->data);
                ?>
                <div class="content listWrap" style = "width:810px;">
                    <div class="contentwrap">
                        <div class="titlem1">
                            <em>고객센터</em>
                            <div class="navgroup">
                                <?php
                                $table_name = '';
                                if($table_kind == 'free')
                                    $table_name = '고객게시판';
                                else if($table_kind == 'datamedia')
                                    $table_name = '자료실';
                                else if($table_kind == 'notice')
                                    $table_name = '공지사항';
                                ?>
                                <p>Home <span class="rt">&gt;</span>고객센터<span class="rt">&gt;</span><?=$table_name?></p>
                            </div>
                        </div>

                        <?php
                        $user_id = get_session_user_id();
                        $user_uid = get_session_user_uid();
                        $user_level= get_session_user_level();
//                              include_once(FCPATH."adminsystem/Test.php");
                        include_once(FCPATH."adminsystem/bbs/board.php");
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

