<?php
/**
 * Author: KMC
 * Date: 2017-06-28
 */

?>


<div id="modalPayScitech" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalPayScitechLabel"
     aria-hidden="true" data-id="0">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title text-lang-1" id="modalPayScitechLabel">광명카드결제확인</h4>
            </div>
            <div class="modal-body">
                <p class="text-lang-1">
                    자료가격은 <span id="data_price" class="text-red">100</span>원입니다.
                </p>

                <p class="text-lang-1">
                    광명카드료금잔고 <span id="card_balance" class="text-blue">200</span>원
                </p>

                <p id="msg_low_balance">료금잔고가 부족합니다. 충진하시겠습니까?</p>

                <p id="msg_pay_confirm">결제하시겠습니까?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default text-lang-1" data-dismiss="modal">취소</button>
                <button type="button" class="btn btn-primary text-lang-1">확인</button>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->