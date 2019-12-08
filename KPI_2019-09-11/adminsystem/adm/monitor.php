<?php
$sub_menu = "700900";
include_once('./_common.php');
auth_check($auth[$sub_menu], 'r');
$g5['title'] = '모니터링';
include_once ('./admin.head.php');
?>
<div id="membePan"></div>    
<div id="sendPan"></div>    
<?php
include_once ('./admin.tail.php');
?>
<script type="text/javascript">
function view_page(wurl){
    window.open(wurl);
}
$(function() {

    auto_list = function(){        
        $.ajax({
            url: "<?=G5_URL?>/adm/monitor/newMember.php",
            cache:false,
            timeout : 30000,
            dataType:'html',
            type:'get',
            success: function(data) {  
               if (data == 'not') {                    
               } else {
                  shtml = data;
                  $('#membePan').html(shtml);                  
               }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //alert(xhr.status);
                //alert(thrownError);
            }
        });
    };    
    sms_list = function(){        
        $.ajax({
            url: "<?=G5_URL?>/adm/monitor/smsSend.php",
            cache:false,
            timeout : 30000,
            dataType:'html',
            type:'get',
            success: function(data) {  
               if (data == 'not') {                    
               } else {
                  shtml = data;
                  $('#sendPan').html(shtml);                  
               }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //alert(xhr.status);
                //alert(thrownError);
            }
        });
    };        
    auto_list();
    sms_list();
    interval = setInterval(auto_list,5000);                  
    interval = setInterval(sms_list,5000);                  
});   

</script>