<?php
$site_url = site_url();
?>

<input type="hidden" id="my_item_total_count" value="<?=$my_item_total?>" >
<table class="import_survey_table my_surveys" id="import_survey_table">
    <thead>
    <th style="width:6%">No.</th>
    <th style="width:13%">고객사</th>
    <th style="width:10%">교육과정/차수</th>
    <th style="width:12%">설문명</th>
    <th style="width:7%">담당자명</th>
    <th style="width:7%">상태<br>(응답수)</br></th>
    <th style="width:10%">작업일시</th>
    <th style="width:10%">종료일시</th>
    <th style="width:6%">유형</th>
    <th style="width:6%">공개여부</th>
    </thead>
    <tbody>
    <?php 
    $counter = 0;
    foreach ($my_items as $item):
        $counter = $counter + 1;
    ?>
        <tr>
            <td><?=$counter?></td>
            <td><?=$item['comment']?></td>
            <td><?=$item['end_count']?></td>
            <td><?=$item['title']?></td>
            <td><?=$item['user_id']?></td>            
            <td><?=$item['question_count']?></td>
            <td><?=$item['s_start_time']?></td>
            <td><?=$item['s_end_time']?></td>
            <td><?=$item['flag']?></td>
            <td><?=$item['is_public']?></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>

