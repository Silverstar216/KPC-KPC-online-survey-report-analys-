<?php
/**
 * Author: CHKD
 * Date: 10/9/2018
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Money extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();


		$this->load->helper('control_helper');

        $this->data['title'] = SITE_TITLE;
        $this->data['styles'] = array(
			'include/plugins/font-awesome/css/font-awesome.min.css',
            'include/plugins/bootstrap-sweetalert/sweetalert.css',
            'include/lib/jquery.datetimepicker.css',
        );

        $this->data['scripts'] = array(			
        	'include/plugins/bootstrap-sweetalert/sweetalert.min.js',
            'include/lib/jquery.datetimepicker.js',
        );
		$this->load->helper('my_directory');
        $this->load->model('money_model');
        $this->load->model('noticeprice_model');
    }
    //새로운 구좌만들기
    public function createMoneyAccount()
    {
        $userid = get_session_user_id();
        $userauth = get_session_user_level();
        if ($userauth === "") {
            $userauth = "";
            $userid = -1;
        }
        if ($userid != -1) {
            $notice_price = $this->noticeprice_model->get_notice_price();
            $data = array(
                'user_id' => $userid,
                'total_deposit' => 1000,
                'current_amount' => 1000,
                'charge_type' => 0, //0:선불충전식 1: 후불정산제
                'sms_g_simple' =>$notice_price[0]['price'],
                'sms_g_attach' =>$notice_price[1]['price'],
                'sms_sur_simple' =>$notice_price[2]['price'],
                'sms_sur_attach' =>$notice_price[3]['price'],
                'lms_g_simple' =>$notice_price[4]['price'],
                'lms_g_attach' =>$notice_price[5]['price'],
                'lms_sur_simple' =>$notice_price[6]['price'],
                'lms_sur_attach' =>$notice_price[7]['price'],
                'current_count' => 0,
                'month_count' => 0,
                'charge_count' => 0,
                'expire_date' =>'9999-12-31',
            );
            $this->money_model->insert_data($data);
        }
    }
    public function getNoticePrice(){
        echo $this->noticeprice_model->get_data();
    }
    public function showAccount()
    {
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
        $this->load->view('templates/header', $this->data);
        $this->load->view('templates/nav', $this->data);
        $this->load->view('phone/index', $this->data);
        $this->load->view('templates/nav-footer', $this->data);
        $this->load->view('templates/scripts', $this->data);
		$this->load->view('templates/footer', $this->data);
    }

}