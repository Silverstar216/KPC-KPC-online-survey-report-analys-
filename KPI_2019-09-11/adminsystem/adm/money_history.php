<?php
/**
 * Author: KMC
 * Date: 10/6/15
 */
$money_historylst = get_member_money_history($mb_id);
?>
<div class="container container-bg" id = "attachedHTMLArea" style="display: none;">
    <div id="content">
        <!--  html편집기  -->
        <div style="text-align: center;display:inline-block;width:100%;margin-top:15px;margin-bottom: 10px;">
            <span style = "margin-left:320px;font-size:18px;font-weight:bold;"><?=$mb['mb_id']?> 입금내역보기</span>
            <button type="button" class="btn btn-outline" onclick="onShowMainArea()" style = "display: inline-block;margin-left:300px;height:35px">돌아가기</button>
        </div>
        <table class = "money_history_table">
            <thead>
                <tr style = "height:30px">
                    <th style = "width:180px;">날 자</th>
                    <th>입 금</th>
                    <th>사 용</th>
                    <th>잔 액</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $eletoday = date("Y-m-d");
                for ($i=0; $row=sql_fetch_array($money_historylst); $i++) {
            ?>
                <tr>
                    <td><?php echo $row['date'] ?></td>
                    <td><?php echo $row['deposit'] ?></td>
                    <td><?php echo $row['used_amount'] ?></td>
                    <td><?php echo $row['current_amount'] ?></td>
                </tr>
            <?php
                }
                if ($i == 0)
                    echo '<tr><td colspan="4" class="empty_table">자료가 없습니다.</td></tr>';
            ?>
            </tbody>
        </table>
        <?php

        ?>
    </div>
</div>
