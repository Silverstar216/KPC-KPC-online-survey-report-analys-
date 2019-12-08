<?php

/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 8/9/15
 * Time: 4:34 PM
 */
class Noticeprice_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'notice_price';
    }
    public function get_notice_price(){

        $sql = " select * from notice_price";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}