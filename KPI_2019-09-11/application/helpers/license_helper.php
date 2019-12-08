<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Signin Helpers
 */


if ( ! function_exists('get_server_sn'))
{
    function get_server_sn()
    {
        $os = 0;    // 0 : windows, 1 : linux

//Windows
        ob_start(); // Turn on output buffering
        system('ipconfig /all'); //Execute external program to display output
        $mycom = ob_get_contents(); // Capture the output into a variable
        ob_clean(); // Clean (erase) the output buffer

        $findme = "Physical";
        $pmac = strpos($mycom, $findme); // Find the position of Physical text
        $mac = substr($mycom, ($pmac + 36), 17); // Get Physical Address

        $mac = str_replace('-', '', $mac);

// Linux
        if (empty($mac)) {
            $os = 1;
            ob_start(); // Turn on output buffering
            system('netstat -ie'); //Execute external program to display output
            $mycom = ob_get_contents(); // Capture the output into a variable
            ob_clean(); // Clean (erase) the output buffer

            $findme = "HWaddr";
            $pmac = strpos($mycom, $findme); // Find the position of Physical text
            $mac = substr($mycom, ($pmac + 7), 17); // Get Physical Address

            $mac = str_replace(':', '', $mac);
        }

        if (empty($mac)) {
            exit;
        }

// $mac = "0011d88cd0d8";

        $mac = strtoupper($mac);
        $mac_bin = base_convert($mac, 16, 2);
        $count_one = strlen(str_replace("0", "", $mac_bin));
        $first = $count_one % 10;
        $first .= sprintf('%03d', hexdec(substr($mac, 0, 4)) % 999);
        $second = sprintf('%04d', 9999 - hexdec(substr($mac, 3, 4)) % 9999);
        $third = sprintf('%04d', 9999 - hexdec(substr($mac, 6, 4)) % 9999);
        $fourth = sprintf('%03d', 999 - hexdec(substr($mac, 9, 3)) % 999);

        $last =
            substr($first, 0, 1) + substr($first, 1, 1) + substr($first, 2, 1) + substr($first, 3, 1) +
            substr($second, 0, 1) + substr($second, 1, 1) + substr($second, 2, 1) + substr($second, 3, 1) +
            substr($third, 0, 1) + substr($third, 1, 1) + substr($third, 2, 1) + substr($third, 3, 1) +
            substr($fourth, 0, 1) + substr($fourth, 1, 1) + substr($fourth, 2, 1);

        $last = substr($last, -1 , 1);

        $machine_num = $first . '-' . $second . '-' . $third . '-' . $fourth . $last;

        if(!preg_match('/^[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{4}$/', $machine_num)) {
            return false;
            exit;
        }

        return $machine_num;
    }
}

if ( ! function_exists('get_server_license')) {
    function get_server_license($machine_num)
    {
        if(!preg_match('/^[0-9]{4}-[0-9]{4}-[0-9]{4}-[0-9]{4}$/', $machine_num)) {
            return false;
            exit;
        }

        $arr_nodes = explode('-', $machine_num);

        $key_1 = 9999 - ($arr_nodes[0] + $arr_nodes[1] + 1234) % 9999;
        $key_2 = 9999 - ($arr_nodes[0] + $arr_nodes[1] + $arr_nodes[2] + 1987) % 9999;
        $key_3 = 9999 - ($arr_nodes[1] + $arr_nodes[2] + $arr_nodes[3] + 113) % 9999;
        $key_4 = 9999 - ($arr_nodes[0] + $arr_nodes[1] + $arr_nodes[3] + 12) % 9999;

        $key = $key_1 . '-' . $key_2 . '-' . $key_3 . '-' . $key_4;

        return $key;
    }
}

if ( ! function_exists('is_licensed')) {
    function is_licensed()
    {
        $CI =& get_instance();

        $CI->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));

        $license_cached = $CI->cache->get('license');

        $redirect_url = $CI->input->server('REDIRECT_URL');

        if (!starts_with($redirect_url, '/km/home') && !starts_with($redirect_url, '/km/admin/manager')) {
            if (!empty($license_cached))
                return true;
        }

        $device_number = get_server_sn();
        $license = get_server_license($device_number);

        $base_path = $CI->input->server('DOCUMENT_ROOT') . base_url();
        $license_path = $base_path . 'uploads/license/license.txt';

        $license_string = trim(read_file($license_path));

        if ($license == $license_string) {
            $CI->cache->save('license', $license, 60 * 60 * 24);
            return true;
        }
        else {
            $CI->cache->delete('license');
        }
        return false;
    }
}
