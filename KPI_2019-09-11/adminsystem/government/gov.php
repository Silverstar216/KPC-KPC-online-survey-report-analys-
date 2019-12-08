<?php
define('G5_IS_SERVICE', true);
include_once('./_common.php');
if($is_guest) {
	header('Location:'.G5_URL); 
	return;
}
if ($member['mb_level'] < 5){
	header('Location:'.G5_URL); 
	return;	
}
include_once('./_head.php');
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/jquery-ui.css">', 0);
add_stylesheet('<link rel="stylesheet" href="./gov.css">', 0);
add_javascript('<script type="text/javascript" src="'.G5_JS_URL.'/jquery-ui.min.js"></script>', 0);
?>
<style type="text/css">
	
</style>
<div class="subVisual10">    
</div>
<div id="sub_content">  
<?php if (isset($elpr_ukey)) {
	include_once('/gov11.php');
} else {
	include_once('/gov01.php');
}
?>
</div>
<?php
include_once('./_tail.php');
?>
