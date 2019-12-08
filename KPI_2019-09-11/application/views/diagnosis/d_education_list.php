<?php
$site_url = site_url();
?>
    <input type="hidden" id="my_item_total_count" value="<?=$my_item_total?>" >
    <table class="import_survey_table my_surveys">
        <thead>
        <th style="width:4%"><input type="checkbox" id="chkall"></th>
        <th style="width:25%">고객사명</th>
        <th style="width:25%">과정명</th>
        <th style="width:10%">과정차수</th>
        <th style="width:13%">작성일</th>
        <th style="width:7%">평가자수</th>
        <th style="width:10%">관리</th>
        </thead>
        <tbody>
        <?php foreach ($my_items as $item): ?>
            <tr>
                <td><input type="checkbox" id="<?=$item['id']?>" class="check_one_mobile"></td>
                <td><?=$item['customer_name'];?></td>
                <td><?=$item['education_name'];?></td>
                <td><?=$item['education_count'];?></td>
                <td><?=$item['upload_date'];?></td>
                <td><?=$item['clients'];?></td>
                <td style = "margin:0;padding:0">
                    <button class = "change_survey_Img btn btn-default btn-sm" id="<?=$prev_survey_id?>" diagnosis_excel_id="<?=$item['id']?>" 
                    style = "font-size: 12px;background-color: #696969;color: white;">불러오기</button>
                </td>     
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
