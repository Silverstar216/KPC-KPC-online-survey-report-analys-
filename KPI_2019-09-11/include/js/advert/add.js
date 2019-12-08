/**
 * Author: KMC
 * Date: 10/6/15
 */


$(function () {

    $('.advert_datepicker').datetimepicker({
        lang: 'ko',
        timepicker: false,
        format: 'Y-m-d',
        minDate:new Date(),
        formatDate: 'Y-m-d',
        scrollMonth: false,
        scrollTime: false,
        scrollInput: false,
        onSelectDate: function() {
            $(this).trigger('close.xdsoft');
        }
    });


});
function advert_send() {

    var valid = true;
    var mobileNumber = $('.receive_phone').val().replace(/-/g,'').replace(/\s/g, '');

    if (mobileNumber == '') {
        valid = false;
        swal({
                title: '', text: '수신번호를 입력하여 주세요',
                confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
            },
            function (isConfirm) {
            });
        return;
    }
    if(!checkMobileNumber(mobileNumber)){
        valid = false;
        swal({title: '', text: "휴대폰번호형식이 정확치 않습니다.\n(예:010YYYYZZZZ, 011YYYYZZZZ)",
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {});
        return;
    }
    var link_url = $('#link_url').val();
    if(link_url == "") {
        valid = false;
        swal({title: '', text: "연결주소를 입력하세요",
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {});
        return;
    }
    if(valid) {
        $.ajax({
            url: site_url + "advert/send",
            cache:false,
            timeout : 10000,
            type: 'POST',
            data: {
                url: link_url,
                mobile:mobileNumber
            },
            success: function(data) {
                if (data == 'err') {
                    swal({title: '', text: "전송실패!\n 다시 시도하세요!",
                            confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                        function(isConfirm) {});
                } else{
                    swal({title: '', text: "성공적으로 전송되였습니다.",
                            confirmButtonText: '확인', allowOutsideClick: false, type: 'success'},
                        function(isConfirm) {});
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

function advert_save() {
    var valid = true;

    var advert_id = $('#advert_id').val();

    var advert_title = $('#title').val();
    var background = $('#advert_background').val();

    if (advert_title == '') {
        valid = false;
        swal({
                title: '', text: '홍보제목을 입력하세요',
                confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
            },
            function (isConfirm) {
            });
        return;
    }

    if (background == '') {
        valid = false;
        swal({
                title: '', text: '배경생을 설정하세요',
                confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
            },
            function (isConfirm) {
            });
        return;
    }
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();


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

        if (start_date >= end_date || start_date < new Date('Y-m-d')) {
            valid = false;
            swal({title: '', text: '날자범위를 정확히 지정하십시오.',
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                function(isConfirm) {

                });
            // open_my_modal('날자범위를 정확히 지정하십시오.');
        }



    var link_url = $('#link_url').val();
    if(link_url == "") {
        valid = false;
        swal({title: '', text: "연결주소를 입력하세요",
                confirmButtonText: '확인', allowOutsideClick: false, type: 'warning'},
            function(isConfirm) {});
        return;
    }
    if(valid) {
        $.ajax({
            url: site_url + "advert/save",
            cache:false,
            timeout : 10000,
            type: 'POST',
            data: {
                url: link_url,
                advert_id:advert_id,
                start_date:start_date,
                end_date:end_date,
                advert_title:advert_title,
                background:background
            },
            success: function(data) {
                if (data == 'err') {
                    swal({title: '', text: "전송실패!\n 다시 시도하세요!",
                            confirmButtonText: '확인', allowOutsideClick: false, type: 'error'},
                        function(isConfirm) {});
                } else{
                    swal({title: '', text: "성공적으로 보존되였습니다.",
                            confirmButtonText: '확인', allowOutsideClick: false, type: 'success'},
                        function(isConfirm) {});
                    $('#advert_id').val(data);
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

function advert_init() {
    $('#advert_id').val("");

    $('#title').val("");




    $('#start_date').val(new Date().toISOString().slice(0,10));
    $('#end_date').val(new Date().toISOString().slice(0,10));
    $('#link_url').val("");
    $('.receive_phone').val("");
}
