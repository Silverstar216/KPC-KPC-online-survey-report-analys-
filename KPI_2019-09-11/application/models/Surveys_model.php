<?php

/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 8/9/15
 * Time: 4:34 PM
 */

class Surveys_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'surveys';
    }
    
    //단순통보문Id에 의해 설문정보를 얻기
    public function get_data_by_noticeId($id)
    {
        $this->db->select('surveys.*,notices.id as noticeId,notices.content,notices.calling_number,notices.mobile_count');
        $this->db->from('notices');
        $this->db->join('surveys', 'notices.survey_id = surveys.id');

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

        $sql.=" from notices as g inner join surveys as s on g.survey_id=s.id ";
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
     * view_flag :  1: 전송목록,  2: 작성중목록
     * survey_flag :  0: 맞춤형교육목록,  1: 공개교육목록
     * page: 현재페지
     * per_page  :  페지당 개수
     * */
    public function get_surveys($user_id, $searchdata) {
        $page = $searchdata['my_page'];
        $per_page = $searchdata['my_per_page'];
        $view_flag = $searchdata['view_flag'];
        $survey_flag = $searchdata['survey_flag'];
        $admin = $searchdata['survey_admin'];
        $title = $searchdata['survey_name'];
        $begintime = $searchdata['survey_begindate'];
        $endtime = $searchdata['survey_enddate'];
        
        if($page > 1) {
            $page = ($page-1)*$per_page;
        } else {
            $page = 0;
        }
        $result = null;

        $sql = "select mb_name as name, id, title, attached, user_id, flag, question_count, show_user_id, is_send, is_public, survey_flag, education_id, date(start_time) as s_start_time, date(end_time) as s_end_time, ";         
        $sql .= "education_course as edu_name, education_customer as customer, education_count as edu_count, education_id";
        if($view_flag ==="1") {         // 완성된 설문
            $sql .= " from surveys ".
                    " left join g5_member ON surveys.user_id=g5_member.mb_no".
                    " where flag = 0 and is_send = 1 and (show_user_id = ".$user_id.
                    " or show_user_id = 0)".
                    " and survey_flag = ".$survey_flag;
        } else if ($view_flag ==="2") { // 설문현황목록
            $sql .= " from surveys ".
                    " left join g5_member ON surveys.user_id=g5_member.mb_no".
                    " where flag = 0 and (show_user_id = ".$user_id.
                    " or show_user_id = 0)".
                    " and education_course != '' and survey_flag = ".$survey_flag;
        } else {                        // 전체 설문
            $sql .= " from surveys ".
                    " left join g5_member ON surveys.user_id=g5_member.mb_no".
                    " where flag = 0 and is_send <> 1 and (show_user_id = ".$user_id.
                    " or show_user_id = 0)".
                    " and survey_flag = ".$survey_flag;
        }
        $sql .= " and created_at >= '".$begintime."' and created_at <= '".$endtime."'";
        $sql .= " and title like '%".$title."%' and mb_name like '%".$admin."%' ";
        $sql .= " order by created_at desc";
        $sql .= " limit ".$page.", ".$per_page;
        

        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;
    }
    /*
     * 설문목록총개수돌려주는 함수
     * view_flag :  1: 전송목록,  2: 작성중목록
     * survey_flag :  0: 맞춤형교육목록,  1: 공개교육목록
     * */
    public function get_surveys_total_count($user_id, $searchdata){
        $page = $searchdata['my_page'];
        $per_page = $searchdata['my_per_page'];
        $view_flag = $searchdata['view_flag'];
        $survey_flag = $searchdata['survey_flag'];
        $title = $searchdata['survey_name'];

        $result = null;
        $sql = "select count(id) as total_count ";
        if ($view_flag ==="1") {            
            $sql .= "from surveys where flag = 0 and is_send = 1 and title like '%".$title.
                    "%' and (show_user_id = ".$user_id. " or show_user_id=0) and survey_flag = ".$survey_flag;;
        } else if ($view_flag ==="2"){
            $sql .= "from surveys where flag = 0 and title like '%".$title.
                    "%' and (show_user_id = ".$user_id. " or show_user_id=0) and survey_flag = ".$survey_flag;;
        } else {
            $sql .= "from surveys where flag = 0 and is_send <> 1 and title like '%".$title.
                    "%' and (show_user_id = ".$user_id. " or show_user_id=0) and survey_flag = ".$survey_flag;;            
        }
        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;
    }

    //설문이 완료되였는가를 검사(mobile_count == reply_count in notices table)
    public function survey_end_check($survey_id){
        $sql = "select mobile_count,reply_count";
        $sql .= " from notices";
        $sql .= " where survey_id = ".$survey_id;
        
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

}