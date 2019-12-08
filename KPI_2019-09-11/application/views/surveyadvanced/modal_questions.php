<?php
/**
 * Author: KMC
 * Date: 10/6/15
 */

$site_url = site_url();

?>

<div class="modal fade draggable-modal" id="questions_modal" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #58b5fb;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title" style="color: #fff;">공개설문 불러오기</h4>
            </div>
            <div class="modal-body" style="font-size: 14px;">
                <div class="row">

                    <div >
                        <div class="serv_t" style="     display: inline-block; float: right;     margin-right: 30px;  margin-left: 0;">
                            총 갯수 0 개
                        </div>
                    </div>
                </div>                        
                <div class="row" id="survey_list">

                </div>


                        <div class="blog-pagination my-item-pagination">

                        </div>



            </div>
            <div class="modal-footer">

                <button type="button" class="btn blue btn-outline" data-dismiss="modal" onclick="close()" >취소</button>
                <button type="button" class="btn blue btn-outline" data-dismiss="modal" onclick="on_import_question()">불러오기</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
