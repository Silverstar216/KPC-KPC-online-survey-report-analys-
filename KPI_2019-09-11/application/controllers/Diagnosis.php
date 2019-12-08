<?php
/**
 * Author: KMC
 * Date: 10/7/18
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Diagnosis extends MY_Controller
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
            'include/js/diagnosis/d_edit.js',
            'include/js/pagination.js'
        );

        $this->load->model('users_model');
        $this->load->model('diagnosis_model');
        $this->load->model('d_questions_model');
        $this->load->model('d_question_groups_model');
        $this->load->model('d_question_exam_kinds_model');
        $this->load->model('d_question_exam_objects_model');
        $this->load->model('d_examples_model');


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

    public function index()
    {
        if (is_signed()) {
            $userauth= get_session_user_level();
            if($userauth ==="") {
                $userauth = "";
    
            }
            $survey_id = $this->input->get_post('survey_id');
    
            $this->data['attached'] = 0;
            $this->data['subtitle'] = '단순설문';
            $this->data['newflag'] = 1;  //새로작성
            $this->data['survey_id']=0;
            $this->data['diagnosis_excel_id']=0;
            $this->data['diagnosis_data']=$this->diagnosis_model->get_diagnosis_education_fromid(0);
            $this->data['user_level'] = $userauth; 

            $this->data['menu'] = '진단';
            $this->data['submenu'] = '진단작성';  

            $this->load->view('templates/header', $this->data);
            $this->load->view('templates/nav', $this->data);
            $this->load->view('diagnosis/d_edit', $this->data);
            $this->load->view('diagnosis/d_modal_questions', $this->data);
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
        $diagnosis_id = $this->input->get_post('diagnosis_id');
        $diagnosis_excel_id = $this->input->get_post('diagnosis_excel_id');
        $newflag = $this->input->get_post('newflag');
        $attached = $this->input->get_post('attached');

        $userauth= get_session_user_level();
        if($userauth ==="") {
            $userauth = "";
            redirect('survey');
        }
        $survey=array();

        if(empty($diagnosis_id)) {
            $diagnosis_id=0;
        }
        $this->data['survey_id']=$diagnosis_id;
        $this->data['diagnosis_excel_id']=$diagnosis_excel_id;
        $this->data['diagnosis_data']=$this->diagnosis_model->get_diagnosis_education_fromid($diagnosis_excel_id);
        $this->data['newflag']=$newflag;
        $this->data['user_level'] = $userauth; 
        $this->data['menu'] = '진단';
        $this->data['submenu'] = '진단생성';  

        $this->load->view('templates/header', $this->data);
        $this->load->view('templates/nav', $this->data);

        if(!empty($attached) && $attached > 0) {
            $this->data['attached'] = $attached;
            $this->data['subtitle'] = '문서포함설문';
            $this->load->view('diagnosis/d_edit', $this->data);
            $this->load->view('diagnosis/d_modal_attachedHTML', $this->data);
        } else {
            $this->data['attached'] = 0;
            $this->data['subtitle'] = '단순설문';
            $this->load->view('diagnosis/d_edit', $this->data);

        }

        $this->load->view('diagnosis/d_modal_questions', $this->data);

        $this->load->view('templates/nav-footer', $this->data);

        $this->load->view('templates/footer', $this->data);$this->load->view('templates/scripts', $this->data);
    }


    public function attached()
    {
        $this->data['attached'] = 1;
        $this->data['subtitle'] = '문서포함설문';

        $this->data['survey_id'] = '';
        $this->data['newflag'] = 1;//새로작성
        $this->load->view('templates/header', $this->data);
        $this->load->view('templates/nav', $this->data);
        $this->load->view('diagnosis/d_edit', $this->data);
        $this->load->view('diagnosis/d_modal_attachedHTML', $this->data);
        $this->load->view('diagnosis/d_modal_questions', $this->data);
        $this->load->view('templates/nav-footer', $this->data);
        $this->load->view('templates/scripts', $this->data);
        $this->load->view('templates/footer', $this->data);
    }

    /*
     * 진단목록보기
     * $view_flag : 1:  진단현황 , 2: 진단목록
     * */
    public function my_list($view_flag){
        if (is_signed()) {
            $this->data['scripts'] = array(
                'include/plugins/bootstrap-sweetalert/sweetalert.min.js',
                'include/js/diagnosis/d_manage.js',
                'include/js/pagination.js',
                'include/lib/jquery.datetimepicker.js',
                'include/lib/jquery.fileupload/js/jquery.fileupload.js',                
            );
    
            $userid = get_session_user_id();
            $userauth= get_session_user_level();
            if($userauth ==="") {
                $userauth = "";
                $userid = -1;
            }
            $this->data['user_level'] = $userauth;
            $this->data['stval']="";
            $this->data['view_flag']=$view_flag;
            $this->data['userid']=$userid;
            $this->data['survey_total_count']= 0;
            $this->data['menu'] = '진단';
            if ($view_flag == 1)
                $this->data['submenu'] = '진단현황';    
            else
                $this->data['submenu'] = '진단목록';    
    
            $this->load->view('templates/header', $this->data);
            $this->load->view('templates/nav', $this->data);
            if ($view_flag == 1)
                $this->load->view('diagnosis/d_manage', $this->data);
            else
                $this->load->view('diagnosis/d_draft', $this->data);
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

    /*
     * 설문목록페지에서 실지 자료를 얻는 함수
    *  $view_flag : 1:  진단현황 , 2: 진단목록, 3: 진단교육과정
     * */
    public function get_my_diagnosises_list()
    {
        $user_id = get_session_user_id();
        if($user_id ==="" || empty($user_id)) {
            $user_id = -1;
        }
        $my_page =isset($_POST['page']) ? $_POST['page']:0;
        $my_per_page =isset($_POST['page_per_count']) ? $_POST['page_per_count']:10;
        $view_flag =isset($_POST['view_flag']) ? $_POST['view_flag']:1;

        $this->data['my_page']=$my_page;
        $this->data['my_per_page']=$my_per_page;
        $this->data['view_flag']=$view_flag;

        if ($view_flag == 1) {
            // 진단현황

            $this->data['my_items'] = array();
            $this->data['my_item_total'] = 0;            
                        
            $this->load->view('diagnosis/d_manage_list', $this->data);
        }
        else if ($view_flag == 2) {
            // 진단목록
            $this->data['survey_admin'] = isset($_POST['admin']) ? $_POST['admin']:'';        
            $this->data['survey_begindate'] = isset($_POST['begindate']) ? $_POST['begindate']:1;
            $this->data['survey_enddate'] = isset($_POST['enddate']) ? $_POST['enddate']:1;
            $this->data['survey_group'] = isset($_POST['group']) ? $_POST['group']:'';
            $this->data['survey_tool'] = isset($_POST['tool']) ? $_POST['tool']:'';
    
            if ($this->data['survey_admin'] == "") {
                if ($this->data['survey_group'] != "") {
                    $search_userid = 'all';
                }
                else {
                    $search_userid = $user_id;            
                }
            }
            else {
                $search_userid = $this->users_model->get_user_id_from_name($this->data['survey_admin'],
                    $this->data['survey_job']);
            }
            $this->data['user_id'] = $search_userid;  

            $my_items = $this->diagnosis_model->get_diagnosises($this->data);
            $this->data['my_item_total'] =  $this->diagnosis_model->get_diagnosises_total_count($this->data)[0]['total_count'];   
            $this->data['my_items']= $my_items;
    
            $this->load->view('diagnosis/d_draft_list', $this->data);
        }       
        else {
            // 진단과정목록
            $this->data['diagnosis_begindate'] = isset($_POST['begindate']) ? $_POST['begindate']:1;
            $this->data['diagnosis_enddate'] = isset($_POST['enddate']) ? $_POST['enddate']:1;
            $this->data['diagnosis_customer'] = isset($_POST['customer']) ? $_POST['customer']:'';
            $this->data['diagnosis_education'] = isset($_POST['education']) ? $_POST['education']:'';
            $this->data['diagnosis_count'] = isset($_POST['education_count']) ? $_POST['education_count']:'';
            $this->data['user_id'] = $user_id;  
            $this->data['prev_survey_id'] = isset($_POST['prev_survey_id']) ? $_POST['prev_survey_id']:'';
            
            $this->data['my_items']= $this->diagnosis_model->get_diagnosis_education($this->data);
            $this->data['my_item_total'] = $this->diagnosis_model->get_diagnosis_education_count($this->data);
            $this->load->view('diagnosis/d_education_list', $this->data);
        } 
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

        $this->load->view('diagnosis/d_survey_list', $this->data);
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
        $survey = $this->diagnosis_model->get_data($condition);
        $survey_ends = $this->diagnosis_model->get_surveys_end_comment($survey_id);
        $survey_data['surveys'] = $survey;
        $survey_data['end_comments'] = $survey_ends;

        $question_data = $this->d_question_groups_model->getExampleData($survey_id);
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

    // 설문보관
    public function save()
    {
        $user_id = get_session_user_id();
        if($user_id !=="" && $user_id > 0) {
            $survey_id = $this->input->get_post('survey_id');
            $survey_attached = $this->input->get_post('survey_attached');
            $attached_file_name = $this->input->get_post('attached_file_name');
            $survey_title = $this->input->get_post('survey_title');
            $start_time = $this->input->get_post('start_time');            
            $end_time = $this->input->get_post('end_time');
            $schedule_name = $this->input->get_post('schedule_name');
            $schedule_count = $this->input->get_post('schedule_count');
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
            $end_count = 0;

            if($survey_attached === "1") {
                $attached_file_name = get_full_url()."/".$attached_file_name;
            }

            $survey_data = array(
                'attached' => $survey_attached,
                'file_url' => $attached_file_name,
                'title' => $survey_title,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'schedule_name' => $schedule_name,
                'schedule_count' => $schedule_count,
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
            );

            $condition = array(
                'id' => $survey_id,
            );                
                        
            $survey_id = $this->diagnosis_model->insert_update_data($survey_data, $condition);                                    

            $real_end_comments = json_decode($end_comments);

            $flag = $this->diagnosis_model->insert_end_comment($survey_id, $real_end_comments);
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
                $question_group_id = $this->d_question_groups_model->insert_update_data($question_group_data, $condition);

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
                        $question_id = $this->d_questions_model->insert_update_data($question_data, $condition);
                        $condition = array(
                            'question_id' => $question_id
                        );
                        $this->d_examples_model->delete_data($condition);
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

                            $this->d_examples_model->insert_data($example_data);
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
                        $question_id = $this->d_questions_model->insert_update_data($question_data, $condition);
                    } else if ($question->type == 2) {
                        $question_data_2 = array(
                            'type_grade' => $question->type_grade,
                        );
                        $question_data = array_merge($question_data, $question_data_2);
                        $condition = array(
                            'survey_id' => $survey_id,
                            'question_group_id' => $question_group_id,
                            'number' => $question->number,
                        );
                        $question_id = $this->d_questions_model->insert_update_data($question_data, $condition);
                    } else if($question->type == 3){

                        $question_data_3 = array(
                            'type_grade' => $question->type_grade,
                            'exam_kind_count' => $question->exam_kind_count,
                            'exam_object_count' => $question->exam_object_count
                        );

                        $question_data = array_merge($question_data, $question_data_3);
                        $condition = array(
                            'survey_id' => $survey_id,
                            'question_group_id' => $question_group_id,
                            'number' => $question->number,
                        );
                        $question_id = $this->d_questions_model->insert_update_data($question_data, $condition);
                        $condition = array(
                            'question_id' => $question_id
                        );
                        //평가지표보관
                        $this->d_question_exam_kinds_model->delete_data($condition);
                        foreach ($question->exam_kinds as $exam_kind) {
                            $exam_kind_data = array(
                                'question_id' => $question_id,
                                'number' => $exam_kind->number,
                                'title' => $exam_kind->title,
                                'content' => $exam_kind->content
                            );
                            $this->d_question_exam_kinds_model->insert_data($exam_kind_data);
                        }

                        //평가대상보관
                        $this->d_question_exam_objects_model->delete_data($condition);
                        foreach ($question->exam_objects as $exam_object) {
                            $exam_object_data = array(
                                'question_id' => $question_id,
                                'number' => $exam_object->number,
                                'title' => $exam_object->title,
                            );
                            $this->d_question_exam_objects_model->insert_data($exam_object_data);
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

            $survey_id = $this->input->get_post('survey_id');
            $survey_attached = $this->input->get_post('survey_attached');
            $attached_file_name = $this->input->get_post('attached_file_name');
            $survey_title = $this->input->get_post('survey_title');
            $start_time = $this->input->get_post('start_time');
            $end_time = $this->input->get_post('end_time');
            $schedule_name = $this->input->get_post('schedule_name');
            $schedule_count = $this->input->get_post('schedule_count');
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
                'schedule_name' => $schedule_name,
                'schedule_count' => $schedule_count,
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
            );

            $condition = array(
                'id' => $survey_id,
            );

            $survey_id = $this->diagnosis_model->insert_update_data($survey_data,$condition);

            $real_end_comments = json_decode($end_comments);

            $flag = $this->diagnosis_model->insert_end_comment($survey_id,$real_end_comments);
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
                $question_group_id = $this->d_question_groups_model->insert_update_data($question_group_data, $condition);

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

                        $question_id = $this->d_questions_model->insert_update_data($question_data, $condition);

                        $condition = array(
                            'question_id' => $question_id
                        );
                        $this->d_examples_model->delete_data($condition);

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

                            $this->d_examples_model->insert_data($example_data);

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
                        $question_id = $this->d_questions_model->insert_update_data($question_data, $condition);

                    } else if ($question->type == 2) {
                        $question_data_2 = array(
                            'type_grade' => $question->type_grade,
                        );
                        $question_data = array_merge($question_data, $question_data_2);
                        $condition = array(
                            'survey_id' => $survey_id,
                            'question_group_id' => $question_group_id,
                            'number' => $question->number,
                        );
                        $question_id = $this->d_questions_model->insert_update_data($question_data, $condition);
                    } else if($question->type == 3){

                        $question_data_3 = array(
                            'type_grade' => $question->type_grade,
                            'exam_kind_count' => $question->exam_kind_count,
                            'exam_object_count' => $question->exam_object_count
                        );

                        $question_data = array_merge($question_data, $question_data_3);
                        $condition = array(
                            'survey_id' => $survey_id,
                            'question_group_id' => $question_group_id,
                            'number' => $question->number,
                        );
                        $question_id = $this->d_questions_model->insert_update_data($question_data, $condition);
                        $condition = array(
                            'question_id' => $question_id
                        );
                        //평가지표보관
                        $this->d_question_exam_kinds_model->delete_data($condition);
                        foreach ($question->exam_kinds as $exam_kind) {
                            $exam_kind_data = array(
                                'question_id' => $question_id,
                                'number' => $exam_kind->number,
                                'title' => $exam_kind->title,
                                'content' => $exam_kind->content
                            );
                            $this->d_question_exam_kinds_model->insert_data($exam_kind_data);
                        }

                        //평가대상보관
                        $this->d_question_exam_objects_model->delete_data($condition);
                        foreach ($question->exam_objects as $exam_object) {
                            $exam_object_data = array(
                                'question_id' => $question_id,
                                'number' => $exam_object->number,
                                'title' => $exam_object->title,
                            );
                            $this->d_question_exam_objects_model->insert_data($exam_object_data);
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
                $condition = array(
                    'id' => $survey_id,
                    'user_id' => $user_id
                );
                $this->diagnosis_model->delete_data($condition);
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

                $this->diagnosis_model->update_data($data, $condition);
            }
            echo 1;
        }
    }

    public function browse_diagnosis_education()
    {
        if (is_signed()) {
            $this->data['scripts'] = array(
                'include/plugins/bootstrap-sweetalert/sweetalert.min.js',
                'include/js/diagnosis/d_manage.js',
                'include/js/pagination.js',
                'include/lib/jquery.datetimepicker.js',
                'include/lib/jquery.fileupload/js/jquery.fileupload.js',                
            );
    
            $userid = get_session_user_id();
            $userauth= get_session_user_level();
            if($userauth ==="") {
                $userauth = "";
                $userid = -1;
            }
            
            $this->data['view_flag']=3; // 진단교육과정
            $this->data['userid']=$userid;
            $this->data['user_level'] = $userauth;
            $this->data['prev_survey_id']=$_GET['prev_survey_id'];
            $this->data['diagnosis_title']=$_GET['diagnosis_title'];
            $this->data['begindate']=$_GET['begindate'];
            $this->data['enddate']=$_GET['enddate'];
            
            $this->data['survey_total_count']= 0;
            $this->data['menu'] = '진단';
            $this->data['submenu'] = '진단과정';    
    
            $this->load->view('templates/header', $this->data);
            $this->load->view('templates/nav', $this->data);
            $this->load->view('diagnosis/d_education', $this->data);
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

    public function upload_diagnosis_excel()
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
                $final_erp_excel = 'd_'.rand(1000,1000000).'_'.$excel;
                
                // check's valid format
                if(in_array($ext, $valid_extensions)) 
                { 
                    $path = $path.strtolower($final_erp_excel); 
                    if(move_uploaded_file($tmp, $path)) 
                    {
                        $base_path = $this->input->server('DOCUMENT_ROOT') . base_url();
                        $erp_excel_path = $path;
                        if ($erp_excel_path != "") {                           
                            $erp_excel_path = $base_path.$erp_excel_path;
                            $this->load->library('excel');

                            try {
                                $inputFileType = PHPExcel_IOFactory::identify($erp_excel_path);
                                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                                $objPHPExcel = $objReader->load($erp_excel_path);
                            } catch(Exception $e) {                        
                                exit;
                            }
                            
                            //  Get worksheet dimensions
                            $objWorksheet = $objPHPExcel->getActiveSheet();
                            $highestRow = $objWorksheet->getHighestRow();
                            $data = array();
                            $data['upload_excel'] =  $erp_excel_path;
                            $data['upload_date'] =  date('Y-m-d');
                            foreach ($objWorksheet->getRowIterator() as $i => $row) {
                                $cellIterator = $row->getCellIterator();
                                $cellIterator->setIterateOnlyExistingCells(false);
                                $row = [];
                                foreach ($cellIterator as $cell) {
                                    $row[] = $cell->getValue();
                                }                         
                                
                                if ($i > 1) {                                    
                                    $data['userid'] = $user_id;
                                    $data['customer_name'] = str_replace(' ', '', $row[0]);   // 고객사명
                                    $data['education_name'] = str_replace(' ', '', $row[1]);  // 과정명
                                    $data['education_count'] = str_replace(' ', '', $row[2]); // 차수
                                    $data['result_count'] = str_replace(' ', '', $row[3]);    // 결과용순번
                                    $data['client_rs_code'] = str_replace(' ', '', $row[5]);  // 피평가자와의 관계
                                    $data['client_name'] = str_replace(' ', '', $row[6]);     // 평가자이름
                                    $data['client_phone'] = str_replace(' ', '', $row[7]);    // 평가자연락처
                                    $data['client_group'] = str_replace(' ', '', $row[8]);    // 평가자소속
                                    $data['client_email'] = str_replace(' ', '', $row[9]);    // 평가자E-mail
                                    $this->diagnosis_model->set_diagnosis_education($data);                                   
                                }
                            }                            
                        }       
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

