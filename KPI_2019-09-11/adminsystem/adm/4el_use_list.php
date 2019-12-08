<?php
$sub_menu = "700700";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$token = get_token();

$sql_common = " from forel_url, doc_info doc1";
$sql_search = " where dc_4key = keyword and dc_usms is null ";

if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'keyword' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
}

if (!$sst) {
    $sst  = "timestamp";
    $sod = "desc";
}
$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt
            {$sql_common}
            {$sql_search} ";
$row = sql_fetch($sql);

$total_count = $row['cnt'];
$rows = 200;//$config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select *, ".
            "( case when dc_kind = 'P' then ". 
"(select mb_nick from epoll_master,g5_member where eplm_ukey = dc_udoc and mb_no = eplm_mbid) ".
"else ".
"(select mb_nick from edoc_master,g5_member where edoc_ukey = dc_udoc and mb_id = edoc_mbid) ".
"end ".
") mb_nick, ".
            "(select count(*) from doc_info doc2 where doc2.dc_udoc = doc1.dc_udoc ) cv_cnt ".
            $sql_common.            
            $sql_search.
            $sql_order.
            " limit {$from_record}, {$rows} ";

$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['PHP_SELF'].'" class="ov_listall">전체목록</a>';

$mb = array();
if ($sfl == 'mb_id' && $stx)
    $mb = get_member($stx);

$g5['title'] = '단축URL 내역';
include_once ('./admin.head.php');

$colspan = 6;

$po_expire_term = '';
if($config['cf_point_term'] > 0) {
    $po_expire_term = $config['cf_point_term'];
}

if (strstr($sfl, "mb_id"))
    $mb_id = $stx;
else
    $mb_id = "";

$processFlag = false;
?>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
    <option value="timestamp"<?php echo get_selected($_GET['sfl'], "timestamp"); ?>>처리일자</option>
    <option value="title"<?php echo get_selected($_GET['sfl'], "title"); ?>>제목</option>
    <option value="keyword"<?php echo get_selected($_GET['sfl'], "keyword"); ?>>단축URL</option>    
    <option value="url"<?php echo get_selected($_GET['sfl'], "url"); ?>>실주소</option>    
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
<input type="submit" class="btn_submit" value="검색">
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['PHP_SELF']}?$qstr&amp;page="); ?>    
</form>
<form name="fpointlist" id="fpointlist">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="<?php echo $token ?>">
<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col"><?php echo subject_sort_link('keyword') ?>단축</a></th>
        <th scope="col"><?php echo subject_sort_link('title') ?>제목</th>
        <th scope="col"><?php echo subject_sort_link('url') ?>실주소</th>
        <th scope="col"><?php echo subject_sort_link('timestamp') ?>처리일자</th>        
        <th scope="col">변환수</th>    
        <th scope="col">사용자</th>        
    </tr>
  
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $bg = 'bg'.($i%2);        
        if ($row['mb_nick'] <> '') {
            $nickname = $row['mb_nick'];
        } else {
            $nickname = '';
        }           
    ?>
    <tr class="<?php echo $bg; ?>">
        <td class="td_mbid"><?php echo $row['keyword'] ?></td>
        <td ><?php echo $row['title'] ?></td>
        <td ><a href="javascript:;" onclick="view_page('<?php echo $row['url'];?>')"><?php echo $row['url'] ?></a></td>
        <td class="td_datetime"><?php echo $row['timestamp'] ?></td>
        <td class="td_price" ><?php echo number_format($row['cv_cnt']); ?></td>        
        <td ><?php echo $nickname ?></td>        
    </tr>
    <?php
    }

    if ($i == 0)
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>
</form>
<?php
include_once ('./admin.tail.php');
?>
<script type="text/javascript">
function view_page(wurl){
    window.open(wurl);
}
</script>