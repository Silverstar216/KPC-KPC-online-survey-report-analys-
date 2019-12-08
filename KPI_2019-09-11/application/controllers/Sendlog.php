<?php
/**
 * Author: KMC
 * Date: 10/6/15
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Sendlog extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();


        // date_default_timezone_set('Asia/Pyongyang');

        $this->data['title'] = SITE_TITLE;
        $this->data['styles'] = array(
            'include/css/log.css',
            'include/css/survey.css',            
            'include/plugins/font-awesome/css/font-awesome.min.css',
            'include/plugins/bootstrap-sweetalert/sweetalert.css',
            'include/lib/jquery.datetimepicker.css',
        );

        $this->data['scripts'] = array(
            'include/plugins/bootstrap-sweetalert/sweetalert.min.js',
            'include/lib/jquery.datetimepicker.js',
            'include/js/log/sendlog.js',
            'include/js/pagination.js'
        );


        $this->load->model('notices_model');
        $this->load->model('msg_result_model');
    }

    public function index()
    {
        if (is_signed()) {
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
                $start_date=date('Y-m-d',$str_date);
    
            }
            if (empty($end_date))
            {
    
                $end_date = date('Y-m-d');
            }
            if($end_date === date('Y-m-d')) {
                $end_date = date('Y-m-d');
            }
            $this->data['start_date'] = $start_date;
            $this->data['end_date'] = $end_date;
            $this->data['total_count'] =0;
            $this->data['menu'] = '문자메시지';
            $this->data['submenu'] = '전송결과';    

            /* $this->session->set_userdata('sendlog', $result);*/
            $this->load->view('log/sendlog', $this->data);
            $this->load->view('templates/nav-footer', $this->data);
            $this->load->view('templates/scripts', $this->data);
            $this->load->view('templates/footer', $this->data);
        }
        else {
            ?>
            <script type="text/javascript">                
                alert("로그인상태에서만 이용하실수 있습니다.");
                base_url = "<?=$GLOBALS['protocol']?>://" + location.host + "/index";
                window.location = base_url;
            </script>
            <?php        
        }
    }

    public function getSendList()
    {
        $this->session->unset_userdata('sendlog');
        $user_id = get_session_user_id();
        $userauth= get_session_user_level();
        if($userauth ==="") {
            $userauth = "";
            $user_id = -1;
        }
        $start_date =$_GET['st'];
        $end_date = $_GET['et'];
        $page =isset($_GET['page']) ? $_GET['page']:0;
        $count =isset($_GET['count']) ? $_GET['count']:10;

        if (empty($start_date))
        {
            $now_date = date('Y-m-d');
            $str_date = strtotime($now_date.'-7 days');
            $start_date = date('Y-m-d H:i', $str_date);
        }

        if (empty($end_date) || $end_date === date('Y-m-d'))
        {
            $end_date = date('Y-m-d H:i');
        }        
        else {
            $end_date_str = strtotime($end_date.'+1 days');
            $end_date = date('Y-m-d', $end_date_str);
        }

        $result = $this->notices_model->get_data_send($user_id,$start_date,$end_date,$page,$count);        
        $this->data['result'] = $result;
        $this->data['total_count'] =  $this->notices_model->get_total_send($user_id,$start_date,$end_date)[0]['total'];
        $this->data['page'] = $page;
        $this->data['page_count'] = $count;

        $this->session->set_userdata('sendlog', $result);
        $this->load->view('log/sendlog_list', $this->data);

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

        $this->excel->getActiveSheet()->setTitle('결과분석');

        $result = $this->session->userdata('sendlog');
        $idx = 1;

        $this->excel->getActiveSheet()->setCellValue('A' . $idx, '번호');
        $this->excel->getActiveSheet()->setCellValue('B' . $idx, '메세지');
        $this->excel->getActiveSheet()->setCellValue('C' . $idx, '전송일시');
        $this->excel->getActiveSheet()->setCellValue('D' . $idx, '총건');
        $this->excel->getActiveSheet()->setCellValue('E' . $idx, '성공');
        $this->excel->getActiveSheet()->setCellValue('F' . $idx, '실패');
        $this->excel->getActiveSheet()->setCellValue('G' . $idx, '대기');
        $this->excel->getActiveSheet()->setCellValue('H' . $idx, '조회');
        $this->excel->getActiveSheet()->setCellValue('I' . $idx, '첨부문서');

        foreach ($result as $rec) {
            $idx++;
            $this->excel->getActiveSheet()->setCellValue('A' . $idx, $idx-1 );
            $this->excel->getActiveSheet()->setCellValue('B' . $idx, $rec['content']);
            $this->excel->getActiveSheet()->setCellValue('C' . $idx, $rec['start_time']);
            $this->excel->getActiveSheet()->setCellValue('D' . $idx, $rec['successCount']);
            $this->excel->getActiveSheet()->setCellValue('E' . $idx, $rec['failureCount']);
            $this->excel->getActiveSheet()->setCellValue('G' . $idx, $rec['waitCount']);
            $this->excel->getActiveSheet()->setCellValue('H' . $idx, $rec['reply_count']);
            $this->excel->getActiveSheet()->setCellValue('I' . $idx, $rec['file_url']);

        }

//        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
//        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
//        $this->excel->getActiveSheet()->mergeCells('A1:D1');
//        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);



        $filename='전송결과분석.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.urlencode($filename).'"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('php://output');

    }
}

/* End of file home.php */
/* Location: ./application/controllers/Home.php */
