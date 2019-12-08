<?php
include_once("../common.php");
if ($is_guest) {   
        alert('로그인 후 사용하십시오!.', G5_URL);
}

 if (!$confirm){
    if ($edu_kind == 1){
            $book_qry_text  = "insert into edoc_variable (edcv_mbno,edcv_grid,edcv_udoc,edcv_ccnt,edcv_name,edcv_hp,edcv_var,edcv_time,edcv_check)";                    
            $book_qry_text .= "select mb_no,ed_emid,'{$doc_ukey}','-1',ed_name,bk_hp,ed_var,now(),'{$edcv_check}' ";
            $book_qry_text .= " from edufine_bill,sms5_book ";
            $book_qry_text .=  "where ed_emid='{$ek}' and mb_no = '{$member['mb_no']}' and ed_year = bk_year and ed_grade = bk_grade ";
            $book_qry_text .= " and ed_class = bk_class and ed_stid = bk_stid and bk_kind = 1";
    } else if ($edu_kind == 2){
            $book_qry_text  = "insert into edoc_variable (edcv_mbno,edcv_grid,edcv_udoc,edcv_ccnt,edcv_name,edcv_hp,edcv_var,edcv_time,edcv_check)";                    
            $book_qry_text .= "select mb_no,ed_emid,'{$doc_ukey}','-1',ed_name,bk_hp,ed_var,now(),'{$edcv_check}' ";
            $book_qry_text .= " from edufine_bill,sms5_book ";
            $book_qry_text .=  "where ed_emid='{$ek}' and mb_no = '{$member['mb_no']}' and ed_year = bk_year and ed_grade = bk_grade ";
            $book_qry_text .= " and ed_class = bk_class and ed_stid = bk_stid and bk_kind = 2";
    } else if ($edu_kind == 3){
            $book_qry_text  = "insert into edoc_variable (edcv_mbno,edcv_grid,edcv_udoc,edcv_ccnt,edcv_name,edcv_hp,edcv_var,edcv_time,edcv_check)";                    
            $book_qry_text .= "select mb_no,ed_emid,'{$doc_ukey}','-1',ed_name,bk_hp,ed_var,now(),'{$edcv_check}' ";
            $book_qry_text .= " from edufine_bill,sms5_book ";
            $book_qry_text .=  "where ed_emid='{$ek}' and mb_no = '{$member['mb_no']}' and ed_year = bk_year and ed_grade = bk_grade ";
            $book_qry_text .= " and ed_class = bk_class and ed_stid = bk_stid ";                
    } else {// 에듀파인 자체 전화번호 
            $book_qry_text  = "insert into edoc_variable (edcv_mbno,edcv_grid,edcv_udoc,edcv_ccnt,edcv_name,edcv_hp,edcv_var,edcv_time,edcv_check)";                    
            $book_qry_text .= "select em_mbno,ed_emid,em_udoc,'-1',ed_name,ed_phone,ed_var,now(),'{$edcv_check}' ";
            $book_qry_text .= " from edufine_bill ,edocvar_master ";
            $book_qry_text .=  "where ed_emid='{$ek}' and em_ukey = ed_emid and em_mbno = '{$member['mb_no']}' and em_udoc = '{$doc_ukey}' and ed_gubn = 7 and ed_phone is not null and ed_phone <> '' ";
    }
    sql_query($book_qry_text);            

    $chkrow = sql_fetch(" select count(*) as scnt from edoc_variable where edcv_mbno = '{$member['mb_no']}' and edcv_grid = '{$ek}' ");  
    if ($chkrow['scnt']) {  
        $scnt = $chkrow['scnt'];
    } else {
        $scnt = 0;
    }
?>
<script>
    var info = parent.document.getElementById('data_file_confirm_pan');    
    var html = '';
    html += "<div id='upload_result'><span class='sms5_txt_success'>총 <?=number_format($scnt)?> 건의 휴대폰번호 및 데이터 등록을 완료하였습니다.</span>";    
    html += '<input name = "btn_var_preeview" id="btn_var_preeview" type="submit" value="미리보기" class="btn_submit" onclick="btn_var_preeview_click();">';
    parent.document.getElementById("varform").value = "set";    
    html += "</div>";
    parent.document.getElementById("file_up_loading").style.display = "none";    
    parent.document.getElementById('data_file_up_pan').style.display = 'none';
    parent.document.getElementById("ed_mnid").value = "<?=$ek?>";                 
    info.style.display = 'block';
    info.innerHTML = html;
</script>
<?php    
    exit();
}
function get_cut_one_phone_number($target_phone){
    if ($target_phone == '') {return '';}
    $rtnphone = str_replace('-', '', trim($target_phone));
    $rtnphone = str_replace('.', '', trim($rtnphone));        
    $rtnphone = str_replace('(', '', trim($rtnphone));
    $rtnphone = str_replace(')', '', trim($rtnphone));
    $rtnphone = str_replace('[', '', trim($rtnphone));
    $rtnphone = str_replace(']', '', trim($rtnphone));      
    $rtnarr = '';
    if (preg_match("/^(01[016789])([0-9]{3,4})([0-9]{4})/", $rtnphone,$rtnarr )) {
        $rtnphone = preg_replace("/^(01[016789])([0-9]{3,4})([0-9]{4})$/", "$1-$2-$3", $rtnarr[0]);
        return $rtnphone;
    } else {
        return '';
    }
}

function get_excel_col_num($countCol){
        $wonVal = ($countCol - ($countCol % 26)) / 26;  
        $modVal = $countCol % 26;
        if ($wonVal == 0){
        $excelCol = Get_AlphaNum($countCol);            
        } else {
            if ($modVal == 0) {
                $excelCol = Get_AlphaNum($wonVal-1).'Z';
            } else {
                $excelCol = Get_AlphaNum($wonVal).Get_AlphaNum($modVal);    
            }       
        }
        return $excelCol;
}

function Get_AlphaNum($alphaNum){
    $excelColArr = array('','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    return $excelColArr[$alphaNum] ;
}

function get_student_number($wtext){
	$rtnval['success'] = false;
	if ($wtext == '') {return $rtnval;}	
	$patt_grade = '/[0-9]+번/';
	$patt_digit   = '/[0-9]+/';
	$parsTxt = '';
	if (preg_match($patt_grade, $wtext,$parsTxt) > 0) {
		$wwtext = $parsTxt[0];
		if (preg_match($patt_digit, $wwtext,$parsTxt) >0 ){
			$rtnval['success'] = true;
			$rtnval['num'] = $parsTxt[0];
		}
	}	
	return $rtnval;
}

function get_grade($wtext){
	$returnVal = '';
	if ($wtext == '') {return $returnVal;}	
	$patt_grade = '/_[0-9]+학년/';
	$patt_digit   = '/[0-9]+/';
	if (preg_match($patt_grade, $wtext,$rtnval) > 0) {
		$wwtext = $rtnval[0];
		if (preg_match($patt_digit, $wwtext,$rtnval) >0 ){
			$returnVal = $rtnval[0];
		}
	}	
	return $returnVal;
}

function get_class_ban($wtext){
	$returnVal = '';
	if ($wtext == '') {return $returnVal;}	
	$patt_grade = '/_[0-9]+반/';
	$patt_digit   = '/[0-9]+/';
	if (preg_match($patt_grade, $wtext,$rtnval) > 0) {
		$wwtext = $rtnval[0];
		if (preg_match($patt_digit, $wwtext,$rtnval) >0 ){
			$returnVal = $rtnval[0];
		}
	}	
	return $returnVal;
}

function get_grade_calss($ttext){
	$rtnval['success'] = false;
	if ($ttext == '') {return $rtnval;}	
          $grade = get_grade($ttext);
          if ($grade == '') {return $rtnval;}	
          $class = get_class_ban($ttext);
          if ($class =='') {return $rtnval;}
          $rtnval['success'] = true;	
          $rtnval['grade'] = $grade;          
          $rtnval['class'] = $class;
          return $rtnval;
}
?>
<head>
 <style type="text/css">
 <!--
 ul, li {list-style: none;font-size:12px;font-family:dotum;margin: 0; padding: 0;}
 h2 {margin: 0; padding: 0; border: 0;font-size:12px;font-family:dotum;font-weight: 700;}
#fail_info{position: relative;float:left;margin:5px;background:#dfdfdf; padding:15px;}
#dup_info{position: relative;float:left;margin:5px;background:#dfdfdf;padding:15px}
 //-->
 </style>
 <script src="<?php echo G5_JS_URL ?>/jquery-1.8.3.min.js"></script>
</head>
<?

if (!$_FILES['goji_up_file']['size']) 
    alert_after('파일을 선택해주세요.');

$tmpfile = $_FILES['goji_up_file']['tmp_name'];
$filename = $_FILES['goji_up_file']['name'];
$filesize  = $_FILES['goji_up_file']['size'];
$filename  = get_safe_filename($filename);

// 서버에 설정된 값보다 큰파일을 업로드 한다면
if ($filename) {
    if ($_FILES['goji_up_file']['error'] == 1) {
        $file_upload_msg .= '\"'.$filename.'\" 파일의 용량이 서버에 설정('.$upload_max_filesize.')된 값보다 크므로 업로드 할 수 없습니다.\\n';
alert_after($file_upload_msg);
    }
    else if ($_FILES['goji_up_file']['error'] != 0) {
        $file_upload_msg .= '\"'.$filename.'\" 파일이 정상적으로 업로드 되지 않았습니다.\\n';
alert_after($file_upload_msg);
    }
}

$pos = strrpos($filename, '.');
$ext = strtolower(substr($filename, $pos, strlen($filename)));
$rtnArr = array();
$excelColCnt = 17;
switch ($ext) {
    case '.xlsx' :        
        include_once(G5_LIB_PATH.'/PHPExcel/Classes/PHPExcel.php');
        //$varCount
        //ABCDEFGHIJKLMNOP
        class Ele_excel_xlsx_ReadFilter implements PHPExcel_Reader_IReadFilter
        {
            public function readCell($column, $row, $worksheetName = '') {
                // Read rows 1 to 7 and columns A to E only
                if (in_array($column,range('A','Q'))) {
                    return true;
                }
                return false;
            }
        }
        $filterReader = new Ele_excel_xlsx_ReadFilter();
        $extF_Type = 'Excel2007';
        if($ext == "xls") {
            $extF_Type = 'Excel5';  
        }
        $objReader = PHPExcel_IOFactory::createReader($extF_Type);
        $objReader->setReadDataOnly(true);  
        $objReader->setReadFilter($filterReader);
        $objPHPExcel = $objReader->load($tmpfile);
        $objPHPExcel->setActiveSheetIndex(0);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
        $num_rows = count($sheetData);
        break;
    case '.xls' :
        include_once(G5_LIB_PATH.'/Excel/reader.php');
        $data = new Spreadsheet_Excel_Reader();

        // Set output Encoding.
        $data->setOutputEncoding('UTF-8');
        $data->read($tmpfile);
        $num_rows = $data->sheets[0]['numRows'];
        break;

    default :
        alert_after('엑셀 파일만 허용합니다.');
}

$encode = array('ASCII','UTF-8','EUC-KR');
for ($i = 1; $i <= $num_rows; $i++) {
    $counter++;
    $j = 1;
    switch ($ext) {
        case '.xlsx' :                        
            for ($cdx=1;$cdx<=$excelColCnt;$cdx++){
                $tmpExcelcol = get_excel_col_num($cdx);
                $tmpTxt = addslashes($sheetData[$i][$tmpExcelcol]);
                $str_encode = @mb_detect_encoding($tmpTxt, $encode);
                if( $str_encode == "EUC-KR" ){
                    $tmpTxt = iconv_utf8( $tmpTxt );
                }                                        
                $tmpArr[$cdx-1] = $tmpTxt;
            }            
            array_push($rtnArr, $tmpArr); 
            break;
        case '.xls' :
	$tmpArr = array();
	for ($cdx=1;$cdx<=$excelColCnt;$cdx++){                
                $tmpTxt = addslashes($data->sheets[0]['cells'][$i][$cdx]);
                $str_encode = @mb_detect_encoding($tmpTxt, $encode);
                if( $str_encode == "EUC-KR" ){
                    $tmpTxt = iconv_utf8( $tmpTxt );
                }                                        
                $tmpArr[$cdx-1] = $tmpTxt;
            }            
            array_push($rtnArr, $tmpArr);
            break;
    }
}

if (is_uploaded_file($tmpfile)) {
        // 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
        $chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));
        shuffle($chars_array);
        $shuffle = implode('', $chars_array);

        // 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다. (길상여의 님 090925)
        $lower_case_name = strtolower($filename);
        $upload_file = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.str_replace('%', '', urlencode(str_replace(' ', '_', $lower_case_name)));

        $dest_file = G5_DATA_PATH.'/file/billpaper/'.$upload_file;
        // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
        $error_code = move_uploaded_file($tmpfile, $dest_file) or die('error!!!');
        // 올라간 파일의 퍼미션을 변경합니다.
        chmod($dest_file, G5_FILE_PERMISSION);
 }

//$rowcnt = count($rtnArr);
$class_start = false; 
$txt_grade = '';
$txt_class = '';
$student_flag = false;
$txt_st_num = '';
$txt_st_name = '';
$bill_arr = array();
$student_info_arr = array();
$s_bill_row = array();
$s_bill_arr = array();
foreach ($rtnArr as $num => $data) {
            if (($data[6] == "") && ($data[7] == "")){
                continue;
            } else if (($data[6] == "납입금구분") && ($data[7] == "차수")){
                continue;                    
            } else if ($data[4] == "미 납 자 현 황 (개인별)"){                    // 페이지 여백 
                continue;                    
            } else if ($data[13] == "발행일 :"){                    // 페이지 여백 
                continue;                                        
            }                
            
	$rtnval = get_grade_calss($data[0]);
	if($rtnval['success']){
		$class_start = true; 
		$txt_grade = $rtnval['grade'];
		$txt_class =  $rtnval['class'];		
        if ($data[6] == '반 합 계') {// 반합계만 별도 장으로 들어가는 경우 오류...2015.11.03
            $class_start = false; 
            $txt_grade = '';
            $txt_class = '';
        }
	} else if ($data[6] == '반 합 계') {
		$class_start = false; 
		$txt_grade = '';
		$txt_class = '';
	} 
	if ($class_start == false ) { continue;}
	$rtnval = get_student_number($data[1]);
	if($rtnval['success']){
        if ($data[6] == '소    계') {// 소계만 별도 장으로 들어가는 경우 오류 2016.09.21
            $student_flag = false; 
            $data[6] = 's'; 
        }  else {
                $student_flag = true; 
                $txt_st_num = $rtnval['num'];
                $txt_st_name = $data[2];
                $student_info_arr['g'] = $txt_grade;
                $student_info_arr['c'] = $txt_class;
                $student_info_arr['i'] = $txt_st_num;
                $student_info_arr['n'] = urlencode($txt_st_name);                     
                $student_info_arr['p'] = get_cut_one_phone_number($data[12]);
        }	
	} else if ($data[6] == '소    계') {
		$student_flag = false; 
		$data[6] = 's';	
	} else if ($student_flag == true){
                if (($data[6] == "") && ($data[7] == "")){
                    continue;
                } else if (($data[6] == "납입금구분") && ($data[7] == "차수")){
                    continue;                    
                } else if ($data[13] == "발행일 :"){                    // 페이지 여백 
                    continue;                                        
                }                
           }
	$sub_title    = $data[6];	
	$sub_chasu = $data[7];
           $link_money      = number_format($data[10]);
           $s_bill_row['t'] = urlencode($sub_title);
           $s_bill_row['s'] = urlencode($sub_chasu);
           $s_bill_row['m'] = $link_money;
	array_push($s_bill_arr,$s_bill_row);           		
	if ($student_flag == false) {
		$student_info_arr['l'] = $s_bill_arr;           	
		array_push($bill_arr, $student_info_arr);
		$txt_st_num = '';
		$txt_st_name = '';	
		unset($s_bill_arr);
		$s_bill_arr = array();
	}
}
        $Rcnt   = count($bill_arr);
        if  ($Rcnt > 0) {
            $sql = " insert into edocvar_master (em_udoc,em_mbno,em_data_file,em_tmp_file,em_scnt,em_type,em_time) values ".
                               "('{$doc_ukey}','{$member['mb_no']}','{$filename}','{$upload_file}','{$Rcnt}',-1,now())";
            sql_query($sql);
            $em_ukey = mysql_insert_id();
            foreach ($bill_arr as $num => $bill_data) {
                    $json_data = json_encode($bill_data);
                    $wonname = urldecode($bill_data['n']);
                    $sql = " insert into edufine_bill (ed_emid,ed_year,ed_grade,ed_class,ed_stid,ed_name,ed_var,ed_time,ed_phone,ed_gubn)";
                    $sql .=  "values ('{$em_ukey}','{$edu_year}','{$bill_data['g']}','{$bill_data['c']}','{$bill_data['i']}','{$wonname}','{$json_data}',now(),'{$bill_data['p']}','{$edu_kind}' );";
                    sql_query($sql);                
            }
            if ($edu_kind == 1){
                    $book_qry_text = "select '1' as etype,edufine_bill.*,sms5_book.* from edufine_bill left outer join sms5_book on mb_no = '{$member['mb_no']}' and ed_year = bk_year and ed_grade = bk_grade and ed_class = bk_class and ed_stid = bk_stid and bk_kind = 1 where ed_emid='{$em_ukey}' ";
            } else if ($edu_kind == 2){
                    $book_qry_text = "select '2' as etype,edufine_bill.*,sms5_book.* from edufine_bill left outer join sms5_book on  mb_no = '{$member['mb_no']}' and ed_year = bk_year and ed_grade = bk_grade and ed_class = bk_class and ed_stid = bk_stid and bk_kind = 2 where ed_emid='{$em_ukey}' ";
            } else if ($edu_kind == 3){
                    $book_qry_text = "select '1' as etype,edufine_bill.*,sms5_book.* from edufine_bill left outer join sms5_book on  mb_no = '{$member['mb_no']}' and ed_year = bk_year and ed_grade = bk_grade and ed_class = bk_class and ed_stid = bk_stid and bk_kind = 1 where ed_emid='{$em_ukey}' ";                
                    $book_qry_text .= ' Union ';
                    $book_qry_text .= "select '2' as etype,edufine_bill.*,sms5_book.* from edufine_bill left outer join sms5_book on  mb_no = '{$member['mb_no']}' and ed_year = bk_year and ed_grade = bk_grade and ed_class = bk_class and ed_stid = bk_stid and bk_kind = 2 where ed_emid='{$em_ukey}' ";                
            } else {
                    $book_qry_text .= "select '7' as etype,edufine_bill.*,ed_phone as bk_no ";
                    $book_qry_text .= " from edufine_bill ,edocvar_master ";
                    $book_qry_text .=  "where ed_emid='{$em_ukey}' and em_ukey = ed_emid and em_mbno = '{$member['mb_no']}' and em_udoc = '{$doc_ukey}' and ed_gubn = 7 ";                    
            }   
            $book_qry = sql_query($book_qry_text);            

            $totalcnt = 0;  
            $success_cnt = 0;   
            $fail_cnt = 0;
            $err_msg_txt = '';
            while ($book_row = sql_fetch_array($book_qry)){
                if ($book_row['bk_no']){
                        $success_cnt++;
                } else {
                        $fail_cnt++;
                        if ($edu_kind == 1){
                                $err_msg_txt .= "<li>".$book_row['ed_grade'].'학년 '.$book_row['ed_class'].'반 '.$book_row['ed_stid'].'번 '.$book_row['ed_name']." 학생 전화번호</li>";
                        } else if ($edu_kind == 2){
                                $err_msg_txt .= "<li>".$book_row['ed_grade'].'학년 '.$book_row['ed_class'].'반 '.$book_row['ed_stid'].'번 '.$book_row['ed_name']." 학부모 전화번호</li>";
                        } else if ($edu_kind == 3){
                            if ($book_row['etype'] == 1){
                                $err_msg_txt .= "<li>".$book_row['ed_grade'].'학년 '.$book_row['ed_class'].'반 '.$book_row['ed_stid'].'번 '.$book_row['ed_name']." 학부모 전화번호</li>";
                            } else {
                                $err_msg_txt .= "<li>".$book_row['ed_grade'].'학년 '.$book_row['ed_class'].'반 '.$book_row['ed_stid'].'번 '.$book_row['ed_name']." 학생 전화번호</li>";
                            }                            
                        } else {
                            $err_msg_txt .= "<li>".$book_row['ed_grade'].'학년 '.$book_row['ed_class'].'반 '.$book_row['ed_stid'].'번 '.$book_row['ed_name']." 에듀파인 전화번호</li>";                            
                        }                            
                }                
                $totalcnt++;                
            }
        }
        if ($edu_kind == 1){
                $sendCnt = '(학부모)';
        } else if ($edu_kind == 2){  
                $sendCnt = '(학생)';              
        } else if ($edu_kind == 3){  
                $sendCnt = '*2(학부모+학생)';
        } else {
                $sendCnt = '(에듀파인)';
        }
?>
<script>
    var info = parent.document.getElementById('data_file_confirm_pan');       
    var html = '';
    html += "<div id='upload_result'><span>총 대상 : <?=number_format($Rcnt)?><?=$sendCnt?> 건</span>&nbsp;";    
<?php    
if ($success_cnt > 0) {
?>        
        html += "<span class='sms5_txt_success'>등록가능 : <?=number_format($success_cnt)?> 건</span>";
<?php    
if ($fail_cnt > 0) {
        $err_msg_txt = "<div id = 'fail_info'><ul><li><h2>미등록 목록</h2></li>".$err_msg_txt."</ul></div>";
?>        
        html += "<span class='sms5_txt_fail'>등록불가.. : <?=number_format($fail_cnt)?> 건</span>&nbsp;";
<?php    
}
?>                
        html += "<span>&nbsp;&nbsp;&nbsp;</span><button type='button' id='btn_fileup' class='btnT1' onclick='dataup(<?=$em_ukey?>)'>등록하기..</button>";
<?php            
} else {
?>
     html += "<br><span class='sms5_txt_fail'>등록할 수 없습니다.</span>";
<?php    
}   
?>
    html += "</div>";
    html += "<?php echo $err_msg_txt; ?>";

    parent.document.getElementById("file_up_loading").style.display = "none";    
    parent.document.getElementById('data_file_up_pan').style.display = 'none';  
    $('#edufiine_select', parent.document).css("display","none");  
    info.style.display = 'block';
    info.innerHTML = html;
</script>
<?php    
function alert_after($str) {
    echo "<script>
    //parent.document.getElementById('upload_bg_no').style.display = 'block';
    //parent.document.getElementById('bgno_label').innerHTML = '그룹선택';    
    //parent.document.getElementById('upload_button').style.display = 'inline';
    //parent.document.getElementById('uploading').style.display = 'none';
    //parent.document.getElementById('register').style.display = 'none';
    //parent.document.getElementById('upload_info').style.display = 'none';
    </script>";    
    alert_just($str);
}
?>