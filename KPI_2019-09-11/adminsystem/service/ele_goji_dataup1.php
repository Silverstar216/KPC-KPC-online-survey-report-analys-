<?php
//echo '$ExcelColNumber'.$ExcelColNumber;exit;
include_once("../common.php");

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
</head>
<?

if ($_FILES['goji_up_file']['size']) 
    alert_after('파일을 선택해주세요.');

$file = $_FILES['goji_up_file']['tmp_name'];
$filename = $_FILES['goji_up_file']['name'];

$pos = strrpos($filename, '.');
$ext = strtolower(substr($filename, $pos, strlen($filename)));
$excelColCnt = $varCount+2;
$ExcelColNumber = get_excel_col_num($excelColCnt);



switch ($ext) {
    case '.csv' :
        $data = file($file);
        $num_rows = count($data) + 1;
        $csv = array();
        foreach ($data as $item) 
        {
            $item = explode(',', $item);

            $item[1] = get_hp($item[1]);

            array_push($csv, $item);

            if (count($item) < 3) 
                alert_after('올바른 파일이 아닙니다.');
        }
        break;
    case '.xlsx' :        
        include_once(G5_LIB_PATH.'/PHPExcel/Classes/PHPExcel.php');
        //$varCount
        //ABCDEFGHIJKLMNOP
        class Ele_excel_xlsx_ReadFilter implements PHPExcel_Reader_IReadFilter
        {
            public function readCell($column, $row, $worksheetName = '') {
                // Read rows 1 to 7 and columns A to E only
                if (in_array($column,range('A',$ExcelColNumber))) {
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
        $objPHPExcel = $objReader->load($file);
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
        $data->read($file);
        $num_rows = $data->sheets[0]['numRows'];
        break;

    default :
        alert_after('xls파일과 csv파일만 허용합니다.');
}

$counter = 0;
$success = 0;
$failure = 0;
$arr_hp = array();
$fail_hp = array();
$succ_hp = array();
$encode = array('ASCII','UTF-8','EUC-KR');
$won_txt = '';

if (!$confirm){    
    $em_ukey  = $ek;
} else {
    $sql = " insert into edocvar_master (em_udoc,em_mbno,em_data_file,em_tmp_file,em_scnt,em_type,em_time) values('{$doc_ukey}','{$member['mb_no']}','{$filename}','{$filename}','{$varCount}','{$varCount}',now())";
    sql_query($sql);
    $em_ukey = mysql_insert_id();
}

for ($i = 1; $i <= $num_rows; $i++) {
    $counter++;
    $j = 1;
    $eleVa = '';
    switch ($ext) {
        case '.csv' :
            $name = $csv[$i][0];
            $str_encode = @mb_detect_encoding($name, $encode);
            if( $str_encode == "EUC-KR" ){
                $name = iconv_utf8( $name );
            }
            $name = addslashes($name);
            $hp   = addslashes($csv[$i][1]);
            $eleVar = addslashes($csv[$i][2]);
            for ($cdx=3;$cdx<=$excelColCnt;$cdx++){
                $eleVar .= '|'.addslashes($csv[$i][$cdx]);
            }            
            break;
        case '.xlsx' :            
            $won_txt = $sheetData[$i]['A'];     
            $name = addslashes($sheetData[$i]['A']);
            $str_encode = @mb_detect_encoding($name, $encode);
            if( $str_encode == "EUC-KR" ){
                $name = iconv_utf8( $name );
            }
            $won_txt = $won_txt.' : '.$sheetData[$i]['B'];            
            $hp   = addslashes(get_hp($sheetData[$i]['B']));        

            $tmpTxt = addslashes($sheetData[$i]['C']);
            $str_encode = @mb_detect_encoding($tmpTxt, $encode);
            if( $str_encode == "EUC-KR" ){
                $tmpTxt = iconv_utf8( $tmpTxt );
            }                        
            $eleVar = $tmpTxt;
            for ($cdx=4;$cdx<=$excelColCnt;$cdx++){
                $tmpExcelcol = get_excel_col_num($cdx);
                $tmpTxt = addslashes($sheetData[$i][$tmpExcelcol]);
                $str_encode = @mb_detect_encoding($tmpTxt, $encode);
                if( $str_encode == "EUC-KR" ){
                    $tmpTxt = iconv_utf8( $tmpTxt );
                }                                        
                $eleVar .= '|'.$tmpTxt ;
            }            
            break;
        case '.xls' :
            $won_txt = $data->sheets[0]['cells'][$i][$j];        
            $name = addslashes($data->sheets[0]['cells'][$i][$j++]);
            $str_encode = @mb_detect_encoding($name, $encode);
            if( $str_encode == "EUC-KR" ){
                $name = iconv_utf8( $name );
            }
            $won_txt = $won_txt.' : '.$data->sheets[0]['cells'][$i][$j];            
            $hp   = addslashes(get_hp($data->sheets[0]['cells'][$i][$j++]));

            $tmpTxt = addslashes($data->sheets[0]['cells'][$i][3]);
            $str_encode = @mb_detect_encoding($tmpTxt, $encode);
            if( $str_encode == "EUC-KR" ){
                $tmpTxt = iconv_utf8( $tmpTxt );
            }                        
            $eleVar = $tmpTxt;
            for ($cdx=4;$cdx<=$excelColCnt;$cdx++){
                $tmpTxt = addslashes($data->sheets[0]['cells'][$i][$cdx]);
                $str_encode = @mb_detect_encoding($tmpTxt, $encode);
                if( $str_encode == "EUC-KR" ){
                    $tmpTxt = iconv_utf8( $tmpTxt );
                }                                        
                $eleVar .= '|'.$tmpTxt ;
            }            

            break;
    }
    if (!(strlen($name)&&$hp))
    {
        $failure++;
        array_push($fail_hp, $won_txt.'==>'.$hp);    
    } else {    
            array_push($arr_hp, $hp);

            if (!$confirm && $hp) 
            {
                array_push($succ_hp, $won_txt);                                                

                $sqlstr = "insert into edoc_variable set edcv_grid = '{$em_ukey}', ".
"edcv_mbno = '{$member['mb_no']}',edcv_udoc= '{$doc_ukey}',edcv_ccnt = '{$varCount}',edcv_name = '".
addslashes($name)."',edcv_hp = '$hp',edcv_var = '{$eleVar}',edcv_check='{$edcv_check}',edcv_time = '".G5_TIME_YMDHIS."' ";
                sql_query($sqlstr);
                $success++;
            }
    }
}

unlink($_FILES['goji_up_file']['tmp_name']);

$result = $counter - $failure;
$process_flag = 0;
if ($result)
{
    if ($confirm) {
        // 등록가능 
        $process_flag = 3;
    }
    else{
        // 등록 완료 
        $process_flag = 7;
    }
} 
else {
    // 등록 불가 
    $process_flag = -1;    
}
?>
<script>
    var info = parent.document.getElementById('data_file_confirm_pan');    
    var html = '';
    html += "<div id='upload_result'><span>대상건수 : <?=number_format($counter)?> 건</span>&nbsp;<br><br>";
    html += "<span class='sms5_txt_fail'>등록불가 : <?=number_format($failure)?> 건</span>&nbsp;<br>";
<?php    
if ($process_flag == 3) {
?>        
        html += "<span class='sms5_txt_success'>등록가능 : <?=number_format($result)?> 건</span>";
        html += "<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><button type='button' id='btn_fileup' class='btnT1' onclick='dataup(<?=$em_ukey?>)'>등록하기</button><br><br><br><br>";        
<?php            
} else if ($process_flag == 7) {
?>
        html += "<br><span class='sms5_txt_success'>총 <?=number_format($success)?> 건의 휴대폰번호 및 데이터 등록을 완료하였습니다.</span>";    
        html += '<input name = "btn_var_preeview" id="btn_var_preeview" type="submit" value="미리보기" class="btn_submit" onclick="btn_var_preeview_click();">';                   
        parent.document.getElementById("ed_mnid").value = "<?=$em_ukey?>";                 
        parent.document.getElementById("varform").value = "set";    
<?php        
} else {
?>
     html += "<br><span class='sms5_txt_fail'>등록할 수 없습니다.</span>";
<?php    
}

$err_msg_txt = '';
if ($failure > 0){
$err_msg_txt .= "<div id = 'fail_info'>";     
$err_msg_txt .= "<ul>";            
$err_msg_txt .= "<li><h2>오류번호 목록</h2></li>";
    for ($i=0; $i<count($fail_hp); $i++){
$err_msg_txt .= "<li>".$fail_hp[$i]."</li>";
    }
$err_msg_txt .= "</ul></div>";        
}
?>
    html += "</div>";
    html += "<?php echo $err_msg_txt; ?>";
    parent.document.getElementById("file_up_loading").style.display = "none";    
    parent.document.getElementById('data_file_up_pan').style.display = 'none';
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