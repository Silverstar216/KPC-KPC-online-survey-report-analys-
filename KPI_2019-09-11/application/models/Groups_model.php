<?php

/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 8/9/15
 * Time: 4:34 PM
 */

class Groups_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'groups';
    }


    public function insert_Group($addstr, $memo, $userid)
    {
        $data = array(
            'name' => $addstr,
            'memo' => $memo,
            'user_id' => $userid,
            'flag' => 0,
            'created_at'=> @date("Y-m-d H:i:s"),
            'updated_at'=> @date("Y-m-d H:i:s")
        );

        $this->db->insert('groups', $data);
        $id = $this->db->insert_id();
        return $id;
    }

    public function get_GroupByUserId($userid)
    {
        $query = $this->db->query('SELECT id, name, "0" as phonecount from '.$this->table_name.' where flag=0 && user_id='.$userid.' ORDER BY id asc');
        return $query->result_array();
    }

    public function get_GroupCount($userid)
    {
        $sql="";
        $sql.=" select count(g.id) as totalcnt ";
        $sql.=" from groups as g ";
        $sql.=" where g.user_id=".$userid." and g.flag=0";
        $query = $this->db->query($sql);
        $row = $query->row();

        return $row->totalcnt;
    }

    public function get_PhoneNumberCount_InGroup($group_id)
    {

    }
        
    public function get_group($userid)
    {
        $sql="";
        $sql.=" select groups.id, groups.name , count(*) as cnt";
        $sql.=" from groups ";
        $sql.=" inner join mobiles on(group_id = groups.id)";
        $sql.=" where groups.user_id=".$userid." and groups.flag=0 ";
        $sql.=" group by groups.id";
        $sql.=" order by groups.id asc ";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_phones($groupid)
    {
        $sql="";
        $sql.="select * from mobiles where group_id=" . $groupid;

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_GroupContent($userid, $gst)
    {
        $where =" where user_id=".$userid." and flag=0 ";
        if($gst!="all" && $gst!="")
            $where.=" and id=".intval($gst);

        $sql = "";
        $sql .=" select id, name, (select count(*) from mobiles where mobiles.user_id = groups.user_id and mobiles.group_id = groups.id) as cnt from groups";
        $sql.= $where;
        $sql .=" order by id asc";

        $query = $this->db->query($sql);

        $groupArray = $query->result_array();


        return $groupArray;
    }

    public function update_groupName($changeGroup, $userid)
    {
        $groups=array();
        $groups = explode(',',$changeGroup);
        for($i=0;$i < count($groups);$i++)
        {
            $idname=array();
            $idname = explode('_',$groups[$i]);
            $id=$idname[0];
            $name=$idname[1];

            $data = array(
                'name' => $name,
                'updated_at'=> @date("Y-m-d H:i:s")
            );

            $w =array(
                'id' => $id,
                'user_id'=>$userid
            );
            //$this->db->where('id', $id);
            $this->db->update('groups', $data, $w);

        }

        return 1;
    }

    public function delete_groupCont($changeGroup, $userid)
    {

        $groups=array();
        $groups = explode(',',$changeGroup);
        for($i=0;$i < count($groups);$i++)
        {
            $idname=array();
            $idname = explode('_',$groups[$i]);
            $id=$idname[0];
            $name=$idname[1];

            $this->db->where('id', $id);
            $this->db->delete('groups');

            $this->db->where('group_id', $id);
            $this->db->delete('mobiles');

        }
        return 1;
    }

    public function delete_contOfGroup($changeGroup, $userid)
    {

        $groups=array();
        $groups = explode(',',$changeGroup);
        for($i=0;$i < count($groups);$i++)
        {
            $idname=array();
            $idname = explode('_',$groups[$i]);
            $id=$idname[0];
            $name=$idname[1];

            $this->db->where('group_id', $id);
            $this->db->delete('mobiles');

        }
        return 1;
    }

}