<?php
/**
 * Author: KMC
 * Date: 10/6/15
 */

$site_url = site_url();

?>
<div class="container container-bg" id = "attachedHTMLArea" style="display: none;">
        <div id="content">
        <!--  html편집기  -->
                <div style="text-align: center;width: 100%;display: block; position: relative;">
                    <div style = "display: block;position: relative;width: 100%; padding: 20px;text-align: center;">
                        <h4 style="width: 70%;padding-left: 150px; display: inline-block;">포함문서미리보기</h4>
                        <button type="button" class="btn btn-outline" onclick="onBack()" style = "margin-left: 100px;width: 100px;">돌아가기</button>
                    </div>


                </div>
            <div id="attachedHTMLDialog">
                <div id="attached_content" style="height:600px;">

                </div>
            </div>

        </div>
</div>
<!--<!--dialog방식인경우-->
<!--<div class="modal fade draggable-modal" id="attachedHTMLDialog" tabindex="-1" role="basic" aria-hidden="true">-->
<!--    <div class="modal-dialog">-->
<!--        <div class="modal-content">-->
<!--            <div class="modal-header" style="background-color: #58b5fb;text-align: center;">-->
<!--                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>-->
<!--                <h4 class="modal-title" style="color: #fff;">포함문서미리보기</h4>-->
<!--            </div>-->
<!--            <div class="modal-body" style="font-size: 14px;">-->
<!--                    <div id="attached_content" style="height:600px;overflow-y: auto;">-->
<!--                        <iframe src="http://localhost/uploads/tmp/20181107131407-test.html" style="width:100%;height:100%"></iframe>-->
<!--                    </div>-->
<!--                <!--<script>$("#attached_content").val("asdfasdf")</script>-->
<!--            </div>-->
<!--            <div class="modal-footer">-->
<!--                <button type="button" class="btn blue btn-outline" data-dismiss="modal" onclick="on_import_question()">확인</button>-->
<!--                <button type="button" class="btn blue btn-outline" onClick="toggleArea1();">편집</button>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<script src="<?=$site_url?>include/js/html_editor/nicEdit.js" type="text/javascript"></script>
<script>
    var area1;



    function toggleArea1() {
        if(!area1) {
            area1 = new nicEditor({fullPanel : true}).panelInstance('attached_content',{hasPanel : true});
        } else {
            area1.removeInstance('attached_content');
            area1 = null;
        }
    }
    // bkLib.onDomLoaded(function() { toggleArea1(); });
</script>