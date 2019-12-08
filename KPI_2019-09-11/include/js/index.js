function onSubMenuClick(key) {

}

/*function onMenuClick($key,$user_id) {
    if($user_id >0)
        location.href = site_url + $key;
    else{
        alert("사용자가입을 하셔야 합니다.");
    }
}*/

function onMoney_Introduce() {
    location.href = site_url + 'index/money_introduce';
}
function onOften_Qeustions() {
    location.href = site_url + 'index/faq';
}
function onNotice_Subject(){
    // location.href = site_url + 'index/notice_subject';
    location.href = site_url + 'index/board?bo_table=notice';
}
function onBoardView(){
    // location.href = site_url + 'index/board';
    location.href = site_url + 'index/board?bo_table=free';

}
function onDataView(){
    // location.href = site_url + 'index/data_view';
    location.href = site_url + 'index/board?bo_table=datamedia';
}
function new_board_write(){
    location.href = site_url + 'index/new_board_write';
}

var dup_send_flag = false;
function sample_send_func(){
    if (dup_send_flag == true) {
        alert('전송중입니다!!!');
        return false;
    }
    var tp = $('#myphone').attr('value');
    if (!chk_ph(tp)) {return false;}
    //휴대폰번호려파
    var mobileNumber = $('#myphone').val().replace(/-/g,'').replace(/\s/g, '');

    $.ajax({
        url: site_url + 'notice/send_sample',
        type: 'POST',
        data: {
            calling_number: '025852359' ,
            receive_mobile:mobileNumber,
        },
        error: function() {
            swal({
                title:  "", text: "전송이 실패하였습니다",
                confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
            }, function (isConfirm) {
            });
        },
        success: function(data) {
            if (data == "success") {
                swal({
                        title: '', text: '성공적으로 전송되었습니다.',
                        confirmButtonText: '확인', allowOutsideClick: false, type: 'success'
                    },
                    function (isConfirm) {
                    });
            }else {
                swal({
                    title:  '', text: '전송이 실패하였습니다.',
                    confirmButtonText: '확인', allowOutsideClick: false, type: 'error'
                }, function (isConfirm) {
                });
            }
        }
    });
}

function chk_ph(){
    var regExp = /^(01[016789]{1})-?[0-9]{3,4}-?[0-9]{4}$/;
    if(!$('#myphone').val()) {
        alert("핸드폰 번호를 입력하세요.");
        $('#myphone').focus();
        return false;
    }
    else if (!regExp.test($('#myphone').val())) {
        alert("핸드폰 번호형식이 올바르지 않습니다. 입력 예) 01X-XXXX-XXXX 또는 01XXXXXXXXX");
        $('#myphone').focus();
        $('#myphone').select();
        return false
    }
    return true;
}
$(function () {
    //
     /*$('#download_han').click(function () {
         $.ajax({
             url: site_url + 'index/download',
            type: 'POST',
             data: {
                 key: 'hwp'
             },
             error: function() {
                 alert('다운로드 실패')
             },
            success: function(data) {
                 if(data == "1") {
                     alert('성공');
                 }
                 }
        });
    });
     $('#download_word').click(function () {
         $.ajax({
             url: site_url + 'index/download',
             type: 'POST',
             data: {
                 key: 'word'
             },
             error: function() {
                 alert('다운로드 실패')
             },
             success: function(data) {

             }
         });
    //
     });*/
    //
    // $('#download_pdf').click(function () {
    //     $.ajax({
    //         url: site_url + 'index/download',
    //         type: 'POST',
    //         data: {
    //             key: 'pdf'
    //         },
    //         error: function() {
    //             alert('다운로드 실패')
    //         },
    //         success: function(data) {
    //
    //         }
    //     });
    // });
});