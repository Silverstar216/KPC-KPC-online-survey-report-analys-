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
$colspan = 6;

$g5['title'] = "문자전송 실패내역";

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

$total_res = sql_fetch("select mb_id,count(*) as cnt from {$g5['sms5_history_table']} where mb_no = '{$member['mb_no']}' and wr_no='$wr_no' and hs_flag = 0 $sql_search $sql_renum");
$total_count = $total_res['cnt'];
if ($total_res['mb_id'] == 'LMS'){
    $lms_flag = true;
} else {
    $lms_flag = false;
}

$total_spage = (int)($total_count/$spage_size) + ($total_count%$spage_size==0 ? 0 : 1);
$spage_start = $spage_size * ( $spage - 1 );

$vnum = $total_count - (($spage-1) * $spage_size);

$write = sql_fetch("select * from {$g5['sms5_write_table']} where wr_no='$wr_no' and wr_id = '{$member['mb_no']}'  $sql_renum");
if ($write['wr_booking'] == '0000-00-00 00:00:00')
    $write['wr_booking'] = '즉시전송';
$gijundate = date("Y-m-d",strtotime($write['wr_datetime']));
$gijuntabe = date("Ym",strtotime($gijundate));
$today_date = date("Y-m-d");
$before_month = date("Ym",strtotime($today_date.' -1 month'));
$flag_possible_log = true;
if ($gijuntabe < $before_month) {
    $flag_possible_log = false;

    if ($lms_flag == true){
        $logtable = "em_mmt_log_".$before_month;
    } else {
        $logtable = "em_smt_log_".$before_month;
    }

} else {
    if ($lms_flag == true){
        $logtable = "em_mmt_log_".$gijuntabe;
    } else {
        $logtable = "em_smt_log_".$gijuntabe;
    }    
}
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
<input type="submit" value="검색" class="btn_submit">
</form>

<div id="sms5_sent">
    <div class="local_ov01 local_ov">
        <span class="ov_listall">전송건수 <?php echo number_format($write['wr_total'])?> 건</span>
        <span class="ov_listall">성공건수 <span class="txt_succeed"><?php echo number_format($write['wr_success'])?> 건</span></span>
        <span class="ov_listall">실패건수 <span class="txt_fail"><?php echo number_format($write['wr_failure'])?> 건</span></span>
        <span class="ov_listall">전송일시 <?php echo $write['wr_datetime']?></span>
        <span class="ov_listall">예약일시 <?php echo $write['wr_booking']?></span>
        <span class="ov_listall">회신번호 <?php echo $write['wr_reply']?></span>        
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
        <?php if (!$wr_renum) {?>
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
            <th scope="col">실패이유(각 통신사에서 통보됨)</th>
        </tr>
        </thead>
        <tbody>

        <?php if ($flag_possible_log == false){ ?>
        <tr>
            <td colspan="<?php echo $colspan?>" class="empty_table">
                실패이유를 확인할 수 있는 기간(전월부터)이 경과 되었습니다. 
            </td>
        </tr>
        ?> } else if ((!$total_count)||($total_count==0)) { ?>
        <tr>
            <td colspan="<?php echo $colspan?>" class="empty_table">
                데이터가 없습니다.
            </td>
        </tr>
        <?php
        } else {
    if ($lms_flag == true){
            $filed_a = "(select srt_name from imds.em_mmt_tran,sms_resultcode where mt_pr = hs_mt_pr and srt_code = mt_report_code_ib) as today_r";
            $filed_b = "(select srt_name from imds.".$logtable.",sms_resultcode where mt_pr = hs_mt_pr and srt_code = mt_report_code_ib) as log_r";
    } else {
            $filed_a = "(select srt_name from imds.em_smt_tran,sms_resultcode where mt_pr = hs_mt_pr and srt_code = mt_report_code_ib) as today_r";
            $filed_b = "(select srt_name from imds.".$logtable.",sms_resultcode where mt_pr = hs_mt_pr and srt_code = mt_report_code_ib) as log_r";        
    }
        $sms_qry = "select his.*, ".
                            "(select ifnull(max(bg_name),'없음') from {$g5['sms5_book_group_table']} as gr where gr.bg_no= his.bg_no and bg_member = '{$member['mb_no']}' ) as bg_name,".$filed_a.",".$filed_b.
        " from {$g5['sms5_history_table']} as his where wr_no='$wr_no' and mb_no ='{$member['mb_no']}'  and hs_flag = 0 $sql_search $sql_renum order by hs_no desc limit $spage_start, $spage_size";
        $qry = sql_query($sms_qry);
        $line = 0;
        while($res = sql_fetch_array($qry)) {
            $bg = 'bg'.($line++%2);         
            $tmpnayo = $res['today_r'];
            if($tmpnayo == ''){
                $tmpnayo = $res['log_r'];
            }                       
            $rslt_hscd = $tmpnayo;                       
        ?>
        <tr class="<?php echo $bg; ?>">
            <td class="td_numsmall" width="60"><?php echo number_format($vnum--)?></td>
            <td class="td_datetime" ><?php echo $res['bg_name']?></td>
            <td class="td_numbig"><?php echo $res['hs_name']?></a></td>
            <td class="td_numbig"><?php echo $res['hs_hp']?></td>
            <td class="td_datetime"><?php echo $res['hs_datetime']?></td>
            <td><?php echo $rslt_hscd; ?></td>
        </tr>
        <?php } 
        if ($line==0){
?>
        <tr>
            <td colspan="<?php echo $colspan?>" class="empty_table">
                데이터가 없습니다.
            </td>
        </tr>
<?php             
        }
         }?>
        </tbody>
        </table>
        <div><br>&nbsp;더 구체적인 실패 이유는 통신사별로 확인해야 하며 수신자의 동의가 필요한 경우도 있습니다.</div>
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