<?
/***************************************************************************
 * ���� ���� include
 **************************************************************************/
include "_head.php";
//require_once('litlog.inc');
//$logger = new litlog(CONFIG_FILENAME);
//$logger->makelog("lownload.php start");


	if(!eregi($HTTP_HOST,$HTTP_REFERER)) die();
	
	ini_set("memory_limit","128M");
	
//	require_once('convert_file.php');
//	global $config_arr;
/***************************************************************************
 * �Խ��� ���� üũ
 **************************************************************************/

// ������ üũ
	if($setup[grant_view]<$member[level]&&!$is_admin) Error("�������� �����ϴ�","login.php?id=$id&page=$page&page_num=$page_num&category=$category&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&file=zboard.php");

// ������� Download ���� �ø�;;

    if($filenum==1) {
        mysql_query("update `$t_board"."_$id` set download1=download1+1 where no='$no'");
    } else {
        mysql_query("update `$t_board"."_$id` set download2=download2+1 where no='$no'");
    }

    $data=mysql_fetch_array(mysql_query("select * from  `$t_board"."_$id` where no='$no'"));

/////////// ����
//        ///// �ٿ�ε�;;
//        $filename="file_name".$filenum;
//        header("location:$data[$filename]");
///////////////////////////////


    $filepath="file_name".$filenum;
	$filename="s_file_name".$filenum;
	
//	$url = "http://web.lemontimeit.com/php/zeroboard.php?filepath=" . urlencode($data[$filepath]) . "&filename=" . urlencode($data[$filename]);
//	$url = "http://".$config_arr['WEB']['BASE_URL'].$config_arr['DIRECTORY']['TARGET_DIR']."zeroboard.php?filepath=" . $data[$filepath] . "&filename=" . $data[$filename] . "&docid=LIT_" . $no;

	$tempfilename = $DOCUMENT_ROOT."/bbs/agent.txt";
	$fp = fopen($tempfilename, 'w+');
	$agent = $_SERVER['HTTP_USER_AGENT'];
	fwrite($fp, $agent, strlen($agent));

//	$_SERVER['HTTP_USER_AGENT'] = 'iPhone';

	////////////////////////////////////////////////////////////////////////////////
	// ����� ������ �߰� 
	// �߰��� ����� ���� Ȯ�ε� ��� if���� �߰� �ؾ���
	////////////////////////////////////////////////////////////////////////////////

	if( !preg_match('/Android|iPhone|ipad|ipod|blackberry|Windows CE|nokia|webos|opera mini|sonyricsson|opera mobi|iemobile|Mobile|Windows Phone|symbian|phone|linux|PPC/',$_SERVER['HTTP_USER_AGENT']) ) {
        ///// �ٿ�ε�;;
//// **************** ���� ���� �ٿ�ε� �Ǵºκ�**************************** ////////////////////
//        $filename="file_name".$filenum;
//        header("location:$data[$filename]");
//////***********************************************************************//////////////////////////////////////////
	
		// by hjhwang
		require_once('convert_file.php');
		
		$job_id = 0;
		$query = "select reg_date from `$t_board"."_$id` where no=$no";
		$res = mysql_query($query, $connect);
		$db_data = mysql_fetch_array($res);

		$job_id = $db_data[reg_date].$filenum;

		$exist_flag = true;
//		if(strlen($job_id) != 16 || !preg_match("/[0-9]/", $job_id)){
//			$exist_flag = false;
//		}
		
		//LIT-Generator ���� �����ϴ� 16�ڸ� file_id ���� �����ϰ��� �ϴ� ��� false ����
		//��ü���� file id ���� �����ϰ��� �ϴ� ��� true ����
		$isLocal = true;
		
		if(exist($job_id, $data[$filename], true, $isLocal) == false || $exist_flag == false) {	
			// web ������ ����.
			$u_job_id = upload($job_id, $data[$filepath], $data[$filename], $isLocal);
			$convert_url = convert($u_job_id, $data[$filename], true, $isLocal);
		}else {
			// web ������ �ִ�(����&ĳ��).
			$convert_url = convert($job_id, $data[$filename], true, $isLocal);
		}

		//$convert_url = convertFile($data[$filepath], $data[$filename], true);
		if($convert_url != false) {
			echo "<script language=javascript>void(window.open('" . $convert_url . "', 'NewWin'));history.go(-1);</script>";
			//echo "<script language=javascript>location.replace('" . $convert_url . "');</script>";
		}else {
			print("<script language=javascript>alert('Warning : Server is not running.');history.go(-1);</script>");
		}	
	}
	else {
		// by hjhwang
		require_once('convert_file.php');
		
		$job_id = 0;

		$query = "select reg_date from `$t_board"."_$id` where no=$no";
		$res = mysql_query($query, $connect);
		$db_data = mysql_fetch_array($res);

		$job_id = $db_data[reg_date].$filenum;

		$exist_flag = true;
//		if(strlen($job_id) != 16){
//			$exist_flag = false;
//		}

		//LIT-Generator ���� �����ϴ� 16�ڸ� file_id ���� �����ϰ��� �ϴ� ��� false ����
		//��ü���� file id ���� �����ϰ��� �ϴ� ��� true ����		
		$isLocal = true;
		if(exist($job_id, $data[$filename], true, $isLocal) == false || $exist_flag == false) {	
			// web ������ ����.
			$u_job_id = upload($job_id, $data[$filepath], $data[$filename], $isLocal);
			$convert_url = convert($u_job_id, $data[$filename], true, $isLocal);
		}else {
			// web ������ �ִ�(����&ĳ��).
			$convert_url = convert($job_id, $data[$filename], true, $isLocal);
		}

		//$convert_url = convertFile($data[$filepath], $data[$filename], true);
		if($convert_url != false) {
			echo "<script language=javascript>location.replace('" . $convert_url . "');</script>";
		}else {
			print("<script language=javascript>alert('Warning : Server is not running.');history.go(-1);</script>");
		}
		
	}

	fwrite($fp, $job_id, strlen($job_id));
	fwrite($fp, $data[$filepath], strlen($data[$filepath]));
	fwrite($fp, $data[$filename], strlen($data[$filename]));


	if($connect) {
		@mysql_close($connect);
		unset($connect);
	}
?>
