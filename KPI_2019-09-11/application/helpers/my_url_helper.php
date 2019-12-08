<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Url Helpers
 *
 * @package        CodeIgniter
 * @subpackage    Helpers
 * @category    Helpers
 * @author        mingzhej@163.com
 * @link        http://wanmei.com/user_guide/helpers/result_helper.html
 */


/**
 * Get Full Server Url
 * *
 * @access    public
 * @return    string
 */
if (!function_exists('get_full_url')) {
    function get_full_url()
    {
        $https = !empty($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'on') === 0;
        return
            ($https ? 'https://' : 'http://') .
            (!empty($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'] . '@' : '') .
            (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ($_SERVER['SERVER_NAME'] .
                ($https && $_SERVER['SERVER_PORT'] === 443 ||
                $_SERVER['SERVER_PORT'] === 80 ? '' : ':' . $_SERVER['SERVER_PORT']))) .
            substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/'));

    }
}
if (!function_exists('get_short_url')) {
    function get_short_url()
    {
        $https = !empty($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'on') === 0;
        return ($https ? 'https://' : 'http://') . 'survey.kpc.or.kr'; 


    }
}

/**
 * Change Http Protocal
 * *
 * @access    public
 * @return    string
 */
if (!function_exists('http2https')) {
    function http2https()
    {
        $https = !empty($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'on') === 0;

        return
            'https://' .
            (!empty($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'] . '@' : '') .
            (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : ($_SERVER['SERVER_NAME'] .
                ($https && $_SERVER['SERVER_PORT'] === 443 ||
                $_SERVER['SERVER_PORT'] === 80 ? '' : ':' . $_SERVER['SERVER_PORT'])));

    }
}
if (!function_exists('base62_encode')) {
    function base62_encode($val)
    {
        $base = 62;
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // can't handle numbers larger than 2^31-1 = 2147483647
        $str = '';
        do {
            $i = $val % $base;
            $str = $chars[$i] . $str;
            $val = ($val - $i) / $base;
        } while ($val > 0);
        return $str;
    }
}
if (!function_exists('base62_decode')) {
    function base62_decode($str)
    {
        $base = 62;
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $len = strlen($str);
        $val = 0;
        $arr = array_flip(str_split($chars));
        for ($i = 0; $i < $len; ++$i) {
            $val += $arr[$str[$i]] * pow($base, $len - $i - 1);
        }
        return $val;
    }
}


