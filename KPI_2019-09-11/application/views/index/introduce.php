<?php
/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 2/29/2016
 * Time: 9:47 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
$site_url = site_url();
?>

<div class="container container-bg">
    <div id="content">
        <div id="contents">
            <div class="m_con">
                <?php
                $this->load->view('index/menu', $this->data);
                ?>
                <div class="content listWrap" style = "float:right">
                    <div id="contents">
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
