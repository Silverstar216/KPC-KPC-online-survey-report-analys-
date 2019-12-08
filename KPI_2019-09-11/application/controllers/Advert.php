<?php
/**
 * Author: KMC
 * Date: 10/7/18
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Advert extends MY_Controller
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
            'include/js/advert/add.js',
            'include/js/advert/list.js',
            'include/js/pagination.js',
            'include/plugins/jscolor.js',
        );

        $this->load->model('users_model');
        $this->load->model('advert_model');
        $this->load->model('member_link_model');
        $this->load->model('examples_model');
        $this->load->model('msg_queue_model');

        $this->load->helper('my_directory');
        $this->load->helper('my_url');


    }

    public function index()
    {
        if (is_signed()) {
            /* $survey_id = $this->input->get_post('survey_id');*/

            $user_level= get_session_user_level();
            if($user_level < 5) {
            return false;

            }
            $title = "";
            $link_url = "";
            $background="be3a94;";
            $start_date = $this->input->get_post('start_date');
            $end_date = $this->input->get_post('end_date');
            if (empty($start_date))
            {

                $start_date = date('Y-m-d');
            }
            if (empty($end_date))
            {

                $end_date=date('Y-m-d');
            }
            $advert_id = $this->input->get_post('advert_id');


            if(empty($advert_id))
                $this->data['subtitle'] = '홍보 추가';
            else {
                $this->data['subtitle'] = '홍보 수정';
                $w = array(
                    'id'=>$advert_id
                );
                $advert_item = $this->advert_model->get_data($w);
                $start_date = substr($advert_item[0]['start_date'],0,10);
                $end_date= substr($advert_item[0]['end_date'],0,10);
                $title = $advert_item[0]['advert_title'];
                $link_url = $advert_item[0]['link_url'];
                $background = $advert_item[0]['background'];
            }
            $this->data['advert_id'] = $advert_id;


            $this->data['start_date'] = $start_date;
            $this->data['end_date'] = $end_date;
            $this->data['advert_title'] = $title;
            $this->data['link_url'] = $link_url;
            $this->data['background'] = $background;
            $this->data['menu'] = '홍보관리';
            $this->data['submenu'] = '홍보작성'; 

            $this->load->view('templates/header', $this->data);
            $this->load->view('templates/nav', $this->data);
            $this->load->view('advert/add_advert', $this->data);
            $this->load->view('templates/nav-footer', $this->data);
            $this->load->view('templates/scripts', $this->data);
            $this->load->view('templates/footer', $this->data);
        }
        else {
            ?>
            <script type="text/javascript">                
                alert("로그인상태에서만 이용하실수 있습니다.");
                base_url = "http://" + location.host + "/index";
                window.location = base_url;
            </script>
            <?php       
        }        
    }
    public function view()
    {
        if (is_signed()) {
            $mb_id = get_session_user_uid();

            $link_text = "";
            $link_count = "";
            $this->data['stval']="";
            $w = array(
                'mb_id'=>$mb_id
            );
            $result = $this->users_model->get_data($w);
            if(sizeof($result) > 0) {
                $link_text = $result[0]['mb_link_list'];
                $link_array = array();
                if (empty($link_text)) {
                    $link_count = "";
                } else {
                   $link_array = explode(",", $link_text);
                    $link_count=sizeof($link_array);
                }
            }
            $this->data['link_count'] = $link_count;
            $this->data['link_text'] = $link_text;
            $this->data['advert_total_count']= 0;
            $this->data['menu'] = '홍보관리';
            $this->data['submenu'] = '홍보목록'; 

            $this->load->view('templates/header', $this->data);
            $this->load->view('templates/nav', $this->data);
            $this->load->view('advert/list_advert', $this->data);
            $this->load->view('templates/nav-footer', $this->data);
            $this->load->view('templates/scripts', $this->data);
            $this->load->view('templates/footer', $this->data);
        }
        else {
            ?>
            <script type="text/javascript">                
                alert("로그인상태에서만 이용하실수 있습니다.");
                base_url = "http://" + location.host + "/index";
                window.location = base_url;
            </script>
            <?php  
        }        
    }

    public function getAdvertList()
    {
        $mb_id = get_session_user_uid();
        if($mb_id ==="" || empty($mb_id)) {
            $mb_id = -1;
        }
        $stval = isset($_POST['stval']) ? $_POST['stval']:'';

        $my_page =isset($_POST['page']) ? $_POST['page']:0;

        $my_per_page =isset($_POST['page_per_count']) ? $_POST['page_per_count']:10;

        $this->data['my_page']=$my_page;
        $this->data['my_per_page']=$my_per_page;
        $this->data['stval']=$stval;
        $my_items = $this->advert_model->get_advert_list($mb_id,$my_page,$my_per_page,$stval);
        $this->data['my_item_total'] =  $this->advert_model->get_advert_total_count($mb_id,$stval)[0]['total_count'];


        $this->data['advert_list']= $my_items;

         $this->load->view('advert/get_list_advert', $this->data);


    }

    public function uselog()
    {

        $mb_id = get_session_user_uid();
        $user_level= get_session_user_level();
        if($user_level<5) {
           return false;
        }
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

        $this->data['start_date'] = $start_date;
        $this->data['end_date'] = $end_date;

        $w = array(
            'mb_id'=>$mb_id
        );
        $result = $this->users_model->get_data($w);
        if(sizeof($result) > 0) {
            $link_text = $result[0]['mb_link_list'];
            $link_array = array();
            if (empty($link_text)) {
                $this->data['link_count'] = "";
            } else {
                $link_array = explode(",", $link_text);
                $this->data['link_count'] = sizeof($link_array);
            }
            $this->data['link_text'] = $link_text;
            $this->data['total_count'] = 0;
            $this->load->view('templates/header', $this->data);
            $this->load->view('templates/nav', $this->data);
            $this->load->view('advert/uselog_advert', $this->data);
            $this->load->view('templates/nav-footer', $this->data);
            $this->load->view('templates/scripts', $this->data);
            $this->load->view('templates/footer', $this->data);
        }
    }

    public function useloglist()
    {
        $mb_id = get_session_user_uid();
        if($mb_id ==="" || empty($mb_id)) {
            $mb_id = -1;
        }
        $start_date =$_GET['st'];
        $end_date = $_GET['et'];
        $page =isset($_GET['page']) ? $_GET['page']:0;
        $count =isset($_GET['count']) ? $_GET['count']:10;
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

        $my_items = $this->advert_model->get_advert_uselog_list($mb_id,$start_date,$end_date,$page,$count);
        $this->data['my_item_total'] =  $this->advert_model->get_advert_uselog_total_count($mb_id,$start_date,$end_date);[0]['total_count'];


        $this->data['advert_list']= $my_items;

        $this->load->view('advert/uselog_advert_list', $this->data);


    }

    public function send(){
        $link_url = $this->input->get_post('url');
        $mobile = $this->input->get_post('mobile');

        $admin = $this->users_model->get_admin_data();
        $mb_no = get_session_user_id();
        $text = "홍보배너 미리보기입니다.\n 연결주소 : ".$link_url;
        $data = array(
            'user_id'=>$mb_no,
            'msg_type' => '1',
            'dstaddr' => $mobile,
            'callback' => $admin[0]['cf_admin_mobile'],
            'stat' => '0',
            'request_time' => date('Y-m-d H:i:s'),
            'text'=>$text,
            'user_msg_type'=>"101"
        );

        $result = $this->msg_queue_model->insert_data($data);
        if($result > -1) {
            echo "ok";
        } else {
            echo "error";
        }
    }

    public function save(){
        $user_id = get_session_user_uid();
        $user_level = get_session_user_level();
        $flag = 0;
        if(!empty($user_id)) {
            $link_url = $this->input->get_post('url');
            $title = $this->input->get_post('advert_title');
            $advert_id = $this->input->get_post('advert_id');
            $start_date = $this->input->get_post('start_date');
            $end_date = $this->input->get_post('end_date');
            $background = $this->input->get_post('background');

            //보존하기전에 먼저 기간이 지난 배너는 연결을 취소한다.
            $this->advert_model->init_connect_advert($user_id);

            $data = array(
                'link_url' => $link_url,
                'end_date' => $end_date,
                'start_date' => $start_date,
                'mb_id'=>$user_id,
                'advert_title'=>$title,
                'flag'=>0,
                'connect_count'=>0,
                'send_count'=>0,
                'background'=>$background,
                'created_at'=>date('Y-m-d H:i:s')
            );
            $data_1 = array(
                'is_connect'=>0
            );
            $data_2 = array(
                'is_connect'=>1
            );
            // 연결된 사용자가 있는가 검사한다.
            $w = array(
                'mb_id'=>$user_id
            );
            $result = $this->member_link_model->get_data($w);

            if($user_level > 6) {  // 7등급이상이며 이미등록된 배너의 is_connect를 0으로 한다.

                $this->advert_model->update_data($data_1, $w);
            }
            if(sizeof($result) > 0) {   //  있다면 연결기발을 1로 한다.
                $data = array_merge($data, $data_2);
                if (empty($advert_id)) {  //  새로등록이면

                    $advert_id= $this->advert_model->insert_data($data);
                } else {  // 수정이면

                    $w = array(
                        'id'=>$advert_id
                    );
                    $this->advert_model->update_data($data,$w);


                }
                if($user_level > 6) {


                    // 자기와 연결된 5등급의 배너가 2개이상이면 오래된 배너를 하나 련결을 취소한다.
                    if(sizeof($result > 0)) {
                        foreach ($result as $item) {
                            $link_mb_id = $item['linked_mb_id'];

                            $link_result = $this->advert_model->get_sendable_advert($link_mb_id);
                            for($i = 2; $i < sizeof($link_result); $i++){
                                $link_condition= array(
                                    'mb_id'=>$link_result[$i]['mb_id'],
                                    'id'=>$link_result[$i]['id'],
                                );
                                $this->advert_model->update_data($data_1,$link_condition);
                            }
                        }
                    }


                } else {  // 5등급이면 자기를 련결한 7등급이 있고 7등급의 배너가 현재기간사이에 있으면 배너를 2개 없으면 3개로한다.
                    if(sizeof($result) > 0) {  //  연결이 있다면 연결기발을 1로 한다.
                        $parent_condition = array(
                            'linked_mb_id'=>$user_id
                        );
                        $parent_result = $this->member_link_model->get_data($parent_condition);
                        if(sizeof($parent_result)>0) {  //  자기를 련결한 부모가 있으면 배너수를 2개
                            $parent_link_result = $this->advert_model->get_sendable_advert($parent_result[0]['mb_id']);
                            // 자기를 련결한 부모의 배너가 있으면
                            if(sizeof($parent_link_result) > 0) {


                                $link_result = $this->advert_model->get_sendable_advert($user_id);
                                // 배너가 3개면 2개를 취소한다.
                                for($i = 2; $i < sizeof($link_result); $i++){
                                    $link_condition= array(
                                        'mb_id'=>$link_result[$i]['mb_id'],
                                        'id'=>$link_result[$i]['id'],
                                    );
                                    $this->advert_model->update_data($data_1,$link_condition);
                                }


                            } else {   //  없으면 배너수를 3개로 유지한다.


                                $link_result = $this->advert_model->get_sendable_advert($user_id);
                                for($i = 3; $i < sizeof($link_result); $i++){
                                    $link_condition= array(
                                        'mb_id'=>$link_result[$i]['mb_id'],
                                        'id'=>$link_result[$i]['id'],
                                    );
                                    $this->advert_model->update_data($data_1,$link_condition);
                                }


                            }
                        } else {//  자기를 련결한 부모가 없으면 배너수를 3개

                            $link_result = $this->advert_model->get_sendable_advert($user_id);
                            for($i = 3; $i < sizeof($link_result); $i++){
                                $link_condition= array(
                                    'mb_id'=>$link_result[$i]['mb_id']
                                );
                                $this->advert_model->update_data($data_1,$link_condition);
                            }

                        }

                    }
                }
            } else {   //  없다면 연결기발을 0로 한다.
                $data = array_merge($data, $data_1);
                $this->advert_model->update_data($data_1,$w);  // 연결이 없다면 연결기발을 모두 0으로 만든다.
                if (empty($advert_id)) {  //  새로등록이면

                    $advert_id= $this->advert_model->insert_data($data);
                } else {  // 수정이면

                    $w = array(
                        'id'=>$advert_id
                    );
                    $this->advert_model->update_data($data,$w);


                }
            }



            echo $advert_id;
            return;
        }
        echo "error";
        return;
    }

    public function connect_save() {
        $id = $this->input->get_post('id');
        $w = array(
            'id'=>$id
        );
        $result = $this->advert_model->get_data($w);
        if(sizeof($result) > 0) {
            $connect_count = $result[0]['connect_count'];
            $connect_count = $connect_count+1;
            $data = array(
                'connect_count'=>$connect_count
            );
            $this->advert_model->update_data($data,$w);
        }
        echo "ok";
    }

    public function delete_advert(){
        $user_id = get_session_user_uid();

        if(empty($user_id) || $user_id==="") {
            $user_id = -1;
            echo -1;

        } else {
            $advert_ids = $this->input->get_post('selected');
            foreach ($advert_ids as $advert_id) {
                $condition = array(
                    'id' => $advert_id,
                    'mb_id' => $user_id
                );
                $this->advert_model->delete_data($condition);
            }
            $this->auto_set_connect();
            echo 1;
        }


    }

    public function auto_set_connect() {
        $user_id = get_session_user_uid();
        $user_level = get_session_user_level();

            $data_1 = array(
                'is_connect'=>0
            );
            $data_2 = array(
                'is_connect'=>1
            );
            // 연결된 사용자가 있는가 검사한다.
            $w = array(
                'mb_id'=>$user_id
            );
            $result = $this->member_link_model->get_data($w);
            // 등급의 상관없이 모든 connect = 0으로 설정한다.
            $this->advert_model->update_data($data_1, $w);
            if($user_level > 6) {  // 7등급이상이면
                if(sizeof($result) > 0) {   //  연결된 사용자가있다면
                   $advert_result = $this->advert_model->get_connectable_advert($user_id); //연결설정을 할 목록을 꺼낸다.
                    if(sizeof($advert_result) > 0) { // 배너광고가 있으면 1개만 connect=1로 설정하고 자식들이 자식을 가지고있으면
                        $link_condition= array(
                            'mb_id'=>$advert_result[0]['mb_id'],
                            'id'=>$advert_result[0]['id'],
                        );
                        $this->advert_model->update_data($data_2,$link_condition);

                        foreach ($result as $item) {  //  자식들이 자식을 가지고있는가 검사한다.
                            $child_condition = array(
                                'mb_id'=>$item['linked_mb_id']
                            );
                            $child_result = $this->member_link_model->get_data($child_condition);
                            //  자식들의 배너를 0 으로 설정한다음 다시 설정한다.
                            $this->advert_model->update_data($data_1, $child_condition);
                            if(sizeof($child_result) > 0) { // 있다면 배너가 있는가 본다.
                                $advert_child_result = $this->advert_model->get_connectable_advert($item['linked_mb_id']);
                                if(sizeof($advert_child_result) > 0) { // 있다면
                                    for($i = 0; $i < sizeof($advert_child_result); $i++){
                                        $link_condition= array(
                                            'mb_id'=>$advert_child_result[$i]['mb_id'],
                                            'id'=>$advert_child_result[$i]['id'],
                                        );
                                        $this->advert_model->update_data($data_2,$link_condition);
                                        if($i ==1) {
                                            break;
                                        }
                                    }
                                }
                            }

                        }
                    } else {   // 배너광고가 없으면 자식들을 3개로 설정하기위한 작업
                        foreach ($result as $item) {  //  자식들이 자식을 가지고있는가 검사한다.
                            $child_condition = array(
                                'mb_id'=>$item['linked_mb_id']
                            );
                            $child_result = $this->member_link_model->get_data($child_condition);
                            //  자식들의 배너를 0 으로 설정한다음 다시 설정한다.
                            $this->advert_model->update_data($data_1, $child_condition);
                            if(sizeof($child_result) > 0) { // 있다면 배너가 있는가 본다.
                                $advert_child_result = $this->advert_model->get_connectable_advert($item['linked_mb_id']);
                                if(sizeof($advert_child_result) > 0) { // 있다면
                                    for($i = 0; $i < sizeof($advert_child_result); $i++){
                                        $link_condition= array(
                                            'mb_id'=>$advert_child_result[$i]['mb_id'],
                                            'id'=>$advert_child_result[$i]['id'],
                                        );
                                        $this->advert_model->update_data($data_2,$link_condition);
                                        if($i ==2) {
                                            break;
                                        }
                                    }
                                }
                            }

                        }
                    }
                }
            } else {   // 5등급이라면면
                if(sizeof($result) > 0) {  //  연결이 있다면 연결기발을 1로 한다.
                    $parent_condition = array(
                        'linked_mb_id'=>$user_id
                    );
                    $parent_result = $this->member_link_model->get_data($parent_condition);
                    if(sizeof($parent_result)>0) {  //  자기를 련결한 부모가 있으면 배너수를 2개
                        $parent_link_result = $this->advert_model->get_connectable_advert($parent_result[0]['mb_id']);
                        // 자기를 련결한 부모의 배너가 있으면
                        if(sizeof($parent_link_result) > 0) {


                            $link_result = $this->advert_model->get_connectable_advert($user_id);
                            // 배너가 3개면 2개를 취소한다.
                            for($i = 0; $i < sizeof($link_result); $i++){
                                $link_condition= array(
                                    'mb_id'=>$link_result[$i]['mb_id'],
                                    'id'=>$link_result[$i]['id'],
                                );
                                $this->advert_model->update_data($data_2,$link_condition);
                                if($i==1){
                                    break;
                                }
                            }


                        } else {   //  없으면 배너수를 3개로 유지한다.
                            $link_result = $this->advert_model->get_connectable_advert($user_id);
                            for($i = 0; $i < sizeof($link_result); $i++){
                                $link_condition= array(
                                    'mb_id'=>$link_result[$i]['mb_id']
                                );
                                $this->advert_model->update_data($data_2,$link_condition);
                                if($i==2){
                                    break;
                                }
                            }

                        }
                    } else {//  자기를 련결한 부모가 없으면 배너수를 3개

                        $link_result = $this->advert_model->get_connectable_advert($user_id);
                        for($i = 0; $i < sizeof($link_result); $i++){
                            $link_condition= array(
                                'mb_id'=>$link_result[$i]['mb_id']
                            );
                            $this->advert_model->update_data($data_2,$link_condition);
                            if($i==2){
                                break;
                            }
                        }

                    }

                }
            }

    }
}

/* End of file home.php */
/* Location: ./application/controllers/Home.php */