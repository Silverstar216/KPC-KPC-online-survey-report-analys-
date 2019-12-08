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
                $this->load->view('index/menu_main', $this->data);
                ?>               
                <div class="content listWrap" style = "float:right; width: 80%;">
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
                            <input type="hidden" id="hstval" value="<?php echo $stval?>" >

                            <div class="serv_t" style="    margin-left: 0;">
                                총 갯수 <?=$advert_total_count;?> 개
                            </div>
                            <div style="border: 5px solid #a1a1a1; font-size: 16px;">
                                <div style="    width: 130px; display: inline-block;background: #d6d1d1;padding: 7px; text-align: center;">
                                    <lable>연결기관 (<?=$link_count?>)</lable>
                                </div>
                                <div style="    display: inline-block; padding-left: 30px;">
                                    <?php if($link_text ==""){ ?>
                                        <lable>연결된 기관이 없습니다.</lable>
                                    <?php } else { ?>
                                        <lable><?=$link_text?></lable>
                                    <?php } ?>
                                </div>
                            </div>
                            <div style="display: inline-block; width: 100%;    margin-top: 20px;">
                                <div style="    display: inline-block;">
                                    <label style="margin-left:20px;">제목 : <input type="text" id="st_val" name="st_val" value="<?=$stval?>" style="width: 300px;height: 27px;"></label>

                                    <a style="cursor:pointer" onclick="getAdvertList(0);"><img style="    margin-top: -2px;" src="<?=$site_url;?>images/btn/btn_search.png"></a>
                                </div>
                                <div style="    display: inline-block; float: right;">

                                    <button onclick="advert_delete();" class="btn_modal_public btn " style="float: none">선택 삭제</button>
                                </div>
                            </div>
                            <div id="grouplistDiv" style=" over-flow:scroll;">

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
