<?php
	include_once("./_common.php");

function dapyo_local_check($secnum){
	$rtnarr['code'] = 'S';
	$phlen = mb_strlen($secnum, "UTF-8");
    if ($phlen == 4){
        switch($secnum) {
        case '1588' : 
        case '1577' : 
        case '1899' : 
        case '1666' : 
        case '1688' : 
        case '1544' : 
        case '1644' : 
        case '1661' : 
        case '1800' : 
        case '1599' : 
        case '1566' : 
        case '1600' : 
        case '1670' : 
        case '1855' : 
        case '1877' :   
        		$rtnarr['code'] = 'E';
	 			$rtnarr['errtext'] = $secnum.'대표번호 앞에는 지역번호가 있으면 안됩니다.';      
                return $rtnarr;
                break;
        default : return $rtnarr; 
        }
    } else if ($phlen == 3){
        return $rtnarr;
    } else {
    	$rtnarr['code'] = 'E';
	 	$rtnarr['errtext'] = '전화번호 자릿수에 오류가 있습니다.'; 
        return $rtnarr;
    }
}

function check_reply_num($aphone){    
	$rtnarr['code'] = 'E';
	$rtnarr['errtext'] = '발신번호 체계에 맞지 않습니다.';
	$phlen = mb_strlen($aphone, "UTF-8");
	if ($phlen < 9){
		$rtnarr['code'] = 'E';
		$rtnarr['errtext'] = '회신번호는 8자리 이상이어야 합니다.';
	}
    $pattern = "/^01[016789]-[0-9]{3,4}-[0-9]{4}$/";

    if (preg_match($pattern,$aphone)) {
    	$rtnarr['code'] = 'S';
        return $rtnarr;
    }
    $patternS = "/^02-[0-9]{3,4}-[0-9]{4}$/";
    if (preg_match($patternS,$aphone)) {
    	$chkphnum = explode('-',$aphone);
        $rtnarr = dapyo_local_check($chkphnum[1]);
        return $rtnarr;
    }

	$patternS = "/^070-[0-9]{3,4}-[0-9]{4}$/";
    if (preg_match($patternS,$aphone)) {       
        $rtnarr['code'] = 'S';
        return $rtnarr;
    }
    $patternS = "/^03[123]-[0-9]{3,4}-[0-9]{4}$/";    
    if (preg_match($patternS,$aphone)) {   
        $chkphnum = explode('-',$aphone);
        $rtnarr = dapyo_local_check($chkphnum[1]);
        return $rtnarr;
    }
    $patternS = "/^0[46][1234]-[0-9]{3,4}-[0-9]{4}$/";    
    if (preg_match($patternS,$aphone)) {   
        $chkphnum = explode('-',$aphone);
        $rtnarr = dapyo_local_check($chkphnum[1]);
        return $rtnarr;
    }        
    $patternS = "/^05[12345]-[0-9]{3,4}-[0-9]{4}$/";    
    if (preg_match($patternS,$aphone)) {   
        $chkphnum = explode('-',$aphone);
        $rtnarr = dapyo_local_check($chkphnum[1]);
        return $rtnarr;
    }

    if ($phlen == 9){
    	$chkphnum = explode('-',$aphone);
        switch($chkphnum[0]) {
        case '1588' : 
        case '1577' : 
        case '1899' : 
        case '1666' : 
        case '1688' : 
        case '1544' : 
        case '1644' : 
        case '1661' : 
        case '1800' : 
        case '1599' : 
        case '1566' : 
        case '1600' : 
        case '1670' : 
        case '1855' : 
        case '1877' : 
           $rtnarr['code'] = 'S';
        	return $rtnarr; 
            break;
        default : 
			$rtnarr['code'] = 'E';
	 		$rtnarr['errtext'] = '지역번호가 없습니다.'; 
        	return $rtnarr;
        }        
    }
    return $rtnarr;
}

$ph = strip_tags($_POST['ph']);
$bg = strip_tags($_POST['bg']);
$pr = strip_tags($_POST['pr']);

	$data_array = array();
	$data_array['rtn'] = 'X';
	if ($is_guest) {
		$data_array['rtn'] = 'G';
	} else {
		if ($pr == 'n'){// 신규 등록이다..			
			$chkarr = check_reply_num($ph);
			if ($chkarr['code'] == 'E'){ // 번호 유효성 체크 해야 한다. 	
				$data_array['rtn'] = $chkarr['code'];
				$data_array['err'] = $chkarr['errtext'];					
			} else {
	// 기존에 있는지 찾는다... 삭제 된것이 있으면 다시 돌려놓는다...	
				$res = sql_fetch("select * from sms5_phone_identity where ph_mbno = '{$member['mb_no']}' and ph_phone = '{$ph}' ");
				if ($res)   {
					if ($res['ph_gubn'] == '5'){
						$SSql = "update sms5_phone_identity set ph_gubn = 1, ph_cdate=sysdate(),ph_bigo = '{$bg}' ".
						        "where ph_mbno = '{$member['mb_no']}' and ph_phone = '{$ph}'";
	    				sql_query($SSql);
	    				$data_array['rtn'] = 'S';
					} else {
						$data_array['rtn'] = 'E';
						$data_array['err'] = '이미 등록되어 있는 번호입니다.'.$res['ph_bigo'];		
					}
				}  else {
					// 인증요청으로 인써트...
					$SSql = "insert into sms5_phone_identity (ph_mbno,ph_phone,ph_identity,ph_idate,ph_gubn,ph_cdate,ph_bigo) values ".
					        "('{$member['mb_no']}','{$ph}',0,sysdate(),1,sysdate(),'{$bg}')";
	    			sql_query($SSql);
	    			$data_array['rtn'] = 'S'; 
				} 
			}
		} else if ($pr == 'd'){// 삭제 처리 
			$SSql = "update sms5_phone_identity set ph_gubn = 5, ph_cdate=sysdate() where ph_mbno = '{$member['mb_no']}' and ph_phone = '{$ph}'";
	    	sql_query($SSql);
	    	$data_array['rtn'] = 'S';
		} else {	
			$SSql = "update sms5_phone_identity set ph_bigo = '{$bg}', ph_cdate=sysdate() where ph_mbno = '{$member['mb_no']}' and ph_phone = '{$pr}'";
	    	sql_query($SSql);
	    	$data_array['rtn'] = 'S';				
		}
	}
	echo  json_encode($data_array);
?>	