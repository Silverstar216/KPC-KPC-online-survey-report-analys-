<?php
if (!defined('_GNUBOARD_')) exit;

function retry_make_4el_surl($convert_url,$titleName,$rcnt){
    $username = 'hancloud';
    $password = 'ynd#1j$.o';
    $url     = $convert_url; // URL to shrink
    $keyword = '';                        // optional keyword
    $title   = $titleName;                // optional, if omitted YOURLS will lookup title with an HTTP request
    $format  = 'simple';                       // output format: 'json', 'xml' or 'simple'

    // EDIT THIS: the URL of the API file
    $api_url = 'http://mms.ac/yourls-api.php';

    // Init the CURL session
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $api_url );
    curl_setopt( $ch, CURLOPT_HEADER, 0 );            // No header in the result
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); // Return, do not echo result
    curl_setopt( $ch, CURLOPT_POST, 1 );              // This is a POST request
    curl_setopt( $ch, CURLOPT_POSTFIELDS, array(      // Data to POST
            'url'      => $url,
            'keyword'  => $keyword,
            'title'    => $title,
            'format'   => $format,
            'action'   => 'shorturl',
            'username' => $username,
            'password' => $password
        ) );

    // Fetch and return content
    $S_url_nm = curl_exec($ch);
    curl_close($ch);

    // Do something with the result. Here, we just echo it.
    if ($S_url_nm == '') {
        sleep(5);// 5초 대기 해버린다. 
        $rcnt++;        
        if ($rcnt > 60) {// 5분 간 시도 해서 안되면 
                $S_url_nm = $convert_url;// 이거 에러다... 어찌 합니까... 
        } else {
                $S_url_nm = retry_make_4el_surl($convert_url,$titleName,$rcnt);    
        }        
    }
    return $S_url_nm;
}

function make_4el_surl($convert_url, $titleName)
{
    $username = 'hancloud';
    $password = 'ynd#1j$.o';
    $url     = $convert_url; // URL to shrink
    $keyword = '';                        // optional keyword
    $title   = $titleName;                // optional, if omitted YOURLS will lookup title with an HTTP request
    $format  = 'simple';                       // output format: 'json', 'xml' or 'simple'

    // EDIT THIS: the URL of the API file
    $api_url = 'http://mms.ac/yourls-api.php';

    // Init the CURL session
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $api_url );
    curl_setopt( $ch, CURLOPT_HEADER, 0 );            // No header in the result
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); // Return, do not echo result
    curl_setopt( $ch, CURLOPT_POST, 1 );              // This is a POST request
    curl_setopt( $ch, CURLOPT_POSTFIELDS, array(      // Data to POST
            'url'      => $url,
            'keyword'  => $keyword,
            'title'    => $title,
            'format'   => $format,
            'action'   => 'shorturl',
            'username' => $username,
            'password' => $password
        ) );

    // Fetch and return content
    $S_url_nm = curl_exec($ch);
    curl_close($ch);

    // Do something with the result. Here, we just echo it.
    if ($S_url_nm == '') {
        $S_url_nm = retry_make_4el_surl($convert_url,$titleName,0);
    }
    return $S_url_nm;
}

function change_var_eletter_text($targetfile)
{   			
	$text_total = '';
	$rfilename = basename($targetfile);                    
	$targetfile = '../upload/etc/'.$rfilename;
	
	$FileExtist = file_exists($targetfile) ;        
	$maxVar_Cnt = '00';
	if ($FileExtist) {
		$tfile = fopen($targetfile, "r");
		if ($tfile) {   
			 while(!feof($tfile))
				$text_total.=fgets($tfile);

		}
		fclose($tfile);
					
		$text_total = replace_phone_info($text_total);
		$chkVar = array(2);             
		$chkVar = is_it_contain_var($text_total);
		//echo 'chk : '.$chkVar[0];
		if ($chkVar[0] == '00'){
			return $chkVar[0] ;
		} else {
	 $maxVar_Cnt = $chkVar[0];              
			$text_total = $chkVar[1];
		}            
		$file_ext= strrchr($rfilename,".");       
		$copyfilen = str_replace($file_ext,'.ele',$rfilename);                        
 $copyfilen = '../upload/etc/'.$copyfilen;            
		$FileExtist = file_exists($copyfilen) ;     
		if ($FileExtist) {
			unlink($copyfilen);
		}
		$cfile = fopen($copyfilen, "w");
		if ($cfile) {
			fwrite($cfile, $text_total);
		}
		fclose($cfile);
				   
		$FileExtist = file_exists($targetfile) ;        
		if ($FileExtist) {
			unlink($targetfile);
		}       
		rename($copyfilen,$targetfile);            
		// 해당 문서에 개별 고지 문서라고 세팅하자...
	}
	return $maxVar_Cnt;                     
}

function is_it_contain_var($wText){
	//변수  갯수 세기        
	//변수 php 치환
	//기록. 
		$alistSrt = explode("{{항목", $wText); 
		$listSize = sizeof($alistSrt);
		$maxValue = '00';
		$val_arr = array();
		for ($idx = 1;$idx< $listSize;$idx++){
			$StrLen = strlen($alistSrt[$idx]);
			if ($StrLen <  4) { continue; }
			$endBrase = substr ($alistSrt[$idx], 2, 2);  
			if (strcmp('}}',$endBrase) <> 0) {
				continue;
			}
			$CurrVal = substr ($alistSrt[$idx], 0, 2);
			if (in_array($CurrVal,$val_arr)) {
				continue;    
			}
			array_push($val_arr,$CurrVal);
			$wText = replace_variable_eletter_html($wText,$CurrVal);
			if (strcmp($maxValue,$CurrVal) <= 0) {                    
				$maxValue = $CurrVal;
			}                
		}
		if ($maxValue == '00'){
			$patt_grade = '/{{에듀파인개인별양식}}/';
			$match_cnt = preg_match_all($patt_grade, $wText);                
			if ($match_cnt > 0){
				$maxValue = '-1';
				$wText      = replace_variable_edufine_html($wText);
			}
		}
		$rtnArr[0] = $maxValue;
		$rtnArr[1] = $wText;
		return $rtnArr;
}

function replace_variable_eletter_html($wText,$cngChar){
	$repStr = '<'.'?'.'='.'$'.'eleText'.$cngChar.'?'.'>';
	$wonStr = '{{항목'.$cngChar.'}}';
	$rtnText = str_replace($wonStr, $repStr,  $wText); 
	return $rtnText;
}

function replace_phone_info($wonText){
	$repStr = '<'.'?'.'='.'$'.'eleName'.'?'.'>';
	$wonStr = '{{이름}}';
	$wonText = str_replace($wonStr, $repStr,  $wonText); 
	$repStr = '<'.'?'.'='.'$'.'elePhone'.'?'.'>';
	$wonStr = '{{전화번호}}';
	$rtnText = str_replace($wonStr, $repStr,  $wonText); 
	return $rtnText;
}

function replace_variable_edufine_html($wText){
	$repStr = '<'.'?'.'php '.' @include_once('.'$'.'edufineBill'.');'.' ?'.'>';
	$wonStr = '{{에듀파인개인별양식}}';
	$rtnText = str_replace($wonStr, $repStr,  $wText); 
	return $rtnText;
}
?>