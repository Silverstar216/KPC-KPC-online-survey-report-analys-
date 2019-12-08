<?php

/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 8/9/15
 * Time: 4:34 PM
 */

class Diagnosis_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'diagnosis';
    }

    //단순통보문Id에 의해 설문정보를 얻기
    public function get_data_by_noticeId($id)
    {
        $this->db->select('diagnosis.*,notices.id as noticeId,notices.content,notices.calling_number,notices.mobile_count');
        $this->db->from('notices');
        $this->db->join('diagnosis', 'notices.survey_id = diagnosis.id');

        $condition = array (
            'notices.flag' => 0,
            'notices.id' => $id
        );

        $this->db->where($condition);
        $result = $this->db->get();

        return $result->result_array();

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
    //특정한 기간내의 모든설문들을 얻기
    public function get_data_review($user_id, $start_date, $end_date)
    {
        $sql="";
        if($user_id == "")
            $user_id = -1;
        $where ="where g.user_id=".$user_id." and g.flag=0 and DATE(g.start_time) >= '".$start_date."' and DATE(g.start_time) <= '".$end_date."'";

        $where.=" order by g.start_time desc  ";
        $sql = "select g.id,s.title,s.start_time, s.end_time, g.mobile_count,";
        $sql.="(select count(mobile) from review where notice_id=g.id) as responseCount ";

        $sql.=" from notices as g inner join diagnosis as s on g.survey_id=s.id ";
        $sql.=$where;
        $query = $this->db->query($sql);

        $result =  $query->result_array();
        return $result;
        /*$this->db->select('surveys.*,notices.mobile_count,notices.reply_count, notices.id as noticeId');
        $this->db->from('notices');
        $this->db->join('surveys', 'notices.survey_id = surveys.id');

        $condition = array (
            'notices.flag' => 0,
            'notices.user_id' => $user_id,
            'notices.created_at >= ' => $start_date,
            'notices.created_at <= ' => $end_date
//            'notices.start_time < ' => date('Y-m-d')
        );

        $this->db->where(   $condition);

        $result = $this->db->get();
        return $result->result_array();*/
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
        $this->db->delete('survey_end');
        $i = 1;
        foreach ($real_end_comments as $comment){
            $data = array(
              'survey_id'=>$survey_id,
              'id'=>$i,
              'content'=>$comment
            );
            $this->set_data($data);
            $this->db->insert('survey_end');
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


        $query = $this->db->get("survey_end");
        return $query->result_array();

    }
    /*
     * 설문목록들을 돌려주는 함수
     *view_flag :  1: 전송목록,  2: 작업목록,  3:  공개목록
     * page: 현재페지
     * per_page  :  페지당 개수
     * */
    public function get_diagnosises($data) {
        $page = $data['my_page'];
        $per_page = $data['my_per_page'];
        $user_id = $data['user_id'];
        $begindate = $data['survey_begindate'];
        $enddate = $data['survey_enddate'];

        if($page > 1) {
            $page = ($page-1)*$per_page;
        } else {
            $page = 0;
        }
        $result = null;

        $sql = "SELECT diagnosis.*,  g5_member.mb_name AS user_name FROM diagnosis ";
        $sql .= " LEFT JOIN g5_member ON g5_member.mb_no = diagnosis.user_id ";
        $sql .= " WHERE flag = 0";
        if ($user_id != "all") {
            $sql .= " AND user_id = ".$user_id;
        }
        $sql .= " AND DATE(start_time) >= '".$begindate."' AND DATE(start_time) <= '".$enddate."'";                
        $sql .= " ORDER BY created_at desc LIMIT ".$page.", ".$per_page;

        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;
    }

    /*
     * 설문목록총개수돌려주는 함수
     *view_flag :  1: 전송목록,  2: 작업목록,  3:  공개목록
     *
     * */
    public function get_diagnosises_total_count($data){
        $page = $data['my_page'];
        $per_page = $data['my_per_page'];
        $user_id = $data['user_id'];
        $begindate = $data['survey_begindate'];
        $enddate = $data['survey_enddate'];

        $result = null;
        $sql = "SELECT count(id) AS total_count FROM diagnosis WHERE flag = 0 ";
        if ($user_id != "all") {
            $sql .=" AND user_id =".$user_id;
        }
        $sql .= " AND DATE(start_time) >= '".$begindate."' AND DATE(start_time) <= '".$enddate."'";      
        $query = $this->db->query($sql);
        $result = $query->result_array();       
        return $result;
    }


    public function get_surveys_end_comment($survey_id){
        $condition = array(
            'survey_id'=>$survey_id
        );
        $this->db->where($condition);
        $this->db->order_by('id');
        $result =$this->db->get('survey_end')->result_array();
        return $result;
    }

    /*
    진단용 엑셀관리
    */
    public function set_diagnosis_education($data) {
        $result = null;

        if (strlen($data['customer_name']) == 0)
            return $result;

        $sql = "SELECT id FROM diagnosis_excel WHERE";
        $sql .= " userid = ".$data['userid'];          
        $sql .= " AND customer_name = '".$data['customer_name']."'";
        $sql .= " AND education_name = '".$data['education_name']."'";
        $sql .= " AND education_count = '".$data['education_count']."'";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        if (count($result) == 0) {
            $insertsql = "INSERT INTO diagnosis_excel (userid, customer_name, education_name, education_count, ";
            $insertsql .= "upload_date, upload_excel) VALUES (";
            $insertsql .= "'".$data['userid']."', '".$data['customer_name']."', ";
            $insertsql .= "'".$data['education_name']."', '".$data['education_count']."', ";
            $insertsql .= "'".$data['upload_date']."', '".$data['upload_excel']."')";
            $query = $this->db->query($insertsql);            
        }       
        
        $query = $this->db->query($sql);
        $result = $query->result_array();

        if (count($result) > 0) {
            $diagnosis_excel_id = $result[0]['id'];

            $sql = "SELECT id FROM diagnosis_excel_clients WHERE";
            $sql .= " diagnosis_excel_id = ".$diagnosis_excel_id;          
            $sql .= " AND client_name = '".$data['client_name']."'";
            $sql .= " AND client_phone = '".$data['client_phone']."'";
            $query = $this->db->query($sql);
            $result = $query->result_array();

            if (count($result) == 0) {
                $sql = "INSERT INTO diagnosis_excel_clients (diagnosis_excel_id, client_name, client_rs_code, client_phone, ";
                $sql .= "client_group, client_email) VALUES (";
                $sql .= $diagnosis_excel_id.", '".$data['client_name']."', ";
                $sql .= $data['client_rs_code'].", '".$data['client_phone']."', ";
                $sql .= "'".$data['client_group']."', '".$data['client_email']."')";
                             
                $query = $this->db->query($sql);    
            }
        }
        return $result;
    }

    public function get_diagnosis_education($data) {     
        $page = $data['my_page'] - 1;
        $per_page = $data['my_per_page'];
        $user_id = $data['user_id'];
        $customer = $data['diagnosis_customer'];
        $education = $data['diagnosis_education'];
        $education_count = $data['diagnosis_count'];

        $sql = "SELECT diagnosis_excel.*, count(diagnosis_excel_clients.client_name) AS clients FROM diagnosis_excel ";
        $sql .= " LEFT JOIN diagnosis_excel_clients ON diagnosis_excel.id = diagnosis_excel_clients.diagnosis_excel_id ";        
        $sql .= " WHERE userid = ".$user_id;          
        if ($customer != "") {
            $sql .= " AND customer_name = '".$customer."'";
        }
        if ($education != "") {
            $sql .= " AND education_name = '".$education."'";
        }
        if ($education_count != "") {
            $sql .= " AND education_count = '".$education_count."'";
        }
        $sql .= " GROUP BY diagnosis_excel_clients.diagnosis_excel_id ";
        $sql .= " ORDER BY diagnosis_excel.id ASC LIMIT ".$page.", ".$per_page;
                
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;
    }

    public function get_diagnosis_education_count($data) {
        $user_id = $data['user_id'];
        $customer = $data['diagnosis_customer'];
        $education = $data['diagnosis_education'];
        $education_count = $data['diagnosis_count'];

        $sql = "SELECT diagnosis_excel.* FROM diagnosis_excel ";
        $sql .= " LEFT JOIN diagnosis_excel_clients ON diagnosis_excel.id = diagnosis_excel_clients.diagnosis_excel_id ";        
        $sql .= " WHERE userid = ".$user_id;          
        if ($customer != "") {
            $sql .= " AND customer_name = '".$customer."'";
        }
        if ($education != "") {
            $sql .= " AND education_name = '".$education."'";
        }
        if ($education_count != "") {
            $sql .= " AND education_count = '".$education_count."'";
        }
        $sql .= " GROUP BY diagnosis_excel_clients.diagnosis_excel_id ";
             
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return count($result);
    }

    public function get_diagnosis_education_fromid($diagnosis_excel_id) {     

        $sql = "SELECT * FROM diagnosis_excel ";
        $sql .= " WHERE id = ".$diagnosis_excel_id;          
                
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;
    }

}