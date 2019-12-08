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
				<div class="sub_title1"><img src="<?=$site_url?>images/icon_title.png">진단결과</div>

				<div class="search">
					<ul>
						<li>검색기간 : <input type="text" id="reviewlog_start_date" class="form-control input-inline reviewlog_start_datepicker" value="<?=$start_date?>" style="padding:2px 6px">
						 ~ 
						 <input type="text" id="reviewlog_end_date" class="form-control input-inline reviewlog_end_datepicker" value="<?=$end_date?>" style="padding:2px 6px"></li>
						 <li><button class="btn btn-default" style="padding:2px 10px" id="reviewlog_search">검색</button></li>
<!--                         <li style = "float:right"><button class="btn btn-default" onclick="onExcelClick()">Excel로 내려받기</button></li>-->
					</ul>
				</div>
                <div class="search_btn">
                    <ul style="float: left">
                        <li><span class="serv_t">총 개수 <?=$total_count;?> 개</span></li>
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

