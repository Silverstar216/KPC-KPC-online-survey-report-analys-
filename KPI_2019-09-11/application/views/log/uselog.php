<?php
/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 2/29/2016
 * Time: 9:47 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
$site_url = site_url();
$total_price = 0;
?>
<div class="container container-bg">
	<div id="content">
		<div id="contents">
			<div class="sub_con">
				<div class="sub_title1"><img src="<?=$site_url?>images/icon_title.png">사용내역
                    <?php if(get_session_user_id() != '' && $current_money != null) {
                              if($current_money['charge_type'] == '0') {//선불충전식인 경우
                        ?>
                                    <a style = "font-size:16px;float:right;color:#1970ce">회원님의 남은 금액은 <?=$current_money == null? 0:number_format($current_money['current_amount'])?>원입니다 </a>
                          <?php }else{ ?>
                                    <a style = "font-size:16px;float:right;color:#1970ce">회원님의 남은건수는 <?=$current_money == null? 0:$current_money['current_count']?>건입니다 </a>
                          <?php } ?>
                    <?php } ?>
                </div>
				<div class="search">
					<ul>
						<li>검색기간 : <input type="text" id="uselog_start_date" class="form-control input-inline uselog_datepicker" value="<?=$start_date?>"style="padding:2px 6px">
						 ~ 
						 <input type="text" id="uselog_end_date" class="form-control input-inline uselog_datepicker" value="<?=$end_date?>"style="padding:2px 6px"></li>
						 <li><button class="btn btn-default" style="padding:2px 10px" id="uselog_search">검색</button></li>
					</ul>
				</div>
				<table class="search_t2">
					<tr>
						<th style="width:20%">구분</th>
						<th style="width:20%">상세</th>
						<th style="width:10%">단가</th>
						<th style="width:10%">전송</th>
						<th style="width:10%">성공</th>
						<th style="width:10%">실패</th>
						<th style="width:10%">대기</th>
						<th style="width:10%">금액</th>
					</tr>
                    <?php if(!empty($prices)){ ?>
					<tr>
						<td rowspan="4">SMS</td>
						<td>일반문자</td>
						<td><?=$prices[0]['sms_g_simple']?>원</td>
						<td><?=$result[0]['totalCount']+$wait_result[0]['sm']?></td>
                        <td><?=$result[0]['successCount']?></td>
                        <td><?=$result[0]['failureCount']?></td>
                        <td><?=$wait_result[0]?></td>
						<td><?php $total_price = ($result[0]['totalCount']+$wait_result[0]) * $prices[0]['sms_g_simple']-$result[0]['failureCount'] * $prices[0]['sms_g_simple'];
						        echo ($result[0]['totalCount']+$wait_result[0]) * $prices[0]['sms_g_simple']-$result[0]['failureCount'] * $prices[0]['sms_g_simple'];
						    ?>
                        </td>
					</tr>
					<tr>
						<td>문서포함문자</td>
						<td><?=$prices[0]['sms_g_attach']?>원</td>
                        <td><?=$result[1]['totalCount']+$wait_result[0]['smd']?></td>
                        <td><?=$result[1]['successCount']?></td>
                        <td><?=$result[1]['failureCount']?></td>
                        <td><?=$wait_result[1]?></td>
                        <td><?php $total_price +=($result[1]['totalCount']+$wait_result[1]) * $prices[0]['sms_g_attach']-$result[1]['failureCount'] * $prices[0]['sms_g_attach'];
                                echo ($result[1]['totalCount']+$wait_result[1]) * $prices[0]['sms_g_attach']-$result[1]['failureCount'] * $prices[0]['sms_g_attach'];
                            ?></td>
					</tr>
					<tr>
						<td>단순설문</td>
						<td><?=$prices[0]['sms_sur_simple']?>원</td>
                        <td><?=$result[2]['totalCount']+$wait_result[0]['sv']?></td>
                        <td><?=$result[2]['successCount']?></td>
                        <td><?=$result[2]['failureCount']?></td>
                        <td><?=$wait_result[2]?></td>
                        <td><?php $total_price +=($result[2]['totalCount']+$wait_result[2]) * $prices[0]['sms_sur_simple']-$result[2]['failureCount'] * $prices[0]['sms_sur_simple'];
                                echo ($result[2]['totalCount']+$wait_result[2]) * $prices[0]['sms_sur_simple']-$result[2]['failureCount'] * $prices[0]['sms_sur_simple'];
                            ?></td>
					</tr>
					<tr>
						<td>문서포함설문</td>
						<td><?=$prices[0]['sms_sur_attach']?>원</td>
                        <td><?=$result[3]['totalCount']+$wait_result[0]['svd']?></td>
                        <td><?=$result[3]['successCount']?></td>
                        <td><?=$result[3]['failureCount']?></td>
                        <td><?=$wait_result[3]?></td>
                        <td><?php $total_price +=($result[3]['totalCount']+$wait_result[3]) * $prices[0]['sms_sur_attach']-$result[3]['failureCount'] * $prices[0]['sms_sur_attach'];
                                echo ($result[3]['totalCount']+$wait_result[3]) * $prices[0]['sms_sur_attach']-$result[3]['failureCount'] * $prices[0]['sms_sur_attach'];
                            ?></td>
					</tr>
					<tr>
						<td rowspan="4">LMS</td>
						<td>일반문자
                        <td><?=$prices[0]['lms_g_simple']?>원</td>
                        <td><?=$result[4]['totalCount']+$wait_result[0]['lm']?></td>
                        <td><?=$result[4]['successCount']?></td>
                        <td><?=$result[4]['failureCount']?></td>
                        <td><?=$wait_result[4]?></td>
                        <td><?php $total_price +=($result[4]['totalCount']+$wait_result[4]) * $prices[0]['lms_g_simple']-$result[4]['failureCount'] * $prices[0]['lms_g_simple'];
                                echo ($result[4]['totalCount']+$wait_result[4]) * $prices[0]['lms_g_simple']-$result[4]['failureCount'] * $prices[0]['lms_g_simple'];
                            ?></td>
					</tr>
					<tr>
						<td>문서포함문자</td>
						<td><?=$prices[0]['lms_g_attach']?>원</td>
                        <td><?=$result[5]['totalCount']+$wait_result[0]['lmd']?></td>
                        <td><?=$result[5]['successCount']?></td>
                        <td><?=$result[5]['failureCount']?></td>
                        <td><?=$wait_result[5]?></td>
                        <td><?php $total_price +=($result[5]['totalCount']+$wait_result[5]) * $prices[0]['lms_g_attach']-$result[5]['failureCount'] * $prices[0]['lms_g_attach'];
                            echo ($result[5]['totalCount']+$wait_result[5]) * $prices[0]['lms_g_attach']-$result[5]['failureCount'] * $prices[0]['lms_g_attach'];
                            ?></td>
					</tr>
					<tr>
						<td>단순설문</td>
						<td><?=$prices[0]['lms_sur_simple']?>원</td>
                        <td><?=$result[6]['totalCount']+$wait_result[0]['lv']?></td>
                        <td><?=$result[6]['successCount']?></td>
                        <td><?=$result[6]['failureCount']?></td>
                        <td><?=$wait_result[6]?></td>
                        <td><?php $total_price +=($result[6]['totalCount']+$wait_result[6]) * $prices[0]['lms_sur_simple']-$result[6]['failureCount'] * $prices[0]['lms_sur_simple'];
                                echo ($result[6]['totalCount']+$wait_result[6]) * $prices[0]['lms_sur_simple']-$result[6]['failureCount'] * $prices[0]['lms_sur_simple'];
                            ?></td>
					</tr>
					<tr>
						<td>문서포함설문</td>
						<td><?=$prices[0]['lms_sur_attach']?>원</td>
                        <td><?=$result[7]['totalCount']+$wait_result[0]['lvd']?></td>
                        <td><?=$result[7]['successCount']?></td>
                        <td><?=$result[7]['failureCount']?></td>
                        <td><?=$wait_result[7]?></td>
                        <td><?php $total_price +=($result[7]['totalCount']+$wait_result[7]) * $prices[0]['lms_sur_attach']-$result[7]['failureCount'] * $prices[0]['lms_sur_attach'];
                                echo ($result[7]['totalCount']+$wait_result[7]) * $prices[0]['lms_sur_attach']-$result[7]['failureCount'] * $prices[0]['lms_sur_attach'];
                            ?></td>
					</tr>
					<tr>
						<td colspan="7">합계금액
							<?=$total_price; ?>
                            <a style = "color:red;font-size:12px;">(VAT별도)</a></td>
						<td></td>
					</tr>
                    <?php } ?>
				</table>

				<div class="sub_img">
					<img src="<?=$site_url?>images/bg/block.png">
				</div>
			</div>
		</div>
	</div>
</div>
