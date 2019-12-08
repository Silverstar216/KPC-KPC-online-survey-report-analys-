<?php

/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 8/9/15
 * Time: 4:34 PM
 */

class Msg_result_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'msg_result';
    }

    public function get_data_notice_id($notice_id,$mobile="",$page=0,$per_page=10)
    {
        if($page > 1) {
            $page = ($page-1)*$per_page;
        } else {
            $page = 0;
        }


        $sql="SELECT msg_result.send_time, msg_result.dstaddr,msg_result.text,msg_result.stat, msg_result.result ,notice_reply.reply_count ";

            $sql.="from msg_result ";
            $sql.="LEFT JOIN notice_reply ON msg_result.notice_id = notice_reply.notice_id and  msg_result.dstaddr=notice_reply.mobile ";
            $sql.="where msg_result.notice_id=".$notice_id." and dstaddr like '%".$mobile."%'";
            $sql.=" order by send_time desc";
        $sql.=" limit ".$page.", ".$per_page;
        $query = $this->db->query($sql);


        return  $query->result_array();
    }
    public function get_total_notice_id($notice_id,$mobile="")
    {
        $sql="SELECT count(dstaddr) as total ";
        $sql.="from msg_result ";
        $sql.="where notice_id=".$notice_id." and dstaddr like '%".$mobile."%'";

        $query = $this->db->query($sql);

        return  $query->result_array();
    }
    public function del_by_object_id($notice_id) {
        $condition = array (
            'flag' => 0,
            'object_id' => $notice_id
        );

        $result = $this->delete_data( $condition);

        return $result;
    }

    public function get_use_history($user_id,$start_date,$end_date,$msg_type) {  //  s:sms  ,m:일반문자,d:문서포함, v:설문, l:lms
        $where =" user_id=".$user_id." and request_time >= '".$start_date."' and request_time <= '".$end_date."' and user_msg_type=".$msg_type;

        $sql="select count(dstaddr) as totalCount,";

        $sql.="(select count(dstaddr) from msg_result where stat=3 and result='100' and ".$where.") as successCount, ";
        $sql.="(select count(dstaddr) from msg_result where stat=3 and result<>'100' and ".$where.") as failureCount ";

        $sql.=" from msg_result where ";
        $sql.=$where;
        $query = $this->db->query($sql);

        $result =  $query->result_array();
        return $result[0];

    }
}