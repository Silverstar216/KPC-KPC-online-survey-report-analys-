/**
 * User: KMC
 * Date: 10/08/2018
 * Time: 9:29 PM
 *
 */
var g_uploader;
var max_file_size = 100;

var current_page_index = 0;
var question_count = 0;
var question_count_page = 0;
var preview_auth = 0;
var preview_attached = 0;
var end_check_flag = 0;
var current_page = 1;
var total_count= 0;
var end_page = 1;
var page_per_count = 10;
var page_per_count = 10;
var survey_id = 0;
$(function () {
    EditUpload.init();

    $("#questions_modal").draggable({
        handle: ".modal-header"
    });

    // $('#survey_start_date').datetimepicker({
    //     lang: 'ko',
    //     format: 'Y-m-d H:i',
    //     formatDate: 'Y-m-d',
    //     minDate:new Date(),
    //     scrollMonth: false,
    //     scrollTime: false,
    //     scrollInput: false,

    //     onSelectDate: function() {
    //         $(this).trigger('close.xdsoft');
    //     }

    // });

    // $('#survey_end_date').datetimepicker({
    //     lang: 'ko',
    //     format: 'Y-m-d H:i',
    //     formatDate: 'Y-m-d',
    //     minDate:new Date(),
    //     scrollMonth: false,
    //     scrollTime: false,
    //     scrollInput: false,

    //     onSelectDate: function() {
    //         $(this).trigger('close.xdsoft');
    //     }
    // });

    // 1개의 그룹을 쌤플로부터 추가 (쌤플블로크 div.survey-group-container-sample)
    on_change_group_count();

    $('input:checkbox[name=end_check_1]').on('change', function() {

        if ($(this).is(':checked')) {
            $('#end_comment_1').removeAttr('readonly');
            add_question_move_end(true,1);
            end_check_flag = 1;
            $('#end_comment_1').val("진단참여에 감사드립니다.");

        } else {
            if($('input:checkbox[name=end_check_2]').is(':checked')) {
                swal({title: '', text: "종료2 체크를 해제하여야 가능합니다.",
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                    function(isConfirm) {});
                $('input:checkbox[name=end_check_1]').prop('checked',true);
            } else {
                $('#end_comment_1').prop('readonly', true);
                $('#end_comment_1').val("");
                add_question_move_end(false,1);
                end_check_flag = 0;
            }

        }
    });
    $('input:checkbox[name=end_check_2]').on('change', function() {
        if ($(this).is(':checked')) {
            if($('input:checkbox[name=end_check_1]').is(':checked')) {
                $('#end_comment_2').removeAttr('readonly');
                add_question_move_end(true,2);
                end_check_flag = 2;
            } else {
                swal({title: '', text: "종료1을 체크하여야 가능합니다.",
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                    function(isConfirm) {});
                $('input:checkbox[name=end_check_2]').prop('checked',false);
            }


        } else {
            if($('input:checkbox[name=end_check_3]').is(':checked')) {
                swal({title: '', text: "종료3 체크를 해제하여야 가능합니다.",
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                    function(isConfirm) {});
                $('input:checkbox[name=end_check_2]').prop('checked',true);
            } else {
                $('#end_comment_2').prop('readonly', true);
                $('#end_comment_2').val("");
                add_question_move_end(false,2);
                end_check_flag = 1;
            }
        }
    });
    $('input:checkbox[name=end_check_3]').on('change', function() {
        if ($(this).is(':checked')) {
            if($('input:checkbox[name=end_check_2]').is(':checked')) {
                $('#end_comment_3').removeAttr('readonly');
                add_question_move_end(true,3);
                end_check_flag = 3;
            } else {
                swal({title: '', text: "종료2을 체크하여야 가능합니다.",
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                    function(isConfirm) {});
                $('input:checkbox[name=end_check_3]').prop('checked',false);
            }


        } else {
            if($('input:checkbox[name=end_check_4]').is(':checked')) {
                swal({title: '', text: "종료4 체크를 해제하여야 가능합니다.",
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                    function(isConfirm) {});
                $('input:checkbox[name=end_check_3]').prop('checked',true);
            } else {
                $('#end_comment_3').prop('readonly', true);
                $('#end_comment_3').val("");
                add_question_move_end(false,3);
                end_check_flag = 2;
            }
        }
    });
    $('input:checkbox[name=end_check_4]').on('change', function() {
        if ($(this).is(':checked')) {
            if($('input:checkbox[name=end_check_3]').is(':checked')) {
                $('#end_comment_4').removeAttr('readonly');
                add_question_move_end(true,4);
                end_check_flag = 4;

            } else {
                swal({title: '', text: "종료3을 체크하여야 가능합니다.",
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                    function(isConfirm) {});
                $('input:checkbox[name=end_check_4]').prop('checked',false);
            }

        } else {
            $('#end_comment_4').prop('readonly', true);
            $('#end_comment_4').val("");
            add_question_move_end(false,4);
            end_check_flag = 3;
        }
    });
    $('input:checkbox[name=end_check_1]').prop('checked',true);
    $('#end_comment_1').removeAttr('readonly');
    $('#end_comment_1').val("진단참여에 감사드립니다.");
    add_question_move_end(true,1);
    end_check_flag = 1;

    survey_id = $('#survey_id').val();

    if(Number(survey_id)>0) {
        setData();
    }
});

var EditUpload = function () {

    var handleFile = function() {

        g_uploader = new plupload.Uploader({

            runtimes : 'html5,flash,silverlight,html4',
            multi_selection : false,

            browse_button : document.getElementById('pick_file_area'),
            container: document.getElementById('file_container'),

            url : site_url + 'survey/upload_file',
            drag_and_drop: true,
            drop_element: $('#uploader_filelist')[0],

            // filters : {
            //     max_file_size : max_file_size + 'mb',
            //     mime_types: [
            //         { title : "Hwp files", extensions : "hwp" },
            //         { title : "Doc files", extensions : "doc,docx" },
            //         { title : "HTML files", extensions : "htm,html" },
            //         { title : "PDF files", extensions : "pdf" }
            //     ]
            // },

            unique_names : true,

            init: {

                PostInit: function() {


                    $('#uploader_filelist').on('click', '.added-files .remove', function(){
                        var uploaded_file_id = $(this).parent('.added-files').attr("id");
                        uploaded_file_id = uploaded_file_id.substr('uploaded_file_'.length);
                        g_uploader.removeFile(uploaded_file_id);
                        $(this).parent('.added-files').remove();
                    });
                },

                FilesAdded: function(up, files) {
                    while(g_uploader.files.length > 1) {
                        g_uploader.removeFile(g_uploader.files[0].id);

                    }

                    if (files.length > 0) {
                        var file = files[0];
                        $('#uploader_filelist').html("");
                        $('#uploader_filelist').append('<div class="alert alert-warning added-files" id="uploaded_file_' + file.id + '">' + file.name + '(' + plupload.formatSize(file.size) + ') <span class="status label label-info"></span></div>');

                        // 미리 보기 단추 비활성화
                        $('#import').removeAttr('disabled');

                        // 화일선택후 자동으로 업로드
                        g_uploader.start();
                        myApp.showProgress();
                    }
                },



                UploadProgress: function(up, file) {
                    var loaded = file.loaded;
                    var loaded_unit = 'KB';
                    var size = file.size;
                    var size_unit = 'KB';

                    if (file.loaded > 1024 * 1024 * 1024) {
                        loaded = file.loaded / (1024 * 1024 * 1024);
                        loaded_unit = 'GB';
                    }
                    else if (file.loaded > 1024 * 1024) {
                        loaded = file.loaded / (1024 * 1024);
                        loaded_unit = 'MB';
                    }
                    else if (file.loaded > 1024) {
                        loaded = (file.loaded / 1024);
                        loaded_unit = 'KB';
                    }

                    if (file.size > 1024 * 1024 * 1024) {
                        size = file.size / (1024 * 1024 * 1024);
                        size_unit = 'GB';
                    }
                    else if (file.size > 1024 * 1024) {
                        size = file.size / (1024 * 1024);
                        size_unit = 'MB';
                    }
                    else if (file.size > 1024) {
                        size = (file.size / 1024);
                        size_unit = 'KB';
                    }

                    loaded = Math.floor(loaded * 100) / 100;
                    size = Math.floor(size * 100) / 100;

                    var label = file.percent + '% (' + loaded + loaded_unit + ')';

                    myApp.updateProgress(file.percent, label);
                },

                FileUploaded: function(up, file, response) {
                    afterUploaded(response.response);
                    myApp.hideProgress();
                    myApp.updateProgress(0, '');
                },

                Error: function(up, err) {
                    myApp.hideProgress();
                    myApp.updateProgress(0, '');
                    console.log(err);
                    swal({title: '', text: err.message,
                            confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                        function(isConfirm) {});
                }
            }
        });

        g_uploader.init();
    }

    return {
        init: function () {
            handleFile();
        }
    };

}();

// 진단전송
function post()
{
    var valid = true;

    var survey_attached = $('#survey_attached').val();

    var attached_file_name = $('#attached_file_name').val();
    var attached_origin_file_name = $('#attached_origin_file_name').val();
    var attached_check = $('#attached_check').val();

    if (survey_attached == 1 && attached_file_name == '') {
        valid = false;
        swal({title: '', text: '첨부하시려는 문서를 선택하지 않았습니다.',
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {});
        return;
    }
    if (survey_attached == 1 && attached_file_name != '' && attached_check !=1) {
        valid = false;
        swal({title: '', text: '포함하기단추를 눌러주십시요',
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {});
        return;
    }

    var survey_title = $('#survey_title').val();
    var diag_type = get_diagtype_value();

    if (survey_title == '') {
        valid = false;
        swal({title: '', text: '진단제목을 입력하십시요.',
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {
                $('#survey_title').focus();
            });
        return;
    }
    var start_date = $('#survey_start_date').val();
    var end_date = $('#survey_end_date').val();
    var schedule_name = $('#schedule_name').val();
    var schedule_count = $('#schedule_count').val();
    var end_condition = $("input[name='survey_end_condition']:checked").val();
    var end_count = parseInt($('#survey_end_count').val());
    if(end_condition== 1) {
        if ((isNaN(end_count) || end_count < 1)) {
            valid = false;
            swal({title: '', text: '응답자 인원수를 입력하십시요.',
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                function(isConfirm) {});

            return;
        }
    } else {   //  진단기간완료를 선택했을경우
        if (start_date == '') {
            valid = false;
            swal({title: '', text: '시작날자를 선택하십시요.',
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                function(isConfirm) {
                    $('#survey_start_date').focus();
                });
            return;
        }


        if (end_date == '') {
            valid = false;
            swal({title: '', text: '시작날자를 선택하십시요.',
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                function(isConfirm) {
                    $('#survey_end_date').focus();
                });
            return;
        }
        start_date = start_date+" 00:00:00";
        end_date = end_date+" 00:00:00";

        if (get_date_diff_from_string(start_date, end_date) <= 0) {
            valid = false;
            swal({title: '', text: '날자범위를 정확히 지정하십시오.',
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                function(isConfirm) {

                });
            // open_my_modal('날자범위를 정확히 지정하십시오.');
        }
    }


    var auth = $("input[name='survey_auth']:checked").val();

    var review_infor = $("input[name='review_infor']:checked").val();

    var question_group_count = $("#survey_group_count").val();
    var question_count = $('.survey-question-container').length;

    var question_count_page = $('#survey_question_count_page').val();
    if($('#newflag').val()==1) {
        survey_id = 0;
    }
    var params = {};
    params.survey_id = survey_id;
    params.survey_attached = survey_attached;
    params.attached_file_name = attached_file_name;
    params.attached_origin_file_name = attached_origin_file_name;
    params.survey_title = survey_title;
    params.diag_type = diag_type;
    params.start_time = start_date;
    params.end_time = end_date;
    params.schedule_name = schedule_name;
    params.schedule_count = schedule_count;
    params.end_condition = end_condition;
    params.end_count = end_count;
    params.auth = auth;
    params.review_infor = review_infor;
    params.question_count = question_count;
    params.question_group_count = question_group_count;
    params.question_count_page = question_count_page;

    var question_groups = [];
    $('.survey-group-container').each(function(question_group_index) {

        if (!valid)
            return;
        var question_group = {};
        question_group.number = question_group_index;
        question_group.title = $(this).find('.group-title').val();
        question_group.question_count = $(this).find('.survey_question_count').val();

        var questions = [];

        $(this).find('.survey-question-container').each(function (question_index) {

            if (!valid)
                return;
            var question = {};
            question.number = question_index;
            question.type = $(this).find('.survey-question .btn-warning').attr('question-type');
            question.question = $(this).find('.survey-question-title').val();
            question.question_img_url = $(this).find('.question-thumbnail-file-name').val();

            if (question.question == '') {
                valid = false;
                swal({
                        title: '', text: $.sprintf('%d번문항의 질문을 입력하십시오.', question_index + 1),
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'
                    },
                    function (isConfirm) {
                    });
                return;
            }

            if (question.type == 0) {
                question.allow_reply_response = $(this).find('.reply_response').is(':checked') ? 1 : 0;
                question.use_other_input = $(this).find('.use_other_input').is(':checked') ? 1 : 0;
                question.example_has_image = $(this).find('.example-image-check').is(':checked') ? 1 : 0;
                question.allow_unselect = $(this).find('.allow_unselect').is(':checked') ? 1 : 0;
                question.allow_random_align = $(this).find('.allow_random_align').is(':checked') ? 1 : 0;

                var examples = [];
                $(this).find('.survey-example .examples .example').each(function (exam_index) {
                    if (!valid)
                        return;
                    var example = {};
                    example.number = exam_index;
                    example.question_move = $(this).find('.question-move').val();
                    if (question.example_has_image == 1) {
                        example.img_url = $(this).find('.example-thumbnail-file-name').val();
                        if (example.img_url == '') {
                            valid = false;
                            swal({
                                    title: '', text: $.sprintf('%d문항의 %d번 그림을 선택하십시오.', question_index + 1, exam_index + 1),
                                    confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'
                                },
                                function (isConfirm) {
                                });

                            return;
                        }
                    }
                    example.title = $(this).find('.example-title').val();
                    if (example.title == '') {
                        valid = false;
                        swal({
                                title: '', text: $.sprintf('%d문항의 %d번 내용을 입력하십시오.', question_index + 1, exam_index + 1),
                                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'
                            },
                            function (isConfirm) {
                            });
                        valid = false;
                        return;
                    }

                    examples[exam_index] = example;
                });
                question.examples = examples;
                question.example_count = examples.length;
            }
            else if (question.type == 1) {
                question.example_count = $(this).find('#survey-example-count1').val();
                question.allow_unselect = $(this).find('.allow_unselect_1').is(':checked') ? 1 : 0;
                question.end_comment_index = $(this).find('.end-move').val();
            }
            else if (question.type == 2) {
                question.type_grade = $(this).find('.question-type-grade').val();
                question.example_count = $(this).find('#survey-example-count2').val();
                question.allow_unselect = $(this).find('.allow_unselect_2').is(':checked') ? 1 : 0;
            }
            else if (question.type == 3) {
                question.type_grade = $(this).find('.question-type-grade').val();
                question.example_count = $(this).find('#survey-example-count2').val();

                question.exam_kind_count = $(this).find('.exam_kind_count').val();
                question.exam_object_count = $(this).find('.exam_object_count').val();
                question.allow_unselect = $(this).find('.allow_unselect_2').is(':checked') ? 1 : 0;
                //평가지표얻기
                var exam_kinds = [];
                $(this).find('.exam-kind-tr').each(function(exam_kind_index) {
                    if (!valid)
                        return;
                    var exam_kind = {};
                    exam_kind.number = exam_kind_index;
                    exam_kind.title = $(this).find('.exam-kind-title').val();
                    if (exam_kind.title == '') {
                        valid = false;
                        swal({
                                title: '', text: $.sprintf('그룹%d_%d문항의 평가지표%d의 제목을 입력하십시오.',question_group_index + 1, question_index + 1, exam_kind_index + 1),
                                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'
                            },
                            function (isConfirm) {
                            });
                        valid = false;
                        return;
                    }
                    exam_kind.content = $(this).find('.exam-kind-content').val();
                    if (exam_kind.content == '') {
                        valid = false;
                        swal({
                                title: '', text: $.sprintf('그룹%d_%d문항의 평가지표%d의 내용을 입력하십시오.',question_group_index + 1, question_index + 1, exam_kind_index + 1),
                                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'
                            },
                            function (isConfirm) {
                            });
                        valid = false;
                        return;
                    }
                    exam_kinds[exam_kind_index] = exam_kind;
                });
                question.exam_kinds = exam_kinds;

                //평가대상얻기
                var exam_objects = [];
                $(this).find('.exam-object-tr').each(function(exam_object_index) {
                    if (!valid)
                        return;
                    var exam_object = {};
                    exam_object.number = exam_object_index;
                    exam_object.title = $(this).find('.exam-object-title').val();
                    if (exam_object.title == '') {
                        valid = false;
                        swal({
                                title: '', text: $.sprintf('그룹%d_%d문항의 평가대상%d의 이름을 입력하십시오.',question_group_index + 1, question_index + 1, exam_object_index + 1),
                                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'
                            },
                            function (isConfirm) {
                            });
                        valid = false;
                        return;
                    }
                    exam_objects[exam_object_index] = exam_object;
                });

                question.exam_objects = exam_objects;
            }

            questions[question_index] = question;
        });

        question_group.questions = questions;
        question_groups[question_group_index] = question_group;
    });

    var end_comment=[];
    for(var i = 1; i < Number(end_check_flag)+1; i++) {
        end_comment[i-1] =  $('#end_comment_'+i).val();
    }
    if(valid) {
        params.question_groups = JSON.stringify(question_groups);
        params.end_comments = JSON.stringify(end_comment);
        $.post(site_url + 'diagnosis/post',
            params,
            function (data, status) {
                if(data > 0) {

                    var object_id = data;
                    location.href = site_url + 'notice?object_id=' + object_id + '&survey=1';
                } else {
                    location.href = site_url + 'notice';
                }
            });
    }
}

// 진단보관
function save()
{
    var valid = true;

    var survey_attached = $('#survey_attached').val();

    var attached_file_name = $('#attached_file_name').val();
    var attached_origin_file_name = $('#attached_origin_file_name').val();
    var attached_check = $('#attached_check').val();

    var survey_title = $('#survey_title').val();
    var diag_type = get_diagtype_value();
    var start_date = $('#survey_start_date').val();
    var end_date = $('#survey_end_date').val();
    var schedule_name = $('#schedule_name').val();
    var schedule_count = $('#schedule_count').val();

    var end_condition = $("input[name='survey_end_condition']:checked").val();
    var end_count = parseInt($('#survey_end_count').val());

    if (start_date != '') {
        start_date = start_date+":00";
    }
    if (end_date != '') {
        end_date = end_date+":00";
    }
    end_date = end_date+":00";

    var auth = $("input[name='survey_auth']:checked").val();
    var review_infor = $("input[name='review_infor']:checked").val();

    var question_count_page = $('#survey_question_count_page').val();
    if($('#newflag').val()==1) {
        survey_id = 0;
    }
    var question_group_count = $("#survey_group_count").val();

    var question_count = $('.survey-question-container').length;

    var params = {};
    params.survey_id = survey_id;
    params.survey_attached = survey_attached;
    params.attached_file_name = attached_file_name;
    params.attached_origin_file_name = attached_origin_file_name;
    params.survey_title = survey_title;
    params.diag_type = diag_type;
    params.start_time = start_date;
    params.end_time = end_date;
    params.schedule_name = schedule_name;
    params.schedule_count = schedule_count;
    params.end_condition = end_condition;
    params.end_count = end_count;
    params.auth = auth;
    params.review_infor = review_infor;
    params.question_count_page = question_count_page;
    params.question_group_count = question_group_count;
    params.question_count = question_count;

    var question_groups = [];

    $('.survey-group-container').each(function(question_group_index) {

        if (!valid)
            return;
        var question_group = {};
        question_group.number = question_group_index;
        question_group.title = $(this).find('.group-title').val();
        question_group.question_count = $(this).find('.survey_question_count').val();

        var questions = [];

        $(this).find('.survey-question-container').each(function(question_index) {
            if (!valid)
                return;
            var question = {};
            question.number = question_index;
            question.type = $(this).find('.survey-question .btn-warning').attr('question-type');
            question.question = $(this).find('.survey-question-title').val();
            question.question_img_url = $(this).find('.question-thumbnail-file-name').val();

            if (question.type == 0) {//주관식
                question.allow_reply_response = $(this).find('.reply_response').is(':checked') ? 1 : 0;
                question.use_other_input = $(this).find('.use_other_input').is(':checked') ? 1 : 0;
                question.example_has_image = $(this).find('.example-image-check').is(':checked') ? 1 : 0;
                question.allow_unselect = $(this).find('.allow_unselect').is(':checked') ? 1 : 0;
                question.allow_random_align = $(this).find('.allow_random_align').is(':checked') ? 1 : 0;

                var examples = [];
                $(this).find('.survey-example .examples .example').each(function(exam_index) {
                    if (!valid)
                        return;
                    var example = {};
                    example.number = exam_index;
                    example.question_move = $(this).find('.question-move').val();
                    if (question.example_has_image == 1) {
                        example.img_url = $(this).find('.example-thumbnail-file-name').val();

                    }
                    example.title = $(this).find('.example-title').val();
                    examples[exam_index] = example;
                });
                question.examples = examples;
                question.example_count = examples.length;
            }
            else if (question.type == 1) {
                question.example_count = $(this).find('#survey-example-count1').val();
                question.allow_unselect = $(this).find('.allow_unselect_1').is(':checked') ? 1 : 0;
                question.end_comment_index = $(this).find('.end-move').val();
            }
            else if (question.type == 2) {
                question.type_grade = $(this).find('.question-type-grade').val();
                question.example_count = $(this).find('#survey-example-count2').val();
                question.allow_unselect = $(this).find('.allow_unselect_2').is(':checked') ? 1 : 0;
            }
            else if (question.type == 3) {
                question.type_grade = $(this).find('.question-type-grade').val();
                question.example_count = $(this).find('#survey-example-count2').val();

                question.exam_kind_count = $(this).find('.exam_kind_count').val();
                question.exam_object_count = $(this).find('.exam_object_count').val();
                question.allow_unselect = $(this).find('.allow_unselect_2').is(':checked') ? 1 : 0;
                //평가지표얻기
                var exam_kinds = [];
                $(this).find('.exam-kind-tr').each(function(exam_kind_index) {
                    if (!valid)
                        return;
                    var exam_kind = {};
                    exam_kind.number = exam_kind_index;
                    exam_kind.title = $(this).find('.exam-kind-title').val();
                    exam_kind.content = $(this).find('.exam-kind-content').val();

                    exam_kinds[exam_kind_index] = exam_kind;
                });
                question.exam_kinds = exam_kinds;

                //평가대상얻기
                var exam_objects = [];
                $(this).find('.exam-object-tr').each(function(exam_object_index) {
                    if (!valid)
                        return;
                    var exam_object = {};
                    exam_object.number = exam_object_index;
                    exam_object.title = $(this).find('.exam-object-title').val();

                    exam_objects[exam_object_index] = exam_object;
                });
                question.exam_objects = exam_objects;

            }

            questions[question_index] = question;
        });

        question_group.questions = questions;
        question_groups[question_group_index] = question_group;
    });

    var end_comment=[];
    for(var i = 1; i < Number(end_check_flag)+1; i++) {
        end_comment[i-1] =  $('#end_comment_'+i).val();
    }

    if(valid) {
        params.question_groups = JSON.stringify(question_groups);
        params.end_comments = JSON.stringify(end_comment);
        $.post(site_url + 'diagnosis/save',
            params,
            function (data, status) {                

                if(data > 0) {
                    survey_id = data;
                    $('#newflag').val("2");
                    swal({title: '', text: '성공적으로 저장하였습니다.',
                            confirmButtonText: '확인', allowOutsideClick: false, type: 'success'},
                        function(isConfirm) {});
                } else if(data ==-1){
                    swal({title: '', text: '회원가입하여야 가능합니다.',
                            confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                        function(isConfirm) {
                            location.href=site_url + 'join/login_view';
                        });
                } else {
                    swal({
                            title: '', text: '저장이 실패하였습니다.',
                            confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                        },
                        function (isConfirm) {
                        });
                }
            });
    }
}

// 첨부문서 올리적재 끝난후
function afterUploaded(data) {
    var obj = jQuery.parseJSON(data);
    $('#attached_check').val("0");
    if (obj.status == 'OK') {
        // 화일 올리적재 성공
        // 미리 보기 단추 활성화
        if(obj.file_name.indexOf('hwp') === -1 && obj.file_name.indexOf('pdf') === -1 && obj.file_name.indexOf('doc') === -1 && obj.file_name.indexOf('html') === -1){
            swal({title: '', text: "hwp,pdf,doc,html 문서만 포함할수있습니다.",
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                function(isConfirm) {});
            while(g_uploader.files.length > 0) {
                g_uploader.removeFile(g_uploader.files[0].id);
            }
            //g_uploader.disableBrowse(false);

            $('#attached_file_name').val('');
            $('#attached_origin_file_name').val('');
            $('#uploader_filelist').html("선택된 파일이 없음");
            $('#import').prop('disabled',true);
        } else {
            $('#import').removeAttr('disabled');
            $('#preview').removeAttr('disabled');
            $('#attached_file_name').val(obj.file_name);
            $('#attached_origin_file_name').val(obj.origin_file_name);
        }

    }
    else {

        while(g_uploader.files.length > 0) {
            g_uploader.removeFile(g_uploader.files[0].id);
        }
        //g_uploader.disableBrowse(false);
        swal({title: '', text: obj.msg,
                confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
            function(isConfirm) {});
        $('#attached_file_name').val('');
        $('#attached_origin_file_name').val('');
        $('#uploader_filelist').html("선택된 파일이 없음");
    }
    return;
}

// 진단 시작시간, 끝시간 범위검사
function check_date_range() {
    var start_date = $('#survey_start_date').val();
    var end_date = $('#survey_end_date').val();

    var start_time = $('#survey_start_time').val();
    var end_time = $('#survey_end_time').val();

    start_date = $.sprintf( '%s %02d:00:00', start_date, start_time);
    end_date = $.sprintf( '%s %02d:00:00', end_date, end_time);

    if (get_date_diff_from_string(start_date, end_date) <= 0) {
        swal({title: '', text: '날자범위를 정확히 지정하십시오.',
                confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
            function(isConfirm) {

            });
        // open_my_modal('날자범위를 정확히 지정하십시오.');
    }
}

//그룹개수변경처리
function on_change_group_count(){
    var group_count = $('#survey_group_count').val();

    var cur_group_count = $('.survey-group-container').length;

    if (group_count < cur_group_count) {
        $( '.survey-group-container' ).each(function( index ) {

            question_count = $('#survey_group_count').val();
            if (index >= question_count) {
                $(this).remove();
            }
        });
    }

    // 그룹 등록 + 사건 추가
    if (group_count > cur_group_count) {
        for (var i = 0; i < group_count - cur_group_count; i++) {
            // 쌤플블로크를 클론하여 추가
            var new_group_container = $('.survey-group-container-sample').first().clone();
            new_group_container.removeClass('survey-group-container-sample').addClass('survey-group-container');
            new_group_container.appendTo('#survey_groups');

            //------------ 그룹번호추가 -------------
            new_group_container.find('.survey-group-number').text('진단그룹' + parseInt(i + 1 + cur_group_count));

            //------------ 샘플문항을 클론하여 survey_questions에 추가 --------------
            new_group_container.find('.survey_question_count').on('change', function() {

                var question_count = $(this).val();
                var closet_question_container = $(this).closest('.survey-group-container').find('.survey_questions').find('.survey-question-container');
                var cur_question_count = closet_question_container.length;

                if (question_count < cur_question_count) {
                    closet_question_container.each(function( index ) {

                        question_count = $(this).closest('.survey-group-container').find('.survey_question_count').val();
                        if (index >= question_count) {
                            $(this).remove();
                        }
                    });
                }

                // 문항 등록 + 사건 추가
                if (question_count > cur_question_count) {
                    for(var i=0; i<question_count - cur_question_count; i++) {
                        // 쌤플블로크를 클론하여 추가
                        var new_container = $( '.survey-question-container-sample').first().clone();
                        new_container.removeClass('survey-question-container-sample').addClass('survey-question-container');
                        new_container.appendTo($(this).closest('.survey-group-container').find('.survey_questions'));

                        // 문항번호추가
                        new_container.find('.survey-question-number').text(i + 1 + cur_question_count + '번문항');

                        // 문항삭제단추 사건추가
                        new_container.find('.btn-question-remove').on('click', function() {
                            var cur_question_count = $(this).closest('.survey-group-container').find('.survey-question-container').length;
                            if (cur_question_count == 1) {
                                swal({title: '', text: '삭제할수 없습니다.',
                                        confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                                    function(isConfirm) {

                                    });
                                return;
                            }
                            $(this).closest('.survey-group-container').find('.survey_question_count').val(cur_question_count - 1);
                            $(this).closest('.survey-question-container').fadeOut( 'slow', function() {
                                $(this).closest('.survey-question-container').remove();
                                remove_questions();
                            });
                        });
                        // 문항우로이동단추 사건추가
                        new_container.find('.btn-question-up').on('click', function() {
                            var container = $(this).closest('.survey-question-container');
                            container.insertBefore(container.prev());

                            move_questions();
                        });
                        // 문항아래로이동단추 사건추가
                        new_container.find('.btn-question-down').on('click', function() {
                            var container = $(this).closest('.survey-question-container');
                            container.insertAfter(container.next());
                            move_questions();
                        });

                        // 문항형태선택 사건추가 (선택된 단추에 btn-warning클라스추가하여 강조표시)
                        new_container.find('.btn-question-type').on('click', function() {
                            var container = $(this).closest('.survey-question-container');
                            var type = $(this).attr('question-type');
                            change_question_type(container, type);
                        });

                        //객관식에서 중복응답체크선택사건처리
                        new_container.find('.reply_response').on('change', function() {
                            var container = $(this).closest('.survey-question-container');
                            if ($(this).is(':checked')) {
                                container.find('input:checkbox[name=use_other_input]').prop("checked", false);
                                container.find('#sub_input_use_scope').css('display', 'none');
                                set_visible_question_move(container,1);
                            } else {

                                container.find('#sub_input_use_scope').css('display', 'inline');
                                container.find('input:checkbox[name=use_other_input]').prop("checked", false);
                                set_visible_question_move(container,0);
                            }
                        });

                        // 질문 이미지 선택 사건처리
                        new_container.find('.question-thumb').fileupload({
                            url: site_url + 'survey/upload_img',
                            survey_container: new_container,
                            dataType: 'json',
                            done: function (e, data) {


                                $.each(data.result.files, function (index, file) {
                                    if(file.error =="File is too big") {
                                        swal({
                                                title: '', text: "이미지파일크기가 200kb 까지 업로드할수있습니다.",
                                                confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                                            },
                                            function (isConfirm) {
                                            });
                                    } else {
                                        var container = data.survey_container;
                                        add_question_img(container, 'survey/thumb_tmp/' + file.name);
                                    }
                                });

                            },
                            progressall: function (e, data) {
                                var progress = parseInt(data.loaded / data.total * 100, 10);
                                $('#progress .progress-bar').css(
                                    'width',
                                    progress + '%'
                                );
                            },
                            Error: function(e, data) {
                                swal({title: '', text: e.message,
                                        confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                                    function(isConfirm) {});
                            }
                        }).prop('disabled', !$.support.fileInput)
                            .parent().addClass($.support.fileInput ? undefined : 'disabled');
                        // 질문 이미지 삭제 사건처리
                        new_container.find('.survey-query-img .btn-del-img').on('click', function() {
                            var container = $(this).closest('.survey-question-container');
                            remove_question_img(container);
                        });
                        // 객관식 보기 이메지삽입체크 사건처리
                        new_container.find('.example-image-check').on('change', function() {
                            var container = $(this).closest('.survey-question-container');
                            if ($(this).is(':checked'))
                                container.find('.example-image-file-container').css('display', 'inline');
                            else
                                container.find('.example-image-file-container').css('display', 'none');
                        });

                        // 만족도 모양변경 사건처리
                        new_container.find('.question-type-grade').on('change', function() {
                            on_change_type_grade($(this).closest('.survey-question-container'));
                        });
                        // 만족도 개수변경 사건처리
                        new_container.find('.question-option-2 .survey-example-count').on('change', function() {
                            on_change_type_grade($(this).closest('.survey-question-container'));
                        });

                        // 객관식의 보기 1 생성
                        var new_example = new_container.find('.example-sample').first().clone();
                        new_example.appendTo(new_container.find('.examples'));
                        on_add_example(new_example);

                        // 객관식의 보기 2 생성
                        var new_example = new_container.find('.example-sample').first().clone();
                        new_example.appendTo(new_container.find('.examples'));
                        on_add_example(new_example);

                        // 주관식의 입력창개수 변경 사건처리
                        new_container.find('.question-option-1 .survey-example-count').on('change', function() {
                            var count = $(this).val();
                            var container = $(this).closest('.survey-question-container');
                            container.find('.question-type-1 input').each(function(index) {

                                if (index >= count) {
                                    $(this).addClass('hidden');
                                }
                                else {
                                    $(this).removeClass('hidden');
                                }
                            });
                        });

                        //강사만족도의 평가자료개수변경처리
                        new_container.find('.exam_kind_count').on('change', function() {
                            var exam_kind_count = $(this).val();
                            var closet_exam_kind_tr = $(this).closest('.survey-question-container').find('.exam-kind-tr');
                            var cur_exam_kind_count = closet_exam_kind_tr.length;

                            if (exam_kind_count < cur_exam_kind_count) {
                                closet_exam_kind_tr.each(function( index ) {

                                    exam_kind_count = $(this).closest('.survey-question-container').find('.exam_kind_count').val();
                                    if (index >= exam_kind_count) {
                                        $(this).remove();
                                    }
                                });
                            }

                            // 평가지표개수변경 + 사건 추가
                            if (exam_kind_count > cur_exam_kind_count) {
                                for (var i = 0; i < exam_kind_count - cur_exam_kind_count; i++) {
                                    // 쌤플블로크를 클론하여 추가
                                    var new_container = $('.exam-kind-tr-sample').first().clone();
                                    new_container.removeClass('exam-kind-tr-sample').addClass('exam-kind-tr');
                                    new_container.css("display","block");
                                    new_container.appendTo($(this).closest('.section-question-type-3').find('.exam-kind-table'));

                                    new_container.find(".tb03").html("평가지표"+ Number(cur_exam_kind_count + i + 1));
                                }
                            }
                        });

                        new_container.find('.exam_kind_count').trigger("change");

                        //강사만족도의 평가대상개수변경처리
                        new_container.find('.exam_object_count').on('change', function() {
                            var exam_object_count = $(this).val();
                            var closet_exam_object_tr = $(this).closest('.survey-question-container').find('.exam-object-tr');
                            var cur_exam_object_count = closet_exam_object_tr.length;

                            if (exam_object_count < cur_exam_object_count) {
                                closet_exam_object_tr.each(function( index ) {

                                    exam_object_count = $(this).closest('.survey-question-container').find('.exam_object_count').val();
                                    if (index >= exam_object_count) {
                                        $(this).remove();
                                    }
                                });
                            }

                            // 평가대상개수변경 + 사건 추가
                            if (exam_object_count > cur_exam_object_count) {
                                for (var i = 0; i < exam_object_count - cur_exam_object_count; i++) {
                                    // 쌤플블로크를 클론하여 추가
                                    var new_container = $('.exam-object-tr-sample').first().clone();
                                    new_container.removeClass('exam-object-tr-sample').addClass('exam-object-tr');
                                    new_container.css("display","block");
                                    new_container.appendTo($(this).closest('.section-question-type-3').find('.exam-object-table'));

                                    new_container.find(".tb03").html("평가대상"+ Number(cur_exam_object_count + i + 1));
                                }
                            }
                        });

                        new_container.find('.exam_object_count').trigger("change");
                    }
                }
                move_questions();
                on_change_question_move();
            });
            new_group_container.find('.survey_question_count').trigger("change");
        }
    }
}

//문항개수변경처리
function on_change_question_count() {
    var question_count = $('.survey_question_count').val();

    var cur_question_count = $('.survey-question-container').length;

    if (question_count < cur_question_count) {

        $( '.survey-question-container' ).each(function( index ) {

            question_count = $('#survey_question_count').val();
            if (index >= question_count) {
                $(this).remove();
            }
        });
    }
    // 문항 등록 + 사건 추가
    if (question_count > cur_question_count) {
        for(var i=0; i<question_count - cur_question_count; i++) {
            // 쌤플블로크를 클론하여 추가
            var new_container = $( '.survey-question-container-sample').first().clone();
            new_container.removeClass('survey-question-container-sample').addClass('survey-question-container');
            new_container.appendTo('#survey_questions');
            // 문항번호추가
            new_container.find('.survey-question-number').text(i + 1 + cur_question_count + '번문항');

            // 문항삭제단추 사건추가
            new_container.find('.btn-question-remove').on('click', function() {
                var cur_question_count = $('.survey-question-container').length;
                if (cur_question_count == 1) {
                    swal({title: '', text: '삭제할수 없습니다.',
                            confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                        function(isConfirm){ });
                    return;
                }
                $('.survey_question_count').val(cur_question_count - 1);
                $(this).closest('.survey-question-container').fadeOut( 'slow', function() {
                    $(this).closest('.survey-question-container').remove();
                    remove_questions();
                });
            });
            // 문항우로이동단추 사건추가
            new_container.find('.btn-question-up').on('click', function() {

                var container = $(this).closest('.survey-question-container');
                container.insertBefore(container.prev());

                move_questions();
            });
            // 문항아래로이동단추 사건추가
            new_container.find('.btn-question-down').on('click', function() {

                var container = $(this).closest('.survey-question-container');
                container.insertAfter(container.next());
                move_questions();
            });

            // 문항형태선택 사건추가 (선택된 단추에 btn-warning클라스추가하여 강조표시)
            new_container.find('.btn-question-type').on('click', function() {
                var container = $(this).closest('.survey-question-container');
                var type = $(this).attr('question-type');
                change_question_type(container, type);
            });

            //객관식에서 중복응답체크선택사건처리
            new_container.find('.reply_response').on('change', function() {
                var container = $(this).closest('.survey-question-container');
                if ($(this).is(':checked')) {
                    container.find('input:checkbox[name=use_other_input]').prop("checked", false);
                    container.find('#sub_input_use_scope').css('display', 'none');
                    set_visible_question_move(container,1);
                } else {

                    container.find('#sub_input_use_scope').css('display', 'inline');
                    container.find('input:checkbox[name=use_other_input]').prop("checked", false);
                    set_visible_question_move(container,0);
                }
            });

            // 질문 이미지 선택 사건처리
            new_container.find('.question-thumb').fileupload({
                url: site_url + 'survey/upload_img',
                survey_container: new_container,
                dataType: 'json',
                done: function (e, data) {
                    $.each(data.result.files, function (index, file) {
                        if(file.error =="File is too big") {
                            swal({
                                    title: '', text: "이미지파일크기가 200kb 까지 업로드할수있습니다.",
                                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                                },
                                function (isConfirm) {
                                });
                        } else {
                            var container = data.survey_container;
                            add_question_img(container, 'survey/thumb_tmp/' + file.name);
                        }
                    });
                },
                progressall: function (e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $('#progress .progress-bar').css(
                        'width',
                        progress + '%'
                    );
                },
                Error: function(e, data) {
                    swal({title: '', text: e.message,
                            confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                        function(isConfirm) {});
                }
            }).prop('disabled', !$.support.fileInput)
                .parent().addClass($.support.fileInput ? undefined : 'disabled');

            // 질문 이미지 삭제 사건처리
            new_container.find('.survey-query-img .btn-del-img').on('click', function() {
                var container = $(this).closest('.survey-question-container');
                remove_question_img(container);
            });

            // 객관식 보기 이메지삽입체크 사건처리
            new_container.find('.example-image-check').on('change', function() {
                var container = $(this).closest('.survey-question-container');
                if ($(this).is(':checked'))
                    container.find('.example-image-file-container').css('display', 'inline');
                else
                    container.find('.example-image-file-container').css('display', 'none');
            });

            // 만족도 모양변경 사건처리
            new_container.find('.question-type-grade').on('change', function() {
                on_change_type_grade($(this).closest('.survey-question-container'));
            });

            // 만족도 개수변경 사건처리
            new_container.find('.question-option-2 .survey-example-count').on('change', function() {
                on_change_type_grade($(this).closest('.survey-question-container'));
            });

            // 객관식의 보기 1 생성
            var new_example = new_container.find('.example-sample').first().clone();
            new_example.appendTo(new_container.find('.examples'));
            on_add_example(new_example);

            // 객관식의 보기 2 생성
            var new_example = new_container.find('.example-sample').first().clone();
            new_example.appendTo(new_container.find('.examples'));
            on_add_example(new_example);

            // 주관식의 입력창개수 변경 사건처리
            new_container.find('.question-option-1 .survey-example-count').on('change', function() {
                var count = $(this).val();
                var container = $(this).closest('.survey-question-container');
                container.find('.question-type-1 input').each(function(index) {

                    if (index >= count) {
                        $(this).addClass('hidden');
                    }
                    else {
                        $(this).removeClass('hidden');
                    }
                });
            });
        }
    }
    move_questions();
    on_change_question_move();
}

// 문항형태변경단추 선택
function change_question_type(container, type) {
    container.find('.btn-question-type').removeClass('btn-warning').addClass('btn-default');
    container.find($.sprintf('.btn-question-type[question-type=%d]',type)).removeClass('btn-default').addClass('btn-warning');

    // 객관식일때
    if (type == 0) {
        //강사만족도숨기기
        container.find('.section-question-type-3').css('display', 'none');
        // 옵션 변경
        container.find('.question-option-0').css('display', 'block');
        container.find('.question-option-1').css('display', 'none');
        container.find('.question-option-2').css('display', 'none');
        // //질문내용현시
        // container.find('.question_content').css('display', 'table-row');
        // 보기 변경
        container.find('.question-type-0').css('display', 'table-row');
        container.find('.question-type-1').css('display', 'none');
        container.find('.question-type-2').css('display', 'none');
    }
    // 주관식일때
    else if (type == 1) {
        //강사만족도숨기기
        container.find('.section-question-type-3').css('display', 'none');
        //옵션변경
        container.find('.question-option-0').css('display', 'none');
        container.find('.question-option-1').css('display', 'block');
        container.find('.question-option-2').css('display', 'none');

        // //질문내용현시
        // container.find('.question_content').css('display', 'table-row');
        // 보기 변경
        container.find('.question-type-0').css('display', 'none');
        container.find('.question-type-1').css('display', 'table-row');
        container.find('.question-type-2').css('display', 'none');
    }
    // 만족도일때
    else if (type == 2) {
        //강사만족도숨기기
        container.find('.section-question-type-3').css('display', 'none');
        //옵션변경
        container.find('.question-option-0').css('display', 'none');
        container.find('.question-option-1').css('display', 'none');
        container.find('.question-option-2').css('display', 'block');

        // //질문내용현시
        // container.find('.question_content').css('display', 'table-row');
        // 보기 변경
        container.find('.question-type-0').css('display', 'none');
        container.find('.question-type-1').css('display', 'none');
        container.find('.question-type-2').css('display', 'table-row');
    }
    // 강사만족도일때
    else if (type == 3) {
        //강사만족도숨기기
        container.find('.section-question-type-3').css('display', 'block');
        //옵션변경
        container.find('.question-option-0').css('display', 'none');
        container.find('.question-option-1').css('display', 'none');
        container.find('.question-option-2').css('display', 'block');

        // //질문내용숨기기
        // container.find('.question_content').css('display', 'none');

        // 보기 변경
        container.find('.question-type-0').css('display', 'none');
        container.find('.question-type-1').css('display', 'none');
        container.find('.question-type-2').css('display', 'table-row');
    }
}

// 질문이미지 추가
function add_question_img(container, file_name) {
    var question_thumb = container.find('.question-thumb-uploaded');
    question_thumb.attr('src', site_url + file_name);
    question_thumb.removeClass('hidden');
    container.find('.btn-del-img-container').css('display', 'block');
    container.find('.survey-query-img .fileinput-button').css('visibility', 'hidden');
    container.find('.survey-query-img .question-thumbnail-file-name').val(file_name);
}

// 질문이미지 삭제
function remove_question_img(container) {
    var question_thumb = container.find('.question-thumb-uploaded');
    question_thumb.addClass('hidden');
    container.find('.btn-del-img-container').css('display', 'none');
    container.find('.survey-query-img .fileinput-button').css('visibility', 'visible');
    container.find('.survey-query-img .question-thumbnail-file-name').val('');
}

// 보기이미지 추가
function add_example_img(container, file_name) {
    var exam_thumb = container.find('.example-thumb-uploaded');
    exam_thumb.attr('src', site_url + file_name);
    exam_thumb.removeClass('hidden');
    container.find('.btn-exam-del-img-container').css('display', 'block');
    container.find('.example-image-file-container .fileinput-button').css('visibility', 'hidden');
    container.find('.example-image-file-container .example-thumbnail-file-name').val(file_name);
}

// 보기이미지 삭제
function remove_example_img(container) {
    var example_thumb = container.find('.example-thumb-uploaded');
    example_thumb.addClass('hidden');
    container.find('.btn-exam-del-img-container').css('display', 'none');
    container.find('.fileinput-button').css('visibility', 'visible');
    container.find('.example-thumbnail-file-name').val('');
}

// 보기들의 번호수정, 기타항목검사
function update_examples(container) {
    var example_count = container.find('.question-type-0 .example').length;
    var current_count = container.find('.survey-example-min option').length;

    /* var diff = example_count - current_count;

     for (var i=0; i<Math.abs(diff); i++) {
         if (example_count > current_count) {
             $($.sprintf('<option value="%1$d">최소 %1$d개</option>', current_count + i + 1)).appendTo(container.find('.survey-example-min'));
             $($.sprintf('<option value="%1$d">최대 %1$d개</option>', current_count + i + 1)).appendTo(container.find('.survey-example-max'));
         }
         else if (example_count < current_count) {
             container.find($.sprintf('.survey-example-min option:nth-child(%d)', current_count - i)).remove();
             container.find($.sprintf('.survey-example-max option:nth-child(%d)', current_count - i)).remove();
         }
     }*/

    container.find('.question-type-0 .example').each(function(index) {
        $(this).find('.example-number').text('보기 ' + (index + 1));
        $(this).find('.btn-example-plus').css('visibility', 'hidden');

        // 보기개수가 2인 경우 보기삭제불가능
        if (example_count > 2)
            $(this).find('.btn-example-remove').removeClass('disabled');
        else
            $(this).find('.btn-example-remove').addClass('disabled');

        // 첫번째 보기
        if(index == 0) {
            $(this).find('.btn-example-up').addClass('disabled');
            $(this).find('.btn-example-down').removeClass('disabled');
        }
        // 마지막 보기
        else if(index == example_count - 1) {
            $(this).find('.btn-example-plus').css('visibility', 'visible');
            $(this).find('.btn-example-down').addClass('disabled');
        }
        // 중간보기
        else {
            $(this).find('.btn-example-down').removeClass('disabled');
            $(this).find('.btn-example-up').removeClass('disabled');
        }
    })
    // new_example.find('.btn-example-plus').css('visibility', 'hidden');
    // new_example.find('.example-type-other').css('display', 'none');
}

// 문항형식이 만족도일때 보기형식이 변하는 사건처리
function on_change_type_grade(container) {
    var type_grade_count = container.find('.question-option-2 .survey-example-count').first().val();
    var type_grade = container.find('.question-option-2 .question-type-grade').first().val();

    container.find('.example-stars-3').css('display', 'none');
    container.find('.example-stars-5').css('display', 'none');
    container.find('.example-squares-3').css('display', 'none');
    container.find('.example-squares-5').css('display', 'none');
    container.find('.example-slider-3').css('display', 'none');
    container.find('.example-slider-5').css('display', 'none');

    if (type_grade_count == 5) {
        container.find('.type-grade-inputs-3').css('display', 'none');
        container.find('.type-grade-inputs-5').css('display', 'block');

        if (type_grade == 0) {
            container.find('#no_select').css('display',"inline-block");
            container.find('.allow_unselect_2').prop('checked',false);
            container.find('.example-stars-5').css('display', 'block');
        }
        else if (type_grade == 1) {
            container.find('#no_select').css('display',"inline-block");
            container.find('.allow_unselect_2').prop('checked',false);
            container.find('.example-squares-5').css('display', 'block');
        }
        /*else {
            container.find('#no_select').css('display',"none");
            container.find('.allow_unselect_2').prop('checked',false);
            container.find('.example-slider-5').css('display', 'block');
        }*/
    }
    else {
        container.find('.type-grade-inputs-3').css('display', 'block');
        container.find('.type-grade-inputs-5').css('display', 'none');

        if (type_grade == 0) {
            container.find('#no_select').css('display',"inline-block");
            container.find('.allow_unselect_2').prop('checked',false);
            container.find('.example-stars-3').css('display', 'block');
        }
        else if (type_grade == 1) {
            container.find('#no_select').css('display',"inline-block");
            container.find('.allow_unselect_2').prop('checked',false);
            container.find('.example-squares-3').css('display', 'block');
        }
        /*else {
            container.find('#no_select').css('display',"none");
            container.find('.allow_unselect').prop('checked',false);
            container.find('.example-slider-3').css('display', 'block');
        }*/
    }
}

function on_change_question_move() {
    var move_count = 0;



    var diff = 0;
    var allow_reply_response = 0;
    var type = 0;
    var i = 0;
    var k = 0;

    var current_value = $('.survey-question-container').length + 1;

    $('.survey-question-container').each(function(question_index) {

        $(this).find('.survey-example .examples .example').each(function(exam_index) {
            var selected_value=$(this).find('.question-move').val();

            var j= 0;
            for( i = 0; i< end_check_flag; i++) {
                $(this).find('.question-move option:last').remove();
            }
            move_count =  $(this).find('.question-move option').length;
            diff = current_value - move_count;
            for ( i=0; i<Math.abs(diff); i++) {
                if (current_value > move_count) {
                    $($.sprintf('<option value="%1$d"> %1$d</option>', move_count + i )).appendTo($(this).find('.question-move'));

                }
                else if (current_value < move_count) {
                    $(this).find($.sprintf('.question-move option:nth-child(%d)', move_count - i)).remove();
                }
            }
            for( i = 0; i< end_check_flag; i++) {
                j = i+1;
                $(this).find('.question-move').append("<option value='2"+j+"'>종료 "+j+"</option>");
            }
            $(this).find('.question-move option[value=' + selected_value + ']').attr('selected', 'selected');
        });

        //  주관식에서 종료로 이동 추가
        var selected_value=$(this).find('.end-move').val();
        move_count =  $(this).find('.end-move option').length;
        var j= 0;
        for( i = 0; i< Number(end_check_flag+move_count); i++) {
            $(this).find('.end-move option:last').remove();
        }
        $(this).find('.end-move').append("<option value='0'></option>");
        for( i = 0; i< end_check_flag; i++) {
            k = i+1;
            $(this).find('.end-move').append("<option value='2"+k+"'>종료 "+k+"</option>");
        }
        $(this).find('.end-move option[value=' + selected_value + ']').attr('selected', 'selected');
    });
}
function move_questions() {
    $( '.survey-question-container' ).each(function( index ) {

        $(this).find('.survey-question-number').text(index + 1 + '번문항');
        if(index ==0) {
            $(this).find('.btn-question-up').prop("disabled",true);

        } else {
            $(this).find('.btn-question-up').removeAttr('disabled');
        }
        if(index == $( '.survey-question-container' ).length-1) {
            $(this).find('.btn-question-down').prop("disabled",true);
        } else {
            $(this).find('.btn-question-down').removeAttr('disabled');
        }
    });
}

// 문항번호 재설정
function remove_questions() {
    $( '.survey-question-container' ).each(function( index ) {

        $(this).find('.survey-question-number').text(index + 1 + '번문항');
        if(index ==0) {
            $(this).find('.btn-question-up').prop("disabled",true);

        } else {
            $(this).find('.btn-question-up').removeAttr('disabled');
        }
        if(index == $( '.survey-question-container' ).length-1) {
            $(this).find('.btn-question-down').prop("disabled",true);
        } else {
            $(this).find('.btn-question-down').removeAttr('disabled');
        }

        $(this).find('.survey-example .examples .example').each(function(exam_index) {
            var selected_value=$(this).find('.question-move').val();

            var j= 0;
            for( i = 0; i< end_check_flag; i++) {
                $(this).find('.question-move option:last').remove();
            }
            move_count =  $(this).find('.question-move option').length;

            $(this).find($.sprintf('.question-move option:nth-child(%d)', move_count)).remove();

            for( i = 0; i< end_check_flag; i++) {
                j = i+1;
                $(this).find('.question-move').append("<option value='2"+j+"'>종료 "+j+"</option>");
            }
            $(this).find('.question-move option[value=' + selected_value + ']').attr('selected', 'selected');
        });

    });
}
function on_add_example(example) {
    example.removeClass('example-sample');
    example.addClass('example');
    var current_value = Number(example.closest('.survey-group-container').find('.survey_question_count').val())+1;
    example.find('.example-title').keydown(function(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
        }
    });
    var diff = 0;
    var j= 0;
    var i = 0;
    var move_count =  example.find('.question-move option').length;
    diff = current_value - move_count;
    for ( i=0; i<Math.abs(diff); i++) {
        $($.sprintf('<option value="%1$d"> %1$d</option>', move_count + i )).appendTo(example.find('.question-move'));
    }
    for( i = 0; i< end_check_flag; i++) {
        j = i+1;
        example.find('.question-move').append("<option value='2"+j+"'>종료 "+j+"</option>");
    }
    if(example.closest("table").find('.reply_response').is(':checked'))
        example.find('.question-move-index').hide();


    // 보기의 이미지삽입 체크가 되여있는 상태에서 새로운 보기를 추가할때는 이미지선택단추를 기정으로 현시시킨다.
    if (example.closest('.question-type-0').find('.example-image-check').is(':checked')) {
        example.find('.example-image-file-container').css('display', 'inline');
    }

    // 보기 이미지 선택 사건처리
    example.find('.example-thumb').fileupload({
        url: site_url + 'survey/upload_img',
        example_container: example,
        dataType: 'json',
        done: function (e, data) {

            $.each(data.result.files, function (index, file) {
                if(file.error =="File is too big") {
                    swal({
                            title: '', text: "이미지파일크기가 200kb 까지 업로드할수있습니다.",
                            confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                        },
                        function (isConfirm) {
                        });
                } else {
                    var container = data.example_container;
                    add_example_img(container, 'survey/thumb_tmp/' + file.name);
                }

            });
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .progress-bar').css(
                'width',
                progress + '%'
            );
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');

    // 보기 이미지 삭제 사건처리
    example.find('.example-image-file-container .btn-exam-del-img').on('click', function() {
        var container = $(this).closest('.example-image-file-container');
        remove_example_img(container);
    });

    // 보기 추가 사건처리
    example.find('.btn-example-plus').on('click', function() {
        example.find('.btn-example-plus').css('visibility', 'hidden');
        var new_container = example.closest('.survey-question-container');
        var new_example = new_container.find('.example-sample').first().clone();
        new_example.appendTo(new_container.find('.examples'));
        on_add_example(new_example);
        /* var current_value = Number($('#survey_question_count').val())+1;

         var diff = 0;
         var j= 0;
         var i = 0;
         var move_count =  new_example.find('.question-move option').length;
         diff = current_value - move_count;
         for ( i=0; i<Math.abs(diff); i++) {
             $($.sprintf('<option value="%1$d"> %1$d</option>', move_count + i )).appendTo(new_example.find('.question-move'));
         }
         for( i = 0; i< end_check_flag; i++) {
             j = i+1;
             new_example.find('.question-move').append("<option value='2"+j+"'>종료 "+j+"</option>");
         }
         if(example.closest("table").find('.reply_response').is(':checked'))
             new_example.find('.question-move-index').hide();*/

    });

    // 보기 삭제 사건처리
    example.find('.btn-example-remove').on('click', function() {
        var container = example.closest('.survey-question-container');
        example.remove();
        update_examples(container);
    });

    // 보기 순서 밑으로 내려보내기 사건처리
    example.find('.btn-example-down').on('click', function() {
        var container = $(this).closest('.survey-question-container');
        var example = $(this).closest('.example');
        example.insertAfter(example.next());
        update_examples(container);
    });

    // 보기 순서 우로 올리기
    example.find('.btn-example-up').on('click', function() {
        var container = $(this).closest('.survey-question-container');
        var example = $(this).closest('.example');
        example.insertBefore(example.prev());
        update_examples(container);
    });

    update_examples(example.closest('.survey-question-container'));
}
function add_question_move_end(checked,index) {
    var move_count = 0;
    $('.survey-question-container').each(function(question_index) {
        // 객관식에서 문항이동추가
        $(this).find('.survey-example .examples .example').each(function(exam_index) {
            move_count =  $(this).find('.question-move option').length;
            if(checked) {
                $(this).find('.question-move').append("<option value='2"+index+"'>종료 "+index+"</option>");
            } else {
                $(this).find('.question-move option:last').remove();
            }

        });
        //  주관식에 종료이동 추가
        if(checked) {
            $(this).find('.end-move').append("<option value='2"+index+"'>종료 "+index+"</option>");
        } else {
            $(this).find('.end-move option:last').remove();
        }
    });
}

var g_question_container;
var g_question_groups = [];

// 문항 불러오기
function on_import_question() {
    var id = $('input[name="public_check"]:checked').val();
    var newflag = $('input[name="public_check"]:checked').attr('role');
    if(id !=undefined && id > 0) {
        location.href = site_url + 'diagnosis/view?survey_id=' + id+'&newflag=1&attached='+newflag;
    }
}

function preview() {
    var valid = true;
    current_page_index = 0;

    preview_attached = $('#survey_attached').val();

    var attached_file_name = $('#attached_file_name').val();

    var attached_check = $('#attached_check').val();

    if (preview_attached == 1 && attached_file_name == '') {
        valid = false;
        swal({title: '', text: '첨부하시려는 문서를 선택하지 않았습니다.',
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {});
        return;
    }
    if (preview_attached == 1 && attached_file_name != '' && attached_check !=1) {
        valid = false;
        swal({title: '', text: '포함하기단추를 눌러주십시요',
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {});
        return;
    }
    var survey_title = $('#survey_title').val();

    if (survey_title == '') {
        valid = false;
        swal({title: '', text: '진단제목을 입력하십시요.',
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {
                $('#survey_title').focus();
            });
        return;
    }
    var review_infor =  $("input[name='review_infor']:checked").val();

    preview_auth = $("input[name='survey_auth']:checked").val();

    question_count = 0;
    $('.survey-group-container').each(function(index) {
        question_count += Number($(this).find('.survey_question_count').val());
    });

    question_count_page = $('#survey_question_count_page').val();
    var html = new Array();
    var htmldata = "";
    htmldata +='<div id="content" style="margin:0;padding: 15px 30px;font-size: 22px;">';
    htmldata +='<div class="sub-content">';
    htmldata +='<div class="sub-title"><img src="/images/icon_title.png">진단미리보기</div>';

    htmldata +='</div>';
    htmldata +='</div>';
    htmldata +='<div id="content" style="margin-top:0px;">';
    if(preview_auth ==1) {  // 본인인증 사용이면
        htmldata +='<div class="popPreview" >';
        htmldata +='<div id="survey_header" class ="survey_header">';
        htmldata +='<span>본인 인증</span>';
        htmldata +='</div>';
        htmldata +=' <div id="survey_body" class="survey_body" style="margin-bottom: 110px;">';
        htmldata +='<div class="survey_title">';
        htmldata +='<p>전화번호뒤 4자리를 넣어주시기 바랍니다.</p>';
        htmldata +='</div>';
        htmldata +='<div class="survey_body">';
        htmldata +='<div class="auth_input">';
        htmldata +=' <input type="password" value="" id="auth_adress" minlength="4">';
        htmldata +='</div>';
        htmldata +=' <button class="btn btn_auth_ok" value="ok" onclick="auth_confirm();">확인</button>';
        htmldata +='</div>';
        htmldata +='</div>';
        htmldata +='</div>';
    }
    if(preview_attached == 1) {  //문서포함이면
        htmldata +='<div class="attached_area">';
        htmldata +='<div id="attached_content" style="height:100%;">';
        //포함된 문서내용을 불러오기
        htmldata +=$("#survey_attachedHTML").val();
        htmldata +='<div id="areaForPDF"></div>';
        htmldata +='</div>';
        htmldata +='<div style=" text-align: center;" id = "enterSurvey">';
        htmldata +='<button onclick="invite();" class="btn btn-primary" style=" letter-spacing: 8px;width: 130px;margin-right: 10px;margin-bottom:20px">진단참여</button>';
        htmldata +='</div>';
        htmldata +='<script>';
        htmldata +='$(function () {';
        htmldata +="if ($('#page-container') != null ) {";
        htmldata +='$("#sidebar").remove();';
        htmldata +="$('#page-container').css({'background-color' : '', 'background-image' : ''});";
        htmldata +='$("#enterSurvey").appendTo($("#page-container"));';
        htmldata +='$("#areaForPDF").html($("#page-container").html());';
        htmldata +='$("#page-container").remove();';
        htmldata +='}';
        htmldata +='});';
        htmldata +='</script>';
        htmldata +='</div>';
    }
    htmldata +=' <div id="preview-content" style="    margin-bottom: 10px;">';
    htmldata +='<div class="preview-header">';
    htmldata +='<div class="survey-title-scope" id="survey-title-scope">';
    htmldata +='<p>'+survey_title+'</p>';
    htmldata +='</div>';

    htmldata +='</div>';
    htmldata +='<div class="survey-comment-scope">';
    if(review_infor =="0") {
        htmldata +='<p>본 진단은 익명이 보장됩니다.</p>';
    } else if(review_infor =="1") {
        htmldata +='<input type="text" id="review_year" value="" size="5" style="margin-top: 10px;text-align: center;border: 2px solid;"><label style="    font-weight: 900; margin-right: 30px;margin-left: 5px;">학년</label> <input type="text" id="review_half" value="" size="5" style="    text-align: center;border: 2px solid;"><label style="    font-weight: 900;margin-right: 30px;margin-left: 5px;">반</label> <label style="font-weight: 900;margin-right: 5px;">이름</label><input type="text" id="review_name" value="" size="10" style="text-align: center;border: 2px solid;">';
    }else if(review_infor =="2") {
        htmldata +='<input type="text" id="review_half" value="" size="5" style="margin-top: 10px;text-align: center;border: 2px solid;"><label style="    font-weight: 900;margin-right: 30px;margin-left: 5px;">소속</label> <label style="font-weight: 900;margin-right: 5px;">이름</label><input type="text" id="review_name" value="" size="10" style="text-align: center;border: 2px solid;">';
    }else {
        htmldata +='<label style="font-weight: 900;margin-right: 5px;margin-top: 10px;">이름</label><input type="text" id="review_name" value="" size="10" style="text-align: center;border: 2px solid;">';
    }

    htmldata +='</div>';

    //그룹
    // htmldata +='<div class="group-scope">';

    htmldata +=' <div class="question-content">';
    htmldata +='<div class="question-scope" id="question-scope">';

    let question_number = 0;
    let question_index = 0;

    $('.survey-group-container').each(function(group_index) {

        var group_title = $('.survey-group-container').find(".group-title").val();
        if(group_title == ""){
            valid = false;
            swal({
                    title: '', text: $.sprintf('진단그룹%d의 제목을 입력하십시오.', group_index + 1),
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'
                },
                function (isConfirm) {
                });
            return;
        }

        //  문항
        var i = 0;
        var questions = [];

        $(this).find('.survey-question-container').each(function () {

            if (!valid)
                return;
            var question = {};
            var condition_text = "";
            var type = $(this).find('.survey-question .btn-warning').attr('question-type');
            var question = $(this).find('.survey-question-title').val();
            var question_img_url = $(this).find('.question-thumbnail-file-name').val();
            var allow_unselect = $(this).find('.allow_unselect').is(':checked') ? 1 : 0;
            var allow_random_align = $(this).find('.example-image-check').is(':checked') ? 1 : 0;
            var type_grade = $(this).find('.question-type-grade').val();
            var allow_reply_response = $(this).find('.reply_response').is(':checked') ? 1 : 0;

            if (type == 3) { //강사만족도이면
                //평가지표검사
                var closet_exam_kind_tr = $(this).closest('.survey-question-container').find('.exam-kind-tr');

                closet_exam_kind_tr.each(function (index) {

                    var exam_kind_title = $(this).find('.exam-kind-title').val();
                    if (exam_kind_title == "") {
                        valid = false;
                        swal({
                                title: '', text: $.sprintf('그룹%d - %d번문항에서 평가지표%d의 제목을 입력하십시오.', group_index + 1, question_index + 1, index + 1),
                                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'
                            },
                            function (isConfirm) {
                            });
                        return;
                    }

                    var exam_kind_content = $(this).find('.exam-kind-content').val();
                    if (exam_kind_content == "") {
                        valid = false;
                        swal({
                                title: '', text: $.sprintf('그룹%d - %d번문항에서 평가지표%d의 내용을 입력하십시오.', group_index + 1, question_index + 1, index + 1),
                                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'
                            },
                            function (isConfirm) {
                            });
                        return;
                    }

                });
                //평가대상검사
                var closet_exam_object_tr = $(this).closest('.survey-question-container').find('.exam-object-tr');

                closet_exam_object_tr.each(function (index) {

                    var exam_object_title = $(this).find('.exam-object-title').val();
                    if (exam_object_title == "") {
                        valid = false;
                        swal({
                                title: '', text: $.sprintf('그룹%d - %d번문항에서 평가대상%d의 제목을 입력하십시오.', group_index + 1, question_index + 1, index + 1),
                                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'
                            },
                            function (isConfirm) {
                            });
                        return;
                    }
                });
            } else if (type != 3 && question == '') {
                valid = false;
                swal({
                        title: '', text: $.sprintf('그룹%d - %d번문항의 질문을 입력하십시오.', group_index + 1, question_index + 1),
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'
                    },
                    function (isConfirm) {
                    });
                return;
            }

            htmldata += '<div class="question-index" style="display: none">';

            if(question_index == 0)
                htmldata += ' <div style = "font-size: 25px; font-weight: bold; margin:15px 0 15px 30px;"> [' + group_title + '] </div>';

            if (allow_reply_response == 1) {
                condition_text += "(복수선택가능)";
            }
            var allow_unselect = 0;
            if (type == 0) { //객관식
                allow_unselect = $(this).find('.allow_unselect').is(':checked') ? 1 : 0;
                if (allow_unselect == 1) {
                    condition_text += "(미선택가능)";
                }
            } else if (type == 1) {
                allow_unselect = $(this).find('.allow_unselect_1').is(':checked') ? 1 : 0;
                if (allow_unselect == 1) {
                    condition_text += "(미선택가능)";
                }
            } else if (type == 2){
                allow_unselect = $(this).find('.allow_unselect_2').is(':checked') ? 1 : 0;
                if (allow_unselect == 1) {
                    condition_text += "(미선택가능)";
                }
            } else if (type == 3){
                allow_unselect = $(this).find('.allow_unselect_3').is(':checked') ? 1 : 0;
                if (allow_unselect == 1) {
                    condition_text += "(미선택가능)";
                }
            }

            if (allow_random_align == 1) {
                condition_text += "(보기순서 임의배열)";
            }

            question_number ++;
            htmldata += ' <h4 style="word-wrap: break-word;">' + Number(question_number) + '. ' + question + '<br><span style="font-size:28px;color: #ff0000;">&nbsp;&nbsp;&nbsp;' + condition_text + '</span></h4>';

            if (question_img_url != '') {

                htmldata += '<img src="/' + question_img_url + '" alt=" " class="img-responsive zoom-img" style="    width: 540px;">';
            }
            htmldata += '<input type="hidden" id="allow_unselect" value="' + allow_unselect + '">';
            htmldata += '<input type="hidden" id="type" value="' + type + '">';
            htmldata += '<input type="hidden" id="type_grade" value="' + type_grade + '">';

            $example_index = 0;

            if (type == 0) { //객관식

                var example_has_image = $(this).find('.example-image-check').is(':checked') ? 1 : 0;
                if (allow_reply_response == 1) {   //증복응답허용일때

                    $(this).find('.survey-example .examples .example').each(function (exam_index) {
                        if (!valid)
                            return;
                        //  질문제목
                        var title = $(this).find('.example-title').val();
                        if (title == '') {
                            valid = false;
                            swal({
                                    title: '', text: $.sprintf('그룹%d - %d문항의 %d번 내용을 입력하십시오.', group_index + 1, question_index + 1, exam_index + 1),
                                    confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'
                                },
                                function (isConfirm) {
                                });
                            valid = false;
                            return;
                        }

                        htmldata += '<label>' + (Number(exam_index) + 1) + ') <input type="checkbox" name="question1_' + question_index + '" value="' + title + '">' + title + '</label>';

                        //  질문이메지
                        if (example_has_image == 1) {
                            var img_url = $(this).find('.example-thumbnail-file-name').val();
                            if (img_url == '') {
                                valid = false;
                                swal({
                                        title: '',
                                        text: $.sprintf('그룹%d - %d문항의 %d번 그림을 선택하십시오.', group_index + 1, question_index + 1, exam_index + 1),
                                        confirmButtonText: '확인',
                                        allowOutsideClick: false,
                                        type: 'warning'
                                    },
                                    function (isConfirm) {
                                    });

                                return;
                            }
                            htmldata += '<img src="/' + img_url + '" style="    width: 540px;">';
                        }

                    });
                } else {
                    var exam_total_count = 0;
                    $(this).find('.survey-example .examples .example').each(function (exam_index) {
                        if (!valid)
                            return;
                        //  질문제목
                        var title = $(this).find('.example-title').val();
                        var move_value = $(this).find('.question-move').val();
                        if (title == '') {
                            valid = false;
                            swal({
                                    title: '', text: $.sprintf('그룹%d - %d문항의 %d번 내용을 입력하십시오.', group_index + 1, question_index + 1, exam_index + 1),
                                    confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'
                                },
                                function (isConfirm) {
                                });
                            valid = false;
                            return;
                        }
                        htmldata += '<label>' + (Number(exam_index) + 1) + ') <input type="radio" name="question1_' + question_index + '" role="' + move_value + '" value="' + title + '" isChecked="false">' + title + '</label>';

                        //  질문이메지
                        if (example_has_image == 1) {
                            var img_url = $(this).find('.example-thumbnail-file-name').val();
                            if (img_url == '') {
                                valid = false;
                                swal({
                                        title: '',
                                        text: $.sprintf('그룹%d - %d문항의 %d번 그림을 선택하십시오.', group_index + 1, question_index + 1, exam_index + 1),
                                        confirmButtonText: '확인',
                                        allowOutsideClick: false,
                                        type: 'warning'
                                    },
                                    function (isConfirm) {
                                    });

                                return;
                            }
                            htmldata += '<img src="/' + img_url + '" style="    width: 540px;">';
                        }
                        htmldata += ' <input type="hidden" name="question_move' + question_index + '" value="' + move_value + '" class="qestion_move"></label>';
                        exam_total_count = Number(exam_total_count) + 1;
                    });
                    var use_other_input = $(this).find('.use_other_input').is(':checked') ? 1 : 0;
                    if (use_other_input == 1) {
                        exam_total_count = Number(exam_total_count) + 1;
                        htmldata += '<label>' + exam_total_count + ') <input type="radio" id="other_input_' + question_index + '" name="question1_' + question_index + '" value="" isChecked="false">기타 <input type="text" name="question_other_' + question_index + '" value="" onkeyup="set_radio_value(' + question_index + ')"onclick="other_input_check(' + question_index + ')" class="example-input-text-1"></label>';

                    }
                }
            }
            else if (type == 1) {  //  주관식
                var move_value = $(this).find('.end-move').val();
                var example_index
                var example_count = $(this).find('#survey-example-count1').val();
                htmldata += '<input type="hidden" id="example_count" value="' + example_count + '">';
                for (i = 0; i < Number(example_count); i++) {
                    example_index = i + 1;
                    htmldata += '<label>' + example_index + ') <input type="text" name="question2_' + i + '" value="" class="example-input-text"></label>';
                }
                htmldata += '<input type="hidden" name="end_comment_index_' + question_index + '" value="' + move_value + '">';

            }
            else if (type == 2) {  //  만족도
                var example_count = $(this).find('#survey-example-count2').val();
                htmldata += '<form>';
                htmldata += '<fieldset class="starRating_' + question_index + '" style = "heigth:30px;width:100%;overflow: hidden">';
                var fav_grades = ['매우 만족', '만족', '보통', '불만족', '매우 불만족'];
                var type_grade = $(this).find('.question-type-grade').val();

                var star_section = "";
                var star_header = '<table class="table-star"><thead class="table-head"><tr>';
                var width_rate = 0;
                if (example_count == 3) {
                    width_rate = 33;
                } else {
                    width_rate = 20;
                }
                if (type_grade == 0) { //별형
                    var itemWidth = 100 / Number(example_count);
                    var j = Number(example_count);
                    for (i = Number(example_count); i > 0; i--) {
                        star_header += '<td class="matrix-col-label" style="width:' + width_rate + '%">' + fav_grades[i - 1] + '</td>';

                        star_section += '<input class="star-input" id="' + question_index + '_rating' + i + '" type="radio" name="rating" value="' + fav_grades[j - i] + '">';
                        star_section += '<label class="star-label" for="' + question_index + '_rating' + i + '" style = "width:' + itemWidth + '%"><i class="fas fa-star example-fav"></i></label>';

                    }
                    star_header += '</tr></thead></table>';
                    htmldata += star_header;
                    htmldata += star_section;
                } else if (type_grade == 1) {  //막대기형
                    var itemWidth = 100 / Number(example_count);
                    var j = Number(example_count);
                    for (i = Number(example_count); i > 0; i--) {
                        star_header += '<td class="matrix-col-label" style="width:' + width_rate + '%">' + fav_grades[i - 1] + '</td>';

                        star_section += '<input class="star-input" id="' + question_index + '_rating' + i + '" type="radio" name="rating" value="' + fav_grades[j - i] + '">';
                        star_section += '<label class="star-label" for="' + question_index + '_rating' + i + '" style = "width:' + itemWidth + '%"><i class="fas fa-window-minimize example-fav"></i></label>';

                    }
                    star_header += '</tr></thead></table>';
                    htmldata += star_header;
                    htmldata += star_section;
                }
                /*else {
                                   htmldata +='<input class="preview-slider_count" type="hidden" value="'+example_count+'">';
                                   for (i =Number(example_count); i > 0; i--) {
                                       star_header += '<td class="matrix-col-label" style="width:'+ width_rate+'%">'+fav_grades[i - 1]+'</td>';

                                   }
                                   star_header+='</tr></thead></table>';
                                   htmldata +=star_header;
                                   htmldata +='<div class="preview-slider">';
                                   htmldata +='<div class="pre-slider-container">';
                                   htmldata +='<div class="preview-fav-slider_'+question_index+' m-nouislider m-nouislider--handle-danger"></div>';
                                   htmldata +='</div>';
                                   htmldata +='</div>';

                               }*/
                htmldata += ' </fieldset>';
                htmldata += '</form>';
            }
            else if (type == 3) {  //  강사만족도
                var example_count = $(this).find('#survey-example-count2').val();
                //평가지표테블
                htmldata += "<table style='border-collapse: collapse;border: 1px solid rgb(167, 167, 167);width: 100%;table-layout: fixed; '>";

                $(this).find(".exam-kind-tr").each(function(exam_kind_index){
                    htmldata += "<tr style='font-size: 14px;text-align: center;border: 1px solid #b3b2b2'>";

                    htmldata += "<td style='width: 40%;background: #eaeaea;font-weight: bold;padding:8px;word-wrap:break-word;'>";
                    htmldata += $(this).find('.exam-kind-title').val();
                    htmldata += "</td>";

                    htmldata += "<td style = 'padding:8px;width:60%;word-wrap:break-word;'>";
                    htmldata += $(this).find('.exam-kind-content').val();
                    htmldata += "</td>";

                    htmldata += "</tr>";

                });
                htmldata += "</table>";

                //평가대상별 만족도구현

                $(this).find(".exam-object-tr").each(function(exam_object_index) {
                    htmldata += "<div style='font-size: 14px;margin: 10px 0;padding: 7px;background: #eaeaea;'>< "
                        + $(this).find('.exam-object-title').val() + " ></div>";

                    $(this).closest('.survey-question-container').find('.exam-kind-tr').each(function(exam_kind_index) {
                        htmldata += "<div style = 'font-size: 14px;margin-bottom: 5px;margin-left: 10px;'>- " + $(this).find('.exam-kind-title').val() + "</div>";

                        htmldata += '<form>';
                        htmldata += '<fieldset class="starRating_' + question_index + '" style = "heigth:30px;width:100%;overflow: hidden">';
                        var fav_grades = ['매우 만족', '만족', '보통', '불만족', '매우 불만족'];
                        var type_grade = $(this).closest('.survey-question-container').find('.question-type-grade').val();

                        var star_section = "";
                        var star_header = '<table class="table-star" style = "margin-bottom: 0"><thead class="table-head"><tr>';
                        var width_rate = 0;
                        if (example_count == 3) {
                            width_rate = 33;
                        } else {
                            width_rate = 20;
                        }
                        if (type_grade == 0) { //별형
                            var itemWidth = 100 / Number(example_count);
                            var j = Number(example_count);
                            for (i = Number(example_count); i > 0; i--) {
                                star_header += '<td class="matrix-col-label" style="width:' + width_rate + '%">' + fav_grades[i - 1] + '</td>';

                                star_section += '<input class="star-input" id="' + question_index +"_" + exam_object_index + "_" + exam_kind_index + '_rating' + i + '" type="radio" name="rating_"' + exam_object_index + "_" + exam_kind_index + ' value="' + fav_grades[j - i] + '">';
                                star_section += '<label class="star-label" for="' + question_index +"_" + exam_object_index + "_" + exam_kind_index + '_rating' + i + '" style = "width:' + itemWidth + '%"><i class="fas fa-star example-fav"></i></label>';

                            }
                            star_header += '</tr></thead></table>';
                            htmldata += star_header;
                            htmldata += star_section;
                        } else if (type_grade == 1) {  //막대기형
                            var itemWidth = 100 / Number(example_count);
                            var j = Number(example_count);
                            for (i = Number(example_count); i > 0; i--) {
                                star_header += '<td class="matrix-col-label" style="width:' + width_rate + '%">' + fav_grades[i - 1] + '</td>';

                                star_section += '<input class="star-input" id="' + question_index +"_" + exam_object_index + "_" + exam_kind_index + '_rating' + i + '" type="radio" name="rating_"' + exam_object_index + "_" + exam_kind_index + 'value="' + fav_grades[j - i] + '">';
                                star_section += '<label class="star-label" for="' + question_index +"_" + exam_object_index + "_" + exam_kind_index + '_rating' + i + '" style = "width:' + itemWidth + '%"><i class="fas fa-window-minimize example-fav"></i></label>';

                            }
                            star_header += '</tr></thead></table>';
                            htmldata += star_header;
                            htmldata += star_section;
                        }

                        htmldata += ' </fieldset>';
                        htmldata += '</form>';
                    });
                });
            }

            htmldata +='</div>';
            question_index ++;
        });
    });

    htmldata +='</div>';
    htmldata +='</div>';

    htmldata +=' <div style="text-align: center; margin-top: 19px; display: none" id ="button_close">';
    htmldata +='<button onclick="previewClose_check();" class="btn_auth_ok btn " style="letter-spacing: 8px;margin-bottom:20px">돌아가기</button>';
    htmldata +='</div>';
    htmldata +='<div style="text-align: center; margin-top: 19px; display: none" id="button_next">';
    htmldata +='<button onclick="next();" class="btn_auth_ok btn " style=" letter-spacing: 8px;margin-right: 10px;margin-bottom:20px">다음</button>';
    htmldata +='</div>';
    htmldata +='</div>';
    htmldata +='</div>';
    htmldata +='</div>';

    if(valid) {
        $('#previewArea').html(htmldata);
        $('#documentArea').hide();
        // $('.header').hide();
        $('#previewArea').show();
        $('.attached_area').hide();
        previewload();
    }

}

function previewload() {
    if(preview_auth ==1) {
        $('#preview-content').hide();
    } else if(preview_attached == 1) {
        $('.attached_area').show();
        $('#preview-content').hide();
    }

    /*  var new_container = $( '.preview-slider').first().clone();
      var count = $('#preview-slider_count').val();*/

    $('.question-index').each(function(question_index) {
        var count =$(this).find('.preview-slider_count').val();
        if(count ==5) {
            noUiSlider.create($(this).find('.preview-fav-slider_'+question_index).first()[0], {
                start: [ 2 ],
                step: 1,
                connect: [true, false],
                range: {
                    'min': [ 0 ],
                    'max': [ 4 ]
                }
            });
        } else if(count==3) {
            noUiSlider.create($(this).find('.preview-fav-slider_'+question_index).first()[0], {
                start: [ 1 ],
                step: 1,
                connect: [true, false],
                range: {
                    'min': [ 0 ],
                    'max': [ 2 ]
                }
            });
        }
    });


    $('.question-index').each(function (index) {
        if(question_count_page > index) {
            $(this).show();
            current_page_index++;
        }
    });

    if(current_page_index==question_count){

        $('#button_close').show();
        $('#button_next').hide();
    } else {
        $('#button_close').hide();
        $('#button_next').show();
    }
}
function invite() {
    $('.attached_area').hide();
    $('#preview-content').show();
}
function next() {
    var pre_index=0;
    var next_index = 0;
    var move_index = 100;
    var flag = 0;
    var type_1 = 0;
    var unselect_flag = 0;
    next_index = Number(current_page_index) + Number(question_count_page);
    pre_index = current_page_index-1;
    var n = 0;
    $('.question-index').each(function (index) {
        if (Number(current_page_index) - Number(question_count_page) <= index && Number(current_page_index)-1 >= index) {
            if ($(this).find('#allow_unselect').val() == "0") {

                if ($(this).find('#type').val() == "0") {
                    if ($(this).find('input[name="question1_' + index + '"]:checked').val() === undefined || $(this).find('input[name="question1_' + index + '"]:checked').val() === null || $(this).find('input[name="question1_' + index + '"]:checked').val() === "") {
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
                } else if($(this).find('#type').val() == "2") {

                    if ($(this).find('#type_grade').val() == "2") {

                    } else {
                        if ($(this).find('input[name="rating"]:checked').val() === undefined) {
                            alert("본 질문에 응답하셔야 다음 질문으로 넘어갈수 있습니다.");
                            unselect_flag = 1;
                            flag = 2;
                            return false;
                        }
                    }
                } else{
                    if ($(this).find('#type_grade').val() == "2") {

                    } else {
                        // if ($(this).find('input[name*="rating_"]:checked').val() === undefined) {
                        //     alert("본 질문에 응답하셔야 다음 질문으로 넘어갈수 있습니다.");
                        //     unselect_flag = 1;
                        //     flag = 2;
                        //     return false;
                        // }
                    }
                }
            } else {
                flag = 1;
            }
        }
        if(unselect_flag == 0){
            if (Number(current_page_index) - Number(question_count_page) <= index) {
                move_index = $(this).find('input[name="question1_' + index + '"]:checked').attr('role');
                if (move_index == "" || move_index == undefined) {
                    type_1 = $(this).find('input[name="end_comment_index_' + index+ '"]').val();
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
        $('#button_close').show();

        $('#button_next').hide();
    } else {

        $('#button_close').hide();
        $('#button_next').show();
    }
}
function auth_confirm() {
    if(preview_attached == 1) {
        $('.attached_area').show();

        $('#preview-content').hide();
        $('.popPreview').hide();
    } else {

        $('.attached_area').hide();

        $('#preview-content').show();
        $('.popPreview').hide();
    }
}

function previewClose_check(){
    var unselect_flag = 0;
    var n = 0;
    var flag = 0;
    var type_1 =0;
    var move_index=0;
    $('.question-index').each(function (index) {
        if (Number(current_page_index) - Number(question_count_page) <= index && Number(current_page_index)-1 >= index) {
            if ($(this).find('#allow_unselect').val() == "0") {

                if ($(this).find('#type').val() == "0") {
                    if ($(this).find('input[name="question1_' + index + '"]:checked').val() === undefined || $(this).find('input[name="question1_' + index + '"]:checked').val() === null || $(this).find('input[name="question1_' + index + '"]:checked').val() === "") {
                        alert("본 질문에 응답하셔야 다음 질문으로 넘어갈수 있습니다.");
                        unselect_flag = 1;
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

                        return false;
                    }

                } else {

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
        if(unselect_flag == 0){
            if (Number(current_page_index) - Number(question_count_page) <= index) {
                move_index = $(this).find('input[name="question1_' + index + '"]:checked').attr('role');
                if (move_index == "" || move_index == undefined) {
                    type_1 = $(this).find('input[name="end_comment_index_' + index+ '"]').val();
                    if (type_1 == "" || type_1 == undefined || type_1 =="0") {
                        flag = 1;
                    } else {

                        flag = 2;
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
    if( $('#end_comment_'+real_index).val() !="" && $('#end_comment_'+real_index).val()!=undefined) {
        alert( $('#end_comment_'+real_index).val());
    } else {
        alert('진단에 참가하여주셔서 감사합니다1.');
    }

    $('#previewArea').html('');
    $('#documentArea').show();
    $('#previewArea').hide();


    // $('.header').show();
}

//첨부파일 <포함하기>
function onImportClick() {

    var message_type = $('#message_type').val();
    if($('#attached_check').val() == 1)
    {
        swal({title: '', text: '이미 포함되여있습니다',
            confirmButtonText: '확인', allowOutsideClick: false, type: 'warning' }, function (isConfirm) {});
        return;
    }

    $.ajax({
        url: site_url + 'diagnosis/convertToHTML',
        type: 'POST',
        data: {
            file_name: $('#attached_file_name').val(),
        },
        error: function () {
            swal({title: '', text: '문서변환이 실패하였습니다!',
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                function(isConfirm) {});
            attachedHtml = 0;
            $('#attached_check').val("0");
        },
        success: function (ConvertedHtmlUrl) {
            $converted_file_url = "http://" + location.hostname + "/" + ConvertedHtmlUrl;
            $('#attached_file_name').val(ConvertedHtmlUrl);
            swal({title: '', text: '문서가 포함되었습니다',
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'success'},
                function(isConfirm) {});
            $('#preview').removeAttr('disabled');
            $('#attached_check').val("1");
            $('#attached_content').html('');
            $('#attached_content').html("<iframe src='" + $converted_file_url + "' " + "style='width:100%;height:100%'></iframe>");

            $.ajax({
                url: site_url + 'diagnosis/getHTML',
                type: 'POST',
                data: {
                    file_url: $converted_file_url,
                },
                error: function () {
                    swal({title: '', text: '문서변환이 실패하였습니다!',
                            confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                        function(isConfirm) {});
                    attachedHtml = 0;
                    $('#attached_check').val("0");
                },
                success: function (ConvertedHtmlUrl) {
                    $("#survey_attachedHTML").val(ConvertedHtmlUrl);
                }
            });


        }
    });

}
function onShowClick() {
    //페지방식인 경우
    if($('#attached_file_name').val() == ''){
        swal({title: '', text: '첨부파일이 선택되지 않았습니다',
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {});
        return;
    }
    if($('#attached_check').val() == 0)
    {
        swal({title: '', text: '포함하기단추를 누르십시오',
            confirmButtonText: '확인', allowOutsideClick: false, type: 'warning' }, function (isConfirm) {});
        return;
    }


    $('#documentArea').hide();
    $('#attachedHTMLArea').show();
}
function onBack(){
    $('#documentArea').show();
    $('#attachedHTMLArea').hide();
}
function set_visible_question_move(question,flag) {
    question.find('.survey-example .examples .example').each(function(exam_index) {
        if(flag ==1 ) {
            $(this).find('.question-move-index').hide();
            $(this).find('.question-move ').val([]);
        } else {
            $(this).find('.question-move-index').show();
        }

    });
}
function change_page_per_count(){
    page_per_count = $('#page_per_count option:selected').val();
    getSurveyList(0);
}
function go_page(page){

    current_page =page;
    getSurveyList(1);

}

function addpageEventlisner() {

    total_count = $('#my_item_total_count').val();
    if(total_count != undefined && total_count != 0) {
        $('.blog-pagination').html( Paging(total_count,page_per_count,current_page));


    } else {
        $('.blog-pagination').html('');
    }
    if(Number(total_count) > 0){
        $('.serv_t').text('총 갯수 '+total_count+' 개');
        $('.serv_t').css('color','#000');
    }else {
        $('.serv_t').text('등록된 진단이 없습니다.');//phonenumberCont
        $('.serv_t').css('color','#e02222');
    }

}

function getSurveyList(flag){
    if(flag == 0) {
        current_page = 1;
    }

    $('#survey_list').empty();
    $.ajax({
        url: site_url + 'diagnosis/get_my_surveys_list',
        cache:false,
        timeout : 10000,
        dataType:'html',
        type: 'POST',
        data: {

            page: current_page,
            page_per_count: page_per_count,
            view_flag: 3,
        },
        success: function(data) {
            if (data !== 'err') {
                $('#survey_list').html(data);
                addpageEventlisner();
                $('#questions_modal').modal('show');

            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            swal({title: '', text: xhr.status,
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                function(isConfirm) {});
        }
    });

}

function setData() {
    var surveyData= [];
    var questionData = [];
    var end_comments =[];

    $.post(site_url + 'diagnosis/get_surveyById',
        {
            survey_id: survey_id
        },
        function(data, status){
            var obj = jQuery.parseJSON(data);
            if (obj.status == 'OK') {
                surveyData = obj.survey_data['surveys'];
                if(surveyData.length>0) {
                    //제목설정
                    if( surveyData[0]['file_url'] !="" && surveyData[0]['file_url']!=undefined){
                        $('#survey_attached').val(1);
                        var file_name = surveyData[0]['file_url'].split("/");
                        $('#attached_content').html('');
                        $('#attached_content').html("<iframe src='" +surveyData[0]['file_url']+ "' " + "style='width:100%;height:100%'></iframe>");


                        $('#attached_file_name').val('uploads/html/'+file_name[5]);
                        $('#import').removeAttr('disabled');
                        $('#preview').removeAttr('disabled');
                        $('#attached_check').val(1);
                        $('#uploader_filelist').html("");
                        $('#uploader_filelist').append('<div class="alert alert-warning added-files" id="uploaded_file">' + file_name[5] + ' <span class="status label label-info"></span></div>');
                        $.ajax({
                            url: site_url + 'diagnosis/getHTML',
                            type: 'POST',
                            data: {
                                file_url: surveyData[0]['file_url'],
                            },
                            error: function () {
                                swal({title: '', text: '문서변환이 실패하였습니다!',
                                        confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                                    function(isConfirm) {});
                                attachedHtml = 0;
                                $('#attached_check').val("0");
                            },
                            success: function (ConvertedHtmlUrl) {
                                $("#survey_attachedHTML").val(ConvertedHtmlUrl);
                            }
                        });
                    }
                    $('#survey_title').val(surveyData[0]['title']);

                    //진단방식설정
                    var diag_type = surveyData[0]['diag_type'];
                    diagtype_radio_check(diag_type);

                    //진단기간설정
                    var start_time = surveyData[0]['start_time'];
                    var end_time = surveyData[0]['end_time'];
                    $('#survey_start_date').val(start_time.substr(0, 10));
                    $('#survey_end_date').val(end_time.substr(0, 10));

                    //진단교육과정명 / 교육차수 설정
                    //var schedule_name = surveyData[0]['schedule_name'];
                    //var schedule_count = surveyData[0]['schedule_count'];
                    //$('#schedule_name').val(schedule_name);
                    //$('#schedule_count').val(schedule_count);

                    //완료조건설정
                    $('input:radio[name=survey_end_condition]:input[value=' + surveyData[0]['end_condition'] + ']').attr("checked", true);
                    $('#survey_end_count').val(surveyData[0]['end_count']);
                    //본인인증설정
                    $('input:radio[name=survey_auth]:input[value=' + surveyData[0]['auth'] + ']').attr("checked", true);
                    // 응답자정보
                    $('input:radio[name=review_infor]:input[value=' + surveyData[0]['review_infor'] + ']').attr("checked", true);

                    //페지당 문항개수설정
                    $('#survey_question_count_page option[value=' + surveyData[0]['question_count_page'] + ']').attr('selected', 'selected');

                    end_comments = obj.survey_data['end_comments'];
                    var index = 0;
                    // 종료문 입력
                    for(comment_index in end_comments) {
                        index +=1;
                        $('input:checkbox[name=end_check_'+index+']').prop("checked",true);
                        $('#end_comment_'+end_check_flag).val(end_comments[comment_index]['content']);
                        $('#end_comment_'+end_check_flag).removeAttr('readonly');
                        end_check_flag +=1;

                    }
                    if(index > 0) {
                        end_check_flag = index;
                    }

                    //그룹개수설정
                    $('#survey_group_count option[value=' + surveyData[0]['question_group_count'] + ']').attr('selected', 'selected');

                    //그룹개수변경처리(새로운 그룹/문항생성처리)
                    on_change_group_count();

                    g_question_groups = obj.survey_data['question_groups'];

                    //------- 그룹순환 --------
                    $('.survey-group-container').each(function (group_index) {
                        // 그룹제목입력
                        $(this).find('.group-title').val(g_question_groups[group_index]['title']);
                        // 그룹안의 문항개수입력
                        $(this).find('.survey_question_count option[value=' + g_question_groups[group_index]['question_count'] + ']').attr('selected', 'selected');
                        $(this).find('.survey_question_count').trigger("change");

                        var g_questions = g_question_groups[group_index]['questions'];

                        //------- 문항순환 -------
                        $(this).find('.survey-question-container').each(function (question_index) {

                            g_question_container = $(this);

                            var examples = g_questions[question_index]['examples'];
                            // 문항형태선택
                            change_question_type(g_question_container, g_questions[question_index]['type']);
                            // 문항질문입력
                            $(this).find('.survey-question-title').val(g_questions[question_index]['question']);

                            // 문항질문그림 삽입 혹은 삭제
                            if (g_questions[question_index]['question_img_url'] != '') {
                                add_question_img(g_question_container, 'diagnosis/thumb/' + g_questions[question_index]['question_img_url']);
                            }
                            else {
                                remove_question_img(g_question_container);
                            }

                            // 문항형태가 객관식일때
                            if (g_questions[question_index]['type'] == 0) {

                                if (g_questions[question_index]['example_has_image'] == 1) {
                                    g_question_container.find('.example-image-check').prop('checked', true);
                                    g_question_container.find('.example-image-check').trigger("change");
                                }

                                g_question_container.find('.examples .example').remove();

                                for (example_idx in examples) {
                                    var new_example = g_question_container.find('.example-sample').first().clone();
                                    new_example.appendTo(g_question_container.find('.examples'));
                                    on_add_example(new_example);
                                    add_example_img(new_example, 'survey/thumb/' + examples[example_idx]['img_url']);
                                    new_example.find('.example-title').val(examples[example_idx]['title']);

                                    new_example.find('.question-move option[value=' + examples[example_idx]['question_move'] + ']').attr('selected', 'selected');

                                }
                                if (g_questions[question_index]['allow_reply_response'] == 1) {
                                    g_question_container.find('.reply_response').prop('checked', true);
                                    g_question_container.find('#sub_input_use_scope').css('display', 'none');
                                    set_visible_question_move(g_question_container, 1);
                                } else {
                                    g_question_container.find('input:checkbox[name=reply_response]').prop("checked", false);
                                    if (g_questions[question_index]['use_other_input'] == 1) {
                                        g_question_container.find('#sub_input_use_scope').css('display', 'inline');
                                        g_question_container.find('input:checkbox[name=use_other_input]').prop("checked", true);
                                    } else {
                                        g_question_container.find('#sub_input_use_scope').css('display', 'inline');
                                        g_question_container.find('input:checkbox[name=use_other_input]').prop("checked", false);
                                    }
                                }
                                if (g_questions[question_index]['allow_random_align'] == 1) {
                                    g_question_container.find('input:checkbox[name=allow_random_align]').prop("checked", true);
                                } else {
                                    g_question_container.find('input:checkbox[name=allow_random_align]').prop("checked", false);
                                }

                                if (g_questions[question_index]['allow_unselect'] == 1) {
                                    g_question_container.find('input:checkbox[name=allow_unselect]').prop("checked", true);
                                } else {
                                    g_question_container.find('input:checkbox[name=allow_unselect]').prop("checked", false);
                                }
                            }
                            // 문항형태가 주관식일때
                            else if (g_questions[question_index]['type'] == 1) {
                                g_question_container.find('.question-option-1 .survey-example-count').val(g_questions[question_index]['example_count']);
                                g_question_container.find('.question-option-1 .survey-example-count').trigger("change");
                                if (g_questions[question_index]['allow_unselect'] == 1) {
                                    g_question_container.find('input:checkbox[name=allow_unselect_1]').prop("checked", true);
                                } else {
                                    g_question_container.find('input:checkbox[name=allow_unselect_1]').prop("checked", false);
                                }
                                g_question_container.find('.question-type-1 .end-move').val(g_questions[question_index]['end_comment_index']);
                            }
                            // 문항형태가 만족도일때
                            else if (g_questions[question_index]['type'] == 2) {

                                g_question_container.find('.question-option-2 .question-type-grade').val(g_questions[question_index]['type_grade']);
                                g_question_container.find('.question-option-2 .question-type-grade').trigger("change");

                                g_question_container.find('.question-option-2 .survey-example-count').val(g_questions[question_index]['example_count']);
                                g_question_container.find('.question-option-2 .survey-example-count').trigger("change");

                                if (g_questions[question_index]['allow_unselect'] == 1) {
                                    g_question_container.find('input:checkbox[name=allow_unselect_2]').prop("checked", true);
                                } else {
                                    g_question_container.find('input:checkbox[name=allow_unselect_2]').prop("checked", false);
                                }
                            }
                            // 문항형태가 강사만족도일때
                            else if (g_questions[question_index]['type'] == 3) {

                                g_question_container.find('.question-option-2 .question-type-grade').val(g_questions[question_index]['type_grade']);
                                g_question_container.find('.question-option-2 .question-type-grade').trigger("change");

                                g_question_container.find('.question-option-2 .survey-example-count').val(g_questions[question_index]['example_count']);
                                g_question_container.find('.question-option-2 .survey-example-count').trigger("change");

                                if (g_questions[question_index]['allow_unselect'] == 1) {
                                    g_question_container.find('input:checkbox[name=allow_unselect_2]').prop("checked", true);
                                } else {
                                    g_question_container.find('input:checkbox[name=allow_unselect_2]').prop("checked", false);
                                }


                                // 평가지표개수변경
                                $(this).find('.exam_kind_count option[value=' + g_questions[question_index]['exam_kind_count'] + ']').attr('selected', 'selected');
                                $(this).find('.exam_kind_count').trigger("change");

                                // 평가대상개수변경
                                $(this).find('.exam_object_count option[value=' + g_questions[question_index]['exam_object_count'] + ']').attr('selected', 'selected');
                                $(this).find('.exam_object_count').trigger("change");

                                //평가지표추가
                                var exam_kinds = g_questions[question_index]['exam_kinds'];
                                $(this).find('.exam-kind-tr').each(function (kind_index) {

                                    $(this).find('.exam-kind-title').val(exam_kinds[kind_index]['title']);

                                    $(this).find('.exam-kind-content').val(exam_kinds[kind_index]['content']);
                                });

                                // 평가대상추가
                                var exam_objects = g_questions[question_index]['exam_objects'];
                                $(this).find('.exam-object-tr').each(function (object_index) {

                                    $(this).find('.exam-object-title').val(exam_objects[object_index]['title']);
                                });
                            }
                        });
                    });
                }
            }
        });
}

function other_input_check(val) {
    $("#other_input_"+val).prop('checked',true);
}

function set_radio_value(index){
    $('#other_input_'+index).val($('input[name="question_other_'+index+'"').val());
}

function diagtype_radio_check(diag_type){
    $('#'+diag_type+'_diag').prop('checked',true);
}

function get_diagtype_value(){
    var val;
    var radios = document.getElementsByName('diagnosis_mode');
    
    // loop through list of radio buttons
    for (var i=0, len=radios.length; i<len; i++) {
        if ( radios[i].checked ) { // radio checked?
            val = radios[i].value; // if so, hold its value in val
            break; // and break out of for loop
        }
    }
    return val; // return value of checked radio or undefined if none checked
}

function browse_survey() 
{
    var diagnosis_id = $("#survey_id").val();
    var diagnosis_title = $("#survey_title").val();
    var diagnosis_begin_date = $("#survey_start_date").val();
    var diagnosis_end_date = $("#survey_end_date").val();

    parameter = "";
    parameter += "prev_survey_id=" + diagnosis_id;
    parameter += "&diagnosis_title=" + diagnosis_title;
    parameter += "&begindate=" + diagnosis_begin_date;
    parameter += "&enddate=" + diagnosis_end_date;

    location.href = site_url + 'diagnosis/browse_diagnosis_education?' + parameter;
}