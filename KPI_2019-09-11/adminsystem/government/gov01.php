<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 
if($is_guest) {
	header('Location:'.G5_URL); 
	return;
}
if ($member['mb_level'] < 5){
	header('Location:'.G5_URL); 
	return;	
}
$ele_today = date("Y-m-d");// 처리
$token = get_token();
$sql_common = " from ele_pr_master ";
$sql_search = " where elpr_mbid = '{$member['mb_no']}'  ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        default :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "elpr_eddt desc,elpr_ukey";
    $sod = "desc";
}
$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt
            {$sql_common}
            {$sql_search}
            {$sql_order} ";

$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 10;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select *,
(select count(*) from ele_pr_history where elph_ukey = elpr_ukey) as click_cnt
            {$sql_common}
            {$sql_search}
            {$sql_order}
            limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['PHP_SELF'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '홍보 관리';

$colspan = 8;

$prsql = "select count(*) as totalcnt from ele_pr_master 
			                  where elpr_mbid = '{$member['mb_no']}' and '{$ele_today}' between elpr_stdt and elpr_eddt";
$prsql_row = sql_fetch($prsql);
$proc_count = $prsql_row['totalcnt'];			                  
?>
<?php echo $listall ?>    문서 <?php echo number_format($total_count) ?>건  / 진행중 홍보사항(<?=$proc_count?>건)
<div class="helpRow">아래를 클릭하시면</div>
<div id="current_pr_pan">
	<?php 
			$prsql = " select * from ele_pr_master 
			                  where elpr_mbid = '{$member['mb_no']}' and '{$ele_today}' between elpr_stdt and elpr_eddt
			                  order by elpr_eddt desc,elpr_stdt desc";		                  
			 $prresult = sql_query($prsql);			 
 			 for ($idx=0; $prrow=sql_fetch_array($prresult); $idx++) {
 			 	?>
 			 	<div class="prRow" id="prRow_<?=$idx+1?>"><a href="<?=$prrow['elpr_wurl']?>" target="_blank" ><?=$prrow['elpr_title']?></a></div>
 			 	<?php
 			 }
			$pr_cnt = $idx; 			 
 			 if ($idx == 0)	{
			echo '<div class="prRow">진행중인 홍보 사항이 없습니다.</div>';
 			 }
	?>	
</div>	 
<div class="helpRow">에 대해 알 수 있습니다.</div>
<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
<div class="sch_last">
    <label for="sfl" class="sound_only">검색대상</label>
    <select name="sfl" id="sfl">
        <option value="elpr_title"<?php echo get_selected($_GET['sfl'], "elpr_title"); ?>>제목</option>
    </select>
    <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>    
    <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
    <input type="submit" class="btn_submit" value="검색">   
</div>
</form>
<div class="bo_fx" id="write_new">
    <ul class="btn_bo_user">
        <li><a href="<?=$_SERVER['PHP_SELF']?>?elpr_ukey=n" class="btn_b01">신규 등록</a></li>
    </ul>
</div> 
<form name="fpolllist" id="fpolllist" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="<?php echo $token ?>">

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">번호</th>
        <th scope="col">제목</th>
        <th scope="col">연결주소</th>
        <th scope="col">시작일</th>        
        <th scope="col">종료일</th>        
        <th scope="col">노출수</th>
        <th scope="col">조회수</th>
        <th scope="col">관리</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $CurrNum = $total_count-$from_record-$i;
        $bg = 'bg'.($i%2);
        $s_mod = '<a href="/government/gov.php?elpr_ukey='.$row['elpr_ukey'].'">수정</a>';
        $active_class = '';
        $elpr_stdt = date("Y-m-d",strtotime($row['elpr_stdt']));
        $elpr_eddt = date("Y-m-d",strtotime($row['elpr_eddt']));
        if ($ele_today <= $elpr_eddt) {
        	if ($ele_today >= $elpr_stdt) {
        		$active_class = 'class="td_alive"';		
        	}
        }
    ?>

    <tr class="<?php echo $bg; ?>">
        <td class="td_num"><?=$CurrNum?></td>
        <td <?=$active_class?>><?=$row['elpr_title']?></td>
        <td><a href="<?=$row['elpr_wurl']?>" target="_blank" ><?=$row['elpr_wurl']?></a></td>
        <td class="td_date"><?=$elpr_stdt?></td>
        <td class="td_date"><?=$elpr_eddt?></td>
        <td class="td_num"><?=number_format($row['elpr_read'],0)?></td>
        <td class="td_num"><?=number_format($row['click_cnt'],0)?></td>
        <td class="td_mngsmall"><?=$s_mod ?></td>
    </tr>

    <?php
    }

    if ($i==0)
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>
</form>
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['PHP_SELF']}?$qstr&amp;m1=8&amp;m2={$pgMNo1}&amp;page="); ?>
<?php if ($pr_cnt > 1) {
?>
<SCRIPT TYPE="text/javascript">

function slide_pr_text() {	
	slideObj = $('#prRow_'+CurrentIndex);
	slideObj.slideUp("slow")
             refreshSlideInterval = setInterval(slide_text,2000);                     
}
function slide_text(){
     clearInterval(refreshSlideInterval);
    slideObj.insertAfter(EndslideObj);
    slideObj = $('#prRow_'+CurrentIndex);
    slideObj.css('display','block')
    EndslideObj = slideObj; 
    CurrentIndex++;     
    if (CurrentIndex > totalCount) {
        CurrentIndex = 1;
    }   
}
$(document).ready(function(){
	totalCount = <?=$pr_cnt ?>;
	CurrentIndex = 1;
	EndslideObj = $('#prRow_'+totalCount);	
	setInterval(slide_pr_text,3500);
});
</SCRIPT>
<?php
}
?>