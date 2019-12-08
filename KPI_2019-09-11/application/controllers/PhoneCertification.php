<?php
/**
 * Author: CHKD
 * Date: 10/9/2018
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class PhoneCertification extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('my_directory');
        $this->load->model('money_model');
        $this->load->model('senderphone_model');
        $this->load->model('noticeprice_model');
        $config['hostname'] = 'ars.innopost.com';
        $config['username'] = 'hanclaud';
        $config['password'] = 'hanclaud#k2018112810';
        $config['database'] = 'hanclaud';
        $config['dbdriver'] = 'mysqli';
        $config['dbprefix'] = '';
        $config['pconnect'] = FALSE;
        $config['db_debug'] = FALSE;
        $config['cache_on'] = FALSE;
        $config['cachedir'] = '';
        $config['char_set'] = 'utf8';
        $config['dbcollat'] = 'utf8_general_ci';
    }
//  인증요청함수
    public function requestCertification(){
       /* $api_url = 'http://k-survey.or.kr/phone-api.php';
        $post_data = array(
            "name" => "홍길동",
            "birthday" => "1980-08-20"
        );
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $api_url );
        curl_setopt( $ch, CURLOPT_HEADER, 0 );            // No header in the result
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); // Return, do not echo result
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        echo curl_exec($ch);*/


        //login상태검증
        $user_id = get_session_user_id();
        $user_level= get_session_user_level();
        if($user_level ==="") {
            $user_level = "";
            $user_id = -1;
        }
        if($user_level ==="-1" || $user_level ==="") {
            echo -1;
            return;
        }
        //등록신청한 전화번호
        $phoneNumber=isset($_POST['phone']) ? $_POST['phone']:'';

    //인증요청한 전화번호를 인증요청번호테블에 보관
        $condition = array(
            'user_id' => $user_id,
            'phone' => $phoneNumber,
        );
        $searchResult = $this->senderphone_model->get_data($condition);
        $now_date = date('Y-m-d H:i:s');
        $sender_phone_id = 0;
        if(count($searchResult) > 0) {
            echo "duplicated";return;
        }else {
            //ars_call_id를 생성
            $data = array(
                'user_id' => $user_id,
                'phone' => $phoneNumber,
                'status' => 0,
                'request_date'=>$now_date,
            );
            $sender_phone_id = $this->senderphone_model->insert_data($data);
        }
        //4자리인증번호를 생성 예:) 5201
        $rand_num = sprintf("%04d",rand(0000,9999));
        //발신번호테블에 전화번호가 성공적으로 추가되였으면
        if($sender_phone_id > 0){
            //ars인증요청테블에 삽입할 레코드
            $connect_db = @mysqli_connect('ars.innopost.com', 'hanclaud', 'hanclaud#k2018112810', 'hanclaud');
            if($connect_db == true) {
                $select_db = @mysqli_select_db($connect_db, 'hanclaud');
                $register_time = str_replace('-','',$now_date);
                $register_time = str_replace(':','',$register_time);
                $register_time = str_replace(' ','',$register_time);
                $sql = "INSERT INTO ars_info_tbl (call_id, cus_phone, callback_num, register_time, original_key_data, service_id, target_ip, target_port, ment_set_no)";
                $sql .= " VALUES('" . $sender_phone_id . "', '" . $phoneNumber . "', '025852359','" .$register_time. "', '" . $rand_num . "', '003', '203.240.232.213', 9500, 1) ";
                if (@mysqli_query($connect_db, $sql) == true) {
                    echo $rand_num . '&' . $sender_phone_id;
                } else {
                    echo "ars_insert_fail";
                }
                @mysqli_close($connect_db);
            }else {
                echo "connectFail";
                //자료지기접속성공을 가상
//                echo $rand_num . '&' . $sender_phone_id;
            }
        }
    }
//  인증요청결과읽기
    public function getCertificationResult(){
        $user_id = get_session_user_id();
        $user_level= get_session_user_level();
        if($user_level ==="") {
            $user_level = "";
            $user_id = -1;
        }
        if($user_level ==="-1" || $user_level ==="") {
            echo -1;
            return;
        }
        //등록신청한 요청레코드식별자
        $ars_call_id=isset($_POST['ars_call_id']) ? $_POST['ars_call_id']:'';
        //요청id에 해당한 등록날자를 얻어서 <결과테블이름>을 얻기
        $condition = array(
            'user_id' => $user_id,
            'id' => $ars_call_id,
        );
        $searchResult = $this->senderphone_model->get_data($condition);
        //요청테블에 요청한 발신번호에 해당한 레코드가 존재하면
        if(count($searchResult) > 0) {
            $lst_request_date = explode('-',$searchResult[0]['request_date']);
            //발신요청날자가 존재하는가?(례외처리)
            if(count($lst_request_date) > 2) {
                $connect_db = @mysqli_connect('ars.innopost.com', 'hanclaud', 'hanclaud#k2018112810', 'hanclaud');
                //자료기지접속 성공인가?
                if($connect_db == true) {
                    $select_db = mysqli_select_db($connect_db, 'hanclaud');

                    //먼저 <log테블>부터 조회하기
                    $qry = "select * from LOG_" . $lst_request_date[0] . $lst_request_date[1];
                    $qry .= " where ";
                    $qry .= " call_id = '" . $ars_call_id."'";
                    $log_result = @mysqli_query($connect_db, $qry) or die("<p>$qry<p>" . mysqli_errno() . " : " . mysqli_error() . "<p>");

                    //log테블에 자료가 들어왔다면
                    if (mysqli_num_rows($log_result) > 0) {
                        $row = @mysqli_fetch_array($log_result);
                        //인증에 성공
                        if ($row['call_result_code'] == '00') {
                            //해당발신번호테블에 인증성공결과를 반영하기
                            $now_date = date('Y-m-d H:i:s');
                            $data = array(
                                'status' => 1,
                                'verify_date' => $now_date,
                            );

                            $this->senderphone_model->update_data($data, $condition);
                            echo "success";
                        } else
                            echo "발신번호인증이 실패했습니다!";
                    }else{//log테블에 아직 자료가 들어오지 않았다면
                        //<요청테블>에 발신번호에 해당한 레코드가 존재하는가를 조사
                        $qry = "select * from ars_info_tbl";
                        $qry .= " where ";
                        $qry .= " call_id = '" . $ars_call_id."'";
                        $request_result = @mysqli_query($connect_db, $qry) or die("<p>$qry<p>" . mysqli_errno() . " : " . mysqli_error() . "<p>");

                        $row = @mysqli_fetch_array($request_result);
                        //인증요청에 성공이면 결과테블을 보기
                        if (mysqli_num_rows($request_result) > 0) {
                            if ($row['request_result_code'] == '000' || $row['request_result_code'] == null)
                                echo "wait";
                            else
                                echo "인증요청실패[".$row['request_result_code'].']';
                        }else
                            echo "요청결과테블에 해당 레코드가 없습니다";
                    }
              //자료기지접속실패이면
                }else {
                    echo "ARS자료기지접속실패!";
                    //자료기지접속성공을 가상
//                    //해당발신번호테블에 인증성공결과를 반영하기
//                    $now_date = date('Y-m-d H:i:s');
//                    $data = array(
//                        'status' => 1,
//                        'verify_date' => $now_date,
//                    );
//                    $condition = array(
//                        'id' => $ars_call_id,
//                        'user_id' => $user_id
//                    );
//
//                    $this->senderphone_model->update_data($data, $condition);
//                    echo "success";
                }
                @mysqli_close($connect_db);
            }
            else
                echo "요청날자오류!";
        }else {
            echo "등록신청테이블에 해당 레코드가 없습니다";
        }
        @mysqli_close($connect_db);
    }

//  발신번호보관
   /* public function saveSenderPhone(){
        $user_id = get_session_user_id();
        $user_level= get_session_user_level();
        if($user_level ==="") {
            $user_level = "";
            $user_id = -1;
        }
        if($user_level ==="-1" || $user_level ==="") {
            echo -1;
            return;
        }
        $phone = $this->input->get_post('phone');
        $memo = $this->input->get_post('memo');
        $auKind = $this->input->get_post('auKind');
        $condition = array(
            'user_id' => $user_id,
            'phone' => $phone,
        );
        $searchResult = $this->senderphone_model->get_data($condition);
        if($auKind == 0) {//추가이면
            if(count($searchResult) > 0) {
                echo "duplicated";
            }else {
                $data = array(
                    'user_id' => $user_id,
                    'phone' => $phone,
                    'memo' => $memo,
                    'status' => 0,
                );
                $this->senderphone_model->insert_data($data);
                echo "inserted";
            }
        }else{  //Update이면
            $update_data = array(
                'memo' => $memo,
            );
            $this->senderphone_model->update_data($update_data, $condition);
            echo "updated";
        }
    }*/

    public function saveSenderPhone(){
        $user_id = get_session_user_id();
        $user_level= get_session_user_level();
        if($user_level ==="") {
            $user_level = "";
            $user_id = -1;
        }
        if($user_level ==="-1" || $user_level ==="") {
            echo -1;
            return;
        }
        $totalPhoneArray = $this->input->get_post('totalPhoneArray');
        $sending_mobile = $this->input->get_post('sending_mobile');
        foreach ($totalPhoneArray as $item) {
            $condition = array(
                'user_id' => $user_id,
                'phone' => $item['phone'],
            );
            $searchResult = $this->senderphone_model->get_data($condition);
            if(count($searchResult) < 1) {
                $data = array(
                    'user_id' => $user_id,
                    'phone' => $item['phone'],
                    'memo' => $item['comment'],
                    'request_phone' => $sending_mobile,
                    'request_date'=>date('Y-m-d H:i:s')
                );
                $this->senderphone_model->insert_data($data);
            }
        }
        echo 1;
        $_SESSION['mobile_verfiy_code']="";
    }
//  발신번호삭제
    public function removeSenderPhone(){
        $user_id = get_session_user_id();
        $id = $this->input->get_post('sender_phone_id');
        $condition = array(
            'id' => $id ,
        );
        $result = $this->senderphone_model->delete_data($condition);
        echo "deleted";
    }
// 발신번호의 사용이름( Memo)변경
    public function saveSenderPhoneMemo(){
        $id = $this->input->get_post('sender_phone_id');
        $memo = $this->input->get_post('memo');
        $condition = array (
            'id' => $id
        );
        $update_data = array(
            'memo' => $memo,
        );
        $this->senderphone_model->update_data($update_data, $condition);
    }

}