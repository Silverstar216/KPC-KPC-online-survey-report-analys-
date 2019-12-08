<?php

/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 8/9/15
 * Time: 4:34 PM
 */

class Educations_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'education_schedule';
    }
 
    /*
     * 교육과정목록들을 돌려주는 함수
     * page: 현재페지
     * per_page  :  페지당 개수
     * */
    public function get_education_schedules($user_name, $searchdata) {
        $page = $searchdata['my_page'];
        $per_page = $searchdata['my_per_page'];
        $begintime = $searchdata['survey_begindate'];
        $endtime = $searchdata['survey_enddate'];
        $coursename = $searchdata['survey_course'];
        $groupname = $searchdata['survey_groupname'];
        $survey_flag = $searchdata['survey_flag'];
        $survey_customer = $searchdata['survey_customer'];
        $survey_count = $searchdata['survey_count'];
        $empseq = $searchdata['empseq'];
        $is_landing = $searchdata['is_landing'];
        $education_type = $searchdata['education_type'];

        $begintime = str_replace("-", "", $begintime);
        $endtime = str_replace("-", "", $endtime);

        if($page > 1) {
            $page = ($page-1)*$per_page;
        } else {
            $page = 0;
        }               

        $serverName = $GLOBALS['erp_mssql_addr']; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array( "Database"=>$GLOBALS['erp_mssql_db'], 
                    "UID"=>$GLOBALS['erp_mssql_uid'], 
                    "PWD"=>$GLOBALS['erp_mssql_pwd']);
        $conn = sqlsrv_connect( $serverName, $connectionInfo);
        
        $result = array();
        if( $conn ) {          
            $sql = "WITH Results_CTE AS (";
            $sql .= "SELECT MyTable.Serl as id, VI_EduCourse.UMCourseTypeName as main_type, MyTable.UMEduThemeName as sub_type, 0 as is_public, MyTable.EduSeq as count_name, MyTable.BegDate as begin_date, MyTable.EndDate as end_date, '' as teachers_name, '0' as student_count, VI_EduCourse.EmpSeq as manager_id, ";
            $sql .= "VI_EduCourse.DeptName as subject_group, VI_EduCourse.ProcCourseName as subject_name, ";
            if ($survey_flag != 1) {
                $sql .= "CourseClient.CustName as customer, ";
            }
            $sql .= "ROW_NUMBER() OVER (ORDER BY MyTable.BegDate, MyTable.Serl) AS RowNum ";
            $sql .= "FROM (SELECT Serl, CourseSeq, YY, EduSeq, BegDate, EndDate, UMEduThemeName FROM VI_EduCourseLecture ";
            $sql .= "WHERE BegDate >= '".$begintime."' AND BegDate <= '". $endtime."' ";
            $sql .= "GROUP BY CourseSeq, Serl, YY, EduSeq, BegDate, EndDate, UMEduThemeName) AS MyTable ";
            $sql .= "LEFT JOIN VI_EduCourse ON MyTable.Serl = VI_EduCourse.Serl ";
            if ($survey_flag != 1) {
                $sql .= "LEFT JOIN (SELECT Serl, CustName FROM VI_EduCourseClient GROUP BY Serl, CustName) AS CourseClient ON MyTable.Serl = CourseClient.Serl ";
            }
            $sql .= "WHERE ";
            if ($survey_flag == 1 ) {
                // 공개교육과정 : ERP 디비에서 공개/공개(특수)/수탁 만 불러온다.
                $sql .= "VI_EduCourse.UMCourseTypeName like '%공개%' ";
            }
            else {
                // 맞춤형교육과정 : ERP 디비에서 공개/공개(특수)/수탁 아닌것만 불러온다.
                $sql .= "VI_EduCourse.UMCourseTypeName not like '%공개%' ";
            }
            if ($user_name != "all") {
                if($is_landing == "1")
                    $sql .= "AND VI_EduCourse.EmpSeq=".$empseq;
                else
                    $sql .= "AND VI_EduCourse.EmpName='".$user_name."' ";
            }
            if ($coursename != "") {
                $sql .= " AND VI_EduCourse.ProcCourseName like '%".$coursename."%' ";
            }  
            if ($education_type != "") {
                $sql .= " AND VI_EduCourse.UMEduTypeName like '%".$education_type."%' ";
            }  
            if ($groupname != "") {
                $sql .= " AND VI_EduCourse.DeptName like '%".$groupname."%' ";
            }              
            if ($survey_flag != 1 && $survey_customer != "") {
                $sql .= " AND CourseClient.CustName like '%".$survey_customer."%' ";
            }
            if ($survey_count != "") {
                $sql .= " AND MyTable.EduSeq = '".$survey_count."' ";
            }
            $sql .= ") SELECT * FROM Results_CTE WHERE RowNum >= ".($page + 1);
            $sql .= " AND RowNum < ".($page + $per_page + 1);
                     
            $params = array();
            $options =  array( "Scrollable" => "Keyset" );
            $stmt = sqlsrv_query( $conn, $sql, $params, $options);
            $row_count = null;
            
            if($stmt == null){
                sqlsrv_close($conn);   
                return $result;
            }else {
                $row_count = sqlsrv_num_rows( $stmt );      
                if ($row_count === false) {
                    print_r(sqlsrv_errors());
                }
                while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC ) ) {    
                    $data = array();
                    $data['id'] = $row['id'];
                    $data['main_type'] = $row['main_type'];
                    $data['sub_type'] = $row['sub_type'];
                    $data['is_public'] = $row['is_public'];
                    $data['count_name'] = $row['count_name'];
                    $data['begin_date'] = $row['begin_date'];
                    $data['end_date'] = $row['end_date'];
                    $data['teachers_name'] = $row['teachers_name'];
                    $data['student_count'] = $row['student_count'];
                    $data['manager_id'] = $row['manager_id'];
                    $data['subject_group'] = $row['subject_group'];
                    $data['subject_name'] = $row['subject_name'];
                    if ($survey_flag != 1) {
                        $data['customer'] = $row['customer'];
                    }
                    $result[]= $data;
                }                
            }
            sqlsrv_free_stmt($stmt);
            sqlsrv_close($conn);    
        }

        for ($i = 0; $i < count($result); $i++) {           
            $result[$i]['student_count'] = $this->get_education_students_count_fromid($result[$i]['id']); 
        }

        return $result;
    }
    //userId로부터 EmpSeq얻기
    public function get_empseq_from_userid($userid){
        $result = array();

        $serverName = $GLOBALS['erp_mssql_addr']; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array( "Database"=>$GLOBALS['erp_mssql_db'],
            "UID"=>$GLOBALS['erp_mssql_uid'],
            "PWD"=>$GLOBALS['erp_mssql_pwd']);
        $conn = sqlsrv_connect( $serverName, $connectionInfo);
        $empseq = '';

        if( $conn ) {
            $sql =  "SELECT EmpSeq ";
            $sql .= "FROM VI_EduManager ";
            $sql .= "WHERE UserId='".$userid."'";

            $params = array();
            $options =  array( "Scrollable" => "Keyset" );
            $stmt = sqlsrv_query( $conn, $sql, $params, $options);

            while( $row = sqlsrv_fetch_array( $stmt) ) {
                $empseq = $row['EmpSeq'];

                break;
            }

            sqlsrv_close($conn);
        }

        return $empseq;
    }
    //선택된 교육과정의 고객사정보를 돌려주는 함수
    public function get_education_customer_fromid($education_id) {
        $result = array();

        $serverName = $GLOBALS['erp_mssql_addr']; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array( "Database"=>$GLOBALS['erp_mssql_db'],
            "UID"=>$GLOBALS['erp_mssql_uid'],
            "PWD"=>$GLOBALS['erp_mssql_pwd']);
        $conn = sqlsrv_connect( $serverName, $connectionInfo);
        $Customer = '';

        if( $conn ) {
            $sql =  "SELECT CustName ";
            $sql .= "FROM VI_EduCourseClient ";
            $sql .= "WHERE Serl='".$education_id."'";

            $params = array();
            $options =  array( "Scrollable" => "Keyset" );
            $stmt = sqlsrv_query( $conn, $sql, $params, $options);

            while( $row = sqlsrv_fetch_array( $stmt) ) {
                $Customer = $row['CustName'];

                break;
            }

            sqlsrv_close($conn);
        }

        return $Customer;
    }
        
    /*
     * 교육과정목록총개수돌려주는 함수
     * */
    public function get_education_schedules_total_count($user_name, $searchdata){
        $begintime = $searchdata['survey_begindate'];
        $endtime = $searchdata['survey_enddate'];
        $coursename = $searchdata['survey_course'];
        $groupname = $searchdata['survey_groupname'];
        $survey_flag = $searchdata['survey_flag'];
        $survey_customer = $searchdata['survey_customer'];
        $survey_count = $searchdata['survey_count'];
        $empseq = $searchdata['empseq'];
        $is_landing = $searchdata['is_landing'];
        $education_type = $searchdata['education_type'];
        
        $begintime = str_replace("-", "", $begintime);
        $endtime = str_replace("-", "", $endtime);
        
        $serverName = $GLOBALS['erp_mssql_addr']; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array( "Database"=>$GLOBALS['erp_mssql_db'], 
                    "UID"=>$GLOBALS['erp_mssql_uid'], 
                    "PWD"=>$GLOBALS['erp_mssql_pwd']);
        $conn = sqlsrv_connect( $serverName, $connectionInfo);
        $row_count = 0;

        if( $conn ) {            
            $sql =  "SELECT MyTable.Serl ";
            $sql .= "FROM (SELECT Serl, EduSeq FROM VI_EduCourseLecture ";
            $sql .= "WHERE BegDate >= '".$begintime."' AND BegDate <= '". $endtime."' ";
            $sql .= "GROUP BY CourseSeq, Serl, YY, EduSeq, BegDate, EndDate, UMEduThemeName) AS MyTable ";            
            $sql .= "LEFT JOIN VI_EduCourse ON MyTable.Serl = VI_EduCourse.Serl ";
            if ($survey_flag != 1 ) {
                $sql .= "LEFT JOIN (SELECT Serl, CustName FROM VI_EduCourseClient GROUP BY Serl, CustName) AS CourseClient ON MyTable.Serl = CourseClient.Serl ";
            }
            $sql .= "WHERE ";
            if ($survey_flag == 1 ) {
                // 공개교육과정 : ERP 디비에서 공개/공개(특수)/수탁 만 불러온다.
                $sql .= "VI_EduCourse.UMCourseTypeName like '%공개%' ";
            }
            else {
                // 맞춤형교육과정 : ERP 디비에서 공개/공개(특수)/수탁 아닌것만 불러온다.
                $sql .= "VI_EduCourse.UMCourseTypeName not like '%공개%' ";
            }
            if ($user_name != "all") {
                if($is_landing == "1")
                    $sql .= "AND VI_EduCourse.EmpSeq=".$empseq;
                else
                    $sql .= "AND VI_EduCourse.EmpName='".$user_name."' ";
            }
            if ($coursename != "") {
                $sql .= " AND VI_EduCourse.ProcCourseName like '%".$coursename."%' ";
            }  
            if ($education_type != "") {
                $sql .= " AND VI_EduCourse.UMCourseTypeName like '%".$education_type."%' ";
            }  
            if ($groupname != "") {
                $sql .= " AND VI_EduCourse.DeptName like '%".$groupname."%' ";
            }              
            if ($survey_flag != 1 && $survey_customer != "") {
                $sql .= " AND CourseClient.CustName like '%".$survey_customer."%' ";
            }
            if ($survey_count != "") {
                $sql .= " AND MyTable.EduSeq = '".$survey_count."' ";
            }
            $params = array();
            $options =  array( "Scrollable" => "Keyset" );
            $stmt = sqlsrv_query( $conn, $sql, $params, $options);

            $row_count = null;

            if($stmt == null){
                sqlsrv_close($conn);    
                return 0;
            }else 
                $row_count = sqlsrv_num_rows( $stmt );

            sqlsrv_close($conn);    
        }
        return $row_count;
    }
    
    /*
    * 선택된 교육과정을 돌려주는 함수
    * */
    public function get_education_schedule_fromid($education_id) {
        $result = array();

        $serverName = $GLOBALS['erp_mssql_addr']; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array( "Database"=>$GLOBALS['erp_mssql_db'], 
                    "UID"=>$GLOBALS['erp_mssql_uid'], 
                    "PWD"=>$GLOBALS['erp_mssql_pwd']);
        $conn = sqlsrv_connect( $serverName, $connectionInfo);
        $row_count = 0;

        if( $conn ) {            
            $sql =  "SELECT Serl as id, EduSeq as count_name, '' as teachers_name, 0 as student_count, 0 as teaching_hours, ";
            $sql .= "BegDate as begin_date, EndDate as end_date, ProcCourseName as subject_name ";
            $sql .= "FROM VI_EduCourse ";
            $sql .= "WHERE Serl='".$education_id."'";

            $params = array();
            $options =  array( "Scrollable" => "Keyset" );
            $stmt = sqlsrv_query( $conn, $sql, $params, $options);

            while( $row = sqlsrv_fetch_array( $stmt) ) {    
                $data = array();
                $data['id'] = $row['id'];
                $data['count_name'] = $row['count_name'];
                $data['begin_date'] = $row['begin_date'];
                $data['end_date'] = $row['end_date'];
                $data['teachers_name'] = $row['teachers_name'];
                $data['student_count'] = $row['student_count'];
                $data['subject_name'] = $row['subject_name'];

                $result[]= $data;
                break;
            }
            
            sqlsrv_close($conn);    
        }

        if (count($result) > 0) { 
            $result[0]['customer_name'] = $this->get_education_customer_fromid($result[0]['id']);           
            $result[0]['teachers_name'] = $this->get_education_teachers_fromid($result[0]['id']); 
            $result[0]['student_count'] = $this->get_education_students_count_fromid($result[0]['id']); 
        }

        return $result;
    }

    /*
    * 선택된 교육과정에 해당한 수강생목록을 돌려주는 함수
    * */
    public function get_education_students_fromid($education_id) {
        $result = null;
        
        $serverName = $GLOBALS['erp_mssql_addr']; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array( "Database"=>$GLOBALS['erp_mssql_db'], 
                    "UID"=>$GLOBALS['erp_mssql_uid'], 
                    "PWD"=>$GLOBALS['erp_mssql_pwd']);
        $conn = sqlsrv_connect( $serverName, $connectionInfo);
        
        if( $conn ) {            
            $params = array();
            $options =  array( "Scrollable" => "Keyset" );
            if ($education_id != "0") {
                $sql =  "SELECT ClientSeq, EduName, CellPhoneNo FROM VI_EduCourseClient ";
                $sql .= "WHERE Serl=" . $education_id;
    
                $stmt = sqlsrv_query( $conn, $sql, $params, $options);            
                $result = array();
                while( $row = sqlsrv_fetch_object($stmt) ) 
                {
                    $data = array();
                    $data['id'] = $row->ClientSeq;
                    $data['name'] = $row->EduName;
                    $data['mobile'] = $row->CellPhoneNo;
    
                    $result[]= $data;
                }
            }

            sqlsrv_close($conn);      
        }

        return $result;
    }

    /*
    * 선택된 교육과정에 해당한 수강생목록을 돌려주는 함수
    * */
    public function get_education_students_count_fromid($education_id) {
        $students = "0";

        $serverName = $GLOBALS['erp_mssql_addr']; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array( "Database"=>$GLOBALS['erp_mssql_db'], 
                    "UID"=>$GLOBALS['erp_mssql_uid'], 
                    "PWD"=>$GLOBALS['erp_mssql_pwd']);
        $conn = sqlsrv_connect( $serverName, $connectionInfo);
        
        if( $conn ) {          
            $params = array();
            $options =  array( "Scrollable" => "Keyset" );

            if ($education_id != "0") {
                $sql =  "SELECT Count(*) as Students FROM VI_EduCourseClient ";
                $sql .= "WHERE Serl=" . $education_id;

                $stmt = sqlsrv_query( $conn, $sql, $params, $options);

                while( $row = sqlsrv_fetch_object($stmt) ) 
                {
                    $students = $row->Students;
                    break;
                }
                sqlsrv_close($conn);      
            }
            
        }

        return $students;
    }

    /*
    * 선택된 교육과정에 해당한 강사목록을 돌려주는 함수
    * */
    public function get_education_teachers_fromid($education_id) {
        $teachers = "";

        $serverName = $GLOBALS['erp_mssql_addr']; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array( "Database"=>$GLOBALS['erp_mssql_db'], 
                    "UID"=>$GLOBALS['erp_mssql_uid'], 
                    "PWD"=>$GLOBALS['erp_mssql_pwd']);
        $conn = sqlsrv_connect( $serverName, $connectionInfo);
        
        if( $conn ) {          
            $params = array();
            $options =  array( "Scrollable" => "Keyset" );

            if ($education_id != "0") {
                $sql =  "SELECT LecturerName FROM VI_EduCourseLecture ";
                $sql .= "WHERE Serl=" . $education_id;

                $stmt = sqlsrv_query( $conn, $sql, $params, $options);

                while( $row = sqlsrv_fetch_object($stmt) ) 
                {
                    $teachers .= ($row->LecturerName . " ");
                }
                sqlsrv_close($conn);      
            }
            
        }

        return $teachers;
    }

    /*
    맞춤형교육을 위한 Excel ERP 파일저장
    */
    public function set_education_excel($userid, $erp_excel_path) {
        $result = null;

        $sql = "select * from education_excel";
        $sql .= " where userid = ".$userid;
                
        $query = $this->db->query($sql);
        $result = $query->result_array();

        if (count($result) == 0) {
            $sql = "insert into education_excel (userid, erp_excel_path) values ('";
            $sql .= $userid."', '".$erp_excel_path."')";
                                
            $query = $this->db->query($sql);
        }
        else {
            $sql = "update education_excel set";
            $sql .= " erp_excel_path='".$erp_excel_path."'";
            $sql .= " where userid='".$userid."'";
                                            
            $query = $this->db->query($sql);
        }
        return $result;
    }

    public function get_education_excel($userid) {
        $result = null;

        $sql = "select * from education_excel";
        $sql .= " where userid = ".$userid;
                
        $query = $this->db->query($sql);
        $result = $query->result_array();

        if (count($result) > 0) {
            return $result[0]['erp_excel_path'];
        }

        return "";
    }

    public function get_education_schedules_old($user_id, $searchdata) {
        $page = $searchdata['my_page'];
        $per_page = $searchdata['my_per_page'];
        $begintime = $searchdata['survey_begindate'];
        $endtime = $searchdata['survey_enddate'];
        $coursename = $searchdata['survey_course'];
        
        if($page > 1) {
            $page = ($page-1)*$per_page;
        } else {
            $page = 0;
        }        

        $sql = "SELECT education_schedule.*, education_subject.subject_group, education_subject.subject_name ";
        $sql .= " FROM education_schedule";
        $sql .= " LEFT JOIN education_subject ON education_schedule.subject_id = education_subject.subject_id";        
        $sql .= " WHERE manager_id = ".$user_id;
        $sql .= " AND begin_date >= '".$begintime."' AND begin_date <= '".$endtime."'";
        if ($coursename != "") {
            $sql .= " AND education_subject.subject_name like '%".$coursename."%'";
        }        
        $sql .= " ORDER BY education_schedule.subject_id, count_name, begin_date ASC";
        $sql .= " LIMIT ".$page.", ".$per_page;

       
        $query = $this->db->query($sql);
        $result = $query->result_array();

        for ($i = 0; $i < count($result); $i++) {
            $result[$i]['student_count'] = $this->get_education_students_count_fromid($result[$i]['id']); 
        }

        return $result;
    }

    public function get_education_schedule_from_params($params) {
        $sql = "SELECT education_schedule.*, education_subject.subject_group, education_subject.subject_name";
        $sql .= " FROM education_schedule";
        $sql .= " LEFT JOIN education_subject on education_schedule.subject_id = education_subject.subject_id";
        $sql .= " WHERE education_schedule.subject_id=".$params['subject_id'];
        $sql .= " and count_name = '".$params['count_name']."'";
        $sql .= " and begin_date = '".$params['begin_date']."'";
        $sql .= " and end_date = '".$params['end_date']."'";
        $sql .= " limit 1";

        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result;
    }    

    public function get_education_schedule_fromid_old($education_id) {
        $result = null;

        $sql = "select id, count_name, teachers_name, student_count, teaching_hours, begin_date, end_date, subject_name";
        $sql .= " from education_schedule, education_subject";
        $sql .= " where id = ".$education_id;
        $sql .= " and education_subject.subject_id = education_schedule.subject_id";

                
        $query = $this->db->query($sql);
        $result = $query->result_array();

        if (count($result) > 0) {
            $result[0]['student_count'] = $this->get_education_students_count_fromid($result[0]['id']); 
        }

        return $result;
    }
}