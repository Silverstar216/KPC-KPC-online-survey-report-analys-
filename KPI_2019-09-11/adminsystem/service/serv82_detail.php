<?php
define('G5_IS_SERVICE', true);
include_once('../common.php');
$pgMNo = 8;
$pgMNo1 = 2;
if($is_guest)  
alert('회원이시라면 로그인 후 이용해 보십시오.', 
    G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/serv.php?m1='.$pgMNo.'&m2='.$pgMNo1));

   if (!isset($startdate)){
alert('기간을 확인하십시오.',G5_URL.'/serv.php?m1=8&m2=2');
    }
    if (!isset($enddate)){
alert('기간을 확인하십시오.',G5_URL.'/serv.php?m1=8&m2=2');
    }
    if ($startdate > $enddate ){
alert('기간을 확인하십시오.',G5_URL.'/serv.php?m1=8&m2=2');
    }
  $qrystime = $startdate.' 00:00:00';
  $qryetime = $enddate.' 23:59:59';

     $sql_search = " and wr_datetime between '{$qrystime}' and '{$qryetime}' ";

$calc_qry = "SELECT ".
"(case when ( ifnull((select count(*) from sms5_history as sh where sh.wr_no = sm.wr_no and sh.mb_id = 'LMS'),0) > 0) then 'LMS' else 'SMS' end) as lms_yn, ".
"sum(sm.wr_total) tcnt ,sum(sm.wr_success) scnt,sum(sm.wr_failure) fcnt, ".
"sum(case when (locate('http://mms.ac',wr_message) > 0) then wr_total else 0 end) cv_total,".
"sum(case when (locate('http://mms.ac',wr_message) > 0) then wr_success else 0 end) cv_success,".
"sum(case when (locate('http://mms.ac',wr_message) > 0) then wr_failure else 0 end) cv_failed ".
"FROM sms5_write sm ".
"where wr_id=  '{$member['mb_no']}' and wr_renum=0 $sql_search group by 1;";

    $m_title = 'SMS_History('.$startdate.'_'.$enddate.')';
    $qry_text = "select a.*,  ".
"(case when ( ifnull((select count(*) from sms5_history as sh where sh.wr_no = a.wr_no and sh.mb_id = 'LMS'),0) > 0) then 'LMS' else 'SMS' end) as lms_yn, ".
"(case when (locate('http://mms.ac',wr_message) > 0) then 'c' else '' end) as attach ".
    "from {$g5['sms5_write_table']} a ".
    "where wr_id=  '{$member['mb_no']}' and wr_renum=0 $sql_search order by wr_no desc";
    $qry = sql_query($qry_text);

    include_once(G5_LIB_PATH.'/PHPExcel/Classes/PHPExcel.php');
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator('HANCLOUD(CO.)')
                                 ->setLastModifiedBy('e-letter')
                                 ->setTitle('e-Letter Poll Document')
                                 ->setSubject('e-Letter Poll Document')
                                 ->setDescription('e-Letter Poll Document');


$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(1);

$objPHPExcel->getActiveSheet()->setTitle('전송내역');

$objPHPExcel->getActiveSheet()->setCellValue('A1', '구분')
                                                ->setCellValue('B1', '전송시간')
                                                ->setCellValue('C1', '예약시간')
                                                ->setCellValue('D1', '첨부여부')                                                
                                                ->setCellValue('E1', '총 건수')
                                                ->setCellValue('F1', '성공')
                                                ->setCellValue('G1', '실패')
                                                ->setCellValue('H1', '메세지내용'); 
$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray(
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
$sms_tt_total = 0;
$sms_ts_total = 0;
$sms_tf_total = 0;
$sms_cv_total = 0;
$sms_cs_total = 0;
$sms_cf_total = 0;

$lms_tt_total = 0;
$lms_ts_total = 0;
$lms_tf_total = 0;
$lms_cv_total = 0;
$lms_cs_total = 0;
$lms_cf_total = 0;

    while($res = sql_fetch_array($qry)) {
        $time1 = $res['wr_datetime']; 

        $total = $res['wr_total'];
        $succ = $res['wr_success'];
        $failed = $res['wr_failure'];
       if ($res['lms_yn'] == 'LMS'){
            $SMS_flag = false;
            $lms_tt_total = $lms_tt_total+$total;
            $lms_ts_total = $lms_ts_total+$succ;
            $lms_tf_total = $lms_tf_total+$failed;            
       } else {
            $SMS_flag = true;
            $sms_tt_total = $sms_tt_total+$total;
            $sms_ts_total = $sms_ts_total+$succ;
            $sms_tf_total = $sms_tf_total+$failed;
       }

        if ($res['wr_booking'] == '0000-00-00 00:00:00') {
                $time2 = '';     
        } else {
                $time2 = date('Y-m-d H:i', strtotime($res['wr_booking'])) ;     
        }
        if ($res['attach'] == 'c'){
            $attach = '첨부';
            if ($SMS_flag == true){
                    $sms_cv_total = $sms_cv_total+$total;
                    $sms_cs_total = $sms_cs_total+$succ;
                    $sms_cf_total = $sms_cf_total+$failed;
            } else {
                    $lms_cv_total = $lms_cv_total+$total;
                    $lms_cs_total = $lms_cs_total+$succ;
                    $lms_cf_total = $lms_cf_total+$failed;
            }
        } else {
            $attach = '';
        }
        $wr_total = $res['wr_total'];
        $wr_success = $res['wr_success'];
        $wr_failure = $res['wr_failure'];
$objPHPExcel->getActiveSheet()->setCellValue('A'.$CurrRow,$res['lms_yn'])
                                                ->setCellValue('B'.$CurrRow, date('Y-m-d H:i', strtotime($time1)))
                                                ->setCellValue('C'.$CurrRow, $time2)
                                                ->setCellValue('D'.$CurrRow, $attach)
                                                ->setCellValue('E'.$CurrRow, $wr_total)
                                                ->setCellValue('F'.$CurrRow, $wr_success)
                                                ->setCellValue('G'.$CurrRow, $wr_failure)
                                                ->setCellValue('H'.$CurrRow, $res['wr_message']);
$CurrRow++;
}   

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(100);
$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A2:A'.$CurrRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B2:B'.$CurrRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('C2:C'.$CurrRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('D2:D'.$CurrRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('E2:E'.$CurrRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('F2:F'.$CurrRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('G2:G'.$CurrRow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('A:H')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->setTitle('전송통계');


$objPHPExcel->getActiveSheet()->setCellValue('B2', '전송내역')
                                                ->setCellValue('C2', $startdate.'~'.$enddate);
$objPHPExcel->getActiveSheet()->mergeCells('C2:K2');

$objPHPExcel->getActiveSheet()->setCellValue('B4', '구분')
                                                ->setCellValue('C4', '총건수')
                                                ->setCellValue('D4', '성공')
                                                ->setCellValue('E4', '실패')
                                                ->setCellValue('F4', '단순 총건')
                                                ->setCellValue('G4', '단순 성공')
                                                ->setCellValue('H4', '단순 실패')
                                                ->setCellValue('I4', '첨부 총건')
                                                ->setCellValue('J4', '첨부 성공')
                                                ->setCellValue('K4', '첨부 실패');

$objPHPExcel->getActiveSheet()->getStyle('B4:K4')->applyFromArray(
    array('fill'    => array(
                                'type'      => PHPExcel_Style_Fill::FILL_SOLID,
                                'color'     => array('argb' => 'FFFFFF00')
                            )          
         )
);             


$objPHPExcel->getActiveSheet()->setCellValue('B5', 'SMS')
                                                ->setCellValue('C5', $sms_tt_total)
                                                ->setCellValue('D5',$sms_ts_total)
                                                ->setCellValue('E5', $sms_tf_total)
                                                ->setCellValue('F5', $sms_tt_total-$sms_cv_total)
                                                ->setCellValue('G5',$sms_ts_total-$sms_cs_total)
                                                ->setCellValue('H5', $sms_tf_total-$sms_cf_total)
                                                ->setCellValue('I5',$sms_cv_total)
                                                ->setCellValue('J5', $sms_cs_total)
                                                ->setCellValue('K5', $sms_cf_total);

$objPHPExcel->getActiveSheet()->setCellValue('B6', 'LMS')
                                                ->setCellValue('C6', $lms_tt_total)
                                                ->setCellValue('D6',$lms_ts_total)
                                                ->setCellValue('E6', $lms_tf_total)
                                                ->setCellValue('F6', $lms_tt_total-$lms_cv_total)
                                                ->setCellValue('G6',$lms_ts_total-$lms_cs_total)
                                                ->setCellValue('H6', $lms_tf_total-$lms_cf_total)
                                                ->setCellValue('I6',$lms_cv_total)
                                                ->setCellValue('J6', $lms_cs_total)
                                                ->setCellValue('K6', $lms_cf_total);
$objPHPExcel->getActiveSheet()->setCellValue('B7', '합계')
                                                ->setCellValue('C7', $sms_tt_total+$lms_tt_total)
                                                ->setCellValue('D7',$sms_ts_total+$lms_ts_total)
                                                ->setCellValue('E7', $sms_tf_total+$lms_tf_total)
                                                ->setCellValue('F7', $sms_tt_total-$sms_cv_total+$lms_tt_total-$lms_cv_total)
                                                ->setCellValue('G7',$sms_ts_total-$sms_cs_total+$lms_ts_total-$lms_cs_total)
                                                ->setCellValue('H7', $sms_tf_total-$sms_cf_total+$lms_tf_total-$lms_cf_total)
                                                ->setCellValue('I7',$sms_cv_total+$lms_cv_total)
                                                ->setCellValue('J7', $sms_cs_total+$lms_cs_total)
                                                ->setCellValue('K7', $sms_cf_total+$lms_cf_total);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(1);
$objPHPExcel->getActiveSheet()->getStyle('B4:K4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B5:B7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('C5:K5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('C6:K6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('C7:K7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('A:K')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()->getStyle('B4:K7')->applyFromArray(
    array(
          'borders' => array('allborders' => array(
              'style' => PHPExcel_Style_Border::BORDER_THIN
          )
                            )
         )
);             

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


