<?php

/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 8/9/15
 * Time: 4:34 PM
 */
class Users_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'g5_member';
    }

    public function encode_password($value){
        $sql = "select password('$value') as pass";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result[0]['pass'];
    }

    public function update_from_erp_mssql()
    {                
        $bResult = false;
        $serverName = $GLOBALS['erp_mssql_addr']; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array( "Database"=>$GLOBALS['erp_mssql_db'], 
                    "UID"=>$GLOBALS['erp_mssql_uid'], 
                    "PWD"=>$GLOBALS['erp_mssql_pwd']);
        $conn = sqlsrv_connect( $serverName, $connectionInfo);
        
        if( $conn ) {            
            $sql = "SELECT * FROM VI_EduManager";            
            $params = array();
            $options =  array( "Scrollable" => "Keyset" );
            $stmt = sqlsrv_query( $conn, $sql, $params, $options);

            $row_count = sqlsrv_num_rows( $stmt );
            if ($row_count === false) {
                print_r(sqlsrv_errors());
            }
            else {
                while( $row = sqlsrv_fetch_array( $stmt) ) {                    
                    $join_no = $row['EmpSeq'];
                    $join_uid = $row['UserId'];
                    $encoded_password = $row['Password'];

                    $query = $this->db->get_where('g5_member', array(//making selection
                        'mb_id' => $join_uid
                    ));
                    $count = $query->num_rows(); //counting result from query
                    if ($count !== 0)
                    {
                        continue;
                    }

                    if ($encoded_password == '')
                        $encoded_password = $this->encode_password("1234");    
                        
                    $data = array(
                        'mb_no' => $join_no,
                        'mb_id' => $join_uid,
                        'mb_password' => $encoded_password,
                        'mb_name' => $row['EmpName'],
                        'mb_nick' => $row['DeptName'],                        
                        'mb_hp' => $row['Phone'],
                        'mb_email' => $row['Email'],
                        'mb_level'=> "2",
                        'mb_today_login' => date('Y-m-d H:i:s'),
                        'mb_datetime' => date('Y-m-d H:i:s'),
                        'mb_login_ip' => $_SERVER['REMOTE_ADDR']
                    );                    
                    $this->insert_data($data);                      
                }                   
                $bResult = true;                        
            }
            sqlsrv_close($conn);      
        }
        else {
            echo 'sqlsrv_connect: invalid';
        }

        return $bResult;
    }

    public function update_from_erp_mssql_subject()
    {        
        $serverName = $GLOBALS['erp_mssql_addr']; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array( "Database"=>$GLOBALS['erp_mssql_db'], 
                    "UID"=>$GLOBALS['erp_mssql_uid'], 
                    "PWD"=>$GLOBALS['erp_mssql_pwd']);
        $conn = sqlsrv_connect( $serverName, $connectionInfo);
        
        if( $conn ) {            
            $sql =  "SELECT CourseSeq, ProcCourseName, DeptName FROM VI_EduCourse " . 
                    "GROUP BY CourseSeq, ProcCourseName, DeptName " . 
                    "ORDER BY CourseSeq ASC";            
            $params = array();
            $options =  array( "Scrollable" => "Keyset" );
            $stmt = sqlsrv_query( $conn, $sql, $params, $options);

            $row_count = sqlsrv_num_rows( $stmt );
            if ($row_count === false) {
                print_r(sqlsrv_errors());
            }
            else {
                while( $row = sqlsrv_fetch_array( $stmt) ) {                    
                    $CourseSeq = $row['CourseSeq'];
                    $ProcCourseName = $row['ProcCourseName'];
                    $DeptName = $row['DeptName'];

                    $query = $this->db->get_where('education_subject', array(//making selection
                        'subject_id' => $CourseSeq
                    ));
                    $count = $query->num_rows(); //counting result from query
                    if ($count !== 0)
                    {
                        continue;
                    }

                    $ProcCourseName = str_replace("\"","'", $ProcCourseName);

                    $kpc_sql =  "INSERT INTO education_subject VALUES (" . 
                                $CourseSeq . ", \"" . 
                                $ProcCourseName . "\", \"" . 
                                $DeptName . "\")";
                    $query = $this->db->query($kpc_sql);
                }
            }
            sqlsrv_close($conn);    
        }
    }

    public function update_from_erp_mssql_course()
    {
        $serverName = $GLOBALS['erp_mssql_addr']; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array( "Database"=>$GLOBALS['erp_mssql_db'], 
                    "UID"=>$GLOBALS['erp_mssql_uid'], 
                    "PWD"=>$GLOBALS['erp_mssql_pwd']);
        $conn = sqlsrv_connect( $serverName, $connectionInfo);
        
        if( $conn ) {
            $sql = "SELECT MyTable.*, VI_EduCourse.EmpSeq FROM (";
            $sql .= "SELECT Serl, CourseSeq, YY, EduSeq, BegDate, EndDate, UMEduTypeName, UMEduThemeName ";
            $sql .= "FROM VI_EduCourseLecture ";
            $sql .= "WHERE BegDate >= '2019-01-01' ";
            $sql .= "GROUP BY CourseSeq, Serl, YY, EduSeq, BegDate, EndDate, UMEduTypeName, UMEduThemeName) AS MyTable, VI_EduCourse ";
            $sql .= "WHERE MyTable.Serl = VI_EduCourse.Serl";
                
            $stmt = sqlsrv_query( $conn, $sql,  array(), array('Scrollable' => SQLSRV_CURSOR_KEYSET));

            // echo $sql;
            // echo "<br>";

            // echo (sqlsrv_num_rows($stmt));
            // echo "<br>";
            while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC ) ) {   
                $id = $row['Serl'];
                $subject_id = $row['CourseSeq'];
                $yy = $row['YY'];
                $main_type = $row['UMEduTypeName'];
                $sub_type = $row['UMEduThemeName'];
                $count_name = $row['EduSeq'];
                $begin = $row['BegDate'];
                $end = $row['EndDate'];
                $manager_id = $row['EmpSeq'];

                $query = $this->db->get_where('education_schedule', array(//making selection
                    'id' => $id
                ));

                $count = $query->num_rows(); //counting result from query
                if ($count !== 0)
                {
                    continue;
                }

                $kpc_sql =  "INSERT INTO education_schedule VALUES (" . 
                            $id . ", \"" . 
                            $main_type . "\", \"" . 
                            $sub_type . "\", " . 
                            $subject_id . ", " . 
                            "0, \"" .
                            $count_name . "\", \"" . 
                            "\", \"" . 
                            "\", \"" . 
                            $begin . "\", \"" . 
                            $end . "\", \"" . 
                            "\", " . 
                            "0, " . 
                            "0, \"" . 
                            $manager_id . "\")";
                // echo $kpc_sql;
                // echo "<br>";

                $query = $this->db->query($kpc_sql);
            }
        }

    }

    public function update_from_erp_mssql_schedule()
    {        
        $serverName = $GLOBALS['erp_mssql_addr']; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array( "Database"=>$GLOBALS['erp_mssql_db'], 
                    "UID"=>$GLOBALS['erp_mssql_uid'], 
                    "PWD"=>$GLOBALS['erp_mssql_pwd']);
        $conn = sqlsrv_connect( $serverName, $connectionInfo);
        
        if( $conn ) {            
            $sql =  "SELECT VI_EduCourseClient.DtlSerl, VI_EduCourse.UMEduTypeName, VI_EduCourse.UMEduThemeName, VI_EduCourse.CourseSeq, VI_EduCourse.EduSeq, " . 
                    "VI_EduCourseClient.BegDate, VI_EduCourseClient.EndDate, VI_EduCourseLecture.LecturerName, VI_EduCourse.EmpSeq " .
                    "FROM VI_EduCourseClient " . 
                    "LEFT JOIN VI_EduCourse ON VI_EduCourseClient.CourseSeq = VI_EduCourse.CourseSeq AND VI_EduCourseClient.Serl= VI_EduCourse.Serl AND VI_EduCourseClient.YY = VI_EduCourse.YY " .
                    "LEFT JOIN VI_EduCourseLecture ON VI_EduCourseClient.CourseSeq = VI_EduCourseLecture.CourseSeq AND VI_EduCourseClient.Serl= VI_EduCourseLecture.Serl AND VI_EduCourseClient.YY = VI_EduCourseLecture.YY " .
                    "WHERE VI_EduCourseClient.BegDate >= '2019-01-01' " .
                    "ORDER BY VI_EduCourseClient.DtlSerl ASC";

            $params = array();
            $options =  array( "Scrollable" => "Keyset" );
            $stmt = sqlsrv_query( $conn, $sql, $params, $options);

            $row_count = sqlsrv_num_rows( $stmt );
            echo("cycle end:");
            echo($row_count);

            if ($row_count === false) {
                print_r(sqlsrv_errors());
            }
            else {
                $real_count = 0;
                $list_schedule = array();
                while( $row = sqlsrv_fetch_array( $stmt) ) {   
                    array_push($list_schedule, $row);
                    $real_count++;
                }
                echo(':');
                echo(count($list_schedule));
                $real_count = 0;
                foreach($list_schedule as $row) {                    
                    $id = $row['DtlSerl'];
                    $main_type = $row['UMEduTypeName'];
                    $sub_type = $row['UMEduThemeName'];
                    $subject_id = $row['CourseSeq'];
                    $count_name = $row['EduSeq'];
                    $begin = $row['BegDate'];
                    $end = $row['EndDate'];
                    $teachers_name = $row['LecturerName'];
                    $manager_id = $row['EmpSeq'];

                    $query = $this->db->get_where('education_schedule', array(//making selection
                        'id' => $id
                    ));

                    $count = $query->num_rows(); //counting result from query
                    if ($count !== 0)
                    {
                        continue;
                    }

                    $kpc_sql =  "INSERT INTO education_schedule VALUES (" . 
                                $id . ", \"" . 
                                $main_type . "\", \"" . 
                                $sub_type . "\", " . 
                                $subject_id . ", " . 
                                "0, \"" .
                                $count_name . "\", \"" . 
                                "\", \"" . 
                                "\", \"" . 
                                $begin . "\", \"" . 
                                $end . "\", \"" . 
                                $teachers_name . "\", " . 
                                "0, " . 
                                "0, \"" . 
                                $manager_id . "\")";
                    $query = $this->db->query($kpc_sql);
                    $real_count++;
                }
            }
            echo(':');
            echo($real_count);
            echo(":cycle end");            
            sqlsrv_close($conn);    
        }
    }    

    public function get_phone_number($user_id) {
        $sql = "select mb_hp,mb_tel from g5_member where id=".$user_id;
        $query = $this->db->query($sql);
        $result = $query->result_array();
        if($result[0]['mb_hp']===null || $result[0]['mb_hp']==="") {
            if($result[0]['mb_tel']===null || $result[0]['mb_tel']==="") {
                return"";
            } else {
                return $result[0]['mb_tel'];
            }
        }
        return $result[0]['mb_hp'];
    }

    public function get_user_name($user_id) {
        $sql = "select mb_name from g5_member where mb_no=".$user_id;
        $query = $this->db->query($sql);
        $result = $query->result_array();
        if (count($result) == 0 || $result[0]['mb_name'] === null) {
            return "";
        } 
        return $result[0]['mb_name'];
    }
    
    public function get_member_id_from_id($user_id){
        $sql = "select mb_id from g5_member where mb_no=".$user_id;
        $query = $this->db->query($sql);
        $result = $query->result_array();
        if (count($result) == 0 || $result[0]['mb_id'] === null) {
            return "";
        }
        return $result[0]['mb_id'];
    }

    public function get_user_id_from_name($name, $job) {

        $result = "";
        $serverName = $GLOBALS['erp_mssql_addr']; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array( "Database"=>$GLOBALS['erp_mssql_db'], 
                    "UID"=>$GLOBALS['erp_mssql_uid'], 
                    "PWD"=>$GLOBALS['erp_mssql_pwd']);
        $conn = sqlsrv_connect( $serverName, $connectionInfo);
        
        if( $conn ) {          
            $params = array();
            $options =  array( "Scrollable" => "Keyset" );

            if ($name != "") {
                $sql =  "SELECT TOP(1) EmpSeq FROM VI_EduManager ";
                $sql .= "WHERE EmpName='" . $name . "'";
                if ($job != "")
                    $sql .= " AND UMJpName='" . $job . "'";

                $stmt = sqlsrv_query( $conn, $sql, $params, $options);

                while( $row = sqlsrv_fetch_object($stmt) ) 
                {
                    $result = $row->EmpSeq;
                }
                sqlsrv_close($conn);      
            }
            
        }
        return $result;
    }

    public function get_user_group_from_id($user_id) {

        $result = "";
        $serverName = $GLOBALS['erp_mssql_addr']; //serverName\instanceName, portNumber (default is 1433)
        $connectionInfo = array( "Database"=>$GLOBALS['erp_mssql_db'], 
                    "UID"=>$GLOBALS['erp_mssql_uid'], 
                    "PWD"=>$GLOBALS['erp_mssql_pwd']);
        $conn = sqlsrv_connect( $serverName, $connectionInfo);
        
        if( $conn ) {          
            $params = array();
            $options =  array( "Scrollable" => "Keyset" );

            if ($user_id != "") {
                $sql =  "SELECT TOP(1) DeptName FROM VI_EduManager ";
                $sql .= "WHERE EmpSeq='" . $user_id . "'";
                $stmt = sqlsrv_query( $conn, $sql, $params, $options);

                while( $row = sqlsrv_fetch_object($stmt) ) 
                {
                    $result = $row->DeptName;
                }
                sqlsrv_close($conn);      
            }
            
        }
        return $result;
    }
    
    public function update_pass($user_id,$pass){
        $condition =array(
            'mb_id' => $user_id
        );
        $data = array(
            'mb_password'=>$pass,
            'mb_lost_certify'=>""
        );
        $this->update_data($data,$condition);

    }

    public function insert_lost_pass_certify($mb_no, $pass)
    {
        $result = 0;

        $data =array(
            'mb_lost_certify' => $pass


        );
        $w =array(
            'mb_no' => $mb_no


        );
          $this->update_data($data,$w);

                $this->db->select('mb_no');
                $this->db->select('mb_name');
                $this->db->select('mb_id');
                $this->db->where('mb_no', $mb_no);

                $result = $this->db->get($this->table_name);

                return $result->result_array()[0];

        return $result;
    }

    public function get_admin_email()
    {

            $this->db->select('mb_email');

            $this->db->where('mb_level', '10');

            $result = $this->db->get($this->table_name);

            return $result->result_array()[0];


    }

    /*  public function get_total_number() {
        $sql = "";
        $sql .= "select count(mb_id) as total_count, ";
        $sql.="(select count(mb_id) from g5_member where mb_intercept_date !=NULL and mb_intercept_date !='') as intercept_count, ";
        $sql.="(select count(mb_id) from g5_member where mb_leave_date !=NULL and mb_leave_date !='') as mb_leave_count ";
        $sql.=" from g5_member ";

        $query = $this->db->query($sql);

        $result =  $query->result_array();
        return $result;
    }

    public function get_new_users() {
        $condition = array(
            'permission_date' =>NULL,
            'leave_date' =>NULL,
            'intercept_date' =>NULL
        );

        $result = $this->get_data($condition)[0];


        return $result;
    }*/

    public function get_last_loginuser($login_ip)
    {
        $condition = "vi_ip='" . $login_ip . "' AND vi_userid != ''";
        
        $this->db->select('vi_userid');
        $this->db->where($condition, NULL, FALSE);  
        $this->db->order_by("vi_date", "desc");
        $result = $this->db->get('visit')->result_array();
        
        if (count($result))
            return $result[0]['vi_userid'];

        return '';
    }

    public function insert_visit($login_ip, $user_agent, $user_id = ''){
        $data = array(
            'vi_ip'=>$login_ip,
            'vi_date'=>date('Y-m-d H:i:s'),
            'vi_agent'=>$user_agent, 
            'vi_userid'=>$user_id, 
        );
        $visit_count = 1;
        $this->db->insert('visit',$data);

        $current_date = date('Y-m-d');
        $condition = array(
            'vs_date'=>$current_date
        );
        $this->db->select('vs_count');
        $this->db->where($condition);
        $result = $this->db->get('visit_sum')->result_array();

        if(count($result) > 0 && $result[0] != null) {
            $visit_count = $result[0]['vs_count'] + 1;
            $w = array(
                'vs_count'=>$visit_count
            );
            $this->db->where($condition);
            $this->db->update('visit_sum', $w);
        }else {
            $w = array(
                'vs_count'=>$visit_count,
                'vs_date'=>$current_date
            );
            $this->db->insert('visit_sum',$w);
        }
    }

    public function get_admin_data(){
            $this->db->select('cf_admin_email, cf_admin_mobile');
            $this->db->where('cf_admin','admin');
            $result = $this->db->get('g5_config')->result_array();
            return $result;
    }
}