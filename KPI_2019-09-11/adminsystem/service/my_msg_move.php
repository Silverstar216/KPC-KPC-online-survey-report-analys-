<?php
define('G5_IS_SERVICE', true);
include_once('../common.php');
$pgMNo = 4;
$pgMNo1 = 5;
if($is_guest)  
alert('회원이시라면 로그인 후 이용해 보십시오.', 
	G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/serv.php?m1='.$pgMNo.'&m2='.$pgMNo1));

include_once('../_head.php');
include_once('../_ele_sub_menu.php');
if ($sw != 'move'){
    alert('sw 값이 제대로 넘어오지 않았습니다.');
}

$g5['title'] = '내 메세지 그룹 이동';
include_once(G5_PATH.'/head.sub.php');

$fo_no_list = implode(',', $_POST['fo_no']);

$sql = " select * from {$g5['sms5_form_group_table']} where fg_member = '{$member['mb_no']}' order by fg_no ";
$result = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++)
{
    $list[$i] = $row;
}
?>
<div class="titlegroup">
     <em>내 메세지</em>      
</div>
<!-- 휴대폰번호 -->
<div class="phonegroup">
<div class="phonegroupin">
<div class="phonegroupwrap">
<div id="sub_content">
<div id="copymove" class="new_win">
    <h1 id="win_title"><?php echo $g5['title'] ?></h1>

    <form name="fboardmoveall" method="post" action="/service/my_msg_move_update.php" onsubmit="return fboardmoveall_submit(this);">
    <input type="hidden" name="sw" value="<?php echo $sw ?>">
    <input type="hidden" name="fo_no_list" value="<?php echo $fo_no_list ?>">
    <input type="hidden" name="url" value="<?php echo $_SERVER['HTTP_REFERER'] ?>">

    <div class="tbl_head01 tbl_wrap">
        <table>
        <caption>이동할 그룹을 한개 이상 선택하여 주십시오.</caption>
        <thead>
        <tr>
            <th scope="col">선택</th>
            <th scope="col">그룹</th>
        </tr>
        </thead>
        <tbody>
        <?php for ($i=0; $i<count($list); $i++) { ?>
        <tr>
            <td class="td_chk">
                <input type="radio" value="<?php echo $list[$i]['fg_no'] ?>" id="chk<?php echo $i ?>" name="chk_fg_no[]">
            </td>
            <td>
                <label for="chk<?php echo $i ?>"><?php echo $list[$i]['fg_name'] ?></label>
            </td>
        </tr>
        <?php } ?>
        </tbody>
        </table>
    </div>

    <div class="win_btn">
        <input type="submit" value="이동" id="btn_submit" class="btn_submit">
         <a href="/serv.php?m1=4&m2=5?<?php echo $_SERVER['QUERY_STRING']?>">목록</a>
    </div>
    </form>

</div>

<script>

function all_checked(sw) {
    var f = document.fboardmoveall;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_fg_no[]")
            f.elements[i].checked = sw;
    }
}

function fboardmoveall_submit(f)
{
    var check = false;

    if (typeof(f.elements['chk_fg_no[]']) == 'undefined')
        ;
    else {
        if (typeof(f.elements['chk_fg_no[]'].length) == 'undefined') {
            if (f.elements['chk_fg_no[]'].checked)
                check = true;
        } else {
            for (i=0; i<f.elements['chk_fg_no[]'].length; i++) {
                if (f.elements['chk_fg_no[]'][i].checked) {
                    check = true;
                    break;
                }
            }
        }
    }

    if (!check) {
        alert('내 메세지 그룹을 한개 이상 선택해 주십시오.');
        return false;
    }

    document.getElementById('btn_submit').disabled = true;

    return true;
}
</script>
</div>
</div>
</div>
</div>
<?php
include_once('../_tail.php');
?>
