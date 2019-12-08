<?php
	define('G5_SMS_BASEURL', 'E:/freelancer/7_wwwroot');
	include_once($G5_SMS_BASEURL.'/common.php');
	$www_link = 'http://192.168.0.40:1001';
	$epls_wrno = $argv[1];
     $sender_com = 'IFB';  
	if ($epls_wrno == '') {die();}
	$sql_squery = "select w.*,(select mb_nick from g5_member where mb_no = wr_id) as wr_nick,(select mb_7 from g5_member where mb_no = wr_id) as lmsflag from {$g5['sms5_write_table']} as w where wr_no = '{$epls_wrno}' ";
	echo	$sql_squery;
	exit;

	$row = sql_fetch($sql_squery);
	if ($row) {
                 $wr_type  = $row['wr_type'];
                 $wr_udoc  = $row['wr_udoc'];                 
                 $wr_poll   = $row['wr_poll'];
                 $wr_datetime = $row['wr_datetime'];// 등록시간...
                 $wr_booking = $row['wr_booking'];// 예약시간 
                 $wr_nick       = $row['wr_nick'];
	      if ($wr_booking == '0000-00-00 00:00:00') {
		$date_client_req = $wr_datetime;		
	      } else {
		$date_client_req = $wr_booking;		
	      }               

            $wr_reply = $row['wr_reply'];// 회신번호 
	      $smsr_bsbh = str_replace('-', '', trim($wr_reply));
	      $smsr_bsbh = str_replace(' ', '', trim($smsr_bsbh));                        
	      $smsr_bsbh = str_replace('.', '', trim($smsr_bsbh));        
	      $smsr_bsbh = str_replace('(', '', trim($smsr_bsbh));
	      $smsr_bsbh = str_replace(')', '', trim($smsr_bsbh));
	      $smsr_bsbh = str_replace('[', '', trim($smsr_bsbh));
	      $smsr_bsbh = str_replace(']', '', trim($smsr_bsbh));      
	              
                 $wr_message = $row['wr_message'];
$wr_message = str_replace("'", "\'", $wr_message);        

$LMS_FLAG = $row['lmsflag'];        
$Mb_7 = json_decode($LMS_FLAG);
$hs_code_qry = '';
if ($Mb_7->{'LMS'} == '1'){
    $msg_length = strlen(utf2euc($wr_message));
    if (($wr_type == 0)||($wr_type == 1)||($wr_type == 2)) {// 첨부 문서 
        $limit_len = 69;
    } else {
        $limit_len = 90;
    }
    if ($msg_length > $limit_len) {
      $LMS_PROCESS = 'LMS';
      $s_subject = '['.$wr_nick.']';
      $hs_code_qry = ", hs_code = 'LGUPLMS' ";
    } else {
      $LMS_PROCESS = 'SMS';
      $s_subject = '';
    }
}  else {
      $LMS_PROCESS = 'SMS';
      $s_subject = '';  
}
          $insertMessage = $wr_message; 
	      $mlong_url = '';
	      $edoc_surl  = '';
                 if (($wr_type == 0)||($wr_type == 1)||($wr_type == 2)) {// 첨부 문서 
		          include_once(include_once($G5_SMS_BASEURL.'/service/ele_func.php');
                     if ($wr_type == 0) {
                     		$qrydoc = sql_fetch("select edoc_surl,edoc_wdoc from edoc_master where edoc_ukey = '{$wr_udoc}' ");
                     		$edoc_surl = $qrydoc['edoc_surl'];              
                           $edoc_file_name = $qrydoc['edoc_wdoc'];              
                           $pos = strrpos($edoc_file_name, '.');                         
                           $titleName = substr($edoc_file_name, 0, $pos);          
			           $mlong_url = $www_link.'/upload/etc/eletter.php?ep='.$wr_udoc;
                     } else {                           
			           $mlong_url = $www_link.'/felv.php?ep='.$wr_poll;
			           $edoc_surl = make_4el_surl($mlong_url,$titleName);
                     }
                 } else { 	// 그냥 SMS
                 	// 링크 따지마..
                 } 
 	      $qry = sql_query("select * from {$g5['sms5_history_table']} where wr_no = '{$epls_wrno}' and hs_mt_pr is null ");
                 while ($hisrow = sql_fetch_array($qry))
                 {
                       $hs_no = $hisrow['hs_no'];   
                       $hs_hp = $hisrow['hs_hp'];
                       $hs_name = $hisrow['hs_name'];
                       $insertMessage =  str_replace('{이름}', $hs_name, $wr_message);
                       if (($wr_type == 0)||($wr_type == 1)||($wr_type == 2)) {
                       	// short_url 생성                       	
                       	$mmlong_url = $mlong_url.'&sk='.$hs_no;
                       	$S_url_nm = make_4el_surl($mmlong_url,$titleName); 
                       	$insertMessage = $insertMessage.' '.$S_url_nm;
                       } 
                       
     	            $smsr_jhbh = str_replace('-', '', trim($hs_hp));
	            $smsr_jhbh = str_replace(' ', '', trim($smsr_jhbh));                        
	            $smsr_jhbh = str_replace('.', '', trim($smsr_jhbh));        
	            $smsr_jhbh = str_replace('(', '', trim($smsr_jhbh));
	            $smsr_jhbh = str_replace(')', '', trim($smsr_jhbh));
	            $smsr_jhbh = str_replace('[', '', trim($smsr_jhbh));
	            $smsr_jhbh = str_replace(']', '', trim($smsr_jhbh));      
             
                      if ($sender_com == 'IFB') {
                          if ($LMS_PROCESS ==  'LMS') {
                              $sql_text = "insert into imds.em_mmt_tran (date_client_req,subject,content,attach_file_group_key,callback,service_type,broadcast_yn,msg_status,recipient_num) values ";
                              $sql_text = $sql_text."('{$date_client_req}','{$s_subject}','{$insertMessage}', '0','{$smsr_bsbh}','3','N','1','{$smsr_jhbh}'); ";
                               sql_query($sql_text);
                               $sql_text = "select max(mt_pr) mt_pr from imds.em_mmt_tran where date_client_req = '{$date_client_req}' ";
                               $sql_text = $sql_text."and content = '{$insertMessage}' and callback =  '{$smsr_bsbh}' ";
                               $sql_text = $sql_text."and recipient_num = '{$smsr_jhbh}' ";
							   
							   echo $sql_text;
							   exit;
							   
                               $qryimdsc = sql_fetch($sql_text);	
							   
                                  $mt_pr = $qryimdsc['mt_pr'];
                                    if (($wr_type == 0)||($wr_type == 1)||($wr_type == 2)) {                                // short_url 생성                         
                                         sql_query("update {$g5['sms5_history_table']} set hs_message ='{$insertMessage}',hs_lurl = '{$mmlong_url}',hs_surl='{$S_url_nm}', hs_mt_pr = '{$mt_pr}', mb_id = '{$LMS_PROCESS}' where hs_no='{$hs_no}' ");                       
                                    } else {
                                         sql_query("update {$g5['sms5_history_table']} set hs_message ='{$insertMessage}', hs_mt_pr = '{$mt_pr}', mb_id = '{$LMS_PROCESS}' where hs_no='{$hs_no}' ");                                               
                                     }     
                          } else {
                               $sql_text = "insert into imds.em_smt_tran (date_client_req,content,callback,service_type,broadcast_yn,msg_status,recipient_num) values ";
                               $sql_text = $sql_text."('{$date_client_req}','{$insertMessage}', '{$smsr_bsbh}','0','N','1','{$smsr_jhbh}'); ";
                               sql_query($sql_text);
                               $sql_text = "select max(mt_pr) mt_pr from imds.em_smt_tran where date_client_req = '{$date_client_req}' ";
                               $sql_text = $sql_text."and content = '{$insertMessage}' and callback =  '{$smsr_bsbh}' ";
                               $sql_text = $sql_text."and recipient_num = '{$smsr_jhbh}' ";
							   
							   echo $sql_text;
							   exit;
							   
                               $qryimdsc = sql_fetch($sql_text);

                                $mt_pr = $qryimdsc['mt_pr'];
                                  if (($wr_type == 0)||($wr_type == 1)||($wr_type == 2)) {                                // short_url 생성                         
                                           sql_query("update {$g5['sms5_history_table']} set hs_message ='{$insertMessage}',hs_lurl = '{$mmlong_url}',hs_surl='{$S_url_nm}', hs_mt_pr = '{$mt_pr}' where hs_no='{$hs_no}' ");                       
                                  } else {
                                       sql_query("update {$g5['sms5_history_table']} set hs_message ='{$insertMessage}', hs_mt_pr = '{$mt_pr}' where hs_no='{$hs_no}' ");                                               
                                   }                                
                          }
                     } else {// 현재는 LGU+ ($sender_com == 'LGU') {

                           if ($LMS_PROCESS ==  'LMS') {
                                 $sql_text = "insert into uplusms.MMS_MSG (SUBJECT,REQDATE,MSG,CALLBACK,TYPE,STATUS,PHONE,ETC3) values ";
                                 $sql_text = $sql_text."('{$s_subject}','{$date_client_req}','{$insertMessage}', '{$smsr_bsbh}','0','0','{$smsr_jhbh}','{$hs_no}'); ";
                                 sql_query($sql_text);
                                 $sql_text = "select MSGKEY from uplusms.MMS_MSG where REQDATE = '{$date_client_req}' ";
                                 $sql_text = $sql_text."and MSG = '{$insertMessage}' and CALLBACK =  '{$smsr_bsbh}' ";
                                 $sql_text = $sql_text."and PHONE = '{$smsr_jhbh}' ";
                                 $qryimdsc = sql_fetch($sql_text);
                                 $mt_pr = $qryimdsc['MSGKEY'];
                           } else {
                                 $sql_text = "insert into uplusms.sc_tran (TR_SENDDATE,TR_MSG,TR_CALLBACK,TR_MSGTYPE,TR_SENDSTAT,TR_PHONE,TR_ETC3) values ";
                                 $sql_text = $sql_text."('{$date_client_req}','{$insertMessage}', '{$smsr_bsbh}','0','0','{$smsr_jhbh}','{$hs_no}'); ";
                                 sql_query($sql_text);
                                 $sql_text = "select TR_NUM from uplusms.sc_tran where TR_SENDDATE = '{$date_client_req}' ";
                                 $sql_text = $sql_text."and TR_MSG = '{$insertMessage}' and TR_CALLBACK =  '{$smsr_bsbh}' ";
                                 $sql_text = $sql_text."and TR_PHONE = '{$smsr_jhbh}' ";
                                 $qryimdsc = sql_fetch($sql_text);
                                 $mt_pr = $qryimdsc['TR_NUM'];
                          }
                          if (($wr_type == 0)||($wr_type == 1)||($wr_type == 2)) {                                // short_url 생성                         
                                   sql_query("update {$g5['sms5_history_table']} set hs_message ='{$insertMessage}'".$hs_code_qry.", hs_lurl = '{$mmlong_url}',hs_surl='{$S_url_nm}', hs_mt_pr = '{$mt_pr}', mb_id = '{$LMS_PROCESS}' where hs_no='{$hs_no}' ");                       
                          } else {
                               sql_query("update {$g5['sms5_history_table']} set hs_message ='{$insertMessage}'".$hs_code_qry.", hs_mt_pr = '{$mt_pr}', mb_id = '{$LMS_PROCESS}' where hs_no='{$hs_no}' ");                                               
                          }    

                     }
                 }   
                 if (($wr_type == 0)||($wr_type == 1)||($wr_type == 2)) {                 	
                 	    sql_query("update {$g5['sms5_write_table']} set wr_message = concat(wr_message,' ','{$edoc_surl}') where wr_no='{$epls_wrno}' ");
                 }
          }		
?>