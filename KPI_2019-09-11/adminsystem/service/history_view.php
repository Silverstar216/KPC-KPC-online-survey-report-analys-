<?php
define('G5_IS_SERVICE', true);
include_once('../common.php');

$pgMNo = 8;
$pgMNo1 = 2;
if($is_guest)  
alert('회원이시라면 로그인 후 이용해 보십시오.', 
    G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/serv.php?m1='.$pgMNo.'&m2='.$pgMNo1));

include_once('../_head.php');
$spage_size = 20;
$colspan = 8;

$g5['title'] = "문자전송 상세내역";

if (!is_numeric($wr_no))
    alert('전송 고유 번호가 없습니다.');

if ($spage < 1) $spage = 1;

if ($sst && trim($ssv))
    $sql_search = " and $sst like '%$ssv%' ";
else
    $sql_search = "";

if ($wr_renum) {
    $sql_renum = " and wr_renum='$wr_renum' ";
    $re_text = " <span style='font-weight:normal; color:red;'>(재전송)</span>";
} else
    $sql_renum = " and wr_renum='0'";

$testflag  = 'N';
if ($member['mb_no']== 25){
    $testflag = 'Y';
}

$anomy_flag_qry = "SELECT ifnull(Max(eplm_gubn),'') as anoflag FROM epoll_master ".
                               "where (eplm_ukey = (SELECT edoc_attach_poll_id FROM edoc_master where edoc_ukey = (SELECT wr_udoc FROM  sms5_write  where wr_no='$wr_no' ))) ".
                                " or (eplm_ukey = (SELECT wr_poll FROM sms5_write  where wr_no='$wr_no' ))";

$ano_res = sql_fetch($anomy_flag_qry);
$anomy_flag = $ano_res['anoflag'];

$total_res = sql_fetch("select count(*) as cnt from {$g5['sms5_history_table']} where mb_no = '{$member['mb_no']}' and wr_no='$wr_no' $sql_search $sql_renum");
$total_count = $total_res['cnt'];

$total_spage = (int)($total_count/$spage_size) + ($total_count%$spage_size==0 ? 0 : 1);
$spage_start = $spage_size * ( $spage - 1 );

$vnum = $total_count - (($spage-1) * $spage_size);

$write = sql_fetch("select a.* ".
        "from {$g5['sms5_write_table']} a ".
    "where wr_no='$wr_no' and wr_id = '{$member['mb_no']}'  $sql_renum");
if ($write['wr_booking'] == '0000-00-00 00:00:00')
    $write['wr_booking'] = '즉시전송';

$wait_sms_count = $write['wr_total'] - $write['wr_success'] - $write['wr_failure'];
?>
<div class="titlegroup">
     <em>전송결과</em>      
</div>
<!-- 휴대폰번호 -->
<div class="phonegroup">
<div class="phonegroupin">
<div class="phonegroupwrap">
<div id="sub_content">
<form name="search_form" method="get" action="<?php echo $_SERVER['PHP_SELF']?>" class="local_sch01 local_sch">
<input type="hidden" name="wr_no" value="<?php echo $wr_no?>">
<input type="hidden" name="wr_renum" value="<?php echo $wr_renum?>">
<input type="hidden" name="page" value="<?php echo $page?>">
<input type="hidden" name="st" value="<?php echo $st?>">
<input type="hidden" name="sv" value="<?php echo $sv?>">
<label for="sst" class="sound_only">검색대상</label>
<select name="sst" id="sst">
    <option value="hs_name" <?php echo get_selected('hs_name', $sst); ?>>이름</option>
    <option value="hs_hp" <?php echo get_selected('hs_hp', $sst); ?>>휴대폰번호</option>
</select>
<label for="ssv" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="ssv" value="<?php echo $ssv?>" id="ssv" class="frm_input">
<input type="submit" value="검색" class="btnW2">
</form>

<div id="sms5_sent">
    <div class="local_ov01 local_ov">
        <span class="ov_listall">요청건수 : <?php echo number_format($write['wr_total'])?> 건 중 </span>
        <span class="ov_listall">성공건수 <span class="txt_succeed"><?php echo number_format($write['wr_success'])?> 건, </span></span>
        <span class="ov_listall">대기건수 <span class="txt_fail"><?php echo number_format($wait_sms_count)?> 건, </span></span>
		<span class="ov_listall">실패건수 <span class="txt_fail"><?php echo number_format($write['wr_failure'])?> 건</span></span>
    </div>
	<div class="local_ov01 local_ov">
	    <span class="ov_listall">전송일시 : <?php echo $write['wr_datetime']?></span>
	</div>
	<div class="local_ov01 local_ov">
        <span class="ov_listall">예약일시 : <?php echo $write['wr_booking']?></span>
	</div>		
	<div class="local_ov01 local_ov">
        <span class="ov_listall">회신번호 : <?php echo $write['wr_reply']?></span>      
	</div>
		
    <h2>전송내용(가정통신문의 경우 문서 첨부 주소는 각 수신별로 상이합니다.)</h2><br>
    <div id="con_sms" class="sms5_box">
        <span class="box_ico"></span>
        <textarea class="box_txt2" readonly><?php echo $write['wr_message'];?></textarea>
    </div>
    <?php if ($write['wr_re_total'] && !$wr_renum) { ?>
    <h2>전송실패 문자 재전송 내역</h2>
    <table>
    <thead>
    <tr>
        <th scope="col">번호</th>
        <th scope="col">전송일시</th>
        <th scope="col">총건수</th>
        <th scope="col">성공</th>
        <th scope="col">실패</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $res = sql_fetch("select count(*) as cnt from {$g5['sms5_write_table']} where wr_no='$wr_no' and wr_renum>0");
    $re_vnum = $res['cnt'];

    $qry = sql_query("select * from {$g5['sms5_write_table']} where wr_no='$wr_no' and wr_renum>0 order by wr_renum desc");
    while($res = sql_fetch_array($qry)) {
    ?>
    <tr>
        <td><?php echo $re_vnum--?></td>
        <td><?php echo $res['wr_datetime']?></td>
        <td><?php echo number_format($res['wr_total'])?></td>
        <td><?php echo number_format($res['wr_success'])?></td>
        <td><?php echo number_format($res['wr_failure'])?></td>
    </tr>
    <?php } ?>
    </tbody>
    </table>
    <?php } ?>

    <?php
    if( $write['wr_memo'] ){
        $tmp_wr_memo = @unserialize($write['wr_memo']);
        if( count($tmp_wr_memo) && is_array($tmp_wr_memo) ){
        $arr_wr_memo = array_count_values( $tmp_wr_memo['hp'] );
    ?>
    <h2>중복번호 <?php echo $tmp_wr_memo['total'];?>건</h2>
    <ul id="sent_overlap">
        <?php
        foreach( $arr_wr_memo as $key=>$v){
        if( empty($v) ) continue;
        ?>
        <li><b><?php echo $key;?></b> 중복 <?php echo $v;?>건</li>
        <?php } ?>
    </ul>
    <?php
        }
    }
    ?>

    <h2>문자전송 목록 <?php echo $re_text?></h2>
    <div class="btn_add01 btn_add">        
        <?php if ($write['wr_failure'] > 0) { ?>
                <a href="/service/history_view_failed.php?page=<?php echo $page?>&amp;st=<?php echo $st?>&amp;sv=<?php echo $sv?>&amp;wr_no=<?php echo $wr_no?>">실패내역</a>
        <?php  }
         if (!$wr_renum) {?>
        <a href="/serv.php?m1=8&m2=2&page=<?php echo $page?>&amp;st=<?php echo $st?>&amp;sv=<?php echo $sv?>">목록</a>
        <?php } else { ?>
        <a href="/service/history_view.php?page=<?php echo $page?>&amp;st=<?php echo $st?>&amp;sv=<?php echo $sv?>&amp;wr_no=<?php echo $wr_no?>">뒤로가기</a>
        <?php } ?>
    </div>

    <div class="tbl_head01 tbl_wrap">
        <table>
        <thead>
        <tr>
            <th scope="col">번호</th>
            <th scope="col">그룹</th>
            <th scope="col">이름</th>
            <th scope="col">휴대폰번호</th>
            <th scope="col">전송일시</th>            
            <th scope="col">결과</th>
            <th scope="col">조회</th>
            <th scope="col">첨부문서</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!$total_count) { ?>
        <tr>
            <td colspan="<?php echo $colspan?>" class="empty_table">
                데이터가 없습니다.
            </td>
        </tr>
        <?php
        }
        if ($anomy_flag == '2') {
        $sms_qry = "select his.*, ".
                            "(select ifnull(max(bg_name),'없음') from {$g5['sms5_book_group_table']} as gr where gr.bg_no= his.bg_no and bg_member = '{$member['mb_no']}' ) as bg_name, ".
                            " '설문' as clicks ".
        "from {$g5['sms5_history_table']} as his where wr_no='$wr_no' and mb_no ='{$member['mb_no']}' $sql_search $sql_renum order by hs_no desc limit $spage_start, $spage_size";
        } else {
        $sms_qry = "select his.*, ".
                            "(select ifnull(max(bg_name),'없음') from {$g5['sms5_book_group_table']} as gr where gr.bg_no= his.bg_no and bg_member = '{$member['mb_no']}' ) as bg_name, ".
                            "(SELECT sum(clicks) FROM forel_url where url = hs_lurl and hs_lurl is not null) as clicks ".
        "from {$g5['sms5_history_table']} as his where wr_no='$wr_no' and mb_no ='{$member['mb_no']}' $sql_search $sql_renum order by hs_no desc limit $spage_start, $spage_size";
        }		
		
        $qry = sql_query($sms_qry);
        while($res = sql_fetch_array($qry)) {
            $bg = 'bg'.($line++%2);         
            if ($res['hs_flag'] == '0'){
                $rslt_hscd = '실패';
                $res['clicks'] = '0';
            } else if ($res['hs_flag'] == '1'){
                $rslt_hscd = '성공';
            } else {
                $rslt_hscd = '대기';
            }     
            $tmp_msg = $res['hs_message'];
            $tmp_arr   = explode('http://mms.ac/',$tmp_msg);

            $lastrow = count($tmp_arr );

            if ($lastrow < 2){
                    $link = "";
            } else if ($tmp_arr[$lastrow-1] == '') {
                    $link = "";
            } else {
                    $stmpArr = explode(' ',$tmp_arr[$lastrow-1]);

                    $tmpurl = '"http://mms.ac/'.$stmpArr[0].'"';
                    $link = "<a href='javascript:;' onclick='opennnewwindow(".$tmpurl.");'>http://mms.ac/".$stmpArr[0]."</a>";
            }
            
            if ($testflag == 'Y'){
                if ($res['hs_name'] != ''){
                    $res['hs_name'] = '***';    
                }                
                $res['hs_hp'] = '***-****-****';
            }
        ?>
        <tr class="<?php echo $bg; ?>">
            <td class="td_numsmall" width="60"><?php echo number_format($vnum--)?></td>
            <td class="td_mbname"><?php echo $res['bg_name']; ?></td>
            <td class="td_mbname"><?php echo $res['hs_name']?></td>
            <td class="td_numbig"><?php echo $res['hs_hp']?></td>
            <td class="td_datetime"><?php echo $res['hs_datetime']?></td>
            <td class="td_boolean"><?php echo $rslt_hscd; ?></td>
            <td class="td_numbig"><?php echo $res['clicks']?></td>
            <td class="td_datetime"><?=$link?></td>
        </tr>
        <?php } ?>
        </tbody>
        </table>
        <div><br>&nbsp;"조회"는 문서첨부 SMS인경우에 페이지 조회 건수이며 회신 참여, 설문 참여를 의미하지 않습니다!! </div>
    </div>
</div>

<?php echo sms5_sub_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $spage, $total_spage, $_SERVER['PHP_SELF']."?wr_no=$wr_no&amp;wr_renum=$wr_renum&amp;page=$page&amp;st=$st&amp;sv=$sv&amp;sst=$sst&amp;ssv=$ssv", "", "spage"); ?>
</div>
</div>
</div>
</div>
<script type="text/javascript">
function opennnewwindow(url){         
      window.open(url);
}
</script>
<?php
include_once('../_tail.php');
?>