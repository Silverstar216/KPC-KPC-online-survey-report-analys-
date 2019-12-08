<?php

/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 8/9/15
 * Time: 4:34 PM
 */

class Messages_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'messages';
    }


    public function get_data_send_detail($notice_id, $mobile)
    {
        $condition = array (
            'flag' => 0,
            'type' => 0,
            'object_id' => $notice_id,
        );

        $this->my_select();

        $this->set_where($condition);
        $this->db->like('mobile', $mobile, 'both');

        $query = $this->db->get($this->table_name);
        return $query->result_array();
    }

    public function get_count($start_date, $end_date, $kind, $type, $attached)
    {
        $result = array();

        $condition = array (
            'flag' => 0,
            'kind' => $kind,
            'type' => $type,
            'attached' => $attached,
            'sending_at >= ' => $start_date,
            'sending_at <= ' => $end_date
        );

        $this->set_where($condition);
        $query = $this->db->get($this->table_name);
        $result[] = $query->num_rows();

        $this->set_where($condition);
        $this->db->where('status = 1');
        $query = $this->db->get($this->table_name);
        $result[] = $query->num_rows();

        $this->set_where($condition);
        $this->db->where('status = 2');
        $query = $this->db->get($this->table_name);
        $result[] = $query->num_rows();

        $this->set_where($condition);
        $this->db->where('status = 0');
        $query = $this->db->get($this->table_name);
        $result[] = $query->num_rows();

        return $result;
    }
}