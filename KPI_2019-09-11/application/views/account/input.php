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


<div class="container">
    <div id="content">
        <?php
        $this->load->view('account/nav', $this->data);
        ?>
        <div class="account-form account-input-form">
            <div class="text-center form-title"><h3>광명카드료금충진</h3></div>
            <div class="row">
                <label class="col-sm-5 control-label text-right">금액</label>

                <div class="col-sm-6">
                    <input id="pay_amount" class="form-control form-control-inline account-amount text-right" value="0">
                    <span>원</span>
                </div>
            </div>
            <div class="row">
                <label class="col-sm-5 control-label text-right">광명홈페지암호</label>

                <div class="col-sm-6">
                    <input id="password" type="password" class="form-control account-password">
                </div>
            </div>
            <div class="text-center">
                <button id="btn_pay" class="btn btn-primary account-btn-ok" role="button" >결제</button>
            </div>
        </div>
    </div>
</div>

<div class="hidden">
    <form action="<?= $pay_url ?>" method="post" id="payform" name="payform">
        <input id="merchant_id" name="merchant_id" value="<?= $merchant_id; ?>">
        <input id="return_url" name="return_url" value="<?= $return_url; ?>">
        <input id="amount" name="amount" value="0">
        <input id="currency_type" name="currency_type" value="<?= $currency_type; ?>">
        <input id="other" name="other" value="<?= $other; ?>">
        <input id="sign" name="sign" value="<?= $sign; ?>">
    </form>
</div>