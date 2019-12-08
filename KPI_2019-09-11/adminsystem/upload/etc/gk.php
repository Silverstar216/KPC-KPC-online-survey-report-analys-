<?php	
	include_once('../../common.php');
	$ed_type = 'D';

	include_once('./01141205105701.htm');	 // 2998
if ($poll_type > '0') {	
?>
<script src="<?php echo G5_JS_URL ?>/jquery-1.8.3.min.js"></script>
<script type="text/javascript">
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
<?
}// 하단 첨부 끝.
if ($poll_type > '0') {	
}  else if ($pr_attach_flag){
} else {// 첨부 홍보가 이닌경우 
?>
<script src="<?php echo G5_JS_URL ?>/jquery-1.8.3.min.js"></script>
<script type="text/javascript">             
	var susinmenuPan = $('<div></div>').attr('id', 'susin_menu_pan');
	susinmenuPan.appendTo('body');	
	search_sms_list = function (ep,sk){
	        var params = { ep: ep,sk : sk };                        
	        $.ajax({            
	            url: "<?=G5_URL?>/service/get_susuin_menu.php",
	            cache:false,
	            timeout : 30000,
	            dataType:'html',
	            data:params,
	            type:'get',
	            success: function(data) {  
 			if (data == 'not') {                    
               		} else {
	                  		shtml = data;
	                  		$('#susin_menu_pan').html(shtml);                  
	                  		$('#susin_menu_pan').fadeIn('fast');                      
               		}
	            },
	            error: function (xhr, ajaxOptions, thrownError) {
	            }
	        });
	};
	search_sms_list('<?=$ep?>','<?=$sk?>');
</script>
<?php
}
?>	