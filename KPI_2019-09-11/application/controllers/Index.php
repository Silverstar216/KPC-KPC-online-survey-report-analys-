<?php
/**
 * Author: KMC
 * Date: 10/6/15
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Seoul");
        // date_default_timezone_set('Asia/Pyongyang');

        $this->data['title'] = SITE_TITLE;
        $this->data['styles'] = array(
            'include/css/log.css',            
            'include/css/index.css',
            'include/css/survey.css',
            'include/css/components.css',
            'include/plugins/font-awesome/css/font-awesome.min.css',
            'include/plugins/bootstrap-sweetalert/sweetalert.css',
            'include/lib/jquery.datetimepicker.css',            
        );

        $this->data['scripts'] = array(
            'include/plugins/bootstrap-sweetalert/sweetalert.min.js',
            'include/js/index.js'
        );

        $this->load->model('site_datas_model');
        $this->load->model('users_model');

        $sql = " select * from g5_new_win where SYSDATE() between nw_begin_time and nw_end_time and nw_device IN ( 'both', 'pc' ) and nw_division IN ( 'both', 'comm' ) order by nw_id asc ";
        $query = $this->db->query($sql);
        $this->data['nw']=$query->result_array();
    }

    public function update_erp_data()
    {
        // $this->users_model->update_from_erp_mssql_subject();
        $this->users_model->update_from_erp_mssql_course(); 

        exit;

        $this->index();
    }

    public function index()
    {
        $this->load->view('templates/header', $this->data);
        $this->load->view('templates/nav', $this->data);
        
        if (is_signed()) {
            $this->data['scripts'] = array(
                'include/plugins/bootstrap-sweetalert/sweetalert.min.js',
                'include/js/survey/manage.js',
                'include/js/pagination.js',
                'include/lib/jquery.datetimepicker.js',
            );
    
            $userid = get_session_user_id();
            $userauth= get_session_user_level();
            if($userauth ==="") {
                $userauth = "";
                $userid = -1;
            }
    
            $this->data['username'] = $this->users_model->get_user_name($userid);
            $this->data['user_level'] = $userauth;
            $this->data['stval'] = "";            
            $this->data['userid'] = $userid;
            $this->data['is_landing'] = 1;
            $this->data['survey_total_count'] = 0;  
            $this->data['survey_id'] = 0;
            $this->data['newflag'] = 0;
            $this->data['attached'] = 0;          
            $this->data['view_flag'] = 4;   // 교육과정목록보기
            $this->data['survey_flag'] = 1; 
            $this->data['menu'] = '공개교육 설문';
            $this->data['submenu'] = '교육과정';    
            $this->data['selected_survey_name'] = '';

            $this->load->view('survey/survey_list', $this->data);
        }
        else {
            $this->data['prev_userid'] = $this->users_model->get_last_loginuser($_SERVER['REMOTE_ADDR']);
            $this->load->view('index/index', $this->data);
            $this->load->view('index/news_win', $this->data);    
        }
        $this->load->view('templates/nav-footer', $this->data);
        $this->load->view('templates/scripts', $this->data);
        $this->load->view('templates/footer', $this->data);
    }

       
    public function introduce()
    {
        $this->load->view('templates/header', $this->data);
        $this->load->view('templates/nav', $this->data);

        $key = $this->input->get_post('key');

        $result = $this->site_datas_model->get_data_by_key($key);
        $this->data['result'] = $result[0];
        $this->load->view('index/introduce', $this->data);

        $this->load->view('templates/nav-footer', $this->data);
        $this->load->view('templates/scripts', $this->data);
       $this->load->view('templates/footer', $this->data);
    }

    public function introduce_img()
    {
        $this->load->view('templates/header', $this->data);
        $this->load->view('templates/nav', $this->data);

        $key = $this->input->get_post('key');
        $this->data['key'] =$key;

        $this->load->view('index/introduce_img', $this->data);

        $this->load->view('templates/nav-footer', $this->data);
        $this->load->view('templates/scripts', $this->data);
       $this->load->view('templates/footer', $this->data);
    }

    public function money_introduce()
    {

        $prices_1 = array();
        $prices_1[] = 15;
        $prices_1[] = 50;
        $prices_1[] = 50;
        $prices_1[] = 70;



        $prices_2 = array();
        $prices_2[] = 35;
        $prices_2[] = 70;
        $prices_2[] = 70;
        $prices_2[] = 100;

        $this->data['prices_1'] = $prices_1;
        $this->data['prices_2'] = $prices_2;

        $this->load->view('templates/header', $this->data);
        $this->load->view('templates/nav', $this->data);



        $this->load->view('index/money_introduce', $this->data);

        $this->load->view('templates/nav-footer', $this->data);
        $this->load->view('templates/scripts', $this->data);
        $this->load->view('templates/footer', $this->data);
    }
    public function faq(){
        $this->load->view('templates/header', $this->data);
        $this->load->view('templates/nav', $this->data);

        $this->load->view('index/faq', $this->data);

        $this->load->view('templates/nav-footer', $this->data);
        $this->load->view('templates/scripts', $this->data);
        $this->load->view('templates/footer', $this->data);
    }
    public function notice_subject(){
        $this->load->view('templates/header', $this->data);
        $this->load->view('templates/nav', $this->data);



        $this->load->view('index/notice_subject', $this->data);

        $this->load->view('templates/nav-footer', $this->data);
        $this->load->view('templates/scripts', $this->data);
        $this->load->view('templates/footer', $this->data);
    }
    public function board(){

        $bo_table = isset($_GET['bo_table']) ? $_GET['bo_table']:'';
        $this->data['table_kind'] =$bo_table;
        $this->load->view('templates/header', $this->data);
        $this->load->view('templates/nav', $this->data);
        $this->data['disableLoadingIcon'] = true;
        $this->load->view('index/board', $this->data);

        $this->load->view('templates/nav-footer', $this->data);
        $this->load->view('templates/scripts', $this->data);
        $this->load->view('templates/footer', $this->data);
    }
    public function board_write(){

        $this->load->view('templates/header', $this->data);
        $this->load->view('templates/nav', $this->data);
        $this->load->view('index/board_write', $this->data);
        $this->load->view('templates/nav-footer', $this->data);
        $this->load->view('templates/scripts', $this->data);
        $this->load->view('templates/footer', $this->data);
    }
    public function data_view(){
        $this->load->view('templates/header', $this->data);
        $this->load->view('templates/nav', $this->data);



        $this->load->view('index/data_view', $this->data);

        $this->load->view('templates/nav-footer', $this->data);
        $this->load->view('templates/scripts', $this->data);
        $this->load->view('templates/footer', $this->data);
    }

    public function download($key)
    {
        /*$key = $this->input->post('key');*/
        $this->load->helper('download');

        $base_path = $this->input->server('DOCUMENT_ROOT').base_url();
       /* $p_id = "application/userGuide_file/useGuide.doc";
        $filename = '이용신청소.doc';*/
        if($key ==="1") {
            $p_id = "application/userGuide_file/useGuide.docx";
            $filename = '이용신청서.docx';
        } else if($key ==='2') {
            $p_id = "application/userGuide_file/useGuide.pdf";
            $filename = '이용신청서.pdf';
        } else {
            $p_id = "application/userGuide_file/useGuide.hwp";
            $filename = '이용신청서.hwp';
        }
        $file = $base_path.$p_id;
        // 내리적재 권한검사
      /*  $pth    =   file_get_contents($file);*/



        force_download($file, NULL, FALSE, $filename);




    }
    function _push_file($path, $name)
    {
        // make sure it's a file before doing anything!
        if(is_file($path))
        {
            // required for IE
            if(ini_get('zlib.output_compression')) { ini_set('zlib.output_compression', 'Off'); }

            // get the file mime type using the file extension
            $this->load->helper('file');

            $mime = get_mime_by_extension($path);

            // Build the headers to push out the file properly.
            header('Pragma: public');     // required
            header('Expires: 0');         // no cache
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($path)).' GMT');
            header('Cache-Control: private',false);
            header('Content-Type: '.$mime);  // Add the mime type from Code igniter.
            header('Content-Disposition: attachment; filename="'.basename($name).'"');  // Add the file name
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: '.filesize($path)); // provide file size
            header('Connection: close');
            readfile($path); // push it out
            exit();
        }
    }
}

/* End of file home.php */
/* Location: ./application/controllers/Home.php */
