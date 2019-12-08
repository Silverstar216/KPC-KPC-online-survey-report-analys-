var contact_type = 0;// 0 : 전화번호그룹 // 1 : 개별전화번호
var g_uploader;
var max_file_size = 100;
var attachedHtml = 0; //부과내용이 첨부되었을떄 1: / not : 0\
var selectedRow = "";
var stval='';
var st='all';
var selected = [];
var current_page = 1;
var total_count= 0;
var end_page = 1;
var page_per_count = 20;
var lms_flag = 0;
var myInterval;
var ars_call_id = 0;

$(function () {
    EditUpload.init();
    // set_dropzone_style();
    //초기전화번호전체적재
    searchPhone(0,20);
    $('.notice_datepicker').datetimepicker({
        lang: 'ko',
        format: 'Y-m-d H:i',
        formatDate: 'Y-m-d',
        step:5,
        minDate:new Date(),
        scrollMonth: false,
        scrollTime: false,
        scrollInput: false,

        onSelectDate: function() {
            $(this).trigger('close.xdsoft');
        }
    });

    var currentMobileCount = 0;
    $('.mobile_list_table tbody tr').each( function() {        
        currentMobileCount = currentMobileCount + 1;        
    });    
    $('.mobile_count').text(currentMobileCount);
   
    
    $('.check_all_mobile').change(function() {
        var input = $(this);
        if (input.prop('checked') == true) {
            $('.mobile_list_table tbody tr').each( function() {
                $(this).find('.check_one_mobile').prop('checked', true);
            });
        } else {
            $('.mobile_list_table tbody tr').each( function() {
                $(this).find('.check_one_mobile').prop('checked', false);
            });
        }
    });

    // $('.address_select_table').on('click','tbody td', function (e) {
    //     alert("ddddddddddd",$(this).index());
    //     var $td = $(e.target).closest("td"); // e.target can be <span> instead of <td>
    //     if ($td.length > 0 && $.inArray($td[0].cellIndex, columnIndexesIgnore) < 0) {
    //         // cellIndex is 0-based index. We display in alert 1-based column index
    //         alert("I've been clicked on column " + ($td[0].cellIndex + 1) + "!");
    //     }
    // });

    $('#notice_reserve').change(function() {
        var input = $(this);
        if (input.prop('checked') == true)
            $('#notice_reserve_date_container').removeClass('hidden');
        else
            $('#notice_reserve_date_container').addClass('hidden');
    });

    $('#check_all_group').change(function() {
        var input = $(this);
        if (input.prop('checked') == true) {
            $('#tbl_group table tbody tr').each( function() {
                $(this).find('.check_one_address').prop('checked', true);
            });
        } else {
            $('#tbl_group table tbody tr').each( function() {
                $(this).find('.check_one_address').prop('checked', false);
            });
        }
    });
    $('#check_all_phone').change(function() {
        var input = $(this);
        if (input.prop('checked') == true) {
            $('#tbl_phone table tbody tr').each( function() {
                $(this).find('.check_one_address').prop('checked', true);
            });
        } else {
            $('#tbl_phone table tbody tr').each( function() {
                $(this).find('.check_one_address').prop('checked', false);
            });
        }
    });
    //발신번호등록관련함수들
    var tr = $('.availableSenderList-tbody').find('tr');
    tr.bind('click', function(event) {
        tr.removeClass('row-highlight');
        var tds = $(this).addClass('row-highlight').find('td');

        $.each(tds, function(index, item) {
            $("#senderPhoneInput").val(item.innerHTML);
        });
        onUpdateSenderPhone();
    });
    $( ".availableSenderList" ).hide();
    $( "#senderPhoneInput" ).mouseenter(function() {
        $( ".availableSenderList" ).show();
    });

    //발신번호입력TextBox에서 마우스위치추적
    $(".sec02_c").mousemove(function(evt) {
        if($(".availableSenderList").is(':visible')) {
            var posXFrom = $("#senderPhoneInput").offset().left;
            var posXto = posXFrom + $("#senderPhoneInput").width();
            var posYFrom = $("#senderPhoneInput").offset().top;
            var posYto = posYFrom + $("#senderPhoneInput").height() + 145;
            if (evt.pageX > posXFrom && evt.pageX < posXto && evt.pageY > posYFrom && evt.pageY < posYto)
                $(".availableSenderList").show();
            else
                $(".availableSenderList").hide();
        }

    });
    if($('#phonenumber_mobile').val() != 0 && $('#phonenumber_name').val() != 0) {
        $('.mobile_list_table > tbody').append('<tr><td class="t01"><input type="checkbox" title="선택" class="check_one_mobile"></td><td class="mobile_number_item">' + $('#phonenumber_mobile').val() + '</td><td>' + $('#phonenumber_name').val() + '</td></tr>');
        $('.mobile_count').text(1);
    }



    var new_container = $( '.sec04').first().clone();
    var new_example = $('.phone_item1').clone();

    new_example.appendTo($.find('.div-phone-verify'));
    on_add_example(new_example);

});
function on_add_example(example) {
    example.removeClass('phone_item1');
    example.addClass('example');


    var diff = 0;
    var j= 0;
    var i = 0;
       // 보기 추가 사건처리
    example.find('.btn-example-plus').on('click', function() {
        example.find('.btn-example-plus').css('visibility', 'hidden');

        var new_container = $( '.sec04').first().clone();
        var new_example = $('.phone_item1').clone();

        new_example.appendTo($.find('.div-phone-verify'));
        on_add_example(new_example);

    });

    // 보기 삭제 사건처리
    example.find('.btn-example-remove').on('click', function() {
        var container = example.closest('.div-phone-verify');
        example.remove();
        update_examples(container);
    });

    update_examples(example.closest('.div-phone-verify'));
}

// 보기들의 번호수정, 기타항목검사
function update_examples(container) {
    var example_count = container.find('.example').length;

    container.find('.example').each(function(index) {

        $(this).find('.btn-example-plus').css('visibility', 'hidden');

        // 보기개수가 2인 경우 보기삭제불가능
        if (example_count > 1)
            $(this).find('.btn-example-remove').removeClass('disabled');
        else
            $(this).find('.btn-example-remove').addClass('disabled');

         if(index == example_count - 1) {
            $(this).find('.btn-example-plus').css('visibility', 'visible');
        }

    })
}
function onClickedCal () {
    $('.notice_datepicker').trigger("click");
    //$('.notice_datepicker').datepicker('show');
}
function onEmptyEditorClick() {
    $('.content_editor').val('');
    $('.content_length').text('0');
}

//==============발신번호관리코드부분(시작)===================
//선택한 발신전화를 입력box에 넣고 관리대화창 닫기
function onUseAvailableSenderPhone(phoneNumber){
    $( "#senderPhoneInput" ).val(phoneNumber);
    onCloseSenderPhoneArea()
}

//발신번호관리대화창 열기
function onShowSenderPhoneArea() {
    $(".sec04").show();
    $(".sec02").hide();
    $(".sec03").hide();

    /*$.ajax({
        url: site_url + 'phoneCertification/requestCertification',
        type: 'POST',
        data: {
            phone: "",
        },
        error: function () {
            swal({
                    title: '', text: '보관도중 오류가 발생하였습니다',
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                },
                function (isConfirm) {
                });
        },
        success: function (data) {
            alert(data);
        }
    });*/
}
//발신번호관리대화창 닫기
function  onCloseSenderPhoneArea(){
    $(".sec04").hide();
    $(".sec02").show();
    $(".sec03").show();
}
//추가상태설정
// function onAddSenderPhone(){
//     $("#auKind").val("0");
//     $("#senderphoneNumber").removeAttr("disabled");
//     $("#senderphoneNumber").val("");
//     $("#senderphoneMemo").val("");
// }
//갱신상태설정
function onUpdateSenderPhone(){
    // $("#senderphoneNumber").attr("disabled", true);
    $("#auKind").val("1");
}
//인증코드받기
function onRegisterSenderPhone(){
    var flag = true;
    var savePhone = "";
    var phoneNumber = $("#txtSendingMobile").val();
    //형식오류검사
    if(!checkPhoneNumber(phoneNumber)){
        swal({title: '', text: "인증받을 휴대폰번호형식이 정확치 않습니다.\n(예:02YYYZZZZ, 031YYYZZZZ, 010ABYYYYYY)",
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {});
        flag = false;
        return;
    }

    $('.example').each(function(index) {
        savePhone = $(this).find('.phoneNumberPart1').val();
        if(savePhone =="") {
            swal({title: '', text: "발신번호를 입력하세요",
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                function(isConfirm) {});
            flag = false;
            return;
        }
        if(!checkPhoneNumber(savePhone)){
            swal({title: '', text: "발신번호형식이 정확치 않습니다.\n(예:02YYYZZZZ, 031YYYZZZZ, 010ABYYYYYY)",
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                function(isConfirm) {});
            flag = false;
            return;
        }
    })
    if(flag) {
        $.ajax({
            url: site_url + 'notice/mobile_verify',
            type: 'POST',
            data: {
                sending_mobile: phoneNumber,
            },
            error: function () {
                swal({
                        title: '', text: '인증코드전송실패',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                    },
                    function (isConfirm) {
                    });
            },
            success: function (data) {
                if (data == "1") {
                    swal({
                            title: '', text: '인증코드를 정확히 전송하였습니다. 인증코드를 입력해주세요',
                            confirmButtonText: '확인', allowOutsideClick: false, type: 'success'
                        },
                        function (isConfirm) {
                        });
                } else  if (data == "0") {
                    swal({
                            title: '', text: '회원가입해주세요',
                            confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                        },
                        function (isConfirm) {
                        });
                } else {
                    swal({
                            title: '', text: '인증코드전송실패',
                            confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                        },
                        function (isConfirm) {
                        });
                }
            }
        });
    }
    /*var questionText = '등록신청하시겠습니까?';
    swal({
            title: '', text: '발신번호(' + phoneNumber + ')를 ' + questionText,
            autocomplete:false,
            allowOutsideClick: false,
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonClass: 'btn-danger',
            cancelButtonClass: 'btn-default',
            closeOnConfirm: false,
            closeOnCancel: true,
            confirmButtonText: '예',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: '아니오',
            type: 'warning'
        },
        function (isConfirm) {
            if(isConfirm) {
                $.ajax({
                    url: site_url + 'phoneCertification/requestCertification',
                    type: 'POST',
                    data: {
                        phone: phoneNumber,
                    },
                    error: function () {
                        swal({
                                title: '', text: '보관도중 오류가 발생하였습니다',
                                confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                            },
                            function (isConfirm) {
                            });
                    },
                    success: function (data) {
                        if(data == -1) {
                            swal({
                                    title: '', text: '회원가입을 해주세요',
                                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                                },
                                function (isConfirm) {
                                    location.href=site_url+"join/login_view"
                                });
                        } else if (data == "duplicated") {
                            swal({
                                    title: '', text: '이미 등록되여있는 번호입니다',
                                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                                },
                                function (isConfirm) {
                                });
                        }else if (data == "ars_insert_fail")
                            swal({
                                    title: '', text: 'ARS인증봉사 요청실패',
                                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                                },
                                function (isConfirm) {
                                });
                        else if (data == "connectFail")
                            swal({
                                    title: '', text: 'ARS자료지기접속실패!',
                                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                                },
                                function (isConfirm) {
                                });
                        else {
                            var registerResult = data.split("&");
                            ars_call_id = registerResult[1];
                            $("#verifyNumber").val("인증번호 : " + registerResult[0]);

                            swal({
                                    title: '', text: '전화를 받으셔서 인증번호<'+registerResult[0]+'>를 입력하십시요',
                                    confirmButtonText: '확인', allowOutsideClick: false, type: 'success'
                                },
                                function (isConfirm) {
                                    // getVerifyResult();
                                    myInterval = setInterval(getVerifyResult, 3000);
                            });
                            // var resultText = "";
                            // if ($("#auKind").val() == 0) { //추가인경우
                            //     resultText = "추가되었습니다";
                            //     var appendRow = "<tr class='contact phonegroup'>";
                            //     appendRow += "<td class='phoneNumber' style='width: 25%'>" + phoneNumber + "</td>";
                            //     appendRow += "<td style='width: 45%'>" + $("#senderphoneMemo").val() + "</td>";
                            //     appendRow += "<td style='width: 15%'>인증전</td>";
                            //     appendRow += "<td style='width: 15%'></td></tr>";
                            //     console.log("appendRow:", appendRow);
                            //     // $('#sendphone-body-table tr:last').after(appendRow);
                            //     $('.sendphone-body-table > tbody:last-child').append(appendRow);
                            // } else {                      //Update인경우
                            //     resultText = "수정되었습니다";
                            //     selectedRow.find("td:eq(1)").html($("#senderphoneMemo").val());
                            // }
                        }
                    }
                });
            }
        });*/
}

function onSaveSenderPhone(){
    var flag = true;
    var savePhone = "";
    var verifyCode = $("#txtVerifyCode").val();
    var totalPhoneArray = [];

    //형식오류검사
    if(verifyCode==""){
        swal({title: '', text: "인증코드를 입력해주세요",
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {});
        flag = false;
        return;
    }


    if(flag) {
        $.ajax({
            url: site_url + 'notice/confirm_verify',
            type: 'POST',
            data: {
                verify_code: verifyCode,
            },
            error: function () {
                swal({
                        title: '', text: '인증코드확인실패',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                    },
                    function (isConfirm) {
                    });
            },
            success: function (data) {
                if (data == "1") {
                    $('.example').each(function(index) {
                        var phoneArray = {};
                        savePhone = $(this).find('.phoneNumberPart1').val();
                        if(savePhone =="") {
                            swal({title: '', text: "발신번호를 입력하세요",
                                    confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                                function(isConfirm) {});
                            flag = false;
                            return;
                        }
                        if(!checkPhoneNumber(savePhone)){
                            swal({title: '', text: "인증받을 휴대폰번호형식이 정확치 않습니다.\n(예:02YYYZZZZ, 031YYYZZZZ, 010ABYYYYYY)",
                                    confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                                function(isConfirm) {});
                            flag = false;
                            return;
                        }
                        phoneArray.phone = savePhone;
                        phoneArray.comment = $(this).find('.phonetextComment').val();
                        totalPhoneArray[index]=phoneArray;
                    })
                    if(flag){
                        $.ajax({
                            url: site_url + 'phoneCertification/saveSenderPhone',
                            type: 'POST',
                            data: {
                                totalPhoneArray: totalPhoneArray,
                                sending_mobile:$("#txtSendingMobile").val(),
                            },
                            error: function () {
                                swal({
                                        title: '', text: '발신번호보존실패',                                        confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                                    },
                                    function (isConfirm) {
                                    });
                            },
                            success: function (data) {
                                if (data == "1") {
                                    // location.href = site_url + 'notice?IsShowRegisterArea=true';
                                    location.href = site_url + 'phone/sendPhoneNumber';
                                } else {
                                    swal({
                                            title: '', text: '발신번호보존실패',
                                            confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                                        },
                                        function (isConfirm) {
                                        });
                                }
                            }
                        });
                    }

                } else {
                    swal({
                            title: '', text: '인증코드가 정확하지 않습니다.',
                            confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                        },
                        function (isConfirm) {
                        });
                }
            }
        });
    }
}
/*//인증결과를 조회하는 함수
function getVerifyResult(){
    $.ajax({
        url: site_url + 'phoneCertification/getCertificationResult',
        type: 'POST',
        data: {
            ars_call_id : ars_call_id,
        },
        error: function () {
            clearInterval(myInterval);
            swal({
                    title: '', text: '인증요청과정에 오류가 발생하였습니다',
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                },
                function (isConfirm) {

                });
        },
        success: function (data) {
            if(data == -1) {
                clearInterval(myInterval);
                swal({
                        title: '', text: '회원가입을 해주세요',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                    },
                    function (isConfirm) {

                        location.href=site_url+"join/login_view"
                    });
            }else if (data == "success") {
                clearInterval(myInterval);
                swal({
                        title: '', text: '발신번호인증이 성공하였습니다',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'success'
                    },
                    function (isConfirm) {
                        location.href = site_url + 'notice?IsShowRegisterArea=true';
                    });
            }else if (data == "wait"){
                return;
            }else{
                clearInterval(myInterval);
                swal({
                        title: '', text: data,
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                    },
                    function (isConfirm) {

                    });
            }
        }
    });
}*/
//발신번호삭제
function onRemoveSenderPhone(senderPhoneId){
    swal({
            title: '', text: '정말 발신번호를 삭제하시겠습니까?',
            autocomplete: false,
            allowOutsideClick: false,
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonClass: 'btn-danger',
            cancelButtonClass: 'btn-default',
            closeOnConfirm: false,
            closeOnCancel: true,
            confirmButtonText: '예',
            cancelButtonText: '아니오',
            type: 'warning'
        },
        function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: site_url + 'phoneCertification/removeSenderPhone',
                    type: 'POST',
                    data: {
                        sender_phone_id: senderPhoneId,
                    },
                    error: function () {
                        swal({
                                title: '', text: '삭제도중 오류가 발생하였습니다',
                                confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                            },
                            function (isConfirm) {
                            });
                    },
                    success: function (data) {
                        swal({
                                title: '', text: '성공적으로 삭제되었습니다',
                                confirmButtonText: '확인', allowOutsideClick: false, type: 'success'
                            },
                            function (isConfirm) {
                                location.href = site_url + 'phone/sendPhoneNumber';
                            });
                    }
                });
            }
        });
}
//발신번호 이름보관
function onSaveSenderPhoneMemo(senderPhoneId){
        var senderPhoneMemoId = "#senderPhoneMemo" + senderPhoneId;
        $.ajax({
            url: site_url + 'phoneCertification/saveSenderPhoneMemo',
            type: 'POST',
            data: {
                sender_phone_id: senderPhoneId,
                memo:$(senderPhoneMemoId).val(),
            },
            error: function () {
                swal({
                        title: '', text: '저장도중 오류가 발생하였습니다',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                    },
                    function (isConfirm) {
                    });
            },
            success: function (data) {
                swal({
                        title: '', text: '성공적으로 저장되었습니다',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'success'
                    },
                    function (isConfirm) {
                        location.href = site_url + 'phone/sendPhoneNumber';
                    });
            }
        });

}
//형식오류검사
function checkPhoneNumber(phoneNumber){
    //유선번호목록
    var phoneFilterList = [['02',7],['031',7],['032',7],['033',7],['041',7],['042',7],['042',7],['043',7],['044',7],
        ['051',7],['052',7],['053',7],['054',7],['055',7],
        ['061',7],['062',7],['063',7],['064',7],
        ['02',8],['031',8],['032',8],['033',8],['041',8],['042',8],['042',8],['043',8],['044',8],
        ['051',8],['052',8],['053',8],['054',8],['055',8],
        ['061',8],['062',8],['063',8],['064',8],
        //이동통신전화번호
        ['010',7],['011',7],['016',7],['017',7],['018',7],['019',7],
        ['010',8],['011',8],['016',8],['017',8],['018',8],['019',8],
        //대표전화번호 예) 15
        ['15',6],['16',6],['18',6],
        //공통서비스식별번호
        ['020',8],['030',8],['040',8],['050',8],['060',8],['070',8],['080',8],['090',8]
    ];
    var checkResult = false;
    $.each(phoneFilterList, function (index, value) {
        if(phoneNumber.substr(0,value[0].length) == value[0] && phoneNumber.length == value[0].length + Number(value[1])){
            checkResult = true;
            return false;
        };
    });
    return checkResult;
}
//휴대폰형식오류검사
function checkMobileNumber(phoneNumber){
    //유선번호목록
    var phoneFilterList = [
        //이동통신전화번호
        ['010',7],['011',7],['016',7],['017',7],['018',7],['019',7],
        ['010',8],['011',8],['016',8],['017',8],['018',8],['019',8],
    ];
    var checkResult = false;
    $.each(phoneFilterList, function (index, value) {
        if(phoneNumber.substr(0,value[0].length) == value[0] && phoneNumber.length == value[0].length + Number(value[1])){
            checkResult = true;
            return false;
        };
    });
    return checkResult;
}

//==============발신번호관리코드부분(끝)========================
function onAddOneClick() {

    var mobileNumber = $('.one_mobile_number').val().replace(/-/g,'').replace(/\s/g, '');
    $('.one_mobile_number').val("");
    if (mobileNumber == '') {
        swal({
                title: '', text: '수신번호를 입력하여 주세요',
                confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
            },
            function (isConfirm) {
            });
        return;
    }
    // if ($('.calling_name').val() == '') {
    //     swal({
    //             title: '', text: '수신인이름을 입력하여 주세요',
    //             confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
    //         },
    //         function (isConfirm) {
    //         });
    //     return;
    // }
    if(!checkMobileNumber(mobileNumber)){
        valid = false;
        swal({title: '', text: "휴대폰번호형식이 정확치 않습니다.\n(예:010YYYYZZZZ, 011YYYYZZZZ)",
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {});
        return;
    }
    if (mobileNumber != '') {
        var cNumber='';
        if(mobileNumber.length == 11)      //모바일이면
            cNumber = mobileNumber.substr(0,3)+'-'+mobileNumber.substr(3,4)+'-'+mobileNumber.substr(7,4);
        else if(mobileNumber.length == 9)  //유선전화번호이면
            cNumber = mobileNumber.substr(0,2)+'-'+mobileNumber.substr(2,3)+'-'+mobileNumber.substr(5,4);
        else
            cNumber = mobileNumber;
        var str = $('.mobile_list_table tbody')[0].innerHTML;
        if (str.indexOf('class="mobile_number_item">' + cNumber + '</td>') > 0) {
            alert("전화번호가 이미 추가되여있습니다!");
            return;
        }
        //전화번호를 새로 등록하기

        $.ajax({
            url: site_url + "phone/addMobileUsr",
            cache:false,
            timeout : 10000,
            type: 'POST',
            data: {
                groups: '',
                username: $('.calling_name').val(),
                mobile:mobileNumber,
                memo:''
            },
            success: function(data) {
                if (data == 'err') {
                    swal({title: '', text: "추가오류!\n 다시 시도하세요!",
                            confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                        function(isConfirm) {});
                } else{
                    //목록테블에 현시
                    var total_count = Number($('.mobile_count').text()) + 1;

                    $('.mobile_list_table > tbody').append('<tr><td class="t01"><input type="checkbox" title="선택" class="check_one_mobile"></td><td class="mobile_number_item">' + cNumber + '</td><td>'+$('.calling_name').val()+'</td></tr>');
                    $('.mobile_count').text(total_count);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                swal({title: '', text: xhr.status,
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                    function(isConfirm) {});
            }
        });
    }
}
function isNumberKey(evt){
    console.log("keyConde",event.keyCode);
    console.log("evt",evt.which);
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function onDeleteSelectedClick() {
    $('.mobile_list_table tbody tr').each( function() {
        if ($(this).find('.check_one_mobile').prop('checked') == true) {
            var currentCount = $('.mobile_count').text()
            var container = $(this).find('.group_count_item');

            var group_count = Number(container.text());
            if (group_count == 0)
                $('.mobile_count').text(currentCount - 1);
            else {
                var total_count = currentCount - group_count;

                $('.mobile_count').text(total_count);
            }
            this.parentNode.removeChild(this);

        }
    });


}

function onDeleteAllClick() {
    $('.mobile_list_table tbody tr').each(function() {
        this.parentNode.removeChild(this);
    });

    $('.mobile_count').text($('.mobile_list_table tbody tr').length);
}
//미리보기에서 <되돌이> 단추를 눌렀을때
function onShowDocumentArea(){
    $('#documentArea').show();
    $('#attachedHTMLArea').hide();
}
//미리보기에서 <보관>단추를 눌렀을때
function onSaveAttachedHtml(){
    swal({title: '', text: '성공적으로 보관되었습니다',
            confirmButtonText: '확인', allowOutsideClick: false, type: 'success'},
        function(isConfirm) {});
}
//일반문서에서 <미리보기> 단추를 눌렀을때 처리
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
    //모달방식인 경우
    // $('#attached_content').html($('#attached_check_content').val());
    // $('#attachedHTMLDialog').modal('show');
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
    if (message_type == 0) { //문자서비스전송방식
        //포함문서가 doc/HTML 파일인경우
        $.ajax({
            url: site_url + 'notice/convertToHTML',
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
                $converted_file_url = "https://" + location.hostname + "/" + ConvertedHtmlUrl;
                $('#attached_file_url').val($converted_file_url);
                swal({title: '', text: '문서가 포함되었습니다',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'success'},
                    function(isConfirm) {});
                $('#attached_check').val("1");
                // $("#attached_content").val("asdfasdf")
                $('#attached_content').html('');

                $('#attached_content').html("<iframe src='" + $converted_file_url + "' " + "style='width:100%;height:100%'></iframe>");
                bytesLength();
            }
        });
    }
}

function onSendNoticeClick() {

    var content = $('.content_editor').val();
    if (content == '') {
        valid = false;
        myApp.hideProgress();
        myApp.updateProgress(0, '');
        swal({title: '', text: '메세지내역을 입력하여 주세요',
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {
                $('#content_editor').focus();
            });
        return;
    }
    var attached = 0
    var message_type = $('#message_type').val();
    var object_id = $('#object_id').val();
    
    if(message_type == 0) {  //  설문이 아니면.3
        attached = $('#attached_check').val();
    } else {
        attached = $('#attached').val();
    }

    var file_url = $('#attached_file_url').val();
    // var mobile_count = $('.mobile_list_table tbody tr').length;
    var mobile_count = $('.mobile_count').html();
    if (mobile_count < 1) {
        myApp.hideProgress();
        myApp.updateProgress(0, '');
        valid = false;
        swal({title: '', text: '전송할 전화번호를 입력하여 주세요',
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {
                $('#content_editor').focus();
            });
        return;
    }
    var calling_number = $('.calling_number').val();
    if (calling_number == '') {
        myApp.hideProgress();
        myApp.updateProgress(0, '');
        valid = false;
        swal({title: '', text: '발신번호를 입력하여 주세요',
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {
                $('#content_editor').focus();
            });
        return;
    }
    if(!checkPhoneNumber(calling_number)){
        myApp.hideProgress();
        myApp.updateProgress(0, '');
        valid = false;
        swal({title: '', text: "발신번호형식이 정확하지 않습니다.\n(예:02YYYZZZZ, 031YYYZZZZ, 010ABYYYYYY)",
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {});

        return;
    }

    var calling_name = $('.calling_name').val();
    var start_time = $('#notice_reserve_date').val();
    var current_time = new Date();
    if ($('#notice_reserve').prop('checked') == true) {
        var endDateArr = start_time.split('-');

        var startDateCompare = new Date();
        var endDateCompare = new Date(endDateArr[0], parseInt(endDateArr[1])-1, endDateArr[2]);

        if(startDateCompare.getTime() > endDateCompare.getTime()) {
            if(start_time < current_time){
                swal({title: '', text: "예약시간을 정확히 설정하여야 합니다.",
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                    function(isConfirm) {});
                return;
            }
        }
    }

    var sms_length = $('#sms_length').val();
    var length = strlength(content);
    if (length > sms_length) {
        lms_flag = 1;
    }

    if($('#attached_check').val() == 0 && $('#attached_file_name').val() != ''){
        myApp.hideProgress();
        myApp.updateProgress(0, '');
        swal({title: '', text: "첨부파일을 선택하지 않았습니다. <포함하기>단추를 누르세요",
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {});
        return;
    }

    if (lms_flag == 1){
        var base_length =$('.base_length').text();
        base_length = base_length.substr(1,base_length.length);
        swal({
                title: '', text: base_length+'이상이므로 LMS(장문)으로 발송됩니다.',
                autocomplete:false,
                allowOutsideClick: false,
                showConfirmButton: true,
                showCancelButton: true,
                confirmButtonClass: 'btn-danger',
                cancelButtonClass: 'btn-default',
                closeOnConfirm: false,
                closeOnCancel: true,
                confirmButtonText: '예',
                cancelButtonText: '아니오',
                type: 'warning'
            },
            function (isConfirm) {
                myApp.showProgress();
                myApp.updateProgress(100, '전송중입니다.');
                if (isConfirm) {
                    swal.close();
                    save_notices(1,object_id,content,message_type,attached,file_url,mobile_count,calling_number,calling_name,start_time);
                } else {
                    myApp.hideProgress();
                    myApp.updateProgress(0, '');
                }
            });
    } else {
        myApp.showProgress();
        myApp.updateProgress(100, '전송중입니다.');
        save_notices(0,object_id,content,message_type,attached,file_url,mobile_count,calling_number,calling_name,start_time);
    }
    
    /*var stringByteLength =strlength(content);
    if(($('#attached_check').val() == undefined && message_type == 0) || ($('#attached_check').val() == 0 && message_type == 0)) {   //일반문자서비스인경우
        if(stringByteLength > 89) {
            swal({
                    title: '', text: 'LMS(장문)으로 발송됩니다.',
                    autocomplete:false,
                    allowOutsideClick: false,
                    showConfirmButton: true,
                    showCancelButton: true,
                    confirmButtonClass: 'btn-danger',
                    cancelButtonClass: 'btn-default',
                    closeOnConfirm: false,
                    closeOnCancel: true,
                    confirmButtonText: '예',
                    cancelButtonText: '아니오',
                    type: 'warning'
                },
                function (isConfirm) {
                    if (isConfirm) {
                        save_notices(1,object_id,content,message_type,attached,file_url,mobile_count,calling_number,calling_name,start_time);
                    }
                });
        }else {
            save_notices(0,object_id,content,message_type,attached,file_url,mobile_count,calling_number,calling_name,start_time);
        }
    } else { // 문서포함일반서비스 와 설문인경우
        if(stringByteLength > 68) {
            swal({
                    title: '', text: 'LMS(장문)으로 발송됩니다.',
                    autocomplete:false,
                    allowOutsideClick: false,
                    showConfirmButton: true,
                    showCancelButton: true,
                    confirmButtonClass: 'btn-danger',
                    cancelButtonClass: 'btn-default',
                    closeOnConfirm: false,
                    closeOnCancel: true,
                    confirmButtonText: '예',
                    cancelButtonText: '아니오',
                    type: 'warning'
                },
                function (isConfirm) {
                    if (isConfirm) {
                        save_notices(1,object_id,content,message_type,attached,file_url,mobile_count,calling_number,calling_name,start_time);
                    }
                });
        }else {
            save_notices(0,object_id,content,message_type,attached,file_url,mobile_count,calling_number,calling_name,start_time);
        }
    }*/
}

function save_notices(kind ,object_id,content,message_type,attached,file_url,mobile_count,calling_number,calling_name,start_time) {
    $.ajax({
        url: site_url + 'notice/send',
        type: 'POST',
        data: {
            object_id:object_id,
            content: content,
            message_type:message_type,
            message_kind: kind,
            attached: attached,
            file_url: file_url,
            mobile_count: mobile_count,
            calling_number: calling_number,
            calling_name: calling_name,
            start_time: start_time
        },
        error: function(xhr, httpStatusMessage, customErrorMessage) {
            myApp.hideProgress();
            myApp.updateProgress(0, '');
            console.log("send Action call fail!");
        },
        success: function(data) {
            if(data > 0) {
                var object_id = data;

                var mobiles = [];
                $('.mobile_list_table tbody tr .mobile_number_item').each(function (index) {
                    mobiles[index] = $(this).text().replace(/-/g,'').replace(/\s/g, '');
                });
                var groups = [];
                $('.mobile_list_table tbody tr .g_group_id').each(function (index) {
                    groups[index] = $(this).text();
                });
                send_message(object_id, message_type, kind, attached, mobiles, groups, content, start_time, calling_number);
            } else if(data == -1){
                myApp.hideProgress();
                myApp.updateProgress(0, '');
                swal({title: '', text: "사용자가입을 하여야 합니다.",
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                    function(isConfirm) {
                        location.href=site_url+"join/login_view"
                    });
            }
        }
    });
}

function send_message(object_id, type, kind, attached, mobiles, groups,content,start_time,calling_number) {
    $.ajax({
        url: site_url + 'notice/save_mobiles',
        cache: false,
        timeout: 10000,
        data: {
            object_id: object_id,
            type: type,
            kind: kind,
            content: content,
            attached: attached,
            calling_number: calling_number,
            start_time: start_time,
            mobiles: JSON.stringify(mobiles),
            groups: JSON.stringify(groups),
        },
        type: 'POST',
        dataType: "json",
        success: function (data) {
            myApp.hideProgress();
            myApp.updateProgress(0, '');
            jQuery.parseJSON(JSON.stringify(data));

            if (data.status == 0) {
                swal({
                        title: '', text: '성공적으로 전송되었습니다.',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'success'
                    },
                    function (isConfirm) {
                        if($('#message_type').val() != "4")
                            location.href = site_url + 'sendlog';
                    });
            }else {
                swal({
                    title:  data.msg, text: data.number_phones,
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                }, function (isConfirm) {
                });
            }
        }
    });
}

function onSearchAddSelectedClick() {
    $('.address_select_table tbody tr').each( function() {
        if (contact_type == 0) {
            var search_word = $("#search_group").val();
            if (search_word == '')
                return;
            var contact_container = $(this).closest('.phonegroup');
            var group_name = contact_container.find('.group-name').text();
            if (group_name.indexOf(search_word) >= 0) {
                $(this).find('.check_one_address').prop('checked', true);

            } else {
                $(this).find('.check_one_address').prop('checked', false);
            }

        } else {
            var search_word = $("#search_name").val();
            if (search_word == '')
                return;
            var contact_container = $(this).closest('.phonenum');
            var mobile = contact_container.find('.contact-mobile').text();
            var user_name = contact_container.find('.contact-name').text();
            if (user_name.indexOf(search_word) >= 0) {
                $(this).find('.check_one_address').prop('checked', true);

            } else {
                $(this).find('.check_one_address').prop('checked', false);
            }
        }
    });
}

function onAddSelectedAddressClick() {
    var includingPhone = "";
    $('input.check_one_address').each(function(index) {
        if ($(this).is(':checked')) {
            //전화번호그룹추가인경우
            if (contact_type == 0) {
                var contact_container = $(this).closest('.phonegroup');
                var group_name = contact_container.find('.group-name').text();
                var group_id = contact_container.find('.group-id').text();
                //이미 그룹이 추기되있으면
                var str = $('.mobile_list_table tbody')[0].innerHTML;
                if (str.indexOf('class="g_group_id hidden">' + group_id + '</td>') > 0)
                    return;
                // //이미 그룹안의 전화번호가 추가되있으면
                // if (str.indexOf('class="p_group_id hidden">' + group_id + '</td>') > 0){
                //
                // }
                if (group_id != '') {
                    var group_count = Number(contact_container.find('.group-count').text());
                    var total_count = Number($('.mobile_count').text()) + group_count;

                    $('.mobile_list_table > tbody').append('<tr><td class="t01"><input type="checkbox" title="선택" class="check_one_mobile"></td><td class="group-name">' + group_name + '</td><td class="group_count_item">' + group_count + '</td><td class="g_group_id hidden">' + group_id + '</td></tr>');
                    $('.mobile_count').text(total_count);
                }
            //개별전화번호추가인경우
            } else {
                var contact_container = $(this).closest('.phonenum');
                var mobile = contact_container.find('.contact-mobile').text().trim();
                var user_name = contact_container.find('.contact-name').text();
                var group_id = contact_container.find('.group-id').text();
                var str = $('.mobile_list_table tbody')[0].innerHTML;
                //이미 전화번호가 추가되있으면 되돌이
                if (str.indexOf('class="mobile_number_item">' + mobile + '</td>') > 0){
                    // includingPhone += mobile+",";
                    return;
                }

                //이미 그 전화번호가 속한 그룹이 추가되있으면 되돌이
                if(str.indexOf('class="g_group_id hidden">'+group_id) > 0){
                    // includingPhone += mobile+",";
                    // swal({title: '', text: '전화번호(' + mobile + ')가 이미 포함되있습니다' ,
                    //         confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                    //     function(isConfirm) {});
                    return;
                }

                var total_count = Number($('.mobile_count').text()) + 1;
                if (mobile != '') {
                    $('.mobile_list_table > tbody').append('<tr><td class="t01"><input type="checkbox" title="선택" class="check_one_mobile"></td><td class="mobile_number_item">' + mobile + '</td><td>' + user_name + '</td><td class="p_group_id hidden">' + group_id + '</td></tr>');
                    $('.mobile_count').text(total_count);
                }
            }
            //this.parentNode.parentNode.parentNode.hidden = true;
        }
    });
    // swal({title: '', text: '전화번호(' + mobile + ')가 이미 포함되있습니다' ,
    //         confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
    //     function(isConfirm) {});
}

function onAddPhoneClick(phone,name,group_id){
    var str = $('.mobile_list_table tbody')[0].innerHTML;
    var cPhone='';
    if(phone.length == 11)      //모바일이면
        cPhone=phone.substr(0,3)+'-'+phone.substr(3,4)+'-'+phone.substr(7,4);
    else if(phone.length == 9)  //유선전화번호이면
        cPhone=phone.substr(0,2)+'-'+phone.substr(2,3)+'-'+phone.substr(5,4);
    else
        cPhone = phone;
    //이미 전화번호가 추가되있으면 되돌이
    if (str.indexOf('class="mobile_number_item">' + cPhone + '</td>') > 0){
        // includingPhone += mobile+",";
        return;
    }

    //이미 그 전화번호가 속한 그룹이 추가되있으면 되돌이
    if(str.indexOf('class="g_group_id hidden">'+group_id) > 0){
        // includingPhone += mobile+",";
        // swal({title: '', text: '전화번호(' + mobile + ')가 이미 포함되있습니다' ,
        //         confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
        //     function(isConfirm) {});
        return;
    }

    var total_count = Number($('.mobile_count').text()) + 1;
    if (phone != '') {
        $('.mobile_list_table > tbody').append('<tr><td class="t01"><input type="checkbox" title="선택" class="check_one_mobile"></td><td class="mobile_number_item">' + cPhone + '</td><td>' + name + '</td><td class="p_group_id hidden">' + group_id + '</td></tr>');
        $('.mobile_count').text(total_count);
    }
}

//전화번호류형(그룹/개별)선택 사건추가 (선택된 단추에 btn-warning클라스추가하여 강조표시)
$('.btn-phonenum-type').on('click', function() {
    var container = $(this).closest('.div-phone');
    var type = $(this).attr('phonenum-type');
    contact_type = type;
    if(type == 0) {
        $('.t_text').show();
        $("#tbl_group").show();
        $("#tbl_phone").hide();
    }
    else {
        $('.t_text').hide();
        $("#tbl_group").hide();
        $("#tbl_phone").show();
    }
    change_phonenum_type(container, type);
});

//전화번호검색
function searchPhone(flag,page_per_count)
{
    console.log("searchPhone");
    if(flag == 0) {
        current_page = 1;
    }
    //
    if($( "#st" ).length)
        st = $.trim($('#st').val());
    else
        st = 'all';
    if($( "#st_val" ).length)
        stval = $.trim($('#st_val').val());
    else
        stval = '';
    if($( "#groups" ).length)
        ngst = $.trim($('#groups').val());
    else
        ngst = 'all';
    $.ajax({
        url: site_url + "notice/searchPhone",
        cache:false,
        timeout : 10000,
        dataType:'html',
        data: {
            st: st,
            ngst: ngst,
            stval:stval,
            page:current_page,
            count:page_per_count
        },
        type:'post',
        success: function(data) {
            if (data !== 'err') {
                // $('#sendphone-body-table tr:last').after(appendRow);
                // $('.phonenum').remove();
                $('.check_all_address').removeAttr('checked');
                $('#phone_tbody').html(data);
                // $('#tbl_phone > tbody:last-child').append();
                addpageEventlisner();
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            swal({title: '', text: xhr.status,
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                function(isConfirm) {});
        }
    });
}

function show_phoneByGroup(group_id) {
    $("#groups").val(group_id).prop("selected", true);
        st = 'all';
        stval = '';
        ngst = group_id;
    $.ajax({
        url: site_url + "notice/searchPhone",
        cache:false,
        timeout : 10000,
        dataType:'html',
        data: {
            st: st,
            ngst: ngst,
            stval:stval,
            page:1,
            count:20
        },
        type:'post',
        success: function(data) {
            if (data !== 'err') {
                // $('#sendphone-body-table tr:last').after(appendRow);
                // $('.phonenum').remove();
                $('.check_all_address').removeAttr('checked');
                $('#phone_tbody').html(data);
                // $('#tbl_phone > tbody:last-child').append();
                addpageEventlisner();
                $('.btn-phone').trigger('click');
                /*var container = $(this).closest('.div-phone');
                var type = 1;
                contact_type = type;
                if(type == 0) {
                    $('.t_text').show();
                    $("#tbl_group").show();
                    $("#tbl_phone").hide();
                }
                else {
                    $('.t_text').hide();
                    $("#tbl_group").hide();
                    $("#tbl_phone").show();
                }
                change_phonenum_type(container, type);*/
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            swal({title: '', text: xhr.status,
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                function(isConfirm) {});
        }
    });
}

//전화번호페지절환tab사건처리
function addpageEventlisner() {
    total_count = $('#totalcnt').val();
    if (total_count != undefined && total_count != 0) {
        $('.blog-pagination-small').html(Paging(total_count, page_per_count, current_page));
    } else {
        $('.blog-pagination-small').html('');
    }
}

//절환tab생성처리
function Paging(total_count,  pageSize, cur_page) {

    mod_page = 0;
    total_page= 0;

    total_page = parseInt(parseInt(total_count)/parseInt(pageSize));
    mod_page = parseInt(total_count) % parseInt(pageSize);
    if(mod_page !=0) {
        total_page = total_page +1;
    }

    if(cur_page > total_page)
        cur_page=total_page;
    prev_page = parseInt(cur_page) - 1;
    if (prev_page < 1)
        prev_page = 1;
    next_page = parseInt(cur_page) + 1;
    if (next_page > total_page)
        next_page = total_page;

    class_first = '';
    class_prev = '';
    class_next = '';
    class_last = '';

    page_per_count_statue = "display: inline-block;";
    if(total_count < 11) {
        class_first = 'disabled';
        class_prev = 'disabled';
        class_next = 'disabled';
        class_last = 'disabled';
        page_per_count_statue = "display: none;";
    }

    page_counts = 6;
    var html = "";

    start_page = cur_page - (parseInt)(page_counts / 2);
    end_page = cur_page + (parseInt)(page_counts / 2);
    if (start_page < 1) {
        start_page = 1;
        if (page_counts < total_page)
            end_page = page_counts;
        else
            end_page = total_page;
    }
    if (end_page > total_page) {
        end_page = total_page;
        if (page_counts < total_page)
            start_page = total_page - page_counts + 1;
        else
            start_page = 1;
    }

    page = start_page;
    if(total_page ==1){
        end_page = 0;
    }
    class_pagination = '';
    if(total_page == 1)
        class_pagination = 'hidden';

    html+='<ul class="list-unstyled" style="display: inline-block;">';
    html+='<li class="'+class_first+'"><a href="javascript:go_page(1)">❮❮</a></li>';
    html+='<li class="'+class_prev+'"><a href="javascript:go_page('+ prev_page+')">❮</a></li>';
    for (page = start_page; page <= end_page; page++) {
        active = '';
        if (page == cur_page)
            active = 'active';
        html+='<li class="'+active+'"><a href="javascript:go_page('+page+')">'+page+'</a></li>';
    }
    html+='<li class="'+class_next+'"><a href="javascript:go_page('+ next_page+')">❯</a></li>';
    html+='<li class="'+class_last+'"><a href="javascript:go_page('+ total_page+')">❯❯</a></li>';
    html+='</ul>';
    html+='<div style="'+page_per_count_statue+' margin-left: 30px;font-size: 14px;">';
    html+='</div>';
    return html;
}

//절환사건처리
function go_page(page){
    current_page =page;
    searchPhone(1,page_per_count);
}

function searchBtnClick(obj_id)
{
    searchPhone(0,page_per_count);
}

// 전화번호형태변경단추 선택
function change_phonenum_type(container, type) {
    container.find('.btn-phonenum-type').removeClass('btn-active').addClass('btn-default');
    container.find($.sprintf('.btn-phonenum-type[phonenum-type=%d]',type)).removeClass('btn-default').addClass('btn-active');

    // 전화그룹
    if (type == 0) {
        // 옵션 변경
        // container.find('.phonenum-option-0').css('display', 'block');
        // container.find('.phonenum-option-1').css('display', 'none');

        // 보기 변경
        container.find('.phonenum-type-0').css('display', 'table-row');
        container.find('.phonenum-type-1').css('display', 'none');

        $('.phonegroup').removeClass('hidden');
        $('.phonenum').addClass('hidden');

        $('.group-tr').removeClass('hidden');
        $('.number-tr').addClass('hidden');
    }
    // 전화번호 일때
    else if (type == 1) {
        container.find('.phonenum-option-0').css('display', 'none');
        container.find('.phonenum-option-1').css('display', 'block');

        // 보기 변경
        container.find('.phonenum-type-0').css('display', 'none');
        container.find('.phonenum-type-1').css('display', 'table-row');

        $('.phonegroup').addClass('hidden');
        $('.phonenum').removeClass('hidden');

        $('.group-tr').addClass('hidden');
        $('.number-tr').removeClass('hidden');
    }

}

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
            //
            // filters : {
            //     max_file_size : max_file_size + 'mb',
            //     // mime_types: [
            //     //     { title : "Hwp files", extensions : "hwp" },
            //     //     // { title : "Doc files", extensions : "doc,docx" },
            //     //     { title : "HTML files", extensions : "htm,html" },
            //     //     { title : "PDF files", extensions : "pdf" }
            //     // ]
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
                        $('#preview').removeAttr('disabled');
                        $('#import').removeAttr('disabled');


                        // 화일선택후 자동으로 업로드
                        g_uploader.start();
                        myApp.showProgress();
                    }
                },

                FilesRemoved: function() {
                    if (g_uploader.files.length == 0) {

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


// 첨부문서 올리적재 끝난후
function afterUploaded(data) {
    var obj = jQuery.parseJSON(data);
    $('#attached_check').val("0");
    if (obj.status == 'OK') {
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
            // 화일 올리적재 성공
            // 미리 보기 단추 활성화
            $('#preview').removeAttr('disabled');
            $('#import').removeAttr('disabled');
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
    }
    return;
}

/*
// 첨부문서를 drag-drop하는 령역 설정
function set_dropzone_style() {
    $('.dropzone-file-area').bind('dragenter', function() {
        $(this).css("border", "2px solid #028AF4");
    });

    $('.dropzone-file-area').bind('dragleave', function() {
        $(this).css("border", "2px dashed #028AF4");
    });

    $('.dropzone-file-area').bind('dragover', function() {
        $(this).css("border", "2px solid #028AF4");
    });

    $('.dropzone-file-area').bind('drop', function() {
        $(this).css("border", "2px dashed #028AF4");
    });
}
*/

function bytesLength() {
    var message_type = $('#message_type').val();

    var attached = $('#attached_check').val();

    var content = $('.content_editor').val();
    var length = strlength(content);
    if(($('#attached_check').val() == undefined && message_type == 0) || ($('#attached_check').val() == 0 && message_type == 0)) {   //일반문자서비스인경우
        $('.content_length').text(length);
        $('.base_length').text('/89 byte');


        if(length > 89) {
            if(lms_flag ==0) {
                swal({title: '', text: '문자의 길이가 89 byte를 초과하였습니다.\n 90 byte 이하이면 SMS(단문), 90 byte 이상이면 LMS(장문)으로 발송됩니다.',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                    function(isConfirm) {});
            }
            lms_flag = 1;
        }else {
            lms_flag = 0;
        }
    }else {
        $('.content_length').text(length);
        $('.base_length').text('/69 byte');
        if(length > 69) {
            if(lms_flag ==0) {
                swal({title: '', text: '문자의 길이가 69 byte를 초과하였습니다.\n 70 byte 이하이면 SMS(단문),70 byte 이상이면 LMS(장문)으로 발송됩니다.',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
                    function(isConfirm) {});
            }
            lms_flag = 1;
        } else {
            lms_flag = 0;
        }
    }
}

function isKorean(value) {
    var numUnicode = value.charCodeAt(0);
    if (44031 <= numUnicode && numUnicode <= 55203 || 12593 <= numUnicode && numUnicode <= 12643) return true;
    return false;
}

//문자 하나씩 잘라서 한글 여부 체크해서 길이반환
function strlength(value) {
    var strlen = 0;
    var str;
    var len = value.length;
    var i;
    for (i = 0; i < len; i++) {
        str = value.substr(i, 1);
        if (isKorean(str)) {
            strlen = strlen + 2;
        } else {
            strlen = strlen + 1;
        }
    }
    return strlen;
}