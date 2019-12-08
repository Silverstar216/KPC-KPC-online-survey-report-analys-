<?php

/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 8/9/15
 * Time: 4:34 PM
 */

class Reviews_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'review';
    }
    public function get_data_review($user_id, $survey_flag, $start_date, $end_date, $page=0, $per_page=10)
    {
        if($page > 1) {
            $page = ($page-1)*$per_page;
        } else {
            $page = 0;
        }

        $where = "where s.survey_flag = ".$survey_flag." and g.user_id=".$user_id." and s.flag=0 and DATE(g.start_time) >= '".$start_date;
        $where .= "' and DATE(g.start_time) <= '".$end_date."'";
        $where .= " order by g.start_time desc  ";

        $sql = "select g.id, s.education_id, s.education_course as subject_name, s.title,s.start_time, s.end_time, g.reply_count,s.end_count,";
        $sql.="(select count(mobile) from review where notice_id=g.id) as responseCount, ";
        $sql.="(select count(dstaddr) from msg_result where stat=3 and result='100' and notice_id=g.id) as successCount ";
        $sql.=" from notices as g inner join surveys as s on g.survey_id=s.id ";

        $sql.=$where;
        $sql.=" limit ".$page.", ".$per_page;

        
        $query = $this->db->query($sql);

        $result =  $query->result_array();
            return $query->result_array();

    }
    public function get_total_review($user_id, $survey_flag, $start_date, $end_date)
    {
        $where ="where s.survey_flag=".$survey_flag." and g.user_id=".$user_id." and g.flag=0 and DATE(g.start_time) >= '".$start_date."' and DATE(g.start_time) <= '".$end_date."'";

        $where.=" order by g.start_time desc  ";
        $sql=" select count(s.id) as total ";

        $sql.=" from notices as g inner join surveys as s on g.survey_id=s.id ";
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
        $query1 .= " and dstaddr not In (select mobile from review where notice_id = ".$notice_id.")) and mobiles.user_id = ".$user_id;
        $query2 = "select * from mobiles where mobile in (";
        $query2 .= " select dstaddr from msg_result where notice_id=".$notice_id ;
        $query2 .= " and dstaddr not In (select mobile from review where notice_id = ".$notice_id.")) and mobiles.user_id = ".$user_id;
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
                        $query = " select mobiles.mobile,review.response_man from review inner join mobiles on review.mobile = mobiles.mobile and mobiles.user_id = " . $user_id;
                        $query .= " where answer like '%" . '"' . $n . '미선택"%' . "'";
                        $query .= " and review.notice_id = " . $notice_id;
                        $query .="  group by mobiles.mobile";
                        $temp = $this->db->query($query);

                        $resultQuestion['e0'] = $temp->result_array();
                        $countInQuestion += count($temp->result_array());
                        $result['q' . $n . 'unselect'] = $resultQuestion;
                    }
                }

                //문항별로 순환
                switch($question['type']) {
                    case 0://객관식항목이면
                        $m = 0;
                       if($question['use_other_input'] === "1"){
                           foreach ($question['examples'] as $example):
                               $m ++;
                               $query = " select mobiles.mobile,review.response_man from review inner join mobiles on review.mobile = mobiles.mobile and mobiles.user_id = ".$user_id;
                               $query .=" where answer like '%".'"'.$n.$example['title'].'"%'."'";
                               $query .=" and review.notice_id = ".$notice_id;
                               $query .="  group by mobiles.mobile";
                               $temp = $this->db->query($query);
                               $resultQuestion['e'.$m]= $temp->result_array();
                               $countInQuestion += count($temp->result_array());
                           endforeach;


                           $query = " select mobiles.mobile,review.response_man,answer from review inner join mobiles on review.mobile = mobiles.mobile and mobiles.user_id = ".$user_id;
                           $query .=" where answer like '%".'"'.$n.'기타%'."'";
                           $query .=" and review.notice_id = ".$notice_id;
                           $query .="  group by mobiles.mobile";
                           $temp = $this->db->query($query);
                           $resultQuestion['e기타']= $temp->result_array();
                           $countInQuestion += count($temp->result_array());
                       } else {
                           foreach ($question['examples'] as $example):
                               $m ++;
                               $query = " select mobiles.mobile,review.response_man from review inner join mobiles on review.mobile = mobiles.mobile and mobiles.user_id = ".$user_id;
                               $query .=" where answer like '%".'"'.$n.$example['title'].'"%'."'";
                               $query .=" and review.notice_id = ".$notice_id;
                               $query .="  group by mobiles.mobile";
                               $temp = $this->db->query($query);
                               $resultQuestion['e'.$m]= $temp->result_array();
                               $countInQuestion += count($temp->result_array());
                           endforeach;
                       }

                        $result['q'.$n] = $resultQuestion;
                        break;
                    case 1: //주관식항목이면
                            $resultExamples = array();
                            // $query = " select mobiles.name,answer,review.response_man from review inner join mobiles on review.mobile = mobiles.mobile and mobiles.user_id = ".$user_id;
                            // $query .=" where review.notice_id = ".$notice_id;
                            // $query .="  group by mobiles.mobile";
                            $query = " select '' as name, answer, response_man from review";
                            $query .=" where notice_id = ".$notice_id;
                            
                            $temp = $this->db->query($query);
                            $tempExamples = $temp->result_array();
                            $m = 0;
                            foreach ($tempExamples as $tempExample):
                                    // $jsonTemp = json_decode($tempExample['answer']);
                                    $segment_pos = strpos($tempExample['answer'],'"'.$n.'":');
                                    $begin_pos = strpos($tempExample['answer'],'["', $segment_pos);
                                    $end_pos = strpos($tempExample['answer'],'"]', $begin_pos);
                                    if ($begin_pos > 0 && $end_pos > 0) {
                                        $m++;                                        
                                        $resultExamples[$m] = array('name' => $tempExample['name'],
                                            'response_man' => $tempExample['response_man'], 
                                            'text' => substr($tempExample['answer'], $begin_pos + 2, $end_pos - $begin_pos - 2));
                                    }
                            endforeach;
                            $result['q'.$n] = $resultExamples;
                            $countInQuestion += count($resultExamples);
                        break;
                    case 2://만족도이면
                        $exam_count = $question['example_count'];
                        $elements = array();
                        if($question['rating_names'] != null){
                            $elements = explode(',',$question['rating_names']);
                        }else{
                            if($exam_count == 5)
                                $elements=['매우 불만족','불만족','보통','만족','매우 만족'];
                            else
                                $elements=['불만족','보통','만족'];
                        }

                        $m=0;
                        foreach ($elements as $element):
                            $m++;
                            $query = " select mobiles.mobile,review.response_man from review inner join mobiles on review.mobile = mobiles.mobile and mobiles.user_id = ".$user_id;
                            $query .=" where answer like '%".'"'.$n.$element.'"%'."'";
                            $query .=" and review.notice_id = ".$notice_id;
                            $query .="  group by mobiles.mobile";
                            $temp = $this->db->query($query);
                            $resultQuestion['e'.$m]= $temp->result_array();
                            $countInQuestion += count($temp->result_array());
                        endforeach;
                        $result['q'.$n] = $resultQuestion;
                        break;
                    case 3://강사만족도이면
                        $exam_count = $question['example_count'];
                        $elements = array();
                        if($question['rating_names'] != null){
                            $elements = explode(',',$question['rating_names']);
                        }else{
                            if($exam_count == 5)
                                $elements=['매우 불만족','불만족','보통','만족','매우 만족'];
                            else
                                $elements=['불만족','보통','만족'];
                        }
                        $t=0;
                        //강사별순환
                        foreach ($question['teachers'] as $teacher):
                            $resultMarkQuestion = array();
                            $t ++;

                            $m = 0;
                            //평가지표별 순환
                            foreach ($question['teacher_marks'] as $teacher_mark):
                                $resultElementQuestion = array();
                                $m ++;

                                $countInQuestion = 0;
                                $e = 0;
                                //만족도지표순환
                                foreach ($elements as $element):
                                    $e++;
                                    $query = " select mobiles.mobile,review.response_man from review inner join mobiles on review.mobile = mobiles.mobile and mobiles.user_id = ".$user_id;
                                    $query .=" where answer like '%".'"'.$n.'|'.$teacher['id'].'|'.$teacher_mark['id'].$element.'"%'."'";
                                    $query .=" and review.notice_id = ".$notice_id;
                                    $query .="  group by mobiles.mobile";

                                    $temp = $this->db->query($query);
                                    $resultElementQuestion['e'.$e]= $temp->result_array();
                                    $countInQuestion += count($temp->result_array());
                                endforeach;

                                $resultMarkQuestion['m'.$m] = $resultElementQuestion;
                                $resultMarkQuestion['m'.$m.'count'] = $countInQuestion;
                            endforeach;

                            //강사에게 하고싶은 말
                            $MessagesToTeacher = array();
                            $query = " select answer, response_man from review";
                            $query .=" where notice_id = ".$notice_id;

                            $temp = $this->db->query($query);
                            $tempResults = $temp->result_array();
                            $s = 0;
                            $searchText = '"'.$n.'t'.$teacher['id'].'":';
                            foreach ($tempResults as $item):

                                $segment_pos = strpos($item['answer'],$searchText);
                                $begin_pos = strpos($item['answer'],'[[', $segment_pos);
                                $end_pos = strpos($item['answer'],'],', $begin_pos);
                                if ($segment_pos > 0 && $begin_pos > 0 && $end_pos > 0) {
                                    $s++;
                                    $MessagesToTeacher[$s] = array(
                                        'response_man' => $item['response_man'],
                                        'text' => substr($item['answer'], $begin_pos + 2, $end_pos - $begin_pos - 2));
                                }
                            endforeach;
                            $resultQuestion['t'.$t.'text'] = $MessagesToTeacher;

                            $resultQuestion['t'.$t] = $resultMarkQuestion;
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
        $sql = "select count(mobile) as cnt from review where notice_id=".$notice_id;
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