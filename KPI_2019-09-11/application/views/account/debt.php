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
        <h3 class="text-center">광명카드료금지불</h3>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>번호</th>
                <th>날자</th>
                <th class="text-right">금액</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $i = 0;
            foreach ($debt as $item):
                $i++;
                ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= $item['io_date'] ?></td>
                    <td class="text-right"><?= number_format($item['io_amount'], 0, '.', ' ') ?> 원</td>
                    <td><a href="javascript:pay_debt(<?=$item['io_id']?>)">지불</a></td>
                </tr>
            <?php endforeach ?>
            <tr>
                <td colspan="2">합계</td>
                <td class="text-right"><?= number_format($debt_sum, 0, '.', ' ') ?> 원</td>
            </tr>
            </tbody>
        </table>
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