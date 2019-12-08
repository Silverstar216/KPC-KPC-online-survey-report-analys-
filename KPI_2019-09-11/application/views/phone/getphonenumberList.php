<?php
$site_url = site_url();
?>
<input type="hidden" id="totalcnt" value="<?=$phonenumberCont?>" >
<table class="import_survey_table" style="margin-top: 10px;">
    <thead>
    <th style="width:5%"><input type="checkbox" id="chkall"></th>
    <th style="width:15%">이름</th>
    <th style="width:25%">휴대폰</th>
    <th style="width:15%">그룹</th>
   <!-- <th style="width:20%">주민등록번호</th>-->
    <th style="width:25%">메모</th>
    <th style="width:15%">관리</th>
    </thead>
    <tbody>
    <?php foreach ($mobiles as $item):?>
        <tr>
            <td><input type="checkbox" id="<?=$item['id']?>" class="check_one_mobile" role="<?=$item['gid']?>"></td>
            <td><?=$item['name']?></td>
            <td><?=$item['mobile'];?></td>
            <td><?=$item['gname']?></td>
           <!-- <td><?/*=$item['address_num'];*/?></td>-->
            <td><?=$item['memo1'];?></td>
            <td><img class="phonecursorImg" src="<?=$site_url?>images/btn/btn_num01.png" role="<?=$item['gid']?>" id="c_<?=$item['id']?>" />
                <img class="phonecursorImg" src="<?=$site_url?>images/btn/btn_num02.png" role="<?=$item['name']?>"style="cursor:pointer" id="d_<?=$item['mobile']?>" />

            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
