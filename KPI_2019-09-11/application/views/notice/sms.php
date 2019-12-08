<?php
/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 2/29/2016
 * Time: 9:47 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
$site_url = site_url();

$hidden = '';
$readonly = '';
$sms_length = 89;
$cur_length = 0;
if($survey == 1 || $attached == 1 || $goji == 1){
    $sms_length = 69;
}

$sms_text = '';
if ($education_id > 0) {
    $sms_text = '한국생산성본부 <' . $education_title . '>에 대한 <' . $survey_title . '>입니다. 설문에 응해주십시오. 감사합니다.';  
    $cur_length = $sms_length;
}

$base_text = '/' . $sms_length . ' byte';

?>

<?php if($goji != 1) { ?>
    <input id="message_type" type="hidden" value="<?=$survey?>">
<?php }else { ?>   <!-- 개별고지전송인경우-->
    <input id="message_type" type="hidden" value="4">
<?php } ?>

<input id="attached" type="hidden" value="<?=$attached?>">
<input id="object_id" type="hidden" value="<?=$object_id?>">
<input id="sms_length" type="hidden" value="<?=$sms_length?>">
<input id="phonenumber_mobile" type="hidden" value="<?php echo $m?>" >
<input id="phonenumber_name" type="hidden" value="<?php echo $n?>" >

<div class="sms01">
    <div class="sec01">
        <textarea class='content_editor' oninput="bytesLength()" ><?=$sms_text?></textarea>
        <div class="sec_btn <?=$hidden?>">
            <img class="linkImg" src="<?=$site_url?>images/btn/btn_del.png" onclick="onEmptyEditorClick()">
            <span class="base_length" style="margin-right: 25px;"><?=$base_text?></span>
            <span class="content_length" style="color: #ff0000"><?=$cur_length?></span>
        </div>
    </div>
    <div class="sec02" <?= $IsShowRegisterArea != true?'':'style="display:none;"'?>>
        <div class="sec02_c">
            <ul>
                <li class="text">발신번호</li>
                <li class="text1"><input type = "text" class="input calling_number" id = "senderPhoneInput" style = "font-size: 14px;" value="027241114">
                    <table class = "availableSenderList" style="display: none;">
                        <tbody class = "availableSenderList-tbody">
                            <?php
                            foreach($senderPhoneList as $senderPhone):
                                ?>
                                    <tr class = "availableSenderList-tbody-tr"><td class = "availableSenderList-tbody-td"><?=$senderPhone['phone']?></td></tr>
                            <?php  endforeach;?>
                        </tbody>
                    </table>
                </li>
                <!-- <li class="icon"><img width="30" src="<?=$site_url?>images/btn/btn_phone.png" onclick="onShowSenderPhoneArea()"></li> -->
            </ul>
            <?php if($goji == 1) { ?>
                <ul>
                    <li class="text">받는이름</li>
                    <li class="text1"><input type="text" class="input calling_name" disabled> </li>
                </ul>
                <ul>
                    <li class="text">번호</li>
                    <li class="text1"><input type = "text" class="input one_mobile_number" disabled> </li>
                    <li class="icon"><img width="30" src="<?=$site_url?>images/btn/btn_add.png" style = "opacity:0.7"></li>
                </ul>
                <ul>
                    <li class="text2">목록 총 <span class="mobile_count"><?=count($gojiMobiles)?></span>명</li>
                </ul>
            <?php } else { ?>
                <ul>
                    <li class="text">받는이름</li>
                    <li class="text1"><input type="text" class="input calling_name"> </li>
                </ul>
                <ul>
                    <li class="text">번호</li>
                    <li class="text1"><input type = "text" class="input one_mobile_number"> </li>
                    <li class="icon"><img width="30" src="<?=$site_url?>images/btn/btn_add.png" onclick="onAddOneClick()"></li>
                </ul>
                <ul>
                    <li class="text2">목록 총 <span class="mobile_count">0</span>명</li>
                </ul>
            <?php } ?>
            <ul>
                <li>
                    <div class="list">
                        <table class="mobile_list_table" summary="휴대폰번호, 이름, 기타에 대한 선택 가능한 테이블">
                            <colgroup>
                                <col width="30px">
                                <col width="120px">
                                <col width="120px">
                            </colgroup>
                            <thead>
                            <tr>
                                <?php if($goji == 1) { ?>
                                    <th scope="col" class="t01"><input type="checkbox" title="전체선택" class="check_all_mobile" disabled></th>                                    
                                <?php }else { ?>
                                    <th scope="col" class="t01"><input type="checkbox" title="전체선택" class="check_all_mobile"></th>
                                <?php } ?>
                                <th scope="col">그룹명(휴대폰번호)</th>
                                <th scope="col">갯수(이름)</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php 
                                if($goji == 1) {
                                    for($nRow = 0; $nRow < count($gojiMobiles); $nRow ++) {
                                        ?>
                                        <tr>
                                            <td class="t01"><input type="checkbox" title="선택" class="check_one_mobile" disabled></td>
                                            <td class="mobile_number_item"><?= $gojiMobiles[$nRow] ?></td>
                                            <td></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                else if ($education_id > 0) {
                                    for($nRow = 0; $nRow < count($students); $nRow ++) {
                                        ?>
                                        <tr>
                                            <td class="t01"><input type="checkbox" title="선택" class="check_one_mobile"></td>
                                            <td class="mobile_number_item"><?= $students[$nRow]['mobile'] ?></td>
                                            <td class="mobile_name_item"><?= $students[$nRow]['name'] ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </li>
            </ul>
            <ul>
                <li>
                    <?php if($goji != 1) { ?>
                        <img src="<?=$site_url?>images/btn/btn_del01.png" onclick="onDeleteSelectedClick()">
                        <img src="<?=$site_url?>images/btn/btn_del02.png" onclick="onDeleteAllClick()">
                    <?php }else { ?>
                        <img src="<?=$site_url?>images/btn/btn_del01.png" style="opacity: 0.7">
                        <img src="<?=$site_url?>images/btn/btn_del02.png" style="opacity: 0.7">
                    <?php } ?>
                </li>
            </ul>
            <ul class="<?=$hidden?>">
                <li class="text2" style="text-align: center; ">
                    <input type="checkbox" id="notice_reserve" class="text_ck">
                    예약전송
                </li>
            </ul>
            <ul>
                <li id="notice_reserve_date_container" class="hidden">
                    <input style="height: 25px;" type="text" id="notice_reserve_date" class="form-control input-inline notice_datepicker" value="<?=$start_date?>">
                    <img src="<?=$site_url?>images/icon_cal.png" onclick="onClickedCal()">
                </li>
            </ul>
        </div>
    </div>
<!--    등록번호관리-->
    <div class="sec03" <?= $IsShowRegisterArea != true?'':'style="display:none;"'?>>
        <?php if($goji == 1) { ?> <!--    개별고지전송인경우 현시하지 않는다.-->
            <div style="opacity: 0.05;width: 308px;height: 530px;background: black;position:absolute;"></div>
        <?php } ?>
        <div class="row div-phone">
            <button class="col-md-6 btn btn-active btn-phonenum-type" phonenum-type="0">
                번호그룹</button>
            <button class="col-md-6 btn btn-default btn-phonenum-type btn-phone" phonenum-type="1">
                등록번호</button>
        </div>
        <div class="t_text">상단체크(전체), 체크박스 선택 후 상단 [선택추가]로 추가</div>
        <div id = "tbl_group">
            <table class="address_select_table" style="margin-left: 0px;width:100%;" >
                <thead>
                    <tr class="phone-tr group-tr">
                        <th class="t01" ><input type="checkbox" class="check_all_address" id = "check_all_group"></th>
                        <th class="t02" colspan="2">
                            <ul>
                                <li><img class="linkImg" src="<?=$site_url?>images/btn/btn_add_s.png" onclick="onAddSelectedAddressClick()"></li>
                                <li><input type="text" placeholder="그룹명" style="line-height: 100%; box-sizing:content-box !important" id="search_group"></li>
                                <li><img class="linkImg" src="<?=$site_url?>images/btn/btn_find_s.png" onclick="onSearchAddSelectedClick()"></li>
                            </ul>
                        </th>
                    </tr>
                </thead>
                <tbody style = "display:block;overflow-y:scroll; height: 250px;">
                <?php
                foreach($group_data as $group):
                    ?>
                    <tr class="contact phonegroup">
                        <td class="t01" style="width: 19%"><input type="checkbox" class="check_one_address"></td>
                        <td class="t02 group-name" style="width: 71%"><a href="javascript:show_phoneByGroup(<?=$group['id']?>)"><?=$group['name']?></a></td>
                        <td class="t03 group-count" style="width: 20%; text-align: left;"><?=$group['cnt']?></td>
                        <td class="t03 group-id hidden" style="width: 0%; text-align: left;"><?=$group['id']?></td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
        <div id = "tbl_phone" style = "display: none;">
            <table class="address_select_table" style="margin-left: 0px;width:100%;">
                <thead>
                <tr class="phone-tr number-tr">
                    <th class="t01"><input type="checkbox" class="check_all_address" id = "check_all_phone"></th>
                    <th class="t02" colspan="2">
                        <ul>
                            <li><img class="linkImg" src="<?=$site_url?>images/btn/btn_add_s.png" onclick="onAddSelectedAddressClick()"></li>

                        </ul>
                    </th>
                </tr>
                </thead>
                <tbody style = "display:block;overflow-y:scroll;height:250px;" id = "phone_tbody">

                </tbody>
            </table>
            <td>
                <div class="blog-pagination-small"></div>
            </td>
            <div class = "search-condition-area" style = "text-align: center;">
                <div style = "margin-bottom: 5px;">
                    <div>그룹 :
                        <select id="groups" name="groups">
                            <option value="all" selected>전체</option>
                            <?php foreach ($group_data as $item):?>
                                <option value="<?=$item['id'];?>"><?=$item['name']?> (<?=$item['cnt']?>명)</option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div style = "margin-left: 10px;">
                        <select name="st" id="st">
                            <option value="all" selected>이름 + 휴대폰번호</option>
                            <option value="name">이름</option>
                            <option value="hp">휴대폰번호</option>
                        </select>
                    </div>
                </div>
                <div>
                    <div style="padding-left:3px;"><input type="text" id="st_val" name="st_val" value=""></div>
                    <div style="cursor:pointer" onclick="searchBtnClick();"><img src="<?=$site_url;?>images/btn/btn_search.png"></div>
                </div>
            </div>
        </div>
     </div>

<!--    발신전화번호관리-->
    <div class="sec04" <?= $IsShowRegisterArea == true?'':'style="display:none;"'?>>
        <div class="div-phone-header">
            <div class = "item1">
                <h4>발신번호등록(인증)/삭제</h4>
            </div>
            <div class = "item3" style = "float:right">
                <button type="button" onclick="onCloseSenderPhoneArea()">닫 기</button>
            </div>
        </div>
<!--        설명문부분-->
        <div class = "comment_area">
            <a>
                발신번호 등록후 번호이동 등으로 등록된 번호를 사용하지 않는 경우에는 반드시 삭제해주시기 바랍니다.<br>
                "번호도용문자차단서비스"에 가입된 번호는 발신번호를 등록했더라도 발송이 되지 않습니다.<br>
            </a>
        </div>
        <div class = "phone_register_title">발신번호(보내는번호)등록
        </div>
        <div class="div-phone-verify" style="display: block">
            <div class = "phone_item1" style = "margin-top:5px;">
                <a >발신번호(보내는 번호): </a>
                <input type = "text" style = "width:20%" class="phoneNumberPart1">
                <lable style="    margin-left: 20px;">설명: </lable>
                <input type = "text" style = "width:20%" class="phonetextComment">
               <!-- <input type = "text" style = "width:20%" id="txtComment">-->
                <button class="btn-example-remove btn btn-danger btn-sm"><i class="fas fa-times"></i></button>
                <button class="btn-example-plus btn btn-success btn-sm"><i class="fas fa-plus"></i></button>
            </div>

        </div>
        <div class="div-phone-verify_1" style="display: block">
            <div class = "item2">
                <a style="color:#FF0000">인증받을 이동전화: </a>
                <input type = "text" style = "width:20%" id="txtSendingMobile">
                <button type="button" onclick="onRegisterSenderPhone()" id = "btnSendingVerifyCode" style="margin-left: 20px;">코드받기</button>
                <input type = "text" style = "width:18%" id="txtVerifyCode">
                <button type="button" onclick="onSaveSenderPhone()" id = "btnAddSenderPhone" style="margin-left: 20px;">등록</button>
            </div>
        </div>
        <table class="sendphone-header-table">
            <thead>
            <th style="width: 8%">대 표</th>
            <th colspan = "2" style="width: 25%">설 명</th>
            <th colspan = "2" style="width: 27%">발신번호(보내는 번호)</th>
            <th style="width: 20%">인증일시</th>
            <th style="width: 20%">인증번호</th>
            </thead>
        </table>
        <div class="div-phone-body">
            <table class="sendphone-body-table">
                <tbody>
                    <?php
                    foreach($senderPhoneList as $senderPhone):
                        ?>
                        <tr>
                            <td style="width: 8%"><img src="<?=$site_url?>include/img/star-off-big.png" alt=" " class="img-responsive zoom-img" style = "margin-left:12px;"></td>
                            <td style="width: 15%"><input type = "text" value = "<?=$senderPhone['memo'] == null ?'(이름없음)':$senderPhone['memo'] ?>" style = "width:100%;border:none;text-align: center" id="senderPhoneMemo<?=$senderPhone['id']?>"></td>
                            <td style="width: 10%"><button onclick="onSaveSenderPhoneMemo(<?=$senderPhone['id']?>)">저장</button></td>
                            <td class="phoneNumber" style="width: 17%"><?=$senderPhone['phone']?></td>
                            <td style="width: 10%"><button onclick="onRemoveSenderPhone(<?=$senderPhone['id']?>)">삭제</button></td>

                            <td style="width: 20%"><?=$senderPhone['request_date']?></td>
                            <td style="width: 20%"><?=$senderPhone['request_phone']?></td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
        <input type = "hidden" id="auKind" value="0">
    </div>
</div>



