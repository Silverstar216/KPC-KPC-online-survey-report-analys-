<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Download Helpers
 */

/**
 * Download Range File
 * *
 * @access    public
 * @return    void
 */
if (!function_exists('range_download')) {
    function range_download($file)
    {
        $CI =& get_instance();
        $CI->load->helper('file');
        $range = $CI->input->server('HTTP_RANGE');
        if(!isset($range))
            return;

        $fp = @fopen($file, 'rb');

        if ($fp == false)
            return;

        $positions = explode('-', str_replace('bytes=', '', $range));
        $start = (int)($positions[0]);
        $total = filesize($file);

        $mime = get_mime_by_extension($file);

        if(!isset($mime))
            $mime = 'unknown';

        if($total!==FALSE) {
            $end = $positions[1] ? (int)($positions[1]) : $total - 1;
            $chunksize = ($end - $start) + 1;

            header('HTTP/1.1 206 Partial Content');
            header('Content-Range: bytes ' . $start . "-" . $end . "/" . $total);
            header('Accept-Ranges: bytes');
            header('Content-Length: ' . $chunksize);
            header('Content-Type: ' . $mime);
        }

        fseek($fp, $start);

        // Start buffered download
        $buffer = 1024 * 8;
        while(!feof($fp) && ($p = ftell($fp)) <= $end) {
            if ($p + $buffer > $end) {
                // In case we're only outputtin a chunk, make sure we don't
                // read past the length
                $buffer = $end - $p + 1;
            }
            set_time_limit(0); // Reset time limit for big files
            echo fread($fp, $buffer);
            flush(); // Free up memory. Otherwise large files will trigger PHP's memory limit.
        }

        fclose($fp);
    }
}


