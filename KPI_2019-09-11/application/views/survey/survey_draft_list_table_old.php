<?php
$site_url = site_url();
?>
    <input type="hidden" id="my_item_total_count" value="<?=$my_item_total?>" >
    <table class="import_survey_table my_surveys">
        <thead>
        <th style="width:5%"><input type="checkbox" id="chkall"></th>
        <th style="width:35%">설문제목</th>
        <th style="width:7%">문항수</th>
        <th style="width:20%">설문기간</th>
        <th style="width:15%">등록일</th>
        <th style="width:7%">첨부문서</th>
        <th style="width:10-%">관리</th>
        </thead>
        <tbody>
        <?php foreach ($my_items as $item):?>
            <tr>
                <td><input type="checkbox" id="<?=$item['id']?>" class="check_one_mobile"></td>
                <td style="text-align: left;"><a href="<?=$site_url?>preview/preview/<?=$item['id']?>" target="_blank"><?=$item['title']?></a></td>
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
                    <button class = "change_survey_Img" role="<?=$item['attached']?>" id="<?=$item['id']?>" education_id="<?=$item['education_id']?>" 
                    style = "font-size: 13px;background-color: #696969;color: white;">불러오기</button>
                </td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
