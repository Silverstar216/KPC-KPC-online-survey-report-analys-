<?php
  if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가   
?>
<div class="subTopTab">
    <ul class="item">
        <li><a href="#" title="페이지 이동" class="active"><span>SMS전송</span></a></li>           
    </ul>
</div>
<div class="titleBoxsms">
    <em>SMS전송</em>
    <div class="resendsms">
       <div class="bca">
            <div class="breadCrumbs scBasic">
                  <p>Home <span class="rt">&gt;</span> 개별고지 <span class="rt">&gt;</span> SMS전송</p>
           </div>
       </div>
    </div>
</div>
<div id="sms">
    <?php include_once('/service/smsForm/pb01.php'); ?>
    <?php include_once('/service/smsForm/ph01.php'); ?>
</div>