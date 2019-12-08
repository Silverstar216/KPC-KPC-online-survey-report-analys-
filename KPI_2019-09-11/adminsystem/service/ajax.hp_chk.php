<?php
include_once("../common.php");
@include_once(G5_PLUGIN_PATH."/sms5/JSON.php");

if( !function_exists('json_encode') ) {
    function json_encode($data) {
        $json = new Services_JSON();
        return( $json->encode($data) );
    }
}
$err = '';
$arr_ajax_msg = array();
$exist_hplist = array();

if( !$bk_hp )
    $err = '휴대폰번호를 입력해 주십시오.';


function chk_edufile_sdata2($grnm){
    if (preg_match("/^학(생|부모)\(([1-6]{1})-([\s1-9]{1}[\d]{1})\)$/", $grnm,$rtnarr)) {
        $rtnarr[1] = '학'.$rtnarr[1];
        return $rtnarr;
    } else {
        return false;
    }
}

$bk_hp = get_hp($bk_hp);

$sql = " select bk_name as bk_name from {$g5['sms5_book_table']} where bk_hp = '$bk_hp' and mb_no = '{$member['mb_no']}'  ";
if($w == 'u' && $bk_no) {
    $sql .= " and bg_no = '$bg_no'  and bk_no <> '$bk_no' ";
}  
else {
    $sql .= " and bg_no = '$bg_no' ";	
}
$row = sql_fetch($sql);

if($row['bk_name']) {
    $err = '해당 그룹에 전화번호가 '.$row['bk_name'].'으로 이미 등록되어 있습니다.';
} else {// 같은 번호가 없으면 학년, 반, 학생번호가 이미 있는지 없는지. 
	if (($bk_stid=='')&&($get_bk_stid=='')){// 둘다 없으면 변경사항 없음.. 

	} else if (($get_bg_no==$bg_no)&&($get_bk_stid==$bk_stid)){// 변경사항 없음.. 

	} else if ($get_bg_no==$bg_no){// 그룹이 같으면 번호만 바뀐 것임.. 		
		if ($bk_stid == ''){// 삭제하는 것임... 
			
		} else {
			// 같은 그룹에 번호가 이미 있는지 체크 
			$sql = " select * from {$g5['sms5_book_table']} where bg_no = '$bg_no' and bk_stid = '$bk_stid' and mb_no = '{$member['mb_no']}'  ";
			$row = sql_fetch($sql);
			if($row['bk_name']) {
				$err = $bk_stid.'번은 이미 사용 중입니다('.$row['bk_name'].').';
			} 
		}
	} else {// 그룹도 바뀌고 번호는 바꼈나???? 변경우 그룹에 같은 번호가 있는지 체크 
		if ($bk_stid == ''){// 그룹을 바꾸면서 삭제하는 것임... 

		} else {
			// 변경후 그룹이 사용할 수 있는 그룹인지 체크 해아 한다. 
	    	$sql = " select bg_name from {$g5['sms5_book_group_table']} where bg_no = '$bg_no' ";		
			$row = sql_fetch($sql);
			if($row['bg_name']) {
				$chkbgn = chk_edufile_sdata2($row['bg_name']);
				if ($chkbgn == false){
					$err = '학생번호를 부여할 수 없는 그룹입니다.';	
				} else {// 
					$sql = " select * from {$g5['sms5_book_table']} where bg_no = '$bg_no' and bk_stid = '$bk_stid' and mb_no = '{$member['mb_no']}'  ";
					$row2 = sql_fetch($sql);
					if($row2['bk_name']) {
						$err = $bk_stid.'번은 이미 사용 중입니다('.$row['bg_name'].' '.$row2['bk_name'].').';
					} 
				}
			} else {
				$err = '학생번호를 부여할 수 없는 그룹입니다.';
			}    				
		}		
	}
}    

$arr_ajax_msg['error'] = $err;
$arr_ajax_msg['exist'] = $exist_hplist;

die( json_encode($arr_ajax_msg) );

?>