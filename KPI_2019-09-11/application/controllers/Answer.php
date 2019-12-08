<?php
/**
 * Author: KMC
 * Date: 10/7/18
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Answer extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->data['title'] = SITE_TITLE;

        $this->load->model('shorturls_model');
        $this->load->helper('my_url');
    }

    public function index()
    {

        $short_url = $_SERVER['REQUEST_URI'];
        $short_url = substr($short_url, 1, strlen($short_url));

        $id = base62_decode($short_url);

        $long_url = $this->shorturls_model->get_long_url($id);

        if (sizeof($long_url) > 0) {
           header('HTTP/1.1 301 Moved Permanently');
           header('Location: ' .  $long_url[0]['long_url']);
        } else {
            die('Not a valid URL');
        }
    }

}

/* End of file home.php */
/* Location: ./application/controllers/Home.php */