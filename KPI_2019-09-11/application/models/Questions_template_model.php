<?php

/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 8/9/15
 * Time: 4:34 PM
 */

class Questions_template_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'questions_template';
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
            $sql.="select * from examples_template where question_id=" . $question['id'];
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
        $sql = "select id from questions_template where survey_id =".$id;
        $query = $this->db->query($sql);
        $questions =$query->result_array();
        foreach ($questions as $index) {
            $sql = "delete from examples_template where question_id=".$index['id'];
            $query = $this->db->query($sql);
        }
        $result = $this->delete_data( $condition);
        return $result;

    }
    public function insert_data_question($question_data){
        $condition = array (
            'flag' => 0,
            'survey_id' => $question_data['survey_id'],
            'number'=>$question_data['number']
        );
        $this->db->select('id');
        $this->db->where($condition);
        $this->db->get($this->table_name);
        $query = $this->db->query($sql);
        $questions =$query->result_array();
    }
}