<?php
include_once("../common.php");

$colspan = 3;

$no_group = sql_fetch("select bg_no,bg_name,bg_count,bg_member,bg_nomember,
ifnull((select count(*) from {$g5['sms5_book_table']} as sg5 where sg5.bg_no = 1 and mb_no = '{$member['mb_no']}' and bk_receipt= 1),0) as bg_receipt,    
bg_reject from {$g5['sms5_book_group_table']} where bg_no=1 ");

$group = array();
$qry = sql_query("select bg_no,bg_name,bg_count,bg_member,bg_nomember,
ifnull((select count(*) from {$g5['sms5_book_table']} as sg5 
    where sg5.bg_no = w.bg_no and mb_no = '{$member['mb_no']}' and bk_receipt= 1),0) as bg_receipt,    
bg_reject from {$g5['sms5_book_group_table']} as w where bg_no>1 and bg_member = '{$member['mb_no']}'  order by bg_name");
while ($res = sql_fetch_array($qry)) array_push($group, $res);
?>
<div class="tbl_head03 tbl_wrap">
    <div id="gr_help">상단 체크(전체),열 체크박스 선택후 상단 [선택추가]로 추가</div>
    <table>
    <thead>
    <tr>
        <th class="th_chk" scope="col">
            <label for="all_checked" class="sound_only">현페이지전체</label>
            <input type="checkbox" id="all_checked" onclick="sms_obj.group_all_checked(this.checked)">
        </th>        
        <th scope="col" class="th_list01"><button id="gr_allbtn" type="button" onclick="sms_obj.group_multi_add()">선택추가</button><button type="button" id="txtqbtn" onclick="sms_obj.group_txt_sel()">찾기 선택</button><input type="text" id="gr_seltext" size="14" value="" onkeypress="if(event.keyCode==13) sms_obj.group_txt_sel()"></th>
        <th scope="col" class="th_num">갯수</th>        
    </tr>
    </thead>
    </table>
    <div id = "gr_tbody">   
    <table>     
    <tbody >

<?php 
    $line = 0; 
if ($no_group['bg_receipt'] > 0)  { ?>
    <tr>
        <td class="td_chk">
            <label for="bkg_no_1" class="sound_only"><?php echo $no_group['bg_name']?></label>
            <input type="checkbox" name="bkg_no" value="1" id="bkg_no_1" cnt="<?php echo $no_group['bg_receipt'] ?>" grnm="<?php echo $no_group['bg_name']?>">
        </td>        
        <td><a href="javascript:sms_obj.person(1)"><?php echo $no_group['bg_name']?></a></td>
        <td class="td_num"><?php echo number_format($no_group['bg_receipt'])?></td>
    </tr>
    <?php $line = 1; }
    
    for ($i=0; $i<count($group); $i++) {        
        if ($group[$i]['bg_receipt'] > 0) {            
            $bg = 'bg'.($line++%2);
    ?>
    <tr class="<?php echo $bg; ?>">
        <td class="td_chk">
            <label for="bkg_no_<?php echo $group[$i]['bg_no']?>" class="sound_only"><?php echo $group[$i]['bg_name']?></label>
            <input type="checkbox" name="bkg_no" value="<?php echo $group[$i]['bg_no']?>" id="bkg_no_<?php echo $group[$i]['bg_no']?>" cnt="<?php echo $group[$i]['bg_receipt'] ?>" grnm="<?php echo $group[$i]['bg_name']?>">
        </td>                
        <td><a href="javascript:sms_obj.person(<?php echo $group[$i]['bg_no']?>)"><?php echo $group[$i]['bg_name']?></a></td>
        <td class="td_num"><?php echo number_format($group[$i]['bg_receipt'])?></td>        
    </tr>
    <?php } } ?>
    </tbody>
    </table>
     </div>
</div>