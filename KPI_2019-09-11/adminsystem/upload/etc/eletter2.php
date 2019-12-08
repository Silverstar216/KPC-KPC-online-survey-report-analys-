<?php	
	include_once('c:/eletter/common.php');
	$ed_type = 'D';
	include_once('./ele_doc_cnt2.php');
	include_once($filename);	
if ($poll_type > '0') {	
	if ($poll_type == 1) {
		$poll_type_str = '회신하기';
	} else {
		$poll_type_str = '회신하기';//설문 참여
	}
?>
<script src="<?php echo G5_JS_URL ?>/jquery-1.8.3.min.js"></script>
<script type="text/javascript">
	var linkPan = $('<div "></div>').attr('id', 'pollPan');
	linkPan.appendTo('body');
$("#pollPan").css({"width":"100%","text-align":"center"}); 	
	var linkButton = $('<button type="button" onclick="goPoll()"><?=$poll_type_str?></button>').attr('id', 'pollBtn');
	linkButton.appendTo('#pollPan');
	$("#pollBtn").css({"display":"inline-block","margin":"0","padding":"15px","border":"1px solid #ff3061","background":"#ff3061","color":"#fff","font-size":"1.0em", "text-decoration":"none","cursor":"pointer"}); 
function goPoll(){
	document.location.replace("http://www.schoolnews.or.kr/felv.php?ep=<?=$edoc_attach_poll_id?>&sk=<?=$sk?>");
}
</script>
<?php } ?>