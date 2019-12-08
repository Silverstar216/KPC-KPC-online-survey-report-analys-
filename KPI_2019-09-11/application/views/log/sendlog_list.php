<?php
$site_url = site_url();
?>
<input type="hidden" id="totalcnt" value="<?=$total_count?>" >
<table class="import_survey_table">
    <tr>
        <th style="width:4%">번호</th>
        <th style="width:5%">구분</th>
        <th style="width:10%">발신번호</th>
        <th style="width:35%">메세지</th>
        <th style="width:15%">전송일시</th>
        <th style="width:3%">총건</th>
        <th style="width:3%">성공</th>
        <th style="width:3%">대기</th>
        <th style="width:3%">실패</th>
        <th style="width:3%">조회</th>
        <th style="width:8%">첨부문서</th>
        <th style="width:8%">관리</th>
    </tr>
    <?php
    $n=0;
    $kind ="";
    foreach ($result as $item):
        $n++;

        if($item['message_kind']=="0") {
            $kind = "SMS";
        }else {
            $kind ="LMS";
        }
        ?>
        <Tr>
            <td><?=$n?></td>
            <td><?=$kind?></td>
            <td><?=$item['calling_number']?></td>
            <td><?=$item['content']?></td>
            <td><?=$item['start_time']?></td>
            <td><?=$item['mobile_count']?></td>
            <td><?=$item['successCount']?></td>
            <td><?=$item['waitCount']?></td>
            <td><?=$item['failureCount']?></td>
            <td><?=$item['reply_count']?></td>
            <?php if($item['file_url'] ==="" || empty($item['file_url'])) {
            ?>
            <td><?=$item['file_url']?></td>
            <?php }else { ?>
            <td><a href="<?=$item['file_url']?>" target="_blank">첨부문서</a></td>
            <?php } ?>
            <td>
                <button class="btn btn-default" style="padding: 5px 8px;" onclick="onDetailClick(<?=$item['id']?>)">상세</button>
            </td>
        </tr>
    <?php endforeach; ?>
</table>