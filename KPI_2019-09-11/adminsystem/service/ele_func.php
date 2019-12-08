<?php
if (!defined('_GNUBOARD_')) exit;
function retry_make_4el_surl_T($convert_url,$titleName,$rcnt){
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
                $S_url_nm = retry_make_4el_surl_T($convert_url,$titleName,$rcnt);    
        }        
    }
    return $S_url_nm;
}

function make_4el_surl_T($convert_url,$titleName){
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
        $S_url_nm = retry_make_4el_surl_T($convert_url,$titleName,0);
    }
    //echo $convert_url."===".$S_url_nm;exit;
    return $S_url_nm;
}
    function change_var_eletter_text_T($targetfile)
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
 
             $encode = array('ASCII','UTF-8','EUC-KR');//chkd
			 $str_encode = @mb_detect_encoding($text_total, $encode);
			 //var_dump($str_encode);
			if( $str_encode == "EUC-KR" ){
                $text_total = iconv_utf8( $text_total );
            }
			

            $text_total = replace_phone_info_T($text_total);
						
            $chkVar = array(2);             
            $chkVar = is_it_contain_var_T($text_total);
            echo 'chk step2: '.$chkVar[0];
            
			if ($chkVar[0] == '00'){
                return $chkVar[0] ;
            } else {
				$maxVar_Cnt = $chkVar[0];              
                $text_total = $chkVar[1];
            }    
			
             $text_total = str_replace("{{", "",  $text_total);
			 $text_total = str_replace("}}", "",  $text_total);

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
			//echo $targetfile;exit;
            // 해당 문서에 개별 고지 문서라고 세팅하자...
        }
        return $maxVar_Cnt;                     
    }
	
    function is_it_contain_var_T($wText){
        //변수  갯수 세기        
        //변수 php 치환
        //기록.
        $maxValue = '00';

		//$result =  strpos($wText,"&#54637;&#47785;");
		//$result1 = strpos($wText,"항목");
		//$result2 = strpos($wText,"亲格");//亲格

		 $patt_grade = '/&#54637;&#47785;/';//항목
		 $result = preg_match_all($patt_grade, $wText); 

		 $patt_grade1 = '/항목/';//항목
		 $result1 = preg_match_all($patt_grade1, $wText); 

         $patt_grade2 = '/亲格/';//항목
		 $result2 = preg_match_all($patt_grade2, $wText);  

		if($result > 0)
		 {
            $alistSrt = explode("&#54637;&#47785;", $wText); 
            $listSize = sizeof($alistSrt);
            $val_arr = array();
            for ($idx = 1;$idx< $listSize;$idx++){
                $StrLen = strlen($alistSrt[$idx]);
               // if ($StrLen <  4) { continue; }
				
				$CurrValPos = strpos($alistSrt[$idx], '}}');
				if ($CurrValPos != false) {
					$CurrVal = substr ($alistSrt[$idx],0, $CurrValPos);
					if (in_array($CurrVal,$val_arr)) 
					{
						continue;    
					}
					array_push($val_arr,$CurrVal);
//=====================================================
					//$wText = replace_variable_eletter_html_T($wText,$CurrVal);
					//$repStr = '<'.'?'.'='.'$'.'eleText'.$CurrVal.'?'.'>';
					$repStr = '<'.'?'.'='.'$'.'eleText'.'['.'"'.$CurrVal.'"'.']'.'?'.'>';
					//$repStr = '<'.'?'.'='.'$'.'eleText'.'?'.'>'.$CurrVal;
					$wText = str_replace("&#54637;&#47785;".$CurrVal, $repStr,  $wText);//항목
//=====================================================
					if (strcmp($maxValue,$CurrVal) <= 0) {                    
						$maxValue = $CurrVal;
					}                					
				}
            }
		}
		else if($result1 > 0)
		{
		   $alistSrt = explode("항목", $wText); 
            $listSize = sizeof($alistSrt);
            $val_arr = array();
            for ($idx = 1;$idx< $listSize;$idx++){
                $StrLen = strlen($alistSrt[$idx]);
               // if ($StrLen <  4) { continue; }
				
				$CurrValPos = strpos($alistSrt[$idx], '}}');
				if ($CurrValPos != false) {
					$CurrVal = substr ($alistSrt[$idx],0, $CurrValPos);
					if (in_array($CurrVal,$val_arr)) 
					{
						continue;    
					}
					array_push($val_arr,$CurrVal);
//=====================================================
					//$wText = replace_variable_eletter_html_T($wText,$CurrVal);
					//$repStr = '<'.'?'.'='.'$'.'eleText'.$CurrVal.'?'.'>';
					$repStr = '<'.'?'.'='.'$'.'eleText'.'['.'"'.$CurrVal.'"'.']'.'?'.'>';
					//$repStr = '<'.'?'.'='.'$'.'eleText'.'?'.'>'.$CurrVal;
					$wText = str_replace("항목".$CurrVal, $repStr,  $wText);//항목
//=====================================================
					if (strcmp($maxValue,$CurrVal) <= 0) {                    
						$maxValue = $CurrVal;
					}                					
				}
            }
		}
		else if($result2 > 0)
		{
		   $alistSrt = explode("亲格", $wText); 
            $listSize = sizeof($alistSrt);
            $val_arr = array();
            for ($idx = 1;$idx< $listSize;$idx++){
                $StrLen = strlen($alistSrt[$idx]);
               // if ($StrLen <  4) { continue; }
				
				$CurrValPos = strpos($alistSrt[$idx], '}}');
				if ($CurrValPos != false) {
					$CurrVal = substr ($alistSrt[$idx],0, $CurrValPos);
					if (in_array($CurrVal,$val_arr)) 
					{
						continue;    
					}
					array_push($val_arr,$CurrVal);
//=====================================================
					//$wText = replace_variable_eletter_html_T($wText,$CurrVal);
					//$repStr = '<'.'?'.'='.'$'.'eleText'.$CurrVal.'?'.'>';
					$repStr = '<'.'?'.'='.'$'.'eleText'.'['.'"'.$CurrVal.'"'.']'.'?'.'>';
					$wText = str_replace("亲格".$CurrVal, $repStr,  $wText);
//=====================================================
					if (strcmp($maxValue,$CurrVal) <= 0) {                    
						$maxValue = $CurrVal;
					}                					
				}
            }
		}
		

            if ($maxValue == '00'){
                $patt_grade = '/&#50640;&#46272;&#54028;&#51064;&#44060;&#51064;&#48324;&#50577;&#49885;/';//에듀파인개인별양식
				$patt_grade1 = '/에듀파인개인별양식/';//에듀파인개인별양식
				$patt_grade2 = '/俊掂颇牢俺牢喊剧侥/';//에듀파인개인별양식

                $match_cnt = preg_match_all($patt_grade, $wText);  
				$match_cnt1 = preg_match_all($patt_grade1, $wText);
				$match_cnt2 = preg_match_all($patt_grade2, $wText);

                if ($match_cnt > 0)
				{
                    $maxValue = '-1';
                   // $wText      = replace_variable_edufine_html_T($wText);
//======================
                    $wontext="&#50640;&#46272;&#54028;&#51064;&#44060;&#51064;&#48324;&#50577;&#49885;";
                    $repStr = '<'.'?'.'php '.' @include_once('.'$'.'edufineBill'.');'.' ?'.'>';
                    $wText = str_replace($wontext, $repStr,  $wText);
//======================
                }
				else  if ($match_cnt1 > 0)
				{
                    $maxValue = '-1';
                   // $wText      = replace_variable_edufine_html_T($wText);
//======================
                    $wontext="에듀파인개인별양식";
                    $repStr = '<'.'?'.'php '.' @include_once('.'$'.'edufineBill'.');'.' ?'.'>';
                    $wText = str_replace($wontext, $repStr,  $wText);
//======================
                }
				else  if ($match_cnt2 > 0)
				{
                    $maxValue = '-1';
                   // $wText      = replace_variable_edufine_html_T($wText);
//======================
                    $wontext="俊掂颇牢俺牢喊剧侥";
                    $repStr = '<'.'?'.'php '.' @include_once('.'$'.'edufineBill'.');'.' ?'.'>';
                    $wText = str_replace($wontext, $repStr,  $wText);
//======================
                }


            }
			
            $rtnArr[0] = $maxValue;
            $rtnArr[1] = $wText;

            return $rtnArr;
    }
	 
//html에서 호출안함 
    function replace_variable_eletter_html_T($wText,$cngChar){
        $repStr = '<'.'?'.'='.'$'.'eleText'.$cngChar.'?'.'>';
        $wonStr = '항목'.$cngChar;
        $rtnText = str_replace($wonStr, $repStr,  $wText); 
        return $rtnText;
    }

    function replace_phone_info_T($wonText){
		    $aaa = array();
            $aaa = explode("{{", $wonText);
            
            for($i=1;$i < count($aaa);$i++)
            {
               $bbb= array();
               $bbb = explode("}}", $aaa[$i]);

			   $bbb[0] = strip_tags($bbb[0]);
               
			   $repStr = '<'.'?'.'='.'$'.'eleName'.'?'.'>';			   
			   $bbb[0] = str_replace("&#51060;&#47492;", $repStr,  $bbb[0]);//이름
			   $bbb[0] = str_replace("이름", $repStr,  $bbb[0]);//이름//
			   $bbb[0] = str_replace("捞抚", $repStr,  $bbb[0]);
			   
			   $repStr = '<'.'?'.'='.'$'.'elePhone'.'?'.'>';
			   $bbb[0] = str_replace("&#51204;&#54868;&#48264;&#54840;", $repStr,  $bbb[0]);//전화번호
			   $bbb[0] = str_replace("전화번호", $repStr,  $bbb[0]);//전화번호
			   $bbb[0] = str_replace("傈拳锅龋", $repStr,  $bbb[0]);//전화번호

               $aaa[$i] = implode("}}", $bbb);
            }
			$ppp = implode("{{", $aaa);
			
        return $ppp;
    }
   
//html에서 호출안함 
    function replace_variable_edufine_html_T($wText){
        $repStr = '<'.'?'.'php '.' @include_once('.'$'.'edufineBill'.');'.' ?'.'>';
        $wonStr = '에듀파인개인별양식';
        $rtnText = str_replace($wonStr, $repStr,  $wText); 

		//$rtnText = str_replace("&#50640;&#46272;&#54028;&#51064;&#44060;&#51064;&#48324;&#50577;&#49885;", $repStr,  $wText);
		//$rtnText = str_replace("俊掂颇牢俺牢喊剧侥", $repStr, $wText); 

        return $rtnText;
    }
    
    //======================================================================================
    
    function retry_make_4el_surl_s($convert_url,$titleName,$rcnt){
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
                $S_url_nm = retry_make_4el_surl_s($convert_url,$titleName,$rcnt);    
        }        
    }
    return $S_url_nm;
}

function make_4el_surl_s($convert_url,$titleName){
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
        $S_url_nm = retry_make_4el_surl_s($convert_url,$titleName,0);
    }
    return $S_url_nm;
}

function change_var_eletter_text_s($targetfile)
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
        
       // echo $text_total;
                    
        $text_total = replace_phone_info_s($text_total);
        $chkVar = array(2);             
        $chkVar = is_it_contain_var_s($text_total);
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

function is_it_contain_var_s($wText){
    //변수  갯수 세기        
    //변수 php 치환
    //기록. 
        $alistSrt = explode("항목", $wText); 
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
            $wText = replace_variable_eletter_html_s($wText,$CurrVal);
            if (strcmp($maxValue,$CurrVal) <= 0) {                    
                $maxValue = $CurrVal;
            }                
        }
        if ($maxValue == '00'){
            $patt_grade = '/{{에듀파인개인별양식}}/';
            $match_cnt = preg_match_all($patt_grade, $wText);                
            if ($match_cnt > 0){
                $maxValue = '-1';
                $wText      = replace_variable_edufine_html_s($wText);
            }
        }
        $rtnArr[0] = $maxValue;
        $rtnArr[1] = $wText;
        return $rtnArr;
}

function replace_variable_eletter_html_s($wText,$cngChar){
    $repStr = '&lt'.'?'.'='.'$'.'eleText'.$cngChar.'?'.'&gt';
    $wonStr = '{{항목'.$cngChar.'}}';
    $rtnText = str_replace($wonStr, $repStr,  $wText); 
    return $rtnText;
}

function replace_phone_info_s($wonText){
    /*
    $repStr = '<'.'?'.'='.'$'.'eleName'.'?'.'>';
    $wonStr = '{{이름}}';
    $wonText = str_replace($wonStr, $repStr,  $wonText); 
    $repStr = '<'.'?'.'='.'$'.'elePhone'.'?'.'>';
    $wonStr = '{{전화번호}}';
    $rtnText = str_replace($wonStr, $repStr,  $wonText); 
    return $rtnText;
    */
  
            $aaa = array();
            $aaa = explode("{{", $wonText);
            
            for($i=1;$i < count($aaa);$i++)
            {
               $bbb= array();
               $bbb = explode("}}", $aaa[$i]);

               $bbb[0] = strip_tags($bbb[0]);

               $bbb[0] = str_replace("이름", "&lt?=$eleName?&gt",  $bbb[0]);//이름
               $bbb[0] = str_replace("전화번호", "&lt?=$elePhone?&gt",  $bbb[0]);//전화번호

               $aaa[$i] = implode("}}", $bbb);
            }
            $ppp = implode("{{", $aaa);
            //echo $ppp;exit;
        return $ppp;
    
}

function replace_variable_edufine_html_s($wText){
    $repStr = '&lt'.'?'.'php '.' @include_once('.'$'.'edufineBill'.');'.' ?'.'&gt';
    $wonStr = '{{에듀파인개인별양식}}';
    $rtnText = str_replace($wonStr, $repStr,  $wText); 
    return $rtnText;
}
?>