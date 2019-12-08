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
        <li class="title">소개</li>
        <li class="sub"><a href="<?=$site_url?>index/introduce_img?key=기술소개">기술소개</a></li>
        <li class="sub"><a data-fancybox href="<?=$site_url?>include/School.mp4" class="video-popup">동영상보기</a></li>
        <li class="title">고객센터</li>
        <li class="sub"><a href="javascript:onNotice_Subject()">공지사항</a></li>
        <li class="sub"><a href="javascript:onOften_Qeustions()">자주묻는 질문</a></li>
        <li class="sub"><a href="javascript:onBoardView()">고객게시판</a></li>
        <li class="sub"><a href="javascript:onDataView()">자료실</a></li>
        <li class="title">요금안내</li>
        <li class="sub"><a href="javascript:onMoney_Introduce()">요금안내</a></li>
    </ul>
    <div class="left_sample">
        <p>
        휴대폰 번호를 입력 후 </br>
        문자받기를 누르시면 스쿨뉴스</br>
        Sample을 받아보실 수 있습니다.
        </p>
        <div class="sample_num">
            <span>받을번호</span>
            <input name="myphone" id="myphone" type="text" size="15" maxlenght="13">
            <input type="button" value="문자받기" id="sample_btn" class="btnT3" onclick="javascript:sample_send_func()">
            <!--<img src="<?/*=$site_url*/?>images/btn/btn_sms.png">-->
        </div>
    </div>
</div>
<script type="text/javascript">

</script>