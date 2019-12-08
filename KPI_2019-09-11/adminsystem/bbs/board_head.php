<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
// 게시판 관리의 상단 내용
if (G5_IS_MOBILE) {
    // 모바일의 경우 설정을 따르지 않는다.
    include_once('./_head.php');
    echo stripslashes($board['bo_mobile_content_head']);
} else {
    @include ($board['bo_include_head']);
    echo stripslashes($board['bo_content_head']);
    if ($bo_table=='free') {
        $brd_title_html =  '고객게시판(질의응답)';
    } else if ($bo_table=='notice') {
        $brd_title_html =  '공지사항';    
    } else if ($bo_table=='tax') {
        $brd_title_html =  '전자세금 계산서발급';        
    } else if ($bo_table=='datamedia') {
        $brd_title_html =  '자료실';            
    } else {
        $brd_title_html =  $board['bo_subject'];   
    }
?>
<div class="snb">
    <div class="leftbanner">
        <h2 class="h2_tit">스쿨뉴스소개</h2>
        <ul class="snb_li">
            <li class=""><a href="<?php echo G5_URL ?>/introduce01.php">기술소개</a></li>
            <li class=""><a href="<?php echo G5_URL ?>/introduce02.php">친환경서비스</a></li>
            <li class=""><a href="<?php echo G5_URL ?>/introduce03.php">교원업무경감</a></li>
            <li class=""><a href="<?php echo G5_URL ?>/introduce04.php">원활한 소통</a></li>            
        </ul>       
        <h2 class="h2_tit">고객센터</h2>
        <ul class="snb_li">
            <li class=""><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=notice">공지사항</a></li>
            <li class=""><a href="<?php echo G5_URL ?>/introduce06.php">자주묻는질문</a></li>
            <li class=""><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=free">고객게시판(질의응답)</a></li>
            <li class=""><a href="<?php echo G5_BBS_URL ?>/board.php?bo_table=datamedia">자료실</a></li>           
        </ul>
       <h2 class="h2_tit">요금안내</h2>
	   	<ul class="snb_li">
            <li class=""><a href="<?php echo G5_URL ?>/introduce09.php">요금안내</a><li>
		  </ul>
	  	<br>        
        <?php include_once(G5_PATH.'/service/sample_send.php') ?>       
    </div>  
    <div class="content listWrap" id="content">
<div class="contentwrap"> 
    <div class="titlem1">
        <em><?=$brd_title_html?></em>      
         <div class="navgroup">     
                 <p>Home <span class="rt">&gt;</span> 고객센터 <span class="rt">&gt;</span><?=$brd_title_html?></p>
        </div>     
    </div>  
<?php }
?>
<div class="m7con">