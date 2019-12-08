<?php
/**
 * Author: KMC
 * Date: 10/6/15
 */

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH."/third_party/class.sms.php";
include APPPATH.'third_party/docx_reader.php';
class Goji extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        // date_default_timezone_set('Asia/Pyongyang');
        $this->load->helper('control_helper');
        $this->data['title'] = SITE_TITLE;
        $this->data['styles'] = array(
            'include/css/log.css',
            'include/css/goji.css',
            'include/plugins/font-awesome/css/font-awesome.min.css',
            'include/plugins/bootstrap-sweetalert/sweetalert.css',
            'include/lib/jquery.datetimepicker.css',
            'include/lib/nouislider/nouislider.min.css',
            'include/plugins/jquery-file-upload/css/jquery.fileupload.css',
            'include/plugins/plupload/js/jquery.ui.plupload/css/jquery.ui.plupload.css',
            'include/css/plugins.css',
            'include/lib/jquery.fileupload/css/jquery.fileupload.css'
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
            'include/js/goji.js'
        );

        $user_id = get_session_user_id();
        $user_level= get_session_user_level();
        if($user_level ==="") {
            $user_level = "";
            $user_id = -1;
        }

        $this->load->model('goji_model');
    }

    public function index()
    {
        $user_id = get_session_user_id();
        $user_level= get_session_user_level();
        if($user_level ==="") {
            $user_level = "";
            $user_id = -1;
        }
        $this->data['user_id']=$user_id;
        $start_date = $this->input->get_post('start_date');
        // $end_date = $this->input->get_post('end_date');
        if (empty($start_date))
        {
            $start_date = date('Y-m-d H:i');
        }

        $condition = array(
            'user_id' => $user_id,
        );
//        $this->data['senderPhoneList'] = $this->senderphone_model->get_data($condition);
        $this->data['start_date'] = $start_date;
        $this->load->view('templates/header', $this->data);
        $this->load->view('templates/nav', $this->data);

        $this->load->view('notice/goji', $this->data);
        $this->load->view('templates/nav-footer', $this->data);
        $this->load->view('templates/scripts', $this->data);
        $this->load->view('templates/footer', $this->data);
    }
//---------개별고지문서 <변환> -----------
    function convertGoji(){
        $user_id = get_session_user_id();
        $user_level= get_session_user_level();

        if($user_id == "" || $user_level ==="-1" || $user_level ==="") {

            echo -1;
            return;
        }

        $DocInfo = new stdClass();

        $DocInfo->user_id = $user_id;

        $DocInfo->edoc_wdoc = $this->input->get_post('file_name');
        $DocInfo->edoc_adoc = $this->input->get_post('uploaded_file_name');
        //-------------  1단계: HTML문서로 변환  --------------

        $htmlFile_url = $this->convertToHTML();

        //-------------  2단계: 고지문서안의 변수목록을 치환한 HTML파일 생성 ----------

        $text_total = '';
        $rfilename = '';
        $lstHtmlFilename = explode("/", $htmlFile_url);
        if(count($lstHtmlFilename) > 0)
            $rfilename = $lstHtmlFilename[count($lstHtmlFilename) - 1];
        else
            $rfilename = $htmlFile_url;

        $DocInfo->edoc_wurl = $rfilename;

        $FileExist = file_exists($htmlFile_url) ;
        $maxVar_Cnt = '00';
        if ($FileExist) {
            $tfile = fopen($htmlFile_url, "r");
            if ($tfile) {
                while(!feof($tfile))
                    $text_total.=fgets($tfile);

            }
            fclose($tfile);

            $text_total = $this->replace_phone_info($text_total);
            $chkVar = $this->is_it_contain_var($text_total);
            //echo 'chk : '.$chkVar[0];
            if ($chkVar[0] == '00'){
                echo -2;
                return;
//                return $chkVar[0] ;
            } else {
                $maxVar_Cnt = $chkVar[0];
                $text_total = $chkVar[1];
            }
            $file_ext= strrchr($rfilename,".");
            $copyfilen = str_replace($file_ext,'-replaced-goji.html',$rfilename);
            $copyfilen = FCPATH . 'uploads/html/'.$copyfilen;
            $FileExist = file_exists($copyfilen) ;
            if ($FileExist) {
                unlink($copyfilen);
            }
            $cfile = fopen($copyfilen, "w");
            if ($cfile) {
                fwrite($cfile, $text_total);
            }
            fclose($cfile);

//            $FileExist = file_exists($htmlFile_url) ;
//            if ($FileExist) {
//                unlink($htmlFile_url);
//            }
//            rename($copyfilen,$htmlFile_url);
            // 해당 문서에 개별 고지 문서라고 세팅하자...
        }

        $DocInfo->edoc_var = $maxVar_Cnt;

        // ------------ 3단계 : 변환된고지문서정보를 자료기지에 보관 --------------
        $insertResult = $this->goji_model->insertGojiDoc($DocInfo);

        $response = array(
            'file_path' => $htmlFile_url,
            'gojidoc_id' => $this->goji_model->get_gojidoc_id($DocInfo)
        );

        if($insertResult)
            echo json_encode($response);
        else
            echo -2;
    }

    function is_it_contain_var($wText){
        //변수  갯수 세기
        //변수 php 치환
        //기록.
        $alistSrt = explode("{{항목", $wText);
        $listSize = sizeof($alistSrt);
        $maxValue = '00';
        $val_arr = array();
        for ($idx = 1;$idx< $listSize;$idx++){
            $StrLen = strlen($alistSrt[$idx]);
            if ($StrLen <  4) { continue; }
            $endBrase = substr ($alistSrt[$idx], 2, 2);
            if (strcmp('}}',$endBrase) <> 0) {
                continue;
            }
            $CurrVal = substr ($alistSrt[$idx], 0, 2);
            if (in_array($CurrVal,$val_arr)) {
                continue;
            }
            array_push($val_arr,$CurrVal);
            $wText = $this->replace_variable_eletter_html($wText,$CurrVal);
            if (strcmp($maxValue,$CurrVal) <= 0) {
                $maxValue = $CurrVal;
            }
        }
        if ($maxValue == '00'){
            $patt_grade = '/{{에듀파인개인별양식}}/';
            $match_cnt = preg_match_all($patt_grade, $wText);
            if ($match_cnt > 0){
                $maxValue = '-1';
                $wText      = replace_variable_edufine_html($wText);
            }
        }
        $rtnArr[0] = $maxValue;
        $rtnArr[1] = $wText;
        return $rtnArr;
    }

    function replace_variable_eletter_html($wText,$cngChar){
        $repStr = '<'.'?'.'='.'$'.'eleText'.$cngChar.'?'.'>';
        $wonStr = '{{항목'.$cngChar.'}}';
        $rtnText = str_replace($wonStr, $repStr,  $wText);
        return $rtnText;
    }

    function replace_phone_info($wonText){
        $repStr = '<'.'?'.'='.'$'.'eleName'.'?'.'>';
        $wonStr = '{{이름}}';
        $wonText = str_replace($wonStr, $repStr,  $wonText);
        $repStr = '<'.'?'.'='.'$'.'elePhone'.'?'.'>';
        $wonStr = '{{전화번호}}';
        $rtnText = str_replace($wonStr, $repStr,  $wonText);
        return $rtnText;
    }

    function replace_variable_edufine_html($wText){
        $repStr = '<'.'?'.'php '.' @include_once('.'$'.'edufineBill'.');'.' ?'.'>';
        $wonStr = '{{에듀파인개인별양식}}';
        $rtnText = str_replace($wonStr, $repStr,  $wText);
        return $rtnText;
    }
// ------- hwp/pdf/docx/doc/xls/jpg/html파일을 html로 변환 -------------
    function convertToHTML()
    {
        $file_name = $this->input->get_post('uploaded_file_name');
        $from = FCPATH . 'uploads/tmp/'.$file_name;
        $converted_url = "";

        if (strpos($file_name, '.htm') > 0) {

            $to = FCPATH . 'uploads/html/'.$file_name;
            if(copy($from,$to))
                $converted_url = 'uploads/html/'.$file_name;
            else
                $converted_url = "";
            //hwp파일인 경우
        }else if(strpos($file_name, '.hwp') > 0){

            $to = FCPATH . 'uploads/html/'.substr($file_name,0,strpos($file_name, ".hwp")).'.html';
            shell_exec("cd /opt/hwp2htmlEX && ./conv -s '".$from."' -o '".$to."' -m convert");

            $converted_url = 'uploads/html/'.substr($file_name,0,strpos($file_name, ".hwp")).'.html';
            //pdf파일인 경우
        }else if(strpos($file_name, '.pdf') > 0){
            $fromDir = FCPATH . 'uploads/tmp';
            shell_exec("pdf2htmlEX --fallback 1 --process-outline 0 --dest-dir '".$fromDir."' '".$from."'");

            $toHTML = FCPATH . 'uploads/html/'.substr($file_name,0,strpos($file_name, ".pdf")).'.html';
            $fromHTML = FCPATH . 'uploads/tmp/'.substr($file_name,0,strpos($file_name, ".pdf")).'.html';
            if(copy($fromHTML,$toHTML))
                $converted_url = 'uploads/html/'.substr($file_name,0,strpos($file_name, ".pdf")).'.html';
            else
                $converted_url = "";
            //doc파일인 경우
        }else if(strpos($file_name, '.doc') > 0){

//            shell_exec('/opt/libreoffice5.0/program/python /opt/libreoffice5.0/program/conv.py -s "/tmp/test.docx"');

        }

        return $converted_url;
    }

//--------- 개별고지자료문서읽기 ---------------
    function readGojiData(){
        $user_id = get_session_user_id();
        $user_level= get_session_user_level();

        if($user_id == "" || $user_level ==="-1" || $user_level ==="") {

            echo -1;
            return;
        }

        $filename = $this->input->get_post('file_name');
        $uploaded_filename = $this->input->get_post('uploaded_file_name');

        //개별고지양식자료얻기
        $gojidoc_row = $this->goji_model->get_gojidoc_row($this->input->get_post('gojidoc_id'));

        if(count($gojidoc_row) > 0)
            $excelColCnt = $gojidoc_row[0]['edoc_var'] + 2;
        else{
            echo -1;
            return;
        }

    //---------- 엑셀자료를 읽어서 자료기지에 넣기--------------

        $this->load->library('excel');

        $filepath = FCPATH . 'uploads/tmp/'.$uploaded_filename;
        //--------파일이 존재하지 않는경우 ---------
        if (!file_exists($filepath))
        {
            echo -1;
            return;
        }
        else
        {
        //----- 엑셀객체준비 ---------
            try {
                $inputFileType = PHPExcel_IOFactory::identify($filepath);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($filepath);
            } catch(Exception $e) {
                @unlink($filepath);
                echo -1;
                return;
            }
        //------ 응답변수를 준비 -------------
            $response = new stdClass();

            $response->filename = $filename;
            $response->user_id = $user_id;
            $response->edcv_udoc = $this->input->get_post('gojidoc_id');

            $AllGojiData = array();

        //-----엑셀자료로부터 이름/전화번호/항목자료들을 추출 --------
            $sheet = $objPHPExcel->getSheet(0);

            $i=1;
            $total_count = 0;
            $error_count = 0;
            $error_mobile = "";
            $check_phone = $this->input->get_post('phone_check');

            foreach ($sheet->getRowIterator() as $row) {

                $eleVar = "";
                $total_count++;
                $name = $sheet->getCell("A$i")->getValue();
                $hp   = $sheet->getCell("B$i")->getValue();
                $var_count = 0;
                if ($name != '' && $hp  != '') {
                    $hp = str_replace('-', '', $hp);
                    //전화번호유효성검사
                    if($check_phone == "true") {
                        if (!$this->checkPhoneNumber($hp)) {
                            $error_mobile .= "'" . $hp . "', ";
                            $error_count++;
                            continue;
                        }
                    }

                    if ($excelColCnt > 2) {
                        $var_count ++;
                        $eleVar .= $sheet->getCell("C$i")->getValue();
                    } else {
                        $error_mobile .= "'" . $hp . "', ";
                        $error_count++;
                        continue;
                    }

                    for ($column = 4; $column <= $excelColCnt; $column++) {

                        $tmpExcelcol = $this->get_excel_col_num($column);
                        $itemValue = $sheet->getCell($tmpExcelcol . $i)->getValue();
                        if($itemValue != "" && $itemValue != null) {
                            $eleVar .= '|' . $itemValue;
                            $var_count ++;
                        }
                    }

                    //----- 추출한 자료를 배렬변수에  넣기--------
                    $GojiData = new stdClass();
                    $GojiData->hp = $hp;
                    $GojiData->name = $name;
                    $GojiData->var = $eleVar;
                    $GojiData->var_count = $var_count;
                    array_push($AllGojiData, $GojiData);
                }
                $i++;
            }

            $response->goji_data = $AllGojiData;
            $response->total_count = $total_count;
            $response->count = $total_count - $error_count;
            $response->error_count = $error_count;
            $response->var_count = $excelColCnt - 2;
            $response->error_mobile = $error_mobile;
            $response->column_count = $gojidoc_row[0]['edoc_var'];

            @unlink($filepath);
            $this->session->set_userdata('gojiData', $response);

            echo json_encode($response);
        }
    }

//--------- 개별고지변수 자료기지에 등록 ------------
    function registryGojiVar(){
        $user_id = get_session_user_id();
        $user_level= get_session_user_level();

        if($user_id == "" || $user_level ==="-1" || $user_level ==="") {

            echo -1;
            return;
        }

        $gojiVarData = $this->session->userdata('gojiData');

        if($gojiVarData->var_count == 0){
            echo -2;
            return;
        }

        $result = $this->goji_model->insertGojiVar($gojiVarData);

        $gojiVarData->row_count = $result->insert_count;
        $gojiVarData->em_ukey = $result->em_ukey;

        if($result->insert_count == 0)//등록실패이면
            echo -2;
        else
            echo json_encode($gojiVarData);
    }

//---------- 개별고지문서목록 읽기 -----------
    function getGojiList(){
        $user_id = get_session_user_id();
        $user_level= get_session_user_level();

        if($user_id == "" || $user_level ==="-1" || $user_level ==="") {

            echo -1;
            return;
        }

        $doc_name = isset($_POST['doc_name']) ? $_POST['doc_name']:'';
        $type=isset($_POST['type']) ? $_POST['type']:'all';
        $page =isset($_POST['page']) ? $_POST['page']:0;

        $this->data['goji_list'] =  $this->goji_model->getGojiList($user_id,$page,$type,$doc_name);
        $this->data['gojiList_totalCount'] =  $this->goji_model->get_GojiList_count($user_id, $type,$doc_name);

        $this->load->view('notice/gojiList', $this->data);
    }

//---------- 개별고지문서 삭제 -----------
    function removeDoc(){
        $user_id = get_session_user_id();
        $user_level= get_session_user_level();

        if($user_id == "" || $user_level ==="-1" || $user_level ==="") {

            echo -1;
            return;
        }

        $edoc_ukey = isset($_POST['edoc_ukey']) ? $_POST['edoc_ukey'] : -1;
        $result = $this->goji_model->removeDoc($edoc_ukey);

        if($result)
            echo 1;
        else
            echo 0;
    }

//---------- 개별고지문서 <사용> 처리 (양식목록에서 선택된 처리)-----------
    function useDoc(){
        $user_id = get_session_user_id();
        $user_level= get_session_user_level();

        if($user_id == "" || $user_level ==="-1" || $user_level ==="") {
            echo -1;
            return;
        }

        $edoc_ukey = isset($_POST['edoc_ukey']) ? $_POST['edoc_ukey'] : -1;

        $edoc_data = $this->goji_model->get_gojidoc_row($edoc_ukey);

        $htmlFile_url = "";
        if(count($edoc_data) > 0){
            $htmlFile_url = 'uploads/html/'.$edoc_data[0]['edoc_wurl'];
        }

        $response = array(
            'file_path' => $htmlFile_url,
            'gojidoc_id' => $edoc_ukey
        );

        echo json_encode($response);
    }

    //형식오류검사
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
    function get_excel_col_num($countCol){
        $wonVal = ($countCol - ($countCol % 26)) / 26;
        $modVal = $countCol % 26;
        if ($wonVal == 0){
            $excelCol = $this->Get_AlphaNum($countCol);
        } else {
            if ($modVal == 0) {
                $excelCol = $this->Get_AlphaNum($wonVal-1).'Z';
            } else {
                $excelCol = $this->Get_AlphaNum($wonVal).Get_AlphaNum($modVal);
            }
        }
        return $excelCol;
    }

    function Get_AlphaNum($alphaNum){
        $excelColArr = array('','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        return $excelColArr[$alphaNum] ;
    }

}
