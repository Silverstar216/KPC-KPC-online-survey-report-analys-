<?php
include_once('../common.php');
if($is_guest)  
alert('회원이시라면 로그인 후 이용해 보십시오.', 
    G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/serv.php?m1='.$pgMNo.'&m2='.$pgMNo1));
if(!count($_POST['chk_fg_no']))
    alert('메세지를 이동할 그룹을 한개 이상 선택해 주십시오!!!.', $url);

$sql = "select * from {$g5['sms5_form_table']} where fg_member='{$member['mb_no']}' and fo_no in ($fo_no_list) order by fo_no desc ";
$result = sql_query($sql);
$save = array();
for ($kk=0;$row = sql_fetch_array($result);$kk++)
{
    $fo_no = $row['fo_no'];
    for ($i=0; $i<count($_POST['chk_fg_no']); $i++)
    {
        $fg_no = $_POST['chk_fg_no'][$i];
        if( !$fg_no ) continue;
        $group = sql_fetch("select * from {$g5['sms5_form_group_table']} where fg_no = '$fg_no'");
        $sql = " insert into {$g5['sms5_form_table']}
                    set fg_no='$fg_no',
                        fg_member='{$member['mb_no']}',
                        fo_name='".addslashes($row['fo_name'])."',
                        fo_content='".addslashes($row['fo_content'])."',
                        fo_datetime='".G5_TIME_YMDHIS."' ";
        sql_query($sql);
    }
    $save[$kk]['fo_no'] = $row['fo_no'];
    $save[$kk]['fg_no'] = $row['fg_no'];
}

if ($sw == 'move')
{
    foreach ($save as $v)
    {
        if( empty($v['fo_no']) ) continue;
        sql_query(" delete from {$g5['sms5_form_table']} where fo_no = '{$v['fo_no']}' ");
    }
}
$go_url = '/serv.php?m1=4&m2=5&page='.$page;
goto_url($go_url);
?>