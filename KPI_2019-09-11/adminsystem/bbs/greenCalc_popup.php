<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
?>
<!-- 팝업레이어 시작 { -->
<div id="green_pop">
    <h2>스쿨뉴스</h2>
    <div id="hd_pops_green" class="hd_pops" style="top:430px;left:50px;display:none">
        <div class="hd_pops_con" style="width:876px;height:244px">
        <table width="100%" border="1px solid black;" cellspacing="0" cellpadding="0">
        <tr>
            <td height=165 style="padding:0 40px;">
                <table width="100%" border="1px solid black;" cellspacing="0" cellpadding="0">
                <tr>
                    <td height="40" colspan="7"><input id="gr_stu" type="text" style="padding:5px;" /><img src="../img/txt01.png"/></td>            
                </tr>
                <tr>
                    <td height="40"><img src="../img/txt02.png"/></td>
                    <td id="gr_paper"style="color: green;font-size:20px;font-weight: bold;text-align:right;"></td>
                    <td><img src="../img/txt03.png"/></td>
                     <td id="gr_water" style="color: green;font-size:20px;font-weight: bold;text-align:right;"></td>
                     <td><img src="../img/txt04.png"/></td>
                     <td id="gr_carbon" style="color: green;font-size:20px;font-weight: bold;text-align:right;"></td>
                     <td><img src="../img/txt05.png"/></td>
                </tr>
                </table>                
            </td>
        </tr>
        <tr>
        <td><img src="../img/txt06.png" /></td>
        </tr>
        </table>
        </div>
        <div class="hd_pops_footer">
            <button id="hd_green_calc">계산하기</button>
            <button class="hd_pops_close hd_pops_green">닫기</button>
        </div>
    </div>        
</div>
<script>
function green_comma(n) {
    var parts=n.toString().split(".");
    return parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (parts[1] ? "." + parts[1] : "");    
}
$(function() {
    $('.hd_pops_close').click(function() {
        $('#hd_pops_green').css('display','none');
    });
    $('#hd_green_calc').click(function() {
        su             = $('#gr_stu').val();
        paper_c   = 180*su;
        water_c    = paper_c/100;      
        carbon_c  =  paper_c*2.88/1000; 
        $('#gr_paper').text(green_comma(paper_c));
        $('#gr_water').text(green_comma(water_c));
        $('#gr_carbon').text(green_comma(carbon_c));
    });    
});
</script>
<!-- } 팝업레이어 끝 -->