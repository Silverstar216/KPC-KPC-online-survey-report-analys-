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
    <div id="content" style="min-height: 480px; background: none;margin-left: 25%;margin-top: 100px;max-width: 50%;">
          <div style="margin-left: 8.33%;max-width: 83.33%;">
              <h2 class="login_content" style="padding-bottom: 20px;text-align: center;width: 100%;">온라인서베이 로그인</h1>
              <div id="contents" style="padding: 30px;box-shadow: 0 1px 6px rgb(157, 157, 169);">
                <div class="login_content" style="padding-bottom: 15px;text-align: center;"><b>한국생산성본부 온라인서베이</b> 접속을 환영합니다.</div>
                <div class="id_pass_containter" style="color:black;font-size:14px">
                    <div class="col" style="margin-right: 5px;">
                      <div style="padding-bottom: 10px;width: 100%;">
                        <input type="text" placeholder="ID" class="login_uid" style="color:black;opacity: 0.7;width: 100%;">
                      </div>
                      <div style="padding-bottom: 10px;width: 100%;">
                        <input type="password" placeholder="비밀번호(password)" class="login_password"  style="color:black;opacity: 0.7;width: 100%;">
                      </div>
                      <div style="padding-bottom: 10px;width: 100%;">
                        <button class="btn-warning btn" style="width: 97%;font-size: 16px;" onclick="onLoginClick()">로그인</button>
                    </div>
                    </div>
                </div>
                <div style="margin-top:10px;color:black;font-size: 14px">
                    <p style="margin:0 0 0 -25px;text-align:center">
                      <input style="width: 10%" type="checkbox" name="saveAccount" id="saveAccount">아이디 저장 &nbsp;&nbsp;|&nbsp;&nbsp;
                      <!-- <a href="<?=$site_url?>join">회원가입</a> &nbsp;&nbsp;|&nbsp;&nbsp; -->
                      <a href="<?=$site_url?>join/pass_lost">id/pw 찾기</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
