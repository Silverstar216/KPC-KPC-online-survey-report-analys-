<?php
/**
 * Created by PhpStorm.
 * User: CHKD
 * Date: 10/9/2018
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
							<input type="hidden" id="user_level" value="<?php echo $user_level?>" >
							<input type="hidden" id="hst" value="<?php echo $st?>" >
							<input type="hidden" id="hgst" value="<?php echo $ngst?>" >
							<input type="hidden" id="hstval" value="<?php echo $stval?>" >

							<div class="search">
								<ul>
									<li>
									<select name="st" id="st">
										<option value="all"<?php echo get_selected('all', $st); ?>>이름 + 휴대폰번호</option>
										<option value="name"<?php echo get_selected('name', $st); ?>>이름</option>
										<option value="hp" <?php echo get_selected('hp', $st); ?>>휴대폰번호</option>
									</select>
									</li>
									<li style="padding-left:3px;"><input type="text" id="st_val" name="st_val" value="<?=$stval?>"></li>
									<li style="cursor:pointer" onclick="searchBtnClick();"><img src="<?=$site_url;?>images/btn/btn_search.png"></li>
								</ul>
								
								<ul style="margin-top: 5px">
									<li>그룹 : 
									<select id="groups" name="groups">
										<option value="all" <?php echo get_selected('all', $ngst); ?>>전체</option>
										<?php foreach ($groups as $item):?>
											<option value="<?=$item['id'];?>" <?php echo get_selected($item['id'], $ngst); ?>><?=$item['name']?></option>
										<?php endforeach;?>
									</select>
									</li>
								
								</ul>
							</div>
							<div class="search_btn" style="padding-left: 30px;padding-top: 5px;">
								<ul>
									<li style="float:left; width: 20%;">
									<input type="text" id="addname" name="addname"  placeholder="이름" style="width: 100%; height: 28px;">
									</li>
									<li style="float:left;padding-left:8px; width: 20%;">
									<input type="number" id="phonenum" name="phonenum" placeholder="휴대폰번호" style="width: 100%; height: 28px;" >
									</li>
									<!--<li style="float:left;padding-left:8px;">
									<select id="agroups" name="agroups" style="width: 130px;height: 25px;">
										<option value="">그룹미지정</option>
									<?php /*foreach ($groups as $item):*/?>
										<option value="<?/*=$item['id'];*/?>"><?/*=$item['name']*/?></option>
									<?php /*endforeach;*/?>
									</select>
									</li>-->
								<!-- <li style="float:left;padding-left:8px;">
										<input type="text" id="address_num" name="address_num" style="width: 220px;    height: 28px;" title="주민등록번호" placeholder="주민등록번호">
									</li>-->
									<li style="float:left;padding-left:8px; width: 30%;">
									<input type="text" id="addmemo" name="addmemo" style="width: 100%; height: 28px;" title="메모" placeholder="메모">
									</li>
									<li style="float:right;padding-left:8px;cursor:pointer; width: 15%" ;>
										<button onclick="addGroupUser();" class=" btn-delete btn " >번호추가</button>

									</li>
									<li style="float:right;padding-left:8px;cursor:pointer; width: 15%" >
										<button onclick="deleteGroupUser();" class=" btn-delete btn " >선택삭제</button>
									<!--<img src="<?/*=$site_url*/?>images/btn/btn_del01.png">-->
									</li>
								</ul>															
							</div>
							<div id="grouplistDiv" style="display: inline-block; over-flow:scroll;">

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
</div>
