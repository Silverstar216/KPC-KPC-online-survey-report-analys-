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

<div class="text-right account-nav">
    <sapn>료금잔고 <?=number_format($balance, 0, '.', ' ')?>원</sapn>
    <a href="<?=$site_url?>account/input">료금충진</a>
    <a href="<?=$site_url?>account/send">료금이송</a>
    <a href="<?=$site_url?>account/history">리력</a>
    <a href="<?=$site_url?>account/debt" class="<?=$hidden_debt?>">지불</a>
</div>