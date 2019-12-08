<?php
$sub_menu = "300100";
include_once('./_common.php');
auth_check($auth[$sub_menu], 'w');

$sql_common = " from {$g5['member_table']} ";

$sql_search = " where mb_leave_date = '' ";
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case 'mb_point' :
            $sql_search .= " ({$sfl} >= '{$stx}') ";
            break;
        case 'mb_level' :
            $sql_search .= " ({$sfl} = '{$stx}') ";
            break;            
        case 'mb_nick' : 
           $sql_search .= " ({$sfl} like '%{$stx}%') ";
            break;
        case 'mb_tel' :
        case 'mb_hp' :
            $sql_search .= " ({$sfl} like '%{$stx}') ";
            break;
        default :
            $sql_search .= " ({$sfl} like '{$stx}%') ";
            break;
    }
    $sql_search .= " ) ";
} else {
	echo '사용자를 찾을조건을 확인하십시오!';
	exit();
}
$sql_order = " order by {$sfl} asc ";

$sql = " select * {$sql_common} {$sql_search} {$sql_order} ";
$result = sql_query($sql);
?>
<div class="tbl_head02 tbl_wrap">
    <table>
    <thead>
    <tr>
        <th scope="col" id="mb_list_nick">선택</th>    	
        <th scope="col" id="mb_list_nick">기관명</th>    	
        <th scope="col" id="mb_list_name">이름</th>
        <th scope="col" rowspan="2" id="mb_list_id">아이디</th>        
        <th scope="col" id="mb_list_mobile">전화</th>
        <th scope="col" id="mb_list_mobile">휴대폰</th>
        <th scope="col" id="mb_list_lastcall">최종접속</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        // 접근가능한 그룹수
        $mb_id = $row['mb_id'];
        $bg = 'bg'.($i%2);

    ?>
    <tr class="<?php echo $bg; ?>">
        <td headers="mb_list_chk" class="td_chk" >
            <input type="checkbox" class="id_chk" name="chk[]" value="<?php echo $row['mb_no'] ?>" id="chk_<?php echo $i ?>" nm= "<?php echo $row['mb_nick']; ?>">
        </td>
        <td headers="mb_list_name" class="td_mbname"><?php echo $row['mb_nick']; ?></td>
        <td headers="mb_list_name" class="td_mbname"><?php echo $row['mb_name']; ?></td>
        <td headers="mb_list_id" class="td_name sv_use"><?php echo $mb_id ?> </td>
        <td headers="mb_list_tel" class="td_tel"><?php echo $row['mb_tel']; ?></td>
        <td headers="mb_list_mobile" class="td_tel"><?php echo $row['mb_hp']; ?></td>
        <td headers="mb_list_lastcall" class="td_date"><?php echo substr($row['mb_today_login'],2,8); ?></td>
    </tr>
    <?php
    }
    if ($i == 0)
        echo "<tr><td colspan='7' class=\"empty_table\">자료가 없습니다.</td></tr>";
    ?>
    </tbody>
    </table>
</div>


