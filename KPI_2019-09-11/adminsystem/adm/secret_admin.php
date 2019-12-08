<?
$sub_menu = "999999";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');

if (!isset($n)){
	goto_url('./member_list.php');    	
	return;
}

if (strtolower($n) == strtolower($config['cf_admin'])) {
	goto_url('./member_list.php');    	
	return;
}	
$mb = get_member($n);
    if (!$mb['mb_id'])
        alert('존재하지 않는 회원자료입니다.');

// 로그아웃 한다. 
session_unset(); // 모든 세션변수를 언레지스터 시켜줌
session_destroy(); // 세션해제함

// 정보로 자동로그인을 만든다. 
    $key = md5($_SERVER['SERVER_ADDR'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $mb['mb_password']);
    set_cookie('ck_mb_id', $mb['mb_id'], 86400 * 31);
    set_cookie('ck_auto', $key, 86400 * 31);
// 자동로그인 창을 띄운다. 
goto_url(G5_URL);    
?>