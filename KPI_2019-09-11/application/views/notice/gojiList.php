<?php
$site_url = site_url();
?>
<input type="hidden" id="totalcnt" value="<?=$gojiList_totalCount?>" >
<table class="search_t2" style="margin-top: 10px;">
    <thead>
        <th style="width:15%">구분</th>
        <th style="width:65%">제목(클릭 미리보기)</th>
        <th style="width:15%">사용</th>
        <th style="width:15%">삭제</th>
    </thead>
    <tbody>
    <?php foreach ($goji_list as $item):?>
        <tr>
            <td><?=($item['edoc_var'] == -1 ? '에듀파인':'일반')?></td>
            <td>
                <a href="<?='/uploads/html/'.$item['edoc_wurl']?>" target="_blank">
                    <?=$item['edoc_wdoc']?>
                </a>
            </td>
            <td style = "text-align: center">
                <a class="btn green" style="display: inline-block;background: #5756f1;border-color:#a3bbf5" href="javascript:onUseDoc(<?=$item['edoc_ukey']?>)">
                    사용
                </a>
            </td>
            <td style = "text-align: center">
                <a class="btn green" style="display: inline-block;background: #5756f1;border-color:#a3bbf5" href="javascript:onRemoveDoc(<?=$item['edoc_ukey']?>)">
                    삭제
                </a>
            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>