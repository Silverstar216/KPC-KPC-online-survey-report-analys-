<?php
/**
 * Created by PhpStorm.
 * User: CHKD
 * Date: 10/9/2018
 */
defined('BASEPATH') OR exit('No direct script access allowed');
$site_url = site_url();
if (!isset($gst))
    $gst = 'all';
?>

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
                                <p>Home <span class="rt">&gt;</span>고객센터<span class="rt">&gt;</span>자주묻는 질문</p>
                            </div>
                        </div>

                        <?php
                        $user_id = get_session_user_id();
                        $user_uid = get_session_user_uid();
                        $user_level= get_session_user_level();
                        //                              include_once(FCPATH."adminsystem/Test.php");
                        include_once(FCPATH."adminsystem/bbs/faq.php");
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
