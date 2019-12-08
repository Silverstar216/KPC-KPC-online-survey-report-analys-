<?php
define('G5_IS_SERVICE', true);
include_once('../common.php');
$pgMNo = 8;
$pgMNo1 = 2;
if($is_guest)  
alert('회원이시라면 로그인 후 이용해 보십시오.',
    Main_DOMAIN.'join/login_view');

include_once('../_head.php');
$page_size = 20;
$colspan = 9;

$g5['title'] = "문자전송 내역 (번호별)";

if ($page < 1) $page = 1;

if ($st && trim($sv))
    $sql_search = " and $st like '%$sv%' ";
else
    $sql_search = "";

$total_res = sql_fetch("select count(*) as cnt from {$g5['sms5_history_table']} where mb_no=  '{$member['mb_no']}' $sql_search");
$total_count = $total_res['cnt'];
$total_page = (int)($total_count/$page_size) + ($total_count%$page_size==0 ? 0 : 1);
$page_start = $page_size * ( $page - 1 );

$vnum = $total_count - (($page-1) * $page_size);
?>
   <div class="subTopTab">
    <ul class="item">
        <li><a href="#" title="페이지 이동" class="active"><span>휴대폰 번호</span></a></li>
           
    </ul>
    </div>

<div class="titlegroup">
        <em>휴대폰 번호</em>      
         <div class="navgroup">     
                 <p>Home <span class="rt">&gt;</span> 전화번호관리 <span class="rt">&gt;</span> 휴대폰 번호</p>
        </div>     
</div>
<!-- 휴대폰번호 -->
<div class="phonegroup">
<div class="phonegroupin">
<div class="phonegroupwrap">
<div id="sub_content">
<form name="search_form" method="get" action="<?echo $_SERVER['PHP_SELF']?>" class="local_sch01 local_sch" >
<label for="st" class="sound_only">검색대상</label>
<select name="st" id="st">
    <option value="hs_name"<?php echo get_selected('hs_name', $st); ?>>이름</option>
    <option value="hs_hp"<?php echo get_selected('hs_hp', $st); ?>>휴대폰번호</option>
    <option value="bk_no"<?php echo get_selected('bk_no', $st); ?>>고유번호</option>    
</select>
<label for="sv" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="sv" value="<?php echo $sv; ?>" id="sv" required class="required frm_input">
<input type="submit" value="검색" class="btnW2">
</form>

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">번호</th>
        <th scope="col">그룹</th>
        <th scope="col">이름</th>
        <th scope="col">전화번호</th>
        <th scope="col">전송일시</th>
        <th scope="col">예약</th>
        <th scope="col">전송</th>
        <th scope="col">메세지</th>
        <th scope="col">관리</th>
     </tr>
     </thead>
     <tbody>
        <?php if (!$total_count) { ?>
        <tr>
            <td colspan="<?php echo $colspan; ?>" class="empty_table" >
                데이터가 없습니다.
            </td>
        </tr>
    <?php
    }
    $qry = sql_query("select * from {$g5['sms5_history_table']} where mb_no=  '{$member['mb_no']}' $sql_search order by hs_no desc limit $page_start, $page_size");
    while($res = sql_fetch_array($qry)) {
        $bg = 'bg'.($line++%2);
        $write = sql_fetch("select * from {$g5['sms5_write_table']} where wr_no='{$res['wr_no']}' and wr_id=  '{$member['mb_no']}' and wr_renum=0");
        $group = sql_fetch("select * from {$g5['sms5_book_group_table']} where bg_no='{$res['bg_no']}' and bg_member =  '{$member['mb_no']}' ");
        if ($group)
            $bg_name = $group['bg_name'];
        else
            $bg_name = '없음';
    ?>
    <tr class="<?php echo $bg; ?>">
        <td class="td_num"><?php echo $vnum--; ?></td>
        <td class="td_mbname"><?php echo $bg_name; ?></td>
        <td class="td_mbname"><a href="./num_book_write.php?w=u&amp;bk_no=<?php echo $res['bk_no']; ?>"><?php echo $res['hs_name']; ?></a></td>
        <td class="td_numbig"><?php echo $res['hs_hp']; ?></td>
        <td class="td_datetime"><?php echo date('Y-m-d H:i', strtotime($write['wr_datetime']))?></td>
        <td class="td_boolean"><?php echo $write['wr_booking']!='0000-00-00 00:00:00'?"<span title='{$write['wr_booking']}'>예약</span>":'';?></td>
        <td class="td_boolean"><?php echo $res['hs_flag']?'성공':'실패'?></td>
        <td><span title="<?php echo $write['wr_message']?>"><?php echo $write['wr_message']?></span></td>
        <td class="td_mngsmall">
            <a href="/service/history_view.php?page=<?php echo $page; ?>&amp;st=<?php echo $st; ?>&amp;sv=<?php echo $sv; ?>&amp;wr_no=<?php echo $res['wr_no']; ?>">상세</a>
        </td>
    </tr>
    <?php } ?>
    </tbody>
    </table>
</div>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['PHP_SELF']."?st=$st&amp;sv=$sv&amp;page="); ?>
</div>
</div>
</div>
</div>
<?php
include_once('../_tail.php');
?>