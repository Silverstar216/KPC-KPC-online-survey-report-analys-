/**
 * User: KMC
 * Date: 10/08/2018
 * Time: 9:29 PM
 */
var g_uploader;
var max_file_size = 100;
var current_page_index = 0;
var question_count = 0;
var question_count_page = 0;
$(function () {

    if($('#file_url').val() !="") {
        $('.attached_area').show();
        $('#preview-content').hide();
    } else {
        $('.attached_area').hide();
        $('#preview-content').show();
    }
    question_count_page = $('#question_count_page').val();
    question_count = $('#question_count').val();


    $('.question-index').each(function (index) {
        if(question_count_page > index) {
            $(this).show();
            current_page_index++;
        }
    });

    if(current_page_index==question_count){
        $('.preview-footer').show();
        $('#button_next').hide();
    } else {
        $('.preview-footer').hide();
        $('#button_next').show();
    }

    $(window).resize(function(){
        $("iframe.myFrame").height($(window).height()-70);
        $("iframe.myFrame").width($(window).width());
    });
    $('input[type="radio"]').click(function(){
        if($(this).attr("isChecked") !=undefined){
            if($(this).attr("isChecked")=="false"){
                $('input[name="'+this.name+'"]').each(function(){
                    $(this).attr("isChecked","false");
                    $(this).prop("checked",false);
                });

                $(this).attr("isChecked","true");
                $(this).prop("checked",true);
            }
            else{
                $('input[name="'+this.name+'"]').each(function(){
                    $(this).attr("isChecked","false");
                });
                /* $(this).attr("isChecked","false");*/
                $(this).prop("checked",false);
            }
        }
    });

});
function invite() {

    $('.attached_area').hide();
    $('#preview-content').show();
}
function next() {
    var pre_index=0;
    var next_index = 0;
    var notice_id = $('#notice_id').val();
    var unselect_flag = 0;
    var flag = 0;
    var type_1 = 0;
    next_index = Number(current_page_index) + Number(question_count_page);
    pre_index = current_page_index-1;
    var n = 0;
    $('.question-index').each(function (index) {
        n= index+1;
        if (Number(current_page_index) - Number(question_count_page) <= index && Number(current_page_index)-1 >= index) {
            if ($(this).find('#allow_unselect').val() == "0") {

                if ($(this).find('#type').val() == "0") {
                    if ($(this).find('input[name="question_' + n + '"]:checked').val() === undefined || $(this).find('input[name="question_' + n + '"]:checked').val() === null || $(this).find('input[name="question_' + n + '"]:checked').val() === "") {
                        alert("본 질문에 응답하셔야 다음 질문으로 넘어갈수 있습니다.");
                        unselect_flag = 1;
                        flag = 2;
                        return false;
                    }

                } else if ($(this).find('#type').val() == "1") {
                    var question2_count = $(this).find('#example_count').val();
                    var i = 0;
                    var input_flag = 0;

                    for (i = 0; i < question2_count; i++) {
                        if ($(this).find('input[name="question2_' + i + '"]').val() !== "" && $(this).find('input[name="question2_' + i + '"]').val() !== undefined) {

                            input_flag = 1;

                        }
                    }
                    if (input_flag == 0) {
                        alert("본 질문에 응답하셔야 다음 질문으로 넘어갈수 있습니다.");
                        unselect_flag = 1;
                        flag = 2;
                        return false;
                    }

                } else {

                    if ($(this).find('#type_grade').val() == "2") {

                    } else {
                        if ($(this).find('input[name="rating"]:checked').val() === undefined) {
                            alert("본 질문에 응답하셔야 다음 질문으로 넘어갈수 있습니다.");
                            unselect_flag = 1;
                            flag = 2;
                            return false;
                        }
                    }
                }
            } else {
                flag = 1;

            }
        }
        if(unselect_flag == 0) {
            if (Number(current_page_index) - Number(question_count_page) <= index) {
                move_index = $(this).find('input[name="question_' + n + '"]:checked').attr('role');
                if (move_index == "" || move_index == undefined) {
                    type_1 = $(this).find('input[name="end_comment_index_' + n+ '"]').val();
                    if (type_1 == "" || type_1 == undefined || type_1 =="0") {
                        flag = 1;
                    } else {
                        previewClose(type_1);
                        flag = 2;
                        return false;
                    }


                } else if (Number(move_index) > Number(question_count)) {
                    previewClose(move_index);
                    flag = 2;
                    return false;
                } else if (Number(move_index) <= Number(current_page_index)) {
                    flag = 1;
                    return false;
                } else {
                    flag = 0;
                    return false;
                }

            } else {
                flag = 1;

            }
        }

    });
    if(flag == 1) {
        $('.question-index').each(function (index) {

            if (index > pre_index && index < next_index) {
                $(this).show();
                current_page_index++;
            } else {
                $(this).hide();

            }
        });
    } else if(flag ==0){
        current_page_index = Number(move_index)-1;
        next_index = Number(current_page_index) + Number(question_count_page);
        pre_index = current_page_index-1;
        /* current_page_index--;*/
        $('.question-index').each(function (index) {

            if (index > pre_index && index < next_index) {
                $(this).show();
                current_page_index++;
            } else {
                $(this).hide();

            }
        });
    }

    if(current_page_index==question_count){

        $('.preview-footer').show();
        $('#button_next').hide();

    } else {

        $('.preview-footer').hide();
        $('#button_next').show();



    }



}

function previewClose_check(){
    var unselect_flag = 0;
    var n = 0;
    var flag = 0;
    var type_1 =0;
    var move_index=0;
    $('.question-index').each(function (index) {
        n= index+1;
        if (Number(current_page_index) - Number(question_count_page) <= index && Number(current_page_index)-1 >= index) {
            if ($(this).find('#allow_unselect').val() == "0") {

                if ($(this).find('#type').val() == "0") {
                    if ($(this).find('input[name="question_' + n + '"]:checked').val() === undefined || $(this).find('input[name="question_' + n + '"]:checked').val() === null || $(this).find('input[name="question_' + n + '"]:checked').val() === "") {
                        alert("본 질문에 응답하셔야 다음 질문으로 넘어갈수 있습니다.");
                        unselect_flag = 1;
                        flag = 2;
                        return false;
                    }

                } else if ($(this).find('#type').val() == "1") {
                    var question2_count = $(this).find('#example_count').val();
                    var i = 0;
                    var input_flag = 0;

                    for (i = 0; i < question2_count; i++) {
                        if ($(this).find('input[name="question2_' + i + '"]').val() !== "" && $(this).find('input[name="question2_' + i + '"]').val() !== undefined) {

                            input_flag = 1;

                        }
                    }
                    if (input_flag == 0) {
                        alert("본 질문에 응답하셔야 다음 질문으로 넘어갈수 있습니다.");
                        unselect_flag = 1;
                        flag = 2;
                        return false;
                    }

                } else {

                    if ($(this).find('#type_grade').val() == "2") {

                    } else {
                        if ($(this).find('input[name="rating"]:checked').val() === undefined) {
                            alert("본 질문에 응답하셔야 다음 질문으로 넘어갈수 있습니다.");
                            unselect_flag = 1;
                            flag = 2;
                            return false;
                        }
                    }
                }
            } else {
                flag = 1;

            }
        }
        if(unselect_flag == 0) {
            if (Number(current_page_index) - Number(question_count_page) <= index) {
                move_index = $(this).find('input[name="question_' + n + '"]:checked').attr('role');
                if (move_index == "" || move_index == undefined) {
                    type_1 = $(this).find('input[name="end_comment_index_' + n+ '"]').val();
                    if (type_1 == "" || type_1 == undefined || type_1 =="0") {
                        flag = 1;
                    } else {

                        return false;
                    }


                } else if (Number(move_index) > Number(question_count)) {

                    flag = 3;
                    return false;
                } else if (Number(move_index) <= Number(current_page_index)) {
                    flag = 1;
                    return false;
                } else {
                    flag = 0;
                    return false;
                }

            } else {
                flag = 1;

            }
        }

    });

    if(flag ==2){
        previewClose(type_1);
    } else if(flag ==3){
        previewClose(move_index);
    } else {
        previewClose(21);
    }
}
function previewClose(index){


    var real_index = Number(index)-20;
    if( $('#comment_'+real_index).val() !="" && $('#comment_'+real_index).val()!=undefined) {
        alert( $('#comment_'+real_index).val());
    } else {
        alert('설문에 참가하여주셔서 감사합니다.');
    }

    // $('.header').show();
}

/*function before() {
    var pre_index=0;
    var before_index = 0;

    before_index = Number(current_page_index) - Number(question_count_page);
    if(before_index <question_count_page) {
        before_index = Number(question_count_page);
    }
    pre_index = current_page_index- Number(question_count_page)-Number(question_count_page)-1;
    $('.question-index').each(function (index) {
        if(index > pre_index && index < before_index) {
            $(this).show();
            current_page_index--;
        } else {
            $(this).hide();

        }
    });
    if(current_page_index <question_count_page) {
        current_page_index =question_count_page;
    }

    if(current_page_index==question_count){
        $('.preview-footer-before').show();
        $('.preview-footer').hide();
        $('#button_next').hide();
        $('#button_before').hide();
        $('#button_both').hide();
    } else {
        $('.preview-footer-before').hide();
        $('.preview-footer').hide();
        $('#button_next').hide();
        $('#button_before').hide();
        $('#button_both').show();


    }
    if(current_page_index == question_count_page) {
        $('.preview-footer-before').hide();
        $('.preview-footer').hide();
        $('#button_next').show();
        $('#button_before').hide();
        $('#button_both').hide();


    }
    if(question_count_page==question_count){
        $('.preview-footer-before').hide();
        $('.preview-footer').show();
        $('#button_next').hide();
        $('#button_before').hide();
        $('#button_both').hide();
    }

}*/

//본인인증확인
function auth_confirm() {

    location.href = site_url + 'preview/survey_view';

}
function other_input_check(val) {
    $("#other_input_"+val).prop('checked',true);
}
function set_radio_value(index){
    $('#other_input_'+index).val($('input[name="question_other_'+index+'"').val());
}