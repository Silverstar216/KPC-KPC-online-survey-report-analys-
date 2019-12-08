<?php
/**
 * Author: KMC
 * Date: 10/7/18
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Survey extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        // date_default_timezone_set('Asia/Pyongyang');
        $this->load->helper('control_helper');
        $this->data['title'] = SITE_TITLE;
        $this->data['styles'] = array(
            'include/css/survey.css',
            'include/plugins/font-awesome/css/font-awesome.min.css',
            'include/plugins/bootstrap-sweetalert/sweetalert.css',
            'include/lib/jquery.datetimepicker.css',
            'include/lib/nouislider/nouislider.min.css',
            'include/plugins/jquery-file-upload/css/jquery.fileupload.css',
            'include/plugins/plupload/js/jquery.ui.plupload/css/jquery.ui.plupload.css',
            'include/css/plugins.css',
            'include/lib/jquery.fileupload/css/jquery.fileupload.css'
        );

        $this->data['scripts'] = array(
            'include/plugins/bootstrap-sweetalert/sweetalert.min.js',
            'include/lib/jquery.datetimepicker.js',
            'include/lib/nouislider/nouislider.min.js',            
            'include/lib/jquery.fileupload/js/jquery.iframe-transport.js',            
            'include/lib/jquery.fileupload/js/jquery.fileupload.js',
            'include/plugins/plupload/js/plupload.full.min.js',
            'include/plugins/plupload/js/jquery.ui.plupload/jquery.ui.plupload.min.js',
            'include/plugins/plupload/js/i18n/ko.js',
            'include/js/survey/edit.js',
            'include/js/pagination.js'
        );

        $this->load->model('users_model');
        $this->load->model('surveys_model');
        $this->load->model('educations_model');
        $this->load->model('questions_model');
        $this->load->model('question_groups_model');
        $this->load->model('question_exam_kinds_model');
        $this->load->model('question_exam_objects_model');
        $this->load->model('examples_model');


        $this->load->helper('my_directory');
        $this->load->helper('my_url');

        $GLOBALS['survey']['fav_grades'] = array(
            0 => '매우 불만족',
            1 => '불만족',
            2 => '보통',
            3 => '만족',
            4 => '매우 만족'
        );

        $GLOBALS['survey']['fav_grades_3'] = array(
            0 => '불만족',
            1 => '보통',
            2 => '만족'
        );

    }

    /*
    * 설문새로작성
    * $survey_type : public:    공개설문작성  
    * $survey_type : advanced:  맞춤형설문작성
    * */
    public function write_survey($survey_type)
    {
        if (is_signed()) {
            $userauth= get_session_user_level();
            if($userauth ==="") {
                $userauth = "";
            }
            
            $this->data['attached'] = 0;
            if ($survey_type === 'public') {
                $this->data['menu'] = '공개교육 설문';
                $this->data['submenu'] = '설문작성';    
                $this->data['survey_flag'] = 1;
            }
            else {
                $this->data['menu'] = '맞춤형 설문';
                $this->data['submenu'] = '설문작성';    
                $this->data['survey_flag'] = 0;
            }
            $this->data['survey_id'] = '';            
            $this->data['newflag'] = 1;  //새로작성

            $this->load->view('templates/header', $this->data);
            $this->load->view('templates/nav', $this->data);
            $this->load->view('survey/survey_create', $this->data);
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

    public function view()
    {
        $education_id = $this->input->get_post('education_id');
        $survey_id = $this->input->get_post('survey_id');
        $survey_flag = $this->input->get_post('survey_flag');
        $newflag = $this->input->get_post('newflag');
        
        $attached = $this->input->get_post('attached');
        $education_title = $this->input->get_post('education_title');
        $survey_start_date = $this->input->get_post('survey_start_date');
        $survey_end_date = $this->input->get_post('survey_end_date');

        $sms_available = isset($_GET['sms_available']) ? $_GET['sms_available']:0 ;
        $education_course = isset($_GET['education_course']) ? $_GET['education_course'] : '';
        $education_customer = isset($_GET['education_customer']) ? $_GET['education_customer'] : '';
        $education_teacher = isset($_GET['education_teacher']) ? $_GET['education_teacher'] : '';


        $userauth = get_session_user_level();
        if($userauth ==="") {
            $userauth = "";
            redirect('survey');
        }

        $survey=array();
        if (empty($survey_id)) {
            $survey_id = 0;
        }

        if (empty($education_id)) {
            $education_id = 0;
        }

        if (empty($education_title)) {
            $education_title = '';
        }

        $userid = get_session_user_id();
        $is_public_enable = "0";    // public disable
        if ($survey_id > 0) {
            $survey = $this->surveys_model->get_data_by_id($survey_id);  
            if (count($survey) > 0 && 
                ($userid == "300" || $survey[0]['user_id'] == $userid))
                $is_public_enable = "1";    // public show
        }

        $education = $this->educations_model->get_education_schedule_fromid($education_id);        
        $survey = $this->surveys_model->get_data_by_id($survey_id);  
        if (count($survey) > 0)
            $education_title = $survey[0]['title'];

        $this->data['education']= $education;
        $this->data['survey_id'] = $survey_id;        
        $this->data['survey_flag'] = $survey_flag;
        $this->data['is_public_enable'] = $is_public_enable;
        $this->data['newflag'] = $newflag;
        $this->data['user_level'] = $userauth;
        $this->data['education_title'] = $education_title;
        $this->data['survey_start_date'] = $survey_start_date;
        $this->data['survey_end_date'] = $survey_end_date;

        $this->data['sms_available'] = $sms_available;
        $this->data['education_course']= $education_course;
        $this->data['education_customer']= $education_customer;
        $this->data['education_teacher']= $education_teacher;

        if ($survey_flag ==1) {
            $this->data['menu'] = '공개교육 설문';
        }
        else {
            $this->data['menu'] = '맞춤형 설문';
        }
        $this->data['submenu'] = '설문작성';    

        $this->load->view('templates/header', $this->data);
        $this->load->view('templates/nav', $this->data);

        if(!empty($attached) && $attached > 0) {
            $this->data['attached'] = $attached;
            $this->data['subtitle'] = '문서포함설문';
            $this->load->view('survey/edit', $this->data);
            $this->load->view('survey/modal_attachedHTML', $this->data);
        } else {
            $this->data['attached'] = 0;
            $this->data['subtitle'] = '단순설문';
            $this->load->view('survey/edit', $this->data);
        }

        $this->load->view('survey/modal_questions', $this->data);
        $this->load->view('templates/nav-footer', $this->data);
        $this->load->view('templates/footer', $this->data);$this->load->view('templates/scripts', $this->data);
    }   

    public function attached()
    {
        $this->data['attached'] = 1;
        $this->data['subtitle'] = '문서포함설문';

        $this->data['survey_id'] = '';
        $this->data['newflag']=1;//새로작성
        $this->load->view('templates/header', $this->data);
        $this->load->view('templates/nav', $this->data);
        $this->load->view('survey/edit', $this->data);
        $this->load->view('survey/modal_attachedHTML', $this->data);
        $this->load->view('survey/modal_questions', $this->data);
        $this->load->view('templates/nav-footer', $this->data);
        $this->load->view('templates/scripts', $this->data);
        $this->load->view('templates/footer', $this->data);
    }

   /*
    * 설문목록보기
    * $survey_type : public:    공개설문목록  
    * $survey_type : advanced:  맞춤형설문목록
    * */
    public function survey_list($survey_type)
    {
        if (is_signed())
        {
            $this->data['scripts'] = array(
                'include/plugins/bootstrap-sweetalert/sweetalert.min.js',
                'include/js/survey/manage.js',
                'include/js/pagination.js',
                'include/lib/jquery.datetimepicker.js',
                'include/lib/jquery.fileupload/js/jquery.fileupload.js',
            );
                      
            $selected_survey_name = '';    
            $survey_id = $this->input->get_post('survey_id');
            $newflag = $this->input->get_post('newflag');
            $attached = $this->input->get_post('attached');
            
            if (empty($survey_id)) 
                $survey_id = 0;      
            if (empty($newflag))
                $newflag = 0;      
            if (empty($attached))
                $attached = 0;     
            
            if ($survey_id > 0) {
                $survey = $this->surveys_model->get_data_by_id($survey_id);  
                if (count($survey) > 0)
                    $selected_survey_name = $survey[0]['title'];
            }

            $userid = get_session_user_id();
            $userauth= get_session_user_level();
            if($userauth ==="") {
                $userauth = "";
                $userid = -1;
            }
                
            $this->data['user_level'] = $userauth;
            $this->data['stval'] = "";            
            $this->data['userid'] = $userid;
            $this->data['username'] = $this->users_model->get_user_name($userid);
            $this->data['survey_total_count'] = 0;            
            $this->data['survey_id'] = $survey_id;
            $this->data['newflag'] = $newflag;
            $this->data['attached'] = $attached;
            $this->data['selected_survey_name'] = $selected_survey_name;

            $this->data['view_flag'] = 4;   // 교육과정목록보기
            if ($survey_type === 'public') {
                $this->data['survey_flag'] = 1; 
                $this->data['menu'] = '공개교육 설문';
                $this->data['submenu'] = '교육과정';    
            }
            else {
                $this->data['survey_flag'] = 0; 
                $this->data['menu'] = '맞춤형 설문';
                $this->data['submenu'] = '교육과정';    
            }

            $this->load->view('templates/header', $this->data);
            $this->load->view('templates/nav', $this->data);
            $this->load->view('survey/survey_list', $this->data);
            $this->load->view('templates/nav-footer', $this->data);
            $this->load->view('templates/scripts', $this->data);
            $this->load->view('templates/footer', $this->data);    
        }
        else 
        {    
            ?>
            <script type="text/javascript">                
                alert("로그인상태에서만 이용하실수 있습니다.");
                base_url = "<?=$GLOBALS['protocol']?>://" + location.host + "/index";
                window.location = base_url;
            </script>
            <?php            
        }
    }

    /*
    * 작성중설문목록보기
    * $survey_type : public:    공개설문목록  
    * $survey_type : advanced:  맞춤형설문목록
    * */
    public function draft_list($survey_type){
        if (is_signed())
        {
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
    
            $this->data['user_level'] = $userauth;
            $this->data['stval']="";
            $this->data['userid']=$userid;
            $this->data['survey_total_count']= 0;
            $this->data['view_flag'] = 2;   // 설문현황목록
            if ($survey_type === 'public') {
                $this->data['survey_flag'] = 1; 
                $this->data['menu'] = '공개교육 설문';
                $this->data['submenu'] = '설문현황';    
            }
            else {
                $this->data['survey_flag'] = 0; 
                $this->data['menu'] = '맞춤형 설문';
                $this->data['submenu'] = '설문현황';       
            }
    
            $this->load->view('templates/header', $this->data);
            $this->load->view('templates/nav', $this->data);
            $this->load->view('survey/survey_draft_list', $this->data);
            $this->load->view('templates/nav-footer', $this->data);
            $this->load->view('templates/scripts', $this->data);
            $this->load->view('templates/footer', $this->data);    
        }
        else 
        {    
            ?>
            <script type="text/javascript">                
                alert("로그인상태에서만 이용하실수 있습니다.");
                base_url = "<?=$GLOBALS['protocol']?>://" + location.host + "/index";
                window.location = base_url;
            </script>
            <?php            
        }
    }

    /*
    * 불러오기가능한 전체설문목록보기
    * $survey_type : public:    공개설문목록  
    * $survey_type : advanced:  맞춤형설문목록
    * */
    public function load_list($survey_type){
        if (is_signed())
        {
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

            $prev_education_id = $this->input->get_post('prev_education_id');
            $education_title = $this->input->get_post('education_title');
            $survey_start_date = $this->input->get_post('survey_start_date');
            $survey_end_date = $this->input->get_post('survey_end_date');

            $sms_available = isset($_GET['sms_available']) ? $_GET['sms_available'] : 0;
            $education_course = isset($_GET['education_course']) ? $_GET['education_course'] : '';
            $education_customer = isset($_GET['education_customer']) ? $_GET['education_customer'] : '';
            $education_teacher = isset($_GET['education_teacher']) ? $_GET['education_teacher'] : '';

            if (empty($prev_education_id)) 
                $prev_education_id = '0';      
            if (empty($education_title)) 
                $education_title = '';      
            if (empty($survey_start_date))
                $survey_start_date = '';      
            if (empty($survey_end_date))
                $survey_end_date = '';   

            $this->data['user_level'] = $userauth;
            $this->data['stval']="";
            $this->data['view_flag'] = 3;   // 전체 설문목록
            $this->data['userid'] = $userid;
            $this->data['survey_total_count']= 0;
            $this->data['prev_education_id']= $prev_education_id;
            $this->data['education_title']= $education_title;
            $this->data['survey_start_date']= $survey_start_date;
            $this->data['survey_end_date']= $survey_end_date;

            $this->data['sms_available']= $sms_available;
            $this->data['education_course']= $education_course;
            $this->data['education_customer']= $education_customer;
            $this->data['education_teacher']= $education_teacher;

            if ($survey_type === 'public') {
                $this->data['survey_flag'] = 1; 
                $this->data['menu'] = '공개교육 설문';
                $this->data['submenu'] = '설문목록';    
            }
            else {
                $this->data['survey_flag'] = 0; 
                $this->data['menu'] = '맞춤형 설문';
                $this->data['submenu'] = '설문목록';    
            }
    
            $this->load->view('templates/header', $this->data);
            $this->load->view('templates/nav', $this->data);
            $this->load->view('survey/survey_load_list', $this->data);
            $this->load->view('templates/nav-footer', $this->data);
            $this->load->view('templates/scripts', $this->data);
            $this->load->view('templates/footer', $this->data);    
        }
        else 
        {    
            ?>
            <script type="text/javascript">                
                alert("로그인상태에서만 이용하실수 있습니다.");
                base_url = "<?=$GLOBALS['protocol']?>://" + location.host + "/index";
                window.location = base_url;
            </script>
            <?php            
        }
    }

    /*
     * 설문목록페지에서 실지 자료를 얻는 함수
     * $view_flag : 1:  전송한 설문목록 , 2: 작성중 설문목록, 3: 전체 설문목록
     * $survey_type : public:  공개교육목록 , advanced: 맞춤형목록
     * */
    public function get_my_surveys_list()
    {        
        $user_id = get_session_user_id();
        if($user_id ==="" || empty($user_id)) {
            $user_id = -1;
        }
        
        $this->data['stval'] = isset($_POST['stval']) ? $_POST['stval']:'';
        $this->data['my_page'] = isset($_POST['page']) ? $_POST['page']:0;
        $this->data['my_per_page'] = isset($_POST['page_per_count']) ? $_POST['page_per_count']:0;
        $this->data['view_flag'] = isset($_POST['view_flag']) ? $_POST['view_flag']:1;
        $this->data['survey_flag'] = isset($_POST['survey_flag']) ? $_POST['survey_flag']:1;
        $this->data['survey_begindate'] = isset($_POST['survey_begindate']) ? $_POST['survey_begindate']:1;
        $this->data['survey_enddate'] = isset($_POST['survey_enddate']) ? $_POST['survey_enddate']:1;
        $this->data['survey_admin'] = isset($_POST['survey_admin']) ? $_POST['survey_admin']:'';
        $this->data['survey_groupname'] = isset($_POST['survey_groupname']) ? $_POST['survey_groupname']:'';
        $this->data['survey_team'] = isset($_POST['survey_team']) ? $_POST['survey_team']:'';
        $this->data['survey_course'] = isset($_POST['survey_course']) ? $_POST['survey_course']:'';
        $this->data['survey_name'] = isset($_POST['survey_name']) ? $_POST['survey_name']:'';
        $this->data['survey_customer'] = isset($_POST['survey_customer']) ? $_POST['survey_customer']:'';
        // echo "survey_flag:".$this->data['survey_flag'];
        $my_items = $this->surveys_model->get_surveys($user_id, $this->data);
        $this->data['my_item_total'] =  $this->surveys_model->get_surveys_total_count($user_id, $this->data)[0]['total_count'];
        
        $this->data['my_items']= $my_items;
        $m = 0;
        if ($this->data['view_flag'] == 1) {        // 완성된 설문
            $this->load->view('survey/survey_list_table', $this->data);
        }
        else if ($this->data['view_flag'] == 2){    // 현황분석용 설문
            //설문완료기발설정
            foreach($my_items as $item) {
                
                $check_result = $this->surveys_model->survey_end_check($item['id']);
                if(count($check_result) > 0){
                    if($check_result[0]['mobile_count'] != null && $check_result[0]['mobile_count'] == $check_result[0]['reply_count'])
                        $my_items[$m]['survey_end'] = 1;
                    else
                        $my_items[$m]['survey_end'] = 0;
                }else
                    $my_items[$m]['survey_end'] = 0;
                $m ++;
            }

            $this->data['my_items']= $my_items;
            $this->load->view('survey/survey_draft_list_table', $this->data);
        }        
        else {                                      // 전체 설문
            $this->load->view('survey/survey_load_list_table', $this->data);
        }
    }

    /*
     * 교육과정목록을 얻는 함수
     * $survey_type : public:  공개교육목록 , advanced: 맞춤형목록
     * */
    public function get_my_educations_list()
    {        
        $user_id = get_session_user_id();
        if($user_id ==="" || empty($user_id)) {
            $user_id = -1;
        }
        
        $survey_flag = isset($_POST['survey_flag']) ? $_POST['survey_flag']:0;

        $this->data['my_page'] = isset($_POST['page']) ? $_POST['page']:0;
        $this->data['my_per_page'] = isset($_POST['page_per_count']) ? $_POST['page_per_count']:0;
        $this->data['survey_admin'] = isset($_POST['survey_admin']) ? $_POST['survey_admin']:'';        
        $this->data['survey_begindate'] = isset($_POST['survey_begindate']) ? $_POST['survey_begindate']:1;
        $this->data['survey_enddate'] = isset($_POST['survey_enddate']) ? $_POST['survey_enddate']:1;
        $this->data['survey_course'] = isset($_POST['survey_course']) ? $_POST['survey_course']:'';
        $this->data['survey_groupname'] = isset($_POST['survey_groupname']) ? $_POST['survey_groupname']:'';
        $this->data['survey_job'] = isset($_POST['survey_job']) ? $_POST['survey_job']:'';
        $this->data['survey_customer'] = isset($_POST['survey_customer']) ? $_POST['survey_customer']:'';
        $this->data['survey_count'] = isset($_POST['survey_count']) ? $_POST['survey_count']:'';
        $this->data['education_type'] = isset($_POST['education_type']) ? $_POST['education_type']:'';
        $is_landing = isset($_POST['is_landing']) ? $_POST['is_landing']:'';

        if($is_landing == "1"){
            $user_memberid = $this->users_model->get_member_id_from_id($user_id);
            $this->data['empseq'] = $this->educations_model->get_empseq_from_userid($user_memberid);
            $this->data['is_landing'] = 1;
        }else{
            $this->data['is_landing'] = 0;
            $this->data['empseq'] = 0;
        }

        if ($this->data['survey_admin'] == "") {
            // if ($this->data['survey_groupname'] != "" || $this->data['survey_course'] != "" || 
            //     $this->data['survey_customer'] != "") {
                $search_username = 'all';
            // }
            // else {
            //     $search_username = $user_id;            
            // }
        }
        else {
            $search_username = $this->data['survey_admin'];
        }
        if ($search_username == "") {
            echo ('err');                        
        }
        else {
            if ($survey_flag == 1) {    // 공개교육
                $this->data['survey_flag'] = $survey_flag;
                $this->data['survey_customer'] = '';
                $my_items = $this->educations_model->get_education_schedules($search_username, $this->data);                
                $this->data['my_item_total'] =  $this->educations_model->get_education_schedules_total_count($search_username, $this->data);
                $this->data['my_items']= $my_items;                
                $this->load->view('survey/education_schedule_list_table', $this->data);    
            }
            else {                      // 맞춤형교육
                $this->data['survey_flag'] = $survey_flag;
                $my_items = $this->educations_model->get_education_schedules($search_username, $this->data);
                $this->data['my_item_total'] =  $this->educations_model->get_education_schedules_total_count($search_username, $this->data);
                $this->data['my_items']= $my_items;    
                $this->load->view('survey/education_schedule_list_table', $this->data);    

                // $this->data['my_items'] = array();
                // $this->data['my_item_total'] = 0;
                // $base_path = $this->input->server('DOCUMENT_ROOT') . base_url();
                // $erp_excel_path = $this->educations_model->get_education_excel($search_username);                
                // if ($erp_excel_path != "") {
                //     $this->data['erp_excel_file'] =  $erp_excel_path;
                //     $erp_excel_path = $base_path.$erp_excel_path;
                //     $this->load->library('excel');

                //     try {
                //         $inputFileType = PHPExcel_IOFactory::identify($erp_excel_path);
                //         $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                //         $objPHPExcel = $objReader->load($erp_excel_path);
                //     } catch(Exception $e) {                        
                //         exit;
                //     }
                    
                //     //  Get worksheet dimensions
                //     $objWorksheet = $objPHPExcel->getActiveSheet();
                //     $highestRow = $objWorksheet->getHighestRow();
                //     foreach ($objWorksheet->getRowIterator() as $i => $row) {
                //         $cellIterator = $row->getCellIterator();
                //         $cellIterator->setIterateOnlyExistingCells(false);
                //         $row = [];
                //         foreach ($cellIterator as $cell) {
                //             $row[] = $cell->getValue();
                //         }                         
                //         if ($row[1] == '') {
                //             $this->data['my_item_total'] =  $i - 2;
                //             $this->load->view('survey/education_schedule_list_table', $this->data);    
                //             break;
                //         } else if ($i > 1) {
                //             $data = array();
                //             $data['id'] = $row[0];
                //             $data['main_type'] = '';
                //             $data['sub_type'] = '';
                //             $data['is_public'] = '';
                //             $data['count_name'] = $row[10];
                //             $data['begin_date'] = '19000101'; 
                //             $data['end_date'] = '21001231'; 
                //             $data['teachers_name'] = $row[3];
                //             $data['student_count'] = '0';
                //             $data['manager_id'] = $search_username;
                //             $data['subject_group'] = $row[2];
                //             $data['subject_name'] = $row[9];
                                                        
                //             $this->data['my_items'][] = $data;
                //         }
                //     }
                // }                      
            }
        }        
    }

    public function get_educations_from_excel()
    {

    }

    // 문항불러오기에 응답하여 이전 설문들을 돌려준다.
    public function get_surveys()
    {        
        $user_id = get_session_user_id();
        if($user_id ==="" || empty($user_id)) {
            $user_id = -1;
        }
        $my_page =isset($_GET['page']) ? $_GET['page']:0;

        $my_per_page =isset($_GET['page_per_count']) ? $_GET['page_per_count']:10;


        $this->data['my_page']=$my_page;
        $this->data['my_per_page']=$my_per_page;


        $public_items = $this->surveys_template_model->get_surveys($user_id,1,$my_page,$my_per_page);
        $this->data['my_item_total'] =  $this->surveys_template_model->get_surveys_total_count($user_id,1)[0]['total_count'];


        $this->data['my_items']=$public_items;

        $this->load->view('survey/survey_list', $this->data);
    }

    // 문항불러오기에 응답하여 이전 설문들을 돌려준다.
    public function get_surveyById()
    {
        $survey_data = array();
        $survey_id = $this->input->get_post('survey_id');
        $condition = array (
            'id' => $survey_id,
            'flag' => 0
        );
        $survey = $this->surveys_model->get_data($condition);
        $survey_ends = $this->surveys_model->get_surveys_end_comment($survey_id);
        $survey_data['surveys'] = $survey;
        $survey_data['end_comments'] = $survey_ends;

        $question_data = $this->question_groups_model->getExampleData($survey_id);
        $survey_data['question_groups'] = $question_data;
        $result = array(
            'status' => 'OK',
            'survey_data' => $survey_data
        );

        echo json_encode($result);
    }


    // 문항불러오기에 응답하여 이전 설문들을 돌려준다.
    public function get_questions()
    {
//        $user_id = 1;
        $user_id = get_session_user_id();

        $survey_id = $this->input->get_post('survey_id');

        $condition = array (
            'survey_id' => $survey_id,
            'flag' => 0
        );
        $question_data = $this->questions_template_model->get_data($condition);

        $result = array(
            'status' => 'OK',
            'question_data' => $question_data
        );

        echo json_encode($result);
    }
    
    // 선택된 문항의 보기들을 가져오기
    public function get_examples()
    {
        $question_id = $this->input->get_post('question_id');
        $condition = array (
            'question_id' => $question_id,
            'flag' => 0
        );
        $example_data = $this->examples_template_model->get_data($condition);

        $result = array(
            'status' => 'OK',
            'example_data' => $example_data
        );

        echo json_encode($result);
    }

    // 화상올리적재, 자동으로 survey프로젝트등록부의 /upload/tmp에 화상과 thumbnails가 생간다.
    public function upload_img()
    {
        $this->load->library('upload_handler');
    }

    public function upload_file()
    {
        $user_uid = get_session_user_uid();

        if($user_uid ==="") {

            $user_uid = "ghost";
        }

        $filesize = 0;
        $filename_origin = '';
        $filename = '';
        $filename_tmp = '';
        $filepath = '';

        $datetime = date('Y-m-d H:i:s');

        $uid = sprintf('%s-%s', date('YmdHis'), $user_uid);

        if (isset($_FILES) && sizeof($_FILES) > 0) {
            if ($_FILES['file']['error'] == 0) {
                $filename_origin = $_FILES['file']['name'];
                $filesize = $_FILES['file']['size'];

                $fileext = my_get_extension($filename_origin);
                $base_path = $this->input->server('DOCUMENT_ROOT') . base_url();
                $upload_path = $base_path . 'uploads/tmp';
                $dir_path = $upload_path;
//                $dir_path = sprintf('%s/%s/%s', $upload_path, date('Y'), date('m'));
                $filepath = $dir_path . '/' . $uid . $fileext;
                $filename = $uid . $fileext;

                if (!is_dir($dir_path)) {
                    my_mkdir($dir_path);
                }

                if (is_dir($dir_path) && is_uploaded_file($_FILES['file']['tmp_name'])) {
                    move_uploaded_file($_FILES['file']['tmp_name'], $filepath);
                }
            }
            else {
                $response = array(
                    'status' => 'upload_error',
                    'msg' => '오류가 발생하였습니다.'
                );
                echo json_encode($response);
                exit;
            }
        }

        $response = array(
            'status' => 'OK',
            'msg' => '성공',
            'file_name' => $filename,
            'origin_file_name' => $filename_origin
        );
        echo json_encode($response);
    }
    public function delete(){


        $userauth= get_session_user_level();
        if($userauth ==="") {
            $userauth = "";
            $user_id = -1;
        }
        $survey_id = $this->session->userdata('survey_draft')['survey_id'];


        $this->session->unset_userdata('survey_draft');
        return -1;
    }

    //중복이름체크
    public function check_duplicate_name(){
        $user_id = get_session_user_id();

        if($user_id !=="" && $user_id > 0) {
            //survey_id가 존재하면 다시저장이므로 중복검사를 하지 않음
            $survey_id = $this->input->get_post('survey_id');
            if($survey_id > 0){
                echo 0;
                return;
            }

            $survey_title = $this->input->get_post('survey_title');
            $where = array(
                'title' => $survey_title
            );
            $result = $this->surveys_model->get_data($where);

            if(count($result) > 0)
                echo 1;
            else
                echo 0;
        }
    }
    // 설문보관
    public function save()
    {
        $user_id = get_session_user_id();

        if($user_id !=="" && $user_id > 0) {

            $education_id = $this->input->get_post('education_id');
            $education_count = '';
            $education_customer = '';
            $education_course = '';

            //education정보불러오기
            if($education_id > 0){
                $serverName = $GLOBALS['erp_mssql_addr']; //serverName\instanceName, portNumber (default is 1433)
                $connectionInfo = array( "Database"=>$GLOBALS['erp_mssql_db'],
                    "UID"=>$GLOBALS['erp_mssql_uid'],
                    "PWD"=>$GLOBALS['erp_mssql_pwd']);
                $conn = sqlsrv_connect( $serverName, $connectionInfo);

                if( $conn ) {
                    //교육과정/차수 얻기
                    $sql =  "SELECT ProcCourseName as subject_name,EduSeq as count_name ";
                    $sql .= "FROM VI_EduCourse ";
                    $sql .= "WHERE Serl='".$education_id."'";

                    $params = array();
                    $options =  array( "Scrollable" => "Keyset" );
                    $stmt = sqlsrv_query( $conn, $sql, $params, $options);

                    while( $row = sqlsrv_fetch_array( $stmt) ) {
                        $education_count = $row['count_name'];
                        $education_course = $row['subject_name'];
                        break;
                    }

                    //고객사명 얻기
                    $sql =  "SELECT CustName as customer ";
                    $sql .= "FROM VI_EduCourseClient ";
                    $sql .= "WHERE Serl='".$education_id."'";

                    $params = array();
                    $options =  array( "Scrollable" => "Keyset" );
                    $stmt = sqlsrv_query( $conn, $sql, $params, $options);

                    while( $row = sqlsrv_fetch_array( $stmt) ) {
                        $education_customer = $row['customer'];
                        break;
                    }

                    sqlsrv_close($conn);
                }
            }
            $survey_id = $this->input->get_post('survey_id');
            $survey_flag = $this->input->get_post('survey_flag');
            $survey_attached = $this->input->get_post('survey_attached');
            $attached_file_name = $this->input->get_post('attached_file_name');
            $survey_title = $this->input->get_post('survey_title');
            $start_time = $this->input->get_post('start_time');
            $end_time = $this->input->get_post('end_time');
            $end_condition = $this->input->get_post('end_condition');
            $end_count = $this->input->get_post('end_count');
            $auth = $this->input->get_post('auth');
            $comment = $this->input->get_post('comment');
            $question_group_count = $this->input->get_post('question_group_count');
            $question_count = $this->input->get_post('question_count');
            $question_count_page = $this->input->get_post('question_count_page');
            $question_groups = $this->input->get_post('question_groups');
            $end_comments = $this->input->get_post('end_comments');
            $review_infor = $this->input->get_post('review_infor');
            $isPublicShow = $this->input->get_post('show_type');
            $show_user_id = $user_id;

            if ($isPublicShow == "1")
                $show_user_id = 0;

            if (empty($end_count)) {
                $end_count = 0;
            }

            if($survey_attached === "1") {
                $attached_file_name = get_full_url()."/".$attached_file_name;
            }

            $survey_data = array(
                'attached' => $survey_attached,
                'file_url' => $attached_file_name,
                'title' => $survey_title,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'end_condition' => $end_condition,
                'end_count' => $end_count,
                'auth' => $auth,
                'review_infor' => $review_infor,
                'comment' => $comment,
                'question_group_count' => $question_group_count,
                'question_count' => $question_count,
                'question_count_page' => $question_count_page,
                'user_id' => $user_id,
                'show_user_id' => $show_user_id,
                'created_at' => date('Y-m-d H:i:s'),
                'survey_flag' => $survey_flag,
                'education_id' => $education_id,
                'education_count' => $education_count,
                'education_customer' => $education_customer,
                'education_course' => $education_course,  
            );

            $condition = array(
               'id' => $survey_id,
            );

            $survey_id = $this->surveys_model->insert_update_data($survey_data, $condition);

            $real_end_comments = json_decode($end_comments);

            $flag = $this->surveys_model->insert_end_comment($survey_id,$real_end_comments);
            if($flag < 1) {
                return -1;
            }

            $oQuestion_groups = json_decode($question_groups);
            //질문그룹순환
            foreach($oQuestion_groups as $oQuestions) {
                $question_group_data = array(
                    'survey_id' => $survey_id,
                    'number' => $oQuestions->number,
                    'title' => $oQuestions->title,
                    'question_count' => $oQuestions->question_count,
                );
                $condition = array(
                    'survey_id' => $survey_id,
                    'number' => $oQuestions->number,
                );
                $question_group_id = $this->question_groups_model->insert_update_data($question_group_data, $condition);

                //질문순환
                foreach ($oQuestions->questions as $question) {
                    $question->question_img_url = str_replace('survey/thumb_tmp/', '', $question->question_img_url);
                    $question->question_img_url = str_replace('survey/thumb/', '', $question->question_img_url);

                    $question_data = array(
                        'survey_id' => $survey_id,
                        'question_group_id' => $question_group_id,
                        'number' => $question->number,
                        'type' => $question->type,
                        'question' => $question->question,
                        'question_img_url' => $question->question_img_url,
                        'example_count' => $question->example_count,
                        'created_at' => date('Y-m-d H:i:s'),
                        'allow_unselect' => $question->allow_unselect,
                    );

                    $base_path = $this->input->server('DOCUMENT_ROOT') . base_url();
                    if (!empty($question->question_img_url)) {
                        $thumb_tmp = $base_path . 'uploads/tmp/thumbnail/' . $question->question_img_url;
                        $thumb = $base_path . 'uploads/survey/thumbnail/' . $question->question_img_url;

                        $img_tmp = $base_path . 'uploads/tmp/' . $question->question_img_url;
                        $img = $base_path . 'uploads/survey/' . $question->question_img_url;

                        if (!is_dir($base_path . 'uploads/survey')) {
                            my_mkdir($base_path . 'uploads/survey');
                        }
                        if (!is_dir($base_path . 'uploads/survey/thumbnail')) {
                            my_mkdir($base_path . 'uploads/survey/thumbnail');
                        }

                        if (is_file($thumb_tmp))
                            copy($thumb_tmp, $thumb);
                        if (is_file($img_tmp))
                            copy($img_tmp, $img);
                    }

                    if ($question->type == 0) {
                        $question_data_0 = array(
                            'allow_reply_response' => $question->allow_reply_response,
                            'example_has_image' => $question->example_has_image,
                            'use_other_input' => $question->use_other_input,

                            'allow_random_align' => $question->allow_random_align
                        );

                        $question_data = array_merge($question_data, $question_data_0);
                        $condition = array(
                            'survey_id' => $survey_id,
                            'question_group_id' => $question_group_id,
                            'number' => $question->number,
                        );
                        $question_id = $this->questions_model->insert_update_data($question_data, $condition);
                        $condition = array(
                            'question_id' => $question_id
                        );
                        $this->examples_model->delete_data($condition);
                        foreach ($question->examples as $example) {
                            $example_data = array(
                                'question_id' => $question_id,
                                'number' => $example->number,
                                'title' => $example->title,
                                'created_at' => date('Y-m-d H:i:s'),
                                'question_move' => $example->question_move,
                            );

                            if ($question->example_has_image == 1) {
                                $example->img_url = str_replace('survey/thumb_tmp/', '', $example->img_url);
                                $example->img_url = str_replace('survey/thumb/', '', $example->img_url);

                                $example_data_img = array(
                                    'img_url' => $example->img_url,
                                );
                                $example_data = array_merge($example_data, $example_data_img);

                                $base_path = $this->input->server('DOCUMENT_ROOT') . base_url();
                                if (!empty($example->img_url)) {
                                    $thumb_tmp = $base_path . 'uploads/tmp/thumbnail/' . $example->img_url;
                                    $thumb = $base_path . 'uploads/survey/thumbnail/' . $example->img_url;

                                    $img_tmp = $base_path . 'uploads/tmp/' . $example->img_url;
                                    $img = $base_path . 'uploads/survey/' . $example->img_url;

                                    if (!is_dir($base_path . 'uploads/survey')) {
                                        my_mkdir($base_path . 'uploads/survey');
                                    }
                                    if (!is_dir($base_path . 'uploads/survey/thumbnail')) {
                                        my_mkdir($base_path . 'uploads/survey/thumbnail');
                                    }

                                    if (is_file($thumb_tmp))
                                        copy($thumb_tmp, $thumb);
                                    if (is_file($img_tmp))
                                        copy($img_tmp, $img);
                                }
                            }
                        
                            $this->examples_model->insert_data($example_data);
                        }
                    } else if ($question->type == 1) {
                        $question_data_1 = array(
                            'end_comment_index' => $question->end_comment_index,
                        );
                        $condition = array(
                            'survey_id' => $survey_id,
                            'question_group_id' => $question_group_id,
                            'number' => $question->number,
                        );
                        $question_data = array_merge($question_data, $question_data_1);
                        $question_id = $this->questions_model->insert_update_data($question_data, $condition);
                    } else if ($question->type == 2) {
                        $question_data_2 = array(
                            'type_grade' => $question->type_grade,
                            'rating_move_value' => $question->rating_move_value,
                            'reverse_question' => $question->reverse_question,
                            'rating_names' => $question->rating_names,
                        );
                        $question_data = array_merge($question_data, $question_data_2);
                        $condition = array(
                            'survey_id' => $survey_id,
                            'question_group_id' => $question_group_id,
                            'number' => $question->number,
                        );
                        $question_id = $this->questions_model->insert_update_data($question_data, $condition);
                    } else if($question->type == 3){

                        $question_data_3 = array(
                            'type_grade' => $question->type_grade,
                            'rating_names' => $question->rating_names,
                            'exam_kind_count' => $question->exam_kind_count,
                            'exam_object_count' => $question->exam_object_count
                        );

                        $question_data = array_merge($question_data, $question_data_3);
                        $condition = array(
                            'survey_id' => $survey_id,
                            'question_group_id' => $question_group_id,
                            'number' => $question->number,
                        );
                        $question_id = $this->questions_model->insert_update_data($question_data, $condition);
                        $condition = array(
                            'question_id' => $question_id
                        );
                        //평가지표보관
                        $this->question_exam_kinds_model->delete_data($condition);
                        foreach ($question->exam_kinds as $exam_kind) {
                            $exam_kind_data = array(
                                'question_id' => $question_id,
                                'number' => $exam_kind->number,
                                'title' => $exam_kind->title,
                                'content' => $exam_kind->content
                            );
                            $this->question_exam_kinds_model->insert_data($exam_kind_data);
                        }

                        //평가대상보관
                        $this->question_exam_objects_model->delete_data($condition);
                        foreach ($question->exam_objects as $exam_object) {
                            $exam_object_data = array(
                                'question_id' => $question_id,
                                'number' => $exam_object->number,
                                'title' => $exam_object->title,
                                'profile' => $exam_object->profile,
                            );
                            $this->question_exam_objects_model->insert_data($exam_object_data);
                        }
                    }
                }
            }
            echo $survey_id;
        } else {
            echo "-1";
        }
    }

    //  설문전송
    public function post()
    {
        $user_id = get_session_user_id();

        if($user_id !=="" && $user_id > 0) {

            $education_id = $this->input->get_post('education_id');
            //education정보불러오기
            $education_count = $this->input->get_post('education_count');
            $education_customer = $this->input->get_post('education_customer');
            $education_course = $this->input->get_post('education_course');
            $education_teacher = $this->input->get_post('education_teacher');

            $survey_id = $this->input->get_post('survey_id');
            $survey_flag = $this->input->get_post('survey_flag');
            $survey_attached = $this->input->get_post('survey_attached');
            $attached_file_name = $this->input->get_post('attached_file_name');
            $survey_title = $this->input->get_post('survey_title');
            $start_time = $this->input->get_post('start_time');
            $end_time = $this->input->get_post('end_time');
            $end_condition = $this->input->get_post('end_condition');
            $end_count = $this->input->get_post('end_count');
            $auth = $this->input->get_post('auth');
            $comment = $this->input->get_post('comment');
            $question_group_count = $this->input->get_post('question_group_count');
            $question_count = $this->input->get_post('question_count');
            $question_count_page = $this->input->get_post('question_count_page');
            $question_groups = $this->input->get_post('question_groups');
            $end_comments = $this->input->get_post('end_comments');
            $review_infor = $this->input->get_post('review_infor');

            if($survey_attached === "1") {
                $attached_file_name = get_full_url()."/".$attached_file_name;
            }

            $survey_data = array(
                'attached' => $survey_attached,
                'file_url' => $attached_file_name,
                'title' => $survey_title,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'end_condition' => $end_condition,
                'end_count' => $end_count,
                'auth' => $auth,
                'review_infor' => $review_infor,
                'comment' => $comment,
                'question_group_count' => $question_group_count,
                'question_count' => $question_count,
                'question_count_page' => $question_count_page,
                'user_id' => $user_id,
                'created_at' => date('Y-m-d H:i:s'),
                'survey_flag' => $survey_flag,
                'education_id' => $education_id, 
                'education_count' => $education_count,
                'education_customer' => $education_customer,  
                'education_course' => $education_course,  
                'education_teacher' => $education_teacher,  
            );
            //post인 경우 반드시 새 설문을 생성한다.
            $condition = array(
                'id' => 0,
            );

            $survey_id = $this->surveys_model->insert_update_data($survey_data,$condition);

            $real_end_comments = json_decode($end_comments);

            $flag = $this->surveys_model->insert_end_comment($survey_id,$real_end_comments);
            if($flag < 1) {
                return -1;
            }

            $oQuestion_groups = json_decode($question_groups);

            //질문그룹순환
            foreach($oQuestion_groups as $oQuestions) {
                $question_group_data = array(
                    'survey_id' => $survey_id,
                    'number' => $oQuestions->number,
                    'title' => $oQuestions->title,
                    'question_count' => $oQuestions->question_count,
                );
                $condition = array(
                    'survey_id' => $survey_id,
                    'number' => $oQuestions->number,
                );
                $question_group_id = $this->question_groups_model->insert_update_data($question_group_data, $condition);

                //질문순환
                foreach ($oQuestions->questions as $question) {
                    $question->question_img_url = str_replace('survey/thumb_tmp/', '', $question->question_img_url);
                    $question->question_img_url = str_replace('survey/thumb/', '', $question->question_img_url);

                    $question_data = array(
                        'survey_id' => $survey_id,
                        'question_group_id' => $question_group_id,
                        'number' => $question->number,
                        'type' => $question->type,
                        'question' => $question->question,
                        'question_img_url' => $question->question_img_url,
                        'example_count' => $question->example_count,
                        'allow_unselect' => $question->allow_unselect,
                        'created_at' => date('Y-m-d H:i:s'),
                    );

                    $base_path = $this->input->server('DOCUMENT_ROOT') . base_url();
                    if (!empty($question->question_img_url)) {
                        $thumb_tmp = $base_path . 'uploads/tmp/thumbnail/' . $question->question_img_url;
                        $thumb = $base_path . 'uploads/survey/thumbnail/' . $question->question_img_url;

                        $img_tmp = $base_path . 'uploads/tmp/' . $question->question_img_url;
                        $img = $base_path . 'uploads/survey/' . $question->question_img_url;

                        if (!is_dir($base_path . 'uploads/survey')) {
                            my_mkdir($base_path . 'uploads/survey');
                        }
                        if (!is_dir($base_path . 'uploads/survey/thumbnail')) {
                            my_mkdir($base_path . 'uploads/survey/thumbnail');
                        }

                        if (is_file($thumb_tmp))
                            copy($thumb_tmp, $thumb);
                        if (is_file($img_tmp))
                            copy($img_tmp, $img);
                    }

                    if ($question->type == 0) {
                        $question_data_0 = array(
                            'allow_reply_response' => $question->allow_reply_response,
                            'example_has_image' => $question->example_has_image,
                            'use_other_input' => $question->use_other_input,

                            'allow_random_align' => $question->allow_random_align
                        );

                        $question_data = array_merge($question_data, $question_data_0);

                        $condition = array(
                            'survey_id' => $survey_id,
                            'question_group_id' => $question_group_id,
                            'number' => $question->number,
                        );

                        $question_id = $this->questions_model->insert_update_data($question_data, $condition);

                        $condition = array(
                            'question_id' => $question_id
                        );
                        $this->examples_model->delete_data($condition);

                        foreach ($question->examples as $example) {
                            $example_data = array(
                                'question_id' => $question_id,
                                'number' => $example->number,
                                'title' => $example->title,
                                'created_at' => date('Y-m-d H:i:s'),
                                'question_move' => $example->question_move,
                            );

                            if ($question->example_has_image == 1) {
                                $example->img_url = str_replace('survey/thumb_tmp/', '', $example->img_url);
                                $example->img_url = str_replace('survey/thumb/', '', $example->img_url);

                                $example_data_img = array(
                                    'img_url' => $example->img_url,
                                );
                                $example_data = array_merge($example_data, $example_data_img);

                                $base_path = $this->input->server('DOCUMENT_ROOT') . base_url();
                                if (!empty($example->img_url)) {
                                    $thumb_tmp = $base_path . 'uploads/tmp/thumbnail/' . $example->img_url;
                                    $thumb = $base_path . 'uploads/survey/thumbnail/' . $example->img_url;

                                    $img_tmp = $base_path . 'uploads/tmp/' . $example->img_url;
                                    $img = $base_path . 'uploads/survey/' . $example->img_url;

                                    if (!is_dir($base_path . 'uploads/survey')) {
                                        my_mkdir($base_path . 'uploads/survey');
                                    }
                                    if (!is_dir($base_path . 'uploads/survey/thumbnail')) {
                                        my_mkdir($base_path . 'uploads/survey/thumbnail');
                                    }

                                    if (is_file($thumb_tmp))
                                        copy($thumb_tmp, $thumb);
                                    if (is_file($img_tmp))
                                        copy($img_tmp, $img);
                                }
                            }

                            $this->examples_model->insert_data($example_data);

                        }
                    } else if ($question->type == 1) {
                        $question_data_1 = array(
                            'end_comment_index' => $question->end_comment_index,
                        );
                        $condition = array(
                            'survey_id' => $survey_id,
                            'question_group_id' => $question_group_id,
                            'number' => $question->number,
                        );
                        $question_data = array_merge($question_data, $question_data_1);
                        $question_id = $this->questions_model->insert_update_data($question_data, $condition);

                    } else if ($question->type == 2) {
                        $question_data_2 = array(
                            'type_grade' => $question->type_grade,
                            'rating_move_value' => $question->rating_move_value,
                            'reverse_question' => $question->reverse_question,
                            'rating_names' => $question->rating_names,
                        );
                        $question_data = array_merge($question_data, $question_data_2);
                        $condition = array(
                            'survey_id' => $survey_id,
                            'question_group_id' => $question_group_id,
                            'number' => $question->number,
                        );
                        $question_id = $this->questions_model->insert_update_data($question_data, $condition);
                    } else if($question->type == 3){

                        $question_data_3 = array(
                            'type_grade' => $question->type_grade,
                            'rating_names' => $question->rating_names,
                            'exam_kind_count' => $question->exam_kind_count,
                            'exam_object_count' => $question->exam_object_count
                        );

                        $question_data = array_merge($question_data, $question_data_3);
                        $condition = array(
                            'survey_id' => $survey_id,
                            'question_group_id' => $question_group_id,
                            'number' => $question->number,
                        );
                        $question_id = $this->questions_model->insert_update_data($question_data, $condition);
                        $condition = array(
                            'question_id' => $question_id
                        );
                        //평가지표보관
                        $this->question_exam_kinds_model->delete_data($condition);
                        foreach ($question->exam_kinds as $exam_kind) {
                            $exam_kind_data = array(
                                'question_id' => $question_id,
                                'number' => $exam_kind->number,
                                'title' => $exam_kind->title,
                                'content' => $exam_kind->content
                            );
                            $this->question_exam_kinds_model->insert_data($exam_kind_data);
                        }

                        //평가대상보관
                        $this->question_exam_objects_model->delete_data($condition);
                        foreach ($question->exam_objects as $exam_object) {
                            $exam_object_data = array(
                                'question_id' => $question_id,
                                'number' => $exam_object->number,
                                'title' => $exam_object->title,
                                'profile' => $exam_object->profile,
                            );
                            $this->question_exam_objects_model->insert_data($exam_object_data);
                        }
                    }
                }
            }
            echo $survey_id;
        } else {
            echo "-1";
        }
    }

    public function download_img_tmp($filename)
    {
        $this->load->helper('download');
        $base_path = $this->input->server('DOCUMENT_ROOT') . base_url();
        $thumb = $base_path . 'uploads/tmp/' . $filename;
        force_download($thumb, NULL, TRUE);
    }

    public function thumb_tmp($filename)
    {
        $this->load->helper('download');
        $base_path = $this->input->server('DOCUMENT_ROOT') . base_url();
        $thumb = $base_path . 'uploads/tmp/thumbnail/' . $filename;
        force_download($thumb, NULL, TRUE);
    }

    public function download_img($filename)
    {
        $this->load->helper('download');
        $base_path = $this->input->server('DOCUMENT_ROOT') . base_url();
        $thumb = $base_path . 'uploads/survey/' . $filename;
        force_download($thumb, NULL, TRUE);
    }

    public function thumb($filename)
    {
        $this->load->helper('download');
        $base_path = $this->input->server('DOCUMENT_ROOT') . base_url();
        $thumb = $base_path . 'uploads/survey/thumbnail/' . $filename;
        force_download($thumb, NULL, TRUE);
    }

    public function getHTML(){
        $file_url = $this->input->get_post('file_url');
        $htmlPage = file_get_contents($file_url);
        $startBodyPos = strpos($htmlPage, '<body>');
        $endBodyPos = strpos($htmlPage, '</body>');
        $startHeadPos = strpos($htmlPage, '<head>');
        $endHeadPos = strpos($htmlPage, '</head>');
        $content = substr($htmlPage,$startBodyPos + 6,$endBodyPos - $startBodyPos - 6);
        $headContent = substr($htmlPage,$startHeadPos + 6,$endHeadPos - $startHeadPos - 6);
        echo $headContent.$content;
    }

//    hwp/pdf/docx/doc/xls/jpg/html파일을 html로 변환
    function convertToHTML()
    {
        $file_name = $this->input->get_post('file_name');
        $from = FCPATH . 'uploads/tmp/'.$file_name;
        $converted_url = "";

        //html파일인 경우
//            $htmlPage = file_get_contents("http://localhost/uploads/html/20181107192807-test.html");
//            $startBodyPos = strpos($htmlPage, '<body>');
//            $endBodyPos = strpos($htmlPage, '</body>');
//            $startHeadPos = strpos($htmlPage, '<head>');
//            $endHeadPos = strpos($htmlPage, '</head>');
//            $content = substr($htmlPage,$startBodyPos + 6,$endBodyPos - 1);
//            $headContent = substr($htmlPage,$startHeadPos + 6,$endHeadPos - 1);
//            $myfile = fopen(FCPATH."121212.txt", "w") or die("Unable to open file!");
//            fwrite($myfile, $headContent.$content);
//            fclose($myfile);
//            echo $headContent.$content;
        if (strpos($file_name, '.htm') > 0) {

            $to = FCPATH . 'uploads/html/'.$file_name;
            if(copy($from,$to))
                $converted_url = 'uploads/html/'.$file_name;
            else
                $converted_url = "";
            //hwp파일인 경우
        }else if(strpos($file_name, '.hwp') > 0){

            $to = FCPATH . 'uploads/html/'.substr($file_name,0,strpos($file_name, ".hwp")).'.html';
            shell_exec("cd /opt/hwp2htmlEX && ./conv -s '".$from."' -o '".$to."' -m convert");

            $converted_url = 'uploads/html/'.substr($file_name,0,strpos($file_name, ".hwp")).'.html';
            //pdf파일인 경우
        }else if(strpos($file_name, '.pdf') > 0){
            $fromDir = FCPATH . 'uploads/tmp';
            shell_exec("pdf2htmlEX --fallback 1 --process-outline 0 --dest-dir '".$fromDir."' '".$from."'");

            $toHTML = FCPATH . 'uploads/html/'.substr($file_name,0,strpos($file_name, ".pdf")).'.html';
            $fromHTML = FCPATH . 'uploads/tmp/'.substr($file_name,0,strpos($file_name, ".pdf")).'.html';
            if(copy($fromHTML,$toHTML))
                $converted_url = 'uploads/html/'.substr($file_name,0,strpos($file_name, ".pdf")).'.html';
            else
                $converted_url = "";
            //doc파일인 경우
        }else if(strpos($file_name, '.doc') > 0){

//            shell_exec('/opt/libreoffice5.0/program/python /opt/libreoffice5.0/program/conv.py -s "/tmp/test.docx"');

        }

        echo $converted_url;
    }

    public function delete_survey(){
        $user_id = get_session_user_id();

        if(empty($user_id) || $user_id==="") {
            $user_id = -1;
            echo -1;

        } else {
            $survey_ids = $this->input->get_post('selected');        
            foreach ($survey_ids as $survey_id) {
                $survey_entry = $this->surveys_model->get_data_by_id($survey_id);
                if (count($survey_entry)) {                           
                    if ($survey_entry[0]['show_user_id'] == "0") {
                        if ($user_id == "300") {
                            $condition = array(
                                'id' => $survey_id
                            );
                            $this->surveys_model->delete_data($condition);            
                        }
                        continue;
                    }
                }
                $condition = array(
                    'id' => $survey_id,
                    'user_id' => $user_id
                );
                $this->surveys_model->delete_data($condition);
            }
            echo 1;
        }
    }

    public function show_public_survey() {
        $user_id = get_session_user_id();

        if(empty($user_id) || $user_id==="") {
            $user_id = -1;
            echo -1;
        } else {

            $survey_ids = $this->input->get_post('selected');
            foreach ($survey_ids as $survey_id) {
                $condition = array(
                    'id' => $survey_id,
                    'user_id' => $user_id
                );
                $data = array(
                    'show_user_id' => 0,
                    'public_date' => date('Y-m-d H:i:s')
                );

                $this->surveys_model->update_data($data, $condition);
            }
            echo 1;
        }
    }

    public function public_survey() {
        $user_id = get_session_user_id();

        if(empty($user_id) || $user_id==="") {
            $user_id = -1;
            echo -1;
        } else {

            $survey_ids = $this->input->get_post('selected');
            foreach ($survey_ids as $survey_id) {

                $condition = array(
                    'id' => $survey_id,
                    'user_id' => $user_id
                );
                $data = array(
                    'is_public' => 1,
                    'public_date' => date('Y-m-d H:i:s')
                );

                $this->surveys_model->update_data($data, $condition);
            }
            echo 1;
        }
    }

    public function upload_educations_excel()
    {
        $user_id = get_session_user_id();

        if(empty($user_id) || $user_id==="") {
            $user_id = -1;
            echo "error";
            return;
        } else {
            $valid_extensions = array('xlsx'); // valid extensions
            $path = 'uploads/erp/'; // upload directory
    
            if($_FILES['erp_excel'])
            {
                $excel = $_FILES['erp_excel']['name'];
                $tmp = $_FILES['erp_excel']['tmp_name'];
                // get uploaded file's extension
                $ext = strtolower(pathinfo($excel, PATHINFO_EXTENSION));
                // can upload same image using rand function
                $final_erp_excel = rand(1000,1000000).'_'.$excel;
                // check's valid format
                if(in_array($ext, $valid_extensions)) 
                { 
                    $path = $path.strtolower($final_erp_excel); 
                    if(move_uploaded_file($tmp, $path)) 
                    {
                        $this->educations_model->set_education_excel($user_id, $path);
                        echo "success";
                        return;
                    }
                }
                else {
                    echo "invalid extension";
                    return;
                }                
            }
        }        
        echo "error";
        return;
    }
}

