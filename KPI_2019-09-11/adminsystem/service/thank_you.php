<?php
	include_once('../common.php');
	if (!$ep) {
		die();
	}
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<?php
	echo '<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10">'.PHP_EOL;
	echo '<meta name="HandheldFriendly" content="true">'.PHP_EOL;
	echo '<meta name="format-detection" content="telephone=no">'.PHP_EOL;
	echo '<link rel="stylesheet" href="'.G5_CSS_URL.'/eleview.css">'.PHP_EOL;
?>
<title>참여해주셔서 감사합니다.</title>
<!--[if lte IE 8]>
<script src="<?php echo G5_JS_URL ?>/html5.js"></script>
<![endif]-->
<script src="<?php echo G5_JS_URL ?>/jquery-1.8.3.min.js"></script>
<script src="<?php echo G5_JS_URL ?>/common.js"></script>
<script src="<?php echo G5_JS_URL ?>/wrest.js"></script>
</head>
<body>
<div id="wrapper">
    <div id="container">
	<div id="container_head">    	
		<div id="container_title">참여해 주셔서 감사합니다.</div>	
	</div> 
    </div>        
</div>
<div id="ft">
&nbsp;
</div>
<!-- } 하단 끝 -->
</body>
</html>
<?php echo html_end(); // HTML 마지막 처리 함수 : 반드시 넣어주시기 바랍니다. ?>