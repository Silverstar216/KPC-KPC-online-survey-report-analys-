<?php
/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 2/29/2016
 * Time: 9:47 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
$site_url = site_url();
require_once (APPPATH.  "third_party/Captcha/CaptchaBuilderInterface.php");
require_once (APPPATH.  "third_party/Captcha/PhraseBuilderInterface.php");
require_once (APPPATH.  "third_party/Captcha/CaptchaBuilder.php");
require_once (APPPATH.  "third_party/Captcha/PhraseBuilder.php");
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
$phraseBuilder = new PhraseBuilder(5, '0123456789');
$captcha = new CaptchaBuilder(null, $phraseBuilder);
$captcha->build();
$_SESSION['phrase'] = $captcha->getPhrase();
?>


<div class="container container-bg">
    <div id="content">
        <div id="contents">



            <div class="sub_con">

                <div class="sub_title"><img src="<?=$site_url?>images/icon_title.png">아이디 비밀번호 찾기</div>
                <div id="reg_result" class="mbskin" style="padding-bottom: 20px;">

                    <p>
                        회원가입 시 등록하신 이름과 휴대폰 전화번호를 입력해 주세요.<br>
                    </p>

                    <p>
                        아이디와 임시비밀번호를 문자로 보내드립니다.
                    </p>

                    <p>
                        임시비밀번호로 로그인후, 비밀번호를 변경하십시요.
                    </p>

                </div>
                <div class="lost_email_scope" >
                    <label style="font-size: 14px;" for="email">사용자이름</label>

                    <input type="text" class="form-control" required id="lost_mb_name" name="lost_mb_name" value=""  maxlength="30" placeholder="사용자아이디를 입력해주세요">

                </div>
                <div class="lost_email_scope" >
                    <label style="font-size: 14px;" for="email">휴대폰번호</label>

                    <input type="number" class="form-control" required id="lost_mobile" name="lost_mobile" value="" minlength="6" maxlength="30" placeholder="휴대폰번호를 입력해주세요">

                </div>
                <div class="lost_email_scope">
                    <label style="font-size: 14px;" for="captcha">자동복사방지</label>
                    <input type="hidden" name="lost_confirm_captcha" value="<?=$_SESSION['phrase']?>">
                    <img style="padding-left: 20px;" src="<?php echo $captcha->inline(); ?>" /><button type="button" id="captcha_reload" onclick="lost_captcha_refresh()"><span></span></button><input type="text" class="form-control" required name="lost_captcha">


                </div>


                <div class="join_btn">
                    <button class="btn btn-default btn_submit" onclick="onPassfind()" id="btn_submit" accesskey="s" type="submit">확인</button>
                    <button class="btn btn-default" onclick="onCancelClick()">취소</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
