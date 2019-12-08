<?php

/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 8/9/15
 * Time: 4:34 PM
 */

class Surveys_template_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'surveys_template';
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
    public function get_data_reserve($user_id, $start_date, $end_date)
    {
        $condition = array (
            'flag' => 0,
            'user_id' => $user_id,
            'created_at >= ' => $start_date,
            'created_at <= ' => $end_date,
            'start_time >= ' => date('Y-m-d')
        );

        $result = $this->get_data($condition);
        return $result;
    }

    public function get_data_send($user_id, $start_date, $end_date)
    {
        $condition = array (
            'flag' => 0,
            'user_id' => $user_id,
            'created_at >= ' => $start_date,
            'created_at <= ' => $end_date,
            'start_time < ' => date('Y-m-d')
        );

        $result = $this->get_data($condition);
        return $result;
    }

    public function get_data_use($user_id, $start_date, $end_date)
    {
        $condition = array (
            'flag' => 0,
            'user_id' => $user_id,
            'created_at >= ' => $start_date,
            'created_at <= ' => $end_date,
            'start_time < ' => date('Y-m-d')
        );

        $result = $this->get_data($condition);
        return $result;
    }

    public function delete_by_id($id)
    {

        $condition = array (
            'flag' => 0,
            'id' => $id
        );

        $result = $this->delete_data($condition);
        return $result;
    }
    public function insert_end_comment($survey_id,$real_end_comments){
        $condition = array (
            'survey_id' => $survey_id

        );
        $this->set_where($condition);
        $this->db->delete('survey_end_template');
        $i = 1;
        foreach ($real_end_comments as $comment){
            $data = array(
              'survey_id'=>$survey_id,
              'id'=>$i,
              'content'=>$comment
            );
            $this->set_data($data);
            $this->db->insert('survey_end_template');
            $i++;
        }
        return 1;
    }
    public function get_end_comment($survey_id){
        $condition = array (
            'survey_id' => $survey_id

        );
        $this->db->select('content');

        $this->set_where($condition);
        $this->db->order_by("id asc");


        $query = $this->db->get("survey_end_template");
        return $query->result_array();

    }
    public function get_surveys($user_id,$is_public,$page=0,$per_page=10,$title ="") {
        if($page > 1) {
            $page = ($page-1)*$per_page;
        } else {
            $page = 0;
        }
        $result = null;
        if($is_public ===0) {
            $sql = "select * ";
            $sql .=" from surveys_template where flag =0 and is_public = 0 and user_id =".$user_id." and title like '%".$title."%' order by created_at desc";
            $sql.=" limit ".$page.", ".$per_page;
            $query = $this->db->query($sql);
            $result = $query->result_array();

        } else {
           $sql = "select a.id,a.attached, a.title,a.question_count,(select mb_nick from g5_member where mb_no = a.user_id) as name";
           $sql .=" from surveys_template as a where is_public = 1 and flag =0 order by a.title asc";
            $sql.=" limit ".$page.", ".$per_page;
           $query = $this->db->query($sql);
           $result = $query->result_array();
        }
        return $result;
    }
    public function get_surveys_total_count($user_id,$is_public,$title=""){
        $result = null;
        if($is_public ===0) {
            $sql = "select count(id) as total_count ";
            $sql .=" from surveys_template where flag =0 and is_public = 0 and title like '%".$title."%' and  user_id =".$user_id;
            $query = $this->db->query($sql);
            $result = $query->result_array();

        } else {
            $sql = "select count(id) as total_count ";
            $sql .=" from surveys_template as a where is_public = 1 and flag =0";

            $query = $this->db->query($sql);
            $result = $query->result_array();
        }
        return $result;
    }

    public function get_surveys_end_comment($survey_id){
        $condition = array(
            'survey_id'=>$survey_id
        );
        $this->db->where($condition);
        $this->db->order_by('id');
        $result =$this->db->get('survey_end_template')->result_array();
        return $result;
    }

}