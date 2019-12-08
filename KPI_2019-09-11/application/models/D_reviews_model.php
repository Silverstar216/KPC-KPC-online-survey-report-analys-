<?php

/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 8/9/15
 * Time: 4:34 PM
 */

class D_reviews_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'd_review';
    }
    public function get_data_review($user_id, $start_date, $end_date,$page=0,$per_page=10)
    {
        if($page > 1) {
            $page = ($page-1)*$per_page;
        } else {
            $page = 0;
        }
        $sql = "";
           /* $where = "where s.user_id=" . $user_id . " and s.flag=0 and s.start_time >'" . $start_date . "'' and s.start_time < '" . $end_date."''";

            $where .= "  order by s.start_time desc  ";
            $sql = "";
            $sql .= " select s.id, s.calling_number,s.content, ";
            $sql .= "(select start_time from survey where flag=0 and id=s.survey_id) as start_time , ";
            $sql .= "(select end_time from survey where flag=0 and id=s.survey_id) as end_time ,  ";
            $sql .= "(select end_count from survey where flag=0 and id=s.survey_id) as end_count ,  ";
            $sql .= "(select count(mobile) from review where notice_id=s.id and flag =0) as cnt,s.mobile_count ";
            $sql .= " from notice as s ";
            $sql .= $where;
            $sql.=" limit ".$page.", ".$per_page;
            $query = $this->db->query($sql);*/
        $where ="where g.user_id=".$user_id." and g.flag=0 and DATE(g.start_time) >= '".$start_date."' and DATE(g.start_time) <= '".$end_date."'";

        $where.=" order by g.start_time desc  ";
        $sql = "select g.id,s.title,s.start_time, s.end_time, g.reply_count,s.end_count,";
        $sql.="(select count(mobile) from review where notice_id=g.id) as responseCount, ";
        $sql.="(select count(dstaddr) from msg_result where stat=3 and result='100' and notice_id=g.id) as successCount ";
        $sql.=" from notices as g inner join diagnosis as s on g.survey_id=s.id ";
        $sql.=$where;
        $sql.=" limit ".$page.", ".$per_page;
        $query = $this->db->query($sql);

        $result =  $query->result_array();
            return $query->result_array();

    }
    public function get_total_review($user_id, $start_date, $end_date)
    {


        $where ="where g.user_id=".$user_id." and g.flag=0 and DATE(g.start_time) >= '".$start_date."' and DATE(g.start_time) <= '".$end_date."'";

        $where.=" order by g.start_time desc  ";
        $sql=" select count(s.id) as total ";

        $sql.=" from notices as g inner join diagnosis as s on g.survey_id=s.id ";
        $sql .= $where;

        $query = $this->db->query($sql);

        $result =  $query->result_array();
        return $result;
    }

    //미응답자들의 전화번호와 정보를 얻기
    public function get_noResponseMobile($notice_id,$user_id)
    {
        $result = array();
       $query1 = "select * from mobiles where mobile in (";
        $query1 .= " select dstaddr from msg_queue where notice_id=".$notice_id ;
        $query1 .= " and dstaddr not In (select mobile from d_review where notice_id = ".$notice_id.")) and mobiles.user_id = ".$user_id;
        $query2 = "select * from mobiles where mobile in (";
        $query2 .= " select dstaddr from msg_result where notice_id=".$notice_id ;
        $query2 .= " and dstaddr not In (select mobile from d_review where notice_id = ".$notice_id.")) and mobiles.user_id = ".$user_id;
           /* $query  = " select * from mobiles where mobile in (";
            $query .= " select mobile from messages where object_id = ".$notice_id." and type = 1 ";
            $query .= " and mobile not In (select mobile from review where notice_id = ".$notice_id."))";
            $result = $this->db->query($query);
            return $result->result_array();*/
        $result1 = $this->db->query($query1);
        $result[]=$result1->result_array();
        $result2 = $this->db->query($query2);
        $result[]=$result2->result_array();
        return $result;
    }
    //설문에 대한 응답결과를 종합적으로 얻기
    public function get_allReview($notice_id,$questions,$user_id){
            $result = array();
            $n = 0;
            $countInQuestion=0;
            //질문별로 순환
            foreach ($questions as $question):
                $resultQuestion = array();
                $n++;
                $countInQuestion = 0;
                if($question['allow_unselect'] === "1") {
                    if($question['type'] !=="1") {
                        $query = " select mobiles.mobile,d_review.response_man from d_review inner join mobiles on d_review.mobile = mobiles.mobile and mobiles.user_id = " . $user_id;
                        $query .= " where answer like '%" . '"' . $n . '미선택"%' . "'";
                        $query .= " and d_review.notice_id = " . $notice_id;
                        $query .="  group by mobiles.mobile";
                        $temp = $this->db->query($query);

                        $resultQuestion['e0'] = $temp->result_array();
                        $countInQuestion += count($temp->result_array());
                        $result['q' . $n . 'unselect'] = $resultQuestion;
                    }
                }
                //문항별로 순환
                switch($question['type']) {
                    case 0://옵션항목이면
                        $m = 0;
                       if($question['use_other_input'] === "1"){
                           foreach ($question['examples'] as $example):
                               $m ++;
                               $query = " select mobiles.mobile,d_review.response_man from d_review inner join mobiles on d_review.mobile = mobiles.mobile and mobiles.user_id = ".$user_id;
                               $query .=" where answer like '%".'"'.$n.$example['title'].'"%'."'";
                               $query .=" and d_review.notice_id = ".$notice_id;
                               $query .="  group by mobiles.mobile";
                               $temp = $this->db->query($query);
                               $resultQuestion['e'.$m]= $temp->result_array();
                               $countInQuestion += count($temp->result_array());
                           endforeach;


                           $query = " select mobiles.mobile,d_review.response_man,answer from d_review inner join mobiles on d_review.mobile = mobiles.mobile and mobiles.user_id = ".$user_id;
                           $query .=" where answer like '%".'"'.$n.'기타%'."'";
                           $query .=" and d_review.notice_id = ".$notice_id;
                           $query .="  group by mobiles.mobile";
                           $temp = $this->db->query($query);
                           $resultQuestion['e기타']= $temp->result_array();
                           $countInQuestion += count($temp->result_array());
                       } else {
                           foreach ($question['examples'] as $example):
                               $m ++;
                               $query = " select mobiles.mobile,d_review.response_man from d_review inner join mobiles on d_review.mobile = mobiles.mobile and mobiles.user_id = ".$user_id;
                               $query .=" where answer like '%".'"'.$n.$example['title'].'"%'."'";
                               $query .=" and d_review.notice_id = ".$notice_id;
                               $query .="  group by mobiles.mobile";
                               $temp = $this->db->query($query);
                               $resultQuestion['e'.$m]= $temp->result_array();
                               $countInQuestion += count($temp->result_array());
                           endforeach;
                       }

                        $result['q'.$n] = $resultQuestion;
                        break;
                    case 1://주관Text항목이면
                            $resultExamples = array();
                            $query = " select mobiles.name,answer,d_review.response_man from d_review inner join mobiles on d_review.mobile = mobiles.mobile and mobiles.user_id = ".$user_id;
                            $query .=" where d_review.notice_id = ".$notice_id;
                        $query .="  group by mobiles.mobile";
                            $temp = $this->db->query($query);
                            $tempExamples = $temp->result_array();
                            $m = 0;

                            foreach ($tempExamples as $tempExample):
                                    $jsonTemp = json_decode($tempExample['answer']);
                                    if ($jsonTemp && strpos($tempExample['answer'],'"'.$n.'":') > 0) {
                                        $m++;
                                        if(is_array($jsonTemp->$n))
                                        $resultExamples[$m] = array('name' => $tempExample['name'],'response_man' => $tempExample['response_man'], 'text' => is_array($jsonTemp->$n) ? implode(', ',$jsonTemp->$n):$jsonTemp->$n);
                                    }
                            endforeach;
                            $result['q'.$n] = $resultExamples;
                            $countInQuestion += count($resultExamples);
                        break;
                    case 2://만족도이면
                        if($question['example_count'] == 5)
                            $elements = ['매우 불만족','불만족','보통','만족','매우 만족'];
                        else
                            $elements = ['불만족','보통','만족'];
                        $m=0;
                        foreach ($elements as $element):
                            $m++;
                            $query = " select mobiles.mobile,d_review.response_man from d_review inner join mobiles on d_review.mobile = mobiles.mobile and mobiles.user_id = ".$user_id;
                            $query .=" where answer like '%".'"'.$n.$element.'"%'."'";
                            $query .=" and d_review.notice_id = ".$notice_id;
                            $query .="  group by mobiles.mobile";
                            $temp = $this->db->query($query);
                            $resultQuestion['e'.$m]= $temp->result_array();
                            $countInQuestion += count($temp->result_array());
                        endforeach;
                        $result['q'.$n] = $resultQuestion;
                        break;
                }
                $result['q'.$n.'Count'] = $countInQuestion;
            endforeach;

            return $result;
    }
    public function get_response_count($notice_id) {
        $sql ="";
        $sql = "select count(mobile) as cnt from d_review where notice_id=".$notice_id;
        $query = $this->db->query($sql);
        return $query->result_array();

    }
    public function set_reply_count($notice_id,$mobile) {
        $flag = -1;
        $condition = array(
            'notice_id'=>$notice_id,
            'mobile'=>$mobile
        );
        $reply_count = 0;
        $this->db->select('reply_count');
        $this->db->where($condition);
        $result = $this->db->get('notice_reply')->result_array();
        if(sizeof($result) > 0 && $result[0]['reply_count'] !=="") {
            $reply_count = $result[0]['reply_count'] + 1;
            $w =array(
                'reply_count' => $reply_count
            );
            $this->db->where($condition);
             $this->db->update('notice_reply', $w);
            $flag = $this->db->affected_rows();
        } else {
            $w = array(
                'notice_id'=>$notice_id,
                'mobile'=>$mobile,
                 'reply_count' => 1
            );
            $this->set_data($w);
            $this->db->insert('notice_reply');

                $this->update_survey_reply($notice_id);
            $flag=1;
        }
        return $flag;
    }
    public function update_survey_reply($id)
    {
        $reply_count = 0;
        $this->db->select('reply_count');
        $this->db->where('id', $id);

        $result = $this->db->get('notices')->result_array();
        if(sizeof($result) > 0) {
            $reply_count = $result[0]['reply_count'];
            if(!empty($reply_count)) {
                $reply_count +=1;
            } else {
                $reply_count = 1;
            }
            $data = array(
                'reply_count'=> $reply_count
            );
            $this->db->where('id', $id);
            $this->db->update('notices', $data);
        }
    }
}