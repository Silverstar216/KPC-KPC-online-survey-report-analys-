<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Signin Helpers
 */

// date_default_timezone_set('Asia/Pyongyang');

// ------------------------------------------------------------------------

if ( ! function_exists('is_signed'))
{
    /**
     * @param bool $check_double_signin : 중복가입검사여부, true:검사, false: 비검사
     * @return bool
     */
    function is_signed($check_double_signin=true)
    {
        $CI =& get_instance();
        $user_infor = $CI->session->userdata('user_infor');
        if(isset($user_infor) && is_array($user_infor) && array_key_exists('user_uid', $user_infor) && !empty($user_infor['user_uid'])) {
            // if($check_double_signin == false)
            //     return true;
            // if(check_double_signin())
            //     return true;
            return true;
        }

        return false;
    }
}

if ( ! function_exists('is_password_correct'))
{
    function is_password_correct($user_uid, $password)
    {
        $CI =& get_instance();
        $CI->load->model('users_model');
        $user_infor = $CI->users_model->get_data(array('uid' => $user_uid));

        if (sizeof($user_infor) == 1 && md5($password) == $user_infor[0]['password']) {
            return true;
        }

        return false;
    }
}

// 중복가입검사, 정상이면 true, 비정상이면 false를 귀환
if ( ! function_exists('check_double_signin'))
{
    function check_double_signin()
    {
        $CI =& get_instance();
        $user_infor = $CI->session->userdata('user_infor');
        $CI->load->model('user_check_model');
        if(isset($user_infor) && is_array($user_infor) && array_key_exists('user_uid', $user_infor) && !empty($user_infor['user_uid'])) {
            $user_check_data = $CI->user_check_model->get_data(array('user_uid'=>$user_infor['user_uid']));
            if(!isset($user_infor['user_index']) || !isset($user_infor['user_ip']) || $user_check_data[0]['user_index'] != $user_infor['user_index'] || $user_check_data[0]['user_ip'] != $user_infor['user_ip']) {
                $CI->session->unset_userdata('user_infor');
                return false;
            }
            return true;
        }

        return false;
    }
}

// ------------------------------------------------------------------------

/**
 * Check Signin
 *
 * Unless signed, redirect to the home page
 *
 * @access	public
 * @return	void
 */
if ( ! function_exists('check_signed'))
{
    function check_signed()
    {
        if(!is_signed()) {
            echo "<script>alert('사용자가입을 하셔야 합니다.');</script>";
            redirect('index');
        }
    }
}

// ------------------------------------------------------------------------

/**
 * Is Admin
 *
 * Returns the Admin Status (if signed return true, else return false)
 *
 * @access	public
 * @return	bool
 */
if ( ! function_exists('is_admin'))
{
//    function is_admin()
//    {
//        return false;
//    }
}
// ------------------------------------------------------------------------

/**
 * 관리자인가를 판정하고 아니면 관리자로그인페지로 이동
 *
 * Unless signed, redirect to the sign in page
 *
 * @access	public
 * @return	void
 */
if ( ! function_exists('check_admin'))
{
//    function check_admin()
//    {
//        if(!is_admin())
//            redirect('index');
//    }
}

if ( ! function_exists('get_session_user_id'))
{
    function get_session_user_id()
    {
        if(!is_signed(false))
            return '';
        $CI =& get_instance();
        $user_infor = $CI->session->userdata('user_infor');
        return $user_infor['user_id'];
    }
}

if ( ! function_exists('get_session_user_uid'))
{
    function get_session_user_uid()
    {
        if(!is_signed(false))
            return '';
        $CI =& get_instance();
        $user_infor = $CI->session->userdata('user_infor');

            return $user_infor['user_uid'];

    }
}

if ( ! function_exists('get_session_user_name'))
{
    function get_session_user_name()
    {
        if(!is_signed(false))
            return '';
        $CI =& get_instance();
        $user_infor = $CI->session->userdata('user_infor');
        return $user_infor['user_name'];
    }
}

if ( ! function_exists('get_session_user_nick'))
{
    function get_session_user_nick()
    {
        if(!is_signed(false))
            return '';
        $CI =& get_instance();
        $user_infor = $CI->session->userdata('user_infor');
        return $user_infor['user_nick'];
    }
}
if ( ! function_exists('get_session_user_level'))
{
    function get_session_user_level()
    {
        if(!is_signed(false))
            return '';
        $CI =& get_instance();
        $user_infor = $CI->session->userdata('user_infor');
        return $user_infor['user_level'];
    }
}
