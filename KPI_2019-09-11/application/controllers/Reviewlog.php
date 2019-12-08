<?php
/**
 * Author: KMC
 * Date: 10/6/15
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Reviewlog extends MY_Controller
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
            'include/js/log/reviewlog.js',
            'include/js/index.js',
            'include/js/pagination.js'
        );

        $this->load->model('reviews_model');
        $this->load->model('notices_model');
        $this->load->model('educations_model');
    }

    public function showresult($survey_flag)
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

            if ($survey_flag === 'public') {
                $this->data['survey_flag'] = 1; 
                $this->data['menu'] = '공개교육 설문';
                $this->data['submenu'] = '설문결과';    
            }
            else {
                $this->data['survey_flag'] = 0; 
                $this->data['menu'] = '맞춤형 설문';
                $this->data['submenu'] = '설문결과';    
            }

            $this->load->view('log/reviewlog', $this->data);
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
        $survey_flag =isset($_GET['survey_flag']) ? $_GET['survey_flag']:1;
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

        $result = $this->reviews_model->get_data_review($user_id, $survey_flag, $start_date, $end_date, $page, $count);
        // for ($i = 0; $i < count($result); $i++) {     
        //     $education = $this->educations_model->get_education_schedule_fromid($result[$i]['education_id']); 
        //     if (count($education) > 0) {
        //         $result[$i]['subject_name'] = $education[0]['subject_name'];
        //     }
        // }

        $this->data['result'] = $result;
        $this->data['total_count'] =  $this->reviews_model->get_total_review($user_id, $survey_flag, $start_date,$end_date)[0]['total'];
        $this->data['page'] = $page;
        $this->data['page_count'] = $count;
        $this->data['survey_flag'] = $survey_flag;
        
        $this->load->view('log/reviewlog_list', $this->data);        
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

    public function delete_review()
    {
        $user_id = get_session_user_id();

        if(empty($user_id) || $user_id==="") {
            $user_id = -1;
            echo -1;

        } else {
            $notice_id = $this->input->get_post('selected_review_id');
            $condition = array(
                'id' => $notice_id
            );
            $this->notices_model->delete_data($condition);
            echo 1;
        }  
    }
}

/* End of file home.php */
/* Location: ./application/controllers/Home.php */