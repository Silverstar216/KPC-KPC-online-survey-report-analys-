<?php
/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 2/29/2016
 * Time: 9:47 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
$site_url = site_url();

?>
<?php
foreach($mobiles as $mobile):
    ?>
    <tr class="contact phonenum" >
        <td class="t01" style="width: 12%;text-align: center;"><input type="checkbox" class="check_one_address"></td>
        <td class="t03 contact-name" style="width: 17%; text-align: center;"><?=$mobile['name']?></td>
        <td class="t02 contact-mobile" style="width: 30%;text-align: center;">
            <?php
            $phone = $mobile['mobile'];
            if(strlen($phone) == 11)      //모바일이면
                echo substr($phone,0,3).'-'.substr($phone,3,4).'-'.substr($phone,7,4);
            else if(strlen($phone) == 9)  //유선전화번호이면
                echo substr($phone,0,2).'-'.substr($phone,2,3).'-'.substr($phone,5,4);
            else
                echo $phone;
            ?>
        </td>
        <td class="t04 contact-group" style="width: 25%;text-align: center;"><?=$mobile['gname']?></td>
        <td style  = "width:10%">
            <button type="button" class="btn_frmline" onclick="onAddPhoneClick('<?=$mobile['mobile']?>','<?=$mobile['name']?>', '<?=$mobile['gid']?>')" style = "background: #444;color:white;">←</button>
        </td>
        <td class="t03 group-id hidden" style="width: 0%; text-align: left;"><?=$mobile['gid']?></td>
    </tr>
<?php endforeach;?>
<input type="hidden" id="totalcnt" value="<?=$phonenumberCount?>" >

