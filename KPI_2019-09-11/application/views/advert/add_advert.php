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
                            <input id="advert_id" type="hidden" value="<?=$advert_id?>">
                            <div style="    font-size: 18px; margin: 50px;">
                                <div class="form-group">
                                    <label for="title">홍보배너 제목</label>
                                    <input type="text" class="form-control" id="title" value="<?=$advert_title?>" name="title" placeholder="홍보제목을 입력하세요">

                                </div>

                                <div class="form-group url-attached" >
                                    <label for="link_url">연결 주소</label>
                                    <div>
                                    <input type="text" class="form-control" id="link_url" value="<?=$link_url?>" name="link_url" placeholder="연결주소를 입력하세요">

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="date">홍보 기간</label>
                                    <div>
                                    <input type="text" id="start_date" class="form-control input-inline advert_datepicker" value="<?=$start_date?>" style="    text-align: center;padding:2px 6px">
                                    ~
                                    <input type="text" id="end_date" class="form-control input-inline advert_datepicker" value="<?=$end_date?>" style="    text-align: center;padding:2px 6px">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="date">배경색</label>
                                    <div>
                                        <input class="jscolor" id="advert_background"value="<?=$background?>">
                                    </div>
                                </div>

                                <div style="margin-top: 50px;border: 5px solid #a1a1a1; padding: 20px;">
                                    <div style="text-align: center">
                                    <label style="    font-size: 20px;">폰으로 받아보기</label>
                                    </div>
                                    <div style="    margin-top: 20px;    margin-left: 100px;">
                                        <label for="date">전화 번호</label>
                                        <input type="text" class="form-control receive_phone" style="    width: 200px; display: inline-block;" id="title" name="title" placeholder="전화번호 입력하세요">
                                        <button onclick="advert_send();" class="advert_send btn " >받기</button>
                                    </div>
                                </div>

                                <div style="    text-align: center;  margin-top: 50px;">
                                    <button onclick="advert_init();" class="btn_modal_public btn " style="width: 130px; height: 30px;font-size: 18px;    float: none;">초기화</button>
                                    <button onclick="advert_save();" class="btn_modal_public btn " style="width: 130px; height: 30px;font-size: 18px;    float: none;">보 존</button>
                                </div>
                            </div>
                        </div>                            
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
