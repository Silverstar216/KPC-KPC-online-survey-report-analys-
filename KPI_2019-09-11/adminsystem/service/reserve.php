<?php
define('G5_IS_SERVICE', true);
include_once('../common.php');

$pgMNo = 8;
$pgMNo1 = 2;
if($is_guest)  
alert('회원이시라면 로그인 후 이용해 보십시오.', 
    G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/serv.php?m1='.$pgMNo.'&m2='.$pgMNo1));

include_once('../_head.php');
   $page_size = 20;
   $colspan = 11;

  $g5['title'] = "문자예약 내역";

  if ($page < 1) $page = 1;

  if ($st && trim($sv))
      $sql_search = " and wr_message like '%$sv%' ";
  else
      $sql_search = "";

  $total_res = sql_fetch("select count(*) as cnt from {$g5['sms5_write_table']} where wr_id=  '{$member['mb_no']}' and wr_booking > now() and wr_renum=0 $sql_search");
  $total_count = $total_res['cnt'];

  $total_page = (int)($total_count/$page_size) + ($total_count%$page_size==0 ? 0 : 1);
  $page_start = $page_size * ( $page - 1 );

  $vnum = $total_count - (($page-1) * $page_size);
?>
   <div class="subTopTab">
  <ul class="item">
        <li><a href="#" title="페이지 이동" class="active"><span>예약내역조회</span></a></li>
           
    </ul>
    </div>

<div class="titlegroup">
     <em>예약내역조회</em>      
     <div class="navgroup">   
         <p>Home <span class="rt">&gt;</span> 마이페이지 <span class="rt">&gt;</span> 예약내역조회</p>
    </div>     
</div>

<div class="phonegroup">
<div class="phonegroupin">
<div class="phonegroupwrap">
<div id="sub_content">
<img src="/service/images/sub08_04_txt03.png" width="636" height="111">

<form name="search_form" id="search_form" action=<?php echo $_SERVER['PHP_SELF'];?> class="local_sch01 local_sch" method="get">

<label for="st" class="sound_only">검색대상</label>
<input type="hidden" name="st" id="st" value="wr_message" >
<label for="sv" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="sv" value="<?php echo $sv ?>" id="sv" required class="required frm_input">
<input type="submit" value="검색" class="btn_submit">
<div class="btn_add01 btn_add">
        <a href="/serv.php?m1=8&m2=2">문자전송 내역</a>
</div>
</form>

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">번호</th>
        <th scope="col">메세지</th>
        <th scope="col">회신번호</th>
        <th scope="col">처리일시<br>예약일시</th>
        <th scope="col">예약</th>
        <th scope="col">총건수</th>
        <th scope="col">성공</th>
        <th scope="col">실패</th>
        <th scope="col">중복</th>
        <th scope="col">재전송</th>
        <th scope="col">관리</th>
     </tr>
     </thead>
     <tbody>
    <?php if (!$total_count) { ?>
    <tr>
        <td colspan="<?php echo $colspan?>" class="empty_table" >
            데이터가 없습니다.
        </td>
    </tr>
    <?php
    }
    $qry = sql_query("select * from {$g5['sms5_write_table']} where wr_id=  '{$member['mb_no']}' and wr_booking > now() and wr_renum=0 $sql_search order by wr_no desc limit $page_start, $page_size");
    while($res = sql_fetch_array($qry)) {
        $bg = 'bg'.($line++%2);
        $tmp_wr_memo = @unserialize($res['wr_memo']);
        $dupli_count = $tmp_wr_memo['total'] ? $tmp_wr_memo['total'] : 0;
    ?>
    <tr class="<?php echo $bg; ?>">
        <td class="td_numsmall"><?php echo $vnum--?></td>
        <td><span title="<?php echo $res['wr_message']?>"><?php echo $res['wr_message']?></span></td>
        <td class="td_numbig"><?php echo $res['wr_reply']?></td>
        <td class="td_datetime"><?php echo date('Y-m-d H:i', strtotime($res['wr_datetime'])).'<br>'.date('Y-m-d H:i', strtotime($res['wr_booking'])) ?></td>
        <td class="td_boolean"><?php echo $res['wr_booking']!='0000-00-00 00:00:00'?"<span title='{$res['wr_booking']}'>예약</span>":'';?></td>
        <td class="td_num"><?php echo number_format($res['wr_total'])?></td>
        <td class="td_num"><?php echo number_format($res['wr_success'])?></td>
        <td class="td_num"><?php echo number_format($res['wr_failure'])?></td>
        <td class="td_num"><?php echo $dupli_count;?></td>
        <td class="td_num"><?php echo number_format($res['wr_re_total'])?></td>
        <td class="td_mngsmall">
            <a href="/service/history_view.php?page=<?php echo $page;?>&amp;st=<?php echo $st;?>&amp;sv=<?php echo $sv;?>&amp;wr_no=<?php echo $res['wr_no'];?>">수정</a>
            <!-- <a href="./history_del.php?page=<?php echo $page;?>&amp;st=<?php echo $st;?>&amp;sv=<?php echo $sv;?>&amp;wr_no=<?php echo $res['wr_no'];?>">삭제</a> -->
        </td>
    </tr>
    <?php } ?>
    </tbody>
    </table>
</div>
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['PHP_SELF']."?m1=8&m2=3&st=$st&amp;sv=$sv&amp;page="); ?>
</div>
</div>
</div>
</div>
<?php
include_once('../_tail.php');
?>