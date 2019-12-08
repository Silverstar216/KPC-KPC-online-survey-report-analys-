<?php	
	header('Content-Type: text/html; charset=utf8');
		
	include_once('../../common.php');
	$ed_type = 'D';
	include_once('./ele_doc_cnt.php');
	include_once($filename);	
				
	if ($poll_type > 0) {	
		if ($poll_type == 1) {
			$poll_type_str = '회신하기';
		} else {
			$poll_type_str = '설문 참여';//설문 참여
		}
?>
<script src="<?php echo G5_JS_URL ?>/jquery-1.8.3.min.js"></script>
<script type="text/javascript">
	var linkPan = $('<div "></div>').attr('id', 'pollPan');
	linkPan.appendTo('body');
$("#pollPan").css({"width":"100%","text-align":"center", "position":"absolute"}); 	
	var linkButton = $('<button type="button" onclick="goPoll()"><?=$poll_type_str?></button>').attr('id', 'pollBtn');
	linkButton.appendTo('#pollPan');
	$("#pollBtn").css({"width":"20%", "display":"inline-block","margin":"0","padding":"15px","border":"1px solid #ff3061","background":"#ff3061","color":"#fff","font-size":"1.0em", "text-decoration":"none","cursor":"pointer"}); 
function goPoll(){
	document.location.replace("http://www.schoolnews.or.kr/felv.php?ep=<?=$edoc_attach_poll_id?>&sk=<?=$sk?>");
}
</script>
<?php }  else if ($pr_attach_flag){
	$prlink     = '/elepr.php?n='.$pr_list[0]['elpr_ukey'].'&s='.$sk;
	$linktitle  = $pr_list[0]['elpr_title'];
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
if ($proc_count >1){
 			 for ($idx=1; $idx < $proc_count ; $idx++) {
				$prlink     = '/elepr.php?n='.$pr_list[$idx]['elpr_ukey'].'&s='.$sk;
				$linktitle  = $pr_list[$idx]['elpr_title'];
				$CurrIndex = $idx+1;
 			 	?>
	linkprObj = $('<div class="prRow"><a href="<?=$prlink?>" target="_blank" ><?=$linktitle?></a></div>').attr('id', 'prRow_<?=$CurrIndex?>');
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
	totalCount = <?=$proc_count ?>;
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
<?php } ?>