<?php
/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 2/29/2016
 * Time: 9:47 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
$site_url = site_url();
$star_index=1;
?>

<div class="popPreview">
    <input id="notice_id" type="hidden" value="<?=$notice_id?>">
    <div id="survey_header" class ="survey_header">
        <span>본인 인증</span>
    </div>
    <div id="survey_body" class="survey_body">
        <div class="survey_title">
            <p>전화번호뒤 4자리를 넣어주시기 바랍니다.</p>
        </div>
        <div class="survey_body">
            <div class="auth_input">
                <input type="password" value="" id="auth_adress" minlength="4">
            </div>
            <button class="btn btn_auth_ok" value="ok" onclick="auth_confirm();">확인</button>
        </div>
    </div>
</div>