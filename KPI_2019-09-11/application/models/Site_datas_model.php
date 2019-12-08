<?php

/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 8/9/15
 * Time: 4:34 PM
 */

class Site_datas_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'site_datas';
    }

    public function get_data_by_key($key)
    {
        $condition = array (
            'flag' => 0,
            'key' => $key
        );

        $result = $this->get_data($condition);
        return $result;
    }
}