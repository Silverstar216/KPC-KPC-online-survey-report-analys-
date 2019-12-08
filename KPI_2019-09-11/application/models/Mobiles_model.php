<?php

/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 8/9/15
 * Time: 4:34 PM
 */
class Mobiles_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'mobiles';
    }
    /*
     * st : 검색조건
     * stval:검색값
     * ngst: 그룹검색
     * */
    public function get_Mobiles_sorted($userId){
        $qry = "select mobiles.*,groups.name as group_name from mobiles inner join groups on (mobiles.group_id = groups.id) where mobiles.user_id=".$userId." and mobiles.flag = 0 order by name ASC";
        $query = $this->db->query($qry);
        return $query->result_array();
    }
	public function get_Mobiles($userid,$page=0, $st='all', $stval='', $ngst='all',$per_page=10)
	{
        if($page > 1) {
            $page = ($page-1)*$per_page;
        } else {
            $page = 0;
        }
		$sql=" ";
		$stfield="";

		if($ngst=='all')
		{ 
			if ($st == 'name')
             	$stfield = " and m.name like '%".$stval."%' ";
			else if ($st == "hp")
             	$stfield = " and m.mobile like '%".$stval."%' ";
            else 
            {
            	 $stfield = " and (m.name like '%".$stval."%' ";
				$stfield .= (" or m.mobile like '%".$stval."%') ");
            }
           

			$sql.=" SELECT mmg.id, IFNULL(gp.name,'미분류') as gname, mmg.mobile, mmg.`name`,  mmg.address_num, mmg.memo1, ifnull(gp.id, 0) as gid ";
			$sql.=" from  ";
			$sql.=" ( ";
			$sql.=" SELECT m.id, m.mobile, m.name,  m.address_num, m.memo1, m.user_id, IFNULL(m.group_id,0) as group_id from ".$this->table_name." ";
			$sql.=" as m ";

			$sql.= " where 1 and m.user_id =".$userid."  && m.flag=0 ".$stfield." ORDER BY m.name asc ";
			$sql.=" ) as mmg ";
			$sql.=" LEFT JOIN ";
			$sql.=" groups as gp ";
			$sql.=" on mmg.group_id = gp.id ";
            $sql.=" order by mmg.name ASC";
			$sql.=" limit ".$page.", ".$per_page;
		}
		else
		{
			$ngst=intval($ngst);

			if ($st == 'name')
             	$stfield = " and m.name like '%".$stval."%' ";
			else if ($st == "hp")
             	$stfield = " and m.mobile like '".$stval."%' ";
            else if($st == 'all')
                if($stval !="")
                    $stfield = " and (m.mobile like '".$stval."%' or m.name like '%".$stval."%') ";
		     $sql.=" select m.id, g.name as gname, m.mobile, m.name, m.address_num,m.memo1, g.id as gid from groups as g ";

			 $sql.=" inner join mobiles as m ";
			 $sql.=" on m.group_id=g.id ";
			 $sql.=" where g.user_id=".$userid." and g.id=".$ngst." and g.flag=0 ".$stfield." ORDER BY m.name asc ";
			 $sql.=" limit ".$page.", ".$per_page;
		}
		$query = $this->db->query($sql);
		return $query->result_array();
		//return $sql;
	}

	public function get_total_count($userid, $st='all', $stval='', $ngst='all')
    {

        $sql=" ";
        $stfield="";

        if($ngst=='all')
        {
            if ($st == 'name')
                $stfield = " and m.name like '%".$stval."%' ";
            else if ($st == "hp")
                $stfield = " and m.mobile like '".$stval."%' ";
            else
            {
                $stfield = " and (m.name like '%".$stval."%' ";
                $stfield .= (" or m.mobile like '%".$stval."%') ");
            }


            $sql.=" SELECT Count(mmg.id) as total_count ";
            $sql.=" from  ";
            $sql.=" ( ";
            $sql.=" SELECT m.id, m.mobile, m.name,  m.address_num, m.memo1, m.user_id, IFNULL(m.group_id,0) as group_id from ".$this->table_name." ";
            $sql.=" as m ";

            $sql.= " where 1 and m.user_id =".$userid."  && m.flag=0 ".$stfield." ORDER BY m.id asc ";
            $sql.=" ) as mmg ";
            $sql.=" LEFT JOIN ";
            $sql.=" groups as gp ";
            $sql.=" on mmg.group_id = gp.id ";


        }
        else
        {
            $ngst=intval($ngst);


            if ($st == 'name')
                $stfield = " and m.name like '%".$stval."%' ";
            else if ($st == "hp")
                $stfield = " and m.mobile like '".$stval."%' ";
            else if($st == 'all')
                if($stval !="")
                    $stfield = " and (m.mobile like '".$stval."%' or m.name like '%".$stval."%') ";
            $sql.=" SELECT Count(m.id)  as total_count ";
            $sql.=" from groups as g";

            $sql.=" inner join mobiles as m ";
            $sql.=" on m.group_id=g.id ";
            $sql.=" where g.user_id=".$userid." and g.id=".$ngst." and g.flag=0 ".$stfield." ORDER BY m.id asc ";

        }

        $query = $this->db->query($sql);

        return $query->result_array();
        //return $sql;
    }
	public function exChangeGroupByNewId($preid, $newid)
	{	
		$count = 0;	
		$sql=" select id, mobile from mobiles where group_id = " . $preid;
		$query = $this->db->query($sql);
		foreach ($query->result() as $row) {
			$id = $row->id;
			$mobile = $row->mobile;
			$sql=" select count(*) as cnt from mobiles where group_id=" . $newid . " and mobile=" . $mobile;
			$subquery = $this->db->query($sql);			
			if ($subquery->result()[0]->cnt == '0') {
				$data = array(
					'group_id' => $newid
				);
				$this->db->where('id', $id);
				$this->db->update('mobiles', $data);
				$count++;
			}
		}
		return $count;
	}

/*
* paraArry:
            'userid' => $userid,
		    'groups' => $groups,
			'username' => $username,
			'mobile' => $mobile,
			'address_num' => $address_num,
			'memo1' => $memo1
*/
	public function insertAddUserInMobile($paraArray)
	{
		$userid=$paraArray['userid'];
		$groups=$paraArray['groups'];
		$username=$paraArray['username'];
		$mobile=$paraArray['mobile'];
		$address_num=$paraArray['address_num'];
		$memo1=$paraArray['memo1'];

		$data = array(
				'mobile' => $mobile,
				'name' => $username,
				'address_num' => $address_num,
				'memo1' => $memo1,
				'flag' =>0,
				'group_id' => $groups,
				'user_id' => $userid,
				'created_at'=> @date("Y-m-d H:i:s"),
				'updated_at'=> @date("Y-m-d H:i:s")
			);

		$this->db->insert('mobiles', $data);
		$mobile_id = $this->db->insert_id();
		
		if($mobile_id > 0)
		return 1;
		else 
		return 0;
	}
	
  	public function insertAddUserInMobileTwo($paraArray)
	{
		$useid=$paraArray['userid'];
		$groups=$paraArray['groups'];
		$username=$paraArray['username'];
		$mobile=$paraArray['mobile'];
		$address_num=$paraArray['address_num'];
		$memo1=$paraArray['memo1'];

		$data = array(
				'mobile' => $mobile,
				'name' => $username,
				'address_num' => $address_num,
				'memo1' => $memo1,
				'user_id' => $useid,
				'created_at'=> @date("Y-m-d H:i:s"),
			    'updated_at'=> @date("Y-m-d H:i:s")
			);

		$this->db->insert('mobiles', $data);
		$mobile_id = $this->db->insert_id();

		
		/*$groupA=array();
        $groupA=explode(',',$groups);
		$cnt=count($groupA);

        $data = array();
        if($cnt==1)
		{
			$data = array(
				'group_id' => $groupA[0],
				'mobile_id' => $mobile_id,
				'created_at'=> @date('Y-m-d H:i:s'),
				'updated_at'=> @date("Y-m-d H:i:s") //초기화를 안해주면 오류이다
			);

			$this->db->insert('mobile_groups', $data);
		}
		else 
		{
			for($i=0;$i<$cnt;$i++)
			{
			 $aaa =array(
						'group_id' => $groupA[$i],
						'mobile_id' => $mobile_id,
				        'created_at'=> @date('Y-m-d H:i:s'),
				        'updated_at'=> @date("Y-m-d H:i:s")
				);
			  array_push($data,$aaa);
			}
          
			$this->db->insert_batch('mobile_groups', $data);
		}*/

       return 1;
    }

	public function updatUserDataInMobile($paraArray)
	{
	  	$useid=$paraArray['userid'];
		$groups=$paraArray['groups'];
		$username=$paraArray['username'];
		$mobile=$paraArray['mobile'];
		$address_num=$paraArray['address_num'];
		$memo1=$paraArray['memo1'];
		$mobile_id=$paraArray['mid'];

		$data = array(
				'mobile' => $mobile,
				'name' => $username,
				'address_num' => $address_num,
				'memo1' => $memo1,
			    'updated_at'=> @date("Y-m-d H:i:s")
			);


		$this->db->where('id', $mobile_id);
		$this->db->update('mobiles', $data);


     //=== 먼저 삭제 ===//
	 /* $this->db->where('mobile_id', $mobile_id);
      $this->db->delete('mobile_groups');

		$groupA=array();
        $groupA=explode(',',$groups);
		$cnt=count($groupA);

        $data = array();
        if($cnt==1)
		{
			$data = array(
				'group_id' => $groupA[0],
				'mobile_id' => $mobile_id,
				'created_at'=> @date('Y-m-d H:i:s'),
				'updated_at'=> @date("Y-m-d H:i:s") //초기화를 안해주면 오류이다
			);

			$this->db->insert('mobile_groups', $data);
		}
		else 
		{
			for($i=0;$i<$cnt;$i++)
			{
			 $aaa =array(
						'group_id' => $groupA[$i],
						'mobile_id' => $mobile_id,
				        'created_at'=> @date('Y-m-d H:i:s'),
				        'updated_at'=> @date("Y-m-d H:i:s")
				);
			  array_push($data,$aaa);
			}
          
			$this->db->insert_batch('mobile_groups', $data);
		}*/

       return 1;
	}

	public function get_UserMobileDataByMobileId($userid,$mobileid)
    {
		$sql = "";
	    $sql.=" select id, mobile, name, address_num, memo1, user_id from mobiles ";
        $sql.=" where user_id=".$userid." and id=".$mobileid." ";
		 //echo $sql;exit;
		$query = $this->db->query($sql);
		return $query->row_array();
	}

   public function get_UserGroupDataByMobileId($mobileid)
    {
        $sql="";
		$sql.=" select  group_id as id,  ";
		$sql.=" (select name from groups where id=group_id) as name, ";
		$sql.=" mobile_id from mobiles where id=".$mobileid." ";
        
		 //echo $sql;exit;
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
	public function deletemobileById($mid)
	{	
		 
			$this->db->where('id', $mid);
			$this->db->delete('mobiles');
			

			
		return 1;
	}
	
	public function changeMobileInfoByUserId($params)
	{	
	
		 $pgid=$params['groupid'];
		 $moid=$params['moid'];
		 
          $data = array(
		    'mobile' => $params['mobile'],
			'name' => $params['username'],
			'address_num' => $params['address_num'],
			'memo1' => $params['memo1'],
			'group_id'=>$pgid,
			'updated_at'=>@date("Y-m-d H:i:s")
			);
		
			$w =array(
				'id' => $moid,
				'user_id'=>$params['userid']
			);
		
		 $this->db->update('mobiles', $data, $w);
		
		/*$aaa=array();
		 $sql=" select id from mobile_groups where group_id=".$pgid." and mobile_id=".$moid." ";
		 $query = $this->db->query($sql);		 
		 $aaa=$query->row_array();
		 $mgid=$aaa['id'];
		
		  $data = array(
		    'group_id' => $params['groupid'],
			'updated_at'=>@date("Y-m-d H:i:s")
			);
		  $w =array(
			'id' => $mgid
			);	
		 $this->db->update('mobile_groups', $data, $w);*/
		 
		return $this->db->affected_rows();
		
	}
    public function confirm_address_num($mobile,$address_num) {
       $sql = "select id from mobiles where mobile=".$mobile." and address_num like '%".$address_num."'";
       $query = $this->db->query($sql);
        $count = $query->num_rows(); //counting result from query
        return $count;
    }

    public function check_repeat_mobile($user_id,$mobile,$group_id){
      $sql = "";
      $group_name = "";
        $sql .= "select m.group_id as id from mobiles as m ";
     /* $sql .= "select g.name as name from mobiles as m inner join groups as g on m.group_id = g.id ";*/

            $sql .= " where m.user_id =" . $user_id . " and mobile ='" . $mobile . "' and group_id=" . $group_id;

      $query = $this->db->query($sql);
      $count = $query->num_rows(); //counting result from query
        if($count > 0) {
            $result = $query->result_array();
            if($result[0]['id'] > 0) {
                $sql = "select name from groups where id=".$result[0]['id'];
                $query = $this->db->query($sql);
                $result = $query->result_array();
                $group_name =$result[0]['name'];
            } else {
                $group_name = "미분류";
            }

        }

        return $group_name;
    }
		

}