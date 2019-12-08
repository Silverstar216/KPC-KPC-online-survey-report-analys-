<?php
/**
 * Author: KMC
 * Date: 10/6/15
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class D_reviewlog extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

//        check_signed();
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
            'include/js/log/d_reviewlog.js',
            'include/js/index.js',
            'include/js/pagination.js'
        );

        $this->load->model('d_reviews_model');
    }

    public function index()
    {
        if (is_signed()) {
            //        $user_id = 1;
            $user_id = get_session_user_id();

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
            $this->data['menu'] = '진단';
            $this->data['submenu'] = '진단결과';    

            $this->load->view('log/d_reviewlog', $this->data);
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
    public function get_reviewlog_list()
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
        if (empty($start_date))
        {

            $now_date = date('Y-m-d');
            $str_date=strtotime($now_date.'-7 days');
            $start_date=date('Y-m-d H:i',$str_date);

        }
        if (empty($end_date))
        {

            $end_date = date('Y-m-d H:i');
        }
        if($end_date === date('Y-m-d')) {
            $end_date = date('Y-m-d H:i');
        }

        $result = $this->d_reviews_model->get_data_review($user_id,$start_date,$end_date,$page,$count);
        $this->data['result'] = $result;
        $this->data['total_count'] =  $this->d_reviews_model->get_total_review($user_id,$start_date,$end_date)[0]['total'];
        $this->data['page'] = $page;
        $this->data['page_count'] = $count;
        $this->load->view('log/d_reviewlog_list', $this->data);
        //echo $this->data['mobiles'];
    }
    public function download($p_id)
    {
        $p_id = 6;
        // 내리적재 권한검사

        $this->load->helper('download');

        $base_path = $this->input->server('DOCUMENT_ROOT') . base_url();
        $file = sprintf('%suploads/questions/%d.PNG', $base_path, $p_id);
        $filename = '시험.png';
        force_download($file, NULL, FALSE, $filename);
    }
}

/* End of file home.php */
/* Location: ./application/controllers/Home.php */