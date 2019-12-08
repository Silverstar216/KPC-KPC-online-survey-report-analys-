<?php

/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 8/9/15
 * Time: 4:34 PM
 */
class Money_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'user_money';
    }
    //전송에 이용된 금액을 잔고에서 삭감
    public function reduceMoney($user_id,$message_type){
        //현재 회원의 요금상황
        $user_current = $this->getCurrentMoney($user_id);
        $price = 0;
        //회원의 메세지종류에 해당한 가격얻기
        switch($message_type){
            case 1: //sms일반문자
                $price = $user_current['sms_g_simple'];
                break;
            case 2: //sms문서포함문자
                $price = $user_current['sms_g_attach'];
                break;
            case 3: //sms단순설문
                $price = $user_current['sms_sur_simple'];
                break;
            case 4: //sms문서포함설문
                $price = $user_current['sms_sur_attach'];
                break;
            case 5: //lms일반문자
                $price = $user_current['lms_g_simple'];
                break;
            case 6: //lms문서포함문자
                $price = $user_current['lms_g_attach'];
                break;
            case 7: //lms단순설문
                $price = $user_current['lms_sur_simple'];
                break;
            case 8: //lms문서포함설문
                $price = $user_current['lms_sur_attach'];
                break;
        }
        //선불충전식인 경우
        if($user_current['charge_type'] == '0') {
            $sql = "update user_money ";
            $sql .= "set current_amount = current_amount - " . $price;
            $sql .= " where user_id = " . $user_id;
            return $this->db->query($sql);
        }else{
        //후불정산제인 경우
//            $sql = "update user_money ";
//            $sql .= "set current_count = current_count - 1";
//            $sql .= " where user_id = " . $user_id;
//            return $this->db->query($sql);
        }
    }
    //전송에 이용된 금액을 기록
    public function registerUsedMoney($user_id,$usedMoney){
        $sql = "insert into user_money_history(user_id,date,used_amount)";
        $sql .=" values(".$user_id.",'".date('Y-m-d H:i:s')."',".$usedMoney.")";
        return $this->db->query($sql);
    }
    //통보문종류별가격얻기
    public function getNoticePrice($user_id,$user_msg_type){
//        $sql = "select * from notice_price where id = ".$user_msg_type;
//        $query = $this->db->query($sql);
//        $notice_price = $query->result_array();
       $price = 0;
        $sql = "select * from user_money where user_id = ".$user_id;
        $query = $this->db->query($sql);
        $user_money = $query->result_array();
        if(count($user_money) > 0) {

            switch ($user_msg_type) {
                case 1: //sms일반문자
                    $price = $user_money[0]['sms_g_simple'];
                    break;
                case 2: //sms문서포함문자
                    $price = $user_money[0]['sms_g_attach'];
                    break;
                case 3: //sms단순설문
                    $price = $user_money[0]['sms_sur_simple'];
                    break;
                case 4: //sms문서포함설문
                    $price = $user_money[0]['sms_sur_attach'];
                    break;
                case 5: //lms일반문자
                    $price = $user_money[0]['lms_g_simple'];
                    break;
                case 6: //lms문서포함문자
                    $price = $user_money[0]['lms_g_attach'];
                    break;
                case 7: //lms단순설문
                    $price = $user_money[0]['lms_sur_simple'];
                    break;
                case 8: //lms문서포함설문
                    $price = $user_money[0]['lms_sur_attach'];
                    break;
            }
            return $price;
        }
        return null;
    }
    //현재 회원의 잔고를 얻기
    public function getCurrentMoney($user_id){
        $condition = array(
            'user_id' => $user_id,
        );
        $this->my_select();
        $this->set_where($condition);

        $query = $this->db->get($this->table_name);
        $result =$query->result_array();
        if(count($result) > 0)
            return $result[0];
        else
            return null;
    }

//================관리페지에서 요금청구테블내용을 excel로 다운로드받기 위해 query실행함수 ==================
    public function executeQry($qry){
        $query = $this->db->query($qry);
        $result = $query->result_array();
        return $result;
    }
}

