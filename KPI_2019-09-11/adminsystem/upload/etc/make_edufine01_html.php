<?php
  if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가   
	if ($edu_row){
		$bill_data   = json_decode($edu_row,true);			
?>
<style type="text/css">
#bill_outbox {width:100%;border: 2px solid black;border-collapse: collapse;}
#bill_outbox td{padding: 5px;}
#bill_title{width:100%;background-color: #e7e1cb;text-align: center;font-size: 1.1em;font-weight: bold;}
#bill_info{width:100%; border: 1px solid black;border-collapse: collapse;}
#bill_info_t{border: 1px solid black;border-collapse: collapse; text-align: center;background-color:#e7e1cb;width:50%;padding: 10px 0;font-size: 1.2em;font-weight: bold;}
#bill_info_n{border: 1px solid black;border-collapse: collapse; background-color:#e5e500; text-align: center;padding: 10px 0;font-size: 1.2em;font-weight: bold;}
#bill_body{width:100%;border: 1px solid black;border-collapse: collapse; }
#bill_body td{border: 1px solid black;border-collapse: collapse;}
.bill_head{background-color:#e9e9e9; text-align: center;font-size: 1.1em;font-weight: bold;}
.bill_subtitle{width:40%;padding: 5px 0; text-align: center;font-size: 1.0em;font-weight: bold;} 
.bill_money{width:25%;padding: 5px 10px 5px 5px;text-align: right;font-size: 1.0em;font-weight: bold;} 
.bill_bigo{padding: 5px 0;text-align: center;} 
.bill_sum{background:#f5dca8;}
.bill_money_sum{background:#e5e500;}
.bill_sum0 {background:#fff}
.bill_sum1 {background:#f2f5f9}
</style>
<table id="bill_outbox">
	<tr>
		<td>
			<table id='bill_title'>
				<tr><td><?= $edu_msg ?></td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table id='bill_info'>
				<tr><td id='bill_info_t'><?=$bill_data['g']?>학년 <?=$bill_data['c']?>반 <?=$bill_data['i']?>번</td><td id='bill_info_n'><?php echo urldecode($bill_data['n']); ?></td></tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table id='bill_body'>
				<tr><td class='bill_head'>구분</td><td class='bill_head'>금액</td><td class='bill_head'>비고</td></tr>
<?php
		$idx = 0;
		foreach ($bill_data['l'] as $skey => $bill_svalue) {
			$rg_title = urldecode($bill_svalue['t']);
			$rg_money = $bill_svalue['m'];	
			if ($rg_title == 's'){
				$rg_title = '합&nbsp;&nbsp;&nbsp;&nbsp;계';
				$rg_bigo = '&nbsp;';
				$td1class = 'bill_subtitle bill_sum';
				$td2class = 'bill_money bill_money_sum';
				$td3class = 'bill_sum';
			} else {
				$rg_bigo = urldecode($bill_svalue['s']);
				$sgclass = ' bill_sum'.($idx%2);				
				$td1class = 'bill_subtitle'.$sgclass;
				$td2class = 'bill_money'.$sgclass;
				$td3class = 'bill_bigo'.$sgclass;
			}
			$idx++;
?>				
				<tr><td class='<?=$td1class?>'><?=$rg_title?></td><td class='<?=$td2class?>'><?=$rg_money?></td><td class='<?=$td3class?>'><?=$rg_bigo?></td></tr>
<?php		}// end foreach		?>				
			</table>
		</td>
	</tr>	
</table>
<?php 	} // if end ?>