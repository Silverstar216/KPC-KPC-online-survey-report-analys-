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
            <div class="sub_con">

                <div class="sub_title"><img src="<?=$site_url?>images/icon_title.png">회원가입</div>
                <div id="reg_result" class="mbskin">

                    <p>
                        <strong><?=$user_name?></strong>님의 회원가입을 진심으로 축하합니다 !<br>
                    </p>


                    <p>
                        회원님의 비밀번호는 아무도 알수 없는 암호화 코드로 저장되므로 안심하셔도 좋습니다.<br>
                        아이디, 비밀번호 분실시에는 회원가입시 입력하신 이메일 주소를 이용하여 찾을 수 있습니다.
                    </p>

                    <p>
                        회원 탈퇴는 사용내용 확인 후 가능하며 일정기간이 지난 후, 회원님의 정보는 삭제하고 있습니다.<br>
                        감사합니다.
                    </p>




                </div>
                <div class="join_btn">
                    <button class="btn btn_auth_ok" value="ok" onclick="go_main();">메인으로</button>

                </div>

            </div>
        </div>
    </div>
</div>
