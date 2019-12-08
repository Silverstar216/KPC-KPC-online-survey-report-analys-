<?php
  if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가       
  if (isset($polltype)) {
          if ($polltype == 0) {// 첨부 파일 
            $ATTACH_FILE = true;            
            $MaxLimit_len = 88-strlen($udcn);
            $Long_MaxLimit_len = 2000-strlen($udcn);
          } else if ($polltype==1) {// 회신 설문 
            $MaxLimit_len = 69;     // http://mms.ac/123456
            $Long_MaxLimit_len = 1982;  
            $ATTACH_FILE = true;
          } else if ($polltype==2) { // 미 회신 설문 
            $MaxLimit_len = 69;     
            $Long_MaxLimit_len = 1982;  
            $ATTACH_FILE = true;
          } else {
            $MaxLimit_len = 90;
            $Long_MaxLimit_len = 2000;  
            $ATTACH_FILE = false;
            $polltype = -1;
          }                    
} else {
    $polltype = -1;    
    $MaxLimit_len = 90;        
    $Long_MaxLimit_len = 2000;  
    $ATTACH_FILE = false;
}

$Mb_7 = json_decode($member['mb_7']);
if ($Mb_7->{'LMS'} == '1'){
          $LMS_flag = true;  
          if (strlen(iconv('UTF-8','CP949',$stitle)) > $MaxLimit_len){
                $init_chkLen = $Long_MaxLimit_len;
          } else {
                $init_chkLen = $MaxLimit_len;
          }
} else {
    $LMS_flag = false;
    $init_chkLen = $MaxLimit_len;
}

$comp_rph1 = ($member['mb_tel'] == '') ? $member['mb_hp'] : $member['mb_tel'];
$rp_phnum = '';
function Set_relpy_number($a_num){
    $phlen = mb_strlen($a_num, 'UTF-8');
    $rtnNum = '';
    for ($i = 0; $i < $phlen; ++$i) {
           $compchar = mb_substr($a_num,$i,1,'UTF-8');
           if ($compchar == '0') {
           } else if ($compchar == '1') {
           } else if ($compchar == '2') {
           } else if ($compchar == '3') {
           } else if ($compchar == '4') {
           } else if ($compchar == '5') {
           } else if ($compchar == '6') {
           } else if ($compchar == '7') {
           } else if ($compchar == '8') {
           } else if ($compchar == '9') {
           } else { continue;}
           $rtnNum .= $compchar;
    }
    $phlen = mb_strlen($rtnNum, 'UTF-8');
    if ($phlen < 8) {
        $rtnNum = '';
    } else if ($phlen == 8) {
        $rtnNum = mb_substr($rtnNum,0,4,'UTF-8').'-'.mb_substr($rtnNum,4,4,'UTF-8');
    } else {
        $pre_rnum = mb_substr($rtnNum,0,2,'UTF-8');
        if($pre_rnum == '02'){
            if ($phlen == 9) { // 021231234                    
                    $rtnNum = $pre_rnum.'-'.mb_substr($rtnNum,2,3,'UTF-8').'-'.mb_substr($rtnNum,5,4,'UTF-8');
            }else{
                    $rtnNum = $pre_rnum.'-'.mb_substr($rtnNum,2,4,'UTF-8').'-'.mb_substr($rtnNum,6,4,'UTF-8');
            }
        } else {
            $pre_rnum = mb_substr($rtnNum,0,3,'UTF-8');            
            if ($phlen == 10) { // 0311231234                    
                    $rtnNum = $pre_rnum.'-'.mb_substr($rtnNum,3,3,'UTF-8').'-'.mb_substr($rtnNum,6,4,'UTF-8');
            }else{
                    $rtnNum = $pre_rnum.'-'.mb_substr($rtnNum,3,4,'UTF-8').'-'.mb_substr($rtnNum,7,4,'UTF-8');
            }            

        }        
    }
    return $rtnNum;
}
  $comp_rph2 = Set_relpy_number($comp_rph1);

  $reply_arr = array();
  $reply_qry_txt = "select ph_phone from sms5_phone_identity where ph_mbno = '{$member['mb_no']}' and ph_identity=1 and ph_gubn=1 order by ph_phone;";
  $reply_qry = sql_query($reply_qry_txt);
  while ($reply_row = sql_fetch_array($reply_qry)){
    $reply_row_num = $reply_row['ph_phone'];
    if ($reply_row_num == '') { continue;}
    $dup_reply_flag = false;
    for ($ii=0;$ii<count($reply_arr);$ii++){        
            if  ($reply_arr[$ii] == $reply_row_num) {
                $dup_reply_flag = true;
                break;
            }            
    }
    if ($dup_reply_flag == false) {
        array_push($reply_arr, $reply_row_num);
        if ($comp_rph2 == $reply_row_num){ $rp_phnum = $reply_row_num;}
    }
  }
  $reply_size = count($reply_arr);
  if ($reply_size > 0){
    if ($rp_phnum == '') {
        $rp_phnum = $reply_arr[0];
    }
  }
  if($reply_size> 7) {$reply_size = 7;}
?>
<form name="form_sms" id="form_sms" method="post" action="/service/sms_write_send.php" onsubmit="return sms5_chk_send(this);"  >
<div class = "svc_box">
        <input type="hidden" name="send_list" value="">            
        <input type="hidden" name="udoc" id="udoc" value="<?=$udoc?>">    
        <input type="hidden" name="udcn" id="udcn" value="<?=$udcn?>">    
        <input type="hidden" name="polltype" value="<?=$polltype?>">    
		<textarea name="wr_message" id="wr_message" class="txt_box" 
				 onkeyup="byte_check('wr_message', 'sms_bytes', 'udcn');" accesskey="m"><?=$stitle?></textarea>
		<div id="sms_byte"><span id="sms_bytes">0</span> / <span id="limt_bytes"><?=$init_chkLen?></span> byte</div>     
<?php
  if ($ATTACH_FILE == true) {
?>           
        <div id="attach_docs"><strong>첨부문서</strong> : <span name = "attach_doc" id="attach_doc"><?=$udcn?></span></div>
<?php } ?>   
   
         <div id="sms_btns">
                <input type="button" value="비우기"  class="btnW3" onclick="show_Input_charPan(3);" > 
          </div>
            <div id = "btn_smsSend" >
                        <input type="submit" value="전송" class="btn_submit">
                        <!-- <input type="submit" value="전송" onclick="send()"> -->
            </div>
</div>   
<table class="snsboard">
        <tr>
                <th style="width:70px">발신번호</th>
                <td><div id="reply_list_box" style="position:relative;">
                    <select name="reply_list" id="reply_list" size="<?=$reply_size?>" style="display:none;position:absolute;top:35px;width:152px;font-size:14px;"></select>
                </div><input type="text" name="wr_reply" value="<?php echo $rp_phnum?>" id="wr_reply" required title="발신번호" class="frm_input required" size="17" readonly="true" ></td>
                <td><img src="<?=G5_IMG_URL?>/icon_reply.gif" onclick="reply_mng()" class="replaybtn"></td>
        </tr>
        <tr>
                <th><p class="pb20">받는이름</p><p>번호</p></th>
                <td><input type="text" name="hp_name" id="hp_name" class="frm_input" size="11" maxlength="20" onkeypress="if(event.keyCode==13) document.getElementById('hp_number').focus();"><br>
                       <input type="text" name="hp_number" id="hp_number" class="frm_input" size="11" maxlength="20" onkeypress="if(event.keyCode==13) hp_add()">
                </td>
                <td><img src="<?=G5_IMG_URL?>/icon_plus.gif" onclick="hp_add()" class="plusbtn"></td>
        </tr>
        <tr>
                <th >목록<br>총<span id="sel_cnt">0</span>명</th>
                <td  colspan="2"><select name="hp_list" id="hp_list" size="14"></select>
                        <button type="button" class="write_floater write_floater_btn" onclick="hp_list_del()">선택삭제</button>
                        <button type="button" class="write_floater write_floater_btn" onclick="hp_list_all_del()">전체삭제</button>                                                                            
                </td>
        </tr>
        <tr>
                <th rowspan="2" >
                       <p>예약전송<p>
                        <input type="checkbox" name="wr_booking" id="wr_booking" onclick="booking(this.checked)">
                        <label for="wr_booking"><span class="sound_only">예약전송 </span>사용</label>   
                </th>
                <td colspan="2">
                            <select name="wr_by" id="wr_by" disabled>
                            <option value="<?php echo date('Y')?>"><?php echo date('Y')?></option>
                            <option value="<?php echo date('Y')+1?>"><?php echo date('Y')+1?></option>
                            </select>
                            <label for="wr_by">년</label><br>   
                            <select name="wr_bm" id="wr_bm" disabled>
                                <?php for ($i=1; $i<=12; $i++) { ?>
                                <option value="<?php echo sprintf("%02d",$i)?>"<?php echo get_selected(date('m'), $i); ?>><?php echo sprintf("%02d",$i)?></option>
                            <?php } ?>
                            </select>
                            <label for="wr_bm">월</label>
                            <select name="wr_bd" id="wr_bd" disabled>
                                <?php for ($i=1; $i<=31; $i++) { ?>
                                <option value="<?php echo sprintf("%02d",$i)?>"<?php echo get_selected(date('d'), $i); ?>><?php echo sprintf("%02d",$i)?></option>
                                <?php } ?>
                            </select>
                            <label for="wr_bd">일</label><br>
                                <select name="wr_bh" id="wr_bh" disabled>
                                <?php for ($i=0; $i<24; $i++) { ?>
                                <option value="<?php echo sprintf("%02d",$i)?>"<?php echo get_selected(date('H')+1, $i); ?>><?php echo sprintf("%02d",$i)?></option>
                                <?php } ?>
                            </select>
                            <label for="wr_bh">시</label>
                            <select name="wr_bi" id="wr_bi" disabled>
                                <?php for ($i=0; $i<=59; $i+=5) { ?>
                                <option value="<?php echo sprintf("%02d",$i)?>"><?php echo sprintf("%02d",$i)?></option>
                                <?php } ?>
                            </select>
                            <label for="wr_bi">분</label>                                                 
                </td>
        </tr>
</table>
</form>   
<script type="text/javascript"> 
<?php if (count($reply_arr) > 1) { 
?>    
    reply_list = document.getElementById('reply_list');
<?php     
   for($idx=0;$idx<count($reply_arr);$idx++){
        if ($reply_arr[$idx] == '') { continue; }
    ?>
        reply_num = '<?php echo $reply_arr[$idx]; ?>';
        reply_list.options[reply_list.length] = new Option(reply_num, reply_num);
        
<?php         
    }
?>
    reply_list.value = '<?php echo $rp_phnum; ?>';
        $("#wr_reply").hover(
          function () {
            $("#reply_list").css('display','block');
            $("#reply_list").focus();
          },
          function () {
          }
        );
        $("#reply_list").hover(
          function () {
            $("#reply_list").css('display','block');
          },
          function () {
            $("#reply_list").css('display','none');
          }
        );

        $('#reply_list').change(function(){ 
            var r_value = $(this).val();
            $('#wr_reply').attr('value',r_value);
            $("#reply_list").css('display','none');
            $('#hp_name').focus();
        });       
<?php } ?>

<?php if ($LMS_flag == true) {
        if (strlen(iconv('UTF-8','CP949',$stitle)) > $MaxLimit_len){
?>        
            scf = true;   
<?php  
        } else {
?>
            scf = false;   
<?php        
        }
    } ?>

function change_link(){

    stitle = $('#wr_message').text();
    var tlinkref = '';
   <?php  if ($ATTACH_FILE == true) {  ?>                   
            polltype     = '<?=$polltype?>';
            udoc = '<?=$udoc?>';
            udon    = '<?=$udcn?>';
            tlinkref = '&polltype='+polltype+'&udoc='+udoc+'&udcn='+udon+'&stitle='+stitle;
    <?php } ?>            
    if (stitle != '') {
        tlinkref = tlinkref +'&stitle='+stitle;
    }
    for (idx = 1; idx < 7;idx++){
        tref = "/serv.php?m1=4&m2="+idx+tlinkref;
        $('#sv_menu_'+idx).attr('href',tref);
    }    
}

function show_Input_charPan(whichPan){
    if (whichPan == 3) {    
        $("#wr_message").attr("value","");
        byte_check('wr_message', 'sms_bytes', 'udcn');
        $("#wr_message").focus();
    }    
}

function byte_check(wr_message, sms_bytes, udcn)
{
    var conts = document.getElementById(wr_message);
	var attach = document.getElementById(udcn);
    var bytes = document.getElementById(sms_bytes);
    var limitbytes = document.getElementById('limt_bytes');

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
	
	if (attach) {
		for (i=0; i<attach.value.length; i++)
		{
			ch = attach.value.charAt(i);
			if (escape(ch).length > 4) {
				cnt += 2;
			} else {
				cnt += 1;
			}
		}
	}
	
    bytes.innerHTML = cnt;    

<?php if ($LMS_flag == true) { ?>
      if (scf == true){
          if (cnt < <?=$MaxLimit_len?>) {
                scf = false;
          }          
    }

    if (scf == true){
        chkLen = <?=$Long_MaxLimit_len ?>;

    } else {
        chkLen = <?=$MaxLimit_len ?>;
    }
    limitbytes.innerHTML = chkLen; 
    if (cnt > chkLen)
    {
        exceed = cnt - chkLen;

        if (chkLen == <?=$MaxLimit_len ?>){
                if (confirm("sms(단문)전송용량(<?=$MaxLimit_len?>byte)를 초과하여\n\nlms(장문)으로 전환됩니다.\n\nlms 발송시 건당 20원의 요금이 추가로 청구됩니다")){
                    scf = true;
                    limitbytes.innerHTML = <?=$Long_MaxLimit_len ?>; 
                    if (cnt > <?=$Long_MaxLimit_len ?>) {
                            chkLen = <?=$Long_MaxLimit_len ?>;
                    }  else {
                        return;  
                    }                                       
                }
        } else {          
                alert('메시지 내용은 <?=$MaxLimit_len?>바이트를 넘을수 없습니다.\n\n작성하신 메세지 내용은 '+ exceed +'byte가 초과되었습니다.\n\n초과된 부분은 자동으로 삭제됩니다.');
        }        
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

            if (tcnt > chkLen) {
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
<?php  } else { ?>
    if (cnt > <?=$MaxLimit_len?>)
    {
        exceed = cnt - <?=$MaxLimit_len?>;
        alert('메시지 내용은 <?=$MaxLimit_len?>바이트를 넘을수 없습니다.\n\n작성하신 메세지 내용은 '+ exceed +'byte가 초과되었습니다.\n\n초과된 부분은 자동으로 삭제됩니다.');
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

            if (tcnt > <?=$MaxLimit_len?>) {
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
<?php  } ?>      
    change_link(); 
}  

function add(str) {
    var conts = document.getElementById('wr_message');
    var bytes = document.getElementById('sms_bytes');
    conts.focus();
    conts.value+=str;
    byte_check('wr_message', 'sms_bytes', 'udcn');
    return;
}
change_link();
</script>