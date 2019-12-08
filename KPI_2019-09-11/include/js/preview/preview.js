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
var link_count = 0;0

var interval_index = 2;
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
    if (question_count > 1 && question_count > question_count_page)
        $('.button_save').hide();
        
    /*$('.question-index').each(function(question_index) {

        var count = $(this).find('.slider_count').val();


        if (count == 5) {
            noUiSlider.create($(this).find('.example-fav-slider_'+question_index).first()[0], {
                start: [2],
                step: 1,
                connect: [true, false],
                range: {
                    'min': [0],
                    'max': [4]
                }
            });
            $(this).find('.example-fav-slider_'+question_index).first()[0].noUiSlider.on('change', function(values, handle) {
                // on update set first input value
                $('#slider_'+(Number(question_index))).val(values[handle]);
                // also set #slider-value-after input value by minus'ing the max value by current slider value

            });

        } else if (count == 3) {
            noUiSlider.create($(this).find('.example-fav-slider_'+question_index).first()[0], {
                start: [2],
                step: 1,
                connect: [true, false],
                range: {
                    'min': [1],
                    'max': [3]
                }
            });
            $(this).find('.example-fav-slider_'+question_index).first()[0].noUiSlider.on('change', function(values, handle) {
                // on update set first input value
                $('#slider_'+(Number(question_index))).val(values[handle]);
                // also set #slider-value-after input value by minus'ing the max value by current slider value

            });
        }
    });*/

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
       /* if(this.checked) {
            $(this).prop("checked",false);
        } else {
            $(this).prop("checked",true);
        }*/
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

    link_count = $('#link_count').val();

    $('#advert_link_1').show();
    startAlert();
});

startAlert = function() {
    playAlert = setInterval(function() {
        if(interval_index > link_count) {
            interval_index = 1;
        }

        if(interval_index ==1) {
            $('#advert_link_'+interval_index).show();
            $('#advert_link_'+link_count).hide();
        } else {
            $('#advert_link_'+interval_index).show();
            $('#advert_link_'+(Number(interval_index)-1)).hide();
        }
        interval_index = Number(interval_index)+1;


    }, 3000);
};

function advert_link(link_url,id) {
    window.open('about:blank').location.href=link_url;
    $.ajax({
        url: site_url + 'advert/connect_save',
        cache: false,
        timeout: 10000,
        dataType: 'text',
        data: {
            id:id
        },
        type: 'POST',

        success: function (data) {

        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert('실패');
        }
    });
}
function invite() {

     $('.attached_area').hide();
     $('#preview-content').show();
}

function next() {
    var pre_index=0;
    var next_index = 0;
    var notice_id = $('#notice_id').val();
    var unselect_flag = 0;
    var type_1 = 0;
    var flag = 0;
    next_index = Number(current_page_index) + Number(question_count_page);
    pre_index = current_page_index-1;
    var n = 0;
    $('.question-index').each(function (index) {
        n = index + 1;
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
                if((move_index == "" || move_index == undefined) && index == (current_page_index - 1)){
                    move_index = $(this).find('input[name="rating"]:checked').attr('role');
                }
                
                if (move_index == "" || move_index == undefined) {
                    type_1 = $(this).find('input[name="end_comment_index_' + n+ '"]').val();
                    if (type_1 == "" || type_1 == undefined || type_1 =="0") {
                        flag = 1;
                    } else {
                        save(type_1);
                        flag = 2;
                        return false;
                    }
                    flag = 1;

                } else if (Number(move_index) > Number(question_count)) {
                    save(move_index);
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
        $('.button_save').show();
        $('#button_next').hide();
    } else {
        $('.preview-footer').hide();
        $('#button_next').show();
    }

}

function before() {
    var pre_index=0;
    var before_index = 0;

    before_index = Number(current_page_index) - Number(question_count_page);
    if(before_index < question_count_page) {
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
        current_page_index = question_count_page;
    }

    if(current_page_index == question_count){
        $('.preview-footer-before').show();
        $('.preview-footer').hide();
        $('#button_next').hide();
        $('#button_before').hide();
    } else {
        $('.preview-footer-before').hide();
        $('.preview-footer').hide();
        $('#button_next').show();
        $('#button_before').show();
    }

    if(current_page_index == question_count_page) {
        $('.preview-footer-before').hide();
        $('.preview-footer').hide();
        $('#button_next').show();
        $('#button_before').show();
    }
    if(question_count_page==question_count){
        $('.preview-footer-before').hide();
        $('.preview-footer').show();
        $('#button_next').hide();
        $('#button_before').hide();
    }
}

function previewClose_check(){
    var unselect_flag = 0;
    var n = 0;
    var flag = 0;
    var type_1 =0;
    var move_index=0;
    $('.question-index').each(function (index) {
        n = index + 1;
        if (Number(current_page_index) - Number(question_count_page) <= index && Number(current_page_index)-1 >= index) {
            if ($(this).find('#allow_unselect').val() == "0") {
                if ($(this).find('#type').val() == "0") {
                    // 주관식 설문
                    if ($(this).find('input[name="question_' + n + '"]:checked').val() === undefined || $(this).find('input[name="question_' + n + '"]:checked').val() === null || $(this).find('input[name="question_' + n + '"]:checked').val() === "") {
                        alert("본 질문에 응답하셔야 다음 질문으로 넘어갈수 있습니다.");
                        unselect_flag = 1;
                        return false;
                    }
                } else if ($(this).find('#type').val() == "1") {
                    // 객관식 설문
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

                        return false;
                    }
                } else {
                    // 강사 만족도 및 일반 만족도 설문
                    if ($(this).find('#type_grade').val() == "2") {

                    } else {
                        if ($(this).find('input[name="rating"]:checked').val() === undefined) {
                            alert("본 질문에 응답하셔야 다음 질문으로 넘어갈수 있습니다.");
                            unselect_flag = 1;

                            return false;
                        }
                    }
                }
            } else {
                unselect_flag = 0;
            }
        }

        if(unselect_flag == 0) {
            if (Number(current_page_index) - Number(question_count_page) <= index) {
                move_index = $(this).find('input[name="question_' + n + '"]:checked').attr('role');
                if (move_index == "" || move_index == undefined) {
                    type_1 = $(this).find('input[name="end_comment_index_' + n + '"]').val();
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


    if (flag == 2) {
        save(type_1);
    } else if (flag ==3) {
        save(move_index);
    } else {
        save(21);
    }

    $('.survey_desc').text('설문응답이 제출되었습니다');
    // $('.btn_auth_ok').css('display', 'none');
    $('.button_save').hide();
    $('#button_next').hide();
    $('.question-content').css('display', 'none');
    $('.preview-footer').show();
    $('#btn_exitBrower').show();
}

function save(index) {
    var n = 0;
    var unselect_flag = 0;
    var response_man = "";

    if (unselect_flag == 0) {
        var error = $('#error').val();
        if (error == "1") {
            alert("귀하는 이미 설문에 참여 하셨습니다.")
        } else if (error == "2") {
            alert(error + "설문조사기간이 끝났습니다.")
        } else {
            var notice_id = $('#notice_id').val();

            var mobile = $('#mobile').val();
            var answer = {};
            answer.notice_id = notice_id;
            answer.mobile = mobile;

            var n = 0;
            var fav_grades = ['매우 만족', '만족', '보통', '불만족', '매우 불만족'];
            var value = '{';
            $('.question-index').each(function (index) {
                n = index + 1;
                var type = $(this).find('#type').val();
                var type_grade = $(this).find('#type_grade').val();
                var reply_response = $(this).find('#reply_response').val();
                if (type == 0) {
                    // 주관식
                    if (reply_response == 1) {  // 중복응답허용일때
                        var flag = 0;
                        value += '"' + n + '":[';

                        $('input:checkbox[name="question_' + n + '"]').each(function () {

                            if (this.checked) {
                                flag = 1;
                                value += '"' + n + $(this).val() + '",';
                            }

                        });
                        if (flag == 0) {
                            value = value+'"' + n +'미선택"],';
                        } else {
                            value = value.substr(0, (value.length - 1)) + "],";
                        }
                    } else {
                        if ($(this).find('input[name="question_' + n + '"]:checked').val() !== undefined && $(this).find('input[name="question_' + n + '"]:checked').val() !== null && $(this).find('input[name="question_' + n + '"]:checked').val() !== "") {
                            if($(this).find('#other_input_'+ n).is(":checked")) {
                                // if($(this).find('input[name="question_other_' + n + '"]').val() !="")
                                value += '"' + n + '":"' + n +"기타"+$(this).find('input[name="question_other_' + n + '"]').val() + '",';
                            }else {
                                value += '"' + n + '":"' + n + $(this).find('input[name="question_' + n + '"]:checked').val() + '",';
                            }
                        }else {
                            value += '"' + n + '":"' + n +'미선택",';
                        }
                    }

                } else if (type == 1) {
                    // 객관식
                    var question2_count = $(this).find('#question2_count').val();
                    var i = 0;
                    var flag = 0;
                    value += '"' + n + '":[';
                    for (i = 0; i < question2_count; i++) {
                        if ($(this).find('input[name="question2_' + i + '"]').val() !== "" && $(this).find('input[name="question2_' + i + '"]').val() !== undefined) {
                            value += '"' + $(this).find('input[name="question2_' + i + '"]').val() + '",';
                            flag = 1;
                        }
                    }
                    if (flag == 0) {
                        value = value+'"' + '미선택"],';
                    } else {
                        value = value.substr(0, (value.length - 1)) + "],";
                    }
                } else if (type == 3) {
                    // 강사 만족도
                    var teacher_array = $(this).find('.teacher_id');
                    var teacher_count = teacher_array.length;
                    var i = 0, j = 0;
                    var flag = 0;
                    value += '"' + n + '":[';
                    for (i = 0; i < teacher_count; i++) {
                        var question_exam_kinds_array = $(teacher_array[i]).find('.question_exam_kinds_id');
                        var question_exam_kinds_count = question_exam_kinds_array.length;
                        var teacher_id = $(teacher_array[i]).attr('teacher_id');

                        if ( teacher_id !== "" && teacher_id !== undefined) {

                            value += '"' + n + 't' + teacher_id + '":[[';
                            //강사에게 하고싶은말
                            value += $(teacher_array[i]).find('textarea[name="teacher_desc"]').val() + "],";

                            flag = 1;
                            value += "[";
                            for (j = 0; j < question_exam_kinds_count; j++) {
                                var question_exam_kinds_id = $(question_exam_kinds_array[j]).attr('question_exam_kinds_id');

                                if ( question_exam_kinds_id !== "" && question_exam_kinds_id !== undefined) {

                                    if ($(this).find('input[name="rating"]:checked').val() !== undefined) {
                                        value += '"' + n + '|' + teacher_id + '|' + question_exam_kinds_id + $(question_exam_kinds_array[j]).find('input[name="rating"]:checked').val() + '",';
                                    } else {
                                        value += '"' + n + '|' + teacher_id + '|' + question_exam_kinds_id + '미선택",';
                                    }
                                }
                            }
                            value += "],],";
                        }
                    }
                    value = value + "],";
                } else {
                    // 만족도
                    if ($(this).find('input[name="rating"]:checked').val() !== undefined) {
                        value += '"' + n + '":"' + n + $(this).find('input[name="rating"]:checked').val() + '",';
                    } else {
                        value += '"' + n + '":"' + n +'미선택",';
                    }
                   
                }
            });
            if (value.indexOf(",") == -1) {
                alert('설문에 참가하지 않으렵니까');
            } else {
                var flag = 0;
                var review_infor = $('#review_infor').val();
                if(review_infor == "1") {
                    if($('#review_year').val() =="") {
                        alert('학년을 입력해주세요');
                        flag = 1;
                    } else if($('#review_half').val() =="") {
                        alert('반을 입력해주세요');
                        flag = 1;
                    }else if($('#review_name').val() =="") {
                        alert('이름을 입력해주세요');
                        flag = 1;
                    }
                    response_man = $('#review_year').val()+"-"+$('#review_half').val()+$('#review_name').val();
                } else  if(review_infor == "2") {

                    if($('#review_half').val() =="") {
                        alert('소속을 입력해주세요');
                        flag = 1;
                    } else if($('#review_name').val() =="") {
                        alert('이름을 입력해주세요');
                        flag = 1;
                    }
                    response_man = $('#review_half').val()+$('#review_name').val();
                } else  if(review_infor == "3") {
                    if($('#review_name').val() =="") {
                        alert('이름을 입력해주세요');
                        flag = 1;
                    }
                    response_man = $('#review_name').val();
                }

                if (flag == 0) {
                    value = value.substr(0, (value.length - 1)) + "}";
                    answer.answer = value;
                    $.ajax({
                        url: site_url + 'preview/save',
                        cache: false,
                        timeout: 10000,
                        dataType: 'text',
                        data: {
                            answer: value,
                            notice_id:notice_id,
                            mobile:mobile,
                            response_man: response_man,
                        },
                        type: 'POST',
                        dataType: "json",
                        success: function (data) {
                            if (data == -1) {
                                alert('귀하는 이미 설문에 참여 하셨습니다.');
                            } else {
                                var real_index = Number(index) - 20;

                                if ($('#comment_' + real_index).val() != "" && $('#comment_' + real_index).val() != undefined) {
                                    alert($('#comment_' + real_index).val());
                                } else {
                                    alert('설문에 참가하여주셔서 감사합니다.');
                                }


                            }
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert('실패');
                        }
                    });
                }
            }
        }
    }

}


//본인인증확인
function auth_confirm() {
    var address_num = $('#auth_adress').val();
    if(address_num.length < 4) {
        alert("4자리 입력하여야 합니다.")
    } else {
        $.ajax({
            url: site_url + 'preview/auth_confirm',
            type: 'POST',
            data: {
                address_num: address_num,
            },
            error: function (data) {
                alert("인증코드가 잘못되었습니다.");
                // swal({
                //         title: '', text: '인증코드가 잘못되었습니다.',
                //         confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                //     },
                //     function (isConfirm) {
                //     });

            },
            success: function (data) {
                if (data > 0) {
                    location.href = site_url + 'preview/view';
                } else {
                    alert("인증코드가 잘못되었습니다.");
                }
            }
        });
    }
}
function other_input_check(val) {
    $("#other_input_"+val).prop('checked',true);
}
function set_radio_value(val) {
    var value = $('input[name="question_other_' + val + '"]').val();

    $("#other_input_"+val).val("기타"+value);

}

//------------- 모바일보기에 대한 사건처리 --------------
function onViewMobile(filePath){
    $.ajax({
        url: site_url + 'preview/mobileView',
        type: 'POST',
        data: {
            file_path: filePath,
        },
        error: function () {
            swal({title: '', text: '적재실패',
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                function(isConfirm) {});
        },
        success: function (mobileHTML) {
            if(mobileHTML == -2){
                swal({title: '', text: '파일이 존재하지 않습니다',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                    function(isConfirm) {});
                return;
            }
            $("#common_view").show();
            $("#mobile_view").hide();

            $('#attached_content').html(mobileHTML);
        }
    });
}
//------------ 일반보기에 대한 사건처리 ----------
function onViewCommon(filePath){
    $.ajax({
        url: site_url + 'preview/commonView',
        type: 'POST',
        data: {
            file_path: filePath,
        },
        error: function () {
            swal({title: '', text: '적재실패',
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                function(isConfirm) {});
        },
        success: function (commonHTML) {
            if(commonHTML == -2){
                swal({title: '', text: '파일이 존재하지 않습니다',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                    function(isConfirm) {});
                return;
            }
            $("#common_view").hide();
            $("#mobile_view").show();

            $('#attached_content').html(commonHTML);
        }
    });
}