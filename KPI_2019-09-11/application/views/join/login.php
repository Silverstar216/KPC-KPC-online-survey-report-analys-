<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 11/9/2018
 * Time: 5:18 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
$site_url = site_url();
?>

<div class="login_container">
    <section id="login_content">

            <h1>사용자 가입</h1>
            <div style="    margin-top: 70px;">
                <input type="text" placeholder="사용자 아이디" required="" id="username"  />
            </div>
            <div style="    margin-top: 20px;">
                <input type="password" placeholder="암호" required="" id="password" />
            </div>
            <div>
                <button class="btn btn_login" value="Log in" onclick="login_click()" >가입</button>

            </div>
        <div>

            <a href="<?=$site_url?>join/pass_lost">비밀번호찾기</a>
            <a href="<?=$site_url?>join">회원 가입</a>
        </div>


    </section><!-- content -->
</div><!-- container -->

