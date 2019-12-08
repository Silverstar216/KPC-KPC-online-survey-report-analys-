<?php
define('G5_IS_SERVICE', true);
include_once('./_common.php');

if (isset($_REQUEST['m1'])) {
    $pgMNo = (int)$_REQUEST['m1'];
} else {
    $pgMNo = 1;
}
if (isset($_REQUEST['m2'])) {
    $pgMNo1 = (int)$_REQUEST['m2'];
} else {
    if ($pgMNo == 8)	{	
    	$pgMNo1 = 2;
    } else {
    	$pgMNo1 = 1;
    }
}
$tlinkref = '';
if ($pgMNo ==  4) {
     if (isset($pt)) {
            $tlinkref = '&pt='.$pt.'&udoc='.$udoc.'&udcn='.$udcn;    
    } else {
            $tlinkref = '';
    }
    if (isset($stitle)) {
        if ($stitle != '') $tlinkref = $tlinkref .'&stitle='.$stitle;
    }    
}
// if($is_guest)  alert('회원이시라면 로그인 후 이용해 보십시오.', 	G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/serv.php?m1='.$pgMNo.'&m2='.$pgMNo1));

include_once('./_head.php');
$ins_home_file = 'service/serv'.$pgMNo.$pgMNo1.'.html';
include_once($ins_home_file);
include_once('./_tail.php');
?>
