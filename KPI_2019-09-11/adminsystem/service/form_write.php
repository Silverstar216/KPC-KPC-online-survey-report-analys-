<?php
define('G5_IS_SERVICE', true);
include_once('../common.php');
$pgMNo = 4;
$pgMNo1 = 5;
if($is_guest)  
alert('회원이시라면 로그인 후 이용해 보십시오.', 
	G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/serv.php?m1='.$pgMNo.'&m2='.$pgMNo1));
include_once('../_head.php');
include_once('../_ele_sub_menu.php');
$g5['title'] = "내 메세지";

if ($w == 'u' && is_numeric($fo_no)) {
    $write = sql_fetch("select * from {$g5['sms5_form_table']} where fo_no='$fo_no'");
    $g5['title'] .= '수정';
}
else  {
    $write['fg_no'] = $fg_no;
    $g5['title'] .= '추가';
}
?>
<div class="titlegroup">
     <em>내 메세지</em>      
</div>
<!-- 휴대폰번호 -->
<div class="phonegroup">
<div class="phonegroupin">
<div class="phonegroupwrap">
<div id="sub_content">
<form name="book_form" method="post" action="form_update.php">
<input type="hidden" name="w" value="<?php echo $w?>">
<input type="hidden" name="page" value="<?php echo $page?>">
<input type="hidden" name="fo_no" value="<?php echo $write['fo_no']?>">
<input type="hidden" name="get_fg_no" value="<?php echo $fg_no?>">

    <div class="tbl_frm01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title'];?> 목록</caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="fg_no">그룹<strong class="sound_only"> 필수</strong></label></th>
        <td>
            <select name="fg_no" id="fg_no" required class="required">
                <option value="0">미분류</option>
                <?php
                $qry = sql_query("select * from {$g5['sms5_form_group_table']} order by fg_name");
                while($res = sql_fetch_array($qry)) {
                ?>
                <option value="<?php echo $res['fg_no']?>"<?php echo get_selected($res['fg_no'], $write['fg_no']); ?>><?php echo $res['fg_name']?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="fo_name">제목<strong class="sound_only"> 필수</strong></label></th>
        <td><input type="text" name="fo_name" id="fo_name" required value="<?php echo $write['fo_name']?>" class="frm_input required" size="70"></td>
    </tr>
    <tr>
        <th scope="row">메세지</th>
        <td>        
		<?php include_once('./Phone_BOX2.php'); ?>
        </td>
    </tr>
    <?php if ($w == 'u') {?>
    <tr>
        <th scope="row">업데이트</th>
        <td> <?php echo $write['fo_datetime']?> </td>
    </tr>
    <?php } ?>
    </tbody>
    </table>
    </div>
    <div class="btn_confirm01 btn_confirm">
        <input type="submit" value="확인" class="btn_submit" accesskey="s">
        <a href="/serv.php?m1=4&m2=5?<?php echo $_SERVER['QUERY_STRING']?>">목록</a>
    </div>
</form>

<script>
$(function(){ 
    $(".scemo_cls_btn").click(function(){
        $(".write_scemo").hide();
    });
});

function show_Input_charPan(whichPan){
    $(".write_scemo").hide();    
    if (whichPan == 1) {
        $("#write_sc").show(); 
    } else if (whichPan == 2) {
        $("#write_emo").show(); 
    } else {
        $("#wr_message").attr("value","");
        byte_check('wr_message', 'sms_bytes');
        $("#wr_message").focus();        
    }    
}

    function add(str) {
        var conts = document.getElementById('wr_message');
        var bytes = document.getElementById('sms_bytes');
        conts.focus();
        conts.value+=str;
        byte_check('wr_message', 'sms_bytes');
        return;
    }
    function byte_check(wr_message, sms_bytes)
    {
        var conts = document.getElementById(wr_message);
        var bytes = document.getElementById(sms_bytes);

        var i = 0;
        var cnt = 0;
        var exceed = 0;
        var ch = '';

        for (i=0; i<conts.value.length; i++)
        {
            ch = conts.value.charAt(i);
            if (escape(ch).length > 4) {
                cnt += 2;
            } else {
                cnt += 1;
            }
        }

        bytes.innerHTML = cnt;

        if (cnt > 80)
        {
            exceed = cnt - 80;
            alert('메시지 내용은 80바이트를 넘을수 없습니다.\n\n작성하신 메세지 내용은 '+ exceed +'byte가 초과되었습니다.\n\n초과된 부분은 자동으로 삭제됩니다.');
            var tcnt = 0;
            var xcnt = 0;
            var tmp = conts.value;
            for (i=0; i<tmp.length; i++)
            {
                ch = tmp.charAt(i);
                if (escape(ch).length > 4) {
                    tcnt += 2;
                } else {
                    tcnt += 1;
                }

                if (tcnt > 80) {
                    tmp = tmp.substring(0,i);
                    break;
                } else {
                    xcnt = tcnt;
                }
            }
            conts.value = tmp;
            bytes.innerHTML = xcnt;
            return;
        }
    }

    byte_check('wr_message', 'sms_bytes');
    document.getElementById('wr_message').focus();
</script>
</div>
</div>
</div>
</div>
<?php
include_once('../_tail.php');
?>
