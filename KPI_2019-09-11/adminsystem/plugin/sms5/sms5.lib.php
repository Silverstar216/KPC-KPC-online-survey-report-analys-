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

/*************************************************************************
**
**  sms5에 사용할 함수 모음
**
*************************************************************************/

// 스킨디렉토리를 SELECT 형식으로 얻음
function get_sms5_skin_select($skin_gubun, $id, $name, $selected='', $event='')
{
    $skins = get_skin_dir($skin_gubun, G5_SMS5_PATH);
    $str = "<select id=\"$id\" name=\"$name\" $event>\n";
    for ($i=0; $i<count($skins); $i++) {
        if ($i == 0) $str .= "<option value=\"\">선택</option>";
        $str .= option_selected($skins[$i], $selected);
    }
    $str .= "</select>";
    return $str;
}

// 한페이지에 보여줄 행, 현재페이지, 총페이지수, URL
function sms5_sub_paging($write_pages, $cur_page, $total_page, $url, $add="", $starget="")
{
    if( $starget ){
        $url = preg_replace('#&amp;'.$starget.'=[0-9]*#', '', $url) . '&amp;'.$starget.'=';
    }
    
    $str = '';
    if ($cur_page > 1) {
        $str .= '<a href="'.$url.'1'.$add.'" class="pg_page pg_start">처음</a>'.PHP_EOL;
    }

    $start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
    $end_page = $start_page + $write_pages - 1;

    if ($end_page >= $total_page) $end_page = $total_page;

    if ($start_page > 1) $str .= '<a href="'.$url.($start_page-1).$add.'" class="pg_page pg_prev">이전</a>'.PHP_EOL;

    if ($total_page > 1) {
        for ($k=$start_page;$k<=$end_page;$k++) {
            if ($cur_page != $k)
                $str .= '<a href="'.$url.$k.$add.'" class="pg_page">'.$k.'<span class="sound_only">페이지</span></a>'.PHP_EOL;
            else
                $str .= '<span class="sound_only">열린</span><strong class="pg_current">'.$k.'</strong><span class="sound_only">페이지</span>'.PHP_EOL;
        }
    }

    if ($total_page > $end_page) $str .= '<a href="'.$url.($end_page+1).$add.'" class="pg_page pg_next">다음</a>'.PHP_EOL;

    if ($cur_page < $total_page) {
        $str .= '<a href="'.$url.$total_page.$add.'" class="pg_page pg_end">맨끝</a>'.PHP_EOL;
    }

    if ($str)
        return "<nav class=\"pg_wrap\"><span class=\"pg\">{$str}</span></nav>";
    else
        return "";
}

// 권한 검사
function ajax_auth_check($auth, $attr)
{
    global $is_admin;

    if ($is_admin == 'super') return;

    if (!trim($auth))
        die("{\"error\":\"이 메뉴에는 접근 권한이 없습니다.\\n\\n접근 권한은 최고관리자만 부여할 수 있습니다.\"}");

    $attr = strtolower($attr);

    if (!strstr($auth, $attr)) {
        if ($attr == 'r')
            die("{\"error\":\"읽을 권한이 없습니다.\"}");
        else if ($attr == 'w')
            die("{\"error\":\"입력, 추가, 생성, 수정 권한이 없습니다.\"}");
        else if ($attr == 'd')
            die("{\"error\":\"삭제 권한이 없습니다.\"}");
        else
            die("{\"error\":\"속성이 잘못 되었습니다.\"}");
    }
}

if ( ! function_exists('array_overlap')) {
    function array_overlap($arr, $val) {
        for ($i=0, $m=count($arr); $i<$m; $i++) {
            if ($arr[$i] == $val)
                return true;
        }
        return false;
    }
}
if ( ! function_exists('get_hp')) {
    function get_hp($hp, $hyphen=1)
    {
        global $g5;
        if ($hyphen) $preg = "$1-$2-$3"; else $preg = "$1$2$3";
        $hp = str_replace('-', '', trim($hp));
        $hp = str_replace(' ', '', trim($hp));                        
        $hp = str_replace('.', '', trim($hp));        
        $hp = str_replace('(', '', trim($hp));
        $hp = str_replace(')', '', trim($hp));
        $hp = str_replace('[', '', trim($hp));
        $hp = str_replace(']', '', trim($hp));      
        if (!preg_match("/^(01[016789])([0-9]{3,4})([0-9]{4})$/", $hp)) return '';    
        $hp = preg_replace("/^(01[016789])([0-9]{3,4})([0-9]{4})$/", $preg, $hp);
        return $hp;
    }
}

if ( ! function_exists('is_hp')) {
    function is_hp($hp)
    {
        $hp = str_replace('-', '', trim($hp));
        $hp = str_replace(' ', '', trim($hp));                
        $hp = str_replace('.', '', trim($hp));        
        $hp = str_replace('(', '', trim($hp));
        $hp = str_replace(')', '', trim($hp));
        $hp = str_replace('[', '', trim($hp));
        $hp = str_replace(']', '', trim($hp));
        if (preg_match("/^(01[016789])([0-9]{3,4})([0-9]{4})$/", $hp))
            return true;
        else
            return false;
    }
}
if ( ! function_exists('alert_just')) {
    // 경고메세지를 경고창으로
    function alert_just($msg='', $url='')
    {
        global $g5;

        if (!$msg) $msg = '올바른 방법으로 이용해 주십시오.';

        //header("Content-Type: text/html; charset=$g5[charset]");
        echo "<meta charset=\"utf-8\">";
        echo "<script language='javascript'>alert('$msg');";
        echo "</script>";
        exit;
    }
}

if ( ! function_exists('utf2euc')) {
    function utf2euc($str) {
        return iconv("UTF-8","cp949//IGNORE", $str);
    }
}
if ( ! function_exists('is_ie')) {
    function is_ie() {
        return isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false);
    }
}

/**
 * SMS 발송을 관장하는 메인 클래스이다.
 *
 * 접속, 발송, URL발송, 결과등의 실질적으로 쓰이는 모든 부분이 포함되어 있다.
 */
include_once(G5_LIB_PATH.'/common.lib.php');    // 공통 라이브러리

class SMS5 extends SMS {
	var $Log = array();	
	var $Status = array();
	
	function SMS_connect($sms_server, $sms_id, $sms_pw, $port, $dbname) {						
		$this->EmmaId = $sms_id;				// 계약 후 지정
		$this->EmmaPwd = $sms_pw;				// 계약 후 지정
		$this->EmmaDbServer = $sms_server;
		$this->EmmaDbPort = $port;
		$this->EmmaDbName = $dbname;		
		
		$this->EmmaDb = sql_connect($this->EmmaDbServer, $this->EmmaId, $this->EmmaPwd, $this->EmmaDbName) or die('MySQL Connect Error!!!');
		
		$this->SelectEmmadb  = sql_select_db($this->EmmaDbName, $this->EmmaDb) or die('MySQL DB Error!!!');			
	}

     /**
     * 발송번호의 값이 정확한 값인지 확인합니다.
     *
     * @param	strDest	발송번호 배열입니다.
     *			nCount	배열의 크기입니다.
     * @return			처리결과입니다.
     */
    function CheckCommonTypeDest($strDest, $nCount) 
	{
        for ($i=0; $i<$nCount; $i++) {
            $hp_number = preg_replace("/[^0-9]/","",$strDest[$i]['bk_hp']);
            if (strlen($hp_number)<10 || strlen($hp_number)>11) return "휴대폰 번호가 틀렸습니다";

            $CID=substr($hp_number,0,3);
            if ( preg_match("/[^0-9]/",$CID) || ($CID!='010' && $CID!='011' && $CID!='016' && $CID!='017' &&$CID!='018' && $CID!='019') ) return "휴대폰 앞자리 번호가 잘못되었습니다";
        }
    }

    /**
     * 회신번호의 값이 정확한 값인지 확인합니다.
     *
     * @param	strDest	회신번호입니다.
     * @return			처리결과입니다.
     */
    function CheckCommonTypeCallBack($strCallBack) {
        if (preg_match("/[^0-9]/", $strCallBack)) return "회신 전화번호가 잘못되었습니다";
    }


    /**
     * 예약날짜의 값이 정확한 값인지 확인합니다.
     *
     * @param	text	원하는 문자열입니다.
     *			size	원하는 길이입니다.
     * @return			처리결과입니다.
     */
    function CheckCommonTypeDate($strDate) {
        $strDate=preg_replace("/[^0-9]/","",$strDate);
        if ($strDate) {
            if (!checkdate(substr($strDate,4,2),substr($strDate,6,2),substr($rsvTime,0,4))) return "예약날짜가 잘못되었습니다";
            if (substr($strDate,8,2)>23 || substr($strDate,10,2)>59) return "예약시간이 잘못되었습니다";
        }
    }


    /**
     * URL콜백용으로 메세지 크기를 수정합니다.
     *
     * @param	url		URL 내용입니다.
     *			msg		결과메시지입니다.
     *			desk	문자내용입니다.
     */
    function CheckCallCenter($url, $dest, $data) {
        switch (substr($dest,0,3)) {
            case '010': //20바이트
                return cut_char($data,20);
                break;
            case '011': //80바이트
                return cut_char($data,80);
                break;
            case '016': // 80바이트
                return cut_char($data,80);
                break;
            case '017': // URL 포함 80바이트
                return cut_char($data,80 - strlen($url));
                break;
            case '018': // 20바이트
                return cut_char($data,20);
                break;
            case '019': // 20바이트
                return cut_char($data,20);
                break;
            default:
                return cut_char($data,80);
                break;
        }
    }
	
	function AddDests($strDest, $strCallBack, $strCaller, $strURL, $strMessage, $strDate="", $nCount) {
        global $g5;

        $Error = $this->CheckCommonTypeDest($strDest, $nCount);
		$Error = $this->CheckCommonTypeCallBack($strCallBack);
		$Error = $this->CheckCommonTypeDate($strDate);

		$strCallBack    = spacing($strCallBack,11);
		$strCaller      = spacing($strCaller,10);		

		for ($i=0; $i<$nCount; $i++) {
			$hp_number	= spacing($strDest[$i]['bk_hp'],11);			
			$strData = stripslashes($strMessage);			

			if (!$strURL) {
				// $strData	= spacing(cut_char($strData,80),80);
				$this->Data[$i]	= '01144#'.$strDate.'#'.$strData.'#'.$strCallBack.'#'.$hp_number;
			} else {
				$strURL		= spacing($strURL,50);
				$strData	= spacing($this->CheckCallCenter($strURL, $hp_number, $strData),80);

				$this->Data[$i]	= '05173#'.$strDate.'#'.$strData.'#'.$strCallBack.'#'.$hp_number.$strURL;				
			}					
			
			$code = '1000';
			$this->Result[] = "$strCallBack:$hp_number:$code";
		}
		return true; // 수정대기
	}

	function SendMessage($subject, $lmsvalue, $isbundle = true) {				
		$index = 1;
		$max_smt = 0;
								
		foreach($this->Data as $puts) {		
			$pieces = explode("#", $puts);
			if (count($pieces) > 0) {
				$dateclientreq = $pieces[1];				
				$content = $pieces[2];
				$callback = $pieces[3];
				$phone = $pieces[4];
				
				if ($lmsvalue == 'LMS') {
					if ($isbundle == true) {
						// 동보전송
						if ($index == 1) {
							if ($dateclientreq == "")
								$sql = "insert into em_mmt_tran (date_client_req, subject, content, attach_file_group_key, callback, service_type, broadcast_yn, msg_status) values"."(sysdate(), '{$subject}', '{$content}', '0', '{$callback}', '3', 'Y', '9');";
							else
								$sql = "insert into em_mmt_tran (date_client_req, subject, content, attach_file_group_key, callback, service_type, broadcast_yn, msg_status) values"."('{$dateclientreq}', '{$subject}', '{$content}', '0', '{$callback}', '3', 'Y', '9');";
							sql_query($sql);
							
							$sql = "select max(mt_pr) from em_mmt_tran;";
							$max_smt_obj = sql_fetch_emma($this->EmmaDb, $sql);
							$max_smt = $max_smt_obj['max(mt_pr)'];														
						}
						
						$detail = "insert into em_mmt_client (mt_pr, mt_seq, msg_status, recipient_num, change_word1, change_word2, change_word3, change_word4, change_word5) values"."($max_smt, $index, '1', '{$phone}', NULL, NULL, NULL, NULL, NULL);";							
						sql_query($detail);
						
						if ($index == count($this->Data)) {
							$update = "update em_mmt_tran set msg_status='1' where mt_pr=$max_smt;";
							sql_query($update);
						}
					} else {
						// 개별전송
						if ($dateclientreq == "")
							$sql = "insert into em_mmt_tran (date_client_req, subject, content, attach_file_group_key, callback, service_type, broadcast_yn, msg_status, recipient_num) values"."(sysdate(), '{$subject}', '{$content}', '0', '{$callback}', '3', 'Y', '9', '{$phone}');";
						else
							$sql = "insert into em_mmt_tran (date_client_req, subject, content, attach_file_group_key, callback, service_type, broadcast_yn, msg_status, recipient_num) values"."('{$dateclientreq}', '{$subject}', '{$content}', '0', '{$callback}', '3', 'Y', '9', '{$phone}');";
						sql_query($sql);
						
						$sql = "select max(mt_pr) from em_mmt_tran;";
						$max_smt_obj = sql_fetch_emma($this->EmmaDb, $sql);
						$max_smt = $max_smt_obj['max(mt_pr)'];							
					}			   							     
				}
				else {
					if ($isbundle == true) {
						// 동보전송
						if ($index == 1) {
							if ($dateclientreq == "")
								$sql = "insert into em_smt_tran (date_client_req, content, callback, service_type, broadcast_yn, msg_status) values"."(sysdate(), '{$content}', '{$callback}', '0', 'Y', '9');";
							else
								$sql = "insert into em_smt_tran (date_client_req, content, callback, service_type, broadcast_yn, msg_status) values"."('{$dateclientreq}', '{$content}', '{$callback}', '0', 'Y', '9');";								
							sql_query($sql);
							
							$sql = "select max(mt_pr) from em_smt_tran;";
							$max_smt_obj = sql_fetch_emma($this->EmmaDb, $sql);
							$max_smt = $max_smt_obj['max(mt_pr)'];														
						}
						
						$detail = "insert into em_smt_client (mt_pr, mt_seq, msg_status, recipient_num) values"."($max_smt, $index, '1', '{$phone}');";							
						sql_query($detail);
						
						if ($index == count($this->Data)) {
							$update = "update em_smt_tran set msg_status='1' where mt_pr=$max_smt;";
							sql_query($update);
						}
					} else {
						// 개별전송
						if ($dateclientreq == "")
							$sql = "insert into em_smt_tran (date_client_req, content, callback, service_type, broadcast_yn, msg_status, recipient_num) values"."(sysdate(), '{$content}', '{$callback}', '0', 'N', '1', '{$phone}');";
						else
							$sql = "insert into em_smt_tran (date_client_req, content, callback, service_type, broadcast_yn, msg_status, recipient_num) values"."('{$dateclientreq}', '{$content}', '{$callback}', '0', 'N', '1', '{$phone}');";
						sql_query($sql);
						
						$sql = "select max(mt_pr) from em_smt_tran;";
						$max_smt_obj = sql_fetch_emma($this->EmmaDb, $sql);
						$max_smt = $max_smt_obj['max(mt_pr)'];							
					}	
				}														
																
				$this->Status[] = "$max_smt:$callback:$phone";
				$this->Log[] = $puts;																							
			}
			$index++;	
		}							
		
		$this->Data = "";
		return $index;
	}
	
	function SendSMS($wr_type, $titlename, $subject, $lmsvalue, $epls_wrno, $mlong_url, $callback, $dateclientreq, $message) {				
		$isbundle = true;
		$index = 1;
		$max_smt = 0;		
	
		if (($wr_type == 0)||($wr_type == 1)||($wr_type == 2)) {
			$isbundle = false;
		}
		
		$qry = sql_query("select * from sms5_history where wr_no = '{$epls_wrno}' and hs_mt_pr is null ");
		while ($hisrow = sql_fetch_array($qry))
		{
			$hs_no = $hisrow['hs_no'];   
			$hs_hp = $hisrow['hs_hp'];
			$hs_name = $hisrow['hs_name'];
			$content =  str_replace('{이름}', $hs_name, $message);
			if (($wr_type == 0)||($wr_type == 1)||($wr_type == 2)) {
				// short_url 생성                       	
				$mmlong_url = $mlong_url.'&sk='.$hs_no;
				$S_url_nm = make_4el_surl($mmlong_url, $titlename); 
				$content = $content.' '.$S_url_nm;
			} 
	
			$phone = str_replace('-', '', trim($hs_hp));
			$phone = str_replace(' ', '', trim($phone));                        
			$phone = str_replace('.', '', trim($phone));        
			$phone = str_replace('(', '', trim($phone));
			$phone = str_replace(')', '', trim($phone));
			$phone = str_replace('[', '', trim($phone));
			$phone = str_replace(']', '', trim($phone)); 
	
			if ($lmsvalue == 'LMS') {			// LMS		
				if ($dateclientreq == "")
					$sql = "insert into msg_queue (msg_type, dstaddr, callback, stat, subject, text, request_time) values".
						   "('3', '{$phone}', '{$callback}', '0', '{$subject}', '{$content}', sysdate());";
				else
					$sql = "insert into msg_queue (msg_type, dstaddr, callback, stat, subject, text, request_time) values".
						   "('3', '{$phone}', '{$callback}', '0', '{$subject}', '{$content}', '{$dateclientreq}');";
							
				sql_query($sql);
				
				$sql = "select max(mseq) from msg_queue;";
				$max_smt_obj = sql_fetch_emma($this->EmmaDb, $sql);
				$max_smt = $max_smt_obj['max(mseq)'];		
				
				if (($wr_type == 0)||($wr_type == 1)||($wr_type == 2)) {                                // short_url 생성                         
					sql_query("update sms5_history set hs_message ='{$content}',hs_lurl = '{$mmlong_url}',hs_surl='{$S_url_nm}', hs_mt_pr = '{$max_smt}', mb_id = '{$lmsvalue}' where hs_no='{$hs_no}' ");                       
				} else {
					sql_query("update sms5_history set hs_message ='{$content}', hs_mt_pr = '{$max_smt}', mb_id = '{$lmsvalue}' where hs_no='{$hs_no}' ");                                               
				} 			
			}
			else {	// SMS						
				if ($dateclientreq == "")
					$sql = "insert into msg_queue (msg_type, dstaddr, callback, stat, text, request_time) values".
						   "('1', '{$phone}', '{$callback}', '0', '{$content}', sysdate());";
				else
					$sql = "insert into msg_queue (msg_type, dstaddr, callback, stat, text, request_time) values".
						   "('1', '{$phone}', '{$callback}', '0', '{$content}', '{$dateclientreq}');";
							
				sql_query($sql);
				
				$sql = "select max(mseq) from msg_queue;";
				$max_smt_obj = sql_fetch_emma($this->EmmaDb, $sql);
				$max_smt = $max_smt_obj['max(mseq)'];		
				
				if (($wr_type == 0)||($wr_type == 1)||($wr_type == 2)) {                                // short_url 생성                         
					sql_query("update sms5_history set hs_message ='{$content}',hs_lurl = '{$mmlong_url}',hs_surl='{$S_url_nm}', hs_mt_pr = '{$max_smt}', mb_id = '{$lmsvalue}' where hs_no='{$hs_no}' ");                       
				} else {
					sql_query("update sms5_history set hs_message ='{$content}', hs_mt_pr = '{$max_smt}', mb_id = '{$lmsvalue}' where hs_no='{$hs_no}' ");                                               
				} 			
			}														
			$index++;			
		}			
		
		if (($wr_type == 0)||($wr_type == 1)||($wr_type == 2)) {                 	
     	    sql_query("update {$g5['sms5_write_table']} set wr_message = concat(wr_message,' ','{$edoc_surl}') where wr_no='{$epls_wrno}' ");
		}
		
		$this->Data = "";
		return $index;
	}
}
?>