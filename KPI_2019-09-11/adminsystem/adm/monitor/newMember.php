<?php
	$sub_menu = "700900";
	define('G5_IS_ADMIN', true);
	include_once ('../../common.php');
	include_once(G5_ADMIN_PATH.'/admin.lib.php');
	auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['member_table']} ";

$today            = date("Y-m-d");
//$seven_bfday = date("Y-m-d",strtotime($today.' -1 day'));
$qryTime  = date("H:i:s",time());  
$sql_search = " where DATE_FORMAT(mb_datetime,'%Y-%m-%d') = '{$today}'   ";

if (!$sst) {
    $sst = "mb_datetime";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

// 탈퇴회원수
$sql = " select count(*) as cnt {$sql_common}  where mb_leave_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$leave_count = $row['cnt'];

// 차단회원수
$sql = " select count(*) as cnt {$sql_common} where mb_intercept_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$intercept_count = $row['cnt'];

$sql = " select * {$sql_common} {$sql_search} {$sql_order} ";
$result = sql_query($sql);

$colspan = 7;
?>
<section>
    <span class="m_title">금일 신규가입회원 : 조회시간(<?=$qryTime?>)</span><span class="m_info">총회원수 <?php echo number_format($total_count) ?>명 중 차단 <?php echo number_format($intercept_count) ?>명, 탈퇴 : <?php echo number_format($leave_count) ?>명</span>
    <div class="tbl_head01 tbl_wrap">
        <table>
        <caption>신규가입회원</caption>
        <thead>
        <tr>
            <th scope="col">회원아이디</th>
            <th scope="col">이름</th>
            <th scope="col">기관명</th>
            <th scope="col">전화</th>
            <th scope="col">휴대폰</th>
            <th scope="col">이메일</th>
            <th scope="col">최근접속</th>            
        </tr>
        </thead>
        <tbody>
        <?php
        for ($i=0; $row=sql_fetch_array($result); $i++)
        {
            $mb_nick = get_sideview($row['mb_id'], $row['mb_nick'], $row['mb_email'], $row['mb_homepage']);
            $mb_id = $row['mb_id'];
            if ($row['mb_leave_date'])
                $mb_id = $mb_id;
            else if ($row['mb_intercept_date'])
                $mb_id = $mb_id;

        ?>
        <tr>
            <td class="td_mbid"><?php echo $mb_id ?></td>
            <td class="td_mbname"><?php echo $row['mb_name'] ?></td>
            <td class="td_mbname sv_use"><div><?php echo $mb_nick ?></div></td>
            <td class="td_mbname sv_use"><div><?php echo $row['mb_tel']  ?></div></td>
            <td class="td_mbname sv_use"><div><?php echo $row['mb_hp'] ?></div></td>
            <td class="td_mbname sv_use"><div><?php echo $row['mb_email']  ?></div></td>
	<td class="td_mbname sv_use"><div><?php echo $row['mb_datetime']  ?></div></td>
        </tr>
        <?php
            }
        if ($i == 0)
            echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
        ?>
        </tbody>
        </table>
    </div>
</section>