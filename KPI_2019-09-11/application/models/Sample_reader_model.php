<?php

/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 8/9/15
 * Time: 4:34 PM
 */
class Sample_reader_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'sample_reader';
    }
    //최근 예문꺼내기
    public function getRecentSample(){
        $sql = "select * from sample_sms_info where si_sygb = 'Y'";
        $query = $this->db->query($sql);
        return  $query->result_array();
    }
    //전송에 이용된 금액을 잔고에서 삭감
    public function reduceMoney($user_id,$message_type){
        //현재 회원의 요금상황
        $user_current = $this->getCurrentMoney($user_id);

        //선불충전식인 경우
        if($user_current['charge_type'] == '0') {
            $sql = "update user_money ";
            $sql .= "set current_amount = current_amount - " . $this->getNoticePrice($message_type);
            $sql .= " where user_id = " . $user_id;
            return $this->db->query($sql);
        }else{
        //후불정산제인 경우
            $sql = "update user_money ";
            $sql .= "set current_count = current_count - 1";
            $sql .= " where user_id = " . $user_id;
            return $this->db->query($sql);
        }
    }
    //전송에 이용된 금액을 기록
    public function registerUsedMoney($user_id,$usedMoney){
        $sql = "insert into user_money_history(user_id,date,used_amount)";
        $sql .=" values(".$user_id.",'".date('Y-m-d H:i:s')."',".$usedMoney.")";
        return $this->db->query($sql);
    }
    //통보문종류별가격얻기
    public function getNoticePrice($user_msg_type){
        $sql = "select * from notice_price where id = ".$user_msg_type;
        $query = $this->db->query($sql);
        $notice_price = $query->result_array();
        if(count($notice_price) > 0)
            return $notice_price[0]['price'];
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
}

