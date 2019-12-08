<?php
$site_url = site_url();
?>


    <input type="hidden" id="my_item_total_count" value="<?=$my_item_total?>" >
    <table class="import_survey_table my_surveys">
        <thead>
        <th style="width:5%">No.</th>
        <th style="width:15%">진단명</th>
        <th style="width:10%">고객사명</th>
        <th style="width:10%">시행명</th>
        <th style="width:5%">대상</th>
        <th style="width:5%">구분</th>
        <th style="width:7%">담당자명</th>
        <th style="width:7%">상태<br>(응답수)</br></th>
        <th style="width:10%">시작일시</th>
        <th style="width:10%">종료일시</th>
        <th style="width:5%">유형</th>
        <th style="width:5%">공개<br>여부</br></th>        
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
