<?php
/**
 * Author: KMC
 * Date: 10/7/18
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Preview extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();


        // date_default_timezone_set('Asia/Pyongyang');

        $this->data['title'] = SITE_TITLE;
        $this->data['styles'] = array(
            'include/css/survey.css',
            'include/lib/nouislider/nouislider.min.css',
            'include/css/plugins.css',
        );

        $this->data['scripts'] = array(
            'include/lib/nouislider/nouislider.min.js',
            'include/js/preview/preview.js'
        );

        $this->load->model('users_model');
        $this->load->model('surveys_model');
        $this->load->model('questions_model');
        $this->load->model('examples_model');

        $this->load->model('notices_model');
        $this->load->model('reviews_model');
        $this->load->model('mobiles_model');
        $this->load->model('shorturls_model');

        $this->load->model('advert_model');
        $this->load->model('member_link_model');
        $this->load->helper('my_url');
        $this->load->helper('my_directory');
        $this->load->model('goji_model');

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
        $short_url = $_SERVER['REQUEST_URI'];
      
        $advert_result = array();
        $short_url = substr($short_url, 1, strlen($short_url));
        $id = base62_decode($short_url);
        $long_url = $this->shorturls_model->get_data_url($id);

        if (sizeof($long_url) > 0) {
            $is_survey = 1;
            $mobile = $long_url[0]['mobile'];
            $notice_id = $long_url[0]['notice_id'];
            $advert_link = $long_url[0]['advert_link'];
            if (empty($mobile)) {
                $this->data['error'] = "페지오류";
                $this->load->view('preview/bin', $this->data);
                return;
            }
            if (empty($notice_id)) {
                $this->data['error'] = "페지오류";
                $this->load->view('preview/bin', $this->data);
                return;
            }

            $this->data['mobile'] = $mobile;
            $condition = array(
                'id' => $notice_id
            );
            $result = $this->notices_model->get_data($condition);
            if(sizeof($result) > 0) {

                if ($result[0]['survey_id'] === null || $result[0]['survey_id'] === "") {
                    $is_survey = 0;

                    if($result[0]['message_type'] == 4) {//개별고지이면
                        $goji_var = $this->goji_model->get_var($result[0]['em_ukey'], $mobile);

                        if(count($goji_var) > 0)
                            $goji_doc = $this->goji_model->get_gojidoc_row($goji_var[0]['edcv_udoc']);
                        else{
                            $this->data['error'] = "고지변수가 존재하지 않습니다.";
                            $this->load->view('preview/bin', $this->data);

                            return;
                        }

                        $this->data['message_type'] = 4;
                        $this->data['var_list'] = explode("|", $goji_var[0]['edcv_var']);

                        if(count($goji_doc) > 0){
                            $filename = $goji_doc[0]['edoc_wurl'];
                            $file_ext= strrchr($filename,".");
                            $filename = str_replace($file_ext,'-replaced-goji.html',$filename);
                            $this->data['file_url'] =FCPATH . 'uploads/html/'.$filename;
                        }else{
                            $this->data['error'] = "페지오류";
                            $this->load->view('preview/bin', $this->data);

                            return;
                        }

                    }else
                        $this->data['file_url'] = $result[0]['file_url'];

                } else {
                    $survey_id = $result[0]['survey_id'];

                }
            } else {
                $this->data['error'] = "페지오류";
                $this->load->view('preview/bin', $this->data);

                return;
            }
            if($advert_link !=="") {
                $advert_link_array = explode(",", $advert_link);
                $advert_result = $this->advert_model->get_advert_link_list($advert_link_array);
            }

            $this->data['mobile'] = $mobile;
            $this->data['advert_result'] = $advert_result;
            $this->data['error'] = "0";
            if ($is_survey === 1) {  //문서포함 문자전송이 아니면
                $survey = $this->surveys_model->get_data_by_id($survey_id);
                if(sizeof($survey) <1) {

                    $this->data['error'] = "페지오류";
                    $this->load->view('preview/bin', $this->data);

                    return;
                }
                $query = $this->db->get_where('review', array(//making selection
                    'mobile' => $mobile,
                    'notice_id' => $notice_id

                ));
                $count = $query->num_rows(); //counting result from query
                if ($count !== 0) {
                    $this->data['error'] = "이미 응답한 설문입니다.";
                    $this->load->view('preview/bin', $this->data);
                    return;
                }
                if ($survey[0]['end_condition'] === "1") {
                    $respose_count = $this->reviews_model->get_response_count($notice_id);
                    if ($respose_count[0]['cnt'] > $survey[0]['end_count']) {
                        $this->data['error'] = "2";
                    }
                } else {
                    $timenow = date("Y-m-d H:i:s");
                    $timetarget = "2017-03-15 00:00:00";
                    $str_now = strtotime($timenow);
                    $str_start = strtotime($survey[0]['start_time']);
                    $str_end = strtotime($survey[0]['end_time']);
                    if ($str_now < $str_start || $str_now > $str_end) {
                        $this->data['error'] = "2";
                    }
                }
                $reply_flag = $this->reviews_model->set_reply_count($notice_id, $mobile);
                if ($reply_flag < 0) {
                    $this->data['error'] = "실패";
                    $this->load->view('preview/bin', $this->data);
                    return;
                }
                $end_comments = $this->surveys_model->get_end_comment($survey_id);
                $this->data['surveys'] = $survey[0];
                $this->data['survey_id'] = $survey_id;
                $this->data['notice_id'] = $notice_id;

                $question_data = $this->questions_model->getExampleData($survey_id);
                $this->data['questions'] = $question_data;
                $this->data['end_comments'] = $end_comments;
                $this->data['is_survey'] = $is_survey;

                if ($survey[0]['auth'] == 1) {
                    $this->session->set_userdata('mobile', $mobile);
                    $this->session->set_userdata('surveys', $survey[0]);
                    $this->session->set_userdata('survey_id', $survey_id);
                    $this->session->set_userdata('notice_id', $notice_id);
                    $this->session->set_userdata('questions', $question_data);
                    $this->session->set_userdata('is_survey', $is_survey);
                    $this->session->set_userdata('error', $this->data['error']);
                    $this->session->set_userdata('end_comments', $end_comments);
                    $this->session->set_userdata('advert_result', $advert_result);

                    $this->load->view('preview/header', $this->data);

                    $this->load->view('preview/authorization', $this->data);
                    $this->load->view('preview/scripts', $this->data);
                    $this->load->view('preview/footer', $this->data);
                    return;
                }
            } else {
                $result = $this->reviews_model->set_reply_count($notice_id, $mobile);
                if ($result < 0) {
                    $this->data['error'] = "실패";
                    $this->load->view('preview/bin', $this->data);
                }
            }
            $this->data['is_survey'] = $is_survey;

            $this->load->view('preview/header', $this->data);

            $this->load->view('preview/preview', $this->data);
            $this->load->view('preview/scripts', $this->data);
            $this->load->view('preview/footer', $this->data);
        } else {
            die('Not a valid URL');
        }
    }

    public function view() {
        if(empty($this->session->userdata('mobile'))) {
            $this->data['error'] = "실패";
            $this->load->view('preview/bin', $this->data);
            return;
        }
        $this->data['mobile'] =  $this->session->userdata('mobile');
        $this->data['surveys'] =  $this->session->userdata('surveys');
        $this->data['survey_id']= $this->session->userdata('survey_id');
        $this->data['notice_id']= $this->session->userdata('notice_id');
        $this->data['questions'] =  $this->session->userdata('questions');
        $this->data['is_survey'] =  $this->session->userdata('is_survey');
        $this->data['error'] =  $this->session->userdata('error');
        $this->data['end_comments'] =  $this->session->userdata('end_comments');
        $this->data['advert_result'] =  $this->session->userdata('advert_result');

        $this->load->view('preview/header',  $this->data);
        $this->load->view('preview/preview',  $this->data);
        $this->load->view('preview/scripts', $this->data);
        $this->load->view('preview/footer',  $this->data);
    }

    public function auth_confirm()
    {
        $result = 0;
        $mobile = $this->session->userdata('mobile');
        $address_num = $this->input->get_post('address_num');
        $sub = substr($mobile,strlen($mobile)-4,strlen($mobile));
        if($sub === $address_num) {
            $result = 1;
        } else {
            $result = -1;
        }
        /*$result = $this->mobiles_model->confirm_address_num($mobile,$address_num);*/
        echo $result;
    }

    public function save() {
        $query = $this->db->get_where('review', array(//making selection
            'mobile' => $this->input->get_post('mobile'),
            'notice_id'=>$this->input->get_post('notice_id')
        ));
        $count = $query->num_rows(); //counting result from query
        if ($count !== 0)
        {
            echo -1;
            exit;
        }
        
        $mobile = $this->input->get_post('mobile');
        $notice_id = $this->input->get_post('notice_id');
        $answer = $this->input->get_post('answer');
        $response_man = $this->input->get_post('response_man');
        $data = array(
            'mobile' => $mobile,
            'notice_id' => $notice_id,
            'answer' => $answer,
            'response_man' => $response_man,
            'reply_count'=>0

        );

        $notice_id = $this->reviews_model->insert_data($data);


        echo $notice_id;
    }

//  설문불러오기에서 미리보기
    public function preview($survey_id)
    {
        $this->data['scripts'] = array(
            'include/lib/nouislider/nouislider.min.js',
            'include/js/survey/preview.js'
        );

        $this->data['error'] = "0";
        $survey = $this->surveys_model->get_data_by_id($survey_id);


        $end_comments = $this->surveys_model->get_end_comment($survey_id);
        $this->data['surveys'] = $survey[0];
        $this->data['survey_id'] = $survey_id;


        $question_data = $this->questions_model->getExampleData($survey_id);
        $this->data['questions'] = $question_data;
        $this->data['end_comments'] = $end_comments;

        if ($survey[0]['auth'] == 1) {

            $this->session->set_userdata('surveys', $survey[0]);
            $this->session->set_userdata('survey_id', $survey_id);

            $this->session->set_userdata('questions', $question_data);

            $this->session->set_userdata('error', $this->data['error']);
            $this->session->set_userdata('end_comments', $end_comments);

            $this->load->view('preview/header', $this->data);

            $this->load->view('survey/authorization', $this->data);
            $this->load->view('preview/scripts', $this->data);
            $this->load->view('preview/footer', $this->data);
            return;
        }

        $this->load->view('preview/header', $this->data);

        $this->load->view('survey/preview', $this->data);
        $this->load->view('preview/scripts', $this->data);
        $this->load->view('preview/footer', $this->data);
    }

    //  설문불러오기에서 미리보기
    public function survey_view() {

        $this->data['mobile'] =  $this->session->userdata('mobile');
        $this->data['surveys'] =  $this->session->userdata('surveys');
        $this->data['survey_id']= $this->session->userdata('survey_id');
        $this->data['notice_id']= $this->session->userdata('notice_id');
        $this->data['questions'] =  $this->session->userdata('questions');
        $this->data['is_survey'] =  $this->session->userdata('is_survey');
        $this->data['error'] =  $this->session->userdata('error');
        $this->data['end_comments'] =  $this->session->userdata('end_comments');

        $this->load->view('preview/header',  $this->data);
        $this->load->view('survey/preview',  $this->data);
        $this->load->view('preview/scripts', $this->data);
        $this->load->view('preview/footer',  $this->data);

    }

// ---------- <모바일보기> html문서로 변환 ----------
    public function mobileView(){
        $file_path = isset($_POST['file_path']) ? $_POST['file_path']:'';

        $htmlPage = file_get_contents($file_path);
        if($htmlPage == false) {
            echo -2;
            return;
        }

        $startBodyPos = strpos($htmlPage, '<body>');
        $endBodyPos = strpos($htmlPage, '</body>');
        $startHeadPos = strpos($htmlPage, '<head>');
        $endHeadPos = strpos($htmlPage, '</head>');
        $content = substr($htmlPage,$startBodyPos + 6,$endBodyPos - $startBodyPos - 6);

        //-------- font-size를 2.5배 하기 -----------
        $pos = 0; //starting position
        for ($i = 1; $i <= substr_count($content, 'font-size:'); $i++) { //loop through the images

            $locStart = strpos($content, 'font-size:', $pos); //starting position of this image tag
            $locEnd = strpos($content, 'pt', $locStart);  //end of this image tag
            $pos = $locEnd; //set starting position for next image, if any

            $font_size = substr($content, $locStart + 10, ($locEnd - $locStart) - 10);  //this is just the image tag
            $content = substr_replace($content,$font_size * 3,$locStart + 10, ($locEnd - $locStart) - 10);
        };

        //-------- <p>태그안의 width를 없애기 -----------
        $pos = 0; //starting position
//        $content = "asdfadasdfasdf <p"." style = 'width: 123pt; sdfsdfsd height:12pt'><sdfsdf width: 123pt; sdf";
        for ($i = 1; $i <= substr_count($content, '<p'); $i++) { //loop through the images
            // -------- <p> 태그 찾기 -----------
            $locStart = strpos($content, '<p', $pos);
            $locEnd = strpos($content, '>', $locStart);
            $pos = $locEnd;

            $tag = substr($content, $locStart, ($locEnd-($locStart-1)) );

            //--------- width속성을 없애기 ------------
            $widthStart = strpos($tag, 'width:');
            $widthEnd = strpos($tag, 'pt', $widthStart);
            if($widthStart != false)
                $content = substr_replace($content,"",$locStart + $widthStart, ($widthEnd - $widthStart) + 2);

            //--------- text-intent 없애기 ------------
            $widthStart = strpos($tag, 'text-indent:');
            $widthEnd = strpos($tag, 'pt', $widthStart);
            if($widthStart != false)
                $content = substr_replace($content,"",$locStart + $widthStart, ($widthEnd - $widthStart) + 2);
        };

        $headContent = substr($htmlPage,$startHeadPos + 6,$endHeadPos - $startHeadPos - 6);
        echo $headContent.$content;

    }
// ---------- <일반> html문서를 적재 ----------
    public function commonView(){
        $file_path = isset($_POST['file_path']) ? $_POST['file_path']:'';

        $htmlPage = file_get_contents($file_path);
        if($htmlPage == false) {
            echo -2;
            return;
        }

        echo $htmlPage;
    }

}

/* End of file home.php */
/* Locaion: ./application/controllers/Home.php */