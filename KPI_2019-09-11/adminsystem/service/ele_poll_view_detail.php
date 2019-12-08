<?php
define('G5_IS_SERVICE', true);
include_once('../common.php');
$pgMNo = 8;
$pgMNo1 = $ew;
$total_cnt = $sc;
if($is_guest)  
alert('회원이시라면 로그인 후 이용해 보십시오.', 
    G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/serv.php?m1='.$pgMNo.'&m2='.$pgMNo1));
          $res = sql_fetch("select * from epoll_master where eplm_ukey='{$ep}' and eplm_gubn = '1' and eplm_mbid='{$member['mb_no']}'");

	if (!$res)   {
	    alert_after('존재 하지 않는 문서입니다.');
	}    
	$eplm_ukey = $res['eplm_ukey'];
	$eplm_mbid = $res['eplm_mbid'];
	$m_title       = $res['eplm_title'];
	$eplm_qcnt  = $res['eplm_qcnt'];
	$polltype      = $res['eplm_gubn'];	
           $as_type     = $res['eplm_type'];    
	//echo $eplm_ukey.'/'.$eplm_mbid.'/'.$m_title.'/질문 수 : '.$eplm_qcnt.'/'.$polltype.'<br>';
    include_once('./epoll_func.php');    
    include_once(G5_LIB_PATH.'/PHPExcel/Classes/PHPExcel.php');
    $info_bArr = get_detail_info_before($as_type);
    $objPHPExcel = new PHPExcel();

    $objPHPExcel->getProperties()->setCreator('HANCLOUD(CO.)')
                                 ->setLastModifiedBy('e-letter')
                                 ->setTitle('e-Letter Poll Document')
                                 ->setSubject('e-Letter Poll Document')
                                 ->setDescription('e-Letter Poll Document');


$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('회신 문서 내용');

$objPHPExcel->getActiveSheet()->setCellValue('A1', $m_title);
$objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray(
    array('fill'    => array(
                                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                'color'     => array('argb' => 'FFFFFF00')
                            ),
          'borders' => array(
                                'top'    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                'left'    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                'bottom'    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                'right'     => array('style' => PHPExcel_Style_Border::BORDER_THIN)
                            )
         )
);
$CurrRow = 2;
for ($idx=0;$idx<$eplm_qcnt;$idx++){
        $eplh_ilbh = $idx+1;    
$qst_qry_text = "select * ";
$qst_qry_text = $qst_qry_text."from epoll_question where eplh_ukey='{$eplm_ukey}' and eplh_ilbh = '{$eplh_ilbh}' "; 
        $resq = sql_fetch($qst_qry_text);
        if (!$resq['eplh_ilbh']) continue;
        $eplh_title = $resq['eplh_title'];
        $eplh_numb = ($idx+1).'문항';
        $eplh_acnt    = $resq['eplh_acnt'];
        $eplh_chk     = $resq['eplh_chk'];

$objPHPExcel->getActiveSheet()->setCellValue('A'.$CurrRow,$eplh_numb)
                                                ->setCellValue('B'.$CurrRow, $eplh_title);
$objPHPExcel->getActiveSheet()->mergeCells('B'.$CurrRow.':D'.$CurrRow);
$CurrRow++;
        for($jdx=0;$jdx<$eplh_acnt;$jdx++){
            $epla_asbh = $jdx+1;
$sql_text = "select * ";
$sql_text = $sql_text."from epoll_qahist where epla_ukey='{$eplm_ukey}' and epla_ilbh = '{$eplh_ilbh}' and epla_asbh = '{$epla_asbh}' ";
            $resa = sql_fetch($sql_text );
             if (!$resa['epla_asbh']) continue;
            $epla_asbh = $epla_asbh.' ) ';
            $epla_title = $resa['epla_title'];
$objPHPExcel->getActiveSheet()->setCellValue('A'.$CurrRow,$epla_asbh)
                                                ->setCellValue('B'.$CurrRow, $epla_title);
$objPHPExcel->getActiveSheet()->mergeCells('B'.$CurrRow.':D'.$CurrRow);
$CurrRow++;
         }
    if ($eplh_chk=='Y') { 
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$CurrRow,'기타');
            $objPHPExcel->getActiveSheet()->mergeCells('B'.$CurrRow.':D'.$CurrRow);
       }   
}   

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A2:A'.$CurrRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('B2:B'.$CurrRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('A:D')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(1);
$objPHPExcel->getActiveSheet()->setTitle('설문 응답');
$CurrRow2 = 1;
if ($as_type == '3') {
    $grade_str = '';
    $class_str  = '';    
} else if ($as_type == '2') {
    $grade_str = '';
    $class_str = '소속';
} else {
    $grade_str = '학년';
    $class_str = '반';
}

$first_head_count = count($info_bArr);

if ($first_head_count == 1){
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$CurrRow2,$info_bArr[0])
                                                    ->setCellValue('B'.$CurrRow2,'문항')
                                                    ->setCellValue('C'.$CurrRow2,'답변')
                                                    ->setCellValue('D'.$CurrRow2,'기타')
                                                    ->setCellValue('E'.$CurrRow2,'수신자명')
                                                    ->setCellValue('F'.$CurrRow2,'수신번호')
                                                    ->setCellValue('G'.$CurrRow2,'문항')    
                                                    ->setCellValue('H'.$CurrRow2,'답항')
                                                    ->setCellValue('I'.$CurrRow2,'응답일시');                                                                                                            
    $gitacol  = 'D';                                                    
    $centor_col = 'F';
    $endcol = 'I';
} else if ($first_head_count == 2){
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$CurrRow2,$info_bArr[0])
                                                    ->setCellValue('B'.$CurrRow2,$info_bArr[1])
                                                    ->setCellValue('C'.$CurrRow2,'문항')
                                                    ->setCellValue('D'.$CurrRow2,'답변')
                                                    ->setCellValue('E'.$CurrRow2,'기타')
                                                    ->setCellValue('F'.$CurrRow2,'수신자명')
                                                    ->setCellValue('G'.$CurrRow2,'수신번호')
                                                    ->setCellValue('H'.$CurrRow2,'문항')    
                                                    ->setCellValue('I'.$CurrRow2,'답항')
                                                    ->setCellValue('J'.$CurrRow2,'응답일시');                                                                                                            
    $gitacol  = 'E';                                                    
    $centor_col = 'G';
    $endcol = 'J';
} else if ($first_head_count == 3){
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$CurrRow2,$info_bArr[0])
                                                    ->setCellValue('B'.$CurrRow2,$info_bArr[1])
                                                    ->setCellValue('C'.$CurrRow2,$info_bArr[2])
                                                    ->setCellValue('D'.$CurrRow2,'문항')
                                                    ->setCellValue('E'.$CurrRow2,'답변')
                                                    ->setCellValue('F'.$CurrRow2,'기타')
                                                    ->setCellValue('G'.$CurrRow2,'수신자명')
                                                    ->setCellValue('H'.$CurrRow2,'수신번호')
                                                    ->setCellValue('I'.$CurrRow2,'문항')    
                                                    ->setCellValue('J'.$CurrRow2,'답항')
                                                    ->setCellValue('K'.$CurrRow2,'응답일시');   
    $gitacol  = 'F';                                                    
    $centor_col = 'H';
    $endcol = 'K';                                                    
} else if ($first_head_count == 4){
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$CurrRow2,$info_bArr[0])
                                                    ->setCellValue('B'.$CurrRow2,$info_bArr[1])
                                                    ->setCellValue('C'.$CurrRow2,$info_bArr[2])
                                                    ->setCellValue('D'.$CurrRow2,$info_bArr[3])
                                                    ->setCellValue('E'.$CurrRow2,'문항')
                                                    ->setCellValue('F'.$CurrRow2,'답변')
                                                    ->setCellValue('G'.$CurrRow2,'기타')
                                                    ->setCellValue('H'.$CurrRow2,'수신자명')
                                                    ->setCellValue('I'.$CurrRow2,'수신번호')
                                                    ->setCellValue('J'.$CurrRow2,'문항')    
                                                    ->setCellValue('K'.$CurrRow2,'답항')
                                                    ->setCellValue('L'.$CurrRow2,'응답일시');   
    $gitacol  = 'G';
    $centor_col = 'I';                                                    
    $endcol = 'L';                                                                                                        
} else {
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$CurrRow2,'문항')
                                                    ->setCellValue('B'.$CurrRow2,'답변')
                                                    ->setCellValue('C'.$CurrRow2,'기타')
                                                    ->setCellValue('D'.$CurrRow2,'수신자명')
                                                    ->setCellValue('E'.$CurrRow2,'수신번호')
                                                    ->setCellValue('F'.$CurrRow2,'문항')    
                                                    ->setCellValue('G'.$CurrRow2,'답항')
                                                    ->setCellValue('H'.$CurrRow2,'응답일시');   
    $gitacol  = 'C';
    $centor_col = 'E';                                                    
    $endcol = 'H';                                                                                                                                                            
}    

$qst_qry_text = "select epoll_answer.*, ";
$qst_qry_text = $qst_qry_text."(SELECT hs_name FROM sms5_history where hs_no = epls_usms) as hs_name,";
$qst_qry_text = $qst_qry_text."(SELECT hs_hp FROM sms5_history where hs_no = epls_usms) as hs_hp, ";
$qst_qry_text = $qst_qry_text."(select eplh_title from epoll_question where eplh_ukey = epls_ukey and eplh_ilbh = epls_ilbh) qtitle, ";
$qst_qry_text = $qst_qry_text."(select epla_title from epoll_qahist where epla_ukey = epls_ukey and epla_ilbh = epls_ilbh and epla_asbh = epls_asbh) atitle ";
$qst_qry_text = $qst_qry_text."from epoll_answer where epls_ukey='{$eplm_ukey}' order by epls_info,epls_ilbh"; 
$result = sql_query($qst_qry_text);
$CurrRow2++;
for ($i=0; $row=sql_fetch_array($result); $i++) {
    $pInfoRow = return_real_poll_arr($row['epls_info']);
    if ($first_head_count == 1){    
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$CurrRow2,$pInfoRow[0])
                                                ->setCellValue('B'.$CurrRow2,$row['epls_ilbh'])
                                                ->setCellValue('C'.$CurrRow2,$row['epls_asbh'])
                                                ->setCellValue('D'.$CurrRow2,$row['epls_etxt'])
                                                ->setCellValue('E'.$CurrRow2,$row['hs_name'])
                                                ->setCellValue('F'.$CurrRow2,$row['hs_hp'])                
                                                ->setCellValue('G'.$CurrRow2,$row['qtitle'])                
                                                ->setCellValue('H'.$CurrRow2,$row['atitle'])
                                                ->setCellValue('I'.$CurrRow2,$row['epls_time']);                
                                                
    } else if ($first_head_count == 2){        
            
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$CurrRow2,$pInfoRow[0])
                                        ->setCellValue('B'.$CurrRow2,$pInfoRow[1])
                                        ->setCellValue('C'.$CurrRow2,$row['epls_ilbh'])
                                        ->setCellValue('D'.$CurrRow2,$row['epls_asbh'])
                                        ->setCellValue('E'.$CurrRow2,$row['epls_etxt'])
                                        ->setCellValue('F'.$CurrRow2,$row['hs_name'])
                                        ->setCellValue('G'.$CurrRow2,$row['hs_hp'])                
                                        ->setCellValue('H'.$CurrRow2,$row['qtitle'])                
                                        ->setCellValue('I'.$CurrRow2,$row['atitle'])
                                        ->setCellValue('J'.$CurrRow2,$row['epls_time']);                
    } else if ($first_head_count == 3){                
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$CurrRow2,$pInfoRow[0])
                                                ->setCellValue('B'.$CurrRow2,$pInfoRow[1])
                                                ->setCellValue('C'.$CurrRow2,$pInfoRow[2])
                                                ->setCellValue('D'.$CurrRow2,$row['epls_ilbh'])
                                                ->setCellValue('E'.$CurrRow2,$row['epls_asbh'])
                                                ->setCellValue('F'.$CurrRow2,$row['epls_etxt'])
                                                ->setCellValue('G'.$CurrRow2,$row['hs_name'])
                                                ->setCellValue('H'.$CurrRow2,$row['hs_hp'])                
                                                ->setCellValue('I'.$CurrRow2,$row['qtitle'])                
                                                ->setCellValue('J'.$CurrRow2,$row['atitle'])
                                                ->setCellValue('K'.$CurrRow2,$row['epls_time']);
    } else if ($first_head_count == 4){                
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$CurrRow2,$pInfoRow[0])
                                                ->setCellValue('B'.$CurrRow2,$pInfoRow[1])
                                                ->setCellValue('C'.$CurrRow2,$pInfoRow[2])
                                                ->setCellValue('D'.$CurrRow2,$pInfoRow[3])
                                                ->setCellValue('E'.$CurrRow2,$row['epls_ilbh'])
                                                ->setCellValue('F'.$CurrRow2,$row['epls_asbh'])
                                                ->setCellValue('G'.$CurrRow2,$row['epls_etxt'])
                                                ->setCellValue('H'.$CurrRow2,$row['hs_name'])
                                                ->setCellValue('I'.$CurrRow2,$row['hs_hp'])                
                                                ->setCellValue('J'.$CurrRow2,$row['qtitle'])                
                                                ->setCellValue('K'.$CurrRow2,$row['atitle'])
                                                ->setCellValue('L'.$CurrRow2,$row['epls_time']);                
    } else {                
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$CurrRow2,$row['epls_ilbh'])
                                                ->setCellValue('B'.$CurrRow2,$row['epls_asbh'])
                                                ->setCellValue('C'.$CurrRow2,$row['epls_etxt'])
                                                ->setCellValue('D'.$CurrRow2,$row['hs_name'])
                                                ->setCellValue('E'.$CurrRow2,$row['hs_hp'])                
                                                ->setCellValue('F'.$CurrRow2,$row['qtitle'])                
                                                ->setCellValue('G'.$CurrRow2,$row['atitle'])
                                                ->setCellValue('H'.$CurrRow2,$row['epls_time']);                
    }
    $CurrRow2++;
}

if ($first_head_count == 1){    
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);                
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(80);                
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);                
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
} else if ($first_head_count == 2){        
        if ($info_bArr[0] =='소속'){    
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);            
        } else {
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        }
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);            
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(80);                
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(30);                        
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
} else if ($first_head_count == 3){        
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(80);                
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);                        
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
} else if ($first_head_count == 4){        
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(80);                
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);                        
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
} else {                
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(5);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);    
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(80);                
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);                        
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
}
$objPHPExcel->getActiveSheet()->getStyle('A1:'.$centor_col.$CurrRow2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle($gitacol)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('A:'.$endcol)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1:'.$endcol.'1')->applyFromArray(
    array('fill'    => array(
                                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                'color'     => array('argb' => 'FFFFFF00')
                            ),
          'borders' => array(
                                'top'    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                'left'    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                'bottom'    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                                'right'     => array('style' => PHPExcel_Style_Border::BORDER_THIN)
                            )
         )
);
$objPHPExcel->setActiveSheetIndex(0);
/** 위에서 쓴 엑셀을 저장하고 다운로드 합니다. **/
$filename = str_replace(' ', '_', trim($m_title)).'.xls';
//$excelfn = iconv("EUC-KR","UTF-8", $kkk);
$excelfn = iconv("UTF-8", "EUC-KR",$filename);
$excelfn = str_replace(',', '_', $excelfn);

header('Content-Type: application/vnd.ms-excel;charset=utf-8');
header('Content-type: application/x-msexcel;charset=utf-8');
header('Content-Disposition: attachment;filename='.$excelfn);
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');         
?>