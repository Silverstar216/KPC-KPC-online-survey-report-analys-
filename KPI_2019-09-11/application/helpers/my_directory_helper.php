<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Directory Helpers
 *
 * @package        CodeIgniter
 * @subpackage    Helpers
 * @category    Helpers
 * @author        mingzhej@163.com
 * @link        http://wanmei.com/user_guide/helpers/result_helper.html
 */

// date_default_timezone_set('Asia/Pyongyang');

// ------------------------------------------------------------------------

/**
 * 등록부뿌리구조를 만든다.
 * *
 * @access    public
 * @return    void
 */
if (!function_exists('my_mkdir')) {
    function my_mkdir($path)
    {
        $arr_path = explode('/', $path);
        $path = $arr_path[0];
        for($i=1; $i<sizeof($arr_path); $i++) {
            if($arr_path[$i] == '')
                continue;
            $path .= '/' . $arr_path[$i];
            if(!is_dir($path))
                mkdir($path, 0777);
        }
    }
}


/**
 * 화일의 확장자를 얻는다. .pdf .mp4 etc
 * *
 * @access    public
 * @return    string
 */
if (!function_exists('my_get_extension')) {
    function my_get_extension($filename)
    {
        $pos = strripos($filename, '.');
        if($pos === FALSE) {
            return '';
        }

        return substr($filename, $pos);
    }
}

/**
 * 화일경로에서 화일이름을 얻는다.
 * *
 * @access    public
 * @return    string
 */
if (!function_exists('my_get_filename')) {
    function my_get_filename($filepath)
    {
        $pos = strripos($filepath, '/');
        if($pos === FALSE) {
            return '';
        }

        return substr($filepath, $pos + 1);
    }
}

/**
 * 화일경로에서 화일이름을 제외한 등록부 경로를 얻는다.
 * *
 * @access    public
 * @return    string
 */
if (!function_exists('my_get_path')) {
    function my_get_path($filepath)
    {
        $pos = strripos($filepath, '/');
        if($pos === FALSE) {
            return '';
        }

        return substr($filepath, 0, $pos);
    }
}

