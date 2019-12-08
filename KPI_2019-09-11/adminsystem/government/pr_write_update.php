<?php
define('G5_IS_SERVICE', true);
include_once('../common.php');
if($is_guest)  
alert('회원이시라면 로그인 후 이용해 보십시오.', 
    G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/government/gov.php'));
if ($member['mb_level'] < 5){
    header('Location:'.G5_URL); 
    return; 
}
if (!trim($m1))
    alert('정상적인 경로가 아닙니다!',G5_URL.'/government/gov.php');
if ($m1 <> $member['mb_no'])
    alert('정상적인 경로가 아닙니다!!',G5_URL.'/government/gov.php');
if (!trim($elpr_ukey))
    alert('정상적인 경로가 아닙니다!!!',G5_URL.'/government/gov.php');
if (!trim($linktitle))
    alert('연결제목을 확인하십시오!',G5_URL.'/government/gov.php');
if (!trim($prlink))
    alert('연결 주소를 확인하십시오!',G5_URL.'/government/gov.php');

if ($elpr_ukey=='n') {
    $proc_type = 'new';
    $Sql = 'insert into ele_pr_master (elpr_mbid,elpr_title,elpr_udoc,elpr_wurl,elpr_stdt,elpr_eddt,elpr_time) values (';
    $Sql .= " '{$member['mb_no']}', ";
    $Sql .= " '{$linktitle}', ";
    $Sql .= " '{$udoc}', ";
    $Sql .= " '{$prlink}', ";
    $Sql .= " '{$statdate}', ";
    $Sql .= " '{$enddate}', ";
    $Sql .= " sysdate() )";
} else {
    $proc_type = 'mod';
    $Sql = 'update ele_pr_master set ';
    $Sql .= "elpr_title = '{$linktitle}', ";
    $Sql .= "elpr_wurl = '{$prlink}', ";
    $Sql .= "elpr_stdt = '{$statdate}', ";
    $Sql .= "elpr_eddt = '{$enddate}', ";
    $Sql .= "elpr_time = sysdate() ";
    $Sql .= "where elpr_mbid = '{$member['mb_no']}' and elpr_ukey = '{$elpr_ukey}' ";

}
sql_query($Sql);

goto_url(G5_URL.'/government/gov.php');
?>
