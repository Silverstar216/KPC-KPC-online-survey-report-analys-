<?php
  if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가   
?>
<div class = "svc_box2">
        <input type="hidden" name="send_list" value="">    
<textarea name="fo_content"  id="wr_message" class="txt_box" 
         onkeyup="byte_check('wr_message', 'sms_bytes');" accesskey="m"><?php echo $write['fo_content']?></textarea>
        <div id="sms_byte"><span id="sms_bytes">0</span> / 80 byte</div>     
<?php
  if (defined('_ATTACH_FILE_')) {
?>           
        <div id="attach_docs"><strong>첨부문서</strong> : <span name = "attach_doc" id="attach_doc">http://mms.ac/LETTER</span></div>
<?php } ?>   
         <div id="sms_btns">
         <input type="button" value="비우기"  class="btnW3" onclick="show_Input_charPan(3);" >
          </div>   