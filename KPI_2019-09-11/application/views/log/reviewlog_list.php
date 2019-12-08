<?php
$site_url = site_url();
?>
<input type="hidden" id="totalcnt" value="<?=$total_count?>" >
<table class="import_survey_table">
    <tr>
        <th style="width:7%">No.</th>
        <th style="width:40%">과정명</th>
        <th style="width:25%">설문기간</th>
        <th style="width:18%">응답수/대상자수(비율)</th>
        <th style="width:10%">관리</th>
    </tr>
    <?php
    $n=0;
    foreach ($result as $item):
        $n++;
        ?>
        <Tr>
            <td><input style="margin-right: 5px;" type="radio" name="reviews_option" id="reviews_option" value=<?=$item['id']?>><?=$n?></td>
            <td><?=$item['subject_name']?></td>
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
                <button class="btn btn-default" onclick="onDetailClick(<?=$item['id']?>, <?=$survey_flag?>)">상세</button>
            </td>
        </tr>
    <?php endforeach; ?>
</table>