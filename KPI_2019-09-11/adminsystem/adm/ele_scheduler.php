<?php
	include_once('../common.php');	
	
	function update_money_users($mb_id = null) {
		
		$ele_today = date("Y-m-d");										// 	오늘
		$ele_yesterday = date("Y-m-d",strtotime($ele_today.' -1 day')); //	어제 
		$ele_month_firstday = date("Y-m").'-01';						//	월초일 
		$ele_nextmonth_first_day = date("Y-m-d",strtotime($ele_month_firstday.' +1 month'));	// 래월초일
		$ele_month_endday      = date("Y-m-d",strtotime($ele_nextmonth_first_day.' -1 day'));	// 월말일 		
		$this_day = date("d", strtotime($ele_today));
		
		$sql = "select * from ele_money_mst;";    					
		$qry = sql_query($sql);
		while ($member_row = sql_fetch_array($qry))	
		{          
			$elem_id = $member_row['elem_id'];
			$sms_cur_count = $member_row['elem_crnt_cnt'];
			$sms_max_count = $member_row['elem_charge_first_count'];
			$sms_reserve_count = $member_row['elem_charge_last_count'];
			$cur_money = $member_row['elem_money'];
			if ($member_row['elem_type'] == '001') {
				// 선불충전제
				if ($mb_id == $elem_id)	{
					$new_update_date = $ele_today;							
					$sql = "update ele_money_mst set elem_start_date='{$new_update_date}' where elem_id = {$mb_id};";    									
					sql_query($sql);											
				}		
			}
			else if ($member_row['elem_type'] == '002') {
				// 선불정액제									
				if ($member_row['elem_start_date'] < $ele_month_firstday) {						
					if ($sms_max_count > 0)	{
						$new_update_date = $ele_month_firstday;							
						$sql = "update ele_money_mst set elem_start_date='{$new_update_date}', elem_crnt_cnt='{$sms_max_count}' where elem_id = {$elem_id};";    								

						if ($mb_id=='1') {
							echo $sql.PHP_EOL;											
						}
				
						sql_query($sql);											
					}		
				}					
			}
			else if ($member_row['elem_type'] == '003') {
				// 후불정산제
				if ($mb_id == $elem_id)	{
					$new_update_date = $ele_today;							
					$sql = "update ele_money_mst set elem_start_date='{$new_update_date}' where elem_id = {$mb_id};";    									
					sql_query($sql);											
				}	
			}					
		}	
		
	}		
?>
