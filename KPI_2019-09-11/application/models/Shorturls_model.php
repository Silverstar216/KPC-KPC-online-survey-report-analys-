<?php

/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 8/9/15
 * Time: 4:34 PM
 */

class Shorturls_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'shortenedurls';
    }

    //long_url보존하고 아이디얻기
   public function insert_long_url($url){
       $short_id = 0;
       $query = $this->db->get_where($this->table_name, array(//making selection
           'long_url' => $url
       ));
       $count = $query->num_rows(); //counting result from query
       if ($count > 0) {
           $short_id= -1;
       } else {
           $data = array(
               'long_url'=>$url,
               'created' =>date('Y-m-d H:i:s')
           );
           $short_id = $this->insert_data($data);
       }
       return $short_id;

   }

    public function get_long_url($id){
        $long_url = "";
        $this->db->select('long_url');
        $this->db->where('id',$id);
        $query = $this->db->get($this->table_name);
        return $query->result_array();
    }
    
    //long_url보존하고 아이디얻기
    public function insert_data_url($notice_id,$mobile,$advert_link_list){
        $short_id = 0;
        $query = $this->db->get_where($this->table_name, array(//making selection
            'notice_id' => $notice_id,
            'mobile'=>$mobile
        ));
        $count = $query->num_rows(); //counting result from query
        if ($count > 0) {
            $result = $query->result_array();
            $short_id= $result[0]['id'];
        } else {
            $data = array(
                'notice_id' => $notice_id,
                'mobile'=>$mobile,
                'created' =>date('Y-m-d H:i:s'),
                'advert_link'=>$advert_link_list
            );
            $short_id = $this->insert_data($data);
        }
        return $short_id;

    }
    public function get_data_url($id){
        $long_url = "";
        $this->db->select('notice_id, mobile,advert_link');

        $this->db->where('id',$id);
        $query = $this->db->get($this->table_name);
        return $query->result_array();

    }


    //long_url보존하고 아이디얻기
    public function insert_data_pass($user_id,$pass){
        $short_id = 0;

            $data = array(
                'notice_id' => $user_id,
                'advert_link'=>$pass,
                'created' =>date('Y-m-d H:i:s')
            );
            $short_id = $this->insert_data($data);

        return $short_id;

    }

    //long_url보존하고 아이디얻기
    public function get_data_pass($id){
        $long_url = "";
        $this->db->select('notice_id, advert_link,created');

        $this->db->where('id',$id);
        $query = $this->db->get($this->table_name);
        return $query->result_array();

    }


}