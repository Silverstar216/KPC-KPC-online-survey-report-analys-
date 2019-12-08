<?php
$site_url = site_url();
?>
<input type="hidden" id="totalcnt" value="<?=$total_count?>" >
<table class="import_survey_table">
    <tr>
        <th style="width:5%">No.</th>
        <th style="width:10%">진단명</th>
        <th style="width:10%">고객사명</th>
        <th style="width:10%">시행명</th>
        <th style="width:10%">교육과정명</th>
        <th style="width:5%">차수</th>
        <th style="width:10%">대상</th>
        <th style="width:5%">구분</th>
        <th style="width:10%">담당자</th>
        <th style="width:10%">상태(응답자수)</th>
        <th style="width:10%">시작일시</th>
        <th style="width:10%">종료일시</th>
        <th style="width:5%">유형</th>
        <th style="width:5%">공개</th>
        <th style="width:5%">관리</th>
    </tr>
    <?php
    $n=0;
    foreach ($result as $item):
        $n++;
        ?>
        <Tr>
            <td><?=$n?></td>
            <td><?=$item['title']?></td>
            <?php if($item['end_count'] > 0) { ?>
                <td><?=$item['end_count']?></td>
            <?php }else { ?>
                <td><?php echo substr($item['start_time'],0,10)?> / <?php echo substr($item['end_time'],0,10)?></td>
            <?php } ?>

            <td>
                <?=$item['responseCount']?>/<?=$item['successCount']?>(
                <?php
                if ($item['successCount'] > 0)
                    echo intval($item['responseCount'] * 100 / $item['successCount']);
                else
                    echo 0;
                ?>
                %)
            </td>
            <td>
                <button class="btn btn-default" onclick="onDetailClick(<?=$item['id']?>)">상세</button>
            </td>
        </tr>
    <?php endforeach; ?>
</table>