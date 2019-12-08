<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 12/1/2018
 * Time: 6:45 PM
 */

class Config_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'g5_config';
    }
}