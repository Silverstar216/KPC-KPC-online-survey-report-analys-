<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Log Helpers
 *
 * @package        CodeIgniter
 * @subpackage    Helpers
 * @category    Helpers
 * @author        mingzhej@163.com
 * @link        http://wanmei.com/user_guide/helpers/result_helper.html
 */



/**
 * Log
 * *
 * @access    public
 * @return    string
 */
if (!function_exists('add_log_client')) {
    function add_log_client($value)
    {
        $CI =& get_instance();
        $CI->load->model('log_client_model');

        $data = array();
        $data['ip_address'] = $CI->input->ip_address();
        $data['user_agent'] = $CI->input->user_agent();
        $data['activity_time'] = time();
        $data['request'] = json_encode($_REQUEST);
        $data['data'] = json_encode($value);

        $CI->log_client_model->insert_data($data);
    }
}

