<?php
// 이 파일은 새로운 파일 생성시 반드시 포함되어야 함
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
    $ss_name = 'ss_view_datamedia_9';
    if (!get_session($ss_name))
    { 
        set_session($ss_name, TRUE);  
    }
?>
    <div class="contentwrap"> 
    <div class="titlem1">
        <em>요금안내</em>      
		 <div class="navgroup">		
				 <p>Home <span class="rt">&gt;</span> 요금안내</p>
		</div>     
     </div>  
     <div class="m8con">
       
       <div class="tbl_price">
        <table>
        <caption>요금안내</caption>
          <colgroup>         
          <col width="24%" />
          <col width="10%" />                                     
          <col width="*" />
          </colgroup>  
        <thead>
        <tr>
            <th scope="col" width="60">구분</th>
            <th scope="col">단가</th>
            <th scope="col" width="100">비고</th>
            </tr>
        </thead>
        <tbody>
            <tr class="">
            <td class="td_num bggray"><p>SMS( 일반 단문자 ) </p></td>
            <td class="td_subject"> <p>15원 </p></td>            
            <td class="td_date brnone"><p>Short Message Service (80byte) </p>
              <p>한글로 40 字이내를 전송합니다 . </p></td>
            </tr>
            <tr class="">
            <td class="td_num bggray"><p>LMS( 일반 장문자 ) </p></td>
            <td class="td_subject"> <p>35원 </p></td>            
            <td class="td_date brnone"><p>Long Message Service (2,000byte) </p>
              <p>한글로 1,000 字이내를 전송합니다 . </p></td>
            </tr>
            <tr class="">
            <td class="td_num bggray"><p>SMS+ 가정통신문 첨부 </p></td>
            <td class="td_subject"> <p>50원 </p></td>            
            <td rowspan="2" class="td_date brnone"><p>SMS 또는 LMS 에 4M byte 이내의 가정통신문을 </p>
              <p>첨부할 수 있으며 , 회신 또는 설문조사가 가능합니다 . </p></td>
            </tr>
            <tr class="">
              <td class="td_num bggray"><p>LMS+ 가정통신문 첨부 </p></td>
              <td class="td_subject"><p>70원 </p></td>
              </tr>
                
        </tbody>
        </table>	         
    </div>
    </div>
    <div class="pricetxt">
      <ol>
		<li>부가가치세 별도금액입니다.</li>
		<li>매월 말일 기준으로 청구합니다.</li>
		<li>사용계약서를 작성하여 보내 주시면 즉시 사용이 가능합니다. </li>
        </ol>  
    </div>	 
    <div class="m9btn">
 <!--
       <p><a href="#"><input type="button" value="사용계약서 다운받기"  class="btnT2"></a></p>       
-->
     <ul>
      <li>
        <a class="view_file_download" href="/bbs/download.php?bo_table=datamedia&amp;wr_id=9&amp;no=0">
                 <input type="button" value="스쿨뉴스 서비스 신청서 다운받기" class="btnT2"><br>
                 <strong>신청서스쿨뉴스서비스.hwp</strong>(16.0K)</a>
      </li>
     </ul>

    </div>  
  </div>