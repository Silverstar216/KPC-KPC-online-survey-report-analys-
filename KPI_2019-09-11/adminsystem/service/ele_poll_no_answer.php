<?php
define('G5_IS_SERVICE', true);
include_once('../common.php');
$pgMNo = 8;
$pgMNo1 = $ew;
$total_cnt = $sc;
if($is_guest)  
alert('회원이시라면 로그인 후 이용해 보십시오.', 
    G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/serv.php?m1='.$pgMNo.'&m2='.$pgMNo1));

include_once('../_head.php');
          $res = sql_fetch("select * from epoll_master where eplm_ukey='{$ep}' and eplm_mbid='{$member['mb_no']}' ");

    if (!$res)   {
        alert('존재 하지 않는 문서입니다.',G5_URL);
    }    
    $eplm_ukey = $res['eplm_ukey'];
    $eplm_mbid = $res['eplm_mbid'];
    $m_title       = $res['eplm_title'];
    $eplm_qcnt  = $res['eplm_qcnt'];
    $polltype      = $res['eplm_gubn'];     
    $as_type     = $res['eplm_type']; 

    //echo $eplm_ukey.'/'.$eplm_mbid.'/'.$m_title.'/질문 수 : '.$eplm_qcnt.'/'.$polltype.'<br>';
$sql = " select count(*) as cnt FROM sms5_history where hs_flag = '1' ".
            "and sms5_history.wr_no in (SELECT sms5_write.wr_no FROM sms5_write ".
            "where (wr_udoc = (SELECT edoc_ukey FROM edoc_master where edoc_attach_poll_id = '{$ep}')) or (wr_poll = '{$ep}')) ".
            "and hs_no in (select epls_usms from epoll_answer where epls_ukey =  '{$ep}') ";

$row = sql_fetch($sql);
$answer_count = $row['cnt'];
//if ($answer_count < 30) {// 응답자가 30이하이다
    if ($polltype =='2') {
        alert('익명 설문조사는 미응답 리스트가 없습니다!!!','/serv.php?m1=8&m2='.$pgMNo1.'&page='.$page);
    }
//}

$sql = " select count(*) as cnt FROM sms5_history where hs_flag = '1' ".
            "and sms5_history.wr_no in (SELECT sms5_write.wr_no FROM sms5_write ".
           "where (wr_udoc = (SELECT edoc_ukey FROM edoc_master where edoc_attach_poll_id = '{$ep}')) or (wr_poll = '{$ep}')) ".
            "and hs_no not in (select epls_usms from epoll_answer where epls_ukey =  '{$ep}') ";           

$row = sql_fetch($sql);
$total_count = $row['cnt'];


$rows = 30;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = "SELECT hs_name,hs_hp FROM sms5_history where hs_flag = '1' ".
            "and sms5_history.wr_no in (SELECT sms5_write.wr_no FROM sms5_write ".
            "where (wr_udoc = (SELECT edoc_ukey FROM edoc_master where edoc_attach_poll_id = '{$ep}')) or (wr_poll = '{$ep}')) ".
            "and hs_no not in (select epls_usms from epoll_answer where epls_ukey =  '{$ep}') ".
            " order by hs_no limit {$from_record}, {$rows} ";
$result = sql_query($sql);

?>    
<div class="titlegroup">
<?php if ($pgMNo1 == 4) { ?>    
     <em>회신문서 미응답</em>  
<?php } else { ?>   
     <em>설문조사 미응답</em>  
<?}?>                            
</div>
<!-- 휴대폰번호 -->
<div class="phonegroup">
<div class="phonegroupin">
<div class="phonegroupwrap">

<div id="sub_content_narrow">
    <div id="poll_make_pan">    
    <div class="btn_add01 btn_adds">        
        <a href="/service/ele_poll_no_answer_detail.php?ep=<?=$eplm_ukey?>&ew=<?=$pgMNo1?>&sc=<?=$total_cnt?>&page=<?=$page?>">Excel로 받기</a>
        <a href="/serv.php?m1=8&m2=<?=$pgMNo1?>&page=<?=$page?>">목록</a>
    </div>
        <div id="poll_m_title"><?=$m_title?></div>
    </div>
<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">번호</th>
        <th scope="col">이름</th>
        <th scope="col">전화번호</th>        
    </tr>
    </thead>
    <tbody>
<?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $CurrNum = $total_count-$from_record-$i;
?>        
    <tr class="<?php echo $bg; ?>">
        <td class="td_num"><?=$CurrNum?></td>
        <td class="td_date"><?=$row['hs_name']?></td>
        <td class="td_date"><?=$row['hs_hp']?></td>
    </tr>        
<?php
    }

    if ($i==0)
        echo '<tr><td colspan="3" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['PHP_SELF']}?m1=8&amp;m2={$pgMNo1}&amp;ep=".$ep); ?>    
</div>        
</div>        
</div>        
</div>        
</div>        
<?php    
include_once('../_tail.php');
?>