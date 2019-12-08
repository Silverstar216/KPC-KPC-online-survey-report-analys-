<?php
/**
 * Author: KMC
 * Date: 10/6/15
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Reviewlogdetail extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        check_signed();
        // date_default_timezone_set('Asia/Pyongyang');

        $this->data['title'] = SITE_TITLE;
        $this->data['styles'] = array(
            'include/css/log.css',
            'include/plugins/font-awesome/css/font-awesome.min.css',
            'include/plugins/bootstrap-sweetalert/sweetalert.css',
            'include/lib/jquery.datetimepicker.css',
        );

        $this->data['scripts'] = array(
            'include/plugins/bootstrap-sweetalert/sweetalert.min.js',
            'include/plugins/loader.js',
            'include/lib/jquery.datetimepicker.js',

            'include/js/index.js',
            'include/js/log/reviewlogdetail.js',
        );

        $this->load->model('users_model');
        $this->load->model('surveys_model');
        $this->load->model('educations_model');
        $this->load->model('Reviews_model');
        $this->load->model('Questions_model');
    }

    public function index()
    {
        $user_id = get_session_user_id();
        $user_name = get_session_user_name();
        $this->data['user_name'] = $user_name;
        $this->data['user_group'] = $this->users_model->get_user_group_from_id($user_id);

        $this->load->view('templates/header', $this->data);
        $this->load->view('templates/nav', $this->data);

        $notice_id = $this->input->get_post('notice_id');
        $this->data['notice_id'] = $notice_id;

        $survey_flag = $this->input->get_post('survey_flag');
        $this->data['survey_flag'] = $survey_flag;

        $start_date = $this->input->get_post('start_date');
        $end_date = $this->input->get_post('end_date');
        $parent = 'start_date=' . $start_date . '&end_date=' . $end_date;
        $this->data['parent'] = $parent;

        //한개 survey를 꺼내기
        $survey = $this->surveys_model->get_data_by_noticeId($notice_id);
        $this->data['survey'] = $survey[0];

        $education = $this->educations_model->get_education_schedule_fromid($survey[0]['education_id']);

        if($education == null){
            $education = array();
            $education['begin_date']= date('Y-m-d H:i:s');
            $education['end_date']= date('Y-m-d H:i:s');
            $education['subject_name']= "";
            $education['count_name']= "";
            $education['student_count']= "";

            $this->data['education'] = $education;
        }else
            $this->data['education'] = $education[0];

        //미응답자들을 얻기
        $noResponseMobiles = $this->Reviews_model->get_noResponseMobile($notice_id,$user_id);
        $this->data['noResponseMobiles'] = $noResponseMobiles;

        //설문안의 질문들과 모든 문항들을 얻기
        $questions = $this->Questions_model->getExampleData($survey[0]['id']);        
        $this->data['questions'] = $questions;

        //설문에 대한 응답통계자료들을 얻기
        $allReview = $this->Reviews_model->get_allReview($notice_id,$questions,$user_id);

        $this->data['allReview'] = $allReview;

        $response_count = $this->Reviews_model->get_response_count($notice_id);
        if(sizeof($response_count) > 0) {
            $this->data['total_response_count'] = $response_count[0]['cnt'];
        } else {
            $this->data['total_response_count'] = "";
        }

        $this->session->set_userdata('allReview'.$user_id, $allReview);
        $this->session->set_userdata('questions'.$user_id, $questions);
        $this->session->set_userdata('noResponseMobiles', $noResponseMobiles);

        $this->load->view('log/reviewlogdetail', $this->data);
        $this->load->view('log/reviewdetail_modal', $this->data);
        $this->load->view('templates/nav-footer', $this->data);
        $this->load->view('templates/scripts', $this->data);
       $this->load->view('templates/footer', $this->data);
    }

    public function download($p_id)
    {
        $p_id = 6;
        // 내리적재 권한검사
        $user_id = get_session_user_id();
        $this->load->helper('download');
        
        $base_path = $this->input->server('DOCUMENT_ROOT') . base_url();
        $file = sprintf('%suploads/questions/%d.PNG', $base_path, $p_id);
        $filename = '시험.png';
        force_download($file, NULL, FALSE, $filename);
    }
    
    public function download_excel()
    {
        $this->load->library('excel');
        $kind = $_GET['kind']; // 1: 미응답 / 0: 설문통계
        if($kind == 1){
            $this->excel->setActiveSheetIndex(0);
            $this->excel->getActiveSheet()->setTitle('미응답자통계');
            $result = $this->session->userdata('noResponseMobiles');
            $idx = 1;
            $this->excel->getActiveSheet()->setCellValue('A' . $idx, '번호');
            $this->excel->getActiveSheet()->setCellValue('B' . $idx, '이름');
            $this->excel->getActiveSheet()->setCellValue('C' . $idx, '전화번호');

            foreach ($result[0] as $rec) {
                $idx++;
                $this->excel->getActiveSheet()->setCellValue('A' . $idx, $idx-1 );
                $this->excel->getActiveSheet()->setCellValue('B' . $idx, $rec['name']);
                $this->excel->getActiveSheet()->setCellValue('C' . $idx, $rec['mobile']);
            }
            foreach ($result[1] as $rec) {
                $idx++;
                $this->excel->getActiveSheet()->setCellValue('A' . $idx, $idx-1 );
                $this->excel->getActiveSheet()->setCellValue('B' . $idx, $rec['name']);
                $this->excel->getActiveSheet()->setCellValue('C' . $idx, $rec['mobile']);
            }
//        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
//        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
//        $this->excel->getActiveSheet()->mergeCells('A1:D1');
//        $this->excel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(30);

            $filename='미응답자통계.xlsx';
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            $objWriter->save('php://output');
        }else{
            /*$this->excel->setActiveSheetIndex(0);
            $this->excel->getActiveSheet()->setTitle('설문조사통계');*/
            $result = $this->session->userdata('reviewExcel');
            $title = $result['title'];
            array_shift($result);

            $idx = 1;

            $this->excel->getActiveSheet()->setCellValue('A' . $idx, $title['title']);
            $this->excel->getActiveSheet()->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB("bbb8b8");
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
            $this->excel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
            $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->mergeCells('A1:H1');

            $idx = 2;
            $Header = ['A','B','C','D','E','F','G','H'];
            $MergeHeader = ['A','E','F','G','H'];
            $content = ['부서명',$title['group'],'담당자',$title['man'],'차수',$title['count'],'교육일자',$title['edu_date']];
            for($i = 0;$i < 8; $i ++) {
                $this->excel->getActiveSheet()->setCellValue($Header[$i].$idx, $content[$i]);
                $this->excel->getActiveSheet()->getStyle($Header[$i].$idx)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }
            $this->excel->getActiveSheet()->getStyle('A2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB("bbb8b8");
            $this->excel->getActiveSheet()->getStyle('C2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB("bbb8b8");
            $this->excel->getActiveSheet()->getStyle('E2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB("bbb8b8");
            $this->excel->getActiveSheet()->getStyle('G2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB("bbb8b8");
            $this->excel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);

            $idx = 3;
            $content = ['발송수',$title['mobile_count'],'응답수',$title['response_count'],'인원',$title['student_count'],'설문일자',$title['survey_date']];
            for($i = 0;$i < 8; $i ++) {
                $this->excel->getActiveSheet()->setCellValue($Header[$i].$idx, $content[$i]);
                $this->excel->getActiveSheet()->getStyle($Header[$i].$idx)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }
            $this->excel->getActiveSheet()->getStyle('A3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB("bbb8b8");
            $this->excel->getActiveSheet()->getStyle('C3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB("bbb8b8");
            $this->excel->getActiveSheet()->getStyle('E3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB("bbb8b8");
            $this->excel->getActiveSheet()->getStyle('G3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB("bbb8b8");
            $this->excel->getActiveSheet()->getRowDimension('3')->setRowHeight(20);
            
            $idx = 4;
            $MergeContent = ['보기','','응답자','응답수','비율'];
            foreach ($result as $rec) {
                $idx++;
                $this->excel->getActiveSheet()->setCellValue('A' . $idx, $rec[0]);
                $this->excel->getActiveSheet()->getStyle('A'. $idx)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB("cabdbd");
                $this->excel->getActiveSheet()->getStyle('A'. $idx)->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getRowDimension($idx)->setRowHeight(25);
                $this->excel->getActiveSheet()->getStyle('A'. $idx)->getFont()->setSize(16);
                $this->excel->getActiveSheet()->getStyle('A'. $idx)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


                $this->excel->getActiveSheet()->mergeCells('A'. $idx.':H'. $idx);
                $idx++;                
                for($i = 0; $i< 5; $i++){
                    $this->excel->getActiveSheet()->setCellValue($MergeHeader[$i].$idx, $MergeContent[$i]);
                    $this->excel->getActiveSheet()->getStyle($MergeHeader[$i]. $idx)->getFont()->setSize(14);
                    $this->excel->getActiveSheet()->getStyle($MergeHeader[$i].$idx)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    $this->excel->getActiveSheet()->getStyle('A'. $idx)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB("e4e4e4");
                }
                $this->excel->getActiveSheet()->mergeCells('A'. $idx.':D'. $idx);


                $dataSeriesLabels1 = array(new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$G$'.$idx, NULL, 1));
                $type = $rec[1];
                $rec_item = $rec[2];
                $chart_flag = 1;
                if (is_array($rec_item)) {
                    foreach ($rec_item as $val) {
                        $idx++;
                        if (count($val) > 0) {                            
                            $this->excel->getActiveSheet()->setCellValue($MergeHeader[0].$idx, $val[0]);
                            if (strstr( $val[0], '1)미선택' )) {
                                $chart_flag = 0;
                            }
                            $this->excel->getActiveSheet()->getStyle($MergeHeader[0].$idx)->getFont()->setSize(12);
                            for($i = 1; $i< count($val); $i++){
                                $con = str_replace("<br>","\n",$val[$i]);
                                $this->excel->getActiveSheet()->setCellValue($MergeHeader[$i].$idx, $con);
                                $this->excel->getActiveSheet()->getStyle($MergeHeader[$i]. $idx)->getFont()->setSize(12);
                                $this->excel->getActiveSheet()->getStyle($MergeHeader[$i].$idx)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            }
                            $this->excel->getActiveSheet()->mergeCells('A'. $idx.':D'. $idx);    
                        }
                    }                
                }        
                // echo ($idx-count($rec_item) + 1).'~'.$idx.'# '.count($rec_item).'#';
                // print_r($rec_item);
                // echo ', ';
                if($type != 3 && $chart_flag == 1) {
                    // 객관식
                    // 원형도표 
                    $xAxisWorksheetStr = '';
                    $dataWorksheetStr = '';
                    if (is_array($rec_item)) {       
                        for ($colindex = 0; $colindex < count($rec_item) - 1; $colindex++) {
                            $val = $rec_item[$colindex];
                            if (count($val) > 3 && $val[3] != 0 && strstr($val[3], '%') == FALSE && strstr($val[3], '응답수') == FALSE) {
                                $xAxisWorksheetStr = $xAxisWorksheetStr  . 'Worksheet!$A$' . ($colindex + $idx-count($rec_item) + 1) . ',';
                                $dataWorksheetStr = $dataWorksheetStr . 'Worksheet!$G$' . ($colindex + $idx-count($rec_item) + 1) . ',';
                            }
                        }                        
                        if (strlen($xAxisWorksheetStr) > 0) 
                            $xAxisWorksheetStr = substr($xAxisWorksheetStr, 0, -1);
                        if (strlen($dataWorksheetStr) > 0) 
                            $dataWorksheetStr = substr($dataWorksheetStr, 0, -1);
                    }

                    $xAxisTickValues1 = array(new PHPExcel_Chart_DataSeriesValues('String', $xAxisWorksheetStr, NULL, 1));
                    $dataSeriesValues1 = array(new PHPExcel_Chart_DataSeriesValues('Number', $dataWorksheetStr, NULL, 1));
                    
                    $series1 = new PHPExcel_Chart_DataSeries(PHPExcel_Chart_DataSeries::TYPE_PIECHART, NULL, range(0, count($dataSeriesValues1) - 1), $dataSeriesLabels1, $xAxisTickValues1, $dataSeriesValues1);
                    $layout1 = new PHPExcel_Chart_Layout();
                    $layout1->setShowPercent(TRUE);

                    $plotArea1 = new PHPExcel_Chart_PlotArea($layout1, array($series1));
                    $legend1 = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
                    $title1 = new PHPExcel_Chart_Title('');
                    $chart1 = new PHPExcel_Chart('chart1', $title1, $legend1, $plotArea1);

                    $chart1->setTopLeftPosition('A'.($idx+2));
                    $chart1->setBottomRightPosition('C'.($idx+13));

                    $this->excel->getActiveSheet()->addChart($chart1);

                    //  막대기도표
                    $xAxisTickValues1 = array(new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$A$'.($idx-sizeof($rec_item)+1).':$A$'.($idx-1), NULL, sizeof($rec_item)-1));
                    $dataSeriesValues1 = array(new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$G$'.($idx-sizeof($rec_item)+1).':$G$'.($idx-1), NULL, sizeof($rec_item)-1));
                    $series1 = new PHPExcel_Chart_DataSeries(PHPExcel_Chart_DataSeries::TYPE_BARCHART, PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED, range(0, count($dataSeriesValues1) - 1), $dataSeriesLabels1, $xAxisTickValues1, $dataSeriesValues1);
                    $series1->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);
                    /*
                                        $dataSeriesValues2 = array(new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$C$2:$C$13', NULL, 12));*/

                    /*$series2 = new PHPExcel_Chart_DataSeries(PHPExcel_Chart_DataSeries::TYPE_LINECHART, PHPExcel_Chart_DataSeries::GROUPING_STANDARD, range(0, count($dataSeriesValues1) - 1), $dataSeriesLabels1, $xAxisTickValues1, $dataSeriesValues1);*/

                    /*$dataSeriesValues3 = array(new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$D$2:$D$13', NULL, 12));
*/
                    $series3 = new PHPExcel_Chart_DataSeries(PHPExcel_Chart_DataSeries::TYPE_AREACHART, PHPExcel_Chart_DataSeries::GROUPING_STANDARD, range(0, count($dataSeriesValues1) - 1), $dataSeriesLabels1, $xAxisTickValues1, $dataSeriesValues1);
                    $plotArea = new PHPExcel_Chart_PlotArea(NULL, array($series1));

                    $legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
                    $title = new PHPExcel_Chart_Title('');
                    $chart1 = new PHPExcel_Chart('chart2', $title, $legend, $plotArea, true, 0, NULL, NULL);
                    //	Set the position where the chart should appear in the worksheet
                    $chart1->setTopLeftPosition('D'.($idx+2));
                    $chart1->setBottomRightPosition('H'.($idx+13));
                    $idx +=13;
                    //	Add the chart to the worksheet
                    $this->excel->getActiveSheet()->addChart($chart1);
                }
            }

            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);


            $filename='설문조사통계.xlsx';
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.urlencode($filename).'"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
            $objWriter->setIncludeCharts(TRUE);
            /*$objWriter->save(str_replace('.php', '.xlsx', __FILE__));*/
            $objWriter->save('php://output');
        }

    }

    public function excel_chart_download() {

        $objWorksheet =  $this->excel->getActiveSheet();
        $objWorksheet->fromArray(array(array('', 'Rainfall (mm)', 'Temperature (°F)', 'Humidity (%)'), array('Jan', 78, 52, 61), array('Feb', 64, 54, 62), array('Mar', 62, 57, 63), array('Apr', 21, 62, 59), array('May', 11, 75, 60), array('Jun', 1, 75, 57), array('Jul', 1, 79, 56), array('Aug', 1, 79, 59), array('Sep', 10, 75, 60), array('Oct', 40, 68, 63), array('Nov', 69, 62, 64), array('Dec', 89, 57, 66)));
        //	Set the Labels for each data series we want to plot
        //		Datatype
        //		Cell reference for data
        //		Format Code
        //		Number of datapoints in series
        //		Data values
        //		Data Marker
        $dataSeriesLabels1 = array(new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$B$1', NULL, 1));
        $dataSeriesLabels2 = array(new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$C$1', NULL, 1));
        $dataSeriesLabels3 = array(new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$D$1', NULL, 1));

        $xAxisTickValues = array(new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$A$2:$A$13', NULL, 12));

        $dataSeriesValues1 = array(new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$B$2:$B$13', NULL, 12));

        $series1 = new PHPExcel_Chart_DataSeries(PHPExcel_Chart_DataSeries::TYPE_BARCHART, PHPExcel_Chart_DataSeries::GROUPING_CLUSTERED, range(0, count($dataSeriesValues1) - 1), $dataSeriesLabels1, $xAxisTickValues, $dataSeriesValues1);

        $series1->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

        $dataSeriesValues2 = array(new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$C$2:$C$13', NULL, 12));

        $series2 = new PHPExcel_Chart_DataSeries(PHPExcel_Chart_DataSeries::TYPE_LINECHART, PHPExcel_Chart_DataSeries::GROUPING_STANDARD, range(0, count($dataSeriesValues2) - 1), $dataSeriesLabels2, NULL, $dataSeriesValues2);

        $dataSeriesValues3 = array(new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$D$2:$D$13', NULL, 12));

        $series3 = new PHPExcel_Chart_DataSeries(PHPExcel_Chart_DataSeries::TYPE_AREACHART, PHPExcel_Chart_DataSeries::GROUPING_STANDARD, range(0, count($dataSeriesValues2) - 1), $dataSeriesLabels3, NULL, $dataSeriesValues3);

        $plotArea = new PHPExcel_Chart_PlotArea(NULL, array($series1, $series2, $series3));

        $legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
        $title = new PHPExcel_Chart_Title('Average Weather Chart for Crete');

        $chart = new PHPExcel_Chart('chart1', $title, $legend, $plotArea, true, 0, NULL, NULL);
        //	Set the position where the chart should appear in the worksheet
        $chart->setTopLeftPosition('F2');
        $chart->setBottomRightPosition('O16');
        //	Add the chart to the worksheet
        $objWorksheet->addChart($chart);


    }
}


/* End of file home.php */
/* Location: ./application/controllers/Home.php */