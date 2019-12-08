<?php

/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 8/9/15
 * Time: 4:34 PM
 */
class Goji_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'edoc_master';
    }

    //변환된 고지문서를 테블에 추가하기
    public function insertGojiDoc($DocInfo){
        $user_uid = $this->get_user_uid($DocInfo->user_id);

        $sql  = "insert into edoc_master(edoc_mbid,edoc_wdoc,edoc_adoc,edoc_wurl,edoc_time,edoc_var)";
        $sql .= " values('{$user_uid}', '{$DocInfo->edoc_wdoc}', '{$DocInfo->edoc_adoc}',";
        $sql .= "'{$DocInfo->edoc_wurl}','".date('Y-m-d H:i:s')."',";
        $sql .= (int)$DocInfo->edoc_var.")";

        return $this->db->query($sql);
    }

    //추가된 개별고지문서의 ID를 얻기
    public function get_gojidoc_id($DocInfo){
        $user_uid = $this->get_user_uid($DocInfo->user_id);

        $sql  = "select max(edoc_ukey) as curr_ukey from edoc_master ";
        $sql .= "where edoc_mbid='{$user_uid}' and edoc_wdoc='{$DocInfo->edoc_wdoc}' and edoc_adoc='{$DocInfo->edoc_adoc}' and edoc_wurl='{$DocInfo->edoc_wurl}' ";

        $query = $this->db->query($sql);
        $result = $query->result_array();

        if(count($result) > 0)
            return $result[0]['curr_ukey'];
        else
            return null;
    }

    //개별고지문서에 해당한 레코드얻기
    public function get_gojidoc_row($gojidoc_id){
        $sql  = "select * from edoc_master ";
        $sql .= "where edoc_ukey=".$gojidoc_id;

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    //개별고지변수를 edoc_variable, edocvar_master 테블에 추가
    public function insertGojiVar($VarInfo){

        //----- edocvar_master테블에 추가 ----------
        $sql  = "insert into edocvar_master(em_udoc,em_mbno,em_data_file,em_scnt, em_time) ";
        $sql .= "values({$VarInfo->edcv_udoc},{$VarInfo->user_id},";
        $sql .= "'{$VarInfo->filename}',{$VarInfo->var_count},'".date('Y-m-d H:i:s')."')";

        $result = $this->db->query($sql);
        if($result == false)
            return 0;

        //----- 추가된 id얻기 ---------
        $sql  = "SELECT max(em_ukey) as cur_em_ukey from edocvar_master";
        $query = $this->db->query($sql);

        $result = $query->result_array();

        if(count($result) == 0)
            return 0;

        $em_ukey = $result[0]['cur_em_ukey'];

        $insertCount = 0;
        //------- edoc_variable 테블에 추가 ----------
        for($index = 0; $index < count($VarInfo->goji_data); $index ++){

            $goji_data = $VarInfo->goji_data[$index];

            $sql  = "insert into edoc_variable(edcv_grid,edcv_mbno,edcv_udoc,edcv_ccnt, edcv_name,edcv_hp,edcv_var,edcv_time) ";
            $sql .= "values({$em_ukey},{$VarInfo->user_id},{$VarInfo->edcv_udoc},{$goji_data->var_count}, ";
            $sql .= "'{$goji_data->name}','{$goji_data->hp}','{$goji_data->var}',";
            $sql .= "'".date('Y-m-d H:i:s')."')";

            $this->db->query($sql);

            $insertCount ++;
        }

        $result = new stdClass();

        $result->em_ukey = $em_ukey;
        $result->insert_count = $insertCount;

        return $result;
    }

    //em_ukey에 해당한 전화번호목록 얻기
    public function getMobiles($em_ukey){
        $sql  = "select edcv_hp from edoc_variable ";
        $sql .= "where edcv_grid=".$em_ukey;

        $query = $this->db->query($sql);
        $result = $query->result_array();

        $ret = array();
        for($nRow = 0; $nRow < count($result); $nRow ++){
            array_push($ret, $result[$nRow]['edcv_hp']);
        }

        return $ret;
    }

    //전화번호에 해당한 고지변수 얻기
    public function get_var($em_ukey, $hp)
    {
        $sql = "select edcv_udoc, edcv_var from edoc_variable ";
        $sql .= "where edcv_grid={$em_ukey} and edcv_hp={$hp}";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    //사용자id에 해당한 고지양식목록 얻기
    public function getGojiList($user_id,$page=0, $type='all', $doc_name='', $per_page=10){
        $user_uid = $this->get_user_uid($user_id);

        if(!$user_uid)
            return null;

        if($page > 1) {
            $page = ($page-1)*$per_page;
        } else {
            $page = 0;
        }

        $sWhere = "where edoc_mbid='{$user_uid}'";
        if($type == "common")   //일반개별고지문서
            $sWhere .= " and edoc_var > 0";
        else if($type == "edu") //에듀파인문서
            $sWhere .= " and edoc_var = -1";

        $sWhere .= " and edoc_wdoc like '%".$doc_name."%' ";

        $sql  = "select * from edoc_master ";
        $sql .= $sWhere;
        $sql .=" order by edoc_wdoc ASC";
        $sql .= " limit ".$page.", ".$per_page;

        $query = $this->db->query($sql);

        return $query->result_array();
    }

    //사용자id에 해당한 고지양식목록의 전체개수 얻기---------
    public function get_GojiList_count($user_id,$type='all', $doc_name=''){
        $user_uid = $this->get_user_uid($user_id);

        if(!$user_uid)
            return null;

        $sWhere = "where edoc_mbid='{$user_uid}'";
        if($type == "common")   //일반개별고지문서
            $sWhere .= " and edoc_var > 0";
        else if($type == "edu") //에듀파인문서
            $sWhere .= " and edoc_var = -1";
        $sWhere .= " and edoc_wdoc like '%".$doc_name."%' ";

        $sql  = "select count(*) total_count from edoc_master ";
        $sql .= $sWhere;

        $query = $this->db->query($sql);

        $result = $query->result_array();

        if(count($result) > 0)
            return $result[0]['total_count'];
        else
            return 0;
    }

    //고지문서<삭제>처리
    public function removeDoc($edoc_ukey){

    //------- delete from <edoc_variable>-----------
        $sql  = " delete from edoc_variable";
        $sql .= " where edcv_udoc=".$edoc_ukey;

        $result = $this->db->query($sql);

    //------- delete from <edocvar_master>-----------
        $sql  = " delete from edocvar_master";
        $sql .= " where em_udoc=".$edoc_ukey;

        $result = $this->db->query($sql);

    //------- delete from <edoc_master>-----------
        $sql  = " delete from edoc_master";
        $sql .= " where edoc_ukey=".$edoc_ukey;

        $result = $this->db->query($sql);

        return $result;
    }

    //----------- 사용자id로부터 uid값을 얻기 -----------
    public function get_user_uid($user_id){
        $sql  = " select mb_id from g5_member ";
        $sql .= " where mb_no=".$user_id;

        $query = $this->db->query($sql);
        $result = $query->result_array();
        if(count($result) > 0)
            return $result[0]['mb_id'];
        else
            return null;
    }
}

