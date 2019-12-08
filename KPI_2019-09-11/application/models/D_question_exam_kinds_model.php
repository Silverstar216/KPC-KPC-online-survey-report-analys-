<?php

/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 8/9/15
 * Time: 4:34 PM
 */

class D_question_exam_kinds_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'd_question_exam_kinds';
    }
}