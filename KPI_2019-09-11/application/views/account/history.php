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
        <div class="row" style="text-align: center;">
            <div class="text-center"><h3>광명카드리력</h3></div>
        </div>
        <div class="row" style="text-align: center;padding-top:15px;">
            현시개수
            <select id="disp_count" onchange="javascript:refresh_data()">
                <?php foreach ($arr_disp_count as $item):
                    $selected = '';
                    if ($disp_count == $item) {
                        $selected = 'selected';
                    }
                    ?>
                    <option value="<?= $item ?>" <?= $selected ?>><?= $item ?></option>
                <?php endforeach; ?>
            </select>

            , 결과건수: <?php echo($history_data_count) ?>건
            <input id="cur_page" type="hidden" value="<?= $cur_page ?>">
        </div>
        <div style="text-align: center;padding-top:10px;">
            <form id="pay_history_form" class="form-inline pay-history-form" role="form">
                <div class="form-group">
                    날자
                    <input id="pay_date1" name="pay_date1" size="15" class="date" value="<?= $pay_date1 ?>"> ~
                    <input id="pay_date2" name="pay_date2" size="15" class="date" value="<?= $pay_date2 ?>">
                    ,방식
                    <select id="account_method_combo" onchange="reload_method();">
                        <?php
                        $method_val = 0;
                        foreach ($arr_account_method as $item):
                            $selected = '';
                            if ($account_method == $method_val) {
                                $selected = 'selected';
                            }
                            ?>
                            <option value="<?= $method_val ?>" <?= $selected ?>><?= $item ?></option>
                            <?php $method_val++;
                        endforeach; ?>
                    </select>

                    ,보낸사람<input id="user_id" size="15" type="text" value="<?= $user_id ?>" <?= $disable_user ?>>
                    ,받은사람<input id="receiver_id" size="15" type="text"
                                value="<?= $receiver_id ?>" <?= $disable_receiver ?>>
                    <input id="view_history" type="button" value="리력보기" onclick="javascript: reload_data()">
                    <input id="clear_history" type="button" value="리력초기화" onclick="javascript: clear_data()">
                </div>
            </form>
        </div>
        <div class="row">
            <div style="text-align: center;">
                <?php $this->load->view('templates/pagination', array('total_page' => $total_page, 'cur_page' => $cur_page));
                ?>
            </div>
        </div>
        <div id="list" style="text-align: center;">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>번호</th>
                    <th>거래번호</th>
                    <th>내용</th>
                    <th>방식</th>
                    <th style="text-align:right">료금</th>
                    <th>날자</th>
                    <th style="text-align:right">잔고</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (sizeof($history_data) > 0) {
                    for ($i = 0; $i < sizeof($history_data); $i++) { ?>

                        <tr height="23">
                            <td><?= $i + 1 + $start ?></td>
                            <td><?php if ($history_data[$i]['io_type'] == 2) {
                                    if ($history_data[$i]['io_userid_'] == $myid)
                                        echo($history_data[$i]['io_receiver_']);
                                    else
                                        echo($history_data[$i]['io_userid_']);
                                } else
                                    echo($history_data[$i]['io_pay_id']); ?></td>
                            <td><?=$GLOBALS['account_io']['io_sake'][$history_data[$i]['io_sake']]?></td>
                            <td><?=$GLOBALS['account_io']['io_type'][$history_data[$i]['io_type']]?></td>
                            <td style="text-align:right">
                                <?php
                                $color = 'text-green';
                                $sign = '';
                                if ($history_data[$i]['io_type'] == 0 || $history_data[$i]['io_type'] == 4 || ($history_data[$i]['io_type'] == 2 && $history_data[$i]['io_userid_'] == $myid)) {
                                    $sign = '-';
                                    $color = 'text-red';
                                }
                                $amount = number_format($history_data[$i]['io_amount'], 0, '.', ' ');
                                echo sprintf('<span class="%s">%s%s 원</span>', $color, $sign, $amount);
                                ?>
                            </td>
                            <td><?= convert_date_dash_2_dot($history_data[$i]['io_date']); ?></td>
                            <td style="text-align:right"><?= number_format($history_data[$i]['io_balance'], 0, '.', ' ') ?>
                                원
                            </td>
                        </tr>
                    <?php }
                } else {
                    ?>
                    <tr>
                        <td colspan="9">현시할 자료가 없습니다.</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="row">
            <div style="text-align: center;">
                <?php $this->load->view('templates/pagination', array('total_page' => $total_page, 'cur_page' => $cur_page));
                ?>
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