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

<div class="m_left" style="width: 18%">
    <ul>
        <li class="title"><?=$menu?></li>
        <?php
            if ($menu == '공개교육 설문') {
        ?>
            <li class=<?php if($submenu === '교육과정') echo "selected_menu"; else echo "sub";?>><a href="<?=$site_url?>survey/survey_list/public">교육과정</a></li>
            <li class=<?php if($submenu === '설문목록') echo "selected_menu"; else echo "sub";?>><a href="<?=$site_url?>survey/load_list/public">설문목록</a></li>
            <li class=<?php if($submenu === '설문작성') echo "selected_menu"; else echo "sub";?>><a href="<?=$site_url?>survey/view?survey_flag=1">설문작성</a></li>
            <li class=<?php if($submenu === '설문결과') echo "selected_menu"; else echo "sub";?>><a href="<?=$site_url?>reviewlog/showresult/public">설문결과</a></li>
            <!-- <li class=<?php if($submenu === '통계분석') echo "selected_menu"; else echo "sub";?>><a href="<?=$site_url?>reviewlog/showresult/public">통계분석</a></li>             -->
        <?php
            }
        ?>

        <?php
            if ($menu == '맞춤형 설문') {
        ?>
            <li class=<?php if($submenu === '교육과정') echo "selected_menu"; else echo "sub";?>><a href="<?=$site_url?>survey/survey_list/advanced">교육과정</a></li>
            <li class=<?php if($submenu === '설문목록') echo "selected_menu"; else echo "sub";?>><a href="<?=$site_url?>survey/load_list/advanced">설문목록</a></li>
            <li class=<?php if($submenu === '설문작성') echo "selected_menu"; else echo "sub";?>><a href="<?=$site_url?>survey/view?survey_flag=0">설문작성</a></li>
            <li class=<?php if($submenu === '설문결과') echo "selected_menu"; else echo "sub";?>><a href="<?=$site_url?>reviewlog/showresult/advanced">설문결과</a></li>
            <!-- <li class=<?php if($submenu === '통계분석') echo "selected_menu"; else echo "sub";?>><a href="<?=$site_url?>reviewlog/showresult/public">통계분석</a></li>             -->
        <?php
            }
        ?>

        <?php
            if ($menu == '진단') {
        ?>
            <li class=<?php if($submenu === '진단현황') echo "selected_menu"; else echo "sub";?>><a href="<?=$site_url?>diagnosis/my_list/1">진단현황</a></li>
            <li class=<?php if($submenu === '진단목록') echo "selected_menu"; else echo "sub";?>><a href="<?=$site_url?>diagnosis/my_list/2">진단목록</a></li>
            <li class=<?php if($submenu === '진단작성') echo "selected_menu"; else echo "sub";?>><a href="<?=$site_url?>diagnosis/index">진단작성</a></li>
            <li class=<?php if($submenu === '진단결과') echo "selected_menu"; else echo "sub";?>><a href="<?=$site_url?>d_reviewlog/index">진단결과</a></li>
        <?php
            }
        ?>

        <?php
            if ($menu == '문자메시지') {
        ?>
            <li class=<?php if($submenu === '일반문자') echo "selected_menu"; else echo "sub";?>><a href="<?=$site_url?>notice/index">일반문자</a></li>
            <li class=<?php if($submenu === '문서포함') echo "selected_menu"; else echo "sub";?>><a href="<?=$site_url?>notice/document">문서포함</a></li>
            <li class=<?php if($submenu === '전송결과') echo "selected_menu"; else echo "sub";?>><a href="<?=$site_url?>sendlog">전송결과</a></li>
            <li class=<?php if($submenu === '예약내역') echo "selected_menu"; else echo "sub";?>><a href="<?=$site_url?>reservelog">예약내역</a></li>
        <?php
            }
        ?>

        <?php
            if ($menu == '전화번호관리') {
        ?>
            <li class=<?php if($submenu === '번호그룹') echo "selected_menu"; else echo "sub";?>><a href="<?=$site_url?>phone">번호그룹</a></li>
            <li class=<?php if($submenu === '번호파일') echo "selected_menu"; else echo "sub";?>><a href="<?=$site_url?>phone/phonefile">번호파일</a></li>
            <li class=<?php if($submenu === '등록번호') echo "selected_menu"; else echo "sub";?>><a href="<?=$site_url?>phone/phonenumber">등록번호</a></li>
            <li class=<?php if($submenu === '발신번호') echo "selected_menu"; else echo "sub";?>><a href="<?=$site_url?>phone/sendPhoneNumber">발신번호</a></li>                                    
        <?php
            }
        ?>

        <?php
            if ($menu == 'My Page') {
        ?>
            <li class=<?php if($submenu === '회원정보 수정') echo "selected_menu"; else echo "sub";?>><a href="<?=$site_url?>join/member_update_view">회원정보 수정</a></li>
            <li class=<?php if($submenu === '전송현황') echo "selected_menu"; else echo "sub";?>><a href="#">전송현황</a></li>
            <li class=<?php if($submenu === '비밀번호 변경') echo "selected_menu"; else echo "sub";?>><a href="<?=$site_url?>join/change_password_view">비밀번호 변경</a></li>
            <!-- <li class=<?php if($submenu === '회신문서') echo "selected_menu"; else echo "sub";?>><a href="#">회신문서</a></li>             -->
        <?php
            }
        ?>

        <?php
            if ($menu == '홍보관리') {
        ?>
            <li class=<?php if($submenu === '홍보작성') echo "selected_menu"; else echo "sub";?>><a href="<?=$site_url?>advert">홍보작성</a></li>
            <li class=<?php if($submenu === '홍보목록') echo "selected_menu"; else echo "sub";?>><a href="<?=$site_url?>advert/view">홍보목록</a></li>            
        <?php
            }
        ?>


    </ul>    
</div>
<script type="text/javascript">

</script>