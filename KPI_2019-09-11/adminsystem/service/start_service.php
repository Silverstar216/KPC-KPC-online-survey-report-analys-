<?php
define('G5_IS_SERVICE', true);
include_once('../common.php');
$pgMNo = 8;
$pgMNo1 = 1;
if($is_guest)  
alert('회원이시라면 로그인 후 이용해 보십시오.', 
    G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/serv.php?m1='.$pgMNo.'&m2='.$pgMNo1));

include_once('../_head.php');

$g5['title'] = "결제하기";

if ($p1 =='011') {
    $img1 = '/main/images/sub07_05_txt011.png';
    $img2 = '/main/images/sub07_05_txt04.png';
} else if ($p1 =='012') {
    $img1 = '/main/images/sub07_05_txt011.png';
    $img2 = '/main/images/sub07_05_txt05.png';
} else if ($p1 =='013') {
    $img1 = '/main/images/sub07_05_txt011.png';
    $img2 = '/main/images/sub07_05_txt06.png';
} else if ($p1 =='121') {
    $img1 = '/main/images/sub07_05_txt021.png';
    $img2 = '/main/images/sub07_05_txt07.png';
} else if ($p1 =='122') {
    $img1 = '/main/images/sub07_05_txt021.png';
    $img2 = '/main/images/sub07_05_txt08.png';
} else {
    $img1 = '/main/images/sub07_05_txt021.png';
    $img2 = '/main/images/sub07_05_txt09.png';
}        


// 상태 확인
$Sql_text = "select * from ele_money_request   where eler_id = '{$member['mb_no']}' and eler_stat = '1' ";
$srow = sql_fetch($Sql_text);
if ($srow) {
    alert('입금 확인 중인 내역이 존재합니다.', '../serv.php?m1=8&m2=6'); 
}
$Sql_text = "select * from ele_money_mst  where elem_id = '{$member['mb_no']}' ";
$srow = sql_fetch($Sql_text);
$restart_flag = true;
if ($srow) {
    $elem_stat = $srow['elem_stat'];
    $elem_type = $srow['elem_type'];
    $elem_expire_date = $srow['elem_expire_date'];
    $elem_crnt_cnt = $srow['elem_crnt_cnt'];
    $elem_crnt_bonus = $srow['elem_crnt_bonus'];    
    if ( $elem_stat == 'Y') {
        if (($elem_type == '012')||($elem_type == '013')||($elem_type == '122')||($elem_type == '123')){            
            $elem_expire_date  = date("Y-m-d",strtotime($srow['elem_expire_date']));   
            if ($elem_expire_date >= date("Y-m-d")) {
                $restart_flag = false;                // 추가 요금 사용 가능 
            }
        }
    }
}
$Sql_text = "select * from ele_price_user  where elpu_type = '{$p1}' and elpu_stat = 'm' ";
$sprow = sql_fetch($Sql_text);
{
$eler_money = $sprow['elpu_money'];
}
// 요금제 전환인지 추가 요금인지...
?>
<style type="text/css">
#money_help { color: #24989b; font-size: 1.4em; margin: 15px 0;} 
</style>
<div id="sub_content">
<section id="point_mng">        
    <img src="/main/images/direct_bank.png" >          
    <div id="money_help">위 계좌로 무통장 입금후 아래 내역을 알려주시면 입금 확인후 처리됩니다.</div>
    <form name="fpointlist2" method="post" id="fpointlist2" action="./ele_service_start_update.php" onsubmit="return start_submit(this);" autocomplete="off">
        <input type="hidden" name="elem_type" id="elem_type" value="<?=$p1?>">
    <div class="tbl_frm01 tbl_wrap">
        <table>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="eler_name">입금인명<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="eler_name" id="eler_name" required class="required frm_input" value="<?= $member['mb_nick'] ?>"></td>
        </tr>            
        <tr>
            <th scope="row"><label for="eler_money">입금금액<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="eler_money" id="eler_money" >  </td>
        </tr>
        </tbody>
        </table>
    </div>
    <div class="btn_confirm01 btn_confirm">
        <input type="submit" value="확인 요청" class="btn_submit">
    </div>
    </form>
</section>
</div>
<script type="text/javascript">
$(function(){ 
     $("body").on("click","#eler_add_money", function(e) {
        if ($('#eler_add_money').is(':checked')==true) {
            $('#elem_type').attr('value','777');
        } else {
            $('#elem_type').attr('value','<?=$p1?>');
        }        
    });     
 });

function start_submit(f)
{
    if (confirm("입금 확인 요청하시겠습니까??") == true){    //확인

    }else{   //취소
        return false;
    }

}
</script>
<?php
include_once('../_tail.php');
?>