<?php
  if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가   
?>
<div id="tab_sms_helper">
    <button type="button" id="btn_sh1" class="tab_btn_sms_helper" onclick="tab_helper(1);">휴대폰 주소록</button>                                                      
</div>    
<div id="send_emo">    
    <?php include_once('service/sms_write_form.php'); ?>        
</div>
   <div id="send_book">
        <div id="book_tab">
            <a href="#book_group" id="book_group">그룹</a>
            <a href="#book_person" id="book_person">개인</a>
        </div>
        <div id="num_book"></div>        
    </div>
<script type="text/javascript"> 

function set_telephone_count(){
    var CurrCnt = 0;
    var hp_list = document.getElementById('hp_list');
     for (i=0; i<hp_list.length; i++) {
           hkind = hp_list[i].value;
           hkind = hkind.substring(0,1);
           if (hkind == 'h') {
                CurrCnt = CurrCnt+1;     
           } else {
                countStr = hp_list.options[i].innerHTML;
                existPos = countStr.indexOf('명)');
                if (existPos == -1){
                    CurrCnt = CurrCnt+1;     
                } else {
                    startPos = countStr.lastIndexOf('(');
                     if (startPos == -1){   
                        CurrCnt = CurrCnt+1;     
                     } else {
                        hCnt = 1*countStr.substring(startPos+1,existPos);
                        CurrCnt = CurrCnt+hCnt;
                     } 
                }
           }                      
        }
      $('#sel_cnt').html(CurrCnt);
}

function tab_helper(whichpan){
    if (whichpan == 1) {
        $("#btn_sh1").css("color","#464646");        
        $("#btn_sh2").css("color","#999999");
        $("#send_emo").hide();
        $("#send_book").show(); 
    } else if (whichpan == 2) {
        $("#btn_sh1").css("color","#999999");        
        $("#btn_sh2").css("color","#464646");        
        $("#send_book").hide();        
        $("#send_emo").show(); 
    }    
}
function overlap_check()
{
    var hp_list = document.getElementById('hp_list');
    var hp_number = document.getElementById('hp_number');
    var list = '';

    if (hp_list.length < 1) {
        alert('받는 사람을 입력해주세요.');
        hp_number.focus();
        return;
    }

    for (i=0; i<hp_list.length; i++)
        list += hp_list.options[i].value + '/';

    (function($){
        var $form = $("#form_sms");
        $form.find("input[name='send_list']").val( list );
        var params = $form.serialize();
        $.ajax({
            url: '/service/sms_write_overlap_check.php',
            cache:false,
            timeout : 30000,
            dataType:"html",
            data:params,
            success: function(data) {
                alert(data);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    })(jQuery);
}

function dapy_local_check(secnum){
    if (secnum.length == 4){
        switch(secnum) {
        case '1588' : 
        case '1577' : 
        case '1899' : 
        case '1666' : 
        case '1688' : 
        case '1544' : 
        case '1644' : 
        case '1661' : 
        case '1800' : 
        case '1599' : 
        case '1566' : 
        case '1600' : 
        case '1670' : 
        case '1855' : 
        case '1877' :         
                return false;
                break;
        default : return true; 
        }
    } else if (secnum.length == 3){
        return true;
    } else {
        return false;
    }
}
function check_replay(aphone){    
    var hp_reply = document.getElementById(aphone);
    if (!hp_reply.value){
        return false;
    }
    var rr_num = '';
    var ii = 1;
    pattern = /^[0-9]$/;
    for (i=0; i<hp_reply.value.length; i++)
    {
        ch = hp_reply.value.charAt(i);        
        if (pattern.test(ch)) {
            rr_num = rr_num+ch;
        }
    }    
    if (!rr_num){
        return false;
    }
    if (rr_num.length < 8){
        return false;   
    }

    pattern = /^01[016789][0-9]{3,4}[0-9]{4}$/;
    if (pattern.test(rr_num)) {
        hp_reply.value = rr_num.replace(new RegExp("(01[016789])([0-9]{3,4})([0-9]{4})"), "$1-$2-$3");
        return true;
    }
    patternS = /^02[0-9]{3,4}[0-9]{4}$/;
    if (patternS.test(rr_num)) {
        gpattern = "(02)([0-9]{3,4})([0-9]{4})";
        senum = rr_num.match(gpattern);
        if (dapy_local_check(senum[2]) == true) {
            hp_reply.value = rr_num.replace(new RegExp(gpattern), "$1-$2-$3");
            return true;
        } else { 
            return false;
        }        
    }
    patternS = /^070[0-9]{3,4}[0-9]{4}$/;
    if (patternS.test(rr_num)) {        
        hp_reply.value = rr_num.replace(new RegExp("(070)([0-9]{3,4})([0-9]{4})"), "$1-$2-$3");
        return true;
    }    
    patternS = /^03[123][0-9]{3,4}[0-9]{4}$/;    
    if (patternS.test(rr_num)) {
        gpattern = "(03[123])([0-9]{3,4})([0-9]{4})";
        senum = rr_num.match(gpattern);
        if (dapy_local_check(senum[2]) == true) {
            hp_reply.value = rr_num.replace(new RegExp(gpattern), "$1-$2-$3");
            return true;
        } else { 
            return false;
        }        
    }
    patternS = /^0[46][1234][0-9]{3,4}[0-9]{4}$/;    
    if (patternS.test(rr_num)) {
        gpattern = "(0[46][1234])([0-9]{3,4})([0-9]{4})";
        senum = rr_num.match(gpattern);
        if (dapy_local_check(senum[2]) == true) {
            hp_reply.value = rr_num.replace(new RegExp(gpattern), "$1-$2-$3");
            return true;
        } else { 
            return false;
        }        
    }
    patternS = /^05[12345][0-9]{3,4}[0-9]{4}$/;    
    if (patternS.test(rr_num)) {
        gpattern = "(05[12345])([0-9]{3,4})([0-9]{4})";
        senum = rr_num.match(gpattern);
        if (dapy_local_check(senum[2]) == true) {
            hp_reply.value = rr_num.replace(new RegExp(gpattern), "$1-$2-$3");
            return true;
        } else { 
            return false;
        }        
    }
    if (rr_num.length == 8){
        pre8num = rr_num.substring(0,4);
        switch(pre8num) {
        case '1588' : 
        case '1577' : 
        case '1899' : 
        case '1666' : 
        case '1688' : 
        case '1544' : 
        case '1644' : 
        case '1661' : 
        case '1800' : 
        case '1599' : 
        case '1566' : 
        case '1600' : 
        case '1670' : 
        case '1855' : 
        case '1877' : 
            hp_reply.value = rr_num.replace(new RegExp("([0-9]{4})([0-9]{4})"), "$1-$2");        
            return true; 
            break;
        default : return false;
        }        
    }
    return false;
}

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
        if (!byte_last_check()){
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
		
		var p_reply = document.getElementById('wr_reply');
		
		// 메세지전송모듈 점검표시	(관리자만 가능)
		/*if (p_reply.value != '02-3463-3714') {
			alert('메세지전송모듈 수정중입니다. 잠시만 기다려주세요...');			
			is_sms5_submitted = false;
			return false;            
		}*/
		
		
        if (!check_replay('wr_reply')){
            alert('회신번호 형식이 올바르지 않습니다.');
            p_reply.focus();
            is_sms5_submitted = false;
            return false;            
        }
        <?php if ($LMS_flag == true) { ?>
                if (scf == true ) {
                        if(!confirm("LMS 문자 전송 하시겠습니까?")) {
                            wr_message.focus();
                            is_sms5_submitted = false;
                            return false;
                        }      
                } else {
                        if(!confirm("문자 전송 하시겠습니까?")) {
                            wr_message.focus();
                            is_sms5_submitted = false;
                            return false;
                        }                                            
                }
        <?php } else { ?>            
                if(!confirm("문자 전송 하시겠습니까?")) {
                    wr_message.focus();
                    is_sms5_submitted = false;
                    return false;
                }                        
        <?php } ?>                        
        for (i=0; i<hp_list.length; i++)
            list += hp_list.options[i].value + '/';

        f.send_list.value = list;
        return true;
    } else {
        alert("현재 메시지 전송중입니다. 잠시후 확인하십시요");
		f.send_list.value = '';
		return false;
    }
}

function byte_last_check()
{    
    var conts = document.getElementById('wr_message');
    var i = 0;
    var ch = '';
    var chcd = ''; 
    var ulen = 0;
    var upnt = 0;
    for (i=0; i<conts.value.length; i++)
    {
        ch = conts.value.charAt(i);        
        chcd = conts.value.charCodeAt(i);
        if (ch == ''){

        } else if ((chcd > 31 ) &&(chcd < 127)){
        } else if ((chcd > 12592 ) &&(chcd < 12623)){
        } else if ((chcd > 44031 ) &&(chcd < 55204)){
        } else if (chcd == 9){            
        } else if (chcd == 10){                        
        } else if (chcd == 8203){            
        } else if (chcd == 8470){
        } else if (chcd == 8481){
        } else if (chcd == 8482){
        } else if (chcd == 8719){
        } else if (chcd == 8721){
        } else if (chcd == 8857){
        } else if (chcd == 9618){
        } else if (chcd == 9632){
        } else if (chcd == 9633){
        } else if (chcd == 9635){
        } else if (chcd == 9650){
        } else if (chcd == 9654){
        } else if (chcd == 9655){
        } else if (chcd == 9660){
        } else if (chcd == 9664){
        } else if (chcd == 9665){
        } else if (chcd == 9670){
        } else if (chcd == 9671){
        } else if (chcd == 9672){
        } else if (chcd == 9675){
        } else if (chcd == 9679){
        } else if (chcd == 9733){
        } else if (chcd == 9734){
        } else if (chcd == 9742){
        } else if (chcd == 9743){
        } else if (chcd == 9756){
        } else if (chcd == 9758){
        } else if (chcd == 9824){
        } else if (chcd == 9825){
        } else if (chcd == 9827){
        } else if (chcd == 9828){
        } else if (chcd == 9829){
        } else if (chcd == 9831){
        } else if (chcd == 9832){
        } else if (chcd == 9833){
        } else if (chcd == 9834){
        } else if (chcd == 9836){
        } else if (chcd == 9837){
        } else if (chcd == 12828){
        } else if (chcd == 12927){              
        } else {
            upnt = i+1;
            alert('휴대폰에서는 표현되지 않는 글자 '+ch+' 가 포함(앞에서 '+upnt+'번째)되어있습니다.');
            return false;
        }
    }
    return true; 
}  
function hp_add()
{
    var hp_number = document.getElementById('hp_number'),
        hp_name = document.getElementById('hp_name'),
        hp_list = document.getElementById('hp_list'),
        pattern = /^01[016789][0-9]{3,4}[0-9]{4}$/,
        pattern2 = /^01[016789]-[0-9]{3,4}-[0-9]{4}$/;

    if( !hp_number.value ){
        alert("휴대폰번호를 입력해 주세요.");
        hp_number.select();
        return;
    }

    if(!pattern.test(hp_number.value) && !pattern2.test(hp_number.value)) {
        alert("휴대폰번호 형식이 올바르지 않습니다.");
        hp_number.select();
        return;
    }

    if (!pattern2.test(hp_number.value)) {
        hp_number.value = hp_number.value.replace(new RegExp("(01[016789])([0-9]{3,4})([0-9]{4})"), "$1-$2-$3");
    }

    var item = '';
    if (trim(hp_name.value))
        item = hp_name.value + ' (' + hp_number.value + ')';
    else
        item = hp_number.value;

    var value = 'h,' + hp_name.value + ':' + hp_number.value;

    for (i=0; i<hp_list.length; i++) {
        if (hp_list[i].value == value) {
            alert('이미 같은 목록이 있습니다.');
            return;
        }
    }

    if( jQuery.inArray( hp_number.value , sms_obj.phone_number ) > -1 ){
       alert('목록에 이미 같은 휴대폰 번호가 있습니다.');
       return;
    } else {
        sms_obj.phone_number.push( hp_number.value );
    }
    hp_list.options[hp_list.length] = new Option(item, value);   
    hp_list.value = value;
    set_telephone_count();
    hp_number.value = '';
    hp_name.value = '';
    hp_name.select();
}

function hp_list_del()
{
    var hp_list = document.getElementById('hp_list');

    if (hp_list.selectedIndex < 0) {
        alert('삭제할 목록을 선택해주세요.');
        return;
    }

    var regExp = /(01[016789]{1}|02|0[3-9]{1}[0-9]{1})-?[0-9]{3,4}-?[0-9]{4}/,
        hp_number_option = hp_list.options[hp_list.selectedIndex],
        result = (hp_number_option.outerHTML.match(regExp));
    if( result !== null ){
        sms_obj.phone_number = sms_obj.array_remove( sms_obj.phone_number, result[0] );
    }
    hp_list.options[hp_list.selectedIndex] = null;
    set_telephone_count();
}

function hp_list_all_del()
{
    var hp_list = document.getElementById('hp_list');

    if (hp_list.length < 1) {
        alert('삭제할 목록이 없습니다.');
        return;
    }
    sms_obj.phone_number = sms_obj.array_all_remove(sms_obj.phone_number);
    for(var i = hp_list.length; i--;) {
              hp_list.remove(i);
     }    
    set_telephone_count();     
}

function book_change(id)
{
    var book_group  = document.getElementById('book_group');
    var book_person = document.getElementById('book_person');
    var num_book    = document.getElementById('num_book');
    var menu_group  = document.getElementById('menu_group');

    if (id == 'book_group')
    {
        book_group.style.fontWeight    = 'bold';
        book_person.style.fontWeight   = 'normal';
    }
    else if (id == 'book_person')
    {
        book_group.style.fontWeight    = 'normal';
        book_person.style.fontWeight   = 'bold';
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
if ($bk_no) {
$row = sql_fetch("select * from {$g5['sms5_book_table']} where bk_no='$bk_no'");
?>

var hp_list = document.getElementById('hp_list');
var item    = "<?php echo $row['bk_name']?> (<?php echo $row['bk_hp']?>)";
var value   = "p,<?php echo $row['bk_no']?>";
hp_list.options[hp_list.length] = new Option(item, value);
hp_list.value = value;
set_telephone_count();
<?php } ?>

<?php
if ($fo_no) {
    $row = sql_fetch("select * from {$g5['sms5_form_table']} where fo_no='$fo_no'");
    $fo_content = str_replace(array("\r\n","\n"), "\\n", $row['fo_content']);
    echo "add(\"$fo_content\");";
}
?>

byte_check('wr_message', 'sms_bytes');
document.getElementById('wr_message').focus();
</script>

<?php
if ($wr_no)
{
    // 메세지와 회신번호
    $row = sql_fetch(" select * from {$g5['sms5_write_table']} where wr_no = '$wr_no' ");

    echo "<script>\n";
    echo "var hp_list = document.getElementById('hp_list');\n";
    //echo "add(\"$row[wr_message]\");\n";
    $wr_message = str_replace('"', '\"', $row['wr_message']);
    $wr_message = str_replace(array("\r\n","\n"), "\\n", $wr_message);
    echo "add(\"$wr_message\");\n";
    echo "document.getElementById('wr_reply').value = '{$row['wr_reply']}';\n";

    // 회원목록
    $sql = " select * from {$g5['sms5_history_table']} where wr_no = '$wr_no' and bk_no > 0 ";
    $qry = sql_query($sql);
    $tot = mysql_num_rows($qry);

    if ($tot > 0) {

        $str = '재전송그룹 ('.number_format($tot).'명)';
        $val = 'p,';

        while ($row = sql_fetch_array($qry))
        {
            $val .= $row['bk_no'].',';
        }
        echo "hp_list.options[hp_list.length] = new Option('$str', '$val');\n";
        echo "hp_list.value = '$val';\n";
        echo "set_telephone_count();\n";        
    }

    // 비회원 목록
    $sql = " select * from {$g5['sms5_history_table']} where wr_no = '$wr_no' and bk_no = 0 ";
    $qry = sql_query($sql);
    $tot = mysql_num_rows($qry);

    if ($tot > 0)
    {
        while ($row = sql_fetch_array($qry))
        {
            $str = "{$row['hs_name']} ({$row['hs_hp']})";
            $val = "h,{$row['hs_name']}:{$row['hs_hp']}";
            echo "hp_list.options[hp_list.length] = new Option('$str', '$val');\n";
            echo "hp_list.value = '$val';\n";
        }        
        echo "set_telephone_count();\n";        
    }
    echo "</script>\n";
}
?>
<script>
$(function(){
    $(".box_txt").bind("focus keydown", function(){
        $("#wr_message_lbl").hide();
    });
    $(".write_scemo_btn").click(function(){
        $(".write_scemo").hide();
        $(this).next(".write_scemo").show();
    });
    $(".scemo_cls_btn").click(function(){
        $(".write_scemo").hide();
    });
});

var sms_obj={
    phone_number : [],
    el_box : "#num_book",
    person_is_search : false,
    array_remove : function(arr, item){
        for(var i = arr.length; i--;) {
          if(arr[i] === item) {
              arr.splice(i, 1);
          }
        }
        return arr;
    },
    array_all_remove : function(arr){
        for(var i = arr.length; i--;) {
              arr.splice(i, 1);
        }
        return arr;
    },        
    book_all_checked : function(chk){
        var bk_no = document.getElementsByName('bk_no');

        if (chk) {
            for (var i=0; i<bk_no.length; i++) {
                bk_no[i].checked = true;
            }
        } else {
            for (var i=0; i<bk_no.length; i++) {
                bk_no[i].checked = false;
            }
        }
    },
    group_all_checked : function(chk){
        var bk_no = document.getElementsByName('bkg_no');
    
        if (chk) {
            for (var i=0; i<bk_no.length; i++) {
                var ncnt = $(bk_no[i]).attr('cnt');
                if (ncnt < 1) {
                    bk_no[i].checked = false;    
                } else {
                    bk_no[i].checked = true;    
                }                
            }
        } else {
            for (var i=0; i<bk_no.length; i++) {
                bk_no[i].checked = false;
            }
        }
    },    
    group_txt_sel : function(){
        var bk_no = document.getElementsByName('bkg_no');
        for (i=0; i<bk_no.length; i++) {
                 bk_no[i].checked=false;    
        }

        var sc_txt = $('#gr_seltext').attr('value');
        if (sc_txt =='') { 
            alert('그룹명에서 찾을 단어를 입력하세요!')
            $('#gr_seltext').focus();
            return;
        }
    
        var scnt = 0;
        if (bk_no.length > 0){
            var firstObj = $(bk_no[0]).parent().parent();        
        }
        for (var i=0; i<bk_no.length; i++) {


            var gname = $(bk_no[i]).attr("grnm");
            var ichk = gname.indexOf(sc_txt);
            if  (ichk < 0) { continue; }         
            scnt++;
            bk_no[i].checked=true;                        
            pidObj = $(bk_no[i]).parent().parent(); 
            if (scnt==1){
                if(i > 0) {
                    pidObj.insertBefore(firstObj);  
                    firstObj = pidObj;         
                }  
            } else {
                pidObj.insertAfter(firstObj);
                 firstObj = pidObj;  
            }            
        }
        if (scnt == 0){
            alert('해당 단어는 그룹명에 없습니다.');
        } else {
            alert(scnt+'건의 그룹이 선택되었습니다');
        }
    },    

    person_add : function(bk_no, bk_name, bk_hp){
        var hp_list = document.getElementById('hp_list');
        var item    = bk_name + " (" + bk_hp + ")";
        var value   = 'p,' + bk_no;

        for (i=0; i<hp_list.length; i++) {
            if (hp_list[i].value == value) {
                alert('이미 같은 목록이 있습니다.');
                return;
            }
        }
        if( jQuery.inArray( bk_hp , this.phone_number ) > -1 ){
           alert('목록에 이미 같은 휴대폰 번호가 있습니다.');
           return;
        } else {
            this.phone_number.push( bk_hp );
        }
        hp_list.options[hp_list.length] = new Option(item, value);
        hp_list.value = value;
        set_telephone_count();
    },
    person_multi_add : function(){
        var bk_no = document.getElementsByName('bk_no');
        var count = 0;

        for (i=0; i<bk_no.length; i++) {
            if (bk_no[i].checked==true) {
                count++;
            }
        }

        if (!count) {
            alert('하나이상 선택해주세요.');
            return;
        }
        for (i=0; i<bk_no.length; i++) {            
            if (bk_no[i].checked==true) {
                var bk_name = $(bk_no[i]).attr('nm');
                var bk_number     = $(bk_no[i]).attr('value');
                var bk_hp     = $(bk_no[i]).attr('bhp');

                var hp_list = document.getElementById('hp_list');
                var item    = bk_name + " (" + bk_hp + ")";
                var value   = 'p,' + bk_number;
                var unique_flag = true;
                for (ki=0; ki<hp_list.length; ki++) {
                    if (hp_list[ki].value == value) {
                        unique_flag = false;
                        break;
                    }
                }
                if (unique_flag == false) { continue; }

                if( jQuery.inArray( bk_hp , this.phone_number ) > -1 ){
                   continue;
                } else {
                    this.phone_number.push( bk_hp );
                }
                hp_list.options[hp_list.length] = new Option(item, value);
                hp_list.value = value;
            }
        }
        set_telephone_count();
    },
    group_multi_add : function(){
        var bk_no = document.getElementsByName('bkg_no');
        var ck_no = '';
        var count = 0;

        for (i=0; i<bk_no.length; i++) {
            if (bk_no[i].checked==true) {
                count++;
            }
        }
        if (!count) {
            alert('하나이상 선택해주세요.');
            return;
        }
        for (i=0; i<bk_no.length; i++) {
            if (bk_no[i].checked==true) {
                var grno = $(bk_no[i]).attr("value");
                var gcnt = $(bk_no[i]).attr("cnt");
                var gname = $(bk_no[i]).attr("grnm");
                if (gcnt > 0) { 
                    var hp_list = document.getElementById('hp_list');
                    var item    = gname + " 그룹 (" + gcnt + " 명)";
                    var value   = 'g,' + grno;
                    var dup_flag = false;
                    for (ki=0; ki<hp_list.length; ki++) {
                        if (hp_list[ki].value == value) {
                            dup_flag = true;
                            break;
                        }
                    }
                    if (dup_flag == false) {
                            hp_list.options[hp_list.length] = new Option(item, value);
                            hp_list.value = value;
                    }
               }
            }
        }        
        set_telephone_count();        
    },    
    person : function(bg_no){
        var params = { bg_no : bg_no };
        this.person_is_search = false;
        this.person_select( params, "html" );
        book_change('book_person');
    },
    group_add : function(bg_no, bg_name, bg_count){
        if (bg_count == '0') {
            alert('그룹이 비어있습니다.');
            return;
        }

        var hp_list = document.getElementById('hp_list');
        var item    = bg_name + " 그룹 (" + bg_count + " 명)";
        var value   = 'g,' + bg_no;

        for (i=0; i<hp_list.length; i++) {
            if (hp_list[i].value == value) {
                alert('이미 같은 목록이 있습니다.');
                return;
            }
        }

        hp_list.options[hp_list.length] = new Option(item, value);
        hp_list.value = value;
        set_telephone_count();
    }
};
(function($){
    $("#form_sms input[type=text], #form_sms select").keypress(function(e){
        return e.keyCode != 13;
    });
    sms_obj.fn_paging = function( hash_val,total_page,$el,$search_form ){
        $el.paging({
            current:hash_val ? hash_val : 1,
            max:total_page == 0 || total_page ? total_page : 45,
            length : 5,
            liitem : 'span',
            format:'{0}',
            next:'다음',
            prev:'이전',
            sideClass:'pg_page pg_next',
            prevClass:'pg_page pg_prev',
            first:'&lt;&lt;',last:'&gt;&gt;',
            href:'#',
            itemCurrent:'pg_current',
            itemClass:'pg_page',
            appendhtml:'<span class="sound_only">페이지</span>',
            onclick:function(e,page){
                e.preventDefault();
                $search_form.find("input[name='page']").val( page );
                var chk_g  = $search_form.find("input[name='bg_no']").attr('value');
                var params = '';                
                if (chk_g) {
                        if (chk_g > '') {
                            sms_obj.person_is_search = false;
                            params = {  bg_no : chk_g,page: page };
                        }
                } else {               
                    var chk_st  = $search_form.find("select[name='st']").attr('value');
                    var chk_sv  = $search_form.find("input[name='sv']").attr('value');
                    if (chk_sv){
                            sms_obj.person_is_search = false;
                            params = {  st : chk_st, sv: chk_sv, page: page };                        
                    } else if (page > '') {
                        sms_obj.person_is_search = true;
                    }
                }
                if( sms_obj.person_is_search ){
                    params = $search_form.serialize();
                    params = { page: page };
                }
                sms_obj.person_select( params, "html" );
            }
        });
    }
    sms_obj.person_select = function( params, type ){
        emoticon_list.loading(sms_obj.el_box, "/service/images/ajax-loader.gif" ); //로딩 이미지 보여줌
        $.ajax({
            url: "/service/ajax.sms_write_person.php",
            cache:false,
            timeout : 30000,
            dataType:type,
            data:params,
            success: function(data) {
               $(sms_obj.el_box).html(data);
               var $sms_person_form = $("#sms_person_form", sms_obj.el_box),
                   total_page = $sms_person_form.find("input[name='total_pg']").val(),
                   current_page = $sms_person_form.find("input[name='page']").val()
               sms_obj.fn_paging( current_page, total_page, $("#person_pg", sms_obj.el_box), $sms_person_form );
               $sms_person_form.bind("submit", function(e){
                   e.preventDefault();
                   sms_obj.person_is_search = true;
                   $(this).find("input[name='total_pg']").val('');
                   $(this).find("input[name='page']").val('');
                   var params = $(this).serialize();
                   sms_obj.person_select( params, "html" );
                   emoticon_list.loadingEnd(sms_obj.el_box); //로딩 이미지 지움
               });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    }
    sms_obj.triggerclick = function( sel ){
        $(sel).trigger("click");
    }
    $("#book_person").bind("click", function(e){
        e.preventDefault();
        book_change( $(this).attr("id") );
        sms_obj.person_is_search = false;
        sms_obj.person_select( '','html' );
    });
    $("#book_group").bind("click", function(e){
        e.preventDefault();
        book_change( $(this).attr("id") );
        emoticon_list.loading(sms_obj.el_box, "/service/images/ajax-loader.gif" ); //로딩 이미지 보여줌
        $.ajax({
            url: "/service/ajax.sms_write_group.php",
            cache:false,
            timeout : 30000,
            dataType:'html',
            success: function(data) {
                $(sms_obj.el_box).html(data);
                emoticon_list.loadingEnd(sms_obj.el_box); //로딩 이미지 지움
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        })
    }).trigger("click");
})(jQuery);
</script>