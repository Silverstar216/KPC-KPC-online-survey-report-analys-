<?php
$site_url = site_url();
?>
    <input type="hidden" id="my_item_total_count" value="<?=$my_item_total?>" >
    <table class="import_survey_table my_surveys">
        <thead>
        <th style="width:4%"><input type="checkbox" id="chkall"></th>
        <th style="width:25%">진단명</th>
        <th style="width:7%">요인/문항수</th>
        <th style="width:10%">작성자</th>
        <th style="width:13%">작성일</th>
        <th style="width:7%">공개</th>
        <th style="width:7%">발송</th>
        <th style="width:8%">출처/저작권</th>
        <th style="width:8%">목적/개요</th>
        <th style="width:8%">관리</th>
        </thead>
        <tbody>
        <?php foreach ($my_items as $item):?>
            <tr>
                <td><input type="checkbox" id="<?=$item['id']?>" class="check_one_mobile"></td>
                <td><a href="<?=$site_url?>preview/preview/<?=$item['id']?>" target="_blank" style="color: #ff0000"><?=$item['title']?></a></td>
                <td><?=$item['question_count'];?></td>
                <td><?=$item['user_name'];?></td>                
                <td><?=substr($item['created_at'],0,10)?></td>
                <td><?=($item['is_public']=="0")?"비공개":"공개"?></td>
                <td><?=($item['is_send']=="0")?"작성중":"발송됨"?></td>
                <td></td>
                <td></td>
                <td style = "margin:0;padding:0">
                    <button class = "change_survey_Img btn btn-default btn-sm" id="<?=$item['id']?>" diagnosis_excel_id="0" 
                    style = "font-size: 12px;background-color: #696969;color: white;">진단하기</button>
                </td>                
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
