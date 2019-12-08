<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Signin Helpers
 */

// date_default_timezone_set('Asia/Pyongyang');

// ------------------------------------------------------------------------

if ( ! function_exists('get_selected'))
{
    /**
     * @param 
     * @return
     */
	function get_selected($field, $value)
	{
		return ($field==$value) ? ' selected="selected"' : '';
	}
}
