<?php
$site_url = site_url();
?>
    <input type="hidden" id="my_item_total_count" value="<?=$my_item_total?>" >
    <table class="import_survey_table my_surveys">
        <thead>
        <th style="width:5%">No</th>
        <th style="width:15%">고객사</th>
        <th style="width:27%">과정명</th>
        <th style="width:9%">과정/차수</th>        
        <th style="width:19%">설문명</th>
        <th style="width:8%">담당자명</th>
        <th style="width:10%">상태(응답수)</th>
        <th style="width:10%">시작일시</th>
        <th style="width:10%">종료일시</th>
        <th style="width:7%">공개여부</th>
        </thead>
        <tbody>
        <?php 
            $counter = 1;
            foreach ($my_items as $item):?>
            <tr>
                <td><?=$counter?></td>
                <td style="text-align: left;"><?=$item['customer'];?></td>
                <td><?=$item['edu_name'];?></td>
                <td><?=$item['edu_count'];?></td>
                <td><?=$item['title'];?></td>
                <td><?=$item['name'];?></td>
                <td>
                    <?php
                        if($item['survey_end'] == 1)
                            echo '완료';
                        else{
                            if ($item['customer'] == '') 
                                echo '작성중';
                            else 
                                echo '진행중';
                        }
                    ?>
                </td>
                <td style="padding:2px 3px"><?=$item['s_start_time'];?></td>
                <td style="padding:2px 3px"><?=$item['s_end_time'];?></td>
                <td><?=$item['show_user_id'] == "0" ? "공 개" : "개 인";?></td>   
            </tr>
        <?php 
                $counter++;
            endforeach;?>
        </tbody>
    </table>
