<?php
	include_once('../common.php');
	 if ($is_guest) {
		echo 'not';
		return;
	}

// 한페이지에 보여줄 행, 현재페이지, 총페이지수, URL
function get_paging_for_doc($write_pages, $cur_page, $total_page, $url, $add="")
{
    //$url = preg_replace('#&amp;page=[0-9]*(&amp;page=)$#', '$1', $url);
    $url = preg_replace('#&amp;page=[0-9]*#', '', $url) . '&amp;page=';

    $str = '';
    if ($cur_page > 1) {
        $str .= '<a href="javascript:;" onclick="btn_var_preeview_click(1);" class="pg_page pg_start"><img src="'.G5_IMG_URL.'/btn_board_pprev.png" border="0" align=absmiddle title="처음"></a>'.PHP_EOL;
    }

    $start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
    $end_page = $start_page + $write_pages - 1;

    if ($end_page >= $total_page) $end_page = $total_page;

    if ($start_page > 1) $str .= '<a href="javascript:;"  onclick="btn_var_preeview_click('.($start_page-1).');" class="pg_page pg_prev"><img src="'.G5_IMG_URL.'/btn_board_prev.png" border="0" align=absmiddle title="이전"></a>'.PHP_EOL;

    if ($total_page > 1) {
        for ($k=$start_page;$k<=$end_page;$k++) {
            if ($cur_page != $k)
                $str .= '<a href="javascript:;" onclick="btn_var_preeview_click('.$k.');" class="pg_page">'.$k.'<span class="sound_only">페이지</span></a>'.PHP_EOL;
            else
                $str .= '<span class="sound_only">열린</span><strong class="pg_current">'.$k.'</strong><span class="sound_only">페이지</span>'.PHP_EOL;
        }
    }

    if ($total_page > $end_page) $str .= '<a href="javascript:;" onclick="btn_var_preeview_click('.($end_page+1).');" class="pg_page pg_next"><img src="'.G5_IMG_URL.'/btn_board_next.png" border="0" align=absmiddle title="다음"></a>'.PHP_EOL;

    if ($cur_page < $total_page) {
        $str .= '<a href="javascript:;" onclick="btn_var_preeview_click('.$total_page.');" class="pg_page pg_end"><img src="'.G5_IMG_URL.'/btn_board_nnext.png" border="0" align=absmiddle title="맨끝"></a>'.PHP_EOL;
    }

    if ($str)
        return "<nav class=\"pg_wrap\"><span class=\"pg\">{$str}</span></nav>";
    else
        return "";
}

$sql = " select count(*) as cnt from edoc_variable where edcv_mbno = '{$member['mb_no']}' and edcv_grid = '{$uemid}' and edcv_udoc = '{$udoc}' ";

$row = sql_fetch($sql);
$total_count = $row['cnt'];
$rows = 10;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * from edoc_variable 
            where edcv_mbno = '{$member['mb_no']}' and edcv_udoc = '{$udoc}' and edcv_grid = '{$uemid}' limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$row=sql_fetch_array($result);
$heder_helper_text = '';
if ($row['edcv_ccnt'] == -1) {        
        $table_header_tr = '<thead><tr><th scope="col">이름</th><th scope="col">수신번호</th>';
        $table_header_tr .= '</tr></thead>';
        $e4num = substr($row['edcv_hp'],-4,4);
        $tempFunc = "preView_varForm('{$row['edcv_ukey']}','{$e4num}')";
        $table_header_tr .= '<tr class="bg0" onclick="'.$tempFunc.'">';
        $table_header_tr .= '<td>'.$row['edcv_name'].'</td>';
        $table_header_tr .= '<td>'.$row['edcv_hp'].'</td>';
        $table_header_tr .= '</tr>';
} else {
        $heder_helper_text = '(항목이 세자리 콤마 형식 일때 체크)';
        $varList = explode('|', $row['edcv_var']);
        $var_count = count($varList);
        $table_header_tr = '<thead><tr><th scope="col">이름</th><th scope="col">수신번호</th>';
        for($idx=0;$idx<$var_count;$idx++){
            $NumInt = $idx+1;
            $table_header_tr .= '<th scope="col"><input type="checkbox" name="moneyType[]" class="vartitlechk">항목'.sprintf('%02s',$NumInt).'</th>';
        }
        $table_header_tr .= '</tr></thead>';
        $e4num = substr($row['edcv_hp'],-4,4);
        $tempFunc = "preView_varForm('{$row['edcv_ukey']}','{$e4num}')";
        $table_header_tr .= '<tr class="bg0" onclick="'.$tempFunc.'">';
        $table_header_tr .= '<td>'.$row['edcv_name'].'</td>';
        $table_header_tr .= '<td>'.$row['edcv_hp'].'</td>';
        for($idx=0;$idx<$var_count;$idx++){
            $table_header_tr .= '<td>'.$varList[$idx].'</td>';
        }
        $table_header_tr .= '</tr>';
}
?>
<div class="local_ov02">
    데이터 내역<?=$heder_helper_text?> <a href='javascript:;' onclick='javascript:close_var_pan();'>닫기</a>
</div>
<div class="tbl_head01 tbl_wrap">
 <table>
    <tbody>
    <?php    
    echo $table_header_tr;
    for ($i=1; $row=sql_fetch_array($result); $i++) {
        $bg = 'bg'.($i%2);        
        $e4num = substr($row['edcv_hp'],-4,4);
    ?>
    <tr class="<?php echo $bg; ?>" onclick="preView_varForm('<?php echo $row['edcv_ukey'] ?>','<?php echo $e4num ?>');">
        <td><? echo $row['edcv_name'] ?></td>
        <td><? echo $row['edcv_hp'] ?></td>
<?php
    if ($row['edcv_ccnt'] <> -1) {        
        $varList = explode('|', $row['edcv_var']);
        $var_count = count($varList);
        for($idx=0;$idx<$var_count;$idx++){
            echo '<td>'.$varList[$idx].'</td>';
        }        
    }        
?>        
    </tr>
    <?php
    }
    if ($i==0) 
        echo '<tr><td colspan="2" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    <tr><td colspan="2" class="td_help" >&laquo;&nbsp;&nbsp;클릭 : 미리보기&nbsp;&nbsp;&raquo;</td></tr>
    </tbody>

</table>
</div>
<?php 
    echo get_paging_for_doc(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['PHP_SELF']}?&udoc={$udoc}&amp;page=");     
?>