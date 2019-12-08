<?php
/**
 * Author: KMC
 * Date: 10/6/15
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Sendlogdetail extends MY_Controller
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
            'include/js/log/sendlogdetail.js',
            'include/js/pagination.js'
        );

        $this->load->model('surveys_model');
        $this->load->model('notices_model');
        $this->load->model('messages_model');
        $this->load->model('msg_result_model');
        $this->load->model('msg_queue_model');
    }

    public function index()
    {


        $this->load->view('templates/header', $this->data);
        $this->load->view('templates/nav', $this->data);


        $parent = 'start_date=' . $this->input->get_post('parent_start_date') . '&end_date=' . $this->input->get_post('parent_end_date');
        $this->data['parent'] = $parent;


        $notice_id = $this->input->get_post('notice_id');
        $this->data['notice_id'] = $notice_id;
        
        $notice = $this->notices_model->get_data_by_id($notice_id);
        $this->data['notice'] = $notice[0];

        $this->load->view('log/sendlogdetail', $this->data);
        $this->load->view('templates/nav-footer', $this->data);
        $this->load->view('templates/scripts', $this->data);
       $this->load->view('templates/footer', $this->data);
    }
    public function getSendDetailList()
    {

        $notice_id =$_GET['ni'];
        $mobile = $_GET['m'];
        $page =isset($_GET['page']) ? $_GET['page']:0;
        $count =isset($_GET['count']) ? $_GET['count']:10;

            $success_result = $this->msg_result_model->get_data_notice_id($notice_id,$mobile,$page,$count);
            $total_count = $this->msg_result_model->get_total_notice_id($notice_id,$mobile)[0]['total'];


        $this->data['success_result'] = $success_result;
        $this->data['total_count'] =  $total_count;
        $this->data['page_count'] = $count;
        $this->data['page'] = $page;
        $this->session->set_userdata('sendlogdetail', $success_result);
        $this->load->view('log/sendlogdetail_list', $this->data);

        //echo $this->data['mobiles'];
    }
    public function download_excel()
    {
        $this->load->library('excel');
        $user_id = get_session_user_id();
        $userauth= get_session_user_level();
        if($userauth ==="") {
            $userauth = "";
            $user_id = -1;
        }

        $this->excel->setActiveSheetIndex(0);

        $this->excel->getActiveSheet()->setTitle('결과상세분석');

        $result = $this->session->userdata('sendlogdetail');
        $idx = 1;

        $this->excel->getActiveSheet()->setCellValue('A' . $idx, '번호');
        $this->excel->getActiveSheet()->setCellValue('B' . $idx, '전송시각');
        $this->excel->getActiveSheet()->setCellValue('C' . $idx, 'URL');
        $this->excel->getActiveSheet()->setCellValue('D' . $idx, '수신번호');
        $this->excel->getActiveSheet()->setCellValue('E' . $idx, '전송결과');
        $this->excel->getActiveSheet()->setCellValue('F' . $idx, '응답여부');


        foreach ($result as $rec) {
            $idx++;




            $this->excel->getActiveSheet()->setCellValue('A' . $idx, $idx-1 );
            $this->excel->getActiveSheet()->setCellValue('B' . $idx, $rec['send_time']);
            $url = "";
            $index = stripos($rec['text'],"http");
            if($index !==false)
                $url = substr($rec['text'],$index,strlen($rec['text']));

            $this->excel->getActiveSheet()->setCellValue('C' . $idx, $url);
            $this->excel->getActiveSheet()->setCellValue('D' . $idx, $rec['dstaddr']);
            $success_text = "";
                if($rec['stat'] < 3) {
                    $success_text= "송신중";
                } else if($rec['result']==='100') {
                    $success_text= "전송성공";
                } else if($rec['result']==='201') {
                    $success_text= "착신가입자없음";
                } else if($rec['result']==='208') {
                    $success_text= "사용정지된번호";
                } else if($rec['result']==='304') {
                    $success_text= "핸드폰꺼짐";
                } else {
                    $success_text= "실패";
                }

            $this->excel->getActiveSheet()->setCellValue('E' . $idx, $success_text);
                $responsive = "";




        }

//        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
//        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
//        $this->excel->getActiveSheet()->mergeCells('A1:D1');
//        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(30);



        $filename='결과상세분석.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.urlencode($filename).'"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');

    }
}

/* End of file home.php */
/* Location: ./application/controllers/Home.php */