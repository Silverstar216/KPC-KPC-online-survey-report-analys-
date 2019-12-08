<?php
include_once("../common.php");

// 한페이지에 보여줄 행, 현재페이지, 총페이지수, URL
function get_paging_ajax($write_pages, $cur_page, $total_page, $url, $add="")
{
    $url = preg_replace('#&amp;page=[0-9]*#', '', $url) . '&amp;page=';
    $str = '';
    if ($cur_page > 1) {
        $str .= '<a href="javascript:page_view(1);" class="pg_page pg_start"><img src="'.G5_IMG_URL.'/btn_board_pprev.png" border="0" align=absmiddle title="처음"></a>'.PHP_EOL;
    }
    $start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
    $end_page = $start_page + $write_pages - 1;
    if ($end_page >= $total_page) $end_page = $total_page;
    if ($start_page > 1) $str .= '<a href="javascript:page_view('.($start_page-1).');" class="pg_page pg_prev"><img src="'.G5_IMG_URL.'/btn_board_prev.png" border="0" align=absmiddle title="이전"></a>'.PHP_EOL;
    if ($total_page > 1) {
        for ($k=$start_page;$k<=$end_page;$k++) {
            if ($cur_page != $k)
                $str .= '<a href="javascript:page_view('.$k.')" class="pg_page">'.$k.'<span class="sound_only">페이지</span></a>'.PHP_EOL;
            else
                $str .= '<span class="sound_only">열린</span><strong class="pg_current">'.$k.'</strong><span class="sound_only">페이지</span>'.PHP_EOL;
        }
    }
    if ($total_page > $end_page) $str .= '<a href="javascript:page_view('.($end_page+1).');" class="pg_page pg_next"><img src="'.G5_IMG_URL.'/btn_board_next.png" border="0" align=absmiddle title="다음"></a>'.PHP_EOL;
    if ($cur_page < $total_page) {
        $str .= '<a href="javascript:page_view('.$total_page.');" class="pg_page pg_end"><img src="'.G5_IMG_URL.'/btn_board_nnext.png" border="0" align=absmiddle title="맨끝"></a>'.PHP_EOL;
    }
    if ($str)
        return "<nav class=\"pg_wrap\"><span class=\"pg\">{$str}</span></nav>";
    else
        return "";
}
/*
if (isset($ajaxcall)){
	if ($member['mb_no'] == '') {
		return;
	}
} else {
	return;	
}
*/
$page_size = 5;
$colspan = 5;

$g5['title'] = "문항 관리";

if ($page < 1) $page = 1;

if (is_numeric($bg_no))
    $sql_group = " and eplm_gubn='$bg_no' ";
else
    $sql_group = "";

if ($sv == '') {
    $sql_search = '';    
} else  {
    $sql_search = "and eplm_title like '%{$sv}%'";    
}

$qstr = " &amp;bg_no=$bg_no&amp;sv=$sv";
$total_res = sql_fetch("select count(*) as cnt from epoll_tmp_master where ((eplm_mbid = '{$member['mb_no']}') or (eplm_public = 'Y') ) $sql_group $sql_search ");
$total_count = $total_res['cnt'];

$total_page = (int)($total_count/$page_size) + ($total_count%$page_size==0 ? 0 : 1);
$page_start = $page_size * ( $page - 1 );
?>
<div id="poll_list_view_gnb">
<form name="search_form" id="poll_save_sform" method="get" onsubmit="return search_view(this);">
<label for="bg_no" class="sound_only">그룹</label>
<select name="bg_no" id="bg_no">
    <option value=""<?php echo get_selected('', $bg_no); ?>> 전체 </option>
    <option value="1"<?php echo get_selected('1', $bg_no); ?>> 회신 </option>
    <option value="2"<?php echo get_selected('2', $bg_no); ?>> 설문 </option>
</select>

<label for="stt" class="sound_only">검색대상</label>
<input type="hidden" value="<?php echo $st?>" name="st" id="stt">
<label for="svv" class="sound_only">검색어</label>
<input type="text" size="15" name="sv" value="<?php echo $sv?>" id="svv" class="frm_input">
<input type="submit" value="검색" class="btn_submit">
<button type="button"  class="btn_poll_save_close"  onclick="close_view();">목록 닫기</button>
</form>
</div>    
<div class="tbl_head01 tbl_wrap">
    <table>
    <thead>
    <tr>
        <th scope="col" style="width: 40px;">구분</th>	        
        <th scope="col">제목(클릭 미리보기)</th>        
        <th scope="col" style="width: 40px;">사용</th>        
        <th scope="col" style="width: 40px;">삭제</th>
        <th scope="col" style="width: 100px;">공개</th>	
    </tr>
    </thead>
    <tbody>
    <?php if (!$total_count) { ?>
    <tr>
        <td colspan="<?php echo $colspan?>" class="td_mbstat">데이터가 없습니다.</td>
    </tr>
    <?php
    }
    $line = 0;
    $reSql = "select *, ".
                  " (if( eplm_mbid = '{$member['mb_no']}', '', (select mb_nick from g5_member where mb_no =eplm_mbid ) )) as spublic ".
                  "from epoll_tmp_master where ((eplm_mbid = '{$member['mb_no']}') or (eplm_public = 'Y'))  $sql_group $sql_search order by eplm_ukey desc limit $page_start, $page_size";                    

    $qry = sql_query($reSql);
    while($res = sql_fetch_array($qry))
    {
        $bg = 'bg'.($line++%2);
        if ($res['eplm_gubn'] == '1'){
		$gubn = '회신';
        } else if ($res['eplm_gubn'] == '2'){
		$gubn = '설문';
        }    
        $delbtnclick = '';
        if ($res['eplm_public'] == 'Y'){
        		if($res['spublic'] ==''){
			$spublic = '공개';
			$delbtnclick = '<button type="button" class="btn_frmline" onclick="temp_del('.$res['eplm_ukey'].');">삭제</button>';
	           } else {
	           	$spublic = $res['spublic'];
	           }
        } else {
		$spublic = '';
		$delbtnclick = '<button type="button" class="btn_frmline" onclick="temp_del('.$res['eplm_ukey'].');">삭제</button>';
        }                   
    ?>
    <tr class="<?php echo $bg; ?>">
        <td class="td_mbname" height="15"><?php echo $gubn?></td>        
        <td class="td_mbname"><a href="javascript:;" onclick="poll_repreView('<?=$res['eplm_ukey']?>')"><?php echo $res['eplm_title'] ?></a></td>        
        <td class="td_mngsmall"><button type="button" class="btn_frmline" onclick="get_temp_poll('<?=$res['eplm_ukey']?>');">사용</button></td>
        <td class="td_mngsmall"><?=$delbtnclick?></td>
        <td class="td_mbname"><?php echo $spublic?></td>
    </tr>
    <?php } ?>
    </tbody>
    </table>
    <?php echo get_paging_ajax(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['PHP_SELF']}?$qstr&amp;page="); ?>    
</div>
<script src="<?php echo G5_JS_URL ?>/jquery-1.8.3.min.js"></script>
<SCRIPT TYPE="text/javascript">
  function close_view(){
        $('#tempPollPan').fadeOut('slow');      
  }
   function poll_repreView(ep){    
      window.open("/service/recreate_poll_preview.php?ep="+ep);   	
   }
   function page_view(fp){
            bgno = '<?=$bg_no?>';
            stv = '<?=$sv?>';
            var formData = {page:fp, bg_no :bgno,  sv : stv};
            poll_temp_list(formData);
    }
   function search_view(){
            var formData = $("#poll_save_sform").serialize();
            poll_temp_list(formData);
            return false;
    }    
<?php if (!$is_guest) {?> 

     function temp_del(ep){   
	if (confirm("정말 삭제하시겠습니까??") == true){    //확인
	    
	}else{   //취소
	    return;
	}

        var params = { ep : ep, uk : 'd' };        
        $.ajax({
            url: "<?=G5_URL?>/service/recreate_poll_up.php",
            cache:false,
            timeout : 30000,
            data : params,
            dataType:'html',
            type:'Post',
            success: function(data) {  
               if (data == 'not') {    
               } else {
		  poll_temp_list(); 
               }
            },
            error: function (xhr, ajaxOptions, thrownError) {
            }
        });
    }        

<?}?>
</SCRIPT>