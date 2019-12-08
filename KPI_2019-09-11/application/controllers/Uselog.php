<?php
/**
 * Author: KMC
 * Date: 10/6/15
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Uselog extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();


        // date_default_timezone_set('Asia/Pyongyang');

        $this->data['title'] = SITE_TITLE;
        $this->data['styles'] = array(
            'include/css/log.css',
            'include/plugins/font-awesome/css/font-awesome.min.css',
            'include/plugins/bootstrap-sweetalert/sweetalert.css',
            'include/lib/jquery.datetimepicker.css',
        );

        $this->data['scripts'] = array(
            'include/plugins/bootstrap-sweetalert/sweetalert.min.js',
            'include/lib/jquery.datetimepicker.js',
            'include/js/log/uselog.js'
        );

        $this->load->model('messages_model');
        $this->load->model('msg_result_model');
        $this->load->model('msg_queue_model');
        $this->load->model('money_model');
    }

    public function index()
    {
//        $user_id = 1;
        $user_id = get_session_user_id();
        $userauth= get_session_user_level();
        if($userauth ==="") {
            $userauth = "";
            $user_id = -1;
        }

        $this->load->view('templates/header', $this->data);
        $this->load->view('templates/nav', $this->data);

        $start_date = $this->input->get_post('start_date');
        $end_date = $this->input->get_post('end_date');
        
        if (empty($start_date))
        {
            $now_date = date('Y-m-d');
            $str_date=strtotime($now_date.'-7 days');
            $start_date = date('Y-m-d',$str_date);
        }
        if (empty($end_date))
        {
            $now_date = date('Y-m-d');
            $str_date=strtotime($now_date.'+7 days');
            $end_date=date('Y-m-d',$str_date);

        }
        $this->data['start_date'] = $start_date;
        $this->data['end_date'] = $end_date;

        $result = array();
        $result[] = $this->msg_result_model->get_use_history($user_id,$start_date, $end_date,1);
        $result[] = $this->msg_result_model->get_use_history($user_id,$start_date, $end_date,2);
        $result[] = $this->msg_result_model->get_use_history($user_id,$start_date, $end_date,3);
        $result[] = $this->msg_result_model->get_use_history($user_id,$start_date, $end_date,4);
        $result[] = $this->msg_result_model->get_use_history($user_id,$start_date, $end_date,5);
        $result[] = $this->msg_result_model->get_use_history($user_id,$start_date, $end_date,6);
        $result[] = $this->msg_result_model->get_use_history($user_id,$start_date, $end_date,7);
        $result[] = $this->msg_result_model->get_use_history($user_id,$start_date, $end_date,8);

        $this->data['result'] = $result;
        $this->data['wait_result']=$this->msg_queue_model->get_use_history($user_id,$start_date, $end_date);

            /*$this->db->select('price');
            $this->db->order_by("id asc");
            $prices = $this->db->get('notice_price')->result_array();*/
            $condition = array(
                'user_id'=>$user_id
            );
            $this->db->where($condition);
            $prices = $this->db->get('user_money')->result_array();

        /*$prices = array();
        $prices[] = 15;
        $prices[] = 50;
        $prices[] = 50;
        $prices[] = 70;
        $prices[] = 35;
        $prices[] = 70;
        $prices[] = 70;
        $prices[] = 100;*/
        $this->data['prices'] = $prices;
        //현재 회원의 잔고액 보기
        $this->data['current_money'] = $this->money_model->getCurrentMoney($user_id);
        $this->load->view('log/uselog', $this->data);
        
        $this->load->view('templates/nav-footer', $this->data);
        $this->load->view('templates/scripts', $this->data);
       $this->load->view('templates/footer', $this->data);
    }

    //관리자방식에서 요금청구결과를 excel로 다운로드하기
    public function download_excel()
    {
    //파라메터받기
        $start_date = $_GET['start_date'];
        $end_date = $_GET['end_date'];
        $stx = $_GET['stx'];
        $sql_search = '';
        if ((!$start_date)||(!$end_date)) {
            $gijun_date = date("Y-m-d");
            $gijun_start= substr($gijun_date,0,7).'-01';//이번달 1일
            $start_date = $gijun_start;
            $end_date = $gijun_date;
        }
        if ($stx) {
            $sql_search .= " and mb_nic like '%{$stx}%'";
        }
        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('요금청구');
    //머리부작성
        for($i = 1;$i <= 3; $i ++) {
            $this->excel->getActiveSheet()->setCellValue('A'.$i, '기관명');
        }
        $this->excel->getActiveSheet()->mergeCells('A1:A3');
        $this->excel->getActiveSheet()->getStyle('A1:A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //--------------
        $SMSHeader = ['B','C','D','E','F','G','H','I'];
        for($i = 0;$i < 8; $i ++) {
            $this->excel->getActiveSheet()->setCellValue($SMSHeader[$i]."1", 'S M S');
        }
        $this->excel->getActiveSheet()->mergeCells('B1:I1');
        $this->excel->getActiveSheet()->getStyle('B1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //--------------
        $LMSHeader = ['J','K','L','M','N','O','P','Q'];
        for($i = 0;$i < 8; $i ++) {
            $this->excel->getActiveSheet()->setCellValue($LMSHeader[$i]."1", 'L M S');
        }
        $this->excel->getActiveSheet()->mergeCells('J1:Q1');
        $this->excel->getActiveSheet()->getStyle('J1:Q1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //--------------
        for($i = 1;$i <= 3; $i ++) {
            $this->excel->getActiveSheet()->setCellValue('R'.$i, '합계');
        }
        $this->excel->getActiveSheet()->mergeCells('R1:R3');
        $this->excel->getActiveSheet()->getStyle('R1:R3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //--------------
        $MSGKind = ['일반문자','문서포함문자','단순설문','문서포함설문'];
        for($i = 0;$i < 4; $i ++) {
            $this->excel->getActiveSheet()->setCellValue($SMSHeader[$i * 2]."2", $MSGKind[$i]);
            $this->excel->getActiveSheet()->setCellValue($SMSHeader[$i * 2 + 1]."2", $MSGKind[$i]);
            $this->excel->getActiveSheet()->mergeCells($SMSHeader[$i * 2]."2".":".$SMSHeader[$i * 2 + 1]."2");
            $this->excel->getActiveSheet()->getStyle($SMSHeader[$i * 2]."2".":".$SMSHeader[$i * 2 + 1]."2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
        for($i = 0;$i < 4; $i ++) {
            $this->excel->getActiveSheet()->setCellValue($LMSHeader[$i * 2]."2", $MSGKind[$i]);
            $this->excel->getActiveSheet()->setCellValue($LMSHeader[$i * 2 + 1]."2", $MSGKind[$i]);
            $this->excel->getActiveSheet()->mergeCells($LMSHeader[$i * 2]."2".":".$LMSHeader[$i * 2 + 1]."2");
            $this->excel->getActiveSheet()->getStyle($LMSHeader[$i * 2]."2".":".$LMSHeader[$i * 2 + 1]."2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
        //---------------
        for($i = 0;$i < 4; $i ++) {
            $this->excel->getActiveSheet()->setCellValue($SMSHeader[$i * 2]."3", '단가');
            $this->excel->getActiveSheet()->setCellValue($SMSHeader[$i * 2 + 1]."3", '전송성공');
            $this->excel->getActiveSheet()->getStyle($SMSHeader[$i * 2]."3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle($SMSHeader[$i * 2 + 1]."3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
        for($i = 0;$i < 4; $i ++) {
            $this->excel->getActiveSheet()->setCellValue($LMSHeader[$i * 2]."3", '단가');
            $this->excel->getActiveSheet()->setCellValue($LMSHeader[$i * 2 + 1]."3", '전송성공');
            $this->excel->getActiveSheet()->getStyle($LMSHeader[$i * 2]."3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle($LMSHeader[$i * 2 + 1]."3")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
        //머리부색갈설정
        $totalHeader = array_merge(['A'],$SMSHeader,$LMSHeader,['R']);
        for($j = 1; $j <= 3; $j ++){
            for($i = 0; $i < 18 ; $i ++){
                $this->excel->getActiveSheet()->getStyle($totalHeader[$i].$j)->getFill()->applyFromArray(array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => array(
                        'rgb' => 'a9d6bb'
                    )
                ));
            }
            $this->excel->getActiveSheet()->getStyle("A".$j.":"."Z".$j)->applyFromArray(
                array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => 'black')
                        )
                    )
                )
            );
        }
    //자료기지로부터 통계자료얻기
        //기관별(회원별)정보 불러오기
        $sql = "select * from g5_member where (mb_leave_date IS NULL or mb_leave_date = '') and  (mb_intercept_date IS NULL or mb_intercept_date = '') ";
        $sql .= $sql_search; //기관이름검색
        $member_result = $this->money_model->executeQry($sql);
        $msg_result_between = "DATE_FORMAT(request_time,'%Y-%m-%d') between '" .$start_date. "' and '".$end_date."'";
        $total_sms_g_simple   = 0;
        $total_sms_g_attach   = 0;
        $total_sms_sur_simple = 0;
        $total_sms_sur_attach = 0;
        $total_lms_g_simple   = 0;
        $total_lms_g_attach   = 0;
        $total_lms_sur_simple = 0;
        $total_lms_sur_attach = 0;
        $total_price = 0;
        $j = 4;
        foreach ($member_result as $member):
            $sql = "";
            $sql .= " select ";
            $sql .= "(select count(dstaddr) from msg_result where user_msg_type = 1 and stat=3 and result='100' and " . $msg_result_between . " and user_id = ".$member['mb_no'].") as sms_g_simple, ";
            $sql .= "(select count(dstaddr) from msg_result where user_msg_type = 2 and stat=3 and result='100' and " . $msg_result_between . " and user_id = ".$member['mb_no'].") as sms_g_attach, ";
            $sql .= "(select count(dstaddr) from msg_result where user_msg_type = 3 and stat=3 and result='100' and " . $msg_result_between . " and user_id = ".$member['mb_no'].") as sms_sur_simple, ";
            $sql .= "(select count(dstaddr) from msg_result where user_msg_type = 4 and stat=3 and result='100' and " . $msg_result_between . " and user_id = ".$member['mb_no'].") as sms_sur_attach, ";
            $sql .= "(select count(dstaddr) from msg_result where user_msg_type = 5 and stat=3 and result='100' and " . $msg_result_between . " and user_id = ".$member['mb_no'].") as lms_g_simple, ";
            $sql .= "(select count(dstaddr) from msg_result where user_msg_type = 6 and stat=3 and result='100' and " . $msg_result_between . " and user_id = ".$member['mb_no'].") as lms_g_attach, ";
            $sql .= "(select count(dstaddr) from msg_result where user_msg_type = 7 and stat=3 and result='100' and " . $msg_result_between . " and user_id = ".$member['mb_no'].") as lms_sur_simple, ";
            $sql .= "(select count(dstaddr) from msg_result where user_msg_type = 8 and stat=3 and result='100' and " . $msg_result_between . " and user_id = ".$member['mb_no'].") as lms_sur_attach ";

            $d_result = $this->money_model->executeQry($sql);
            //회원의 메세지종류별가격얻기
            $sql = "select * from user_money where user_id = ".$member['mb_no'];
            $member_price_sqlResult = $this->money_model->executeQry($sql);
            if(count($member_price_sqlResult) > 0) {
                $member_price = $member_price_sqlResult[0];
                $memberTotalPrice = 0;
                //회원의 요금상황을 테블에 반영
                $i = 0;
                foreach ($d_result as $row2):
                    $mb_nick = $member['mb_nick'];
                    $sms_g_simple = $member_price['sms_g_simple'];
                    $sms_g_attach = $member_price['sms_g_attach'];
                    $sms_sur_simple = $member_price['sms_sur_simple'];
                    $sms_sur_attach = $member_price['sms_sur_attach'];
                    $lms_g_simple = $member_price['lms_g_simple'];
                    $lms_g_attach = $member_price['lms_g_attach'];
                    $lms_sur_simple = $member_price['lms_sur_simple'];
                    $lms_sur_attach = $member_price['lms_sur_attach'];

                    $this->excel->getActiveSheet()->setCellValue('A' . $j, $mb_nick);

                    $this->excel->getActiveSheet()->setCellValue('B' . $j, $sms_g_simple);
                    $this->excel->getActiveSheet()->setCellValue('C' . $j, $row2['sms_g_simple']);
                    $price1 = $row2['sms_g_simple'] * $sms_g_simple;
                    $memberTotalPrice += $price1;

                    $this->excel->getActiveSheet()->setCellValue('D' . $j, $sms_g_attach);
                    $this->excel->getActiveSheet()->setCellValue('E' . $j, $row2['sms_g_attach']);
                    $price2 = $row2['sms_g_attach'] * $sms_g_attach;
                    $memberTotalPrice += $price2;

                    $this->excel->getActiveSheet()->setCellValue('F' . $j, $sms_sur_simple);
                    $this->excel->getActiveSheet()->setCellValue('G' . $j, $row2['sms_sur_simple']);
                    $price3 = $row2['sms_sur_simple'] * $sms_sur_simple;
                    $memberTotalPrice += $price3;

                    $this->excel->getActiveSheet()->setCellValue('H' . $j, $sms_sur_attach);
                    $this->excel->getActiveSheet()->setCellValue('I' . $j, $row2['sms_sur_attach']);
                    $price4 = $row2['sms_sur_attach'] * $sms_sur_attach;
                    $memberTotalPrice += $price4;

                    $this->excel->getActiveSheet()->setCellValue('J' . $j, $lms_g_simple);
                    $this->excel->getActiveSheet()->setCellValue('K' . $j, $row2['lms_g_simple']);
                    $price5 = $row2['lms_g_simple'] * $lms_g_simple;
                    $memberTotalPrice += $price5;

                    $this->excel->getActiveSheet()->setCellValue('L' . $j, $lms_g_attach);
                    $this->excel->getActiveSheet()->setCellValue('M' . $j, $row2['lms_g_attach']);
                    $price6 = $row2['lms_g_attach'] * $lms_g_attach;
                    $memberTotalPrice += $price6;

                    $this->excel->getActiveSheet()->setCellValue('N' . $j, $lms_sur_simple);
                    $this->excel->getActiveSheet()->setCellValue('O' . $j, $row2['lms_sur_simple']);
                    $price7 = $row2['lms_sur_simple'] * $lms_sur_simple;
                    $memberTotalPrice += $price7;

                    $this->excel->getActiveSheet()->setCellValue('P' . $j, $lms_sur_attach);
                    $this->excel->getActiveSheet()->setCellValue('Q' . $j, $row2['lms_sur_attach']);
                    $price8 = $row2['lms_sur_attach'] * $lms_sur_attach;
                    $memberTotalPrice += $price8;

                    $this->excel->getActiveSheet()->setCellValue('R' . $j, $memberTotalPrice);

                    //합계를 위해 종류별 <건수>를 증가
                    $total_sms_g_simple += $row2['sms_g_simple'];
                    $total_sms_g_attach += $row2['sms_g_attach'];
                    $total_sms_sur_simple += $row2['sms_sur_simple'];
                    $total_sms_sur_attach += $row2['sms_sur_attach'];
                    $total_lms_g_simple += $row2['lms_g_simple'];
                    $total_lms_g_attach += $row2['lms_g_attach'];
                    $total_lms_sur_simple += $row2['lms_sur_simple'];
                    $total_lms_sur_attach += $row2['lms_sur_attach'];
                    //합계를 위해 종류별 <가격>을 증가
                    $total_price += $memberTotalPrice;
                    $i++;
                endforeach;
                $j++;
            }
        endforeach;
        //합계
        if ($i != 0) {
            $this->excel->getActiveSheet()->setCellValue('A'.$j, '합계');
            $this->excel->getActiveSheet()->setCellValue('C'.$j, $total_sms_g_simple);
            $this->excel->getActiveSheet()->setCellValue('E'.$j, $total_sms_g_attach);
            $this->excel->getActiveSheet()->setCellValue('G'.$j, $total_sms_sur_simple);
            $this->excel->getActiveSheet()->setCellValue('I'.$j, $total_sms_sur_attach);
            $this->excel->getActiveSheet()->setCellValue('K'.$j, $total_lms_g_simple);
            $this->excel->getActiveSheet()->setCellValue('M'.$j, $total_lms_g_attach);
            $this->excel->getActiveSheet()->setCellValue('O'.$j, $total_lms_sur_simple);
            $this->excel->getActiveSheet()->setCellValue('Q'.$j, $total_lms_sur_attach);
            $this->excel->getActiveSheet()->setCellValue('R'.$j, $total_price);
        }
        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(26);
        $filename='요금청구.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.urlencode($filename).'"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');
    }
}

?>
