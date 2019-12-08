<?php
$site_url = site_url();
?>


    <input type="hidden" id="my_item_total_count" value="<?=$my_item_total?>" >
    <table class="import_survey_table my_surveys" id="import_survey_table">
        <thead>
        <th style="width:6%"></th>
        <th style="width:54%">진단제목</th>
        <th style="width:10%">문항수</th>
        <th style="width:30%">기관명</th>
        </thead>
        <tbody>
        <?php foreach ($my_items as $item):?>
            <tr>
                <td><input style="width: 20px;height: 20px" type="radio" name="public_check" value="<?=$item['id']?>" role="<?=$item['attached']?>" class="check_one_mobile"></td>
                <td><a href="<?=$site_url?>preview/preview/<?=$item['id']?>" target="_blank" style="color: #ff0000"><?=$item['title']?></a></td>
                <td><?=$item['question_count'];?></td>
                <td><?=$item['name']?></td>

            </tr>
        <?php endforeach;?>
        </tbody>
    </table>

