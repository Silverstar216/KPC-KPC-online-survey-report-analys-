<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Result Helpers
 *
 * @package        CodeIgniter
 * @subpackage    Helpers
 * @category    Helpers
 * @author        mingzhej@163.com
 * @link        http://wanmei.com/user_guide/helpers/result_helper.html
 */


/**
 * Wrap Json as Jsonp
 *
 * Returns Jsonp Result
 *
 * @access	public
 * @return	string of jsonp
 */
if ( ! function_exists('my_jsonp_encode'))
{
    function my_jsonp_encode($key, $data)
    {
        if($key)
            return $key . '(' . json_encode($data) . ')';
        else
            return json_encode($data);
    }
}

