<?php
/**
 * Author: CHKD
 * Date: 10/9/2018
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Smssend extends MY_Controller
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
			'include/lib/chosen/prism.css',
			'include/lib/chosen/chosen.css'
        );

        $this->data['scripts'] = array(
			'include/lib/chosen/chosen.jquery.js',
			'include/lib/chosen/prism.js',
			'include/lib/chosen/init.js',			
            'include/js/phone.js'
        );

		
        $this->load->model('groups_model');
		$this->load->model('mobiles_model');
    }

    public function index()
    {
		$mobilenum = isset($_GET['mobilenum']) ? $_GET['mobilenum']:0;
		//$userid=get_session_user_id();
		$userid=1;

		$this->data['userid']=$userid;
		$this->data['mobilenum']=$mobilenum;

        $this->load->view('templates/header', $this->data);
        $this->load->view('templates/nav', $this->data);
        $this->load->view('phone/sms_send', $this->data);
        $this->load->view('templates/nav-footer', $this->data);
        $this->load->view('templates/scripts', $this->data);
       $this->load->view('templates/footer', $this->data);
    }

 
}