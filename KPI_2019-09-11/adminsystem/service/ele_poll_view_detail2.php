<?php
define('G5_IS_SERVICE', true);
include_once('../common.php');
$pgMNo = 8;
$pgMNo1 = $ew;
$total_cnt = $sc;
if($is_guest)  
alert('회원이시라면 로그인 후 이용해 보십시오.', 
    G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/serv.php?m1='.$pgMNo.'&m2='.$pgMNo1));
          $res = sql_fetch("select * from epoll_master where eplm_ukey='{$ep}' and eplm_gubn = '2' and eplm_mbid='{$member['mb_no']}'");

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
    
    $objPHPExcel = new PHPExcel();

    $objPHPExcel->getProperties()->setCreator('HANCLOUD(CO.)')
                                 ->setLastModifiedBy('e-letter')
                                 ->setTitle('e-Letter Poll Document')
                                 ->setSubject('e-Letter Poll Document')
                                 ->setDescription('e-Letter Poll Document');


$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('설문 문서 내용');

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
$objPHPExcel->getActiveSheet()->setCellValue('A'.$CurrRow2,'문제번호')
                                                ->setCellValue('B'.$CurrRow2,'답 번호')
                                                ->setCellValue('C'.$CurrRow2,'선택 수')
                                                ->setCellValue('D'.$CurrRow2,'답글')
                                                ->setCellValue('E'.$CurrRow2,'문항')
                                                ->setCellValue('F'.$CurrRow2,'답항');

$qst_cnt_text = "select eplh_ilbh,eplh_acnt,eplh_title from epoll_question where eplh_ukey='{$eplm_ukey}' order by eplh_ilbh"; 
$result_cnt = sql_query($qst_cnt_text);
$CurrRow2++;
for ($iii=0; $row3=sql_fetch_array($result_cnt); $iii++) {
    $qilbh = $row3['eplh_ilbh'];
    $qcnt = $row3['eplh_acnt'];
    $qtitle = $row3['eplh_title'];
for ($iiii=1; $iiii<=$qcnt; $iiii++) {
    $answer_qry_text = "select epla_title from epoll_qahist where epla_ukey = '{$eplm_ukey}' and epla_ilbh = '{$qilbh}' and epla_asbh = '{$iiii}' ";
    $answer_text = sql_fetch($answer_qry_text);    
    if ($answer_text['epla_title']) {
        $atitle = $answer_text['epla_title'];
    } else {
        $atitle = '';
    }


$qst_qry_text = "select epls_ilbh,epls_asbh,count(*) as cnt ";
$qst_qry_text = $qst_qry_text."from epoll_answer where epls_ukey='{$eplm_ukey}' and epls_ilbh = '{$qilbh}' and epls_asbh = '{$iiii}' group by 1,2 order by 1,2"; 
$result = sql_query($qst_qry_text);

$row=sql_fetch_array($result);
if ($row){
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$CurrRow2,$row['epls_ilbh'])
                                                ->setCellValue('B'.$CurrRow2,$row['epls_asbh'])
                                                ->setCellValue('C'.$CurrRow2,$row['cnt'])
                                                ->setCellValue('E'.$CurrRow2,$qtitle)
                                                ->setCellValue('F'.$CurrRow2,$atitle);

} else {
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$CurrRow2,$qilbh)
                                                ->setCellValue('B'.$CurrRow2,$iiii)
                                                ->setCellValue('C'.$CurrRow2,'0')
                                                ->setCellValue('E'.$CurrRow2,$qtitle)
                                                ->setCellValue('F'.$CurrRow2,$atitle);
}
    $CurrRow2++;    
    
$qst_gita_qry_text = "select epls_ilbh,epls_asbh,epls_etxt ";
$qst_gita_qry_text = $qst_gita_qry_text."from epoll_answer where epls_ukey='{$eplm_ukey}' and epls_ilbh ='{$qilbh}' and epls_asbh = '{$iiii}' and epls_etxt != '' "; 
$result_gita = sql_query($qst_gita_qry_text);
for ($ii=0; $row2=sql_fetch_array($result_gita); $ii++) {
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$CurrRow2,$row2['epls_ilbh'])
                                                    ->setCellValue('B'.$CurrRow2,$row2['epls_asbh'])
                                                    ->setCellValue('C'.$CurrRow2,'0')
                                                    ->setCellValue('D'.$CurrRow2,$row2['epls_etxt'])
                                                    ->setCellValue('E'.$CurrRow2,$qtitle)
                                                    ->setCellValue('F'.$CurrRow2,$atitle);
        $CurrRow2++;        
}
}//iiii
    $qst_gita_qry_text = "select epls_ilbh,epls_asbh,epls_etxt ";
    $qst_gita_qry_text = $qst_gita_qry_text."from epoll_answer where epls_ukey='{$eplm_ukey}' and epls_ilbh = '{$qilbh}' and epls_asbh ='0' and epls_etxt != '' "; 
    $result_gita = sql_query($qst_gita_qry_text);
    for ($ii=0; $row2=sql_fetch_array($result_gita); $ii++) {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$CurrRow2,$row2['epls_ilbh'])
                                                        ->setCellValue('B'.$CurrRow2,'기타')
                                                        ->setCellValue('C'.$CurrRow2,'1')
                                                        ->setCellValue('D'.$CurrRow2,$row2['epls_etxt'])
                                                        ->setCellValue('E'.$CurrRow2,$qtitle)
                                                        ->setCellValue('F'.$CurrRow2,$atitle);                
            $CurrRow2++;        
    }    
}//iii
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(50);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(80);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);

$objPHPExcel->getActiveSheet()->getStyle('A1:D'.$CurrRow2)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A:F')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray(
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
$excelfn = str_replace(',', '_', $excelfn);// 크롬에서는 ,가 들어 있으며 중복 헤더 문제 발생..

header('Content-Type: application/vnd.ms-excel;charset=utf-8');
header('Content-type: application/x-msexcel;charset=utf-8');
header('Content-Disposition: attachment;filename='.$excelfn);
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');         
?>