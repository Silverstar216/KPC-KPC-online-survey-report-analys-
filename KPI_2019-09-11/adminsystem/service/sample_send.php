<?php
    if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 
?>
<div class="messagbox mb30">
    <form name="sample_send" id="sample_send" onsubmit="return false;" >  
      <input  name="post" id="mypost" type="hidden" value=''>  
    <br>
    <div class="banner1">휴대폰 번호를 입력 후 문자받기를 누르시면 가정통신문 Sample을 받아볼 수 있습니다.</div>
    <div class="banner2"><span>받을번호</span> <input  name="myphone" id="myphone" type="text" size="15" maxlength="13" class="bn"></div>
    <br>
    <div class="pl50"> <input type="button" value="문자받기" id="sample_btn" class="btnT3" onclick="sample_send_func()"> </div>
    <br>
    </form>    
</div>          
<script type="text/javascript">
var dup_send_flag = false;
function sample_send_func(){


    var phoneFilterList = [['031',7],['032',7],['033',7],['041',7],['042',7],['042',7],['043',7],['044',7],
        ['051',7],['052',7],['053',7],['054',7],['055',7],
        ['061',7],['062',7],['063',7],['064',7],
        //이동통신전화번호
        ['010',8],['011',8],['016',8],['017',8],['018',8],['019',8],
        //대표전화번호 예) 15
        ['15',6],['16',6],['18',6],
        //공통서비스식별번호
        ['020',8],['030',8],['040',8],['050',8],['060',8],['070',8],['080',8],['090',8]
    ];
    var checkResult = false;
    $.each(phoneFilterList, function (index, value) {
        if($('#myphone').attr('value').substr(0,value[0].length) == value[0] && $('#myphone').attr('value').length == value[0].length + Number(value[1])){
            checkResult = true;
        };
    });
    if(!checkResult){
        swal({title: '', text: "핸드폰 번호형식이 올바르지 않습니다.\n(예:011YYYZZZZ, 010ABYYYYYY)",
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {});
        return;
    }
  if (dup_send_flag == true) {
    alert('전송중입니다!!!');
    return false;
  }
  var tp = $('#myphone').attr('value');
  var pt = $('#mypost').attr('value');
  if (!chk_ph(tp)) {return false;}
  var params = { tp : tp, pt : pt };        
  $.ajax({
  url: "<?=G5_URL?>/service/sample_write.php",
  cache:false,
  timeout : 30000,
  data : params,
  dataType:'html',
  type:'post',
  success: function(data) {  
    dup_send_flag = false;
    alert(data);
      if (data == 'not') { 

      } else {
          
      }
  },
  error: function (xhr, ajaxOptions, thrownError) {
        dup_send_flag = false;    
      }
  });  
}

function chk_ph(tp){
      var regExp = /^(01[016789]{1})-?[0-9]{3,4}-?[0-9]{4}$/;
      if(!tp) {
        alert("전화번호를 입력하세요.");
        $('#myphone').focus();
        return false;
    }
    else if (!regExp.test(tp)) {
        alert("잘못된 전화번호입니다. 입력 예) 01X-XXXX-XXXX 또는 01XXXXXXXXX");
        $('#myphone').focus();
        $('#myphone').select();
        return false
    }
     return true;
}
</script>