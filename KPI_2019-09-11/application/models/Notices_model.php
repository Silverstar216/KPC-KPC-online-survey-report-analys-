<?php

/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 8/9/15
 * Time: 4:34 PM
 */

class Notices_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'notices';
    }

    public function get_data_reserve($user_id,$start_date,$end_date,$page=0,$per_page=10)
    {
        if($page > 1) {
            $page = ($page-1)*$per_page;
        } else {
            $page = 0;
        }
            $sql = "select * from notices where flag = 0 and user_id=".$user_id." and start_time >= '".$start_date."' and start_time <='".$end_date."'";
            $sql .= " order by start_time desc";
            $sql.=" limit ".$page.", ".$per_page;
        $query = $this->db->query($sql);

        $result =  $query->result_array();

        return $result;
    }
    public function get_total_reserve($user_id,$start_date,$end_date)
    {

        $sql = "select Count(id) as total from notices where flag = 0 and user_id=".$user_id." and start_time >= '".$start_date."' and start_time <='".$end_date."'";


        $query = $this->db->query($sql);

        $result =  $query->result_array();

        return $result;
    }
  //전송결과
    public function get_data_send($user_id, $start_date, $end_date,$page=0,$per_page=10)
    {
        if($page > 1) {
            $page = ($page-1)*$per_page;
        } else {
            $page = 0;
        }
        $where ="where g.user_id=".$user_id." and g.flag=0 and g.start_time >= '".$start_date."' and g.start_time <= '".$end_date."'";

        $where.=" order by g.start_time desc  ";

        $sql="";
        $sql.=" select g.id, g.message_kind,g.calling_number,g.content,g.start_time,g.mobile_count,g.file_url,g.reply_count, ";
       $sql.="(select count(dstaddr) from msg_result where stat <3 and notice_id=g.id) as waitCount, ";
       $sql.="(select count(dstaddr) from msg_result where stat=3 and result='100' and notice_id=g.id) as successCount, ";
       $sql.="(select count(dstaddr) from msg_result where stat=3 and result<>'100' and notice_id=g.id) as failureCount ";
        $sql.=" from notices as g ";
        $sql.=$where;

        $sql.=" limit ".$page.", ".$per_page;

        $query = $this->db->query($sql);

        $result =  $query->result_array();
        return $result;
    }
    public function get_total_send($user_id, $start_date, $end_date)
    {


        $where ="where g.user_id=".$user_id." and g.flag=0 and g.start_time >= '".$start_date."' and g.start_time <= '".$end_date."'";



        $sql="";
        $sql.=" select count(g.id) as total ";

        $sql.=" from notices as g ";
        $sql.=$where;

        $query = $this->db->query($sql);

        $result =  $query->result_array();
        return $result;
    }



    public function update_Reserve_Time($userid,$noticeid,$startTime) {


            $data = array(

                'start_time'=> $startTime,
                 'updated_at' => date('Y-m-d')
            );

            $w =array(
                'id' => $noticeid,
                'user_id'=>$userid

            );
            //$this->db->where('id', $id);
            $this->db->update('notices', $data, $w);
        return $this->db->affected_rows();

    }
    public function delete_data_by_id($user_id,$id)
    {


        $condition = array (
            'flag' => 0,
            'id' => $id,
            'user_id'=>$user_id
        );

        $this->delete_data( $condition);


    }
    public function get_data_by_id($id)
    {
        $where ="where g.id=".$id." and g.flag=0 ";

        $where.=" order by g.start_time desc  ";

        $sql="";
        $sql.=" select g.id, g.message_kind,g.calling_number,g.content,g.start_time,g.mobile_count,g.file_url,g.reply_count, ";
        $sql.="(select count(dstaddr) from msg_result where stat <3 and notice_id=g.id) as waitCount, ";
        $sql.="(select count(dstaddr) from msg_result where stat=3 and result='100' and notice_id=g.id) as successCount, ";
        $sql.="(select count(dstaddr) from msg_result where stat=3 and result<>'100' and notice_id=g.id) as failureCount ";
        $sql.=" from notices as g ";
        $sql.=$where;
        $query = $this->db->query($sql);

        $result =  $query->result_array();
        return $result;
    }
    public function get_survey_id($id)
    {
        $sql="";
        $sql.=" select survey_id,file_url";
        $sql.=" from notices";
        $sql.=" where id=".$id." and flag=0";
        $query = $this->db->query($sql);
        $row = $query->row();

        return $row;
    }
    public function get_survey_data($id)
    {
        $reply_count = 0;
        $this->db->select('survey_id,file_url,reply_count');
        $this->db->where('id', $id);

        $result = $this->db->get($this->table_name)->result_array();
        if(sizeof($result) > 0) {
            $reply_count = $result[0]['reply_count'];
            if(!empty($reply_count)) {
                $reply_count +=1;
            } else {
                $reply_count = 1;
            }
        }
        $data = array(
            'reply_count'=> $reply_count
        );
        $this->db->where('id', $id);


        $this->db->update('notices', $data);

        return $result[0];
    }
}