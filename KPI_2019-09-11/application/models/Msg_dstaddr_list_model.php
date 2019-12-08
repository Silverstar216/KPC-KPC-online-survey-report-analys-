<?php

/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 8/9/15
 * Time: 4:34 PM
 */

class Msg_dstaddr_list_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'msg_dstaddr_list';
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

    public function update_Reserve_Time($userid,$noticeid,$startTime) {


        $data = array(

            'start_time'=> $startTime,
            'updated_at' => date('Y-m-d')
        );

        $w =array(
            'id' => $noticeid,
            'user_id'=>$userid

        );
        //$this->db->where('id', $id);
        $this->db->update('notices', $data, $w);
        return $this->db->affected_rows();

    }
    public function delete_data_by_id($id)
    {
        $data = array (
            'flag' => 1,
        );

        $condition = array (
            'flag' => 0,
            'id' => $id
        );

        $result = $this->update_data($data, $condition);
        return $result;
    }
    public function get_data_by_id($id)
    {
        $condition = array (
            'flag' => 0,
            'id' => $id
        );

        $result = $this->get_data($condition);
        return $result;
    }
    public function get_survey_id($id)
    {
        $sql="";
        $sql.=" select survey_id";
        $sql.=" from notices";
        $sql.=" where id=".$id." and flag=0";
        $query = $this->db->query($sql);
        $row = $query->row();

        return $row->survey_id;
    }
}