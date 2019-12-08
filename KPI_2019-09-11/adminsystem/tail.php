<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 하단 파일 경로 지정 : 이 코드는 가능한 삭제하지 마십시오.
if ($config['cf_include_tail']) {
    if (!@include_once($config['cf_include_tail'])) {
        die('기본환경 설정에서 하단 파일 경로가 잘못 설정되어 있습니다.');
    }
    return; // 이 코드의 아래는 실행을 하지 않습니다.
}

if (G5_IS_MOBILE) {
    include_once(G5_MOBILE_PATH.'/tail.php');
    return;
}
?>
<!-- } 콘텐츠 끝 -->
<hr>
 <div class="footpsy">
    <ul>
    <li><a href="/bbs/content.php?co_id=provision">이용안내</a></li>
    <li><a href="/bbs/content.php?co_id=spampolicy">스팸정책</a></li>
    <li><a href="/bbs/content.php?co_id=privacy">개인정보취급방법</a></li>
    <li><a href="/bbs/content.php?co_id=emailpolicy">이메일무단수집거부</a></li>
    <li><a href="/bbs/board.php?bo_table=free">서비스문의</a></li>
    </ul>
</div> 
 <div id="footer">
  <div class="footer">
    <span class="foot_logo"><a href="http://www.sahack.or.kr" target="_blank"><img src="<?=G5_IMG_URL?>/leftlogo.png" alt="대한사립중고등학교"></a></span>       
    <address>
      <p class="log1">서울시 서초구  서초동 1460-14 재영빌딩 6층 <br>  Tel. 02-585-2359   |   Fax.02-585-2350</p>
        <p class="log2"> 서울시 종로구 사직로 113 사학회관 7층  <!-- <br> Tel. 02-739-6936   |   Fax.02-739-8124 --></p>
        </address>
    <p class="copy">Copyright (C) by <a href="#" target="_blank">HanCloud</a>.  All rights reserved.</p>
     <span class="foot_logo2"><a href="#" target="_blank"><img src="<?=G5_IMG_URL?>/rightlogo.png" alt="HanCloud"></a></span>
  </div>  
</div>
    <!--// footer -->
</div><!-- //#container -->
</div><!-- // #wrap --> 
<?php
if(G5_USE_MOBILE && G5_IS_REAL_MOBILE) {
    $seq = 0;
    $p = parse_url(G5_URL);
    $href = $p['scheme'].'://'.$p['host'].$_SERVER['PHP_SELF'];
    if($_SERVER['QUERY_STRING']) {
        $sep = '?';
        foreach($_GET as $key=>$val) {
            if($key == 'device')
                continue;

            $href .= $sep.$key.'='.strip_tags($val);
            $sep = '&amp;';
            $seq++;
        }
    }
    if($seq)
        $href .= '&amp;device=mobile';
    else
        $href .= '?device=mobile';
?>
<a href="<?php echo $href; ?>" id="device_change">모바일 버전으로 보기</a>
<?php
}
if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
?>
<!-- } 하단 끝 -->
<script>
$(function() {
    // 폰트 리사이즈 쿠키있으면 실행
    font_resize("container", get_cookie("ck_font_resize_rmv_class"), get_cookie("ck_font_resize_add_class"));
});
</script>
<?php
include_once(G5_PATH."/tail.sub.php");
?>