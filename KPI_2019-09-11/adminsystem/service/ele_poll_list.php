<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

function Get_attach_file_info($udoc,$stitle,$wonlink){
    $attchSql = "select edoc_surl from edoc_master where edoc_ukey = '{$udoc}'";
    $rtn_result = sql_query($attchSql);
    if (!$rtn_result) { return $wonlink;}
    $rtnrow=sql_fetch_array($rtn_result);
    if (!$rtnrow) { return $wonlink;}
    $udcn = $rtnrow['edoc_surl'];
    return '<a href="/serv.php?m1=4&m2=1&polltype=0&udoc='.$udoc.'&udcn='.$udcn.'&stitle='.urlencode($stitle).'">SMS</a>';
}

$token = get_token();
$sql_common = " from epoll_master ";
$sql_search = " where eplm_mbid = '{$member['mb_no']}' and eplm_gubn = '{$eplm_gubn}' ";

$sql_search .= " and eplm_ukey in (";
$sql_search .= "select dp from ";
$sql_search .= "(SELECT wr_poll dp FROM eletter.sms5_write where wr_id = '{$member['mb_no']}' and wr_poll  is not null) dpoll";
$sql_search .= " union ";
$sql_search .= "select dp from ";
$sql_search .= "(SELECT edoc_attach_poll_id dp FROM eletter.edoc_master ";
$sql_search .= "where edoc_ukey in (SELECT wr_udoc FROM eletter.sms5_write where wr_id = '{$member['mb_no']}' and wr_udoc is not null)";
$sql_search .= "and edoc_attach_poll_id is not null) epoll";
$sql_search .= ") ";


if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        default :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "eplm_ukey";
    $sod = "desc";
}
$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt
            {$sql_common}
            {$sql_search}
            {$sql_order} ";

$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 7;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
//            (select count(epls_ukey) from epoll_answer where epls_ukey = eplm_ukey and epls_asbh > 0 and epls_usms is null) as d_respons_su,
//            (select count(epls_ukey) from epoll_answer where epls_ukey = eplm_ukey and epls_etxt <> '' and epls_usms is null and ((epls_asbh is null) or (epls_asbh = '') or (epls_asbh < 1))) as d_gita ,
//            (select count(epls_ukey) from epoll_answer where epls_ukey = eplm_ukey and epls_usms is not null and epls_etxt <> '' and ((epls_asbh is null) or (epls_asbh = '') or (epls_asbh < 1))) as gita,
$sql = " select *,
            (select count(distinct epls_usms) from epoll_answer where epls_ukey = eplm_ukey and epls_usms is not null) as respons_su,
            (SELECT max(edoc_ukey) FROM edoc_master where edoc_attach_poll_id = eplm_ukey and edoc_attach_poll_id is not null and edoc_attach_poll_id != '') as f_attach
            {$sql_common}
            {$sql_search}
            {$sql_order}
            limit {$from_record}, {$rows} ";

$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['PHP_SELF'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '회신 관리';

$colspan = 6;   // was 7
?>

 <div class="local_ov01 local_ov">
    <span class="ov_listall"<?php echo $listall ?></span>
    문서 &nbsp;:&nbsp;<?php echo number_format($total_count) ?>개
 </div>

 
<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
 <div class="sch_last">
    <input type="hidden" name="m1" value="8" >
    <input type="hidden" name="m2" value="<?=$pgMNo1 ?>" >    
    <label for="sfl" class="sound_only">검색대상</label>
    <select name="sfl" id="sfl">
        <option value="eplm_title"<?php echo get_selected($_GET['sfl'], "eplm_title"); ?>>제목</option>
    </select>
    <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label> &nbsp;&nbsp     
    <input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">&nbsp;&nbsp;
    <input type="submit" class="btnW2" value="검색">
 </div>
</form>

<form name="fpolllist" id="fpolllist" method="post">
<input type="hidden" name="sst"   value="<?php echo $sst ?>">
<input type="hidden" name="sod"   value="<?php echo $sod ?>">
<input type="hidden" name="sfl"   value="<?php echo $sfl ?>">
<input type="hidden" name="stx"   value="<?php echo $stx ?>">
<input type="hidden" name="page"  value="<?php echo $page ?>">
<input type="hidden" name="token" value="<?php echo $token ?>">

<!--

-->


<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
        <colgroup>
        <col width="7%" />
        <col width="*" />
        <col width="12%" />
        <col width="6%" />  
        <col width="8%" />                                  
        <col width="10%" />
        </colgroup>    
    <thead>
    <tr>
        <th scope="col">번호</th>
        <th scope="col">제 &nbsp;&nbsp;  목</th>
        <th scope="col">등록일</th>        
        <th scope="col">문항</th>
        <th scope="col">투표</th>
        <th scope="col">관리</th>
<!--
        <th scope="col">재전송</th>
-->
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $CurrNum = $total_count-$from_record-$i;
        $bg = 'bg'.($i%2);
        //$Scnt     = $row['d_respons_su'] +$row['respons_su']+$row['d_gita'] +$row['gita'];
        $Scnt     = $row['respons_su'];
        $eplm_qcnt = $row['eplm_qcnt'];
        //$Scnt = number_format($Scnt/ $eplm_qcnt,0);
        $s_mod = '<a href="/service/ele_poll_view.php?ep='.$row['eplm_ukey'].'&amp;ew='.$pgMNo1.'&amp;sc='.$Scnt.'&amp;page='.$page.'">상세</a>';

        $elpm_date = date("Y-m-d",strtotime($row['eplm_time']));
 
        if ($row['eplm_gubn'] == 1) {
                $pudcn = '회신문서';
           } else {
                $pudcn = '설문조사';
        }
 
        $m_title = $row['eplm_title']; 
        $target_para = '<a href="/serv.php?m1=4&m2=1&polltype='.$row['eplm_gubn'].'&udoc='.$row['eplm_ukey'].'&udcn='.urlencode($pudcn).'&stitle='.urlencode($m_title).'">SMS</a>';                    
        if ($row['f_attach'] != ''){
            $target_para = Get_attach_file_info($row['f_attach'],$row['eplm_title'],$target_para);
        }
    ?>
 
    <tr class="<?php echo $bg; ?>">
        <td class="td_num"><?=$CurrNum?></td>
        <td><?=$row['eplm_title']?></td>
        <td class="td_date"><?=$elpm_date?></td>
        <td class="td_num"><?=$eplm_qcnt?></td>
        <td class="td_num"><?=$Scnt?></td>
        <td class="td_mngsmall"><?=$s_mod ?></td>
<!--
        <td class="td_mngsmall"><?=$target_para ?></td>        
-->
    </tr>

    <?php
    }

    if ($i==0) {
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
    }
    ?>

    </tbody>
    </table>
</div>
</form>
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['PHP_SELF']}?$qstr&amp;m1=8&amp;m2={$pgMNo1}&amp;page="); ?>
                                                                                                                                                                        