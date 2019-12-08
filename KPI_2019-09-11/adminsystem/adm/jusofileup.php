<?php
$sub_menu = "300100";
include_once('./_common.php');
auth_check($auth[$sub_menu], 'w');
 

function check_gr_dupfind($grnm){
    $rtngrNum = -1;
    $inArrflag   = false;           
    $tmparr = array();
    global $group_arr;
    for($idx=0;$idx<count($group_arr);$idx++){
        if ($group_arr[$idx][0] == $grnm){// 배열에 이미 존재.
                   $inArrflag  = true;      
                   if ($group_arr[$idx][1] == -1)   {
                $rtngrNum = -999;
                   } else {
                    $rtngrNum = $group_arr[$idx][1];
                   }
            
            break;
        }
    }
    if ($inArrflag == false){
        //처음 보는 그룹이다. 
        // DB에 있는지 찾는다. 있으면 아이디를 [1]에 넣는다. 
        $grId = check_in_DB($grnm);
        $tmparr[0] = $grnm;
        $tmparr[1] = $grId;
        array_push($group_arr, $tmparr);
    }
    return $rtngrNum;
}

function check_in_DB($grnm){
    sql_query("update {$g5['sms5_book_group_table']} set bg_count = ".$total['cnt']." where bg_no='$bg_no'");
    return -1;
}

function is_it_Indup_hp($chp,$grnm){
    return false;
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
if(!$gk_no) {
    alert_after('사용자를 선택해주세요.');
}
if (!$_FILES['csv']['size']) 
    alert_after('파일을 선택해주세요.');

$file = $_FILES['csv']['tmp_name'];
$filename = $_FILES['csv']['name'];

$pos = strrpos($filename, '.');
$ext = strtolower(substr($filename, $pos, strlen($filename)));

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

            if (count($item) < 2) 
                alert_after('올바른 파일이 아닙니다.');
        }
        break;
    case '.xlsx' :        
        include_once(G5_LIB_PATH.'/PHPExcel/Classes/PHPExcel.php');
        class Ele_excel_xlsx_ReadFilter implements PHPExcel_Reader_IReadFilter
        {
            public function readCell($column, $row, $worksheetName = '') {
                // Read rows 1 to 7 and columns A to E only
                if (in_array($column,range('A','C'))) {
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
$inner_overlap = 0;
$overlap = 0;

$group_arr = array();
$data_grep = array();

for ($i = 1; $i <= $num_rows; $i++) {
    $counter++;
    $j = 1;
    $vRowNum     = $i;
    switch ($ext) {
        case '.csv' :
            $wontxt1 = $csv[$i][0];
            $wontxt2 = $csv[$i][1];
            $wontxt3 = $csv[$i][1];        
            $name = $csv[$i][0];
            $str_encode = @mb_detect_encoding($name, $encode);
            if( $str_encode == "EUC-KR" ){
                $name = iconv_utf8( $name );
            }
            $name           = addslashes($name);
            $hp                = addslashes($csv[$i][1]);
            $group_name = addslashes($csv[$i][2]);
            break;
        case '.xlsx' :            
            $wontxt1 = $sheetData[$i]['A'];     
            $wontxt2 = $sheetData[$i]['B'];     
            $wontxt3 = $sheetData[$i]['C'];     
            $name = addslashes($sheetData[$i]['A']);
            $str_encode = @mb_detect_encoding($name, $encode);
            if( $str_encode == "EUC-KR" ){
                $name = iconv_utf8( $name );
            }
            $hp   = addslashes(get_hp($sheetData[$i]['B']));        
            $group_name = addslashes(get_hp($sheetData[$i]['C']));        
            break;
        case '.xls' :
            $wontxt1 = $data->sheets[0]['cells'][$i][$j];        
            $name = addslashes($data->sheets[0]['cells'][$i][$j++]);
            $str_encode = @mb_detect_encoding($name, $encode);
            if( $str_encode == "EUC-KR" ){
                $name = iconv_utf8( $name );
            }
            $won_txt = $won_txt.' : '.$data->sheets[0]['cells'][$i][$j];            
            $wontxt2 = $data->sheets[0]['cells'][$i][$j++];                    
            $hp   = addslashes(get_hp($wontxt2));
            $wontxt3 = $data->sheets[0]['cells'][$i][$j];                    
            $group_name = addslashes($wontxt3);        
            break;
    }
    if (!(strlen($name)&&$hp))// 이름, 전화번호 둘다 없으면...실패 
    {
        $failure++;
        $cflag    = 'E';
        $errorflag = 'E';
        $thisRecord = array($wontxt1,$wontxt2,$wontxt3,$cflag);
        array_push($data_grep, $thisRecord);                             
    } else {
/*   체크 시작 */       
        $errorflag = '';
        $grNum = check_gr_dupfind($group_name);// 그룹이 기존에 존재 하는 건지 아닌지 찾는다. 
        if ($grNum > 0) {
            $cflag    = 'O';
        } else if ($grNum == -1) {
            $cflag    = 'N';    
        } else {
            $cflag    = 'I';    
        }
        //$cflag    = 'N' :신규해야 하는거, 'I' : 이번 파일에 신규인데 중복, 'O' : 이미 DB에 있는거...
        if ($cflag == 'O'){
            if (is_it_dup_hp($hp) == true) {
                      $inner_overlap++;
                      $errorflag = 'D';
                   }
        }
        if ($errorflag == ''){// 에러가 없으면 내부 중복여부를 체크한다. 
            if (is_it_Indup_hp($hp,$group_name) == true) {
                      $errorflag = 'I'; // 내부 그룹 중복 핸드폰
                      
                   }
        }
        $thisRecord = array($name,$hp,$group_name,$cflag);
        array_push($data_grep, $thisRecord);                     

//if그룹안 중복 여부 체크  
//else 
//그룹리스트에 넣고.
        if (in_array($hp, $arr_hp))
        {
            $inner_overlap++;
            array_push($over_hp, $won_txt);
        } else {

            array_push($arr_hp, $hp);

            $res = sql_fetch("select * from {$g5['sms5_book_table']} where bk_hp='$hp'  and mb_no = '{$member['mb_no']}' and bg_no='{$bg_no}' ");
            if ($res) 
            {
                array_push($dupl_hp, $won_txt);                                
                $overlap++;
            } 
            else if (!$confirm && $hp) 
            {
                array_push($succ_hp, $won_txt);                                                
                $sqlstr = "insert into {$g5['sms5_book_table']} set bg_no='$bg_no', bk_name='".addslashes($name)."', bk_hp='$hp', mb_no = '{$member['mb_no']}', bk_receipt=1, bk_datetime='".G5_TIME_YMDHIS."'";
                sql_query($sqlstr);
                $success++;
            }
        }
/*   체크 끝*/                
    }
    if ($inner_overlap > 0) {
        $overlap += $inner_overlap;
        $inner_overlap = 0;
    }
}

unlink($_FILES['csv']['tmp_name']);

if ($success){
    $sql = "select count(*) as cnt from {$g5['sms5_book_table']} where bg_no='$bg_no'";
    $total = sql_fetch($sql);
    sql_query("update {$g5['sms5_book_group_table']} set bg_count = ".$total['cnt']." where bg_no='$bg_no'");
}

$result = $counter - $failure - $overlap;
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
    var info = parent.document.getElementById('upload_info');
    var html = '';
    html += "<div id='upload_result'><span>총 건수 : <?=number_format($counter)?> 건</span>";
    html += "<span class='sms5_txt_fail'>등록불가 : <?=number_format($failure)?> 건</span>";
    html += "<span>중복번호 : <?=number_format($overlap)?> 건</span>";
<?php    
if ($process_flag == 3) {
?>        
        html += "<span class='sms5_txt_success'>등록가능 : <?=number_format($result)?> 건</span>&nbsp;&nbsp;&nbsp;";
        html += "[<?=$gk_nm?>]에 ";        
        html += "<button type='button' id='btn_fileup' class='btn_submit' onclick='upload(1)'>등록하기</button>";
<?php            
} else if ($process_flag == 7) {
?>
        html += "<br><span class='sms5_txt_success'>총 :<?=number_format($success)?> 건의 휴대폰번호 등록을 완료하였습니다.</span>";    
<?php        
} else {
?>
     html += "<br><span class='sms5_txt_fail'>등록할 수 없습니다.</span>";
<?php    
}
?>
    html += "</div>";
    parent.document.getElementById('upload_button').style.display = 'inline';
    parent.document.getElementById('uploading').style.display = 'none';
    parent.document.getElementById('register').style.display = 'none';
    info.style.display = 'block';
    info.innerHTML = html;
</script>
<?php    
if ($failure > 0){
    echo "<div id = 'fail_info'>";     
    echo "<ul>";            
    echo "<li><h2>오류번호 목록</h2></li>";
    for ($i=0; $i<count($fail_hp); $i++){
        echo "<li>".$fail_hp[$i]."</li>";
    }
    echo "</ul></div>";        
}
if ($overlap > 0) {
    echo "<div id = 'dup_info'>";       
    echo "<ul>";            
    echo "<li><h2>중복번호 목록</h2></li>";
    for ($i=0; $i<count($over_hp); $i++){
        echo "<li>".$over_hp[$i]."</li>";
    }
     for ($i=0; $i<count($dupl_hp); $i++){
        echo "<li>".$dupl_hp[$i]."</li>";
    }
    echo "</ul></div>";               
}

function alert_after($str) {
    echo "<script>
    parent.document.getElementById('upload_button').style.display = 'inline';
    parent.document.getElementById('uploading').style.display = 'none';
    parent.document.getElementById('register').style.display = 'none';
    parent.document.getElementById('upload_info').style.display = 'none';
    </script>";
    alert_just($str);
}
?>