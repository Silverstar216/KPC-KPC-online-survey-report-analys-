<?php

/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 8/9/15
 * Time: 4:34 PM
 */

class Msg_queue_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'msg_queue';
    }
    public function get_data_reserve($user_id,$start_date,$end_date)
    {
        if ($start_date == $end_date) {

            $condition = array(
                'flag' => 0,
                'user_id' => $user_id,
                'start_time >= ' => date('Y-m-d')
            );
        } else {
            $condition = array(
                'flag' => 0,
                'user_id' => $user_id,
                'start_time >= ' => $start_date,
                'start_time <= ' => $end_date,
            );
        }

        $result = $this->get_data($condition);
        return $result;
    }

    public function get_data_send($user_id, $start_date, $end_date)
    {
        if ($start_date == $end_date) {

            $condition = array(
                'flag' => 0,
                'user_id' => $user_id,
                'start_time <= ' => date('Y-m-d')

            );
        } else {
            $condition = array(
                'flag' => 0,
                'user_id' => $user_id,
                'start_time >= ' => $start_date,
                'start_time <= ' => $end_date,
            );
        }

        $result = $this->get_data($condition);
        return $result;
    }

    public function update_Reserve_Time($userid,$noticeid,$request_time) {


        $data = array(
            'insert_time'=> date('Y-m-d H:i:s'),
            'request_time' => $request_time
        );

        $w =array(
            'notice_id' => $noticeid,
            'user_id'=>$userid

        );
        //$this->db->where('id', $id);
        $this->db->update('msg_queue', $data, $w);
        return $this->db->affected_rows();

    }

    public function delete_data_by_id($user_id,$notice_id) {
        $condition = array (

            'notice_id' => $notice_id,
            'user_id'=>$user_id
        );

        $this->delete_data( $condition);

    }
    /*
     * 1:sms+일반문자,2:sms+일반문자+문서 , 3:sms+설문,4:sms+설문+문서,5:lms+일반문자,6:lms+일반문자+문서 , 7:lms+설문,8:lms+설문+문서,
     */
    public function get_use_history($user_id,$start_date,$end_date) {  //
        $result = array();

        $condition = array (
            'user_id' => $user_id,

            'request_time >= ' => $start_date,
            'request_time <= ' => $end_date
        );


        $this->set_where($condition);
        $this->db->where('user_msg_type = 1');
        $query = $this->db->get($this->table_name);
        $result[] = $query->num_rows();
        $this->set_where($condition);
        $this->db->where('user_msg_type = 2');
        $query = $this->db->get($this->table_name);
        $result[] = $query->num_rows();
        $this->set_where($condition);
        $this->db->where('user_msg_type = 3');
        $query = $this->db->get($this->table_name);
        $result[] = $query->num_rows();
        $this->set_where($condition);
        $this->db->where('user_msg_type = 4');
        $query = $this->db->get($this->table_name);
        $result[] = $query->num_rows();
        $this->set_where($condition);
        $this->db->where('user_msg_type = 5');
        $query = $this->db->get($this->table_name);
        $result[] = $query->num_rows();
        $this->set_where($condition);
        $this->db->where('user_msg_type = 6');
        $query = $this->db->get($this->table_name);
        $result[] = $query->num_rows();
        $this->set_where($condition);
        $this->db->where('user_msg_type = 7');
        $query = $this->db->get($this->table_name);
        $result[] = $query->num_rows();
        $this->set_where($condition);
        $this->db->where('user_msg_type = 8');
        $query = $this->db->get($this->table_name);
        $result[] = $query->num_rows();



        return $result;

    }

}