<?php
$site_url = site_url();

?>
<input type="hidden" id="gtotal" value="<?=$my_item_total?>" >
<table class="search_t2" style="    margin: 0; margin-top: 10px;">
    <tr>
        <th style="width:4%"><input type="checkbox" id="chkall"></th>
        <th style="width:20%">홍보제목</th>
        <th style="width:25%">연결주소</th>
        <th style="width:10%">배경색</th>
        <th style="width:8%">시작날자</th>
        <th style="width:8%">종료날자</th>
        <th style="width:5%">노출수</th>
        <th style="width:5%">접속수</th>
        <th style="width:5%">접속여부</th>
        <th style="width:10%">관리</th>
    </tr>
    <?php foreach ($advert_list as $item):?>
        <tr>
            <td><input type="checkbox" id="<?=$item['id']?>" ></td>
            <td><?=$item['advert_title']?></td>
            <td><a style="color: #ff0000;" href="<?=$item['link_url']?>"><?=$item['link_url']?></a> </td>
            <td ><lable style="background: #<?=$item['background']?>;padding: 5px 10px;color: #ffffff;"><?=$item['background']?></lable></td>
            <td><?=substr($item['start_date'],0,10)?></td>
            <td><?=substr($item['end_date'],0,10)?></td>
            <td><?=$item['send_count']?></td>
            <td><?=$item['connect_count']?></td>
            <?php if($item['is_connect']=="1") {?>
            <td>Y</td>
            <?php } else {?>
                <td>N</td>
            <?php }?>
            <td style = "margin:0;padding:0">
                <button onclick="advert_update(<?=$item['id']?>)" class = "change_survey_Img" id="<?=$item['id']?>" style = "height: 35px;font-size: 13px;background-color: #ce47a3;color: white;">수정</button>
                <!--                  <img class="change_survey_Img" src="--><?//=$site_url?><!--images/btn/btn_num06.png" role="--><?//=$item['attached']?><!--" id="--><?//=$item['id']?><!--" />-->
            </td>
        </tr>
        <?php

    endforeach;?>
</table>
