<?php
/**
 * Author: KMC
 * Date: 10/6/15
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Reservelog extends MY_Controller
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
            'include/lib/jquery.datetimepicker.css'
        );

        $this->data['scripts'] = array(
            'include/plugins/bootstrap-sweetalert/sweetalert.min.js',
            'include/lib/jquery.datetimepicker.js',
            'include/js/log/reservelog.js',
            'include/js/pagination.js'
        );

        $this->load->model('surveys_model');
        $this->load->model('notices_model');
        $this->load->model('msg_queue_model');
        $this->load->model('questions_model');
    }

    public function index()
    {
        if (is_signed()) {
            $this->load->view('templates/header', $this->data);
            $this->load->view('templates/nav', $this->data);
    
            $start_date = $this->input->get_post('start_date');
            $end_date = $this->input->get_post('end_date');
            if (empty($start_date))
            {
    
                $start_date = date('Y-m-d');
            }
            if (empty($end_date))
            {
                $now_date = date('Y-m-d');
                $str_date=strtotime($now_date.'+7 days');
                $end_date=date('Y-m-d',$str_date);
            }
            $this->data['start_date'] = $start_date;
            $this->data['end_date'] = $end_date;
            $this->data['total_count'] =0;
            $this->data['menu'] = '문자메시지';
            $this->data['submenu'] = '예약내역';  

            $this->load->view('log/reservelog', $this->data);
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

    public function getReserveList()
    {

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
        if (empty($start_date)|| $start_date == date('Y-m-d'))
        {

            $start_date = date('Y-m-d H:i');
        }
        if (empty($end_date))
        {
            $now_date = date('Y-m-d H:i');
            $str_date=strtotime($now_date.'+7 days');
            $end_date=date('Y-m-d H:i',$str_date);
        }




        $result = $this->notices_model->get_data_reserve($user_id,$start_date,$end_date,$page,$count);
        $this->data['result'] = $result;
        $this->data['total_count'] =  $this->notices_model->get_total_reserve($user_id,$start_date,$end_date)[0]['total'];
        $this->data['page'] = $page;
        $this->data['page_count'] = $count;
        $this->load->view('log/reservelog_list', $this->data);
        //echo $this->data['mobiles'];
    }
    /*
             * 예약시간변경
             * */
    public function setReserveTime()
    {
        $user_id = get_session_user_id();
        $notice_id = $this->input->get_post('notice_id');
        $start_time = $this->input->get_post('start_time');
        $result = $this->notices_model->update_Reserve_Time($user_id, $notice_id, $start_time);
        if ($result > 0) {
            $result = $this->msg_queue_model->update_Reserve_Time($user_id, $notice_id, $start_time);
        }
        return $result;
    }

        /*
         * 예약내역에서 삭제할때 일반문자와 설문을 같이 삭제한다.
         * */
    public function delMessage() {
        $user_id = get_session_user_id();
        $notice_id = $this->input->get_post('notice_id');
        $survey=$this->notices_model->get_survey_id($notice_id);
        if($survey->survey_id !=null && $survey->survey_id> 0) {
            $flag =$this->surveys_model->delete_by_id($survey->survey_id);
            $this->questions_model->delete_by_id($survey->survey_id);
        }
        $this->notices_model->delete_data_by_id($user_id,$notice_id);

        $this->msg_queue_model->delete_data_by_id($user_id,$notice_id);

        return 1;
    }
}

/* End of file home.php */
/* Location: ./application/controllers/Home.php */