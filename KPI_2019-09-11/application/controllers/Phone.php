<?php
/**
 * Author: CHKD
 * Date: 10/9/2018
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Phone extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

		$this->load->helper('control_helper');

        // date_default_timezone_set('Asia/Pyongyang');
		// PHP 4.1.0 부터 지원됨
		// php.ini 의 register_globals=off 일 경우
		//@extract($_GET);
		//@extract($_POST);
		//@extract($_SERVER);

        $this->data['title'] = SITE_TITLE;
        $this->data['styles'] = array(    
            'include/css/phone.css',
            'include/css/survey.css',
			'include/plugins/font-awesome/css/font-awesome.min.css',
            'include/plugins/bootstrap-sweetalert/sweetalert.css',
            'include/lib/jquery.datetimepicker.css',
            'include/lib/nouislider/nouislider.min.css',
            'include/plugins/jquery-file-upload/css/jquery.fileupload.css',
            'include/plugins/plupload/js/jquery.ui.plupload/css/jquery.ui.plupload.css',
            'include/css/plugins.css',
            'include/lib/jquery.fileupload/css/jquery.fileupload.css',
			'include/lib/chosen/prism.css',
            'include/lib/chosen/chosen.css',
        );

        $this->data['scripts'] = array(			
        	'include/plugins/bootstrap-sweetalert/sweetalert.min.js',
            'include/lib/jquery.datetimepicker.js',
            'include/lib/nouislider/nouislider.min.js',
            'include/lib/jquery.fileupload/js/jquery.iframe-transport.js',
            'include/lib/jquery.fileupload/js/jquery.fileupload.js',
            'include/plugins/plupload/js/plupload.full.min.js',
            'include/plugins/plupload/js/jquery.ui.plupload/jquery.ui.plupload.min.js',
            'include/plugins/plupload/js/i18n/ko.js',
            'include/lib/chosen/chosen.jquery.js',
			'include/lib/chosen/prism.js',
			'include/lib/chosen/init.js',			
            'include/js/phone.js',
            'include/js/pagination.js',
            'include/js/phonefile.js',
            // 'include/js/notice.js',            
        );

		$this->load->helper('my_directory');
        $this->load->model('groups_model');
        $this->load->model('mobiles_model');
        $this->load->model('senderphone_model');
    }

    public function index()
    {
        if (is_signed()) {
            $userid = get_session_user_id();
            $userauth= get_session_user_level();
            if($userauth ==="") {
                $userauth = "";
                $userid = -1;
            }
    
            $this->data['groups'] =  $this->groups_model->get_GroupByUserId($userid);
            $this->data['totalCnt'] = $this->groups_model->get_GroupCount($userid);
            $this->data['user_id'] = $userid;
            $this->data['user_level'] = $userauth;
            $this->data['menu'] = '전화번호관리';
            $this->data['submenu'] = '번호그룹'; 

            $this->load->view('templates/header', $this->data);
            $this->load->view('templates/nav', $this->data);
            $this->load->view('phone/index', $this->data);
            $this->load->view('templates/nav-footer', $this->data);
            $this->load->view('templates/scripts', $this->data);
            $this->load->view('templates/footer', $this->data);
        }
        else {
            ?>
            <script type="text/javascript">                
                alert("로그인상태에서만 이용하실수 있습니다.");
                base_url = "<?=$GLOBALS['protocol']?>://" + location.host + "/index";
                window.location = base_url;
            </script>
            <?php  
        }
    }
	
	public function getGroupList()
    {
		$userid=get_session_user_id();
        $userauth= get_session_user_level();
        if($userauth ==="" ) {
            $userid = -1;
        }
        $gst = isset($_GET['gst']) ? $_GET['gst']:'all';
        
        $this->data['userid'] = $userid;        
		$this->data['groups'] =  $this->groups_model->get_GroupByUserId($userid);
		$this->data['groupCont'] =  $this->groups_model->get_GroupContent($userid, $gst);
		$this->data['totalCnt'] = count($this->data['groupCont']);
        $this->load->view('phone/groupList', $this->data);
    
    }
	
	public function addGroup()
	{
		$userid=get_session_user_id();
        if($userid =="" ) {
            $userid = -1;
        }
        $addstr = isset($_POST['addstr']) ? $_POST['addstr']:'';
		$memo = isset($_POST['memo']) ? $_POST['memo']:'';


        $query = $this->db->get_where('groups', array(//making selection
            'name' => $addstr,
            'user_id'=>$userid
        ));
        $count = $query->num_rows(); //counting result from query
        if ($count !== 0)
        {
            echo -1;
            exit;
        }
		
		$insertid = $this->groups_model->insert_Group($addstr, $memo, $userid);
	//	$this->index();

			echo $insertid;

	}	
	public function changeGroup()
	{
		$userid=get_session_user_id();
        if($userid =="" ) {
            $userid = -1;
        }

		$preid = isset($_GET['preid']) ? $_GET['preid']:0;
		$newid = isset($_GET['newid']) ? $_GET['newid']:0;
		
		$flag = $this->mobiles_model->exChangeGroupByNewId($preid, $newid);
		if($flag > 0)
			echo "ok";
		else
		    echo "err";
	}
	
	public function changeGroupName()
	{
		$userid=get_session_user_id();
        if($userid =="" ) {
            $userid = -1;
        }

		$changecont = isset($_POST['changecont']) ? $_POST['changecont']:null;	
  		
		$flag = $this->groups_model->update_groupName($changecont, $userid);
		if($flag > 0)
		{
			
			$grouplist =  $this->groups_model->get_GroupByUserId($userid);
			echo json_encode($grouplist);
		}
		else
		    echo "err";
		
		//redirect();
	}
	public function delete_groupCont()
	{
		$userid=get_session_user_id();


		$changecont = isset($_POST['changecont']) ? $_POST['changecont']:null;	
		
		$flag = $this->groups_model->delete_groupCont($changecont, $userid);
		if($flag > 0)
		{
			$grouplist =  $this->groups_model->get_GroupByUserId($userid);
			echo json_encode($grouplist);
		}
		else
		    echo "err";
	}
	
	//그룹내용만 삭제
	public function delete_contOfGroup()
	{
		$userid=get_session_user_id();
//		$userid=1;

		$changecont = isset($_POST['changecont']) ? $_POST['changecont']:null;	
		
		$flag = $this->groups_model->delete_contOfGroup($changecont, $userid);
		if($flag > 0)
		{
			echo "ok";
		}
		else
		    echo "err";
	} 

	public function phoneFile()
    {
        if (is_signed()) {
            $userid = get_session_user_id();
            $userauth= get_session_user_level();
            if($userauth ==="") {
                $userauth = "";
                $userid = -1;
            }
            $this->data['user_level'] = $userauth;
            $this->data['groups'] =  $this->groups_model->get_GroupByUserId($userid);
            $this->data['totalCnt'] = $this->groups_model->get_GroupCount($userid);
    
            $st = isset($_POST['st']) ? $_POST['st']:'all';
            $stval = isset($_POST['stval']) ? $_POST['stval']:'';
            $ngst=isset($_POST['ngst']) ? $_POST['ngst']:'all';
    
            $this->data['attached']='container';
            $this->data['st']=$st;
            $this->data['stval']=$stval;
            $this->data['ngst']=$ngst;
            $this->data['menu'] = '전화번호관리';
            $this->data['submenu'] = '번호파일'; 

            $this->load->view('templates/header', $this->data);
            $this->load->view('templates/nav', $this->data);
            $this->load->view('phone/phone_file', $this->data);
            $this->load->view('templates/nav-footer', $this->data);
            $this->load->view('templates/scripts', $this->data);
            $this->load->view('templates/footer', $this->data);
        }
        else {
            ?>
            <script type="text/javascript">                
                alert("로그인상태에서만 이용하실수 있습니다.");
                base_url = "<?=$GLOBALS['protocol']?>://" + location.host + "/index";
                window.location = base_url;
            </script>
            <?php  
        }    
    }

    public function sendPhoneNumber()
    {
        if (is_signed()) {            
            $userid = get_session_user_id();
            $userauth= get_session_user_level();
            if($userauth ==="") {
                $userauth = "";
                $userid = -1;
            }            
            $this->data['user_level'] = $userauth;
    
            $this->data['menu'] = '전화번호관리';
            $this->data['submenu'] = '발신번호'; 
            $this->data['group_data'] = $this->groups_model->get_group($userid);
            //발신번호를 적재
            $condition = array(
                'user_id' => $userid,
            );
            $this->data['senderPhoneList'] = $this->senderphone_model->get_data($condition);

            $this->load->view('templates/header', $this->data);
            $this->load->view('templates/nav', $this->data);
            $this->load->view('phone/sendphone_number', $this->data);
            $this->load->view('templates/nav-footer', $this->data);
            $this->load->view('templates/scripts', $this->data);
            $this->load->view('templates/footer', $this->data);
        }
        else {
            ?>
            <script type="text/javascript">                
                alert("로그인상태에서만 이용하실수 있습니다.");
                base_url = "<?=$GLOBALS['protocol']?>://" + location.host + "/index";
                window.location = base_url;
            </script>
            <?php  
        }    
    }

	public function upload_file()
    {
        $user_uid = get_session_user_uid();

        $filesize = 0;
        $filename_origin = '';
        $filename = '';
        $filename_tmp = '';
        $filepath = '';

        $datetime = date('Y-m-d H:i:s');

        $uid = sprintf('%s-%s', date('YmdHis'), $user_uid);

        if (isset($_FILES) && sizeof($_FILES) > 0) {
        	

            if ($_FILES['file']['error'] == 0) {
            	

                $filename_origin = $_FILES['file']['name'];
                $filesize = $_FILES['file']['size'];

                $fileext = my_get_extension($filename_origin);
                 
                $base_path = $this->input->server('DOCUMENT_ROOT') . base_url();
               
 
                $upload_path = $base_path . 'uploads/tmp';
                $dir_path = $upload_path;
//                $dir_path = sprintf('%s/%s/%s', $upload_path, date('Y'), date('m'));
                $filepath = $dir_path . '/' . $uid . $fileext;
                $filename = $uid . $fileext;

                if (!is_dir($dir_path)) {
                    my_mkdir($dir_path);
                }

                if (is_dir($dir_path) && is_uploaded_file($_FILES['file']['tmp_name'])) {
                    move_uploaded_file($_FILES['file']['tmp_name'], $filepath);
                }
            }
            else {
                $response = array(
                    'status' => 'upload_error',
                    'msg' => '오류가 발생하였습니다.'
                );
                echo json_encode($response);
                exit;
            }
        }

        $response = array(
            'status' => 'OK',
            'msg' => '성공',
            'file_name' => $filename,
            'origin_file_name' => $filename_origin
        );
        echo json_encode($response);
    }

	public function post()
    {
    	$this->load->library('excel');
        $user_id = get_session_user_id();
        $duplicate_count = 0;

        $attached_file_name = $this->input->get_post('attached_file_name');
        $attached_origin_file_name = $this->input->get_post('attached_origin_file_name');
		$groups = $this->input->get_post('groups');

        $base_path = $this->input->server('DOCUMENT_ROOT') . base_url();
        $upload_path = $base_path . 'uploads/tmp';
		$filepath = $upload_path . '/' . $attached_file_name;
        if (!file_exists($filepath)) 
        {
				$response = array(
                    'status' => 'post_error',
                    'msg' => '오류가 발생하였습니다.'
                );
                echo json_encode($response);
                exit;
        }
        else 
        {
			try {
			    $inputFileType = PHPExcel_IOFactory::identify($filepath);
			    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
			    $objPHPExcel = $objReader->load($filepath);
			} catch(Exception $e) {
			    $response = array(
                    'status' => 'post_error',
                    'msg' => '오류가 발생하였습니다.'
                );
                @unlink($filepath);
                echo json_encode($response);
                exit;
			}

			//  Get worksheet dimensions
			$sheet = $objPHPExcel->getSheet(0); 
			$highestRow = $sheet->getHighestRow(); 
			$highestColumn = $sheet->getHighestColumn();

    		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
            $error_count = 0;
    		$val = '';
			//  Loop through each row of the worksheet in turn


            $i=1;
            $count = 0;
            $total_count = 0;
            $error_mobile = "";
            foreach ($sheet->getRowIterator() as $row) {

                $column_A_Value = $sheet->getCell("A$i")->getValue();//column A
                $column_B_Value = $sheet->getCell("B$i")->getValue();
                //you can add your own columns B, C, D etc.


                if ($column_A_Value != '' && $column_B_Value  != '')
                {
                    //inset $column_A_Value value in DB query here

                        $val .= ($column_A_Value . " " . $column_B_Value . "\r\n");


                        $username = $column_A_Value;
                        $mobile = str_replace('-', '', $column_B_Value);
                        $address_num = '';
                        $memo = '';
                    if($this->checkPhoneNumber($mobile)) {
                        $para = array(
                            'userid' => $user_id,
                            'groups' => $groups,
                            'username' => $username,
                            'mobile' => $mobile,
                            'address_num' => $address_num,
                            'memo1' => $memo
                        );
                        $query = $this->db->get_where('mobiles', array(//making selection
                            'mobile' => $mobile,
                            'user_id' => $user_id,
                            'group_id' => $groups
                        ));
                        $a_count = $query->num_rows(); //counting result from query
                        if ($a_count < 1) {

                            // if($what=='reg')
                            $flag = $this->mobiles_model->insertAddUserInMobile($para);
                            if ($flag > 0)
                                $count++;
                        } else {
                            $duplicate_count++;
                        }
                        /*else {
                            $para = array(

                                'group_id' => $groups,
                                'name' => $username,
                                'address_num' => $address_num,
                                'memo1' => $memo
                            );
                            $where = array(
                                'mobile' => $mobile,
                                'user_id' => $user_id
                            );

                            $this->db->where($where);
                            $this->db->update('mobiles', $para);

                            $count++;
                        }*/

                    } else {
                        $error_mobile .= "'".$column_B_Value."', ";
                        $error_count ++;
                    }
                    $total_count++;
                }
                $i++;

            }
            if(($error_count+$duplicate_count) === 0) {
                    $response=$total_count.'개중 '.$count .'개가 등록되었습니다. @';
            } else {
                if($error_mobile !="") {
                    $error_mobile = substr($error_mobile, 0, strlen($error_mobile) - 2).": 형식오류, ";
                    if($duplicate_count > 0){
                        $response = $total_count.'개중 '.$count .'개가 등록되었습니다. @ '.$error_mobile.'증복번호 : '.$duplicate_count."개";
                    }else {
                        $response = $total_count.'개중 '.$count .'개가 등록되었습니다. @ '.$error_mobile;
                    }
                }else {
                    if($duplicate_count > 0){
                        $response = $total_count.'개중 '.$count .'개가 등록되었습니다. @ 증복번호 : '.$duplicate_count."개";
                    }else {
                        $response=$total_count.'개중 '.$count .'개가 등록되었습니다. @';
                    }
                }


            }
            @unlink($filepath);
	        echo $response;
        }		
    }

    public function checkPhoneNumber($phoneNumber){
        //유선번호목록
        $checkResult = false;
        $phoneFilterList = [
            //이동통신전화번호
            ['010',7],['011',7],['016',7],['017',7],['018',7],['019',7],
            ['010',8],['011',8],['016',8],['017',8],['018',8],['019',8],
        ];

        foreach($phoneFilterList as $phone) {
            if (substr($phoneNumber,0, strlen($phone[0])) === $phone[0] && strlen($phoneNumber) === (strlen($phone[0]) + $phone[1])) {

                return true;
            };
        }
        return $checkResult;
    }

    public function phoneNumber()
    {
        if (is_signed()) {

            $this->data['scripts'][14] = 'include/js/phonenumber.js';

            $st = isset($_GET['st']) ? $_GET['st']:'all';
            $stval = isset($_GET['stval']) ? $_GET['stval']:'';
            $ngst=isset($_GET['ngst']) ? $_GET['ngst']:'all';
            $page =isset($_GET['page']) ? $_GET['page']:0;
    
            $userid = get_session_user_id();
            $userauth= get_session_user_level();
            if($userauth ==="") {
                $userauth = "";
                $userid = -1;
            }
            $this->data['user_level'] = $userauth;
    
            $this->data['st']=$st;
            $this->data['stval']=$stval;
            $this->data['ngst']=$ngst;
            $this->data['userid']=$userid;
            $this->data['page']=$page;
            $this->data['groups'] =  $this->groups_model->get_GroupByUserId($userid);    
            $this->data['mobiles'] = $this->mobiles_model->get_Mobiles($userid,$page, $st, $stval, $ngst);
    
            $this->data['phonenumberCont'] =  $this->mobiles_model->get_total_count($userid, $st, $stval, $ngst)[0]['total_count'];
            $this->data['menu'] = '전화번호관리';
            $this->data['submenu'] = '등록번호'; 
    
            $this->load->view('templates/header', $this->data);
            $this->load->view('templates/nav', $this->data);
            $this->load->view('phone/phone_number', $this->data);
            $this->load->view('templates/nav-footer', $this->data);
            $this->load->view('templates/scripts', $this->data);
            $this->load->view('templates/footer', $this->data);            
        }
        else {
            ?>
            <script type="text/javascript">                
                alert("로그인상태에서만 이용하실수 있습니다.");
                base_url = "<?=$GLOBALS['protocol']?>://" + location.host + "/index";
                window.location = base_url;
            </script>
            <?php  
        }    
    }
	
	public function getPhoneNumberList()
	{
		$st = isset($_POST['st']) ? $_POST['st']:'all';
		$stval = isset($_POST['stval']) ? $_POST['stval']:'';
		$ngst=isset($_POST['ngst']) ? $_POST['ngst']:'all';
        $page =isset($_POST['page']) ? $_POST['page']:0;
        $count =isset($_POST['count']) ? $_POST['count']:10;
        $userid = get_session_user_id();
        $userauth= get_session_user_level();
        if($userauth ==="") {
            $userauth = "";
            $userid = -1;
        }

        $this->data['user_level'] = $userauth;
		$this->data['st']=$st;
		$this->data['stval']=$stval;
		$this->data['ngst']=$ngst;
        $this->data['page']=$page;
        $this->data['mobiles'] = $this->mobiles_model->get_Mobiles($userid,$page, $st, $stval, $ngst,$count);
        $this->data['phonenumberCont'] =  $this->mobiles_model->get_total_count($userid, $st, $stval, $ngst)[0]['total_count'];
        $this->load->view('phone/getphonenumberList', $this->data);        
    }
    
	public function getPagniation(){
        $total_count = $this->input->get_post('total_count');
        $cur_page = $this->input->get_post('cur_page');
        $this->load->view('templates/pagination', array('total_count' => $total_count, 'cur_page' => $cur_page));
    }

//===그룹추가에 사용자추가, 수정, 페지=====//
	public function addPhoneNum()
	{
	    $userid=get_session_user_id();
		$this->data['userid']=$userid;
        $st = isset($_POST['st']) ? $_POST['st']:'all';
        $stval = isset($_GET['stval']) ? $_POST['stval']:'';
        $ngst=isset($_POST['ngst']) ? $_POST['ngst']:'all';

        $this->data['st']=$st;
        $this->data['stval']=$stval;
        $this->data['ngst']=$ngst;

		$what = isset($_POST['what']) ? $_POST['what']:'reg';
		$this->data['what']=$what;

		$this->data['userMobiledata']="";
		$this->data['mobileid']=0;

        if($what=='reg')
		 $this->data['groups'] =  $this->groups_model->get_GroupByUserId($userid);
        else//수정으로 들어왔다면
		if($what=='chg')
		{
		  $mid = isset($_POST['mid']) ? $_POST['mid']:0;
		  $this->data['mobileid']=$mid;
		  
		  $this->data['groups'] =  $this->mobiles_model->get_UserGroupDataByMobileId($mid);
          $this->data['userMobiledata'] =  $this->mobiles_model->get_UserMobileDataByMobileId($userid,$mid);
		}
		
	    $this->load->view('templates/header', $this->data);
        $this->load->view('templates/nav', $this->data);
        $this->load->view('phone/add_phone_num', $this->data);
        $this->load->view('templates/nav-footer', $this->data);
        $this->load->view('templates/scripts', $this->data);
       $this->load->view('templates/footer', $this->data);
	}

//=========== 사용자추가하기===========//
	public function addMobileUsr()
    {
        $userid = get_session_user_id();
        if($userid > 0) {
//		$userid = 1;
            $groups = isset($_POST['groups']) ? $_POST['groups'] : '';
            $username = isset($_POST['username']) ? $_POST['username'] : '';
            $mobile = isset($_POST['mobile']) ? $_POST['mobile'] : 0;
            $address_num = isset($_POST['address_num']) ? $_POST['address_num'] : '';
            $memo = isset($_POST['memo']) ? $_POST['memo'] : '';

            if($groups==="") {
                $addstr = '미분류';
                $memo1 ="";

                $query = $this->db->get_where('groups', array(//making selection
                    'name' => $addstr,
                    'user_id' => $userid
                ));
                $count = $query->num_rows(); //counting result from query
                if ($count < 1) {
                    $groups = $this->groups_model->insert_Group($addstr, $memo1, $userid);
                }else {
                    $groups = $query->result_array()[0]['id'];
                }

            }

            $para = array(
                'userid' => $userid,
                'groups' => $groups,
                'username' => $username,
                'mobile' => $mobile,
                'address_num' => $address_num,
                'memo1' => $memo
            );
            //증복검사
            $result = $this->mobiles_model->check_repeat_mobile($userid, $mobile,$groups);
            if ($result === "") {
                $flag = $this->mobiles_model->insertAddUserInMobile($para);
                if ($flag > 0)
                    echo "ok";
                else
                    echo "err";

                exit;
            } else {
                echo $result;
            }
        }else {
            echo "";
        }

        
	 //	echo "groups=".$groups." name=".$username." mobile=".$mobile." memo1=".$memo1." memo2=".$memo2;exit;
	 //echo "error";exit;

	}
	
	public function deletemobile()
	{
	    $result = "";
		$userid=get_session_user_id();
//		$userid = 1;
        if($userid > 0) {
            $mobile_ids = $this->input->get_post('selected');
            foreach ($mobile_ids as $mobile_id) {
                $flag = $this->mobiles_model->deletemobileById($mobile_id);
            }
            $result = "ok";
        }else {
            $result = "회원가입을 하여야 합니다.";
        }

        echo $result;
		exit;
	}
	
	public function tochangemobile()
	{
		$userid=get_session_user_id();
//		 $userid = 1;
	     $mobileid = isset($_GET['mid']) ? $_GET['mid']:0;
		 $gid = isset($_GET['gid']) ? $_GET['gid']:0;
		 
		 $this->data['groups'] =  $this->groups_model->get_GroupByUserId($userid);//$this->mobiles_model->get_UserGroupDataByMobileId($mobileid);
         $this->data['userMobiledata'] =  $this->mobiles_model->get_UserMobileDataByMobileId($userid,$mobileid);
		 $this->data['what']='chg';
		 $this->data['mobileid']=$mobileid;
		 $this->data['gid']=$gid;
		 
		 $this->load->view('phone/add_phone_num', $this->data);
	}
	
	public function setMobileUser()
	{
		$userid=get_session_user_id();
//		$userid=1;

	    $groups = $this->input->get_post('groups');
		$username = $this->input->get_post('username');
		$mobile = $this->input->get_post('mobile');
		$address_num = $this->input->get_post('address_num');
		$memo = $this->input->get_post('memo');
		$moid = $this->input->get_post('mid');
		$gid = $this->input->get_post('gid');
		
		
		$params =array(
		    'userid' => $userid,
		    'groupid' => $groups,
			'username' => $username,
			'mobile' => $mobile,
			'address_num' => $address_num,
			'memo1' => $memo,
			'moid'=>$moid,
			'gid'=>$gid
		);

        $flag =  $this->mobiles_model->changeMobileInfoByUserId($params);

		if($flag > 0)
			echo "ok";
		else
			echo "err";
		
		exit;

	}
}