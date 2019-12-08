<?php
    // 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
    @mkdir(G5_DATA_PATH.'/file/service', G5_DIR_PERMISSION);
    @chmod(G5_DATA_PATH.'/file/service', G5_DIR_PERMISSION);

    if (!get_session('ele_conv_s')) {
        alert_after('올바른 방법으로 이용해주십시오!!!');
    }    

    include_once ('./ele_func.php');//chkd 꼭 같은 클라스를 사용하기 떄문에 막는다.
    include_once('./eleMoney.php');

    $moneyCheck = new eleMoney();//chkd 
    if(!$moneyCheck->is_possible_use('SMS', '', 0, 1, $member['mb_no'])){
        $rtnErrTxt = $moneyCheck->Get_error_msg();
        $moneyCheck->Init();
        alert_after($rtnErrTxt);
    }
    
  
    $filename = $_FILES['conv_up_file']['name'];

    $pos = strrpos($filename, '.');
    $file_ext = strtolower(substr($filename, $pos, strlen($filename)));
    $file_name = substr($filename, 0, $pos);

    switch ($file_ext) {
        //case '.hwp' :
        //    break;
        case '.jpg' :
            break;
        case '.png' :
            break;
        case '.docx' :
            break;
        case '.doc' :
            break;
        case '.pdf' :
            break;
        case '.xls' :
            break;
        case '.xlsx' :
            break;
        case '.html' :
            break;
        case '.htm' :
            break;
        default :
            alert_after('변환 가능 문서 파일이 아닙니다!!!.');
    }

    $file_upload_msg = '';
    $upload = array();

    $chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));

    $tmp_file  =  $_FILES['conv_up_file']['tmp_name'];
    $filesize  = $_FILES['conv_up_file']['size'];
    $filename  = get_safe_filename($filename);

    // 서버에 설정된 값보다 큰파일을 업로드 한다면
    if ($filename) {
        if ($_FILES['conv_up_file']['error'] == 1) {
            $file_upload_msg .= '\"'.$filename.'\" 파일의 용량이 서버에 설정('.$upload_max_filesize.')된 값보다 크므로 업로드 할 수 없습니다.\\n';
            alert_after($file_upload_msg);
        }
        else if ($_FILES['conv_up_file']['error'] != 0) {
            $file_upload_msg .= '\"'.$filename.'\" 파일이 정상적으로 업로드 되지 않았습니다.\\n';
            alert_after($file_upload_msg);
        }
    }

    if (is_uploaded_file($tmp_file)) {
        // 관리자가 아니면서 설정한 업로드 사이즈보다 크다면 건너뜀      
        if (!$is_admin && $filesize > 134217728) {
            $file_upload_msg .= '\"'.$filename.'\" 파일의 용량('.number_format($filesize).' 바이트)이 게시판에 설정('.number_format($board['bo_upload_size']).' 바이트)된 값보다 크므로 업로드 하지 않습니다.\\n';
            alert_after($file_upload_msg);
        }

        // 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
        $filename = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);
        $hashinput = $filename.date("h:i:sa");
        $shuffle = hash('ripemd160', $hashinput);

        // 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다. (길상여의 님 090925)
        $lower_case_name = strtolower($filename);
        $upload_file = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.$shuffle.$file_ext;
        
        
       if($file_ext=='.htm' || $file_ext=='.html') 
        {
             $dest_file = G5_PATH.'/upload/etc/'.$upload_file;
  
             // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
             $error_code = @move_uploaded_file($tmp_file, $dest_file) or die('error!'); //chkd @
              // 올라간 파일의 퍼미션을 변경합니다.
             chmod($dest_file, G5_FILE_PERMISSION); 
             $job_id = @date( 'ymdHi', time() ).uniqid('');//chkd @
             $convert_url =$dest_file;

        }
		else //($file_ext=='.hwp')
        {
             $dest_file = G5_DATA_PATH.'/file/service/'.$upload_file;
             // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.  
             $error_code = @move_uploaded_file($tmp_file, $dest_file) or die('error!'); //chkd @
              // 올라간 파일의 퍼미션을 변경합니다.
             chmod($dest_file, G5_FILE_PERMISSION);
            require_once('./convert_file.php');
            $job_id = @date( 'ymdHi', time() ).uniqid('');
            $uploaddir = G5_DATA_PATH.'/file/service/';
            $convertdir = G5_PATH.'/upload/etc/';
                                
            $convert_url = convert($upload_file, $convertdir);            
        }
       // else
       // alert_after('변환 가능 문서 파일이 아닙니다!.');         
            
    }
    
    //  잔액 체크
    //  데이터 인써트 
    //  단축 URL 생성 
    //  잔액 차감
    if ($convert_url == 'error')    {
            alert_after('변환중 오류가 발생하였습니다!');    
    }
    
    $now_time = G5_TIME_YMDHIS;
    $base_file_name = basename($convert_url);
    

    sql_query("insert into edoc_master set edoc_mbid='{$member['mb_id']}', edoc_wdoc='{$filename}', edoc_adoc='{$upload_file}',  edoc_wurl   = '{$base_file_name}', edoc_time='{$now_time}' ");
    $res = sql_fetch("select max(edoc_ukey) as curr_ukey, edoc_wurl from edoc_master where edoc_mbid='{$member['mb_id']}' and edoc_wdoc='{$filename}' and edoc_adoc='{$upload_file}' and edoc_time='{$now_time}' ");
    if (!$res)   {
        alert_after('변환 완료 하지 못 했습니다.');
    }    
    
    $curr_ukey = $res['curr_ukey'];
    if (!$moneyCheck->check_and_use_money(0, 1, $member['mb_no'], '1', 'SMS', $curr_ukey, '', '')){
        $rtnErrTxt = $moneyCheck->Get_error_msg();
        $moneyCheck->Init();
        alert_after($rtnErrTxt);
    }

    $mlong_url = G5_URL.'/upload/etc/'.$res['edoc_wurl'];
     if($file_ext=='.htm' || $file_ext=='.html')
        $S_url_nm = make_4el_surl_T($mlong_url,$file_name);//http://mms.ac/jdz1
     else
        $S_url_nm = make_4el_surl_s($mlong_url,$file_name);//http://mms.ac/jdz1
    if ($S_url_nm == '')    {
        $S_url_nm = $mlong_url;
    }
     if($file_ext=='.htm' || $file_ext=='.html')
        $varCnt = change_var_eletter_text_T($base_file_name);
     else
       $varCnt = change_var_eletter_text_s($base_file_name);
    //echo "고지변수---".$varCnt;exit;
    sql_query("update edoc_master set edoc_surl   = '{$S_url_nm}', edoc_var = '{$varCnt }' where edoc_ukey = '{$curr_ukey}' and edoc_mbid='{$member['mb_id']}' ");    
?>