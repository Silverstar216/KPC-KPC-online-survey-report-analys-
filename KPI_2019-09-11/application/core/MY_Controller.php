<?php
/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 6/26/2018
 * Time: 11:50 PM
 */

class MY_Controller extends CI_Controller {
    var $data;
    public function __construct() {
        parent::__construct();

        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
        $this->load->helper('license');

        $this->check_server_license();
        $this->check_blocked();
        $this->check_request();
        $this->add_access_log();
        $this->check_polyfill();

        $this->data = array();
        $this->check_queue();
    }

    public function check_queue() {
        
    }

    public function check_blocked() {
        
    }

    public function check_request() {
        
    }

    public function add_access_log() {
        

    }

    public function check_polyfill() {

    }

    public function check_server_license() {
        
    }
}