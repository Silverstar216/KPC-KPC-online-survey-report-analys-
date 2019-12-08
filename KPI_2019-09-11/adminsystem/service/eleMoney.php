<?php 
if (!defined('_GNUBOARD_')) exit;
class eleMoney {
	var $rtnMessage;
	var $CurrGubn;
	var $CurrType;
	var $CurrCnt;
	var $CurrMoney;
	var $remainCnt;
	var $remainMoney;
	var $useCnt;
	var $useMoney;
	var $sms_user_price;
	var $lms_user_price;
	var $smsdoc_user_price;
	var $lmsdoc_user_price;
	var $cv_user_price;
	var $Curr_Bonus_cv_cnt;
	
	
	function Get_CurrGubn(){
		return $this->CurrGubn;
	}	
	function Get_CurrCnt(){
		return $this->CurrCnt;
	}
	function Get_CurrMoney(){
		return $this->CurrMoney;
	}
	function Get_remainCnt(){
		return $this->remainCnt;
	}
	function Get_remainMoney(){
		return $this->remainMoney;
	}	
	function Get_error_msg(){
		return $this->rtnMessage;
	}
	function Init() {
		$this->rtnMessage = '';
		$this->CurrType = '';
		$this->CurrCnt = 0;
		$this->CurrMoney = 0;
		$this->remainCnt = 0;
		$this->remainMoney = 0;
		$this->useCnt = 0;
		$this->useMoney = 0;
		$this->sms_user_price = 0;
		$this->lms_user_price = 0;
		$this->smsdoc_user_price = 0;
		$this->lmsdoc_user_price = 0;
		$this->cv_user_price = 0;
		$this->Curr_Bonus_cv_cnt = 0;
	}	
	
	function is_possible_use($lmsFlag, $docFlag, $sendCnt, $cvCnt, $mb_no)
	{
		// $lmsFlag : flag for checking sms/lmsFlag
		// $docFlag : flag for checking attached document.	
        $this->Init();
	    if ($sendCnt > 0) {
	    	if ($sendCnt == $cvCnt) {
	    		if ($cvCnt > 0)	 {
	    			$cvCnt = $cvCnt-1;	
	    		}
	    	}
	    }
		
	    if (($sendCnt+$cvCnt) <= 0 ) { return true;}
		
	    $sql = "select a.*,".
	    "(SELECT min(elpu_gubn) FROM ele_price_user where elpu_type = elem_type and elpu_stat = 'm') as elem_gubn ".
	    " from ele_money_mst a ".
	    "where elem_id = '{$mb_no}' and elem_stat = 'Y' ";
			
	    $row = sql_fetch($sql);
	    if ($row) {
	        $elem_type = $row['elem_type'];
	        $this->CurrType = $elem_type;
	        $elem_gubn = $row['elem_gubn'];
	        $this->CurrGubn = $elem_gubn;	        
	        $elem_crnt_cnt = $row['elem_crnt_cnt'];
	        $this->CurrCnt = $elem_crnt_cnt;
	        $elem_crnt_money = $row['elem_crnt_money'];
	        $this->CurrMoney = $elem_crnt_money;
	        $elem_sms_user_price   = $row['elem_sms_user_price'];  
	        $this->sms_user_price = $elem_sms_user_price; 
	        $elem_lms_user_price   = $row['elem_lms_user_price'];  
	        $this->lms_user_price = $elem_lms_user_price; 
	        $elem_smsdoc_user_price   = $row['elem_smsdoc_user_price'];  
	        $this->smsdoc_user_price = $elem_smsdoc_user_price; 
	        $elem_lmsdoc_user_price   = $row['elem_lmsdoc_user_price'];  
	        $this->lmsdoc_user_price = $elem_lmsdoc_user_price; 
	        $elem_cv_user_price = $row['elem_cv_user_price'];
	        $this->cv_user_price = $elem_cv_user_price; 
			
	        if ($elem_type == '001') {	
				// 요금제가 입금제방식일 경우 잔여금액을 건수로 변환시켜 비교, 문서변환은 무료이다.				      				
				$need_money = $sendCnt;
				if ($lmsFlag == 'LMS') {
					if ($docFlag == '')
						$need_money *= $elem_lms_user_price;
					else
						$need_money *= $elem_lmsdoc_user_price;
				}
				else {
					if ($docFlag == '')
						$need_money *= $elem_sms_user_price;
					else
						$need_money *= $elem_smsdoc_user_price;					
				}
								
				if ($need_money > $elem_crnt_money){
					$OverCnt = $need_money - $elem_crnt_money;
					$this->rtnMessage = '전송에 요구되는 금액이 '.$OverCnt .'원 초과되었습니다!.';
					return false;
				}
				$this->useMoney = $need_money;				
	        } else if ($elem_type == '002') {
				// 요금제가 선불제방식일 경우 잔여건수로 비교한다.
				$need_count = $sendCnt;
				if ($lmsFlag == 'LMS') {
					if ($docFlag == '')
						$need_count *= 2.5;
					else
						$need_count *= 5;
				}
								
				if ($need_count > $elem_crnt_cnt){ 
					  $OverCnt = $need_count - $elem_crnt_cnt;
					  $this->rtnMessage = '전송가능 건수가 '.$OverCnt .'건 초과되었습니다!!!.';
					  return false;
				}					
				$this->useCnt = $need_count;
	        } else if ($elem_type == '003') {
				// 요금제가 후불제방식일 경우 무조건 가능으로 귀환한다.				
				$need_count = $sendCnt;
				if ($lmsFlag == 'LMS') {
					if ($docFlag == '')
						$need_count *= 2.5;
					else
						$need_count *= 5;
				}
				$this->useCnt = $need_count;
			}
	        return true;
	    } else {
	         $this->rtnMessage = '결제 후 이용 바랍니다.';
	         return false;	                      
	    }
	}
	
	function check_and_use_money($sendCnt, $cvCnt, $mb_no, $sms_id, $lmsFlag, $docFlag, $eleh_category, $poll_id)
	{
		if ($sendCnt == 0) {	// 문서변환이고 
			if ($poll_id != ''){	// 회신, 설문 첨부인 경우에만 
				if ($docFlag != '') {	// 변환문서에 첨부된 경우에만...
					$cvCnt = $cvCnt-1;	// 변환시 카운트 된 제외 	
				}
			}
		} else if ($docFlag != '') {
			$cvCnt = $cvCnt-1;	// 변환시 카운트 된 제외 	
		}
		
		$curr_date = G5_TIME_YMDHIS;
		if (!$this->is_possible_use($lmsFlag, $docFlag, $sendCnt, $cvCnt, $mb_no)){
			return false;
		}
		if ($sendCnt > 0) {
			$eleh_sms_price_field = '(select max(elpe_sms_money) from ele_price where elpe_stat = "m")';
		} else {
			$eleh_sms_price_field = 0;
		}		
		if ($cvCnt > 0) {
			$eleh_cv_price_field = '(select max(elpe_cv_money) from ele_price where elpe_stat = "m")';
		} else {
			$eleh_cv_price_field = 0;
		}		
		
		// 히스토리 넣고 
		if ($docFlag == '') {
			$SSql = "insert into ele_money_hst (eleh_id,eleh_date,eleh_inout,eleh_category,eleh_real_money,".
				   "eleh_curr_money,eleh_sms_cnt,eleh_cv_cnt,eleh_cv_user_price,eleh_sms_user_price,".
				   "eleh_cv_price,eleh_sms_price,eleh_sms_wr_no,eleh_edoc_ukey,eleh_eplm_ukey) values (".
				   " '{$mb_no}','{$curr_date}','2','{$eleh_category}',0,".
				   " '{$this->useMoney}','{$sendCnt}','{$cvCnt}','{$this->cv_user_price}','{$this->sms_user_price}', ".
				   " {$eleh_cv_price_field},{$eleh_sms_price_field},'{$sms_id}','{$docFlag}','{$poll_id}' ".
				   ")";			
		} else {
			$qSql = "select eleh_ukey from ele_money_hst where eleh_id = '{$mb_no}' and eleh_inout = '2' and eleh_sms_wr_no='' and eleh_edoc_ukey = '{$docFlag}'  ";			
			$qrow = sql_fetch($qSql);
			if ($qrow) {
				$eleh_ukey = $qrow['eleh_ukey'];
				$SSql = "update ele_money_hst set ".
					 "eleh_date = '{$curr_date}',".	    			
					 "eleh_category = '{$eleh_category}',".
					 "eleh_curr_money = eleh_curr_money + {$this->useMoney},". 
					 "eleh_sms_user_price = {$this->sms_user_price},".
					 "eleh_sms_cnt = {$sendCnt},".
					 "eleh_sms_wr_no = '{$sms_id}',".
					 "eleh_cv_cnt = eleh_cv_cnt+{$cvCnt} ".
					 "where eleh_ukey = '{$eleh_ukey}' ";
			} else {
				$SSql = "insert into ele_money_hst (eleh_id,eleh_date,eleh_inout,eleh_category,eleh_real_money,".
				   "eleh_curr_money,eleh_sms_cnt,eleh_cv_cnt,eleh_cv_user_price,eleh_sms_user_price,".
				   "eleh_cv_price,eleh_sms_price,eleh_sms_wr_no,eleh_edoc_ukey,eleh_eplm_ukey) values (".
				   " '{$mb_no}','{$curr_date}','2','{$eleh_category}',0,".
				   " '{$this->useMoney}','{$sendCnt}','{$cvCnt}','{$this->cv_user_price}','{$this->sms_user_price}', ".
				   " {$eleh_cv_price_field},{$eleh_sms_price_field},'{$sms_id}','{$docFlag}','{$poll_id}' ".
				   ")";			
			}	
		}		
		
		// 마스터 업데이트 하고..
		sql_query($SSql);		
		if ($this->CurrType == '001'){
			$Sql = "update ele_money_mst SET ";
			$Sql .= "elem_crnt_money = elem_crnt_money-{$this->useMoney},";
			$Sql .= "elem_crnt_cv_bonus_cnt = elem_crnt_cv_bonus_cnt-{$this->Curr_Bonus_cv_cnt},";
			$Sql .= "elem_proc_time = '{$curr_date}' ";
			$Sql .= "where elem_id = '{$mb_no}' ";
			sql_query($Sql);			
		} else if ($this->CurrType == '002' || $this->CurrType == '003'){
			$Sql = "update ele_money_mst SET ";
			$Sql .= "elem_crnt_cnt = elem_crnt_cnt-{$this->useCnt},";
			$Sql .= "elem_proc_time = '{$curr_date}' ";
			$Sql .= "where elem_id = '{$mb_no}' ";			
			sql_query($Sql);						
		}	
		return true;
	}
}
?>