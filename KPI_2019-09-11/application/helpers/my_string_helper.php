<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter My String Helpers
 *
 * @package        CodeIgniter
 * @subpackage    Helpers
 * @category    Helpers
 * @author        mingzhej@163.com
 */

// date_default_timezone_set('Asia/Shanghai');

// ------------------------------------------------------------------------

/**
 * Tune Summernote Content
 * *
 * @access    public
 * @return    string tuned
 */
if (!function_exists('tune_html_content')) {
    function tune_html_content($data)
    {
        $data = str_replace('<script>', '&lt;script&gt;', $data);
        $data = str_replace('</script>', '&lt;/script&gt;', $data);
        $data = str_replace('<html>', '&lt;html&gt;', $data);
        $data = str_replace('</html>', '&lt;/html&gt;', $data);
        $data = str_replace('<body>', '&lt;body&gt;', $data);
        $data = str_replace('</body>', '&lt;/body&gt;', $data);
        $data = str_replace('<head>', '&lt;head&gt;', $data);
        $data = str_replace('</head>', '&lt;/head&gt;', $data);

        return $data;
    }
}

if (!function_exists('get_user_list_array')) {
    function get_user_list_array($user_list)
    {
        $user_list = str_replace(' ', '', $user_list);
        $user_list = str_replace(array(';', ':'), ',', $user_list);
        while (strpos($user_list, ',,')) {
            $user_list = str_replace(',,', ',', $user_list);
        }
        $user_list = trim($user_list, ',');

        $arr_user = explode(',', $user_list);
        array_unique($arr_user);
        sort($arr_user);
        return $arr_user;
    }
}

if ( ! function_exists('starts_with'))
{
    function starts_with($var, $needle)
    {
        if (strpos($var, $needle) === 0)
            return true;
        return false;
    }
}

if ( ! function_exists('contains_str'))
{
    function contains_str($var, $needle)
    {
        if (strpos($var, $needle) === false)
            return false;
        return true;
    }
}

// 제품키발생시 장치번호(혹은 IMEI번호)가 15~16자리 영문혹은 수자의조합인가를 판정
//
if ( ! function_exists('is_valid_device_id'))
{
    function is_valid_device_id($data)
    {
        $data = str_replace('-', '', $data);
        if(preg_match('/^[A-Fa-f0-9]{15,16}$/', $data))
            return true;

        return false;
    }
}

if ( ! function_exists('is_valid_user_uid'))
{
    function is_valid_user_uid($user_uid)
    {
        // 사용자의 id길이는 2~32까지 허용
        // 영문대문자, 영문소문자로 시작
        // 영문대문자, 소문자, 수자, 밑선을 허용
        if ( preg_match('/^[A-Za-z][A-Za-z0-9_]{1,31}$/', $user_uid) ) {
            return true;
        }
        else {
            return false;
        }
    }
}


if ( ! function_exists('is_valid_card_id'))
{
    function is_valid_card_id($card_id)
    {
        // 94080300007259500
        // 17자리 수자로만 이루어졌다.
        // 9로시작
        if ( preg_match('/^9[0-9]{16,16}$/', $card_id) ) {
            return true;
        }
        else {
            return false;
        }
    }
}

if ( ! function_exists('hex2bin'))
{
    function hex2bin($hex_str) {
        $result = pack("H*", $hex_str);
        return $result;
    }
}

if ( ! function_exists('utf82hex'))
{
    function utf82hex($buffer) {
        $str = bin2hex($buffer);
        return $str;
    }
}

if ( ! function_exists('hex2utf8'))
{
    function hex2utf8($buffer) {
        $str = hex2bin($buffer);
        return $str;
    }
}

if ( ! function_exists('ordersake2str'))
{
    function ordersake2str($sake) {
        foreach($GLOBALS['order']['account']['sake'] as $key => $val) {
            if ($val == $sake)
                return $key;
        }
        return '';
    }
}

if ( ! function_exists('orderdrawing2str'))
{
    function orderdrawing2str($flag) {
        foreach($GLOBALS['order']['drawing_flag'] as $key => $val) {
            if ($val == $flag)
                return $key;
        }
        return '';
    }
}

if ( ! function_exists('ordertaxonomy2str'))
{
    function ordertaxonomy2str($flag) {
        foreach($GLOBALS['order']['term_taxonomy'] as $key => $val) {
            if ($val == $flag)
                return $key;
        }
        return '';
    }
}

// 화일크기현시
if ( ! function_exists('get_file_size'))
{
    function get_file_size($filesize) {
        if ($filesize > 1024 * 1024 * 1024) {
            $filesize = $filesize / (1024 * 1024 * 1024);
            $filesize_unit = 'GB';
        } else if ($filesize > 1024 * 1024) {
            $filesize = $filesize / (1024 * 1024);
            $filesize_unit = 'MB';
        } else if ($filesize > 1024) {
            $filesize = $filesize / 1024;
            $filesize_unit = 'KB';
        } else {
            if ($filesize > 1)
                $filesize_unit = 'bytes';
            else
                $filesize_unit = 'byte';
        }

        if ($filesize == (int)$filesize) {
            $filesize = (int)$filesize;
            $filesize = number_format($filesize, 0, '.', ' ');
        }
        else {
            $filesize = number_format($filesize, 1, '.', ' ');
        }
        return array(
            'filesize' => $filesize,
            'unit' => $filesize_unit
        );
    }
}