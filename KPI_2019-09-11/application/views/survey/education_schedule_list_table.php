<?php
$site_url = site_url();
?>

<input type="hidden" id="my_item_total_count" value="<?=$my_item_total?>" >
<table class="import_survey_table my_surveys" id="import_survey_table">
    <thead>
    <th style="width:7%">No.</th>
    <th style="width:19%">부서명</th>
    <th style="width:30%">과정명</th>
    <th style="width:8%">형태</th>
    <th style="width:7%">차수</th>
    <th style="width:10%">시작일</th>
    <th style="width:10%">종료일</th>
    <?php 
    if ($survey_flag != 1) {
    ?>
    <th style="width:15%">고객사명</th>
    <?php 
    }
    ?>
    <th style="width:8%">인원수</th>
    </thead>
    <tbody>
    <?php 
    $counter = 0;
    $prev_name = '';
    $prev_countname = '';
    $prev_begindate = '';

    foreach ($my_items as $item):        
        // if ($prev_name != $item['subject_name'] ||
        //     $prev_countname != $item['count_name'] || 
        //     $prev_begindate != $item['begin_date']) {
        //         $counter = $counter + 1;
        //         $prev_name = $item['subject_name'];
        //         $prev_countname = $item['count_name'];
        //         $prev_begindate = $item['begin_date'];
        $counter = $counter + 1;
    ?>
        <tr>
            <td><input type="radio" name="education_id" value="<?=$item['id']?>" <?php if ($counter == 1) echo(' checked');?>>&nbsp;&nbsp;&nbsp;<?=$counter?></td>
            <td><?=$item['subject_group']?></td>
            <td style="text-align:left"><?=$item['subject_name']?></td>
            <td><?=$item['main_type']?></td>
            <td><?=$item['count_name']?></td>
            <td><?=$item['begin_date']?></td>            
            <td><?=$item['end_date']?></td>
            <?php 
            if ($survey_flag != 1) {
            ?>
            <td><?=$item['customer']?></td>
            <?php 
            }
            ?>
            <td><?=$item['student_count']?></td>
        </tr>
    <?php 
        //    }
    endforeach;?>
    </tbody>
</table>

