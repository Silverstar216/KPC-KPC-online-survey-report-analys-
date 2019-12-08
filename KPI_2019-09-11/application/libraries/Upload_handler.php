<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "/third_party/UploadHandler.php";

class Upload_handler extends UploadHandler
{
    public function __construct()
    {
        $options = array(
            'image_versions' => array(
                'thumbnail' => array(
                    'max_width' => 540,
                    'min_width'=>540,
                )
            )
        );
        parent::__construct($options);
    }
}

?>
