<?php
define('_INDEX_', true);
define('_SIDEMENU_SERVICE_',true);
/*include_once('./_common.php');
// 초기화면 파일 경로 지정 : 이 코드는 가능한 삭제하지 마십시오.
if ($config['cf_include_index']) {
    if (!@include_once($config['cf_include_index'])) {
        die('기본환경 설정에서 초기화면 파일 경로가 잘못 설정되어 있습니다.');
    }
    return; // 이 코드의 아래는 실행을 하지 않습니다.
}
include_once('./_head.php');
*/?><!--
<div class="snb">
    <div class="leftbanner">
		<h2 class="h2_tit">스쿨뉴스소개</h2>
		<ul class="snb_li">
			<li class=""><a href="./introduce01.php">기술소개</a></li>
			<li class=""><a href="./introduce02.php">친환경서비스</a></li>
			<li class=""><a href="./introduce03.php">교원업무경감</a></li>
			<li class=""><a href="./introduce04.php">원활한 소통</a></li>			
		</ul>		
		<h2 class="h2_tit">고객센터</h2>
		<ul class="snb_li">
			<li class=""><a href="<?php /*echo G5_BBS_URL */?>/board.php?bo_table=notice">공지사항</a></li>
			<li class=""><a href="./introduce06.php">자주묻는질문</a></li>
			<li class=""><a href="<?php /*echo G5_BBS_URL */?>/board.php?bo_table=free">고객게시판(질의응답)</a></li>
			<li class=""><a href="<?php /*echo G5_BBS_URL */?>/board.php?bo_table=datamedia">자료실</a></li>
		</ul>
    <h2 class="h2_tit">요금안내</h2>		
		<ul class="snb_li">		
		<li class=""><a href="/introduce09.php">요금안내</a><li>
		</ul>
		<br>
		<?php /*include_once(G5_PATH.'/service/sample_send.php') */?>
	</div>	
	<div class="content listWrap" id="content">
		<?php	/*include_once(G5_PATH.'/intro/main.php');*/?>
	</div>	
</div>
--><?php
/*include_once('./_tail.php');*/
?>

<!--
    <h2 class="h2_tit mb20"><a href="./introduce09.php">요금안내</a></h2>
-->