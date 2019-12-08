<?php

/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 8/9/15
 * Time: 4:34 PM
 */

class Question_groups_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'question_groups';
    }
    public function getExampleData($survey_id){


        $result= array();
        $condition = array (
            'survey_id' => $survey_id
        );

        $data = $this->get_data($condition);

        $question_group_num = 0;
        foreach ($data as $question_group) {

            $sql="";
            $sql.="select * from questions where question_group_id=" . $question_group['id'];
            $query = $this->db->query($sql);
            $result_question_group = $query->result_array();

            $question_num = 0;
            $result_temp = [];

            foreach($result_question_group as $question) {
                $sql = "";

                if($question['type'] == 3){ //강사만족도일때

                    //평가지표얻기
                    $sql .= "select * from question_exam_kinds where question_id=" . $question['id'];
                    $query = $this->db->query($sql);
                    $question['exam_kinds'] = $query->result_array();

                    //평가대상얻기
                    $sql = "";
                    $sql .= "select * from question_exam_objects where question_id=" . $question['id'];
                    $query = $this->db->query($sql);
                    $question['exam_objects'] = $query->result_array();
                }else{                      //Not 강사만족도

                    $sql .= "select * from examples where question_id=" . $question['id'];
                    $query = $this->db->query($sql);
                    $question['examples'] = $query->result_array();
                }

                $result_temp[$question_num] = $question;
                $question_num++;
            }

            $question_group['questions'] = $result_temp;

            $result[$question_group_num] = $question_group;

            $question_group_num ++;
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