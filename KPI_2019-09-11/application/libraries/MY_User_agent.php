<?php
/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 2/26/2016
 * Time: 11:00 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_User_agent extends CI_User_agent {
    public function is_polyfill() {
        if ($this->is_browser('Firefox') && (int)($this->version()) < 5)
            return true;
        if ($this->is_browser('NaenaraBrowser') && (int)($this->version()) < 3)
            return true;
        if ($this->is_browser('MSIE') && (int)($this->version()) < 10)
            return true;
        if ($this->is_browser('Chrome') && (int)($this->version()) < 20)
            return true;
        return false;
    }

    public function not_support_video() {
        if ($this->is_browser('Firefox') && (int)($this->version()) < 5)
            return true;

        if ($this->is_browser('MSIE'))
            return true;

        if ($this->is_browser('Chrome') && (int)($this->version()) < 20)
            return true;

        return false;
    }
}