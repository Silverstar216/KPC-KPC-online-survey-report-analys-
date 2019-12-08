<?php
/**
 * Created by PhpStorm.
 * User: CHKD
 * Date: 10/9/2018
 */
defined('BASEPATH') OR exit('No direct script access allowed');
$site_url = site_url();

?>

<div class="container container-bg">
 <div id="content">

<!--   -->
<div class="titlegroup">
        <em>휴대폰 번호</em>      
         <div class="navgroup">     
                 <p>Home <span class="rt">&gt;</span> 전화번호관리 <span class="rt">&gt;</span> 휴대폰 번호</p>
        </div>     
</div>
<div class="phonegroup">
<div class="phonegroupin">
<div class="phonegroupwrap">
<div id="sub_content">

<form name="book_form" id="book_form" onSubmit=" return false;">
<input type="hidden" id="what" value="<?php echo $what?>" >
<input type="hidden" id="mobileid" value="<?php echo $mobileid?>" >
<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption>휴대폰번호 추가</caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row"><label for="bg_no">그룹 <strong class="sound_only"> 필수</strong></label></th>
        <td>
		 <select data-placeholder="선택하세요!..." class="chosen-select" multiple style="width:100%;">
		 <option value=""></option>
		 <?php foreach ($groups as $item):?>
            <option value="<?=$item['id']?>" <?php echo get_selected($item['id'], $item['id']); ?> ><?=$item['name']?></option>
			<?php endforeach;?>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="bk_name">이름<strong class="sound_only"> 필수</strong></label></th>
        <td><input type="text" name="bk_name" id="bk_name" maxlength="50" value="<?=isset($userMobiledata['name']) ? $userMobiledata['name']:'' ?>" required="" class="frm_input required"></td>
    </tr>
    <tr>
        <th scope="row"><label for="bk_hp">휴대폰번호<strong class="sound_only"> 필수</strong></label></th>
        <td>
            <input type="text" name="bk_hp" id="bk_hp" value="<?=isset($userMobiledata['mobile']) ? $userMobiledata['mobile']:'' ?>" required="" class="frm_input required">
        </td>
    </tr>
    

        <tr>
        <th scope="row"><label for="bk_memo1">주민등록번호</label></th>
        <td>
            <textarea name="address_num" id="address_num"><?=isset($userMobiledata['address_num']) ? $userMobiledata['address_num']:'' ?></textarea>
        </td>
    </tr>
	 <tr>
        <th scope="row"><label for="bk_memo1">메모</label></th>
        <td>
            <textarea name="bk_memo" id="bk_memo"><?=isset($userMobiledata['memo']) ? $userMobiledata['memo']:''?></textarea>
        </td>
    </tr>

    </tbody>
</table>
</div>

<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="확인" class="btn_submit" accesskey="s" onclick="return book_submit();">
    <a href="index">목록</a>
</div>

</form>

</div>
</div>
</div>
</div>


<!--   -->
 </div>
</div>
