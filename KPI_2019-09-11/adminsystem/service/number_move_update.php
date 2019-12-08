<?php
include_once('../common.php');
if($is_guest)  
alert('회원이시라면 로그인 후 이용해 보십시오.', 
    G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/serv.php?m1='.$pgMNo.'&m2='.$pgMNo1));
if(!count($_POST['chk_bg_no']))
    alert('번호를 '.$act.'할 그룹을 한개 이상 선택해 주십시오.', $url);

$sql = "select * from {$g5['sms5_book_table']} where bk_no in ($bk_no_list) order by bk_no desc ";
$result = sql_query($sql);
$save = array();
$save_group = array();

for ($kk=0;$row = sql_fetch_array($result);$kk++)
{
    $bk_no = $row['bk_no'];
    for ($i=0; $i<count($_POST['chk_bg_no']); $i++)
    {
        $bg_no = $_POST['chk_bg_no'][$i];
        if( !$bg_no ) continue;

        $sql = " insert into {$g5['sms5_book_table']}
                    set bg_no='$bg_no',
                        mb_id='{$row['mb_id']}',
                        mb_no = '{$member['mb_no']}',
                        bk_name='".addslashes($row['bk_name'])."',
                        bk_hp='{$row['bk_hp']}',
                        bk_receipt='1',
                        bk_year = '{$row['bk_year']}',
                        bk_grade = '{$row['bk_grade']}',
                        bk_class = '{$row['bk_class']}',
                        bk_stid = '{$row['bk_stid']}',
                        bk_kind = '{$row['bk_kind']}',
                        bk_datetime='".G5_TIME_YMDHIS."' ";
        sql_query($sql);
        if( !in_array($bg_no, $save_group) ){
            array_push( $save_group, $bg_no );
        }
    }
    $save[$kk]['bg_no'] = $row['bg_no'];
    $save[$kk]['bk_no'] = $row['bk_no'];
    $save[$kk]['mb_id'] = $row['mb_id'];
    $save[$kk]['bk_receipt'] = $row['bk_receipt'];
}

if ($sw == 'move')
{
    foreach ($save as $v)
    {
        if( empty($v['bk_no']) ) continue;
        sql_query(" delete from {$g5['sms5_book_table']} where bk_no = '{$v['bk_no']}' and  mb_no = '{$member['mb_no']}' ");
        if( !in_array($v['bg_no'], $save_group) ){
            array_push( $save_group, $v['bg_no'] );
        }
    }
}

$go_url = '/serv.php?m1=4&m2=3&page='.$page;
goto_url($go_url);
?>