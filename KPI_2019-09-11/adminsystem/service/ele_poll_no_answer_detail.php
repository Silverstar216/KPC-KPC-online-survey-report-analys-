<?php
define('G5_IS_SERVICE', true);
include_once('../common.php');
$pgMNo = 8;
$pgMNo1 = $ew;
$total_cnt = $sc;
if($is_guest)  
alert('회원이시라면 로그인 후 이용해 보십시오.', 
    G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/serv.php?m1='.$pgMNo.'&m2='.$pgMNo1));

       $res = sql_fetch("select * from epoll_master where eplm_ukey='{$ep}' and eplm_mbid='{$member['mb_no']}'");
    if (!$res)   {
        die();
    }    
    $eplm_ukey = $res['eplm_ukey'];
    $eplm_mbid = $res['eplm_mbid'];
    $m_title       = $res['eplm_title'];
    $eplm_qcnt  = $res['eplm_qcnt'];
    $polltype      = $res['eplm_gubn']; 
    $as_type     = $res['eplm_type'];    

$sql = " select count(*) as cnt FROM sms5_history where hs_flag = '1' ".
            "and sms5_history.wr_no in (SELECT sms5_write.wr_no FROM sms5_write ".
            "where (wr_udoc = (SELECT edoc_ukey FROM edoc_master where edoc_attach_poll_id = '{$ep}')) or (wr_poll = '{$ep}')) ".
            "and hs_no in (select epls_usms from epoll_answer where epls_ukey =  '{$ep}') ";

$row = sql_fetch($sql);
$answer_count = $row['cnt'];
//if ($answer_count < 30) {// 응답자가 30이하이다
    if ($polltype =='2') {
    alert('익명 설문조사인 경우 응답 리스트가 없습니다!!!','/serv.php?m1=8&m2='.$pgMNo1.'&page='.$page);
    }
//}

$sql = " select count(*) as cnt FROM sms5_history where hs_flag = '1' ".
            "and sms5_history.wr_no in (SELECT sms5_write.wr_no FROM sms5_write ".
            "where (wr_udoc = (SELECT edoc_ukey FROM edoc_master where edoc_attach_poll_id = '{$ep}')) or (wr_poll = '{$ep}')) ".
            "and hs_no not in (select epls_usms from epoll_answer where epls_ukey =  '{$ep}') ";

$row = sql_fetch($sql);
$total_count = $row['cnt'];

$sql = "SELECT hs_name,hs_hp FROM sms5_history where hs_flag = '1' ".
            "and sms5_history.wr_no in (SELECT sms5_write.wr_no FROM sms5_write ".
            "where (wr_udoc = (SELECT edoc_ukey FROM edoc_master where edoc_attach_poll_id = '{$ep}')) or (wr_poll = '{$ep}')) ".
            "and hs_no not in (select epls_usms from epoll_answer where epls_ukey =  '{$ep}') order by hs_no ";
$result = sql_query($sql);

    include_once(G5_LIB_PATH.'/PHPExcel/Classes/PHPExcel.php');

    $objPHPExcel = new PHPExcel();

    $objPHPExcel->getProperties()->setCreator('HANCLOUD(CO.)')
                                 ->setLastModifiedBy('e-letter')
                                 ->setTitle('e-Letter Poll Document')
                                 ->setSubject('e-Letter Poll Document')
                                 ->setDescription('e-Letter Poll Document');

$CurrRow = 1;
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('회신 문서 미응답 리스트');
$objPHPExcel->getActiveSheet()->setCellValue('A'.$CurrRow,'수신자명')
                                                ->setCellValue('B'.$CurrRow,'수신번호');
$CurrRow++;
for ($i=0; $row=sql_fetch_array($result); $i++) {
$objPHPExcel->getActiveSheet()->setCellValue('A'.$CurrRow,$row['hs_name'])
                                                ->setCellValue('B'.$CurrRow,$row['hs_hp']);
    $CurrRow++;
}
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
$objPHPExcel->getActiveSheet()->getStyle('A1:B'.$CurrRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->applyFromArray(
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
$excelfn = iconv("UTF-8", "EUC-KR",'[미응답]'.$filename);
header('Content-Type: application/vnd.ms-excel;charset=utf-8');
header('Content-type: application/x-msexcel;charset=utf-8');
header('Content-Disposition: attachment;filename='.$excelfn);
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');         
?>


