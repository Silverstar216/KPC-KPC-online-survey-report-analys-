<?php
/**
 * Author: KMC
 * Date: 10/6/15
 */

$site_url = site_url();

$user_uid = get_session_user_uid();

$user_level = get_session_user_level();

?>

<div class="header" style="z-index: 3000">
    <div class="top_menu" style="background: #696969;">
        <div id="cssmenu">
            <ul>                
                <?php
                $width1 = 16;
                if($user_level > 4) {
                    $width1 = 14;
                }
                ?>
                <li class='has-sub' style="width: <?=$width1?>%;"><a href='<?=$site_url?>survey/survey_list/public'><span>공개교육 설문</span></a>
                    <ul>
                        <li class='has-sub'><a href='<?=$site_url?>survey/survey_list/public'><span>교육과정</span></a></li>
                        <li class='has-sub'><a href='<?=$site_url?>survey/load_list/public'><span>설문목록</span></a></li>                        
                        <li class='has-sub'><a href='<?=$site_url?>survey/view?survey_flag=1'><span>설문작성</span></a></li>
                        <li class='has-sub'><a href='<?=$site_url?>reviewlog/showresult/public'><span>설문결과</span></a></li>
                        <!-- <li class='has-sub'><a href='<?=$site_url?>survey/draft_list/public'><span>작성중 설문</span></a></li> -->
                        <!-- <li class='has-sub'><a href='<?=$site_url?>survey/survey_list/public'><span>통계분석</span></a></li> -->
                    </ul>
                </li>
                <li class='has-sub' style="width: <?=$width1?>%"><a href='#'><span>맞춤형 설문</span></a>
                    <ul>
                        <li class='has-sub'><a href='<?=$site_url?>survey/draft_list/advanced'><span>설문현황</span></a></li>
                        <li class='has-sub'><a href='<?=$site_url?>survey/survey_list/advanced'><span>교육과정</span></a></li>
                        <li class='has-sub'><a href='<?=$site_url?>survey/load_list/advanced'><span>설문목록</span></a></li>
                        <li class='has-sub'><a href='<?=$site_url?>survey/view?survey_flag=0'><span>설문작성</span></a></li>                        
                        <li class='has-sub'><a href='<?=$site_url?>reviewlog/showresult/advanced'><span>설문결과</span></a></li>                        
                        <!-- <li class='has-sub'><a href='<?=$site_url?>survey/survey_list/advanced'><span>통계분석</span></a></li> -->
                    </ul>
                </li>
                <li class='has-sub' style="width: <?=$width1?>%"><a href='#'><span>진&nbsp;&nbsp;단</span></a>
                    <ul>
                        <li class='has-sub'><a href='<?=$site_url?>diagnosis/my_list/1'><span>진단현황</span></a></li>
                        <li class='has-sub'><a href='<?=$site_url?>diagnosis/my_list/2'><span>진단목록</span></a></li>                        
                        <li class='has-sub'><a href='<?=$site_url?>diagnosis/index'><span>진단작성</span></a></li>       
                        <li class='has-sub'><a href='<?=$site_url?>d_reviewlog/index'><span>진단결과</span></a></li>                 
                    </ul>
                </li>

                <li class='has-sub' style="width: <?=$width1?>%"><a href='#' ><span>문자메시지</span></a>
                    <ul>
                        <li class='has-sub'><a href='<?=$site_url?>notice'><span>일반문자</span></a></li>
                        <li class='has-sub'><a href='<?=$site_url?>notice/document'><span>문서포함</span></a></li>
                        <!--<li class='has-sub'><a href='<?=$site_url?>goji'><span>개별고지</span></a></li>-->
                        <li class='has-sub'><a href='<?=$site_url?>sendlog'><span>전송결과</span></a></li>
                        <li class='has-sub'><a href='<?=$site_url?>reservelog'><span>예약내역</span></a></li>                        
                    </ul>
                </li>

                <li class='has-sub' style="width: <?=$width1?>%"><a href='#'><span>전화번호관리</span></a>
                    <ul>                        
                        <li class='has-sub'><a href='<?=$site_url?>phone'><span>그룹관리</span></a></li>
                        <li class='has-sub'><a href='<?=$site_url?>phone/phonefile'><span>파일업로드</span></a></li>
                        <li class='has-sub'><a href='<?=$site_url?>phone/phonenumber'><span>등록번호</span></a></li>
                        <li class='has-sub' style = "width: 100px;"><a href='<?=$site_url?>phone/sendPhoneNumber'><span>발신번호변경</span></a></li>
                    </ul>
                </li>
                <li class='has-sub' style="width: <?=$width1?>%"><a href='#'><span>My Page</span></a>
                    <ul>
                        <!-- <li class='has-sub' style="width: 90px;"><a href="<?=$site_url?>join/member_update_view"><span>회원정보수정</span></a></li> -->
                        <li class='has-sub' style="width: 160px;"><a href="<?=$site_url?>join/change_password_view"  style="text-align: center;padding-left: 0;"><span>비밀번호변경</span></a></li>                        
                    </ul>
                </li>
                
                <?php
                if($user_level > "4")
                {
                ?>
                    <li class='has-sub' style="width: <?=$width1?>%"><a href='#'><span>홍보관리</span></a>
                    <ul>
                        <li class='has-sub'><a href='<?= $site_url ?>advert'><span>홍보작성</span></a></li>
                        <li class='has-sub'><a href='<?= $site_url ?>advert/view'><span>홍보목록</span></a></li>

                    </ul>
                </li>
                <?php
                } else {  //  홍보관리권한이 없으면
                }
                ?>
                    
            </ul>
        </div>
    </div>
    <!--
    <div class="top-nav top-nav-main">
        <div class="container">
            <div class="" style="margin-top: 20px">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="#" id="navbarDropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                문자서비스
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="<?=$site_url?>notice">일반문자</a>
                                <a class="dropdown-item" href="<?=$site_url?>notice/document">문서포함문자</a>
                                
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="#" id="navbarDropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                설문조사
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="<?=$site_url?>survey">단순설문</a>
                                <a class="dropdown-item" href="<?=$site_url?>survey/attached">문서포함설문</a>

                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="#" id="navbarDropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                전화번호관리
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="<?=$site_url?>phone">번호그룹</a>
                                <a class="dropdown-item" href="<?=$site_url?>phonenumber">전화번호</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="#" id="navbarDropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                마이페이지
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="<?=$site_url?>reservelog">예약내역</a>
                                <a class="dropdown-item" href="<?=$site_url?>sendlog">전송결과</a>
                                <a class="dropdown-item" href="<?=$site_url?>uselog">사용내역</a>
                                <a class="dropdown-item" href="<?=$site_url?>reviewlog">결과분석</a>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
    -->
</div>
