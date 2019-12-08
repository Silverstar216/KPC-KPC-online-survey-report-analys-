<?php
include_once("../common.php");
if($is_guest)  
alert('회원이시라면 로그인 후 이용해 보십시오.',
    Main_DOMAIN.'join/login_view');
$g5['title'] = "휴대폰번호 업데이트";

$g5['sms5_demo'] = 0;

$is_hp_exist = false;

function chk_edufile_sdata3($grnm){
    if (preg_match("/^학(생|부모)\(([1-6]{1})-([\s1-9]{1}[\d]{1})\)$/", $grnm,$rtnarr)) {
        if ($rtnarr[1] == '부모'){
            $rtnarr[1] = '1';
        } else if ($rtnarr[1] == '생'){
            $rtnarr[1] = '2';
        } else {
            $rtnarr[1] = '';
        }        
        return $rtnarr;
    } else {
        return false;
    }
}
$update_class_qry = ", bk_year=null, bk_grade=null, bk_class=null,bk_stid=null,bk_kind=null ";
if ($w=='d') {
    $update_class_qry = "";
} if ($in_bk_stid==''){
} if ($in_bk_stid < '1'){
} else {
    $gr_nm_sql = " select bg_name from {$g5['sms5_book_group_table']} where bg_no = '$bg_no' ";       
    $gr_nm_row = sql_fetch($gr_nm_sql);
    if($gr_nm_row['bg_name']) {
        $chkbgn = chk_edufile_sdata3($gr_nm_row['bg_name']);
        if ($chkbgn==false){
        } else {
            $update_class_qry = ", bk_year='2015', bk_grade='{$chkbgn[2]}', bk_class='{$chkbgn[3]}',bk_stid='{$in_bk_stid}',bk_kind='{$chkbgn[1]}' ";
        }
    }
}
$bk_hp = get_hp($bk_hp);

if ($w=='u') // 업데이트
{
    if (!$bg_no) $bg_no = 0;

    if (!$bk_receipt) $bk_receipt = 0; else $bk_receipt = 1;

    if (!strlen(trim($bk_name)))
        alert('이름을 입력해주세요');

    if ($bk_hp == '')
        alert('휴대폰번호만 입력 가능합니다.');
/*
    $res = sql_fetch("select * from {$g5['sms5_book_table']} where bk_no<>'$bk_no' and bk_hp='$bk_hp'");
    if ($res)
        alert('같은 번호가 존재합니다.');
*/
    $res = sql_fetch("select * from {$g5['sms5_book_table']} where bk_no='$bk_no'");
    if (!$res)
        alert('존재하지 않는 데이터 입니다.');

    if ($bg_no != $res['bg_no']) {
        sql_query("update {$g5['sms5_book_group_table']} set bg_count = bg_count - 1  where bg_no='{$res['bg_no']}'");
        sql_query("update {$g5['sms5_book_group_table']} set bg_count = bg_count + 1 where bg_no='$bg_no'");
    }

    sql_query("update {$g5['sms5_book_table']} set bg_no='$bg_no', bk_name='$bk_name', bk_hp='$bk_hp', bk_datetime='".G5_TIME_YMDHIS."', bk_memo='".addslashes($bk_memo)."'".$update_class_qry." where bk_no='$bk_no'");

    $get_bg_no = $bg_no;

    $go_url = '/serv.php?m1=4&m2=3&bk_no='.$bk_no.'&amp;w='.$w.'&amp;page='.$page;
    if( $is_hp_exist ){ //중복된 회원 휴대폰번호가 있다면
        //alert( "중복된 회원 휴대폰번호가 있어서 회원정보에는 반영되지 않았습니다.", $go_url );
        goto_url($go_url);
    } else {
        goto_url($go_url);
    }
    exit;
}
else if ($w=='d') // 삭제
{
    if (!is_numeric($bk_no))
        alert('고유번호가 없습니다.');

    $res = sql_fetch("select * from {$g5['sms5_book_table']} where bk_no='$bk_no'");
    if (!$res)
        alert('존재하지 않는 데이터 입니다.');

    sql_query("delete from {$g5['sms5_book_table']} where bk_no='$bk_no'");
/*
    if (!is_numeric($bk_no))
        alert('고유번호가 없습니다.');

    $res = sql_fetch("select * from $g5[sms5_book_table] where bk_no='$bk_no'");
    if (!$res)
        alert('존재하지 않는 데이터 입니다.');

    if (!$res[mb_id])
    {
        if ($res[receipt] == 1)
            $sql_sms = "bg_receipt = bg_receipt - 1";
        else
            $sql_sms = "bg_reject = bg_reject - 1";

        sql_query("delete from $g5[sms5_book_table] where bk_no='$bk_no'");
        sql_query("update $g5[sms5_book_group_table] set bg_count = bg_count - 1, bg_nomember = bg_nomember - 1, $sql_sms where bg_no = '$res[bg_no]'");
    }
    else
        alert("회원은 삭제할 수 없습니다.\\n\\n회원관리 메뉴에서 삭제한 후\\n\\n회원정보업데이트 메뉴를 실행 해주세요.");
*/
}
else // 등록
{
    if (!$bg_no) $bg_no = 1;

    if (!$bk_receipt) $bk_receipt = 0; else $bk_receipt = 1;

    if (!strlen(trim($bk_name)))
        alert('이름을 입력해주세요');

    if ($bk_hp == '')
        alert('휴대폰번호만 입력 가능합니다.');

    $res = sql_fetch("select * from {$g5['sms5_book_table']} where bg_no='$bg_no' and bk_hp='$bk_hp'");
    if ($res)
        alert('같은 번호가 존재합니다.');

    if ($bk_receipt == 1)
        $sql_sms = "bg_receipt = bg_receipt + 1";
    else
        $sql_sms = "bg_reject = bg_reject + 1";

    sql_query("insert into {$g5['sms5_book_table']} set bg_no='$bg_no', mb_no = '{$member['mb_no']}', bk_name='".addslashes($bk_name)."', bk_hp='$bk_hp'".
        $update_class_qry.", bk_receipt='1', bk_datetime='".G5_TIME_YMDHIS."', bk_memo='".addslashes($bk_memo)."'");

    $get_bg_no = $bg_no;
}

$go_url = '/serv.php?m1=4&m2=3&page='.$page.'&amp;bg_no='.$get_bg_no.'&amp;ap='.$ap;
goto_url($go_url);
?>