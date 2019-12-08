<?php
	include_once('./_common.php');
	if($is_guest) {
		header('Location:'.G5_URL); 
		return;
	}
	if ($member['mb_level'] < 5){
		header('Location:'.G5_URL); 
		return;	
	}	
	//add_javascript('<script type="text/javascript" src="'.G5_JS_URL.'/jquery-1.8.3.min.js"></script>', 0);
	if(!isset($m1))alert('잘못된 접근입니다!!!.', $url);	 	
	if(!isset($linktitle))
    		alert('잘못된 접근입니다!!!.', $url);	 	
             if(!isset($prlink))
    		alert('잘못된 접근입니다!!!.', $url);	 	    	
	$ele_today = date("Y-m-d");
	if ($elpr_ukey == 'n') {
		$exceptSql = '';
	} else {
		$exceptSql = "and elpr_ukey != '{$elpr_ukey}' ";
	}
	$prsql = "select count(*) as totalcnt from ele_pr_master 
			                  where elpr_mbid = '{$m1}' ".$exceptSql."and '{$ele_today}' between elpr_stdt and elpr_eddt";                 
	$prsql_row = sql_fetch($prsql);
	$proc_count = $prsql_row['totalcnt'];			                  
	include_once('./sample01.htm');	   
?>
<script src="<?php echo G5_JS_URL ?>/jquery-1.8.3.min.js"></script>
<script type="text/javascript">             
	var linkbackPan = $('<div></div>').attr('id', 'eletterprbackPan');
	linkbackPan.appendTo('body');
	linkbackPan.css('text-align','center');
	linkPan = $('<div class="help_pr_text">아래를 클릭하시면</div>');
	linkPan.appendTo('#eletterprbackPan');
	linkPan = $('<div></div>').attr('id', 'eletterprPan');
	linkPan.appendTo('#eletterprbackPan');
	$("#eletterprPan").css({"width":"100%","text-align":"center","height":"45px","overflow": "hidden"}); 	
	linkprObj = $('<div class="prRow"><a href="<?=$prlink?>" target="_blank" ><?=$linktitle?></a></div>').attr('id', 'prRow_1');
	linkprObj.appendTo('#eletterprPan');		
<?php 
if ($proc_count > 0){
			$prsql = " select * from ele_pr_master 
			                  where elpr_mbid = '{$m1}' {$exceptSql} and '{$ele_today}' between elpr_stdt and elpr_eddt
			                  order by elpr_eddt desc,elpr_stdt desc";		                  
			 $prresult = sql_query($prsql);			 
 			 for ($idx=2; $prrow=sql_fetch_array($prresult); $idx++) {
 			 	?>
	linkprObj = $('<div class="prRow"><a href="<?=$prrow['elpr_wurl']?>" target="_blank" ><?=$prrow['elpr_title']?></a></div>').attr('id', 'prRow_<?=$idx?>');
	linkprObj.appendTo('#eletterprPan');	
 			 	<?php
 			 }
?>
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
	totalCount = <?=$proc_count+1 ?>;
	CurrentIndex = 1;
	EndslideObj = $('#prRow_'+totalCount);	
	setInterval(slide_pr_text,3500);
});
<?php
}
?>		
	linkPan = $('<div class="help_pr_text">에 대해 알 수 있습니다.</div>');
	linkPan.appendTo('#eletterprbackPan');
$('.prRow').css('margin','15px auto');
$('.help_pr_text').css('margin','0 auto');
</script>