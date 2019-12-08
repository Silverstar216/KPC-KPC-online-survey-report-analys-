<?php
/**
 * Created by PhpStorm.
 * User: KMC
 * Date: 2/29/2016
 * Time: 9:47 PM
 */

defined('BASEPATH') OR exit('No direct script access allowed');
$site_url = site_url();
$a = sizeof($_COOKIE);
$new_flag = 0;
foreach ($_COOKIE as $s) {
   if($s==="hd_pop")
       $new_flag =1;
}

?>

<?php
        if(!empty($nw)) {


                if ($new_flag ===0) {

                    ?>
                    <!-- 팝업레이어 시작 { -->
                    <div id="hd_pop"
                         style="top:<?php echo $nw[0]['nw_top'] ?>px;left:<?php echo $nw[0]['nw_left'] ?>px;    position: absolute;">
                        <h2 style="  ">알림</h2>


                        <div class="hd_pops_con"
                             style=" width:<?php echo $nw[0]['nw_width'] ?>px;height:<?php echo $nw[0]['nw_height'] ?>px">
                            <h3 style="text-align: center;color: #da096e;"><?php echo $nw[0]['nw_subject']; ?></h3>
                            <p style="font-size: 18px;line-height: 30px;"><?php echo $nw[0]['nw_content']; ?></p>
                        </div>
                        <div class="hd_pops_footer">
                            <button
                                    class="hd_pops_reject hd_pops_<?php echo $nw[0]['nw_id']; ?> <?php echo $nw[0]['nw_disable_hours']; ?>">
                                <strong><?php echo $nw[0]['nw_disable_hours']; ?></strong>시간 동안 다시 열람하지 않습니다.
                            </button>
                            <button class="hd_pops_close hd_pops_<?php echo $nw[0]['nw_id']; ?>">닫기</button>
                        </div>

                    </div>


                    <?php
                }

    }
        ?>