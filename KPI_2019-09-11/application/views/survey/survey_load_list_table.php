<?php
$site_url = site_url();
?>
    <input type="hidden" id="my_item_total_count" value="<?=$my_item_total?>" >
    <table class="import_survey_table my_surveys">
        <thead>
        <th style="width:5%"><input type="checkbox" id="chkall"></th>
        <th style="width:65%">설문제목</th>
        <th style="width:7%">문항수</th>
        <th style="width:10%">작성자</th>
        <th style="width:10%">설문형태</th>
        <th style="width:10%">관 리</th>        
        </thead>
        <tbody>
        <?php foreach ($my_items as $item):?>
            <tr>
                <td><input type="checkbox" id="<?=$item['id']?>" class="check_one_mobile"></td>
                <td style="text-align: left;"><?=$item['title']?></td>
                <td><?=$item['question_count'];?></td>
                <td><?=$item['name'];?></td>      
                <td><?=$item['show_user_id'] == "0" ? "공 개" : "개 인";?></td>   
                <td style = "margin:0;padding:0">
                    <button class = "change_survey_Img btn btn-default btn-sm" role="<?=$item['attached']?>" id="<?=$item['id']?>" education_id="<?=$item['education_id']?>" 
                    style = "font-size: 12px;background-color: #696969;color: white;">불러오기</button>
                </td>          
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
