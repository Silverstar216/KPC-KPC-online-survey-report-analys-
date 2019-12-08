<?php
  if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 
    if($eler_ukey != '') {// 요청내역인 경우
	$sql = " select * ".
	         " from ele_money_request,{$g5['member_table']},ele_price_user ".
	            " where eler_ukey = '{$eler_ukey}' and mb_no = eler_id and ".
	                                 "elpu_type = eler_type and elpu_stat = 'm' and elpu_end_date = '9999-12-31 00:00:00' ";
	$result2 = sql_query($sql);
	$prow=sql_fetch_array($result2);

	$hereid_info = '아이디: '.$prow['mb_id'].', 기관명: '.$prow['mb_nick'].', 성명: '.$prow['mb_name'].', 요금제: '.$prow['elpu_type_name'].', 입금액: '.number_format($prow['eler_money']).'원, 요청일시: '.$prow['eler_request_date'] ;
	$eler_type = $prow['eler_type'];
	$elpu_type_name = $prow['elpu_type_name'];
	$start_date  = date("Y-m-d",strtotime($prow['eler_request_date']));
	$eler_money = $prow['eler_money'];
	$eler_kind = 'new';
    	$money_or_cnt = 2;	

$remain_cnt = $elem_crnt_cnt;
$remain_bonus = $elem_crnt_cv_bonus_cnt;     	
    } else {// 
    	//$start_date  = date("Y-m-d",strtotime($prow['eler_request_date']));	
    	$hereid_info = '아이디: '.$row['mb_id'].', 기관명: '.$row['mb_nick'].', 성명: '.$row['mb_name'];
    	$eler_kind = 'change';
    	$money_or_cnt = 0;
           $rq_mb_id = $row['mb_id'];
           $eler_ukey = '-1';
           $eler_money = 0;// 보너스란다..
    }
    if ($eler_kind == 'new') {
    } if ($eler_kind == 'add') {
    } if ($eler_kind == 'change') {   // 변경
    } if ($eler_kind == 'Refund') {// 환불 
    }   
    $money_or_cnt = 2;
    if ($eler_type == '001') {
        $money_or_cnt = 0;
    } else if ($eler_type == '011') {
        $end_date   = date("Y-m-d",strtotime($eler_request_date.'+1 month -1 day'));
        $money_or_cnt = 1;
    } else if ($eler_type == '012') {
        $end_date   = date("Y-m-d",strtotime($eler_request_date.'+1 month -1 day'));
    } else if ($eler_type == '013') {
        $end_date   = date("Y-m-d",strtotime($eler_request_date.'+1 month -1 day'));
    } else if ($eler_type == '121') {            
        $money_or_cnt = 1;
        $end_date   = date("Y-m-d",strtotime($eler_request_date.'+1 year -1 day'));
    } else if ($eler_type == '122') {            
        $end_date   = date("Y-m-d",strtotime($eler_request_date.'+1 year -1 day'));                                          
    } else if ($eler_type == '123') {            
        $end_date   = date("Y-m-d",strtotime($eler_request_date.'+1 year -1 day'));
    } else if ($eler_type == '777') {                                            
        $money_or_cnt = 3;        
    } else if ($eler_type == '999') {                                            
        $money_or_cnt = 3;               
    } else {
        $money_or_cnt = 0;        
    }           
?>
<section id="point_mng">
    <h2 class="h2_frm">처리대상(<?php echo $hereid_info?>)</h2>
    <form name="fpointlist2" method="post" id="fpointlist2" action="./money_request_update.php" autocomplete="off">
    <input type="hidden" name="eler_ukey" value="<?php echo $eler_ukey ?>">        
    <input type="hidden" name="eler_id" value="<?php echo $rq_mb_id ?>">
    <input type="hidden" name="eler_type" value="<?php echo $rq_mb_no ?>">
    <input type="hidden" name="elem_crnt_bonus" value="0">
    <input type="hidden" name="money_or_cnt" value="<?php echo $money_or_cnt ?>">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="elem_real_money">관련금액(참고)<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="elem_real_money" id="elem_real_money" required class="required frm_input" value="<?= $eler_money?>"></td>
        </tr>        	
        <tr>
            <th scope="row"><label for="elem_crnt_cnt">sms 건수<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="elem_crnt_cnt" id="elem_crnt_cnt" required class="required frm_input" value="<?= $elpu_sms_limit_count?>"></td>
        </tr>
<?php if ($money_or_cnt == 2) {?>       
        <tr>
            <th scope="row"><label for="elem_start_date">시작일</label></th>
            <td>                
                            <select name="wr_by" id="wr_by" >
                            <option value="<?php echo date('Y',strtotime($start_date))?>"><?php echo date('Y',strtotime($start_date))?></option>
                            <option value="<?php echo date('Y',strtotime($start_date))+1?>"><?php echo date('Y',strtotime($start_date))+1?></option>
                            </select>
                            <label for="wr_by">년</label>
                            <select name="wr_bm" id="wr_bm" >
                                <?php for ($i=1; $i<=12; $i++) { ?>
                                <option value="<?php echo sprintf("%02d",$i)?>"<?php echo get_selected(date('m',strtotime($start_date)), $i); ?>><?php echo sprintf("%02d",$i)?></option>
                            <?php } ?>
                            </select>
                            <label for="wr_bm">월</label>
                            <select name="wr_bd" id="wr_bd" >
                                <?php for ($i=1; $i<=31; $i++) { ?>
                                <option value="<?php echo sprintf("%02d",$i)?>"<?php echo get_selected(date('d',strtotime($start_date)), $i); ?>><?php echo sprintf("%02d",$i)?></option>
                                <?php } ?>
                            </select>
                            <label for="wr_bd">일</label><br>
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="elem_expire_date">종료일</label></th>
            <td>
                            <select name="wr_ey" id="wr_ey" >
                            <option value="<?php echo date('Y',strtotime($end_date))?>"><?php echo date('Y',strtotime($end_date))?></option>
                            <option value="<?php echo date('Y',strtotime($end_date))+1?>"><?php echo date('Y',strtotime($end_date))+1?></option>
                            </select>
                            <label for="wr_ey">년</label>
                            <select name="wr_em" id="wr_em" >
                                <?php for ($i=1; $i<=12; $i++) { ?>
                                <option value="<?php echo sprintf("%02d",$i)?>"<?php echo get_selected(date('m',strtotime($end_date)), $i); ?>><?php echo sprintf("%02d",$i)?></option>
                            <?php } ?>
                            </select>
                            <label for="wr_em">월</label>
                            <select name="wr_ed" id="wr_ed" >
                                <?php for ($i=1; $i<=31; $i++) { ?>
                                <option value="<?php echo sprintf("%02d",$i)?>"<?php echo get_selected(date('d',strtotime($end_date)), $i); ?>><?php echo sprintf("%02d",$i)?></option>
                                <?php } ?>
                            </select>
                            <label for="wr_ed">일</label><br>
            </td>
        </tr>       
<?php } ?>        
        </tbody>
        </table>
        <h2 class="h2_frm">#현재는 무료 기본 건수 조정시 사용하는 화면입니다..</h2>
<!--        
        <h2 class="h2_frm">#건수는 현재 적용월에 적용되며 도래일에 요금제에 따라 초기화됩니다.</h2>
        <h2 class="h2_frm">#잔여건수가 있는 경우 고려하여 건수를 할당 하십시오!!!.</h2>
        <h2 class="h2_frm">#추가 건인 경우 기간 변경이 되지 않도록 주의 바랍니다.</h2>
        <h2 class="h2_frm">#무료 기본서비스인 경우에만 변환잔여건수를 사용합니다.</h2>
!-->        
    </div>
    <div class="btn_confirm01 btn_confirm">
        <input type="submit" value="확인" class="btn_submit">
    </div>
    </form>
</section>   

