<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Date Helpers
 *
 */
date_default_timezone_set("Asia/Seoul");

// ------------------------------------------------------------------------


/**
 * 날자구분형식을 점구분으로 바꾼다. 2016-01-13 -> 2016.01.13
 * *
 * @access    public
 * @return    string
 */
if (!function_exists('convert_date_dash_2_dot')) {
    function convert_date_dash_2_dot($value)
    {
    	if (empty($value) || $value == '0000-00-00 00:00:00')
    		return '';
        $date = substr($value, 0, 4) . '.' . sprintf("%02d", substr($value, 5, 2)) . '.' . sprintf("%02d", substr($value, 8, 2));
        if(strlen($value) > 10)
            $date .= substr($value, 10);
        else
            $date .= '.';
        return $date;
    }
}

if (!function_exists('convert_date_dot_2_dash')) {
    function convert_date_dot_2_dash($value)
    {
        if (empty($value))
            return '';
        $date = substr($value, 0, 4) . '-' . sprintf("%02d", substr($value, 5, 2)) . '-' . sprintf("%02d", substr($value, 8, 2));
        if(strlen($value) > 11)
            $date .= substr($value, 11);
        return $date;
    }
}

if (!function_exists('convert_date_human_2_unix')) {
    function convert_date_human_2_unix($value)
    {
        $value = trim($value, '.');
        $date = substr($value, 0, 4) . '-' . sprintf("%02d", substr($value, 5, 2)) . '-' . sprintf("%02d", substr($value, 8, 2));
        if(strlen($value) > 10)
            $date .= substr($value, 10);
        else
            $date .= ' 00:00:00';

        return human_to_unix($date);
    }
}

if (!function_exists('get_prev_date')) {
    function get_prev_date($date)
    {
        $unix = convert_date_human_2_unix($date);
        $unix -= 24 * 60 * 60;
        return date('Y-m-d', $unix);
    }
}

if (!function_exists('get_next_date')) {
    function get_next_date($date)
    {
        $unix = convert_date_human_2_unix($date);
        $unix += 24 * 60 * 60;
        return date('Y-m-d', $unix);
    }
}

if (!function_exists('get_time_diff')) {
    function get_time_diff($from, $to)
    {
        $a = strtotime($from);
        $b = strtotime($to);
        return $b - $a;
    }
}

