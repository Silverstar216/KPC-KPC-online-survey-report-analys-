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
<input id="survey_attached" type="hidden" value="<?=$key?>">

<div class="container container-bg">
    <div id="content">
        <div id="contents">
            <div class="m_con">
                <?php
                $this->load->view('index/menu', $this->data);
                ?>
                <div class="content listWrap" style = "float:right; width: 80%">
                    <div id="contents">
                        <?php if($key==="이용사례") {
                        ?>
                        <img style="max-width: 100%; max-height: 100%;" src="<?=$site_url?>images/bg/02_1.png">
                        <?php
                        } else if($key==="기술소개"){
                        ?>
                        <img style="max-width: 100%; max-height: 100%;" src="<?=$site_url?>images/bg/01_1.png" >
                        <?php
                        }
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
