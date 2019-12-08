<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 12/8/2018
 * Time: 6:31 PM
 */

class Member_link_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'g5_member_link';
    }

}