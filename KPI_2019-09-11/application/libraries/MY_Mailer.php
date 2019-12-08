<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "/third_party/PHPMailer_v2.0.4/class.phpmailer.php";

class MY_Mailer extends PHPMailer
{
    public function __construct() {
        parent::__construct();
    }


}

?>