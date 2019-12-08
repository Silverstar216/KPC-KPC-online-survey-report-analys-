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
                // $this->load->view('index/menu_main', $this->data);
                ?>               
                <div class="content listWrap" style = "float:right; width: 100%;">
                    <div class="contentwrap">
                        <div class="titlem1">
                            <em><?=$this->data['submenu']?></em>
                            <div class="navgroup">
                                <?php
                                $table_name = $this->data['submenu'];
                                ?>
                                <p>Home <span class="rt">&gt;</span><?=$this->data['menu']?><span class="rt">&gt;</span><font color="red"><?=$table_name?></font></p>
                            </div>
						</div>
						<div class="m7con sub_con">					
							<input id="log_start_date" type="hidden" value="<?=$start_date?>">
							<input id="log_end_date" type="hidden" value="<?=$end_date?>">
							<div class="search">
								<ul>
									<li>검색기간 : <input type="text" id="reservelog_start_date" class="form-control input-inline reservelog_datepicker" value="<?=$start_date?>" style="padding:2px 6px">
									~ 
									<input type="text" id="reservelog_end_date" class="form-control input-inline reservelog_datepicker" value="<?=$end_date?>" style="padding:2px 6px"></li>
									<li><button class="btn btn-default" style="padding:2px 10px" id="reservelog_search">검색</button></li>
								</ul>
							</div>
							<div class="search_btn">
								<ul style="float: left">
									<li><span class="serv_t">총 개수 <?=$total_count;?> 개</span></li>
								</ul>
								<ul>
									<li><button class="btn btn-warning" onclick="onSendClick()">전송내역</button></li>
								</ul>
								<ul>
									<li id="notice_reserve_date_container" class="hidden">
										<input type="text" id="notice_reserve_date" class="form-control input-inline notice_datepicker" value="<?=$start_date?>">
										<img src="<?=$site_url?>images/icon_cal.png" onclick="onClickedCal()">

									</li>

								</ul>
							</div>
							<div id="grouplistDiv" style=" display:inline-block; over-flow:scroll;">

							</div>

							<div class="blog-pagination">

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

