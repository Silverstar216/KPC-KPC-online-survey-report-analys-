<?php
define('G5_IS_SERVICE', true);
include_once('../common.php');
$pgMNo = 4;
$pgMNo1 = 3;
if($is_guest)  
alert('회원이시라면 로그인 후 이용해 보십시오.', 
    G5_BBS_URL.'/login.php?url='.urlencode(G5_URL.'/serv.php?m1='.$pgMNo.'&m2='.$pgMNo1));

include_once('../_head.php');

function chk_edufile_sdata($grnm){

    if (preg_match("/^학(생|부모)\(([1-6]{1})-([\s1-9]{1}[\d]{1})\)$/", $grnm,$rtnarr)) {
        $rtnarr[1] = '학'.$rtnarr[1];
        return $rtnarr;
    } else {
        return false;
    }
}
$get_bg_name = '';
$colspan = 4;

$g5['title'] = "휴대폰번호 ";

if ($w == 'u' && is_numeric($bk_no)) {
    $write = sql_fetch("select * from {$g5['sms5_book_table']} where bk_no='$bk_no' and mb_no = '{$member['mb_no']}' ");
    if (!$write)
        alert('데이터가 없습니다.');
    $g5['title'] .= '수정';
}
else  {
    $write['bg_no'] = $bg_no;
    $g5['title'] .= '추가';
}

if (!is_numeric($write['bk_receipt']))
    $write['bk_receipt'] = 1;

$no_group = sql_fetch("select bg_no,bg_name,bg_count,bg_member,bg_nomember,
ifnull((select count(*) from {$g5['sms5_book_table']} as sg5 where sg5.bg_no = 1 and mb_no = '{$member['mb_no']}' and bk_receipt= 1),0) as bg_receipt,    
bg_reject from {$g5['sms5_book_group_table']} where bg_no = 1");
?>
   <div class="subTopTab">
    <ul class="item">
        <li><a href="#" title="페이지 이동" class="active"><span>휴대폰 번호</span></a></li>
           
    </ul>
    </div>

<div class="titlegroup">
        <em>휴대폰 번호</em>      
         <div class="navgroup">     
                 <p>Home <span class="rt">&gt;</span> 전화번호관리 <span class="rt">&gt;</span> 휴대폰 번호</p>
        </div>     
</div>
<!-- 휴대폰번호 -->
<div class="phonegroup">
<div class="phonegroupin">
<div class="phonegroupwrap">
<div id="sub_content">
<form name="book_form" id="book_form" method="post" action="./num_book_update.php">
<input type="hidden" name="w" value="<?php echo $w?>">
<input type="hidden" name="page" value="<?php echo $page?>">
<input type="hidden" name="ap" value="<?php echo $ap?>">
<input type="hidden" name="bk_no" value="<?php echo $write['bk_no']?>">
<input type="hidden" name="mb_id" id="mb_id" value="<?php echo $write['mb_id']?>">
<input type="hidden" name="get_bg_no" id="get_bg_no" value="<?php echo $write['bg_no']?>">
<input type="hidden" name="get_bk_year" id="get_bk_year" value="<?php echo $write['bk_year']?>">
<input type="hidden" name="get_bk_stid" id="get_bk_stid" value="<?php echo $write['bk_stid']?>">
<input type="hidden" name="in_bk_stid" id="in_bk_stid" value="<?php echo $write['bk_stid']?>">
<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?></caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="bg_no">그룹 <strong class="sound_only"> 필수</strong></label></th>
        <td>
            <select name="bg_no" id="bg_no" required class="required">
                <option value="1"><?php echo $no_group['bg_name']?> (<?php echo number_format($no_group['bg_receipt'])?> 명)</option>
                <?php
                $qry = sql_query("select bg_no,bg_name,bg_count,bg_member,bg_nomember,
ifnull((select count(*) from {$g5['sms5_book_table']} as sg5 
    where sg5.bg_no = w.bg_no and mb_no = '{$member['mb_no']}' and bk_receipt= 1),0) as bg_receipt,    
bg_reject from {$g5['sms5_book_group_table']} as w where bg_no> 1 and bg_member = '{$member['mb_no']}' order by bg_name");
                while($res = sql_fetch_array($qry)) {
                ?>
                <option value="<?php echo $res['bg_no']?>" <?php echo $res['bg_no']==$write['bg_no']?'selected':''?>> <?php echo $res['bg_name']?>  (<?php echo number_format($res['bg_receipt'])?> 명) </option>
                <?php 
                        if ($res['bg_no']==$write['bg_no']) { $get_bg_name = $res['bg_name']; }
                } ?>
            </select>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="bk_name">이름<strong class="sound_only"> 필수</strong></label></th>
        <td><input type="text" name="bk_name" id="bk_name" maxlength="50" value="<?php echo $write['bk_name']?>" required class="frm_input required"></td>
    </tr>
    <tr>
        <th scope="row"><label for="bk_hp">휴대폰번호<strong class="sound_only"> 필수</strong></label></th>
        <td>
            <input type="text" name="bk_hp" id="bk_hp" value="<?php echo $write['bk_hp']?>" required class="frm_input required">
        </td>
    </tr>
<?php 
        $rtnStinfo = chk_edufile_sdata($get_bg_name);    
        if ($rtnStinfo){
?>        
    <tr id="tr_bk_info">
        <th scope="row"><label for="bk_stid">학년반번호</label></th>
        <td><?php echo $rtnStinfo[2].'학년 '.$rtnStinfo[3].'반 ';?><input type="text" name="bk_stid" id="bk_stid" maxlength="4" style="width:20px" value="<?php echo $write['bk_stid']?>" class="frm_input"><?php echo '번 ('.$rtnStinfo[1].')';?>&nbsp;&nbsp;&nbsp;&nbsp;*그룹변경시 학년/반/구분이 변경됩니다.(학생번호가 이미 있으면 등록 불가)</td>
    </tr>                
<?php
        }
?>    

    <?php if ($w == 'u') { ?>
    <tr>
        <th scope="row">업데이트</th>
        <td> <?php echo $write['bk_datetime']?> </td>
    </tr>
    <?php } ?>
    <tr>
        <th scope="row"><label for="bk_memo">메모</label></th>
        <td>
            <textarea name="bk_memo" id="bk_memo"><?php echo $write['bk_memo']?></textarea>
        </td>
    </tr>
    </tbody>
</table>
</div>

<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="확인" class="btn_submit" accesskey="s" onclick="return book_submit();">
    <a href="/serv.php?m1=4&m2=3&<?php echo $_SERVER['QUERY_STRING']?>">목록</a>
</div>

</form>
<script>
function book_submit(){
    var f = document.book_form;
    var regExp_hp = /^(01[016789]{1}|02|0[3-9]{1}[0-9]{1})-?[0-9]{3,4}-?[0-9]{4}$/;

    if(!f.bk_hp.value){
        f.bk_hp.focus();
        alert("휴대폰번호를 입력하세요.");
        return false;
    } else if ( !regExp_hp.test(f.bk_hp.value) )
    {
        f.bk_hp.focus();
        alert("휴대폰번호 입력이 올바르지 않습니다.");
        return false;
    }

    var w = "<?php echo $w; ?>";
    var bk_no = "<?php echo $bk_no; ?>";
    var mb_id = f.mb_id.value;
    var bk_hp = f.bk_hp.value;
    var bg_no = f.bg_no.value;
    var get_bk_stid = f.get_bk_stid.value;
    var bk_stid = '';
    if (f.bk_stid){
       bk_stid = f.bk_stid.value;
    } else {
       bk_stid = f.get_bk_stid.value;
    }    
    var get_bg_no = f.get_bg_no.value;
    f.in_bk_stid.value = bk_stid;    
    var params = { w: w, bk_no: bk_no, mb_id : mb_id, bk_hp : bk_hp, bg_no : bg_no, bk_stid : bk_stid, get_bk_stid : get_bk_stid, get_bg_no : get_bg_no};
    var is_submit;

    $.ajax({
        url: "/service/ajax.hp_chk.php",
        type: "POST",
        cache:false,
        timeout : 30000,
        dataType:"json",
        data:params,
        success: function(data) {
            if(data.error) {
                is_submit = false;
                alert( data.error );
            } else {
                is_submit = true;
            }
            if(is_submit)
                f.submit();
        }
    });

    return false;
}
</script>
</div>
</div>
</div>
</div>
<?php
include_once('../_tail.php');
?>