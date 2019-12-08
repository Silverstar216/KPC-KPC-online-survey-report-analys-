<?php
/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 2/29/2016
 * Time: 9:47 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
$site_url = site_url();
$comment ="문자의 길이가 90byte이하이면 sms(단문)로 발송되며, 90byte 이상이면 lms(장문)으로 발송됩니다.<br>                
			sms와 lms는 요금차이가 있습니다. ";

$title = '일반문자';
$preview_title = '';
if ($survey == 1) {
	$title = '설문전송';
	$preview_title = '설문미리보기';
}else if($goji == 1){
	$title = '개별고지';
	$preview_title = '고지미리보기';
}

if($survey == 1 || $goji == 1) {
    $comment = "문자의 길이가 70byte이하이면  sms(단문)로 발송되며, 70byte 이상이면 lms(장문)으로 발송됩니다.<br>
				sms와 lms는 요금차이가 있습니다.  ";
}
?>

<div class="container container-bg">
	<div id="content">
		<div id="contents">
			<div class="m_con">
                <?php
                // $this->load->view('index/menu_main', $this->data);
                ?>               
                <div class="content listWrap" style = "float:right; width: 100%;">
                    <div class="contentwrap">
                        <div class="titlem1">
                            <em><?=$title?>&nbsp;&nbsp;&nbsp;
								<a target="_blank" style="font-size: 12px; color: mediumblue;" href='<?=$site_url?>Preview/preview/<?=$object_id?>'><?=$preview_title?></a>
							</em>
                            <div class="navgroup">
                                <?php
                                $table_name = $this->data['submenu'];
                                ?>
                                <p>Home <span class="rt">&gt;</span><?=$this->data['menu']?><span class="rt">&gt;</span><font color="red"><?=$table_name?></font></p>
                            </div>
						</div>
						<div class="m7con sub_con">
                            <div class="search">
								<?=$comment?>
							</div>						
							<div style="background: #eeeeee; display: inline-block; border: 1px solid #a1a1a1; margin-top: 10px; padding: 0px 60px;">
                            <p style="margin: 10px 0;">이미 작성된 문서에서  복사하기 ->붙히기로 가져오면,
                                문서내부의 숨은코드로 인해 발송이 안되는 경우가 있습니다.
                                이럴때는 입력창에 직접 입력하십시요.  </p>
                        	</div>
							<?php
							$this->load->view('notice/sms', $this->data);
							?>
							<div style="display: inline-block;width: 70%;">								
								<button style="width: 100px;" class="btn btn-warning" onclick="onSendNoticeClick()">보내기</button>								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

