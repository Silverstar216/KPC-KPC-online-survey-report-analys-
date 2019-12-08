<?php
// 이 파일은 새로운 파일 생성시 반드시 포함되어야 함
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>
  <div class="contentwrap"> 
    <div class="titlem1">
        <em>친환경서비스</em>      
		 <div class="navgroup">		
				 <p>Home <span class="rt">&gt;</span> 스쿨뉴스소개 <span class="rt">&gt;</span> 친환경서비스</p>
		</div>     
    </div>  

 
 
 <!--  디자이너 
     <div class="m2con">
        <span><input type="text"  class="blanktxt"></span>
        <p>명의 학생에게 스쿨뉴스를 사용해서 가정통신문을 보내면 년간</p>
         <p><input type="text" class="linebox">매의 종이와</p>
         <p><input type="text" class="linebox">ton의 물과</p>
         <p><input type="text" class="linebox">kg의 탄소를 절감할 수 있습니다.</p>
         <p>
         <input type="button" value="계산하기"  class="btnT1">  
         </p>
    </div>
-->  

    <div class="m2con">
     <table width="140%" border="1px solid black;" cellspacing="0" cellpadding="0">
        <tr>
            <td height=250 style="padding:0 40px;">
                <table width="100%" border="1px solid black;" cellspacing="0" cellpadding="0">
                <tr>
                    <td height="70" colspan="7">
                   	<input id="gr_stu" type="text" style="padding:7px;" /><span>명에게 스쿨뉴스를 사용해서 가정통신문을 보내면</span>
<!--                   	<img src="../img/txt01.png"/> -->
                    </td>            
                </tr>
                <tr>
                    <td height="24"><img src="../img/txt02.png"/></td>
                </tr>
                <tr>
                    <td height="26" id="gr_paper" style="color: green;font-size:20px;font-weight: bold;text-align:right;"></td>
                    <td><img src="../img/txt03.png"/></td>
                </tr>
                <tr>
                     <td height="26" id="gr_water" style="color: green;font-size:20px;font-weight: bold;text-align:right;"></td>
                     <td><img src="../img/txt04.png"/></td>
                </tr>
                <tr>
                     <td height="26" id="gr_carbon" style="color: green;font-size:20px;font-weight: bold;text-align:right;"></td>
                     <td><img src="../img/txt05.png"/></td>
                </tr>
                </table>                
            </td>
        </tr>
        </table>

         <p>  <button id="hd_green_calc">계산하기</button> </p>
    </div>
   </div>
<!--       -->
     <div class="m1con">
        <ul class="img2">        
         <li>(산출근거) 1장의 종이를 만들기 위해서는 10리터의 물과 2.88gram의 탄소가 배출됨</li>
         <li>종이 = 학생수 x 15매 x 12개월 |  물 = 종이x 10/1000(ton)  |  탄소 = 종이 x 2.88 / 1,000(kg)</li>
        </ul>
    </div>           
<!--     <div><input type="button" value="닫기"  class="mr20 btnT1">  </div> -->

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
