<?php
include_once("../common.php");

if($is_guest) { 		alert('로그인 후 이용해 보십시오.', G5_URL); }

function get_id_juso_flag($hp,$grade,$class,$st_id,$ptype){
    $rtn_grname['check'] = false;
    $rtn_grname['name']  = '';
    $rtn_grname['bk_kind']  = '';
    if ($hp == '') { return $rtn_grname;}
    if ($grade == '') { return $rtn_grname;}
    if ($class == '') { return $rtn_grname;}
    if ($st_id == '') { return $rtn_grname;}
    $rtn_grname['check'] = true;
    if ($class < 10){
        $class_name = ' '.$class;
    } else {
        $class_name = $class;
    }
    if ($ptype == ''){
        $rtn_grname['name'] = '학부모('.$grade.'-'.$class_name.')';
        $rtn_grname['bk_kind'] = '1';
    } else {
        $rtn_grname['name'] = '학생('.$grade.'-'.$class_name.')';
        $rtn_grname['bk_kind'] = '2';        
    }   
    return $rtn_grname;
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
if (!$upload_bg_no)
    alert_after('그룹을 선택해주세요.');

$bg_no = $upload_bg_no;

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
                //이름,전화번호,학년,반,번호,학부모구분
                if (in_array($column,range('A','G'))) {
                    return true;
                }
                return false;
            }
        }
        $filterReader = new Ele_excel_xlsx_ReadFilter();
        $extF_Type = 'Excel2007';
        if($ext == ".xls") {
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
    /*case '.xls' :
        include_once(G5_LIB_PATH.'/Excel/reader.php');
        $data = new Spreadsheet_Excel_Reader();

        // Set output Encoding.
        $data->setOutputEncoding('UTF-8');
        $data->read($file);
        $num_rows = $data->sheets[0]['numRows'];
        break;*/
    default :
        alert_after('xlsx 파일과 csv 파일만 허용합니다.');
}

$counter = 0;
$success = 0;
$failure = 0;
$inner_overlap = 0;
$overlap = 0;
$arr_hp = array();
$fail_hp = array();
$over_hp = array();
$dupl_hp = array();
$succ_hp = array();
$book_group = array();
$encode = array('ASCII','UTF-8','EUC-KR');
$won_txt = '';
for ($i = 1; $i <= $num_rows; $i++) {
    $counter++;
    $j = 1;

    switch ($ext) {
        case '.csv' :
            $name = $csv[$i][0];
            $str_encode = @mb_detect_encoding($name, $encode);
            if( $str_encode == "EUC-KR" ){
                $name = iconv_utf8( $name );
            }
            $name = addslashes($name);
            $hp   = addslashes($csv[$i][1]);
            $grade = addslashes($csv[$i][2]);
            $class  = addslashes($csv[$i][3]);
            $st_id   = addslashes($csv[$i][4]);
            $ptype  = addslashes($csv[$i][5]);
            //이름,전화번호,학년,반,번호,학부모구분
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
            $grade = addslashes($sheetData[$i]['C']);
            $class  = addslashes($sheetData[$i]['D']);
            $st_id   = addslashes($sheetData[$i]['E']);
            $ptype  = addslashes($sheetData[$i]['F']);            
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
            $grade = addslashes($data->sheets[0]['cells'][$i][3]);
            $class  = addslashes($data->sheets[0]['cells'][$i][4]);
            $st_id   = addslashes($data->sheets[0]['cells'][$i][5]);
            $ptype  = addslashes($data->sheets[0]['cells'][$i][6]);                        
            break;
    }

    if (!(strlen($name)&&$hp))
    {
        $failure++;
        if (($won_txt == '')&&($hp == '')){
            array_push($fail_hp, '공란');        
        } else {
            array_push($fail_hp, $won_txt.'==>'.$hp);        
        }
        
    } else {
        $id_juso_arr = get_id_juso_flag($hp,$grade,$class,$st_id,$ptype);
        $id_juso_flag = $id_juso_arr['check'];
        if ($id_juso_flag == true) {    
            $rtn_grname  = $id_juso_arr['name'];
            $bk_kind        = $id_juso_arr['bk_kind'];
            array_push($arr_hp, $hp);            
            if (!$confirm && $hp) 
            {

                $book_group_cnt = count($book_group);
                $found_gr_flag = false;
                for ($idx=0;$idx<$book_group_cnt;$idx++)    {
                    if ($book_group[$idx][0] == $rtn_grname){
                        $found_gr_flag = true;
                        $tmp_bg_id = $book_group[$idx][1];
                        break;
                    }                    
                }
                if ($found_gr_flag == false){
                    $gr_findText = "select bg_no from sms5_book_group where bg_name = '{$rtn_grname}' and bg_member = {$member['mb_no']}";   
                    $res_gr = sql_fetch($gr_findText);
                    if($res_gr){
                            $tmp_bg_id = $res_gr['bg_no'];
                            $book_group[$book_group_cnt][0] = $rtn_grname;
                            $book_group[$book_group_cnt][1] = $tmp_bg_id;
                    } else {
                            $gr_findText = "insert into sms5_book_group ".
                            "(bg_name,bg_count,bg_member,bg_nomember,bg_receipt,bg_reject) VALUES ".
                            "('{$rtn_grname}',0,'{$member['mb_no']}',0,0,0)";
                            sql_query($gr_findText);  
                            $tmp_bg_id = mysql_insert_id();          
                            $book_group[$book_group_cnt][0] = $rtn_grname;
                            $book_group[$book_group_cnt][1] = $tmp_bg_id;                            
                    }
                }                
                array_push($succ_hp, $won_txt);                                                
                if ($bk_kind == 2) {// 학생이면 학년,반 번호 다 지운다.. 
$sqlstr = "UPDATE sms5_book SET bk_year = '', bk_grade='',bk_class= '', bk_stid= '', bk_kind = '', bk_datetime= now() ".
" where bk_year = 2015 and bk_grade = '{$grade}' and bk_class = '{$class}' and bk_stid = '{$st_id}' and bk_kind = 2 and mb_no = '{$member['mb_no']}' ";
                } else {// 학부모면 전화번호 일치하는 것만 지운다. 
$sqlstr = "UPDATE sms5_book SET bk_year = '', bk_grade='',bk_class= '', bk_stid= '', bk_kind = '', bk_datetime= now() ".
" where bk_year = 2015 and bk_grade = '{$grade}' and bk_class = '{$class}' and bk_stid = '{$st_id}' and bk_kind = 1 and mb_no = '{$member['mb_no']}' ";
                }
                sql_query($sqlstr);
                $res = sql_fetch("select * from {$g5['sms5_book_table']} where bk_hp='$hp'  and mb_no = '{$member['mb_no']}' and bg_no='{$tmp_bg_id}' ");
                if ($res) 
                {
                    $sqlstr = "UPDATE sms5_book SET bk_name = '".addslashes($name)."', bk_year = 2015, bk_grade = '{$grade}',".
                                 " bk_class = '{$class}', bk_stid = '{$st_id}', bk_kind = '{$bk_kind}', bk_datetime= now() where bk_no = '{$res['bk_no']}' ";
                } else {
                    $sqlstr = "insert into {$g5['sms5_book_table']} set bg_no='$tmp_bg_id', bk_name='".addslashes($name)."', bk_hp='$hp', mb_no = '{$member['mb_no']}', bk_receipt=1, bk_datetime=now(), bk_year = 2015, bk_grade = '{$grade}',".
                                 " bk_class = '{$class}', bk_stid = '{$st_id}', bk_kind = '{$bk_kind}' "; 
                }                
                sql_query($sqlstr);
                $success++;
            }           
        } else if (in_array($hp, $arr_hp))
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
    }
    if ($inner_overlap > 0) {
        $overlap += $inner_overlap;
        $inner_overlap = 0;
    }
}

unlink($_FILES['csv']['tmp_name']);

if ($success){
    $update_txt   = 
    "update {$g5['sms5_book_group_table']} t set bg_count = ".
    "(select ifnull(count(*),0) from {$g5['sms5_book_table']} w where w.bg_no = t.bg_no)".
    "where (bg_no = 1) or (bg_member = '{$member['mb_no']}') ";
    sql_query($update_txt);
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
        html += "<span class='sms5_txt_success'>등록가능 : <?=number_format($result)?> 건</span>";
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
    parent.document.getElementById('upload_bg_no').style.display = 'block';
    parent.document.getElementById('bgno_label').innerHTML = '그룹선택';    
    parent.document.getElementById('upload_button').style.display = 'inline';
    parent.document.getElementById('uploading').style.display = 'none';
    parent.document.getElementById('register').style.display = 'none';
    parent.document.getElementById('upload_info').style.display = 'none';
    </script>";
    alert_just($str);
}
?>