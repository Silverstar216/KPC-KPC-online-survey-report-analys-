<?php
$site_url = site_url();
?>


    <input type="hidden" id="my_item_total_count" value="<?=$my_item_total?>" >
    <table class="import_survey_table my_surveys" style="margin-top: 10px;">
        <thead>
        <th style="width:5%"><input type="checkbox" id="chkall"></th>
        <th style="width:40%">설문제목</th>
        <th style="width:5%">문항수</th>
        <th style="width:20%">설문기간</th>
        <th style="width:10%">등록일</th>
        <th style="width:10%">첨부문서</th>
        <th style="width:10%">관리</th>
        </thead>
        <tbody>
        <?php foreach ($my_items as $item):?>
            <tr>
                <td><input type="checkbox" id="<?=$item['id']?>" class="check_one_mobile"></td>
                <td><a href="<?=$site_url?>preview/preview/<?=$item['id']?>" target="_blank" style="color: #ff0000"><?=$item['title']?></a></td>
                <td><?=$item['question_count'];?></td>
                <td><?php echo substr($item['start_time'],0,10)?>/<?php echo substr($item['end_time'],0,10)?></td>
                <td><?php echo $item['created_at']?></td>
                <?php if($item['file_url'] ==="" || empty($item['file_url'])) {
                    ?>
                    <td><?=$item['file_url']?></td>
                <?php }else { ?>
                    <td><a href="<?=$item['file_url']?>" target="_blank">첨부문서</a></td>
                <?php } ?>
                <td style = "margin:0;padding:0">
                    <button class = "change_survey_Img" role="<?=$item['attached']?>" id="<?=$item['id']?>" style = "height: 35px;font-size: 13px;background-color: #ce47a3;color: white;">불러오기</button>
<!--                  <img class="change_survey_Img" src="--><?//=$site_url?><!--images/btn/btn_num06.png" role="--><?//=$item['attached']?><!--" id="--><?//=$item['id']?><!--" />-->
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
