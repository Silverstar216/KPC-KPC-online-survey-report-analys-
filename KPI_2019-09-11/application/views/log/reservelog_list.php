<?php
$site_url = site_url();
?>
<input type="hidden" id="totalcnt" value="<?=$total_count?>" >
<table class="import_survey_table">
    <tr>
        <th style="width:5%">번호</th>
        <th style="width:13%">발신번호</th>
        <th style="width:35%">내용</th>
        <th style="width:5%">총건수</th>
        <th style="width:22%">예약일시</th>
        <th style="width:10%">예약시간변경</th>
        <th style="width:10%">삭제</th>
    </tr>
    <?php
    $n=0;
    foreach ($result as $item):
        $n++;
        ?>
        <Tr>
            <td ><?=$n?></td>
            <td ><?=$item['calling_number']?></td>
            <td ><?=$item['content']?></td>
            <td><?=$item['mobile_count']?></td>
            <td><input type="text" id="reserve_date<?=$n?>" class="form-control input-inline notice_datepicker" value="<?=$item['start_time']?>" style="width: 100%;"></td>
            <td>
                <button class="btn btn-default" style="width: 100%; padding: 5px 5px;" onclick="onEditClick(<?=$item['id']?>,<?=$n?>)">수정</button>
            </td >
            <td >
                <button class="btn btn-default" style="width: 100%; padding: 5px 5px;" onclick="onDeleteClick(<?=$item['id']?>,<?=$n?>)">삭제</button>
            </td>
            <input type="hidden" id="before_date<?=$n?>" class="form-control input-inline notice_datepicker" value="<?=$item['start_time']?>">
        </tr>
    <?php endforeach; ?>
</table>
