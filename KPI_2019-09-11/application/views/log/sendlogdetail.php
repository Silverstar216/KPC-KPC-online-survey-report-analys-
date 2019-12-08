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
				<input id="notice_id" type="hidden" value="<?=$notice_id?>">
				<div class="sub_title1"><img src="<?=$site_url?>images/icon_title.png">전송결과</div>
				<div class="sub_img">
					<img src="<?=$site_url?>images/img/img_result.png">
				</div>
				<div class="search_btn">
					<ul>
						<li><button class="btn btn-default" onclick="onParentClick('<?=$parent?>')">목록으로 가기</button></li>
					</ul>
				</div>
				<div class="search">
					
				</div>
				<table class="search_t">
					<tr>
						<th style="width:50%">메세지</th>
						<th style="width:15%">전송일시</th>
						<th>총건</th>
						<th>성공</th>
						<th>대기</th>
						<th>실패</th>
						<th>조회</th>
						<th style="width:15%">첨부문서</th>
					</tr>
					<Tr>
						<td><?=$notice['content']?></td>
						<td><?=$notice['start_time']?></td>
						<td><?=$notice['mobile_count']?></td>
						<td><?=$notice['successCount']?></td>
						<td><?=$notice['waitCount']?></td>
						<td><?=$notice['failureCount']?></td>
						<td><?=$notice['reply_count']?></td>
                        <?php if($notice['file_url'] ==="" || empty($notice['file_url'])) {
                            ?>
                            <td><?=$notice['file_url']?></td>
                        <?php }else { ?>
                            <td style='color:#ff0000'><a href="<?=$notice['file_url']?>" target="_blank">첨부문서</a></td>
                        <?php } ?>
					</tr>
				</table>
				<br />
				<br />
				<div class="search">
					<ul>


						<li style="padding-left:10px">수신번호 : <input type="text" id="sendlogdetail_mobile" value=""></li>
						<li style="padding-left:10px"><button class="btn btn-default" style="padding:2px 10px" id="sendlogdetail_search">검색</button></li>
					</ul>
				</div>
                <div class="search_btn">
                    <ul>
                        <li><button class="btn btn-default" onclick="onDetailDownloadClick()">다운로드</button></li>
                    </ul>
                </div>
                <div id="grouplistDiv" style=" display:inline-block; over-flow:scroll;">

                </div>

                <div class="blog-pagination">

                </div>
				<div class="sub_img">
					<img src="<?=$site_url?>images/bg/block.png">
				</div>
			</div>
		</div>
	</div>
</div>
