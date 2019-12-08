<?php
  if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가   

  $var_cnt_row = sql_fetch("select count(*) as cnt from edoc_variable where edcv_udoc='{$udoc}' and edcv_mbno = '{$member['mb_no']}' and edcv_grid = '{$ed_mnid}' ");
  $var_cnt = $var_cnt_row['cnt'];
?>
<!--
<div id="tab_sms_helper">    
    <button type="button" id="btn_sh2" class="tab_btn_sms_helper" onclick="tab_helper(2)">내 메세지</button>                                                        
</div>    
<div id="send_emo">    
    <?php include_once('/service/sms_write_form.php'); ?>
</div>
-->
<script type="text/javascript"> 
/* function tab_helper(whichpan){
        $("#btn_sh2").css("color","#464646");        
        $("#send_emo").show(); 
}
*/
var is_sms5_submitted = false;  //중복 submit방지
function sms5_chk_send(f)
{
    if( is_sms5_submitted == false ){
        is_sms5_submitted = true;
        var hp_list = document.getElementById('hp_list');
        var wr_message = document.getElementById('wr_message');
        var hp_number = document.getElementById('hp_number');
        var list = '';

        if (!wr_message.value) {
            alert('메세지를 입력해주세요.');
            wr_message.focus();
            is_sms5_submitted = false;
            return false;
        }

        if (hp_list.length < 1) {
            alert('받는 사람을 입력해주세요.');
            hp_number.focus();
            is_sms5_submitted = false;
            return false;
        }
        if(!confirm("문자 전송 하시겠습니까?")) {
            is_sms5_submitted = false;
            return false;
        }
        for (i=0; i<hp_list.length; i++)
            list += hp_list.options[i].value + '/';

        f.send_list.value = list;

        return true;
    } else {
        alert("데이터 전송중입니다.");
    }
}


function booking(val)
{
    if (val)
    {
        document.getElementById('wr_by').disabled = false;
        document.getElementById('wr_bm').disabled = false;
        document.getElementById('wr_bd').disabled = false;
        document.getElementById('wr_bh').disabled = false;
        document.getElementById('wr_bi').disabled = false;
    }
    else
    {
        document.getElementById('wr_by').disabled = true;
        document.getElementById('wr_bm').disabled = true;
        document.getElementById('wr_bd').disabled = true;
        document.getElementById('wr_bh').disabled = true;
        document.getElementById('wr_bi').disabled = true;
    }
}

<?php
if ($fo_no) {
    $row = sql_fetch("select * from {$g5['sms5_form_table']} where fo_no='$fo_no'");
    $fo_content = str_replace(array("\r\n","\n"), "\\n", $row['fo_content']);
    echo "add(\"$fo_content\");";
}
?>

byte_check('wr_message', 'sms_bytes');
document.getElementById('wr_message').focus();
//tab_helper(2);

$(function(){
    $(".box_txt").bind("focus keydown", function(){
        $("#wr_message_lbl").hide();
    });

});

var sms_obj={
    phone_number : [],
    el_box : "#num_book",
    person_is_search : false,
    var_group_add : function(){
        bg_count = "<?php echo $var_cnt ?>";
        bg_no = "<?php echo $udoc ?>";
        var hp_list = document.getElementById('hp_list');
        var item    = " 총 (" + bg_count + " 건)";
        var value   = 'v,' + bg_no;
        hp_list.options[hp_list.length] = new Option(item, value);
        hp_list.value = value;
    }
};
sms_obj.var_group_add();

</script>