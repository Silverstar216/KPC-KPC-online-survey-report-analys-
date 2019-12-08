<?php
/**
 * Author: KMC
 * Date: 10/6/15
 */

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH."/third_party/class.sms.php";
include APPPATH.'third_party/docx_reader.php';
class Notice extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        // date_default_timezone_set('Asia/Pyongyang');
        $this->load->helper('control_helper');
        $this->data['title'] = SITE_TITLE;
        $this->data['styles'] = array(
            'include/css/log.css',
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
            'include/js/notice.js',
        );

        $this->load->model('notices_model');
        $this->load->model('messages_model');
        $this->load->model('surveys_model');
        $this->load->model('educations_model');
        $this->load->model('mobiles_model');
        $this->load->model('senderphone_model');
        $this->load->model('groups_model');
        $this->load->model('msg_queue_model');
        $this->load->model('attached_content_model');
        $this->load->helper('my_directory');
        $this->load->helper('my_url');
        $this->load->model('shorturls_model');
        $this->load->model('money_model');
        $this->load->model('sample_reader_model');
        $this->load->model('advert_model');
        $this->load->model('member_link_model');
        $this->load->model('users_model');
        $this->load->model('goji_model');


    // 설문의 sms전송페지와 련동되는 정보
    // survye = 1이면서 object_id>0인경우 설문전송방식이다.
        $object_id = $this->input->get_post('object_id');
        $survey = $this->input->get_post('survey');

        if (empty($object_id))
            $object_id = 0;

        $this->data['object_id'] = $object_id;

        if (empty($survey))
            $survey = 0;

        $this->data['survey'] = $survey;

        $file_url = "";

        //notice페지에서 발신번호등록성공시 재호출될때 식별파라메터받기
        $this->data['IsShowRegisterArea'] = $this->input->get_post('IsShowRegisterArea');
        //등록번호페지에서 넘어오는 변수
        $m = $this->input->get_post('m');
        $n = $this->input->get_post('n');
        if (empty($m))
            $m = 0;
        $this->data['m'] = $m;
        if (empty($n))
            $n = 0;
        $this->data['n'] = $n;

        $comment = '';
        $attached = 0;
        $education_id = 0;
        $education_title = '';
        $survey_title = '';
        if ($survey == 1 && $object_id > 0) {
            $condition = array (
                'id' => $object_id
            );
            $survey_data = $this->surveys_model->get_data($condition);
            if (sizeof($survey_data) > 0) {
               /* $comment = $survey_data[0]['comment'];*/
                $survey_title = $survey_data[0]['title'];
                $attached = $survey_data[0]['attached'];
                $file_url = $survey_data[0]['file_url'];
                $education_id = $survey_data[0]['education_id'];
                $this->session->set_userdata('survey_file_url', $file_url);
            }
        }

        if ($education_id > 0) {
            $education_data = $this->educations_model->get_education_schedule_fromid($education_id);
            if (count($education_data) > 0) {
                $education_title = $education_data[0]['subject_name'];
            }
        }
        if ($education_id > 0) {
            $education_data = $this->educations_model->get_education_schedule_fromid($education_id);
            if (count($education_data) > 0) {
                $education_title = $education_data[0]['subject_name'];
            }
        }

        $short_url = $_SERVER['REQUEST_URI'];
       /* $this->data['comment'] = $comment;*/
        $this->data['attached'] = $attached;
        $this->data['object_id'] = $object_id;
        $this->data['education_id'] = $education_id;
        $this->data['education_title'] = $education_title;
        $this->data['survey_title'] = $survey_title;
        $this->data['students'] = $this->educations_model->get_education_students_fromid($education_id);

//        $user_id = 1;
        $user_id = get_session_user_id();
        $user_level= get_session_user_level();
        if($user_level ==="") {
            $user_level = "";
            $user_id = -1;
        }

        // 전화번호 목록 얻어오기
//        $condition = array (
//            'user_id' => $user_id,
//            'flag' => 0,
//        );
//        $this->db->select('mobile,mobiles.name as userName,groups.name as groupName');
//        $this->db->from('mobiles');
//        $this->db->join('groups', 'mobiles.group_id = groups.id');
//        $this->db->where('users_mlh.role = user_roles_mlh.id');
//
//        $query = $this->db->get();

//        return $query->result_array();
//        $this->data['mobile_data'] = $this->mobiles_model->get_Mobiles_sorted($user_id);

        $this->data['group_data'] = $this->groups_model->get_group($user_id);

        //---------- 개별고지페지로부터 넘어오는 파라메터받기 ---------

        $goji = $this->input->get_post('goji');
        $this->data['goji'] = 0;

        if($goji == 1){
            $this->data['goji'] = 1;
            $this->data['object_id'] = $this->input->get_post('em_ukey');
            $this->data['attached'] = 1;
        }
    }

    //일반문자
    public function index()
    {
        if (is_signed()) {
            // $user_id = 1;
            $user_id = get_session_user_id();
            $user_level= get_session_user_level();
            if($user_level ==="") {
                $user_level = "";
                $user_id = -1;
            }
            $this->data['user_id']=$user_id;
            $this->data['menu'] = '문자메시지';
            $this->data['submenu'] = '일반문자';    

            $this->load->view('templates/header', $this->data);
            $this->load->view('templates/nav', $this->data);

            $start_date = $this->input->get_post('start_date');
            // $end_date = $this->input->get_post('end_date');
            if (empty($start_date))
            {
                $start_date = date('Y-m-d');
            }
            // if (empty($end_date))
            // {
            //     $end_date = date('Y-m-d');
            // }
            $this->data['start_date'] = $start_date;
            // $this->data['end_date'] = $end_date;

            // $result = $this->surveys_model->get_data_reserve($user_id, $start_date, $end_date);
            // $this->data['result'] = $result;

            //발신번호를 적재
            $condition = array(
                'user_id' => $user_id,
            );

            $this->data['senderPhoneList'] = $this->senderphone_model->get_data($condition);
            // ------------ 개별고지로부터 넘어온 경우 --------------
            if($this->data['goji'] == 1){
                $this->data['gojiMobiles'] = $this->goji_model->getMobiles($this->data['object_id']);
            }

            $this->load->view('notice/notice', $this->data);
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

    //가정통신문
    public function document()
    {
        if (is_signed()) {
            $user_id = get_session_user_id();
            $user_level= get_session_user_level();
            if($user_level ==="") {
                $user_level = "";
                $user_id = -1;
            }
            $this->data['user_id']=$user_id;
            $start_date = $this->input->get_post('start_date');
            // $end_date = $this->input->get_post('end_date');
            if (empty($start_date))
            {
                $start_date = date('Y-m-d H:i');
            }
            
            $condition = array(
                'user_id' => $user_id,
            );
            $this->data['menu'] = '문자메시지';
            $this->data['submenu'] = '문서포함';    
            $this->data['senderPhoneList'] = $this->senderphone_model->get_data($condition);
            $this->data['start_date'] = $start_date;
            $this->load->view('templates/header', $this->data);
            $this->load->view('templates/nav', $this->data);
            $this->load->view('notice/document', $this->data);
            $this->load->view('notice/modal_attachedHTML', $this->data);
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

//    hwp/pdf/docx/doc/xls/jpg/html파일을 html로 변환
    function convertToHTML()
    {
        $user_level= get_session_user_level();

//        if($user_level ==="test" || $user_level ==="") {
//            echo -1;
//            return;
//        }
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
        } else if(strpos($file_name, '.hwp') > 0){ 
  
            $to = FCPATH . 'uploads/html/'.substr($file_name,0,strpos($file_name, ".hwp")).'.html';
            shell_exec("cd /opt/hwp2htmlEX && ./conv -s '".$from."' -o '".$to."' -m convert");

            $converted_url = 'uploads/html/'.substr($file_name,0,strpos($file_name, ".hwp")).'.html';
            //pdf파일인 경우
        } else if(strpos($file_name, '.pdf') > 0){
            $fromDir = FCPATH . 'uploads/tmp';
            shell_exec("pdf2htmlEX --fallback 1 --process-outline 0 --dest-dir '".$fromDir."' '".$from."'");

            $toHTML = FCPATH . 'uploads/html/'.substr($file_name,0,strpos($file_name, ".pdf")).'.html';
            $fromHTML = FCPATH . 'uploads/tmp/'.substr($file_name,0,strpos($file_name, ".pdf")).'.html';
            if(copy($fromHTML,$toHTML))
                $converted_url = 'uploads/html/'.substr($file_name,0,strpos($file_name, ".pdf")).'.html';
            else
                $converted_url = "";
            //doc파일인 경우
        } else if(strpos($file_name, '.doc') > 0){

//            shell_exec('/opt/libreoffice5.0/program/python /opt/libreoffice5.0/program/conv.py -s "/tmp/test.docx"');
//test
        }

        echo $converted_url;

    }

//전화번호검색
    public function searchPhone()
    {
        $st = isset($_POST['st']) ? $_POST['st']:'all';
        $stval = isset($_POST['stval']) ? $_POST['stval']:'';
        $ngst=isset($_POST['ngst']) ? $_POST['ngst']:'all';
        $page =isset($_POST['page']) ? $_POST['page']:0;
        $count =isset($_POST['count']) ? $_POST['count']:10;

        $userid = get_session_user_id();
        $userauth= get_session_user_level();
        if($userauth ==="") {
            $userauth = "";
            $userid = -1;
        }
        $this->data['user_level'] = $userauth;

        $this->data['st']=$st;
        $this->data['stval']=$stval;
        $this->data['ngst']=$ngst;
        $this->data['page']=$page;

        $this->data['mobiles'] = $this->mobiles_model->get_Mobiles($userid,$page, $st, $stval, $ngst,$count);

        $this->data['phonenumberCount'] =  $this->mobiles_model->get_total_count($userid, $st, $stval, $ngst)[0]['total_count'];
        $this->load->view('notice/phoneSearchResult', $this->data);
        //echo $this->data['mobiles'];
    }
    
    public function getHtml(){

    }

    public function send()
    {
        $user_id = get_session_user_id();
        $user_level= get_session_user_level();

        if($user_level ==="-1" || $user_level ==="") {
            echo -1;
            return;
        }
        
        $content = $this->input->get_post('content');
        $message_kind = $this->input->get_post('message_kind');
        $message_type = $this->input->get_post('message_type');
        $attached = $this->input->get_post('attached');
        $file_url = $this->input->get_post('file_url');
        $mobile_count = $this->input->get_post('mobile_count');
        $calling_number = $this->input->get_post('calling_number');
        $calling_name = $this->input->get_post('calling_name');

        $start_time = $this->input->get_post('start_time');
        $survey_id = null;
        $em_ukey = null;
        if($message_type != 4)
            $survey_id = $this->input->get_post('object_id');
        else //개별고지전송이면
            $em_ukey = $this->input->get_post('object_id');

        $mobiles = $this->input->get_post('mobiles');
        $groups = json_decode($this->input->get_post('groups'));
        if($start_time < date('Y-m-d H:i:s')) {
            $start_time = date('Y-m-d H:i:s');
        }
        
        if (empty($survey_id)) {
            $data = array(
                'content' => $content,
                'message_type' => $message_type,
                'message_kind' => $message_kind,
                'attached' => $attached,
                'file_url' => $file_url,
                'mobile_count' => $mobile_count,
                'calling_number' => $calling_number,
                'calling_name' => $calling_name,
                'user_id' => $user_id,
                'start_time' => $start_time,
                'created_at' => date('Y-m-d H:i:s'),
                'em_ukey' => $em_ukey,
                'reply_count'=>0
            );
        } else {
            $file_url = $this->session->userdata('survey_file_url');
            $data = array(
                'content' => $content,
                'message_type' => $message_type,
                'message_kind' => $message_kind,
                'attached' => $attached,
                'file_url' => $file_url,
                'mobile_count' => $mobile_count,
                'calling_number' => $calling_number,
                'calling_name' => $calling_name,
                'user_id' => $user_id,
                'start_time' => $start_time,
                'created_at' => date('Y-m-d H:i:s'),
                'survey_id'=>$survey_id,
                'reply_count'=>0
            );
            $survey_data =array(
                'is_send'=>1
            );
            $condition = array(
                'id'=>$survey_id
            );
            $this->surveys_model->update_data($survey_data,$condition);
        }
        $notice_id = $this->notices_model->insert_data($data);
        
        $advert_link_list = $this->set_advert($mobile_count);
        $this->session->set_userdata('advert_link', $advert_link_list);
        echo $notice_id;
    }

    //예문전송
    public function send_sample()
    {
        $calling_number = $this->input->get_post('calling_number');
        $receive_mobile = $this->input->get_post('receive_mobile');

        //예문내용꺼내기
        $recentSample = $this->sample_reader_model->getRecentSample()[0]['si_msg'];

        //msg_queue테블에 예문내용삽입하기
        $request_time = date('Y-m-d H:i:s');
        $data = array(
            'notice_id' => -1,
            'user_id'=>1,
            'msg_type' => '',
            'dstaddr' => $receive_mobile,
            'callback' => $calling_number,
            'stat' => '0',
            'request_time' => $request_time,
            'text'=>$recentSample,
        );

        //sms인가 lms인가를 판단
        $msg_length = mb_strwidth ( $recentSample ,'UTF-8' );
        if($msg_length > 90) {
            $data['msg_type']="3";
            $data['subject']='예문입니다';
        } else {
            $data['msg_type']="1";
        }
        $result = $this->msg_queue_model->insert_data($data);
        //휴대폰번호가 주소록에 있는가를 검사
        $checkPhone = $this->mobiles_model->confirm_address_num($receive_mobile,'') > 0 ? 1:0;
        //예문전송결과를 예문열람자테블에 넣기
        if($result > -1) {
            $sample_reader = array(
                'mobile' => $receive_mobile,
                'relation' => $checkPhone,
                'request_time' => $request_time,
                'content' => $recentSample,
            );
            $result = $this->sample_reader_model->insert_data($sample_reader);
        }
        if($result > -1)
            echo "success";
        else
            echo "fail";
    }

    public function save_mobiles()
    {
        $calling_number = $this->input->get_post('calling_number');
        $object_id = $this->input->get_post('object_id');
        $type = $this->input->get_post('type');
        $kind = $this->input->get_post('kind');
        $attached = $this->input->get_post('attached');
        $mobiles = json_decode($this->input->get_post('mobiles'));
        $groups = json_decode($this->input->get_post('groups'));
        $content = $this->input->get_post('content');
        $sending_at = $this->input->get_post('start_time');
        if ($object_id === null) {
            $response = array(
                'status' => '-2',
                'msg' => '봉사기에로의 전송오류',
            );
            echo json_encode($response);
            return;
        }
        /*$data = array(
            'object_id' => $object_id,
            'type' => $type,
            'kind' => $kind,
            'attached' => $attached,
            'mobile' => '',
            'created_at' => date('Y-m-d H:i:s'),
            'content'=>'',
            'sending_at'=>$sending_at
        );*/
        
        //전송에 이용될 금액을 계산
        $user_id = get_session_user_id();
        //전송총개수얻기
        $totalRecord = count($mobiles);
        foreach ($groups as $group) {
            $totalRecord += count($this->groups_model->get_phones($group));
        }
        //통보문종류값계산
        $user_msg_type = 0;
        if ($type === "1") {
            $user_msg_type += 2;
        }

        if ($kind === "0") {  //  sms
            $user_msg_type += 1 + $attached;
        } else {  //lms
            $user_msg_type += 5 + $attached;
        }

        //이용될금액
        $usedMoney = floatval($totalRecord) * floatval($this->money_model->getNoticePrice($user_id,$user_msg_type));

        //현재 회원요금상황얻기
        // $user_current = $this->money_model->getCurrentMoney($user_id);
        // if($user_current['charge_type'] == '0') {
        //     //선불충전식인 경우 현재 잔고가 이용될 금액보다 작다면 통보문 발생
        //     if (floatval($usedMoney) > floatval($user_current['current_amount'])) {
        //         $response = array(
        //             'status' => '-2',
        //             'msg' => '요금이 부족합니다. 현재 회원님의 잔고액은 ' . $user_current['current_amount'] . '원입니다.',
        //         );
        //         echo json_encode($response);
        //         return;
        //     }
        // }else{
        //     //후불정산제인 경우 후불유지갯수가 이용될 갯수보다 작다면 통보문 발생
        //     if (intval($totalRecord) > intval($user_current['month_count'])) {
        //         $response = array(
        //             'status' => '-2',
        //             'msg' => '요금이 부족합니다. 현재 회원님의 후불유지갯수은 ' . $user_current['month_count'] . '개입니다.',
        //         );
        //         echo json_encode($response);
        //         return;
        //     }
        // }
    
        //msg_queue테블에 통보문들을 전송
        foreach ($mobiles as $mobile)
        {
           /* $full_url = get_full_url()."/answer?n=".$object_id."&m=".$mobile;
            $content1 = $content;
            $param = "notice_id=".$object_id."&mobile=".$mobile;
            $short_mobile = base62_encode($mobile);
            $short_mobile = base62_encode($param);
            $data['mobile'] = $mobile;
            $this->messages_model->insert_data($data);*/
            $flag = $this->send_message( $type, $attached,$kind, $mobile, $calling_number,$content,"",$object_id,$sending_at);
            if($flag < 0) {
                $response = array(
                    'status' => '-2',
                    'msg' => '봉사기에로의 전송오류',
                    'number_phones' => $mobile
                );
                echo json_encode($response);
                return;
            }
            $totalRecord ++;
        }


        foreach ($groups as $group)
        {
            $mobiles_g = $this->groups_model->get_phones($group);

            foreach ($mobiles_g as $mobile_g)
            {

               /* $short_mobile = base62_encode($mobile_g['mobile']);
                $short_mobile = base62_encode($param);
                $data['mobile'] = $mobile_g['mobile'];
                $data['content']=$content;
                $this->messages_model->insert_data($data);*/

                $flag = $this->send_message( $type,$attached, $kind, $mobile_g['mobile'], $calling_number,$content,"",$object_id,$sending_at);
                if($flag < 0) {
                    $response = array(
                        'status' => '-2',
                        'msg' => '봉사기에로의 전송오류',
                        'number_phones' => $mobile_g
                    );
                    echo json_encode($response);
                    return;
                }
                $totalRecord ++;
            }
        }

    
        // 전송에 이용된 금액을 기록
        // 선불정산제인경우 기록
        // 후불정산제인경우 잔고에서 감소시키기만 하고 기록하지 않는다
        // if($user_current['charge_type'] == '0')
        //     $result = $this->money_model->registerUsedMoney($user_id,$usedMoney);

        $response = array(
            'status' => '0',
            'msg' => '성공적으로 전송되었습니다.',
        );
        echo json_encode($response);
    }

    function send_message( $type,$attached, $kind, $dst_mobile, $src_mobile,$content,$title,$notice_id,$request_time) {
        $result = -1;
        $user_msg_type = 0;
        $user_id = get_session_user_id();

        //모바일번호려파 예:) 010-123-1234 -> 0101231234
        $dst_mobile = str_replace("-","", $dst_mobile);
        $dst_mobile = str_replace(" ","", $dst_mobile);

        $src_mobile = str_replace("-","", $src_mobile);
        $src_mobile = str_replace(" ","", $src_mobile);

        if($user_id === 0) {
            $response = array(
                'status' => '-2',
                'msg' => '봉사기에로의 전송오류(user=0)',
            );
            echo json_encode($response);
            return;
        }

        if($request_time < date('Y-m-d H:i:s')) {
            $request_time = date('Y-m-d H:i:s');
        }
        $data = array(
            'notice_id' => $notice_id,
            'user_id'=>$user_id,
            'msg_type' => '',
            'dstaddr' => $dst_mobile,
            'callback' => $src_mobile,
            'stat' => '0',
            'request_time' => $request_time,
            'text'=>'',
        );
        $full_url = get_full_url()."/preview?n=".$notice_id."&m=".$dst_mobile;
        $short_id = "";
        $short_url =  "";

        if($type ==="1" || $attached ==="1" ) {
            $advert_link_list = $this->session->userdata('advert_link');
            $short_id = $this->shorturls_model->insert_data_url($notice_id, $dst_mobile, $advert_link_list);
            $short_url =" ".get_short_url()."/".base62_encode($short_id);
        }

        $content .= $short_url;

        $user_msg_type = 0;
        if($type ==="1"){
            $user_msg_type+=2;
        }
        
        if($kind === "0"){  //  sms
            $data['msg_type']="1";            
            $user_msg_type = 3;
        } else {  //lms            
            $data['msg_type']="3";
            $user_msg_type = 5;
            $data['subject']="한국생산성본부";
        }
        
        $data['user_msg_type'] = $user_msg_type;
        $data['text']=$content;

        $result = $this->msg_queue_model->insert_data($data);

        //료금잔고에서 덜어준다        
        $result = $this->money_model->reduceMoney($user_id,$user_msg_type);
        return $result;
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

    public function set_advert($mobile_count) {
        $mb_id = get_session_user_uid();
        $mb_level = get_session_user_level();
        $advert_link_list = "";

        $condition = array(
            'is_connect'=>1,
            'mb_id'=>$mb_id
        );
        $this->advert_model->init_advert();
        $advert_result = $this->advert_model->get_data($condition);
        foreach ($advert_result as $item) {
            $advert_link_list .=$item['id'].",";
            $mobile_count = $mobile_count+$item['send_count'];
            $data = array(
                'send_count'=>$mobile_count
            );
            $w = array(
                'id'=>$item['id']
            );
            $this->advert_model->update_data($data,$w);
        }
        $condition = array(
            'linked_mb_id'=>$mb_id
        );
        $member_link_list = $this->member_link_model->get_data($condition);

        foreach ($member_link_list as $item) {

            $condition = array(
                'linked_mb_id'=>$item['mb_id']
            );
            $parent_link_list = $this->member_link_model->get_data($condition);
            foreach ($parent_link_list as $item_2) {
                $condition = array(
                    'is_connect'=>1,
                    'mb_id'=>$item_2['mb_id']
                );
                $parent_advert_result = $this->advert_model->get_data($condition);
                foreach ($parent_advert_result as $item_3) {
                    $flag = 0;
                    $link_explode = explode(",",$advert_link_list);
                    for($i = 0; $i < sizeof($link_explode); $i++){
                        if($link_explode[$i]==$item_3['id']) {
                            $flag = 1;
                        }
                    }
                    if($flag ==0) {
                        $advert_link_list .=$item_3['id'].",";
                        $mobile_count = $mobile_count+$item_3['send_count'];
                        $data = array(
                            'send_count'=>$mobile_count
                        );
                        $w = array(
                            'id'=>$item_3['id']
                        );
                        $this->advert_model->update_data($data,$w);
                    }


                }
            }



            $condition = array(
                'is_connect'=>1,
                'mb_id'=>$item['mb_id']
            );
            $advert_result = $this->advert_model->get_data($condition);
            foreach ($advert_result as $item_1) {
                $advert_link_list .=$item_1['id'].",";
                $mobile_count = $mobile_count+$item_1['send_count'];
                $data = array(
                    'send_count'=>$mobile_count
                );
                $w = array(
                    'id'=>$item_1['id']
                );
                $this->advert_model->update_data($data,$w);
            }

        }
        if($advert_link_list !=="") {
            $advert_link_list = substr($advert_link_list,0,strlen($advert_link_list)-1);
        }
        return $advert_link_list;
    }
    function confirm_verify(){
        $verify_code = $this->input->post('verify_code');
        if($verify_code !== "") {
            if ($verify_code === $_SESSION['mobile_verfiy_code']) {
                echo 1;
            } else {
                echo 0;
            }
        }else {
            echo 0;
        }
    }
    function mobile_verify(){
        $mb_no = get_session_user_id();
        if($mb_no > 0) {
            $mobile = $this->input->post('sending_mobile');
            $admin = $this->users_model->get_admin_data();

            $rand_num = sprintf("%04d", rand(0000, 9999));
            $_SESSION['mobile_verfiy_code'] = $rand_num;
            $text = "발신번호등록을 위한 인증 코드입니다.\n 인증코드 : " . $rand_num;
            $data = array(
                'user_id' => $mb_no,
                'msg_type' => '1',
                'dstaddr' => $mobile,
                'callback' => $admin[0]['cf_admin_mobile'],
                'stat' => '0',
                'request_time' => date('Y-m-d H:i:s'),
                'text' => $text,
                'user_msg_type' => "100"
            );
            $result = $this->msg_queue_model->insert_data($data);
            echo 1;
        }else {
            echo 0;
        }

    }
}

/* End of file home.php */
/* Location: ./application/controllers/Home.php */