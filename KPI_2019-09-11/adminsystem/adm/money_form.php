<?php
$sub_menu = "700100";
include_once('./_common.php');
include_once('./ele_scheduler.php');

auth_check($auth[$sub_menu], 'w');

$token = get_token();

$moneylst = get_member_money($mb_id);

$mb = get_member($mb_id);

if (!$mb['mb_id'])
	alert('존재하지 않는 회원자료입니다.');

if ($is_admin != 'super' && $mb['mb_level'] >= $member['mb_level'])
	alert('자신보다 권한이 높거나 같은 회원은 입금설정을 할수 없습니다.');

$required_mb_id = 'readonly';
$required_mb_password = '';
$html_title = '입금';

// 선불후불
$elem_chargetype = $moneylst['charge_type'];
$charge_type0  =  $elem_chargetype == "0" ? 'checked="checked"' : '';
$charge_type1  =  $elem_chargetype == "1" ? 'checked="checked"' : '';

$g5['title'] = '요금관리 : '.$html_title;
include_once ('./admin.head.php');
include_once ('./money_history.php');
// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js 
?>

<form name="fmember" id="fmember" action="./money_form_update.php" onsubmit="return fmoney_submit(this);" method="post" enctype="multipart/form-data">

<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?></caption>
    <colgroup>
        <col class="grid_4">
        <col>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="mb_id">아이디<?php echo $sound_only ?></label></th>
        <td>
            <input type="text" name="mb_id" value="<?php echo $mb['mb_id'] ?>" id="mb_id" <?php echo $required_mb_id ?> class="frm_input <?php echo $required_mb_id_class ?>" size="15" minlength="3" maxlength="20">            
			<input type="hidden" name="user_id" value="<?php echo $mb['mb_no'] ?>" id="user_id" class="frm_input size="15" minlength="1">
        </td>
	</tr>
    <tr>
        <th scope="row"><label for="mb_id">건당가격(원)<?php echo $sound_only ?></label></th>
        <td style = "border-width: 0!important;">
            <table class = "msg_price">
                <thead>
                    <tr style = "height:30px">
                        <th colspan="4">SMS</th>
                        <th colspan="4">LMS</th>
                    </tr>
                    <tr style = "height:30px">
                        <th>일반문자</th>
                        <th>문서포함문자</th>
                        <th>단순설문</th>
                        <th>문서포함설문</th>
                        <th>일반문자</th>
                        <th>문서포함문자</th>
                        <th>단순설문</th>
                        <th>문서포함설문</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                       <td>
                            <input type="text" name="sms_g_simple" value="<?php echo $moneylst['sms_g_simple'] ?>" id="sms_g_simple">
                       </td>
                        <td>
                            <input type="text" name="sms_g_attach" value="<?php echo $moneylst['sms_g_attach'] ?>" id="lms_g_simple">
                        </td>
                        <td>
                            <input type="text" name="sms_sur_simple" value="<?php echo $moneylst['sms_sur_simple'] ?>" id="sms_g_attach">
                        </td>
                        <td>
                            <input type="text" name="sms_sur_attach" value="<?php echo $moneylst['sms_sur_attach'] ?>" id="lms_g_attach">
                        </td>
                        <td>
                            <input type="text" name="lms_g_simple" value="<?php echo $moneylst['lms_g_simple'] ?>" id="sms_sur_simple">
                        </td>
                        <td>
                            <input type="text" name="lms_g_attach" value="<?php echo $moneylst['lms_g_attach'] ?>" id="lms_sur_simple">
                        </td>
                        <td>
                            <input type="text" name="lms_sur_simple" value="<?php echo $moneylst['lms_sur_simple'] ?>" id="sms_sur_attach">
                        </td>
                        <td>
                            <input type="text" name="lms_sur_attach" value="<?php echo $moneylst['lms_sur_attach'] ?>" id="lms_sur_attach">
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
	<tr>
		<th scope="row">충전모드 (요금제)</th>
        <td>
            <input type="radio" name="elem_chargetype" value="0" id="charge_type" <?php echo $charge_type0; ?>>
            <label for="elem_chargetype">선불충전식</label>
            <input type="radio" name="elem_chargetype" value="1" id="charge_type" <?php echo $charge_type1; ?>>
            <label for="elem_chargetype">후불정산제</label>
        </td>
	</tr>
	<tr>
		<th scope="row"><label for="elem_money_add">선불충전식 (입금액)</label></th>
        <td>
			<?php echo help('일반고객의 금액을 충전합니다. 사용시 금액은 자동차감됩니다. 입금입력시 잔액에 합산됩니다.'); ?>
            <label style = "margin-right:20px;margin-left:10px">잔액</label><input type="text" name="current_amount" id="current_amount" value ="<?php echo $moneylst['current_amount'] ?> " class="frm_input" size="15" maxlength="20" readonly>
            <label style = "margin-right:20px;margin-left:10px">추가</label><input type="text" onkeyup="add_money()" name="add_amount" id="add_amount" class="frm_input" size="15"  maxlength="20" >
            <label style = "margin-right:20px;margin-left:10px">합계</label><input type="text" name="total_amount" id="total_amount" class="frm_input" size="15" maxlength="20" readonly>
            <input type="hidden" name="original_total_deposit" id="original_total_deposit" value='<?php echo $moneylst['total_deposit']?>' class="frm_input" size="15" maxlength="20">
			<input type="hidden" name="total_deposit" id="total_deposit" value='<?php echo $moneylst['total_deposit']?>' class="frm_input" size="15" maxlength="20"> 원
            <input type = "button" value = "입금내역" class = "show_money_history" onclick="showMoneyHistory()">
		</td>
        <script>
                function add_money() {
                    $("#total_amount").val(parseInt($("#current_amount").val(), 10) + parseInt($("#add_amount").val(), 10));
                    $("#total_deposit").val(parseInt($("#original_total_deposit").val(), 10) + parseInt($("#add_amount").val(), 10));
                }
        </script>
	</tr>	
	<tr>
        <th scope="row"><label for="elem_charge_last_count">후불정산제 (발송건수 자동충전)<strong class="sound_only">필수</strong></label></th>
        <td>
			<?php echo help('입력한 건수만큼 1개월 단위로 자동충전합니다.'); ?>
			<input type="text" name="month_count" value="<?php echo $moneylst['month_count'] ?>" id="month_count" class="frm_input" size="15" maxlength="20"> 건
		</td>
    </tr>
	<tr style = "border: 0px!important;">
		<th scope="row" style = "border: 0px!important;padding-top:10px;">
		<div class="btn_confirm01 btn_confirm">
			<input type="submit" value="확인" class="btn_submit" accesskey='s'>			
			<input type="hidden" name="money_form_param" id="money_form_param" value='<? echo $qstr ?>' class="frm_input">
			<a href="./money_list.php?<?php echo $qstr ?>">목록</a>
		</div>
		</th>		
	</tr>
    </tbody>
    </table>
</div>
</form>

<script>
// $(function () {
//     $('select').on('change', function (e) {
//         var optionSelected = $("option:selected", this);
//         var valueSelected = this.value;
//     });
// });
function showMoneyHistory(){
    $('#fmember').hide();
    $('#attachedHTMLArea').show();
}
function onShowMainArea(){
    $('#fmember').show();
    $('#attachedHTMLArea').hide();
}
function fmoney_submit(f)
{
    if (!f.mb_icon.value.match(/\.gif$/i) && f.mb_icon.value) {
        alert('아이콘은 gif 파일만 가능합니다.');
        return false;
    }

    return true;
}
</script>

<?php
include_once('./admin.tail.php');
?>
