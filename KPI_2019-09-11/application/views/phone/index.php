<?php
/**
 * Created by PhpStorm.
 * User: CHKD
 * Date: 10/9/2018
 */
defined('BASEPATH') OR exit('No direct script access allowed');
$site_url = site_url();
if (!isset($gst))
	$gst = 'all';
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
							<div class="serv_t">
							총 개수 <?=$totalCnt;?> 개
							</div>
							<div class="search" style="padding-top:8px;">
								<ul style="margin-top: 5px">
									<li>그룹 : 
									<select id="groups" name="groups" style="width:125px;">
										<option value="all" <?php echo get_selected('all', $gst); ?>>전체</option>
									<?php foreach ($groups as $item) {?>
										<option value="<?=$item['id']?>" <?php echo get_selected($item['id'], $gst); ?>><?=$item['name']?></option>
									<?php }?>
									</select>
									</li>
									<li style="cursor:pointer" onclick="javascritp:SeachBtnClick('groups');"><img src="<?=$site_url . 'images/btn/btn_search.png'?>"></li>
								</ul>
							</div>
							<div class="search">
								<ul style="margin-top:8px">
									<input type="hidden" id='user_level' value="<?php echo $user_level?>" >
									<li>그룹추가 : <input type="text" id="input_groupadd"></li>
									<li style="cursor:pointer" class="groupaddimg"><img src="<?=$site_url?>images/btn/btn_group.png"></li>
								</ul>
							</div>
							<div class="search shmemo" style="display:none">
								<ul style="margin-top:8px">
									<li>메모 : <input type="text" id="input_memo" style="width:350px;"></li>
								</ul>
							</div>
							<div class="search_text" style="margin-top:5px;">
								<!--<div class="group01 groupcntview" style="font-size:14px;font-weight:bold;line-height:36px;">그룹개수 : <?=$totalCnt;?></div>-->
								<div class="group01" style="font-size:11px;line-height:14px;">그룹명순으로 정렬됩니다.</div>
							</div>
							<div class="group_btn">
								<ul>
									<li><a href="#" class="groupnamechange"><img src="<?=$site_url?>images/btn/btn_group01.png"></a></li>
									<li><a href="#" class="groupnamecontdel"><img src="<?=$site_url?>images/btn/btn_group02.png"></a></li>
									<li><a href="#" class="groupcontdel"><img src="<?=$site_url?>images/btn/btn_group03.png"></a></li>
								</ul>
							</div>
							<div class="search_btn" style="display:none;">
								<ul>
									<li><a href="<?=$site_url?>phone/add_phone_num?what=reg'?>"><img src="<?=$site_url?>images/btn/btn_addnum.png"></a></li>
								</ul>
							</div>
							<div id="grouplistDiv" style="max-height:500px; over-flow:scroll;">
							<!-- grouplist -->
							</div>
						</div>		
					</div>
				</div>
			</div>
		</div>
    </div>
</div>


