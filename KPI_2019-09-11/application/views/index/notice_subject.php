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

<div class="container container-bg">
    <div id="content">
        <div class="" style="">
            <!--   -->
            <div id="contents">
                <div class="sub_con">
                    <div class="sub_title1">

                        <img src="<?=$site_url?>images/icon_title.png">공지사항</div>

                    <div class="bo_fx">
                        <div id="bo_list_total">
                            <span>전체 0건</span>&nbsp;&nbsp;/&nbsp;&nbsp;1 페이지
                        </div>

                        <table class="search_t2">
                        <tr>
                            <th  style="width:10%">번호</th>
                            <th  style="width:65%">제목</th>
                            <th  style="width:15%">날자</th>
                            <th  style="width:10%">조회수</th>
                        </tr>
                        <tr>

                        </tr>


                        </table>
                    </div>
                    <div id="bo_list" style="text-align: center"><label style="color: #ff0000">등록된 자료가 없습니다.</label></div>
                    <div class="notice_search">
                        <ul>
                            <li>
                                <select name="st" id="st">
                                    <!--<option value="all"<?php /*echo get_selected('all', $st); */?>>제목 + 내용</option>-->
                                    <option value="all">제목 + 내용</option>
                                    <option value="name">제목</option>
                                    <option value="hp" >내용</option>
                                </select>
                            </li>
                            <li style="padding-left:7px;"><input style="font-size: 14px"type="text" id="st_val" name="st_val" value=""></li>
                            <li style="cursor:pointer;    margin-left: 10px;"><button class="btn btn_search_ok"  onclick="">검색</button></li>
                        </ul>
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


