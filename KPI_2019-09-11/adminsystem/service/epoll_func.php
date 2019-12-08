<?php
	if (!defined('_GNUBOARD_')) exit;
	function get_list_poll_person_info_row(){
		$idx = 0;		
		$poll_person_qry = "select * from epoll_info order by epli_sort asc";
		$rtnArr = array();
 		$result = sql_query($poll_person_qry);
    		while ($lrow = sql_fetch_array($result)){
	             	$rtnArr[$idx] = $lrow;
	             	$tmpArr = explode(',', $rtnArr[$idx]['epli_title']);
	             	$icnt      = $rtnArr[$idx]['epli_icnt'];
	             	$tmplen = count($tmpArr);
	             	if ($icnt > $tmplen){
	             	    $Rcnt = $tmplen;	
	             	} else {
	                              $Rcnt = $icnt;	    		
	             	}
	             	$sel_title = $tmpArr[0];
	             	for($kdx=1;$kdx<$Rcnt;$kdx++){
				$sel_title .= ' + '.$tmpArr[$kdx];
	             	}
	             	$rtnArr[$idx]['epli_icnt'] = $Rcnt;
	             	$rtnArr[$idx]['epli_title'] = $tmpArr;	             	
	             	$rtnArr[$idx]['epli_sel_title'] = $sel_title;	             	
	             	$tmpArr = explode(',', $rtnArr[$idx]['epli_size']);
	             	$rtnArr[$idx]['epli_size'] = $tmpArr;	             	
	             	$tmpArr = explode(',', $rtnArr[$idx]['epli_type']);	             
	             	$rtnArr[$idx]['epli_type'] = $tmpArr;	             	
	             	$idx++;
	             }
		return $rtnArr;
	}

	function get_poll_person_info($as_type){
		if (!$as_type) return '';

		$poll_person_qry = "select * from epoll_info where epli_ilbh = '{$as_type}' ";
 		$result = sql_fetch($poll_person_qry);
		if (!$result) return $result;
	             $icnt      = $result['epli_icnt'];
	             $tmpArr = explode(',', $result['epli_title']);

             	$tmplen = count($tmpArr);
             	if ($icnt > $tmplen){
             	    $Rcnt = $tmplen;	
             	} else {
                              $Rcnt = $icnt;	    		
             	}
             	$result['epli_icnt'] = $Rcnt;
             	$result['epli_title'] = $tmpArr;	             	
             	$tmpArr = explode(',', $result['epli_size']);
             	$result['epli_size'] = $tmpArr;	             	
             	$tmpArr = explode(',', $result['epli_type']);	             
             	$result['epli_type'] = $tmpArr;	             	
		return $result;
	}

	function get_detail_info_before($as_type){
		if (!$as_type) return '';
		$poll_person_qry = "select epli_title from epoll_info where epli_ilbh = '{$as_type}' ";
 		$result = sql_fetch($poll_person_qry);
		if (!$result) return $result;
	             $tmpArr = explode(',', $result['epli_title']);
		return $tmpArr;		
	}
	function return_real_poll_info($barr,$call_str){
		$icnt = count($barr);
	             $tmpArr = explode('|', $call_str);
             	$tmplen = count($tmpArr);
             	if ($icnt > $tmplen){
             	    $Rcnt = $tmplen;	
             	} else {
                              $Rcnt = $icnt;	    		
             	}
             	$rtnStr = '';
             	for ($idx=0;$idx<$Rcnt;$idx++){
             		$tmpStr = str_replace('{g}', '|',$tmpArr[$idx]);
             		if ($barr[$idx]	== '학년'){
				$rtnStr .= $tmpStr.$barr[$idx].' ';
             		} else if ($barr[$idx] == '반'){
             			$rtnStr .= $tmpStr.$barr[$idx].' ';
             		} else {
             			if ($Rcnt == 1){
					$rtnStr .= $tmpStr;
             			} else {
             				$rtnStr .= $barr[$idx].'['.$tmpStr.'] ';             				
             			}
				
             		}
             	}
             	return $rtnStr;
	}
	function return_real_poll_arr($call_str){
	             $tmpArr = explode('|', $call_str);
             	$tmplen = count($tmpArr);
             	for ($idx=0;$idx<$tmplen;$idx++){
             		$tmpArr[$idx] = str_replace('{g}', '|',$tmpArr[$idx]);
             	}
             	return $tmpArr;
	}	
?>