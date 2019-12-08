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
        <div class="account-form">
            <div class="text-center"><h3>광명카드료금이송</h3></div>
            <div class="row">
                <label class="account-sm-0 control-label">이송받을 사용자</label>

                <div class="account-sm-1">
                    <input id="account_receiver" class="form-control form-control-inline account-amount">
                </div>
            </div>
             <div class="row">
                <label class="account-sm-0 control-label">금액</label>

                <div class="account-sm-1">
                    <input id="account_amount" class="form-control form-control-inline account-amount text-right" value="0">
                    <span>원</span>
                </div>
            </div>
            <div class="row">
                <label class="account-sm-0 control-label">광명홈페지암호</label>

                <div class="account-sm-1">
                    <input id="account_password" type="password" class="form-control account-password">
                </div>
            </div>
            <div class="text-center">
                <input id="btn_pay" class="btn btn-primary account-btn-ok" type="button" value="료금이송" onclick="account_send()">
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