<?php

/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 8/9/15
 * Time: 4:34 PM
 */

class D_questions_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'd_questions';
    }
    public function getExampleData($survey_id){


        $result= array();
        $condition = array (
            'flag' => 0,
            'survey_id' => $survey_id
        );

        $data = $this->get_data($condition);
        $example_num = 0;

        foreach ($data as $question) {
            $sql="";
            $sql.="select * from examples where question_id=" . $question['id'];
            $query = $this->db->query($sql);
            $question['examples']=$query->result_array();
            $result[$example_num]=$question;
            $example_num++;
        }

        return $result;

    }
    public function delete_by_id($id)
    {

        $condition = array (
            'flag' => 0,
            'survey_id' => $id
        );
        $sql = "select id from questions where survey_id =".$id;
        $query = $this->db->query($sql);
        $questions =$query->result_array();
        foreach ($questions as $index) {
            $sql = "delete from examples where question_id=".$index['id'];
            $query = $this->db->query($sql);
        }
        $result = $this->delete_data( $condition);
        return $result;

    }
}