<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 12/8/2018
 * Time: 9:36 AM
 */

class Advert_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'advert';
    }

    /*
     * 설문목록들을 돌려주는 함수
     *view_flag :  1: 전송목록,  2: 작업목록,  3:  공개목록
     * page: 현재페지
     * per_page  :  페지당 개수
     * */
    public function get_advert_list($user_id,$page=0,$per_page=10,$title ="") {
        if($page > 1) {
            $page = ($page-1)*$per_page;
        } else {
            $page = 0;
        }
        $result = null;

            $sql = "select * ";
            $sql .=" from advert where mb_id ='".$user_id."' and advert_title like '%".$title."%' order by is_connect desc, created_at desc";
            $sql.=" limit ".$page.", ".$per_page;
            $query = $this->db->query($sql);
            $result = $query->result_array();


        return $result;
    }
    /*
     * 설문목록총개수돌려주는 함수
     *view_flag :  1: 전송목록,  2: 작업목록,  3:  공개목록
     *
     * */
    public function get_advert_total_count($user_id,$title=""){
        $result = null;

            $sql = "select count(id) as total_count ";
            $sql .=" from advert where advert_title like '%".$title."%' and  mb_id ='".$user_id."'";
            $query = $this->db->query($sql);
            $result = $query->result_array();


        return $result;
    }
    public function get_connectable_advert($user_id) {
        $sql = "select * from advert where mb_id ='".$user_id."' and start_date <=SYSDATE() and end_date >= SYSDATE() order by created_at desc";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }


    public function get_sendable_advert($user_id) {
        $sql = "select * from advert where mb_id ='".$user_id."' and is_connect=1 and start_date <=SYSDATE() and end_date >= SYSDATE() order by created_at desc";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }

    public function init_connect_advert($user_id) {
        $sql = "select * from advert where mb_id ='".$user_id."' and is_connect=1 and end_date < SYSDATE() order by created_at desc";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        foreach ($result as $item) {
            $condition = array(
                'mb_id'=>$user_id,
                'id'=>$item['id']
            );
            $data = array(
              'is_connect'=>0
            );
            $this->update_data($data,$condition);

        }

    }

    public function init_advert() {
        $sql = "update advert set is_connect = 0 where end_date < SYSDATE()";
        $query = $this->db->query($sql);

    }
    public function set_sendCount($mb_id,$mobile_count){
        $advert_ids = array();
        $advert_total_count = 0;
        $sql = "select * from advert where mb_id ='".$mb_id."' and start_date <=SYSDATE() and end_date >= SYSDATE()";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        if(sizeof($result) > 0) {
            $sql = "update advert set send_count=".$mobile_count." where mb_id ='".$mb_id."' and start_date <=SYSDATE() and end_date >= SYSDATE()";
            $this->db->query($sql);
            foreach ($result as $item) {
                $advert_ids[]=$item['id'];
                $advert_total_count = $advert_total_count+1;
            }

        }
        $sql = "select mb_id from g5_member_link where linked_mb_id= '".$mb_id."'";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        if(sizeof($result) > 0) {
            foreach ($result as $item) {

            }
        }
    }

    public function get_advert_link_list($link_array) {
        $string="1,2,3,4,5";
        $array_list=array_map('intval', $link_array);
        $array_list = implode(",",$array_list);
        $sql = "select * from advert where id in (".$array_list.")";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;
    }
}
